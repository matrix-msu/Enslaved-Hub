<?php
require_once(BASE_PATH . 'vendor/autoload.php');
require_once(BASE_PATH . 'functions/functions.php');
use Elasticsearch\ClientBuilder;

function get_type_counts() {
    $qi = new QueryIndex();
    $qi->setTypeCounts();
    echo json_encode($qi->getResults());
}

function get_date_range() {
    $qi = new QueryIndex();
    $qi->setDateRange();
    $res = $qi->getResults();
    return json_encode($res['aggregations']);
}

// formally filtered_counts
function get_field_counts() {
    $field = $type = '';
    if (isset($_GET['field']))
        $field = $_GET['field'];

    if (isset($_GET['type'])) {
        $type = strtolower($_GET['type']);
        if ($type != 'people')
            $type = substr_replace($type, '', -1);
    }

    $qi = new QueryIndex([
        'field' => $field,
        'type' => $type
    ]);
    $qi->setFilteredAggs();
    echo json_encode($qi->getResults());
}

function get_featured_records() {
    $type = $template = '';
    if (isset($_GET['templates'])) {
        $template = $_GET['templates'];

        if ($template == 'Person')
            $type = 'people';
        else
            $type = strtolower($template);
    }

    $qi = new QueryIndex([
        'type' => $type,
        'size' => 4
    ]);
    $qi->setFunctionScore();
    $res = $qi->getResults();
    return createCards($res['hits']['hits'], [$template], [], 'featured');
}

function get_search_filters() {
    $filters = [];
    if (isset($_GET['filters']))
        $filters = $_GET['filters'];

    $query = '';
    if (array_key_exists('searchbar', $filters)) {
        $query = $filters['searchbar'][0];
        unset($filters['searchbar']);
    }

    $types = '';
    if (isset($_GET['filter_types']))
        $types = $_GET['filter_types'];

    $type = '';
    if (isset($_GET['search_type'])) {
        $type = $_GET['search_type'];
        if ($type != 'people')
            $type = substr_replace($type, '', -1);
    }

    $qi = new QueryIndex([
        'type' => $type
    ]);

    if ($query)
        $qi->setQueryString($query);

    if ($filters)
        $qi->setBasicFilters($filters, $type);

    $qi->setCategorizedfilteredAggs($types);

    return json_encode($qi->getResults());
}

function get_keyword_search_results() {
    $select_fields = '';
    if(isset($_GET['fields'])){
        $select_fields = $_GET['fields'];
    }

    $preset = '';
    $get_all_counts = false;
    if (isset($_GET['preset'])) {
        $preset = $_GET['preset'];
        if ($preset == 'all' && isset($_GET['display'])) {
            $get_all_counts = true;
            $preset = $_GET['display'];
        }
    }

    $templates = '';
    if (isset($_GET['templates'])) {
        $templates = $_GET['templates'];
    }

    $filters = '';
    if (isset($_GET['filters'])) {
        $filters = $_GET['filters'];
    }

    $type = '';
    if (isset($_GET['display'])) {
        $type = $_GET['display'];
        if ($type != 'people')
            $type = substr_replace($type, '', -1);
    }

    $sort_field = '';
    if (isset($_GET['sort_field'])) {
        $sort_field = $_GET['sort_field'];
    }

    $from = '';
    if (isset($_GET['offset'])) {
        $from = $_GET['offset'];
    }

    $size = '';
    if (isset($_GET['limit'])) {
        $size = $_GET['limit'];
    }

    $sort = '';
    if (isset($_GET['sort'])) {
        $sort = $_GET['sort'];
    }

    $qi = new QueryIndex([
        'size' => $size,
        'from' => $from
    ]);

    if ($sort_field && $sort) {
        $qi->setSort($sort_field, $sort);
    }

    if (array_key_exists('name', $filters)) {
        $qi->setMatchQuery('name', $filters['name'][0]);
        unset($filters['name']);
    } else if (array_key_exists('place_name', $filters)) {
        $qi->setTermQuery('label', $filters['place_name'][0]);
        unset($filters['place_name']);
    } else if (array_key_exists('searchbar', $filters)) {
        $qi->setQueryString($filters['searchbar'][0]);
        unset($filters['searchbar']);
    }

    if ($filters)
        $qi->setBasicFilters($filters, $type);

    // NOTE::cannot get all type counts with type filter
    // applied by default, applying type after avoids this.
    $qit = clone $qi;
    $qit->setTypeCounts();
    $qit->params['size'] = 0;
    $res = $qit->getResults();
    $total = $res['aggregations'];

    $qi->setType($type);
    $res = $qi->getResults();

    return @createCards($res['hits']['hits'], $templates, $select_fields, $preset, $total);
}

class QueryIndex {
    public $params = [
        'index' => ELASTICSEARCH_INDEX_NAME,
        'body' => [
            'query' => [
                'bool' => [
                    'must' => [],
                    'filter' => []
                ]
            ],
            'aggs' => [],
            'size' => 0,
            'track_total_hits' => true
        ]
    ];

    private static $categorizedEHFieldsToES = [
        'people' => [
            'Gender' => 'sex',
            'Age Category' => 'age_category',
            'Ethnodescriptor' => 'ethnodescriptor',
            'Role Types' => 'participant_role',
            'Status' => 'person_status',
            'Occupation' => 'occupation'
        ],
        'event' => [
            'Event Type' => 'event_type'
        ],
        'place' => [
            'Place Type' => 'place_type',
            'Modern Countries' => 'modern_country_code'
        ],
        'source' => [
            'Source Type' => 'source_type'
        ],
        'project' => [
            'Projects' => 'generated_by'
        ]
    ];

    private static $EHFieldsToES = [
        'Gender' => 'sex',
        'Age Category' => 'age_category',
        'Ethnodescriptor' => 'ethnodescriptor',
        'Role Types' => 'participant_role',
        'Status' => 'person_status',
        'Occupation' => 'occupation',
        'Event Type' => 'event_type',
        'Place Type' => 'place_type',
        'Modern Countries' => 'modern_country_code',
        'Source Type' => 'source_type',
        'Projects' => 'generated_by'
    ];

    private static $convertFilters = [
        'gender' => 'sex',
        'role_types' => 'participant_role',
        'status' => 'person_status',
        'event-type' => 'event_type',
        'projects' => 'generated_by',
        'modern_countries' => 'modern_country_code'
    ];

    public function __construct(array $arguments = array()) {
        $this->es = ClientBuilder::create()
            ->setHosts([ELASTICSEARCH_URL])
            ->build();

        $this->params['body']['query']['bool']['must'] = [
            'match_all' => new \stdClass()
        ];

        if (!empty($arguments)) {
            foreach ($arguments as $property => $argument) {
                $this->{$property} = $argument;
            }
        }

        if (property_exists($this, 'type')) {
            $this->params['body']['query']['bool']['filter'] = [
                ['term' => ['type' => $this->type]]
            ];
        }

        foreach (['size', 'from'] as $property) {
            if (property_exists($this, $property)) {
                $this->params['body'][$property] = $this->$property;
            }
        }
    }

    private function setSingleAggs($label, $field, $type = '') {
        if ($label == 'Gender') {
            $this->params['body']['aggs']['No Sex Recorded'] = [
                'missing' => [
                    'field' => $field . '.raw'
                ]
            ];
        }

        $this->params['body']['aggs'][$label] = [
            'terms' => [
                'size' => 1000,
                'field' => $field . '.raw'
            ],
            'meta' => [
                'type' => $type
            ]
        ];
    }

    private function setTermAsBody($field, $value) {
        $this->params['body']['query'] = [
            'term' => [
                $field => $value
            ]
        ];
    }

    public function setType($type) {
        array_push(
            $this->params['body']['query']['bool']['filter'],
            ['term' => ['type' => $type]]
        );
    }

    public function setQueryString($text) {
        $this->params['body']['query']['bool']['must'] = [
            'query_string' => [
                'fields' => [
                    'label',
                    'generated_by',
                    'source_type',
                    'source_repository',
                    'place_type',
                    'modern_country_code',
                    'located_in',
                    'event_type',
                    'provides_participant_role',
                    'name^5',
                    'age',
                    'occupation',
                    'descriptive_occupation',
                    'race',
                    'sex',
                    'person_status',
                    'relationships',
                    'ethnodescriptor',
                    'ethnonym_variant',
                    'participant_role',
                    'date',
                    'end_date',
                    'age_category'
                ],
                'lenient' => true,
                'default_operator' => 'AND',
                'query' => $text
            ]
        ];
    }

    public function setSort($field, $order) {
        $this->params['body']['sort'] = [
            $field => ['order' => $order]
        ];
    }

    // public function __call($method, $arguments) {
    //     $arguments = array_merge(array("stdObject" => $this), $arguments); // Note: method argument 0 will always referred to the main class ($this).
    //     if (isset($this->{$method}) && is_callable($this->{$method})) {
    //         return call_user_func_array($this->{$method}, $arguments);
    //     } else {
    //         throw new Exception("Fatal error: Call to undefined method stdObject::{$method}()");
    //     }
    // }

    public function setDateRange() {
        $this->params['body'] = [
            'size' => 0,
            'aggs' => [
                'max_date' => ['max' => ['field' => 'date', 'format' => 'yyyy']],
                'min_date' => ['min' => ['field' => 'date', 'format' => 'yyyy']],
                'max_end_date' => ['max' => ['field' => 'end_date', 'format' => 'yyyy']],
                'min_end_date' => ['min' => ['field' => 'end_date', 'format' => 'yyyy']]
            ]
        ];
    }

    public function setTypeCounts() {
        $this->params['body']['aggs'] = [
            'type' => [
                'terms' => [
                    'field' => 'type'
                ]
            ]
        ];
    }

    public function setMatchQuery($field, $value) {
        $this->params['body']['query']['bool']['must'] = [
            'match' => [
                $field => [
                    'query' => $value
                ]
            ]
        ];
    }

    public function setTermQuery($field, $value) {
        $this->params['body']['query']['bool']['must'] = [
            'term' => [
                $field => $value
            ]
        ];
    }

    public function setFilteredAggs() {
        if (property_exists($this, 'field')) {
            $this->setSingleAggs($this->field, self::$EHFieldsToES[$this->field]);
        } else {
            foreach (self::$EHFieldsToES as $label => $field) {
                $this->setSingleAggs($label, $field);
            }
        }
    }

    public function setCategorizedfilteredAggs($types) {
        foreach ($types as $type) {
            foreach (self::$categorizedEHFieldsToES[$type] as $label => $field) {
                $this->setSingleAggs($label, $field, $type);
            }
        }
    }

    public function setBasicFilters($filters, $type) {
        foreach ($filters as $key => $value) {
            if (in_array($key, array_keys(self::$convertFilters))) {
                $key = self::$convertFilters[$key];
            }

            if (in_array($key, ['person', 'event', 'place', 'source'])) {
                $qi = new QueryIndex(['size' => 1]);
                $qi->setTermAsBody('id', $value[0]);
                $res = $qi->getResults();
                if (count($res['hits']['hits']) > 0) {
                    $ref_field = 'ref_' . $type;
                    $document = $res['hits']['hits'][0]['_source'];
                    if (array_key_exists($ref_field, $document)) {
                        array_push(
                            $this->params['body']['query']['bool']['filter'],
                            [
                                'terms' => [
                                    'id' => $document[$ref_field]
                                ]
                            ]
                        );
                    }
                }
            } else if ($key == 'date' | $key == 'age') {
                $rf = new RangeFilter($value[0], $this);
                if ($key == 'date')
                    $rf->setDateRangeFilter();
                else
                    $rf->setAgeRangeFilter();
            } else if ($key == 'modern_country_code') {
                $codes = [];
                foreach ($value as $country) {
                    array_push($codes, reversecountrycode[$country]);
                }
                array_push(
                    $this->params['body']['query']['bool']['filter'],
                    ['terms' => ['modern_country_code.raw' => $codes]]
                );
            } else if (in_array('No Sex Recorded', $value)) {
                $should = [
                    'bool' => [
                        'should' => [
                            [
                                'bool' => [
                                    'must_not' => [
                                        'exists' => [
                                            'field' => $key
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
                // Finding the extra genders to add to the filter.
                $value = array_diff($value, ['No Sex Recorded']);
                if ($value) {
                    // NOTE::Elasticsearch will freak out if array values aren't reset.
                    // Using array_values() to avoid this.
                    array_push(
                        $should['bool']['should'],
                        ['terms' => [$key . '.raw' => array_values($value)]]
                    );
                }
                array_push(
                    $this->params['body']['query']['bool']['filter'],
                    $should
                );
            } else {
                array_push(
                    $this->params['body']['query']['bool']['filter'],
                    ['terms' => [$key . '.raw' => $value]]
                );
            }
        }
    }

    public function setFunctionScore() {
        $exist_fields = [
            ['exists' => ['field' => 'date']],
            ['exists' => ['field' => 'place']]
        ];

        $append_fields = [
            ['exists' => ['field' => 'event_type']]
        ];

        if ($this->type == 'people') {
            $append_fields = [
                ['exists' => ['field' => 'name']],
                ['exists' => ['field' => 'person_status']],
            ];
        }

        $this->params['body'] = [
            'query' => [
                'function_score' => [
                    'query' => [
                        'bool' => [
                            'must' => array_merge($exist_fields, $append_fields),
                            'filter' => [
                                ['term' => ['type' => $this->type]]
                            ]
                        ]
                    ],
                    'random_score' => new \stdClass()
                ]
            ],
            'size' => $this->size
        ];
    }

    public function getResults() {
        if (empty($this->params['body']['aggs'])) {
            unset($this->params['body']['aggs']);
        }

        return $this->es->search($this->params);
    }
}

class RangeFilter extends QueryIndex {
    protected function __construct($range_str, $parent) {
        $this->_parent = $parent;
        $this->range = explode('-', $range_str);
    }

    protected function setDateRangeFilter() {
        // Note: Will have to update format
        // once more exact dates get indexed.

        $gte_date = $this->range[0] . '||/y';
        $lte_date = $this->range[1] . '||/y';

        array_push(
            $this->_parent->params['body']['query']['bool']['filter'],
            [
                'bool' => [
                    'should' => [
                        ['range' => [
                            'date' => [
                                'gte' => $gte_date,
                                'lte' => $lte_date,
                                'format' => 'yyyy']
                                ]
                            ],
                        ['range' => [
                            'circa' => [
                                'gte' => $gte_date,
                                'lte' => $lte_date,
                                'format' => 'yyyy'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );
    }

    protected function setAgeRangeFilter() {
        array_push(
            $this->_parent->params['body']['query']['bool']['filter'],
            [
                'range' => [
                    'age' => [
                        'gte' => $this->range[0],
                        'lte' => $this->range[1]
                    ]
                ]
            ]
        );
    }
}

?>
