<?php

// use Elasticsearch\ClientBuilder;

// $hosts = [
//     ELASTICSEARCH_URL
// ];

// $client = ClientBuilder::create()
//                     ->setHosts($hosts)
//                     ->build();

// $params = [
//     'index' => 'my_index',
//     'id'    => 'my_id',
//     'body'  => ['testField' => 'abc']
// ];

// $response = $client->index($params);
// print_r($response);

function keyword_search($es, $text, $filters) {
    $query = str_replace(' ', ' AND ', $text);

    $params = [
        'index' => ELASTICSEARCH_INDEX_NAME,
        'body'  => [
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
            'size' => 1000
        ]
    ];

    // if ($filters) {

    // }

    return $es->search($params);
}

?>
