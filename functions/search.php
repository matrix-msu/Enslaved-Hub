<?php
require_once(BASE_PATH . 'vendor/autoload.php');
require_once(BASE_PATH . 'functions/functions.php');
use Elasticsearch\ClientBuilder;

function all_counts_ajax() {
    echo json_encode(all_counts());
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
            ],
            'collapse' => [
                'field' => 'id'
            ],
            'size' => 0,
            'aggs' => [
                'total' => [
                    'cardinality' => [
                        'field' => 'id'
                    ]
                ]
            ]
        ]
    ];

    $res = $es->search($params);
    $total = [];
    $total['all'] = $res['aggregations']['total']['value'];

    foreach (['people', 'event', 'place', 'source'] as $type) {
        $tmp_params = $params;
        array_push(
            $tmp_params['body']['query']['bool']['filter'],
            ['term' => ['type' => $type]]
        );
        $res = $es->search($tmp_params);
        // TODO::this is annoying, will require a refactor on index (pluralize types)
        if ($type != 'people')
            $type = $type . 's';
        $total[$type] = $res['aggregations']['total']['value'];
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

    $filter_types = '';
    if (isset($_GET['filter_types'])){
        $filter_types = $_GET['filter_types'];
    }

    $search_type = '';
    if (isset($_GET['search_type'])){
        $search_type = $_GET['search_type'];
    }

    if ($search_type != 'people')
        $search_type = substr_replace($search_type, '', -1);

    $filters = [
        'people' => [
            'Gender' => [
                'sex' => sexTypes
            ],
            // 'Age Category => '',
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
                            'must' => [
                                'match_all' => new \stdClass()
                            ],
                            'filter' => [
                                ['term' => ['type' => $search_type]]
                            ]
                        ]
                    ],
                    'collapse' => [
                        'field' => 'id'
                    ],
                    'size' => 0,
                    'aggs' => [
                        'total' => [
                            'cardinality' => [
                                'field' => 'id'
                            ]
                        ]
                    ]
                ]
            ];

            $total[$type] = [];

            foreach ($labels as $label => $fields) {
                $total[$type][$label] = [];
                foreach ($fields as $field => $values) {
                    foreach (array_keys($values) as $value) {
                        $tmp_params = $params;
                        array_push(
                            $tmp_params['body']['query']['bool']['filter'],
                            ['term' => [$field . '.raw' => $value]]
                        );

                        $res = $es->search($tmp_params);

                        $total[$type][$label][$value] = $res['aggregations']['total']['value'];
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
        ], #todo
        'Gender' => [
            'sex' => sexTypes
        ],
        'Role Types' => [
            'participant_role' => roleTypes
        ],
        'Age Category' => [
            'age_category' => ''
        ], #tbd
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
            ],
            'collapse' => [
                'field' => 'id'
            ],
            'size' => 0,
            'aggs' => [
                'total' => [
                    'cardinality' => [
                        'field' => 'id'
                    ]
                ]
            ]
        ]
    ];

    $total = [];

    foreach ($field_translate[$raw_field] as $field => $values) {
        foreach (array_keys($values) as $value) {
            $tmp_params = $params;
            array_push(
                $tmp_params['body']['query']['bool']['filter'],
                ['term' => [$field . '.raw' => $value]]
            );
            $res = $es->search($tmp_params);

            $total[$value] = $res['aggregations']['total']['value'];
        }
    }

    return json_encode($total);
}

function keyword_search() {
    $hosts = [
        ELASTICSEARCH_URL
    ];

    $es = ClientBuilder::create()
                        ->setHosts($hosts)
                        ->build();

    $item_types = ['people', 'event', 'place', 'source'];
    $filters = $templates = [];
    $query = $preset = $item_type = '';
    $size = 12;
    $from = 0;
    $get_all_counts = false;

    $convert_filters = [
        'gender' => 'sex',
        'role_types' => 'participant_role',
        'status' => 'person_status',
        'event-type' => 'event_type',
        'projects' => 'generated_by',
        'modern_countries' => 'modern_country_code'
    ];

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

    $params = [
        'index' => ELASTICSEARCH_INDEX_NAME,
        'body' => [
            'query' => [
                'bool' => [
                    'must' => []
                ]
            ],
            'collapse' => [
                'field' => 'id'
            ],
            'size' => $size,
            'from' => $from,
            'aggs' => [
                'total' => [
                    'cardinality' => [
                        'field' => 'id'
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
                array_push($terms, ['term' => ['ref_' . $key => $value[0]]]);
                break;
            }

            if ($key == 'date') {
                $dates = explode('-', $value[0]);
                //TODO::add gte or lte separate
                $range_filter = [
                    'range' => [
                        'date.raw' => [
                            'gte' => $dates[0],
                            'lte' => $dates[1]
                        ]
                    ]
                ];
                array_push($terms, $range_filter);
            } else if ($key == 'modern_country_code') {
                $codes = [];
                foreach ($value as $country) {
                    array_push($codes, reversecountrycode[$country]);
                }
                array_push($terms, ['terms' => ['modern_country_code.raw' => $codes]]);
            } else {
                $key = $key . '.raw';
                array_push($terms, ['terms' => [$key => $value]]);
            }
        }

        $params['body']['query']['bool']['filter'] = $terms;
    }

    $res = $es->search($params);
    $single_total = $res['aggregations']['total']['value'];

    if ($get_all_counts && $item_type) {
        $total = [];
        unset($params['body']['from']);
        $params['body']['size'] = 0;
        foreach ($item_types as $type) {
            // TODO::this is annoying, will require a refactor on index (pluralize types)
            if ($type == 'people')
                $count_key = $type . 'count';
            else
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
                $total[$count_key]['value'] = $count_res['aggregations']['total']['value'];
            }
        }
    } else {
        $total = $single_total;
    }

    return createCards($res['hits']['hits'], $templates, $preset, $total);
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
    return createCards($res['hits']['hits'], [$template], $preset);
}

?>