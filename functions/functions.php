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
        if (isset($filtersArray['limit'])){
            $limit = "LIMIT " . $filtersArray['limit'];
        } else {
            $limit = '';
        }

    } else {
        $filtersArray = Array();
    }
//    print_r($filtersArray);die;

    $template = $_GET['template'];



    if (isset($_GET['preset'])) {
        $preset = $_GET['preset'];
        $query = array('query' => "");
        switch ($preset){
            case 'people':

                $sexQuery = "";
                if (isset($filtersArray['sex'])){
                    $sex = $filtersArray['sex'];
                    if (array_key_exists($sex, sexTypes)){
                        $qSex = sexTypes[$sex];
                        $sexQuery = "?person wdt:P17 wd:$qSex.";

                    }
                }

                $roleQuery = '';
                if (isset($filtersArray['Role Types'])){
                    $role = $filtersArray['Role Types'];
                    if (array_key_exists($role, roleTypes)){
                        $qRole = roleTypes[$role];
                        $roleQuery = "?person wdt:P39 wd:$qRole.";

                    }
                }

                $query['query'] ='
                SELECT ?person ?personLabel ?age ?agecategoryLabel ?name ?originLabel ?role ?roleLabel
                        (group_concat(distinct ?status; separator = "||") as ?status)
                        (group_concat(distinct ?place; separator = "||") as ?place)
                        (group_concat(distinct ?startyear; separator = "||") as ?startyear)
                        (group_concat(distinct ?endyear; separator = "||") as ?endyear)
                        WHERE {
                          SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
                          ?person wdt:P3 wd:Q602.
                          '. $sexQuery .'
                          ?person wdt:P32 wd:Q66.
                          '. $roleQuery .'
                          OPTIONAL {?person wdt:P3 wd:Q2.}
                          OPTIONAL {?person wdt:P33 ?age.}
                          OPTIONAL {?person wdt:P39 ?role.}
                          OPTIONAL {?person wdt:P32 ?agecategory.}
                          OPTIONAL {?person wdt:P82 ?name.}
                          OPTIONAL {?person wdt:P20 ?origin.}
                          OPTIONAL {?name wdt:P30 ?event.
                                    ?event wdt:P13 ?startdate.}
                          BIND(str(YEAR(?startdate)) AS ?startyear).
                      OPTIONAL {?event wdt:P14 ?enddate.}
                      BIND(str(YEAR(?enddate)) AS ?endyear).
                      OPTIONAL {?event wdt:P12 ?place.}
                      OPTIONAL { ?person wdt:P17 ?sex. }
                      OPTIONAL { ?person wdt:P24 ?status. }
                      OPTIONAL { ?person wdt:P58 ?owner. }
                      OPTIONAL { ?person wdt:P88 ?match. }
                    } group by ?person ?personLabel ?age ?agecategoryLabel ?name ?originLabel ?role ?roleLabel
                ';


                $ch = curl_init("https://sandro-33.matrix.msu.edu/namespace/wdq/sparql");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                    'Accept: application/sparql-results+json'
                ));
                $result1 = curl_exec($ch);
                curl_close($ch);

//                echo $query['query'];die;
//                echo $result1;die;
                
                $query['query'] ='
                SELECT ?person ?personLabel ?name ?originLabel ?sexLabel
                        (group_concat(distinct ?status; separator = "||") as ?status)
                        WHERE {
                          SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
                          ?person wdt:P3 wd:Q602.
                          '. $sexQuery . '
                          ?person wdt:P82 ?name.
                          ?person p:P82 ?namestatement . # with a P82 (hasname) statement
	                 FILTER NOT EXISTS { ?namestatement pq:P30 ?event}
                          # ... but the statement doesnt have  P30 qualifier


                          OPTIONAL {?person wdt:P20 ?origin.}


                          OPTIONAL { ?person wdt:P17 ?sex. }
                          OPTIONAL { ?person wdt:P24 ?status. }


                        } group by ?person ?personLabel ?name ?originLabel ?sexLabel
                        ';


                $ch = curl_init("https://sandro-33.matrix.msu.edu/namespace/wdq/sparql");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                    'Accept: application/sparql-results+json'
                ));
                $result2 = curl_exec($ch);
                curl_close($ch);

                $result1 = json_decode($result1, true)['results']['bindings'];
                $result2 = json_decode($result2, true)['results']['bindings'];

                $results = array_merge($result1, $result2);

//                print_r($results);die;

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

                $results = json_decode($result, true)['results']['bindings'];
                break;
            case 'events':
                $query['query'] =
                    'SELECT ?event ?eventLabel WHERE {
                      ?event wdt:P3 wd:Q34.
                      SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
                    }
                    LIMIT 100';
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

                $results = json_decode($result, true)['results']['bindings'];
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

                $results = json_decode($result, true)['results']['bindings'];
                break;
            case 'projects':
//                $query['query'] =
//                    'SELECT ?project ?projectLabel  WHERE {
//                      ?project wdt:P3 wd:Q264
//
//                      SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
//                    }
//                ';

                $query['query'] =
                    'SELECT ?person ?personLabel ?name ?originLabel
                        (group_concat(distinct ?status; separator = "||") as ?status)
                        (group_concat(distinct ?place; separator = "||") as ?place)
                        (group_concat(distinct ?startyear; separator = "||") as ?startyear)
                        (group_concat(distinct ?endyear; separator = "||") as ?endyear)
                        WHERE {
                          SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
                          ?person wdt:P3 wd:Q602.
                          ?person wdt:P17 wd:Q47.
                          OPTIONAL {?person wdt:P3 wd:Q2.}
                          OPTIONAL {?person wdt:P82 ?name.}
                          OPTIONAL {?person wdt:P20 ?origin.}
                          OPTIONAL {?name wdt:P30 ?event.
                                    ?event wdt:P13 ?startdate.}
                          BIND(str(YEAR(?startdate)) AS ?startyear).

                          OPTIONAL {?event wdt:P14 ?enddate.}
                          BIND(str(YEAR(?enddate)) AS ?endyear).
                          OPTIONAL {?event wdt:P12 ?place.}
                          OPTIONAL { ?person wdt:P17 ?sex. }
                          OPTIONAL { ?person wdt:P24 ?status. }
                          OPTIONAL { ?person wdt:P58 ?owner. }
                          OPTIONAL { ?person wdt:P88 ?match. }

                        } group by ?person ?personLabel ?name ?originLabel
                        ' . $limit;

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

                $results = json_decode($result, true)['results']['bindings'];
                break;
            case 'stories':
                $query['query'] =
                    'SELECT ?person ?personLabel ?name ?originLabel
                        (group_concat(distinct ?status; separator = "||") as ?status)
                        (group_concat(distinct ?place; separator = "||") as ?place)
                        (group_concat(distinct ?startyear; separator = "||") as ?startyear)
                        (group_concat(distinct ?endyear; separator = "||") as ?endyear)
                        WHERE {
                          SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
                          ?person wdt:P3 wd:Q602.
                          ?person wdt:P17 wd:Q47.
                          OPTIONAL {?person wdt:P3 wd:Q2.}
                          OPTIONAL {?person wdt:P82 ?name.}
                          OPTIONAL {?person wdt:P20 ?origin.}
                          OPTIONAL {?name wdt:P30 ?event.
                                    ?event wdt:P13 ?startdate.}
                          BIND(str(YEAR(?startdate)) AS ?startyear).

                          OPTIONAL {?event wdt:P14 ?enddate.}
                          BIND(str(YEAR(?enddate)) AS ?endyear).
                          OPTIONAL {?event wdt:P12 ?place.}
                          OPTIONAL { ?person wdt:P17 ?sex. }
                          OPTIONAL { ?person wdt:P24 ?status. }
                          OPTIONAL { ?person wdt:P58 ?owner. }
                          OPTIONAL { ?person wdt:P88 ?match. }

                        } group by ?person ?personLabel ?name ?originLabel
                        ' . $limit;

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

                $results = json_decode($result, true)['results']['bindings'];
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



//
//    $path = "functions/queries.json";
//    $contents = file_get_contents($path);
//    $contents = json_decode($contents, true);
//    $contents[] = $query['query'];
//    $contents = json_encode($contents);
//
//    file_put_contents($path, $contents);


//    return $result;
    return createCards($results, $template, $preset);
}

// this one is only used for blazegraph page
function createCards($results, $template, $preset = 'default'){
//    print_r($results);die;
    $cards = Array();

    foreach ($results as $index => $record) {
        switch ($preset){
            case 'people':
                $fullName = $record['name']['value'];
                $nameArray = explode(' ', $fullName);
                $firstName = preg_replace('/\W\w+\s*(\W*)$/', '$1', $fullName);
                $lastName = $nameArray[count($nameArray)-1];

                if (isset($record['statusLabel']) && isset($record['statusLabel']['value'])){
                    $status = $record['statusLabel']['value'];
                } else {
                    $status = "";
                }

                if (isset($record['sexLabel']) && isset($record['sexLabel']['value'])){
                    $sex = $record['sexLabel']['value'];
                } else {
                    $sex = 'Unidentified';
                }

                if (isset($record['status']) && isset($record['status']['value'])){
                    $statusArray = explode('||', $record['status']['value']);
                    $status = '';

                    $statusCount = 0;
                    foreach ($statusArray as $statusUrl) {
                        $qStatus = end(explode('/', $statusUrl));
                        if (!empty($qStatus)){
                            $statusLabel = qpersonstatus[$qStatus];
                            if ($statusCount > 0){
                                $status .= ", $statusLabel";
                            } else {
                                $status .= "$statusLabel";

                            }
                            $statusCount++;
                        }
                    }
                } else {
                    $status = '';
                }

                $statusHtml = '';
                // if a person has multiple statuses, display them in a tooltip
                if ($statusCount == 1){
                    $statusHtml = "<p><span>Person Status: </span>$status</p>";
                }
                if ($statusCount > 1){
                    $statusHtml = "<p><span>Person Status: </span><span class='multiple'>Multiple<span class='tooltip'>$status</span></span></p>";
                }


                if (isset($record['originLabel']) && isset($record['originLabel']['value'])){
                    $origin = $record['originLabel']['value'];
                } else {
                    $origin = '';
                }

                if (isset($record['place']) && isset($record['place']['value'])){
                    $placeArray = explode('||', $record['place']['value']);
                    $location = '';
                    $placeUrl = end($placeArray);
//                    foreach ($placeArray as $placeUrl) {
                    $qPlace = end(explode('/', $placeUrl));
                    $place = qPlaces[$qPlace];
                    $location .= "$place ";
//                    }
                } else {
                    $location = '';
                }

                if (isset($record['startyear']) && isset($record['startyear']['value'])){
                    $startYears = explode('||', $record['startyear']['value']);
                    $startYear = min($startYears);
                } else {
                    $startYear = '';
                }

                if (isset($record['endyear']) && isset($record['endyear']['value'])){
                    $endYears = explode('||', $record['endyear']['value']);
                    $endYear = max($endYears);
                } else {
                    $endYear = '';
                }

                if ($startYear == ''){
                    $dateRange = $endYear;
                } elseif ($endYear == ''){
                    $dateRange = $startYear;
                } else {
                    $dateRange = "$startYear - $endYear";
                }


                if ($origin != ''){
                    $originHtml = "<p><span>Origin: </span>$origin</p>";
                } else {
                    $originHtml = '';
                }



                $connection_lists = Array(
                    '<h1>10 Connected People</h1><ul><li>Person Name <span>(Wife)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Brother brother brother)</span> <div id="arrow"></div></li><li>Person Name <span>(Relation)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Father)</span> <div id="arrow"></div></li><li>Person Name <span>(Mother)</span> <div id="arrow"></div></li><li>View All People Connections <div id="arrow"></div></li></ul>',
                    '<h1>10 Connected Places</h1><ul><li>Place Name <div id="arrow"></div></li><li>Place Name is Longer<div id="arrow"></div></li><li>Place Name <div id="arrow"></div></li><li>View All Place Connections <div id="arrow"></div></li></ul>',
                    '<h1>10 Connected Events</h1><ul><li>Event Name <div id="arrow"></div></li><li>Event Name is Longer<div id="arrow"></div></li><li>Event Name <div id="arrow"></div></li><li>View All Event Connections <div id="arrow"></div></li></ul>',
                    '<h1>10 Connected Sources</h1><ul><li>Source Name <div id="arrow"></div></li><li>Source Name is Longer<div id="arrow"></div></li><li>Source Name <div id="arrow"></div></li><li>View All Source Connections <div id="arrow"></div></li></ul>',
                    '<h1>10 Connected Projects</h1><ul><li>Project Name <div id="arrow"></div></li><li>Project Name is Longer<div id="arrow"></div></li><li>Project Name <div id="arrow"></div></li><li>View All Project Connections <div id="arrow"></div></li></ul>'
                );

                $connections = '<div class="connectionswrap"><div class="connections"><div class="card-icons"><img src="../assets/images/Person-dark.svg"><span>10</span><div class="connection-menu">'.$connection_lists[0].
                               '</div></div><div class="card-icons"><img src="../assets/images/Place-dark.svg"><span>10</span><div class="connection-menu">'.$connection_lists[1].
                               '</div></div><div class="card-icons"><img src="../assets/images/Event-dark.svg"><span>10</span><div class="connection-menu">'.$connection_lists[2].
                               '</div></div><div class="card-icons"><img src="../assets/images/Source-dark.svg"><span>10</span><div class="connection-menu">'.$connection_lists[3].
                               '</div></div><div class="card-icons"><img src="../assets/images/Project-dark.svg"><span>10</span><div class="connection-menu">'.$connection_lists[4].
                               '</div></div></div></div>';

                $card_icon = 'Person-light.svg';

                $card = "<li><div class='container card-image'>
                            <p>$fullName</p>
                            <img src='../assets/images/$card_icon'>
                            </div><div class='container cards'>
                            <div class='card-info'>
                            $statusHtml
                            <p><span>Sex: </span>$sex</p>
                            $originHtml
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
                $fullName = $record['personLabel']['value'];
                $nameArray = explode(' ', $fullName);
                $firstName = preg_replace('/\W\w+\s*(\W*)$/', '$1', $fullName);
                $lastName = $nameArray[count($nameArray)-1];

                if (isset($record['statusLabel']) && isset($record['statusLabel']['value'])){
                    $status = $record['statusLabel']['value'];
                } else {
                    $status = "";
                }

                if (isset($record['sexLabel']) && isset($record['sexLabel']['value'])){
                    $sex = $record['sexLabel']['value'];
                } else {
                    $sex = 'Unidentified';
                }

                // todo turn these into status labels
                if (isset($record['status']) && isset($record['status']['value'])){
                    $statusArray = explode('||', $record['status']['value']);
                    $status = '';
                    $count = 1;
                    foreach ($statusArray as $statusUrl) {
                        $status .= "<a href='$statusUrl' target='_blank'>$count</a> ";
                        $count++;
                    }
                } else {
                    $status = '';
                }


                if (isset($record['originLabel']) && isset($record['originLabel']['value'])){
                    $origin = $record['originLabel']['value'];
                } else {
                    $origin = '';
                }

                // todo turn these into placeLabels
                if (isset($record['place']) && isset($record['place']['value'])){
                    $placeArray = explode('||', $record['place']['value']);
                    $location = '';
                    $count = 1;
                    foreach ($placeArray as $placeUrl) {
                        $location .= "<a href='$placeUrl' target='_blank'>$count</a> ";
                        $count++;
                    }
                } else {
                    $location = '';
                }

                if (isset($record['startyear']) && isset($record['startyear']['value'])){
                    $startYears = explode('||', $record['startyear']['value']);
                    $startYear = min($startYears);
                } else {
                    $startYear = '';
                }

                if (isset($record['endyear']) && isset($record['endyear']['value'])){
                    $endYears = explode('||', $record['endyear']['value']);
                    $endYear = max($endYears);
                } else {
                    $endYear = '';
                }

                $dateRange = "$startYear - $endYear";


                if ($template == 'homeCard') {
                    $card = "<li>
                    <a href='".BASE_URL."fullStory/'>
                        <div class='container cards'>
                            <p class='card-title'>$fullName</p>
                            <h4 class='card-view-story'>View Story <div class='view-arrow'></h4>
                        </div>
                    </a>
                </li>";
                }

                array_push($cards, $card);
                break;
            case 'stories':
                $fullName = $record['personLabel']['value'];
                $nameArray = explode(' ', $fullName);
                $firstName = preg_replace('/\W\w+\s*(\W*)$/', '$1', $fullName);
                $lastName = $nameArray[count($nameArray)-1];

                if (isset($record['statusLabel']) && isset($record['statusLabel']['value'])){
                    $status = $record['statusLabel']['value'];
                } else {
                    $status = "";
                }

                if (isset($record['sexLabel']) && isset($record['sexLabel']['value'])){
                    $sex = $record['sexLabel']['value'];
                } else {
                    $sex = 'Unidentified';
                }

                // todo turn these into status labels
                if (isset($record['status']) && isset($record['status']['value'])){
                    $statusArray = explode('||', $record['status']['value']);
                    $status = '';
                    $count = 1;
                    foreach ($statusArray as $statusUrl) {
                        $status .= "<a href='$statusUrl' target='_blank'>$count</a> ";
                        $count++;
                    }
                } else {
                    $status = '';
                }


                if (isset($record['originLabel']) && isset($record['originLabel']['value'])){
                    $origin = $record['originLabel']['value'];
                } else {
                    $origin = '';
                }

                // todo turn these into placeLabels
                if (isset($record['place']) && isset($record['place']['value'])){
                    $placeArray = explode('||', $record['place']['value']);
                    $location = '';
                    $count = 1;
                    foreach ($placeArray as $placeUrl) {
                        $location .= "<a href='$placeUrl' target='_blank'>$count</a> ";
                        $count++;
                    }
                } else {
                    $location = '';
                }

                if (isset($record['startyear']) && isset($record['startyear']['value'])){
                    $startYears = explode('||', $record['startyear']['value']);
                    $startYear = min($startYears);
                } else {
                    $startYear = '';
                }

                if (isset($record['endyear']) && isset($record['endyear']['value'])){
                    $endYears = explode('||', $record['endyear']['value']);
                    $endYear = max($endYears);
                } else {
                    $endYear = '';
                }

                $dateRange = "$startYear - $endYear";


                if ($template == 'homeCard') {
                    $card = "<li>
                    <a href='".BASE_URL."fullStory/'>
                        <div class='container cards'>
                            <p class='card-title'>$fullName</p>
                            <h4 class='card-view-story'>View Story <div class='view-arrow'></h4>
                        </div>
                    </a>
                </li>";
                }

                array_push($cards, $card);
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
