<?php

function testingFunction(){
    echo 'in testing function<br>';
    print_r($_GET);

}

function admin(){
    if (isset($_GET['theme'])){
        $theme = $_GET['theme'];

        if( !file_exists(BASE_PATH.'assets/stylesheets/themes/'.$theme.'.css') ){  //sanitize input
            die;
        }

        $path = BASE_PATH . "config.json";
        $contents = file_get_contents($path);
        $contents = json_decode($contents,true);
        $contents['theme'] = $theme;
        $contents = json_encode($contents);
        file_put_contents($path, $contents);
    }
    else {
        echo 'no theme selected';
    }
}


function blazegraph()
{
//    if (isset($_GET['delete'])) {
//        $path = "functions/queries.json";
//        $contents = file_get_contents($path);
//        $contents = json_decode($contents, true);
//        unset($contents[$_GET['delete']]);
//        $contents = array_values($contents);
//        $contents = json_encode($contents);
//        echo file_put_contents($path, $contents);
//        die;
//    }

    if (isset($_GET['filters'])){
        $filtersArray = $_GET['filters'];
    }

    if (isset($_GET['preset'])) {
        $preset = $_GET['preset'];
        $query = array('query' => "");
        switch ($preset){
            case 'people':

                $query['query'] =
                        'SELECT ?person ?personLabel ?name ?sex ?sexLabel ?race ?age ?ageLabel ?status ?statusLabel ?role ?roleLabel ?owner ?ownerLabel ?match ?matchLabel WHERE {
                      SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
                      ?person wdt:P3 wd:Q2.
                      OPTIONAL { ?person wdt:P82 ?name. }
                      OPTIONAL { ?person wdt:P17 ?sex. }
                      OPTIONAL { ?person wdt:P37 ?race. }
                      OPTIONAL { ?person wdt:P18 ?age. }
                      OPTIONAL { ?person wdt:P24 ?status. }
                      OPTIONAL { ?person wdt:P39 ?role. }
                      OPTIONAL { ?person wdt:P58 ?owner. }
                      OPTIONAL { ?person wdt:P88 ?match. }
                    }
                    LIMIT 100';
                break;
            case 'places':
                $query['query'] =
                    'SELECT DISTINCT ?place ?placeLabel ?place2 ?place2Label ?place3 ?place3Label WHERE {
                     FILTER regex(?regex, "^United States") .
                     ?place rdfs:label ?regex .
                     OPTIONAL{?place2 wdt:P10 ?place . }
                     OPTIONAL {?place3 wdt:P10 ?place2 .}

                    SERVICE wikibase:label { bd:serviceParam wikibase:language "en" .}
                    }order by ?place ?place2 ?place3';
                break;
            case 'events':
                $query['query'] =
                    'SELECT ?event ?eventLabel WHERE {
                      ?event wdt:P3 wd:Q34.
                      SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
                    }
                    LIMIT 100';
                break;
            case 'sources':
                $query['query'] =
                    'SELECT ?person ?personLabel ?name ?sex ?sexLabel ?race ?age ?ageLabel ?status ?statusLabel ?role ?roleLabel ?owner ?ownerLabel ?match ?matchLabel WHERE {
                      SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
                      ?person wdt:P3 wd:Q2.
                      OPTIONAL { ?person wdt:P82 ?name. }
                      OPTIONAL { ?person wdt:P17 ?sex. }
                      OPTIONAL { ?person wdt:P37 ?race. }
                      OPTIONAL { ?person wdt:P18 ?age. }
                      OPTIONAL { ?person wdt:P24 ?status. }
                      OPTIONAL { ?person wdt:P39 ?role. }
                      OPTIONAL { ?person wdt:P58 ?owner. }
                      OPTIONAL { ?person wdt:P88 ?match. }
                    }
                    LIMIT 100';
                break;
            case 'projects':
                $query['query'] =
                    'SELECT ?project ?projectLabel  WHERE {
                      ?project wdt:P3 wd:Q264

                      SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
                    }
                ';
                break;
            default:
                die;
        }
    }
    elseif (isset($_GET['query'])) {
        $query = array(
            'query' => $_GET['query']
        );
        $preset = 'default';
    }
    else{
        die;
    }



//    print_r($query);
//    die;
    // var_export($query);
    // die;

    $ch = curl_init("https://sandro-33.matrix.msu.edu/namespace/wdq/sparql");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        'Accept: application/sparql-results+json'
//        'Content-Type: application/sparql-results+json'
    ));
    $result = curl_exec($ch);
    curl_close($ch);
//    echo $result;

//
//    $path = "functions/queries.json";
//    $contents = file_get_contents($path);
//    $contents = json_decode($contents, true);
//    $contents[] = $query['query'];
//    $contents = json_encode($contents);
//
//    file_put_contents($path, $contents);


//    return $result;
    return createCards($result, $preset);
}

// this one is only used for blazegraph page
function createCards($results, $preset = 'default'){
    $results = json_decode($results, true)['results']['bindings'];
//    print_r($results[0]);die;

    $cards = Array();

    foreach ($results as $index => $record) {
        switch ($preset){
            case 'people':
                $fullName = $record['personLabel']['value'];
                $nameArray = explode(' ', $fullName);
                $firstName = preg_replace('/\W\w+\s*(\W*)$/', '$1', $fullName);
                $lastName = $nameArray[count($nameArray)-1];
                $status = $record['statusLabel']['value'];
                if (isset($record['sexLabel'])){
                    $sex = $record['sexLabel']['value'];
                } else {
                    $sex = 'Unidentified';
                }

                //todo: find a way to get the origin, location, and date range
                $origin = "todo";
                $dateRange = "todo";
                $location = "todo";

                $connection_lists = Array(
                    '<h1>10 Connected People</h1><ul><li>Person Name <span>(Wife)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Brother brother brother)</span> <div id="arrow"></div></li><li>Person Name <span>(Relation)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Father)</span> <div id="arrow"></div></li><li>Person Name <span>(Mother)</span> <div id="arrow"></div></li><li>View All People Connections <div id="arrow"></div></li></ul>',
                    '<h1>10 Connected Places</h1><ul><li>Place Name <div id="arrow"></div></li><li>Place Name is Longer<div id="arrow"></div></li><li>Place Name <div id="arrow"></div></li><li>View All Place Connections <div id="arrow"></div></li></ul>',
                    '<h1>10 Connected Events</h1><ul><li>Event Name <div id="arrow"></div></li><li>Event Name is Longer<div id="arrow"></div></li><li>Event Name <div id="arrow"></div></li><li>View All Event Connections <div id="arrow"></div></li></ul>',
                    '<h1>10 Connected Sources</h1><ul><li>Source Name <div id="arrow"></div></li><li>Source Name is Longer<div id="arrow"></div></li><li>Source Name <div id="arrow"></div></li><li>View All Source Connections <div id="arrow"></div></li></ul>',
                    '<h1>10 Connected Projects</h1><ul><li>Project Name <div id="arrow"></div></li><li>Project Name is Longer<div id="arrow"></div></li><li>Project Name <div id="arrow"></div></li><li>View All Project Connections <div id="arrow"></div></li></ul>'
                );

                $connections = '<div class="connections"><div class="card-icons"><img src="../assets/images/Person-dark.svg"><span>10</span><div class="connection-menu">'.$connection_lists[0].
                               '</div></div><div class="card-icons"><img src="../assets/images/Place-dark.svg"><span>10</span><div class="connection-menu">'.$connection_lists[1].
                               '</div></div><div class="card-icons"><img src="../assets/images/Event-dark.svg"><span>10</span><div class="connection-menu">'.$connection_lists[2].
                               '</div></div><div class="card-icons"><img src="../assets/images/Source-dark.svg"><span>10</span><div class="connection-menu">'.$connection_lists[3].
                               '</div></div><div class="card-icons"><img src="../assets/images/Project-dark.svg"><span>10</span><div class="connection-menu">'.$connection_lists[4].
                               '</div></div></div>';

                $card_icon = 'Person-light.svg';

                $card = "<li><div class='container card-image'>
                            <p>$fullName</p>
                            <img src='../assets/images/$card_icon'>
                            </div><div class='container cards'>
                            <div class='card-info'>
                            <p><span>Person Status: </span><span class='multiple'>$status<span class='tooltip'>Enslaved, Freed, Owner, Status</span></span></p>
                            <p><span>Sex: </span>$sex</p>
                            <p><span>Origin: </span>$origin</p>
                            <p><span>Location: </span>$location</p>
                            <p><span>Date Range: </span>$dateRange</p></div>
                            $connections
                            </div></li>";


                array_push($cards, $card);
                break;
            case 'places':
                $placeName = $record['place3Label']['value'];
                $city = $record['place3Label']['value'];
                $country = $record['placeLabel']['value'];
                $region = $record['place2Label']['value'];;
                $province = "todo";
                $location = "todo";

                $card= "
                    <div class='record'>
                        <div class='image-and-name'>
                            <img src='".BASE_IMAGE_URL."blazegraph/PlaceCard.jpg' alt='record image'>
                            <p class='name'>$placeName</p>
                        </div>
                        <div class='record-main'>
                            <div class='metadata'>
                                <div class='column'>
                                    <div class='metadata-row'>
                                        <span>City:</span><p class='city'>$city</p>
                                    </div>
                                    <div class='metadata-row'>
                                        <span>Country:</span><p class='country'>$country</p>
                                    </div>
                                    <div class='metadata-row'>
                                        <span>Enslaved Region:</span><p class='enslaved-region'>$region</p>
                                    </div>
                                </div>
                                <div class='column'>
                                     <div class='metadata-row'>
                                        <span>Province:</span><p class='province'>$province</p>
                                    </div>
                                    <div class='metadata-row'>
                                        <span>Location:</span><p class='location'>$location</p>
                                    </div>
                                </div>

                            </div>
                            <div class='bottom-row'>
                                <div class='icon-container'><img src='".BASE_IMAGE_URL."blazegraph/People.svg' alt=''><span>10</span></div>
                                <div class='icon-container'><img src='".BASE_IMAGE_URL."blazegraph/Places.svg' alt=''><span>3</span></div>
                                <div class='icon-container'><img src='".BASE_IMAGE_URL."blazegraph/Events.svg' alt=''><span>4</span></div>
                                <div class='icon-container'><img src='".BASE_IMAGE_URL."blazegraph/Sources.svg' alt=''><span>2</span></div>
                                <div class='icon-container'><img src='".BASE_IMAGE_URL."blazegraph/Projects.svg' alt=''><span>1</span></div>
                            </div>
                        </div>
                    </div>
                ";
                break;
            case 'events':
                $eventName = $record['eventLabel']['value'];
                $eventType = "todo";
                $eventDate = "todo";
                $card= "
                    <div class='record'>
                        <div class='image-and-name'>
                            <img src='".BASE_IMAGE_URL."blazegraph/PersonCard.jpg' alt='record image'>
                            <p class='name'>$eventName</p>
                        </div>
                        <div class='record-main'>
                            <div class='metadata'>
                                <div class='column'>
                                    <div class='metadata-row'>
                                        <span>Event Type:</span><p class='event-type'>$eventType</p>
                                    </div>
                                    <div class='metadata-row'>
                                        <span>Event Date:</span><p class='event-date'>$eventDate</p>
                                    </div>
                                </div>
                            </div>
                            <div class='bottom-row'>
                                <div class='icon-container'><img src='".BASE_IMAGE_URL."blazegraph/People.svg' alt=''><span>10</span></div>
                                <div class='icon-container'><img src='".BASE_IMAGE_URL."blazegraph/Places.svg' alt=''><span>3</span></div>
                                <div class='icon-container'><img src='".BASE_IMAGE_URL."blazegraph/Events.svg' alt=''><span>4</span></div>
                                <div class='icon-container'><img src='".BASE_IMAGE_URL."blazegraph/Sources.svg' alt=''><span>2</span></div>
                                <div class='icon-container'><img src='".BASE_IMAGE_URL."blazegraph/Projects.svg' alt=''><span>1</span></div>
                            </div>
                        </div>
                    </div>
                ";
                break;
            case 'sources':
                print_r($results);
                die;
                break;
            case 'projects':
                print_r($results);
                die;
                break;
            default:
                print_r($results);
                die;
                break;
        }

    }

    return json_encode($cards);
}


function printFeaturedEvents(){
    if( !isset($_GET['category'])||!isset($_GET['start'])||!isset($_GET['limit']) ){
        //todo- redirect 404 or 500
        echo 'hi';
        return;
    }
    echo($_GET['category']);
    $data = json_decode(SearchOneForm(
        PID,
        $GLOBALS[$_GET['category']][PID],
        'ALL',
        [],
        "",
        [],
        $_GET['start'],
        $_GET['limit'],
        ['size'=>true]
    ),true);

    $count = $data['counts']['global'];
    $data = $data['records'][0];
    $counter = count($data);
    $html = '';
    $index = -1;
    if( isset($_GET['index'])){
        $index = $_GET['index']-1;
    }


    $dots = '';
    for ($i = 0; $i < $counter; $i++) {
        $dots .= "<div class='dot' index=$i></div>";
    }

//    $dots = '';
//    for ($i = 0; $i < $count; $i++) {
//        $dots .= "<div class='dot' index=$i></div>";
//    }
    echo($data);
    var_dump($data);die;
    if($_GET['category'] == 'PEOPLE_SID_ARRAY') {
        $category = 'people';

        foreach ($data as $record) {
            $index++;
            $background = './assets/images/SourceCard.jpg';
            $icon = './assets/images/Event-dark.svg';
            $view_text = 'VIEW EVENT';

            if (array_key_exists("background-image", $record) && $record['background-image'] != '') {
                $background = $record['background-image'];
            }

            $name = 'Name: ';
            if (isset($record['Name']) && isset($record['Name']['value'])) {
                $name .= $record['Name']['value'];
            }
            $id = 'ID: ';
            if (isset($record['Name Identifier']) && isset($record['Name Identifier']['value'])) {
                $id .= $record['Name Identifier']['value'];
            }
            $voyage = 'Voyage ID: ';
            if (isset($record['VOYAGE ID']) && isset($record['VOYAGE ID']['value'])) {
                $voyage .= $record['VOYAGE ID']['value'];
            }
            $status = 'Gender: ';
            if (isset($record['Gender']) && isset($record['Gender']['value'])) {
                $status .= $record['Gender']['value'];
            }
            $registered = 'Register ID: ';
            if (isset($record['Register ID']) && isset($record['Register ID']['value'])) {
                $registered .= $record['Register ID']['value'];
            }
        }
    }

    if($_GET['category'] == 'EVENTS_SID_ARRAY') {
        $category = 'events';

        foreach ($data as $record) {
            $index++;
            $background = './assets/images/SourceCard.jpg';
            $icon = './assets/images/Event-dark.svg';
            $view_text = 'VIEW EVENT';

            if (array_key_exists("background-image", $record) && $record['background-image'] != '') {
                $background = $record['background-image'];
            }

            $name = 'Name: ';
            if (isset($record['Name']) && isset($record['Name']['value'])) {
                $name .= $record['Name']['value'];
            }
            $id = 'ID: ';
            if (isset($record['Name Identifier']) && isset($record['Name Identifier']['value'])) {
                $id .= $record['Name Identifier']['value'];
            }
            $voyage = 'Voyage ID: ';
            if (isset($record['VOYAGE ID']) && isset($record['VOYAGE ID']['value'])) {
                $voyage .= $record['VOYAGE ID']['value'];
            }
            $status = 'Gender: ';
            if (isset($record['Gender']) && isset($record['Gender']['value'])) {
                $status .= $record['Gender']['value'];
            }
            $registered = 'Register ID: ';
            if (isset($record['Register ID']) && isset($record['Register ID']['value'])) {
                $registered .= $record['Register ID']['value'];
            }
        }
    }

    if($_GET['category'] == 'PLACES_SID_ARRAY') {
        $category = 'events';

        foreach ($data as $record) {
            $index++;
            $background = './assets/images/SourceCard.jpg';
            $icon = './assets/images/Event-dark.svg';
            $view_text = 'VIEW EVENT';

            if (array_key_exists("background-image", $record) && $record['background-image'] != '') {
                $background = $record['background-image'];
            }

            $name = 'Name: ';
            if (isset($record['Name']) && isset($record['Name']['value'])) {
                $name .= $record['Name']['value'];
            }
            $id = 'ID: ';
            if (isset($record['Name Identifier']) && isset($record['Name Identifier']['value'])) {
                $id .= $record['Name Identifier']['value'];
            }
            $voyage = 'Voyage ID: ';
            if (isset($record['VOYAGE ID']) && isset($record['VOYAGE ID']['value'])) {
                $voyage .= $record['VOYAGE ID']['value'];
            }
            $status = 'Gender: ';
            if (isset($record['Gender']) && isset($record['Gender']['value'])) {
                $status .= $record['Gender']['value'];
            }
            $registered = 'Register ID: ';
            if (isset($record['Register ID']) && isset($record['Register ID']['value'])) {
                $registered .= $record['Register ID']['value'];
            }
        }
    }

    if($_GET['category'] == 'SOURCES_SID_ARRAY') {
        $category = 'events';

        foreach ($data as $record) {
            $index++;
            $background = './assets/images/SourceCard.jpg';
            $icon = './assets/images/Event-dark.svg';
            $view_text = 'VIEW EVENT';

            if (array_key_exists("background-image", $record) && $record['background-image'] != '') {
                $background = $record['background-image'];
            }

            $name = 'Name: ';
            if (isset($record['Name']) && isset($record['Name']['value'])) {
                $name .= $record['Name']['value'];
            }
            $id = 'ID: ';
            if (isset($record['Name Identifier']) && isset($record['Name Identifier']['value'])) {
                $id .= $record['Name Identifier']['value'];
            }
            $voyage = 'Voyage ID: ';
            if (isset($record['VOYAGE ID']) && isset($record['VOYAGE ID']['value'])) {
                $voyage .= $record['VOYAGE ID']['value'];
            }
            $status = 'Gender: ';
            if (isset($record['Gender']) && isset($record['Gender']['value'])) {
                $status .= $record['Gender']['value'];
            }
            $registered = 'Register ID: ';
            if (isset($record['Register ID']) && isset($record['Register ID']['value'])) {
                $registered .= $record['Register ID']['value'];
            }
        }
    }

    $html .= "<a class='card' href='' index='$index' set='0'>
                <div class='card-header $category-cards'>
                    <img class='background' src=$background alt='Record Image'>
                    <img class='icon' src='$icon' alt='Record Icon'>
                    <h3 class='card-title'>$name</h3>
                </div>

                <div class='card-body'>
                    <div class='card-data'>
                        <p>$id</p>
                        <p>$voyage</p>
                        <p>$status</p>
                        <p>$registered</p>
                    </div>

                    <p class='card-link'>
                        $view_text
                        <img class='arrow-right' src='./assets/images/arrow-right-white.svg' alt='Arrow Right'>
                    </p>
                </div>
            </a>
            ";
    return json_encode(array('html'=>$html, 'count'=>$count, 'dots'=>$dots, 'counter'=>$counter));
}


function xss_clean($data) {
    // Fix &entity\n;
    $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
    $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
    $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
    $count=0;

    // Remove any attribute starting with "on" or xmlns

    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data,-1 ,$count);
    if ($count>0)
        die("Wrong attribute");

    // Remove javascript: and vbscript: protocols
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data,-1 ,$count);

    if ($count>0)
        die("Bad js input");
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data,-1 ,$count);

    if ($count>0)
        die("Bad js input");
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data,-1 ,$count);
    if ($count>0)
        die("Bad js input");

    // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data,-1 ,$count);
    if ($count>0)
        die("Bad input");
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data,-1 ,$count);
    if ($count>0)
        die("Bad input");
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data,-1 ,$count);
    if ($count>0)
        die("Bad input");

    // Remove namespaced elements (we do not need them)
    $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data,-1 ,$count);
    if ($count>0)
        die("Bad parameter");
    do
    {
        // Remove really unwanted tags
        $old_data = $data;
        $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data,-1 ,$count);

        if ($count>0){
            die("unwanted parameters");
        }

    }
    while ($old_data !== $data);

    // we are done...
    return $data;
}

function checkKID($kid)
{
    if (preg_match("/^[0-9A-F]+-[0-9A-F]+-[0-9A-F]+(-[0-9A-F]+)*$/", $kid))
        return true;
    else
        return false;
}
