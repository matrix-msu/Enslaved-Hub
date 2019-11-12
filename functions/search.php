<?php
require_once(BASE_PATH . 'vendor/autoload.php');
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

    $filters = [];
    $query = '';
    $size = 12;
    $from = 0;

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
                    'must' => [
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
                                'end_date'
                            ],
                            'lenient' => true,
                            'query' => $query
                        ]
                    ]
                ]
            ],
            'collapse' => [
                'field' => 'id'
            ],
            'size' => $size,
            'from' => $from
        ]
    ];

    if ($filters) {
        $terms = [];
        if (count($filters) == 1 && count(array_values($filters)) == 1) {
            $terms = ['term' => $filters];
        } else {
            foreach ($filters as $key => $value) {
                if ($key != 'type')
                    $key = $key . '.raw';

                if (is_array($value))
                    $value = implode(' ', $value);

                array_push($terms, ['terms' => [$key => $value]]);
            }
        }
        $params['body']['query']['bool']['filter'] = $terms;
    }
    // print_r($params);
    print_r($es->search($params));
}

?>
