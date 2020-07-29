<?php
if(isset($argv)){
    require_once("./config.php");
    // echo getcwd();die;
    // echo json_encode($argv[1]);
    $_GET = json_decode($argv[1],true);
}
require_once(BASE_PATH . 'vendor/autoload.php');
require_once(BASE_PATH . 'functions/functions.php');
use Elasticsearch\ClientBuilder;

if(isset($argv)){
    keyword_search();
}

function all_counts_ajax() {
    echo json_encode(all_counts());
}

function dateRange() {
    // NOTE: The results here are wildly different depending on
    // versions of elasticsearch, 7.5+ look at value as string
    // for 7.4 and lower look at value.
    $hosts = [
        ELASTICSEARCH_URL
    ];

    $es = ClientBuilder::create()
                        ->setHosts($hosts)
                        ->build();

    $params = [
        'index' => ELASTICSEARCH_INDEX_NAME,
        'body' => [
            'size' => 0,
            'aggs' => [
                'max_date' => ['max' => ['field' => 'date', 'format' => 'yyyy']],
                'min_date' => ['min' => ['field' => 'date', 'format' => 'yyyy']],
                'max_end_date' => ['max' => ['field' => 'end_date', 'format' => 'yyyy']],
                'min_end_date' => ['min' => ['field' => 'end_date', 'format' => 'yyyy']]
            ]
        ]
    ];

    $res = $es->search($params);
    return json_encode($res['aggregations']);
}

function all_counts() {
    $hosts = [
        ELASTICSEARCH_URL
    ];

    $es = ClientBuilder::create()
                        ->setHosts($hosts)
                        ->build();

    $params = [
        'index' => ELASTICSEARCH_INDEX_NAME,
        'body' => [
            'query' => [
                'bool' => [
                    'must' => [
                        'match_all' => new \stdClass()
                    ],
                    'filter' => []
                ]
            ]
        ]
    ];

    $res = $es->count($params);
    $total = [];
    $total['all'] = $res['count'];

    foreach (['people', 'event', 'place', 'source'] as $type) {
        $tmp_params = $params;
        array_push(
            $tmp_params['body']['query']['bool']['filter'],
            ['term' => ['type' => $type]]
        );
        $res = $es->count($tmp_params);
        // TODO::this is annoying, will require a refactor on index (pluralize types)
        if ($type != 'people')
            $type = $type . 's';
        $total[$type] = $res['count'];
    }

    return $total;
}

function search_filter_counts() {
    $hosts = [
        ELASTICSEARCH_URL
    ];

    $es = ClientBuilder::create()
                        ->setHosts($hosts)
                        ->build();

    $filters = [];
    if (isset($_GET['filters'])) {
        $filters = $_GET['filters'];
    }

    $filter_types = '';
    if (isset($_GET['filter_types'])){
        $filter_types = $_GET['filter_types'];
    }

    $search_type = '';
    if (isset($_GET['search_type'])){
        $search_type = $_GET['search_type'];
    }

    if (array_key_exists('searchbar', $filters)) {
        $query = implode(' AND ', $filters['searchbar']);
        unset($filters['searchbar']);
    }

    if ($search_type != 'people')
        $search_type = substr_replace($search_type, '', -1);

    $filters = [
        'people' => [
            'Gender' => [
                'sex' => sexTypes
            ],
            'Age Category' => [
                'age_category' => ageCategory
            ],
            'Ethnodescriptor' => [
                'ethnodescriptor' => ethnodescriptor
            ],
            'Role Types' => [
                'participant_role' => roleTypes
            ],
            'Status' => [
                'person_status' => personstatus
            ],
            'Occupation' => [
                'occupation' => occupation
            ]
        ],
        'event' => [
            'Event Type' => [
                'event_type' => eventTypes
            ]
        ],
        'place' => [
            'Place Type' => [
                'place_type' => placeTypes
            ],
            'Modern Countries' => [
                'modern_country_code' => countrycode
            ]
        ],
        'source' => [
            'Source Type' => [
                'source_type' => sourceTypes
            ]
        ],
        'project' => [
            'Projects' => [
                'generated_by' => projects
            ]
        ]
    ];

    $total = [];

    foreach ($filters as $type => $labels) {
        if (in_array($type, $filter_types)) {
            $params = [
                'index' => ELASTICSEARCH_INDEX_NAME,
                'body' => [
                    'query' => [
                        'bool' => [
                            'must' => [],
                            'filter' => [
                                ['term' => ['type' => $search_type]]
                            ]
                        ]
                    ]
                ]
            ];

            if ($query) {
                $params['body']['query']['bool']['must'] = [
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
                            'race',
                            'sex',
                            'person_status',
                            'relationships',
                            'ethnodescriptor',
                            'participant_role',
                            'date',
                            'end_date',
                            'age_category'
                        ],
                        'lenient' => true,
                        'query' => $query
                    ]
                ];
            } else {
                $params['body']['query']['bool']['must'] = [
                    'match_all' => new \stdClass()
                ];
            }

            $total[$type] = [];

            foreach ($labels as $label => $fields) {
                $total[$type][$label] = [];
                foreach ($fields as $field => $values) {
                    foreach (array_keys($values) as $value) {
                        $tmp_params = $params;

                        if ($value == 'No Sex Recorded') {
                            $tmp_params['body']['query']['bool']['must_not'] = ['exists' => ['field' => $field]];
                        } else {
                            array_push(
                                $tmp_params['body']['query']['bool']['filter'],
                                ['term' => [$field . '.raw' => $value]]
                            );
                        }

                        $res = $es->count($tmp_params);

                        $total[$type][$label][$value] = $res['count'];
                    }
                }
            }
        }
    }

    return json_encode($total);
}

function filtered_counts() {
    $hosts = [
        ELASTICSEARCH_URL
    ];

    $es = ClientBuilder::create()
                        ->setHosts($hosts)
                        ->build();

    $raw_field = '';
    if (isset($_GET['type'])){
        $raw_field = $_GET['type'];
    }

    $category = '';
    if (isset($_GET['category'])){
        $category = $_GET['category'];
    }

    $field_translate = [
        'Event Type' => [
            'event_type' => eventTypes
        ],
        'Date' => [
            'date' => ''
        ],
        'Gender' => [
            'sex' => sexTypes
        ],
        'Role Types' => [
            'participant_role' => roleTypes
        ],
        'Age Category' => [
            'age_category' => ageCategory
        ],
        'Ethnodescriptor' => [
            'ethnodescriptor' => ethnodescriptor
        ],
        'Place Type' => [
            'place_type' => placeTypes
        ],
        'Source Type' => [
            'source_type' => sourceTypes
        ]
    ];

    if (
        !in_array($category, ['Events', 'People', 'Places', 'Sources']) |
        !array_key_exists($raw_field, $field_translate)
    )
        die;

    $category = strtolower($category);

    if ($category != 'people')
        $category = substr_replace($category, '', -1);

    $params = [
        'index' => ELASTICSEARCH_INDEX_NAME,
        'body' => [
            'query' => [
                'bool' => [
                    'must' => [
                        'match_all' => new \stdClass()
                    ],
                    'filter' => [
                        ['term' => ['type' => $category]]
                    ]
                ]
            ]
        ]
    ];

    $total = [];

    foreach ($field_translate[$raw_field] as $field => $values) {
        foreach (array_keys($values) as $value) {
            $tmp_params = $params;

            if ($value == 'No Sex Recorded') {
                $tmp_params['body']['query']['bool']['must_not'] = ['exists' => ['field' => $field]];
            } else {
                array_push(
                    $tmp_params['body']['query']['bool']['filter'],
                    ['term' => [$field . '.raw' => $value]]
                );
            }

            $res = $es->count($tmp_params);

            $total[$value] = $res['count'];
        }
    }

    return json_encode($total);
}

function keyword_search() {
    $hosts = [
        ELASTICSEARCH_URL
    ];

    // echo ELASTICSEARCH_URL;die;
    $es = ClientBuilder::create()
                        ->setHosts($hosts)
                        ->build();

    $item_types = ['person', 'event', 'place', 'source'];
    $filters = $templates = [];
    $query = $preset = $item_type = $sort = '';
    $size = 12;
    $from = 0;
    $get_all_counts = false;
    if(isset($_GET['fields'])){
        $select_fields = $_GET['fields'];
    }

    $convert_filters = [
        'gender' => 'sex',
        'role_types' => 'participant_role',
        'status' => 'person_status',
        'event-type' => 'event_type',
        'projects' => 'generated_by',
        'modern_countries' => 'modern_country_code'
    ];

    if (isset($_GET['column'])) {
        $columns = $_GET['column'];
    }

    if (isset($_GET['preset'])) {
        $preset = $_GET['preset'];
    }

    if ($preset == 'all' && isset($_GET['display'])) {
        $get_all_counts = true;
        $preset = $_GET['display'];
    }

    if (isset($_GET['templates'])) {
        $templates = $_GET['templates'];
    }

    if (isset($_GET['filters'])) {
        $filters = $_GET['filters'];
    }

    if (isset($_GET['display'])) {
        $filters['type'] = $_GET['display'];
    }

    if (array_key_exists('searchbar', $filters)) {
        $query = implode(' AND ', $filters['searchbar']);
        unset($filters['searchbar']);
    }

    if (array_key_exists('offset', $filters)) {
        $from = $filters['offset'];
        unset($filters['offset']);
    }

    if (array_key_exists('limit', $filters)) {
        $size = $filters['limit'];
        unset($filters['limit']);
    }

    if (array_key_exists('sort', $filters)) {
        $sort = $filters['sort'];
        unset($filters['sort']);
    }

    $params = [
        'index' => ELASTICSEARCH_INDEX_NAME,
        'body' => [
            'query' => [
                'bool' => [
                    'must' => []
                ]
            ],
            'size' => $size,
            'from' => $from,
            'track_total_hits' => TRUE
        ]
    ];

    if ($query) {
        $params['body']['query']['bool']['must'] = [
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
                    'race',
                    'sex',
                    'person_status',
                    'relationships',
                    'ethnodescriptor',
                    'participant_role',
                    'date',
                    'end_date',
                    'age_category'
                ],
                'lenient' => true,
                'query' => $query
            ]
        ];
    } else {
        $params['body']['query']['bool']['must'] = [
            'match_all' => new \stdClass()
        ];
    }

    if (array_key_exists('name', $filters)) {
        $params['body']['query']['bool']['must'] = [
            'match' => [
                'name' => [
                    'query' => $filters['name'][0]
                ]
            ]
        ];
        unset($filters['name']);
    } elseif (array_key_exists('place_name', $filters)) {
        $params['body']['query']['bool']['must'] = [
            'term' => [
                'label' => $filters['place_name'][0]
            ]
        ];
        unset($filters['place_name']);
    }

    if ($sort) {
        $sort_field = $_GET['sort_field'];
        $params['body']['sort'] = [
            $sort_field => ['order' => $sort]
        ];
    }

    if ($filters) {
        $terms = [];
        foreach ($filters as $key => $value) {
            if (in_array($key, array_keys($convert_filters)))
                $key = $convert_filters[$key];

            if ($key == 'type') {
                // TODO::this is annoying, will require a refactor on index (pluralize types)
                if ($value != 'people')
                    $value = substr_replace($value, '', -1);
                // used for all preset to determine base type
                $item_type = $value;
                array_push($terms, ['term' => [$key => $value]]);
                break;
            }

            // for filter by ref item id
            if (in_array($key, $item_types)) {
                $ref_type = $_GET['display'];
                if ($ref_type != 'people')
                    $ref_type = substr_replace($ref_type, '', -1);

                // Have to do a search here in order to get the exact refs found in
                // the full record
                $res = $es->search([
                    'index' => ELASTICSEARCH_INDEX_NAME,
                    'body' => ['query' => ['term' => ['id' => $value[0]]], 'size' => 1]
                ]);

                // TODO::if erroring out here may want to check may want to check if hits has any results
                array_push($terms, ['terms' => ['id' => $res['hits']['hits'][0]['_source'][ref_ . $ref_type]]]);
                break;
            }

            if ($key == 'date' | $key == 'age') {
                $values = explode('-', $value[0]);
                $range_filter = [];
                if ($key == 'date') {
                    $gte_date = $values[0] . '||/y';
                    $lte_date = $values[1] . '||/y';
                    // Note: Will have to update format
                    // once more exact dates get indexed.

                    $range_filter = [
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
                    ];
                } else {
                    $range_filter = [
                        'range' => [
                            $key => [
                                'gte' => $values[0],
                                'lte' => $values[1]
                            ]
                        ]
                    ];
                }
                array_push($terms, $range_filter);
            } else if ($key == 'modern_country_code') {
                $codes = [];
                foreach ($value as $country) {
                    array_push($codes, reversecountrycode[$country]);
                }
                array_push($terms, ['terms' => ['modern_country_code.raw' => $codes]]);
            } else {
                // In order to filter a does not exist query alongside term filters of the
                // same field, you must do this insanely nested bullshit.
                if (in_array('No Sex Recorded', $value)) {
                    $should = ['bool' => ['should' => array(['bool' => ['must_not' => ['exists' => ['field' => $key]]]])]];
                    $value = array_diff($value, ['No Sex Recorded']);
                    if ($value) {
                        // Elasticsearch will freak out if array values aren't reset.
                        array_push($should['bool']['should'], ['terms' => [$key . '.raw' => array_values($value)]]);
                    }
                    array_push($terms, $should);
                } else {
                    array_push($terms, ['terms' => [$key . '.raw' => $value]]);
                }
            }
        }
        $params['body']['query']['bool']['filter'] = $terms;
    }
    // $params['body']['size'] = "25000";
// echo ini_get('error_log');die;
    // $chunkFrom = 10000;
    // $chunkLimit = 10000;
    $chunkTotal = intval($params['body']['size']);
    if($chunkTotal >= 10000){
        // $params['body']['from'] = "0";
        // $params['body']['size'] = "100";
        ini_set("memory_limit", "-1");
        set_time_limit(0);
    }
    // if(isset($_GET['createCSV'])){
    //     $params['body']['size'] = "1000";
    // }
    $res = $es->search($params);
    // echo json_encode($res["hits"]["hits"]);die;
    $single_total = $res['hits']['total']['value'];

    if ($get_all_counts && $item_type) {
        $total = [];
        unset($params['body']['from']);
        $params['body']['size'] = 0;
        foreach ($item_types as $type) {
            // TODO::this is annoying, will require a refactor on index (pluralize types)
            if ($type == 'person') {
                $type = 'people';
                $count_key = $type . 'count';
            } else
                $count_key = $type . 'scount';

            if ($type == $item_type)
                // TODO::refactor front end to ignore pointless value key
                $total[$count_key]['value'] = $single_total;
            else {
                foreach ($params['body']['query']['bool']['filter'] as $key => $value) {
                    if (array_key_exists('term', $value) && array_key_exists('type', $value['term'])) {
                        $params['body']['query']['bool']['filter'][$key]['term']['type'] = $type;
                        break;
                    }
                }
                $count_res = $es->search($params);
                // TODO::refactor front end to ignore pointless value key
                $total[$count_key]['value'] = $count_res['hits']['total']['value'];
            }
        }
    } else {
        $total = $single_total;
    }

    $formattedData = @createCards($res['hits']['hits'], $templates, $select_fields, $preset, $total);

    if(isset($_GET['createCSV'])){
        $downloadFields = $_GET['downloadFields'];
        $csv = 'QID,'.implode($downloadFields,",")."\n";
        $formattedData = json_decode($formattedData,true);
        foreach($formattedData['formatted_data'] as $qid => $row){
            $csv .= $qid.",";
            foreach($downloadFields as $name){
                $csv .= '"'.$row[$name].'",';
            }
            $csv = substr($csv, 0, -1)."\n";
        }
        file_put_contents($_GET['csv_name'],$csv);
        rename($_GET['csv_name'], $_GET['csv_name'].'.csv');
        die;
    }
    return $formattedData;
}

function get_columns() {
    // TODO::Not sure why this has to be an ajax call when
    // it's just getting some arrays. Can move directly to js.
    $type = isset($_GET['type']) ? $_GET['type'] : 'people';

    $columns = [
        'people' => [
            'name' => 'Name',
            'sex' => 'Sex',
            'person_status' => 'Person Status',
            'participant_role' => 'Role',
            'event_type' => 'Event',
            'date' => 'Date',
            'place_type' => 'Place Type',
            'display_place' => 'Place',
            'source_type' => 'Source Type',
            'ethnodescriptor' => 'Ethnodescriptor',
            'occupation' => 'Occupation'
        ],
        'places' => [
            'label' => 'Name',
            'generated_by' => 'Project',
            'located_in' => 'Location',
            'place_type' => 'Place Type'
        ],
        'events' => [
            'name' => 'Name',
            'event_type' => 'Event Type',
            'source_type' => 'Source Type',
            'display_date_range' => 'Date',
            'place_type' => 'Place Type',
            'display_place' => 'Place'
        ],
        'sources' => [
            'label' => 'Name',
            'generated_by' => 'Project',
            'type' => 'Source Type'
        ]
    ];
    echo json_encode($columns[$type]);
}

function featured_items() {
    $hosts = [
        ELASTICSEARCH_URL
    ];

    $es = ClientBuilder::create()
                        ->setHosts($hosts)
                        ->build();

    $preset = 'featured';

    if (isset($_GET['templates'])) {
        $template = $_GET['templates'];
    }

    if ($template == 'Person')
        $type = 'people';
    else
        $type = strtolower($template);

    if ($type == 'people') {
        $must = [
            ['exists' => ['field' => 'name']],
            ['exists' => ['field' => 'person_status']],
            ['exists' => ['field' => 'display_date_range']],
            ['exists' => ['field' => 'display_place']]
        ];
    } else {
        $must = [
            ['exists' => ['field' => 'event_type']],
            ['exists' => ['field' => 'date']],
            ['exists' => ['field' => 'display_place']]
        ];
    }


    $params = [
        'index' => ELASTICSEARCH_INDEX_NAME,
        'body' => [
            'query' => [
                'function_score' => [
                    'query' => [
                        'bool' => [
                            'must' => $must,
                            'filter' => [
                                ['term' => ['type' => $type]]
                            ]
                        ]
                    ],
                    'random_score' => new \stdClass()
                ]
            ],
            'size' => 4
        ]
    ];

    $res = $es->search($params);
    $select_fields = array();
    return createCards($res['hits']['hits'], [$template], $select_fields ,$preset);
}

?>
