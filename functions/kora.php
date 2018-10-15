<?php
require_once 'config.php';
function getStories() {
    $search = ['token' => token, 'form' => storyForm, 'fields' => ['Title_16_23', 'Featured_16_23']];
    $search['realnames'] = true;
    $query = [['search' => 'keyword', 'keys' => 'True', 'method' => 'EXACT', 'fields' => ['Display_16_23']]];
    $search['query'] = $query;
    $data = ['forms' => '['.json_encode($search).']'];
    $ch = curl_init(BASE_URL_KORA.'api/search');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true);
}

function storyContent($kid) {
    $search = ['token' => token, 'form' => storyForm];
    $search['fields'] = ['Title_16_23', 'Images_16_23', 'Caption_16_23', 'Text_16_23', 'Resources_16_23', 'Source_16_23', 'Timeline_16_23', 'Story_Associator_16_23'];
    $search['realnames'] = true;
    $query = [['search' => 'kid', 'kids' => [$kid]]];
    $search['query'] = $query;
    $data = ['forms' => '['.json_encode($search).']'];
    $ch = curl_init(BASE_URL_KORA.'api/search');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true)['records'][0][$kid];
}
