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

function keyword_search() {
    $hosts = [
        ELASTICSEARCH_URL
    ];

    $es = ClientBuilder::create()
                        ->setHosts($hosts)
                        ->build();

    $filters = $templates = [];
    $query = $preset = '';
    $size = 12;
    $from = 0;

    $convert_filters = [
        'gender' => 'sex',
        'role_types' => 'participant_role',
        'status' => 'person_status'
    ];

    if (isset($_GET['preset'])) {
        $preset = $_GET['preset'];
    }

    if ($preset == 'all' && isset($_GET['display'])){
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
            'track_total_hits' => true,
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

    if ($filters) {
        $terms = [];
        foreach ($filters as $key => $value) {
            if (in_array($key, array_keys($convert_filters)))
                $key = $convert_filters[$key];

            if ($key == 'type') {
                // TODO::this is annoying, will require a refactor on index
                if ($value != 'people')
                    $value = substr_replace($value, '', -1);
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
    // print_r($res);
    return createCards($res['hits']['hits'], $templates, $preset, $res['aggregations']['total']['value']);
}

?>
