<?php
require_once(BASE_PATH . 'vendor/autoload.php');
require_once(BASE_PATH . 'functions/functions.php');
use Elasticsearch\ClientBuilder;


function set_text_query($filters, $qi) {
    foreach (['name' => 'name', 'place_name' => 'place'] as $key => $field) {
        if (array_key_exists($key, $filters)) {
            $str = preg_replace("/[^A-Za-z0-9. ]/", '', $filters[$key][0]);
            $qi->setMatchQuery($field, $str);
            unset($filters[$key]);
        }
    }

    if (array_key_exists('searchbar', $filters)) {
        $str = preg_replace('/\PL/u', '', $filters['searchbar']);
        $qi->setQueryString($str);
        unset($filters['searchbar']);
    }

    return array($filters, $qi);
}

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
        'type' => $type
    ]);

    if ($field)
        $qi->setSingleFieldAggs($field);
    else
        $qi->setAllFieldAggs();

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

    list ($filters, $qi) = set_text_query($filters, $qi);

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

    list ($filters, $qi) = set_text_query($filters, $qi);

    if ($filters)
        $qi->setBasicFilters($filters, $type);

    // NOTE::cannot get all type counts with type filter
    // applied by default, applying type after avoids this.
    $qit = clone $qi;
    $qit->setTypeCounts();
    $qit->params['size'] = 0;
    $res = $qit->getResults();
    $total = $res['aggregations'];

    $qi->addTypeFilter($type);
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

    /**
     * QueryIndex constructor
     * @param array $arguments key/value pairs that are tranformed into class properties
     *
     * @return void
     */
    public function __construct(array $arguments = array()) {
        $this->es = ClientBuilder::create()
            ->setHosts([ELASTICSEARCH_URL])
            ->build();

        $this->params['body']['query']['bool']['must'] = [
            'match_all' => new \stdClass()
        ];

        // Used to manage potential multiple full text query scenario
        // Any new must queries will have to append to this array.
        $this->should = [];

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

    /**
     * Single Aggregation Setter
     *
     * Sets a single term aggregation to params property.
     *
     * @param string $label front-end field name
     * @param string $field index (es) field name
     * @param string $type instance of type, used for front-end categorization
     *
     * @return void
     */
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

    /**
     * Term Query
     *
     * Sets the query body to a term search, used for ref or id search.
     *
     * @param string $field index (es) field name
     * @param string $value q number or id
     *
     * @return void
     */
    private function setTermAsBody($field, $value) {
        $this->params['body']['query'] = [
            'term' => [
                $field => $value
            ]
        ];
    }

    /**
     * Type Filter
     *
     * Appends a type filter to the bool query structure.
     *
     * @param string $type instance of type
     *
     * @return void
     */
    public function addTypeFilter($type) {
        array_push(
            $this->params['body']['query']['bool']['filter'],
            ['term' => ['type' => $type]]
        );
    }

    /**
     * Query String Query
     *
     * Sets the query body to a query string query.
     * Appends query to should property to support multiple full-text queries.
     *
     * @param string $text word or phrase to be searched over
     *
     * @return void
     */
    public function setQueryString($text) {
        $query = [
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
                    'descriptive_role',
		            'place_of_origin',
                    'race',
                    'sex',
                    'person_status',
                    'relationships',
                    'ethnodescriptor',
                    'ethnonym_variant',
                    'participant_role',
                    'date',
                    'end_date',
                    'age_category',
                    'has_description'
                ],
                'lenient' => true,
                'default_operator' => 'AND',
                'query' => $text
            ]
        ];
        $this->params['body']['query']['bool']['must'] = $query;
        array_push($this->should, $query);
    }

    /**
     * Sort
     *
     * Sets the result set order by field
     *
     * @param string $field index (es) field name
     * @param string $order order - asc or desc
     *
     * @return void
     */
    public function setSort($field, $order) {
        $this->params['body']['sort'] = [
            $field => ['order' => $order]
        ];
    }

    /**
     * Date Range Aggregation
     *
     * Sets the whole search body into a date range aggregation.
     * Finds the earliest and latest year for date and end_date field.
     * NOTE: this function should not be used in combination with other functions
     * in this class since it overwrites the whole body.
     *
     * @return void
     */
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

    /**
     * Type Count
     *
     * Sets the aggregation to find the number of records per type.
     * NOTE: this function should not be used in combination with other functions
     * that changes aggregations since this function
     * overwrites the whole aggregation value.
     *
     * @return void
     */
    public function setTypeCounts() {
        $this->params['body']['aggs'] = [
            'type' => [
                'terms' => [
                    'field' => 'type'
                ]
            ]
        ];
    }

    /**
     * Match Query
     *
     * Sets the query body to a match query.
     * Appends query to should property to support multiple full-text queries.
     *
     * @param string $field index (es) field name
     * @param string $text word or phrase to be searched over
     *
     * @return void
     */
    public function setMatchQuery($field, $text) {
        $query = [
            'match' => [
                $field => [
                    'query' => $text
                ]
            ]
        ];
        $this->params['body']['query']['bool']['must'] = $query;
        array_push($this->should, $query);
    }

    /**
     * Single Field Aggregation
     *
     * Sets a single aggregation for a field.
     * Using a front to index field translation constant.
     *
     * @param string $field index (es) field name
     *
     * @return void
     */
    public function setSingleFieldAggs($field) {
        $this->setSingleAggs($field, self::$EHFieldsToES[$field]);
    }

    /**
     * All Fields Aggregation
     *
     * Generate aggregations for all fields in translation constant.
     *
     * @return void
     */
    public function setAllFieldAggs() {
        foreach (self::$EHFieldsToES as $label => $field) {
            $this->setSingleAggs($label, $field);
        }
    }

    /**
     * All Fields Aggregation
     *
     * Generate aggregations per type in categorized translation constant.
     *
     * @param string $type instance of type
     *
     * @return void
     */
    public function setCategorizedfilteredAggs($types) {
        foreach ($types as $type) {
            foreach (self::$categorizedEHFieldsToES[$type] as $label => $field) {
                $this->setSingleAggs($label, $field, $type);
            }
        }
    }

    /**
     * Reference Field Filter
     *
     * Generate aggregations per type in categorized translation constant.
     *
     * @param string $type instance of type
     * @param string $value q number
     *
     * @return void
     */
    private function filterByRef($type, $id) {
        $qi = new QueryIndex(['size' => 1]);
        $qi->setTermAsBody('id', $id);
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
    }

    public function setBasicFilters($filters, $type) {
        foreach ($filters as $key => $value) {
            if (in_array($key, array_keys(self::$convertFilters))) {
                $key = self::$convertFilters[$key];
            }

            if (in_array($key, ['person', 'event', 'place', 'source'])) {
                $this->filterByRef($type, $value[0]);
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

        if (count($this->should) > 1) {
            $this->params['body']['query']['bool']['must'] = [
                'bool' => [
                    'should' => $this->should
                ]
            ];
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
