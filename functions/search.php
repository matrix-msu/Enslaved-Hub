<?php
require_once(BASE_PATH . 'vendor/autoload.php');
require_once(BASE_PATH . 'functions/functions.php');
use Elasticsearch\ClientBuilder;

// $params = [
//     'index' => 'my_index',
//     'id'    => 'my_id',
//     'body'  => ['testField' => 'abc']
// ];

// $response = $client->index($params);
// print_r($response);

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

function keyword_search() {
    $hosts = [
        ELASTICSEARCH_URL
    ];

    $es = ClientBuilder::create()
                        ->setHosts($hosts)
                        ->build();

    $filters = $templates = [];
    $query = $preset = $item_type = '';
    $size = 12;
    $from = 0;
    $get_all_counts = false;

    $convert_filters = [
        'gender' => 'sex',
        'role_types' => 'participant_role',
        'status' => 'person_status',
        'event-type' => 'event_type'
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
        $filters['person'][0];
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

            $key = $key . '.raw';

            array_push($terms, ['terms' => [$key => $value]]);
        }
        $params['body']['query']['bool']['filter'] = $terms;
    }
    // print_r($params);
    $res = $es->search($params);

    $single_total = $res['aggregations']['total']['value'];
    // $get_all_counts = true;
    if ($get_all_counts && $item_type) {
        $total = [];
        unset($params['body']['from']);
        $params['body']['size'] = 0;
        foreach (['people', 'event', 'place', 'source'] as $type) {
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

    // print_r($res['hits']['hits']);
    return createCards($res['hits']['hits'], $templates, $preset, $total);
}

?>
