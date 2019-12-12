<?php
function storyContent($kid) {
    $search = ['token' => TOKEN, 'form' => STORY_SID];
    $search['fields'] = ['Title', 'Images', 'Caption', 'Text', 'Resources', 'Source', 'Timeline', 'Story_Associator'];
    $search['realnames'] = true;
    $query = [['search' => 'kid', 'kids' => [$kid]]];
    $search['query'] = $query;
    $data = ['forms' => '['.json_encode($search).']'];
    $ch = curl_init(KORA_SEARCH_URL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true)['records'][0][$kid];
}

function getStories($page = 1, $count = 8, $sort = []){
    $search = [
        'token' => TOKEN,
        'form' => STORY_SID,
        'fields' => ['Title', 'Featured'],
        'realnames' => true,
        'size' => TRUE,
        'index' => ($page-1) * $count,
        'count' => $count
    ];

    $query = [[
      'search' => 'keyword',
      'keys' => 'True',
      'method' => 'EXACT',
      'fields' => ['Display']
    ]];

    $search['query'] = $query;

    if ($sort) {
        $formattedSort = array();

        foreach ($sort as $index => $value) {
            if ($index % 2 == 0) {
                array_push($formattedSort, formatField($value));
            } else {
                array_push($formattedSort, $value);
            }
        }

        $search['sort'] = $formattedSort;
    }

    $data = ['forms' => '['.json_encode($search).']'];
    $ch = curl_init(KORA_SEARCH_URL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true);
}

function displayStories($stories){
    $bg = ['enslaved-header-bg.jpg','enslaved-header-bg2.jpg',
            'enslaved-header-bg3.jpg','enslaved-header-bg4.jpg',
            'enslaved-header-bg5.jpg','enslaved-header-bg6.jpg',
            'enslaved-header-bg7.jpg'];

    foreach ($stories['records'][0] as $kid => $story) {
        //get images from records
        $storyImages = $story["Images"];
        if (isset($storyImages[0])){
            $storyImage = $storyImages[0]['url'];
        } else {
            $storyIndex = array_rand($bg);
            $storyImage = BASE_URL.'assets/images/'.$bg[$storyIndex];
        }

        echo '<li class="card" style="background-image: url('.$storyImage.')"><a href="'.BASE_URL.'fullStory?kid='.$kid.'">';
        echo '<h2 class="card-title">'.$story['Title'].'</h2>';
        echo '</a><div class="overlay"></div></li>';
    }
}

function appendIds($name) {
    // Helper for formatField(), would declare inside of said function
    // for scope but this language is trash.
    return str_replace(' ', '_',
        ucwords(
            $name
        ) . '_' . PID . '_' . STORY_SID
    );
}

function formatField($field) {
    //todo do we need to append ids?
    // Formats real field names into kora unique field ids.
    // Caveat: the unique field ids and $field must match without formatting.
    if (is_array($field)) {
        $result = array();

        foreach ($field as $value) {
            array_push(
                $result,
                appendIds($value)
            );
        }
    } else {
        return appendIds($field);
    }
}
