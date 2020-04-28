<?php

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

function blazegraph() {
    if (isset($_GET['filters'])){
        $filtersArray = $_GET['filters'];

        $limitQuery = '';
        if (isset($filtersArray['limit'])){
            $limit = $filtersArray['limit'];
            $limitQuery = "limit $limit";
        }
        $offsetQuery = '';
        if (isset($filtersArray['offset'])){
            $offset = $filtersArray['offset'];
            $offsetQuery = "offset $offset";
        }
    } else {
        $filtersArray = Array();
    }

    $templates = $_GET['templates'];

    $record_total = 0;
    $queryArray = array();

    if (isset($_GET['preset'])) {
        $preset = $_GET['preset'];
        include BASE_LIB_PATH."variableIncluder.php";

        switch ($preset){
            case 'singleproject':
                // QID is mandatory
                if(!isset($filtersArray["qid"]) || empty($filtersArray["qid"])) return false;

                $Q_ID = $filtersArray["qid"][0];
                $Q_limit = 10;
                $Q_offset = 0;

                // Get Limit and offset from GET
                if(isset($_GET["limit"]) && !empty($_GET["limit"])) $Q_limit = $_GET["limit"];
                if(isset($_GET["offset"]) && !empty($_GET["offset"])) $Q_offset = $_GET["offset"];


                $query = array('query' => "");
                include BASE_PATH."queries/".$preset."/data.php";
                $query['query'] = $tempQuery;

                array_push($queryArray, $query);
                break;

            case 'projectassoc':
                if (isset($filtersArray['qid']) && $filtersArray['qid'][0]){
                    $qid = $filtersArray['qid'][0];
                } else {
                    break;
                }

                $query = array('query' => "");
                include BASE_PATH."queries/".$preset."/personCount.php";
                $query['query'] = $tempQuery;
                array_push($queryArray, $query);

                include BASE_PATH."queries/".$preset."/eventCount.php";
                $query['query'] = $tempQuery;
                array_push($queryArray, $query);

                include BASE_PATH."queries/".$preset."/placeCount.php";
                $query['query'] = $tempQuery;
                array_push($queryArray, $query);

                break;
            case 'projects2':
                $query = array('query' => "");
                include BASE_PATH."queries/".$preset."/findProjects.php";
                $query['query'] = $tempQuery;
                array_push($queryArray, $query);

                include BASE_PATH."queries/".$preset."/findPeople.php";
                $query['query'] = $tempQuery;
                array_push($queryArray, $query);

                include BASE_PATH."queries/".$preset."/findEvents.php";
                $query['query'] = $tempQuery;
                array_push($queryArray, $query);

                include BASE_PATH."queries/".$preset."/findPlaces.php";
                $query['query'] = $tempQuery;
                array_push($queryArray, $query);

                break;
            case 'stories':
                $query = array('query' => "");
                include BASE_PATH."queries/".$preset."/data.php";
                $query['query'] = $tempQuery;
                array_push($queryArray, $query);
                break;
            default:
                break;
        }
    }
    elseif (isset($_GET['query'])) {
        //Preset not supplied so query needs to be supplied instead
        $query = array(
            'query' => $_GET['query']
        );
        array_push($queryArray, $query);

        $preset = 'default';
    }
    else{
        return json_encode(["gridCard"=>array(), "tableCard" => array(), "total" => 0]);
    }

    $resultsArray = array();

    $first = true;
    $oneQuery = count($queryArray) == 1;    // count results differently when there is only one query

    foreach ($queryArray as $i => $query) {
        $result = blazegraphSearch($query);
        if(!$result) continue;

        $presetToCounterFunction = [
            'place' => 'queryPlaceCounter',
            'singleproject' => 'queryProjectsCounter'
        ];

        $count = 0;
        if(isset($presetToCounterFunction[$preset])){
            $count = $presetToCounterFunction[$preset]();
        }

        if ($first){
            $resultsArray = $result;
            $first = false;

            if ($oneQuery){ // this is needed for the search page counter to be working
                $record_total = $count;
            }
        } else {
            if($preset == 'singleproject'){
                //Get the count of all the results
                $record_total += $count;
            }
            else if ($preset != "projects2") {
                $resultsArray = array_merge($resultsArray, $result);
            }
            else {
                foreach ($result as $count) {
                    foreach ($resultsArray as $j => $project) {
                        if ($project['projectLabel']['value'] == $count['projectLabel']['value']) {
                            // how to tell which type it is? (person, place, event)
                            if ($i == 1) {
                                $resultsArray[$j]['personCount'] = $count['count']['value'];
                            }
                            else if ($i == 2) {
                                $resultsArray[$j]['eventCount'] = $count['count']['value'];
                            }
                            else if ($i == 3) {
                                $resultsArray[$j]['placeCount'] = $count['count']['value'];
                            }
                            break;
                        }
                    }
                }
            }
        }
    }
    //Get HTML for the cards
    return createCards($resultsArray, $templates, $preset, $record_total);
}


function blazegraphSearch($query){
    $ch = curl_init(BLAZEGRAPH_URL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        'User-Agent: Enslaved.org/Frontend',
        'Accept: application/sparql-results+json'
    ));
    $result = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($result, true)['results']['bindings'];
    return $result;
}


/**
 * Creates the HTML for type of cards specified in $templates
 *
 * \param $results : Array of results that the query returned
 * \param $templates : Array of the type of cards to make
 * \param $preset :
 */
function createCards($results, $templates, $select_fields, $preset = 'default', $count = 0){
    if (!is_array($results)){
        $results = array();
    }
    // var_dump($select_fields);
    $cards = Array();
    $formattedData = array();   // data formatted to be turned into csv

    foreach ($templates as $template) {
        $cards[$template] = array();
    }
    $cards['total'] = $count;

    // use same people display for people in single project
    if($preset == "singleproject") $preset = "people";

    $first = true;  // need to know if first to add table headers
    // print_r($preset);

    foreach ($results as $index => $record) {
        // var_dump($record);
        $record = $record['_source'];
        $card = '';
        $countpeople = '';
        if (array_key_exists('ref_people', $record))
            $countpeople = count($record['ref_people']);
        $countevent = '';
        if (array_key_exists('ref_event', $record))
            $countevent = count($record['ref_event']);
        $countplace = '';
        if (array_key_exists('ref_place', $record))
            $countplace = count($record['ref_place']);
        $countsource = '';
        if (array_key_exists('ref_source', $record))
            $countsource = count($record['ref_source']);
        switch ($preset){
            case 'people':
                //Person Name
                $name = $record['name'][0];
                //Person QID
                $personQ = $record['id'];
                $person_url = BASE_URL . "record/person/" . $personQ;

                //Person Sex
                $sex = "";
                if(is_array($record['sex']) && count($record['sex']) > 0) {
                    $sex = $record['sex'][0];
                }

                //Person Status
                $status = '';
                $statusCount = 0;
                if (is_array($record['person_status']) && count($record['person_status']) > 0){
                    $statusCount = count($record['person_status']);
                    $status = implode(', ', $record['person_status']);
                }

                //Person location
                $places = '';
                $placesCount = 0;
                if (is_array($record['display_place']) && count($record['display_place']) > 0) {
                    $places = implode(', ', $record['display_place']);
                    $placesCount = count($record['display_place']);
                }

                //Date Range
                $dateRange = '';
                if (is_array($record['display_date_range']) && count($record['display_date_range']) > 0)
                    $dateRange = $record['display_date_range'][0];

                //Connection HTML
                $connection_lists = Array(
                    '<h1>'.$countpeople.' Connected People</h1><ul><li>Person Name <span>(Wife)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Brother brother brother)</span> <div id="arrow"></div></li><li>Person Name <span>(Relation)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Father)</span> <div id="arrow"></div></li><li>Person Name <span>(Mother)</span> <div id="arrow"></div></li><li>View All People Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countplace.' Connected Places</h1><ul><li>Place Name <div id="arrow"></div></li><li>Place Name is Longer<div id="arrow"></div></li><li>Place Name <div id="arrow"></div></li><li>View All Place Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countevent.' Connected Events</h1><ul><li>Event Name <div id="arrow"></div></li><li>Event Name is Longer<div id="arrow"></div></li><li>Event Name <div id="arrow"></div></li><li>View All Event Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countsource.' Connected Sources</h1><ul><li>Source Name <div id="arrow"></div></li><li>Source Name is Longer<div id="arrow"></div></li><li>Source Name <div id="arrow"></div></li><li>View All Source Connections <div id="arrow"></div></li></ul>'
                );
                $connections = '<div class="connectionswrap"><p>Person\'s Connections</p><div class="connections">';
                	if (intval($countpeople) > 0){
                        $connections .= '<div class="card-icons"><img src="../assets/images/Person-dark.svg"><span>'.$countpeople.'</span><div class="connection-menu">'.$connection_lists[0].'</div></div>';
                    }
                    if (intval($countplace) > 0){
                        $connections .= '<div class="card-icons"><img src="../assets/images/Place-dark.svg"><span>'.$countplace.'</span><div class="connection-menu">'.$connection_lists[1].'</div></div>';
                    }
                    if (intval($countevent) > 0){
                        $connections .= '<div class="card-icons"><img src="../assets/images/Event-dark.svg"><span>'.$countevent.'</span><div class="connection-menu">'.$connection_lists[2].'</div></div>';
                    }
                    if (intval($countsource) > 0){
                        $connections .= '<div class="card-icons"><img src="../assets/images/Source-dark.svg"><span>'.$countsource.'</span><div class="connection-menu">'.$connection_lists[3].'</div></div>';
                    }
                $connections .= '</div></div>';

                // create the html for each template
                foreach ($templates as $template) {
                    if ($template == 'gridCard'){

                        $sexHtml = '';
                        if ($sex != ''){
                            $sexHtml = "<div class='detail'><p class='detail-title'>Sex</p><p>$sex</p></div>";
                        }

                        $statusHtml = '';
                        // if a person has multiple statuses, display them in a tooltip
                        if ($statusCount == 1){
                            $statusHtml = "<div class='detail'><p class='detail-title'>Person Status</p><p>$status</p></div>";
                        }
                        if ($statusCount > 1){
                            $statusHtml = "<div class='detail'><p class='detail-title'>Person Status</p><p class='multiple'>Multiple<span class='tooltip'><span class='head'>Multiple Statuses</span>$status</span></p></div>";
                        }

                        $placesHtml = '';
                        if ($placesCount == 1){
                            $placesHtml = "<div class='detail'><p class='detail-title'>Place</p><p>$places</p></div>";
                        }
                        if ($placesCount > 1){
                            $placesHtml = "<div class='detail'><p class='detail-title'>Place</p><p class='multiple'>Multiple<span class='tooltip'><span class='head'>Multiple Places</span>$places</span></p></div>";
                        }

                        $dateRangeHtml = '';
                        if ($dateRange != ''){
                            $dateName = strpos($dateRange, '-') ? "Date Range" : "Date";
                            $dateRangeHtml = "<div class='detail'><p class='detail-title'>$dateName</p><p>$dateRange</p></div>";
                        }

                        $card_icon_url = BASE_IMAGE_URL . 'Person.svg';

                        $card = <<<HTML
<li class="card">
    <a href='$person_url'>
        <div class='card-title'>
            <img src='$card_icon_url' alt="Card Icon">
            <h3>$name</h3>
        </div>
        <div class="details">
            $sexHtml
            $statusHtml
            $placesHtml
            $dateRangeHtml
        </div>
        $connections
    </a>
</li>
HTML;

                    } elseif ($template == 'tableCard'){
                        if ($first) {
                            $first = false;

                            $fields = [];
                            $headers = "<tr>";
                            foreach ($select_fields[0] as $field) {
                              $headers .= "<th class='" . strtolower($field) . "'>" . strtoupper($field) . "</th>";
                              array_push($fields, strtoupper($field));
                            }
                            $headers .= "</tr>";
                            $cards['tableCard']['headers'] = $headers;
                            $cards['fields'] = $fields;
                        }
// People page
                        $card = "<tr> class='tr' data-url='" . $person_url . "'>";
                        foreach ($select_fields[0] as $index => $field) {
                          if($field == "Name"){
                            $value = $record['name'][0];
                          }if($field == "Person Status"){
                            $value = $record['person_status'][0];
                          }if($field == "Role"){
                            $value = $record['participant_role'][0];
                          }if($field == "Event"){
                            $value = $record['event_type'][0];
                          }if($field == "Date"){
                            $value = $record['date'][0];
                          }if($field == "Place Type"){
                            $value = implode(', ', $record['place_type']);
                          }if($field == "Place"){
                            $value = implode(', ', $record['display_place']);
                          }if($field == "Source Type"){
                            $value = $record['source_type'][0];
                          }
                          $card .= "<td class='" . $field . "'><p><span class='first'>" . $field . ": </span>" . $value . "</p></td>";
                        }
                        $card .= "</tr>";
                        // var_dump($card);
                    // format this row for csv download
                    $formattedData[$personQ] = array(
                        'NAME' => $name,
                        'GENDER' => $sex,
                        'AGE' => '',
                        'STATUS' => $status,
                        'ORIGIN' => '',
                        'LOCATION' => $places,
                        'DATE RANGE' => $dateRange
                    );




                    }


                    array_push($cards[$template], $card);
                }

                break;
            case 'places':
                //Place name
                $name = $record['label'];

                //Place URL
                $placeQ = $record['id'];

                //Located In
                $locatedIn = "";
                if (is_array($record['located_in']) && count($record['located_in']) > 0)
                    $locatedIn = $record['located_in'][0];

                $placeType = '';
                if (is_array($record['place_type']) && count($record['place_type']) > 0)
                    $placeType = $record['place_type'][0];

                $geonames = '';
                if (is_array($record['geoname_id']) && count($record['geoname_id']) > 0)
                    $geonames = $record['geoname_id'][0];

                $code = '';
                if (is_array($record['modern_country_code']) && count($record['modern_country_code']) > 0)
                    $code = $record['modern_country_code'][0];

                $country = '';
                if ($code != '')
                    $country = countrycode[$code];

                //Connection html
                $connection_lists = Array(
                    '<h1>'.$countpeople.' Connected People</h1><ul><li>Person Name <span>(Wife)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Brother brother brother)</span> <div id="arrow"></div></li><li>Person Name <span>(Relation)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Father)</span> <div id="arrow"></div></li><li>Person Name <span>(Mother)</span> <div id="arrow"></div></li><li>View All People Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countplace.' Connected Places</h1><ul><li>Place Name <div id="arrow"></div></li><li>Place Name is Longer<div id="arrow"></div></li><li>Place Name <div id="arrow"></div></li><li>View All Place Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countevent.' Connected Events</h1><ul><li>Event Name <div id="arrow"></div></li><li>Event Name is Longer<div id="arrow"></div></li><li>Event Name <div id="arrow"></div></li><li>View All Event Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countsource.' Connected Sources</h1><ul><li>Source Name <div id="arrow"></div></li><li>Source Name is Longer<div id="arrow"></div></li><li>Source Name <div id="arrow"></div></li><li>View All Source Connections <div id="arrow"></div></li></ul>'
                );

                $connections = '<div class="connectionswrap"><p>Place\'s Connections</p><div class="connections">';
                	if (intval($countpeople) > 0){
                        $connections .= '<div class="card-icons"><img src="../assets/images/Person-dark.svg"><span>'.$countpeople.'</span><div class="connection-menu">'.$connection_lists[0].'</div></div>';
                    }
                    if (intval($countplace) > 0){
                        $connections .= '<div class="card-icons"><img src="../assets/images/Place-dark.svg"><span>'.$countplace.'</span><div class="connection-menu">'.$connection_lists[1].'</div></div>';
                    }
                    if (intval($countevent) > 0){
                        $connections .= '<div class="card-icons"><img src="../assets/images/Event-dark.svg"><span>'.$countevent.'</span><div class="connection-menu">'.$connection_lists[2].'</div></div>';
                    }
                    if (intval($countsource) > 0){
                        $connections .= '<div class="card-icons"><img src="../assets/images/Source-dark.svg"><span>'.$countsource.'</span><div class="connection-menu">'.$connection_lists[3].'</div></div>';
                    }
                $connections .= '</div></div>';


                // create the html for each template
                foreach ($templates as $template) {
                    if ($template == 'gridCard'){

                        $typeHtml = '';
                        if ($placeType != ''){
                            $typeHtml = "<div class='detail'><p class='detail-title'>Type</p><p>$placeType</p></div>";
                        }

                        $locatedInHtml = '';
                        if ($locatedIn != ''){
                            $locatedInHtml = "<div class='detail'><p class='detail-title'>Located In</p><p>$locatedIn</p></div>";
                        }

                        $geonamesHtml = '';
                        if ($geonames != ''){
                            $geonames = "<div class='detail'><p class='detail-title'>Geoname Identifier&nbsp;</p><p>$geonames</p></div>";
                        }

                        $countryHtml = '';
                        if ($country != ''){
                            $countryHtml = "<div class='detail'><p class='detail-title'>Modern Country</p><p>$country</p></div>";
                        }

                        $card_icon_url = BASE_IMAGE_URL . 'Place.svg';
                        $place_url = BASE_URL . "record/place/" . $placeQ;

                        $card = <<<HTML
<li class="card">
    <a href='$place_url'>
        <div class='card-title'>
            <img src='$card_icon_url' alt="Card Icon">
            <h3>$name</h3>
        </div>
        <div class="details">
            $typeHtml
            $locatedInHtml
            $geonamesHtml
            $countryHtml
        </div>
        $connections
    </a>
</li>
HTML;

                    } elseif ($template == 'tableCard'){
                        if ($first) {
                            //todo create the correct place headers
                            $first = false;

                            $fields = [];
                            $headers = "<tr>";
                            foreach ($select_fields[2] as $field) {
                              $headers .= "<th class='" . strtolower($field) . "'>" . strtoupper($field) . "</th>";
                              array_push($fields, strtoupper($field));
                            }
                            $headers .= "</tr>";
                            $cards['tableCard']['headers'] = $headers;
                            $cards['fields'] = $fields;
                        }

                        $card = "<tr> class='tr' data-url='" . $place_url . "'>";
                        foreach ($select_fields[2] as $index => $field) {
                          if($field == "Name"){
                            $value = $record['label'];
                          }if($field == "Database"){
                            $value = $record['generated_by'][0];
                          }if($field == "Location"){
                            $value = $record['located_in'][0];
                          }if($field == "Place Type"){
                            $value = $record['place_type'][0];
                          }
                          $card .= "<td class='" . $field . "'><p><span class='first'>" . $field . ": </span>" . $value . "</p></td>";
                        }
                        $card .= "</tr>";


                        // format this row for csv download
                        $formattedData[$placeQ] = array(
                            'NAME' => $name,
                            'TYPE' => $placeType,
                            'LOCATED IN' => $locatedIn,
                            'GEONAME' => $geonames,
                            'COUNTRY' => $country,
                        );
                    }


                    array_push($cards[$template], $card);
                }
                break;
            case 'events':
                //Event name
                $name = $record['label'];

                //Event URL
                $eventQ = $record['id'];

                //Event Type
                $type = 'Unidentified';
                if (is_array($record['event_type']) && count($record['event_type']) > 0)
                    $type = $record['event_type'][0];

                //Event Roles
                $roles = '';
                $rolesCount = 0;
                if (is_array($record['provides_participant_role']) && count($record['provides_participant_role']) > 0) {
                    $roles = implode(', ', $record['provides_participant_role']);
                    $rolesCount = count($record['provides_participant_role']);
                }

                // Event Places
                $places = '';
                $placesCount = 0;
                if (is_array($record['display_place']) && count($record['display_place']) > 0) {
                    $places = implode(', ', $record['display_place']);
                    $placesCount = count($record['display_place']);
                }

                //Event Start Year
                $startYear = '';
                if ($record['date'] != 0000) {
                    $startYear = $record['date'][0];
                }

                //Event End Year
                $endYear = '';
                if ($record['end_date'] != 0000) {
                    $endYear = $record['end_date'][0];
                }

                //Date range
                $dateRange = '';
                if ($startYear != '' && $endYear != ''){
                    $dateRange = "$startYear - $endYear";
                } elseif ($endYear == ''){
                    $dateRange = $startYear;
                } elseif ($startYear == '') {
                    $dateRange = $endYear;
                }

                //Connection html
                $connection_lists = Array(
                    '<h1>'.$countpeople.' Connected People</h1><ul><li>Person Name <span>(Wife)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Brother brother brother)</span> <div id="arrow"></div></li><li>Person Name <span>(Relation)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Father)</span> <div id="arrow"></div></li><li>Person Name <span>(Mother)</span> <div id="arrow"></div></li><li>View All People Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countplace.' Connected Places</h1><ul><li>Place Name <div id="arrow"></div></li><li>Place Name is Longer<div id="arrow"></div></li><li>Place Name <div id="arrow"></div></li><li>View All Place Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countsource.' Connected Sources</h1><ul><li>Source Name <div id="arrow"></div></li><li>Source Name is Longer<div id="arrow"></div></li><li>Source Name <div id="arrow"></div></li><li>View All Source Connections <div id="arrow"></div></li></ul>'
                );
                //'<h1>'.$countevent.' Connected Events</h1><ul><li>Event Name <div id="arrow"></div></li><li>Event Name is Longer<div id="arrow"></div></li><li>Event Name <div id="arrow"></div></li><li>View All Event Connections <div id="arrow"></div></li></ul>',
                $connections = '<div class="connectionswrap"><p>Event\'s Connections</p><div class="connections">';
                	if (intval($countpeople) > 0){
                        $connections .= '<div class="card-icons"><img src="../assets/images/Person-dark.svg"><span>'.$countpeople.'</span><div class="connection-menu">'.$connection_lists[0].'</div></div>';
                    }
                    if (intval($countplace) > 0){
                        $connections .= '<div class="card-icons"><img src="../assets/images/Place-dark.svg"><span>'.$countplace.'</span><div class="connection-menu">'.$connection_lists[1].'</div></div>';
                    }
                    // if (intval($countevent) > 0){
                    //     $connections .= '<div class="card-icons"><img src="../assets/images/Event-dark.svg"><span>'.$countevent.'</span><div class="connection-menu">'.$connection_lists[2].'</div></div>';
                    // }
                    if (intval($countsource) > 0){
                        $connections .= '<div class="card-icons"><img src="../assets/images/Source-dark.svg"><span>'.$countsource.'</span><div class="connection-menu">'.$connection_lists[2].'</div></div>';
                    }
                $connections .= '</div></div>';

                // create the html for each template
                foreach ($templates as $template) {
                    if ($template == 'gridCard'){

                        $typeHtml = "<div class='detail'><p class='detail-title'>Type</p><p>$type</p></div>";

                        $rolesHtml = '';
                        // Check for multiple roles
                        if ($rolesCount == 1){
                            $rolesHtml = "<div class='detail'><p class='detail-title'>Role</p><p>$roles</p></div>";
                        }
                        if ($rolesCount > 1){
                            $rolesHtml = "<div class='detail'><p class='detail-title'>Role</p><p class='multiple'>Multiple<span class='tooltip'><span class='head'>Multiple Roles</span>$roles</span></p></div>";
                        }
                        // Check for multiple places
                        $placesHtml = '';
                        if ($placesCount == 1){
                            $placesHtml = "<div class='detail'><p class='detail-title'>Place</p><p>$places</p></div>";
                        }
                        if ($placesCount > 1){
                            $placesHtml = "<div class='detail'><p class='detail-title'>Place</p><p class='multiple'>Multiple<span class='tooltip'><span class='head'>Multiple Places</span>$places</span></p></div>";
                        }

                        $dateRangeHtml = '';
                        if ($dateRange != ''){
                            $dateName = ($startYear != '' && $endYear != '') ? "Date Range" : "Date";
                            $dateRangeHtml = "<div class='detail'><p class='detail-title'>$dateName</p><p>$dateRange</p></div>";
                        }

                        $card_icon_url = BASE_IMAGE_URL . 'Event.svg';
                        $event_url = BASE_URL . "record/event/" . $eventQ;



                        $card = <<<HTML
<li class="card">
    <a href='$event_url'>
        <div class='card-title'>
            <img src='$card_icon_url' alt="Card Icon">
            <h3>$name</h3>
        </div>
        <div class="details">
            $typeHtml
            $rolesHtml
            $placesHtml
            $dateRangeHtml
        </div>
        $connections
    </a>
</li>
HTML;
                    } elseif ($template == 'tableCard'){
                        if ($first) {
                            //todo: create the correct event headers
                            $first = false;

                            $fields = [];
                            $headers = "<tr>";
                            foreach ($select_fields[1] as $field) {
                              $headers .= "<th class='" . strtolower($field) . "'>" . strtoupper($field) . "</th>";
                              array_push($fields, strtoupper($field));
                            }
                            $headers .= "</tr>";
                            $cards['tableCard']['headers'] = $headers;
                            $cards['fields'] = $fields;
                        }

                        $card = "<tr> class='tr' data-url='" . $event_url . "'>";
                        foreach ($select_fields[1] as $index => $field) {
                          if($field == "Name"){
                            $value = $record['label'];
                          }if($field == "Event Type"){
                            $value = $record['event_type'][0];
                          }if($field == "Source Type"){
                            $value = $record['source_type'][0];
                          }if($field == "Date Range"){
                            $value = $record['display_date_range'][0];
                          }if($field == "Place Type"){
                            $value = $record['place_type'][0];
                          }if($field == "Place"){
                            $value = $record['display_place'][0];
                          }
                          $card .= "<td class='" . $field . "'><p><span class='first'>" . $field . ": </span>" . $value . "</p></td>";
                        }
                        $card .= "</tr>";
                        // format this row for csv download
                        $formattedData[$eventQ] = array(
                            'NAME' => $name,
                            'TYPE' => $type,
                            'PLACES' => $places,
                            'DATE RANGE' => $dateRange
                        );
                    }


                    array_push($cards[$template], $card);
                }
                break;
            case 'sources':
                //Source name
                $name = $record['label'];

                //Source URL
                $sourceQ = $record['id'];

                //Source Type
                $type = "Unidentified";
                $typeCount = 0;
                if (is_array($record['source_type']) && count($record['source_type']) > 0) {
                    $type = implode(' ,', $record['source_type']);
                    $typeCount = count($record['source_type']);
                }

                //Source Project
                $project = "";
                if (is_array($record['generated_by']) && count($record['generated_by']) > 0)
                    $project = $record['generated_by'][0];

                // description (not capturing this currently)
                if(isset($record['desc']) && isset($record['desc']['value'])){
                    $desc = $record['desc']['value'];
                } else {
                    $desc = '';
                }

                //Connection html
                $connection_lists = Array(
                    '<h1>'.$countpeople.' Connected People</h1><ul><li>Person Name <span>(Wife)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Brother brother brother)</span> <div id="arrow"></div></li><li>Person Name <span>(Relation)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Father)</span> <div id="arrow"></div></li><li>Person Name <span>(Mother)</span> <div id="arrow"></div></li><li>View All People Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countplace.' Connected Places</h1><ul><li>Place Name <div id="arrow"></div></li><li>Place Name is Longer<div id="arrow"></div></li><li>Place Name <div id="arrow"></div></li><li>View All Place Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countevent.' Connected Events</h1><ul><li>Event Name <div id="arrow"></div></li><li>Event Name is Longer<div id="arrow"></div></li><li>Event Name <div id="arrow"></div></li><li>View All Event Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countsource.' Connected Sources</h1><ul><li>Source Name <div id="arrow"></div></li><li>Source Name is Longer<div id="arrow"></div></li><li>Source Name <div id="arrow"></div></li><li>View All Source Connections <div id="arrow"></div></li></ul>'
                );

                $connections = '<div class="connectionswrap"><p>Source\'s Connections</p><div class="connections">';
                	if (intval($countpeople) > 0){
                        $connections .= '<div class="card-icons"><img src="../assets/images/Person-dark.svg"><span>'.$countpeople.'</span><div class="connection-menu">'.$connection_lists[0].'</div></div>';
                    }
                    if (intval($countplace) > 0){
                        $connections .= '<div class="card-icons"><img src="../assets/images/Place-dark.svg"><span>'.$countplace.'</span><div class="connection-menu">'.$connection_lists[1].'</div></div>';
                    }
                    if (intval($countevent) > 0){
                        $connections .= '<div class="card-icons"><img src="../assets/images/Event-dark.svg"><span>'.$countevent.'</span><div class="connection-menu">'.$connection_lists[2].'</div></div>';
                    }
                    // if (intval($countsource) > 0){
                    //     $connections .= '<div class="card-icons"><img src="../assets/images/Source-dark.svg"><span>'.$countsource.'</span><div class="connection-menu">'.$connection_lists[3].'</div></div>';
                    // }
                $connections .= '</div></div>';


                // create the html for each template
                foreach ($templates as $template) {
                    if ($template == 'gridCard'){

                        $typeHtml = '';
                        // if a source has multiple types, display them in a tooltip
                        if ($typeCount == 1){
                            $typeHtml = "<div class='detail'><p class='detail-title'>Type</p><p>$type</p></div>";
                        }
                        if ($typeCount > 1){
                            $typeHtml = "<div class='detail'><p class='detail-title'>Type</p><p class='multiple'>Multiple<span class='tooltip'><span class='head'>Multiple Types</span>$type</span></p></div>";
                        }

                        $projectHtml = '';
                        if ($project != ""){
                            $projectHtml = "<div class='detail'><p class='detail-title'>Project</p><p>$project</p></div>";
                        }

                        $card_icon_url = BASE_IMAGE_URL . 'Source.svg';
                        $source_url = BASE_URL . "record/source/" . $sourceQ;

                        $card = <<<HTML
<li class="card">
    <a href='$source_url'>
        <div class='card-title'>
            <img src='$card_icon_url' alt="Card Icon">
            <h3>$name</h3>
        </div>
        <div class="details">
            $typeHtml
            $projectHtml
        </div>
        $connections
    </a>
</li>
HTML;

                    } elseif ($template == 'tableCard'){
                        if ($first) {
                            $first = false;

                            $fields = [];
                            $headers = "<tr>";
                            foreach ($select_fields[3] as $field) {
                              $headers .= "<th class='" . strtolower($field) . "'>" . strtoupper($field) . "</th>";
                              array_push($fields, strtoupper($field));
                            }
                            $headers .= "</tr>";
                            $cards['tableCard']['headers'] = $headers;
                            $cards['fields'] = $fields;
                        }

                        $card = "<tr> class='tr' data-url='" . $source_url . "'>";
                        foreach ($select_fields[3] as $index => $field) {
                          if($field == "Name"){
                            $value = $record['name'][0];
                          }if($field == "Database"){
                            $value = $record['generated_by'][0];
                          }
                          $card .= "<td class='" . $field . "'><p><span class='first'>" . $field . ": </span>" . $value . "</p></td>";
                        }
                        $card .= "</tr>";

                        // format this row for csv download
                        $formattedData[$sourceQ] = array(
                            'NAME' => $name,
                            'TYPE' => $type,
                            'PROJECT' => $project
                        );

                    }


                    array_push($cards[$template], $card);
                }
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


                foreach ($templates as $template) {

                    if ($template == 'homeCard') {
                        $card = "<li>
                    <a href='".BASE_URL."fullStory/'>
                        <div class='container cards'>
                            <p class='card-title'>$fullName</p>
                            <h4 class='card-view-story'>View Story <div class='view-arrow'></h4>
                        </div>
                    </a>
                </li>";

                    } else continue;

                    array_push($cards[$template], $card);
                }

                break;
            case 'projectassoc':
                if (isset($record['agentcount'])) {
                    $card = '<div class="card-icon">
                        <img src="'.BASE_IMAGE_URL.'Person-light.svg" alt="Card Icon"/>
                        <span>'.$record['agentcount']['value'].'</span>
                    </div>';
                }
                else if (isset($record['placecount'])) {
                    $card = '<div class="card-icon">
                        <img src="'.BASE_IMAGE_URL.'Place-light.svg" alt="Card Icon"/>
                        <span>'.$record['placecount']['value'].'</span>
                    </div>';
                }
                else if (isset($record['eventcount'])) {
                    $card = '<div class="card-icon">
                        <img src="'.BASE_IMAGE_URL.'Event-light.svg" alt="Card Icon"/>
                        <span>'.$record['eventcount']['value'].'</span>
                    </div>';
                }
                array_push($cards['projectassoc'], $card);
                break;
            case 'projects2':
                $fullName = $record['projectLabel']['value'];
                $connections = "";
                if (isset($record['personCount'])) {
                    $connections .= '<div class="card-icon">
                        <img src="'.BASE_IMAGE_URL.'Person-light.svg" alt="Card Icon"/>
                        <span>'.$record['personCount'].'</span>
                    </div>';
                }
                if (isset($record['placeCount'])) {
                    $connections .= '<div class="card-icon">
                        <img src="'.BASE_IMAGE_URL.'Place-light.svg" alt="Card Icon"/>
                        <span>'.$record['placeCount'].'</span>
                    </div>';
                }
                if (isset($record['eventCount'])) {
                    $connections .= '<div class="card-icon">
                        <img src="'.BASE_IMAGE_URL.'Event-light.svg" alt="Card Icon"/>
                        <span>'.$record['eventCount'].'</span>
                    </div>';
                }
                $project = array_reverse(explode('/', $record['project']['value']))[0];
                foreach ($templates as $template) {
                    if ($template == 'homeCard') {
                        $card = "<li class='card'>
                        <a href='".BASE_URL."project/$project'>
                            <h2 class='card-title'>$fullName</h2>
                            <div class='connectionswrap'>
                                <div class='connections'>
                                    $connections
                                </div>
                            </div>
                        </a>
                    </li>";
                    }
                    array_push($cards[$template], $card);
                }
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


                foreach ($templates as $template) {

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

                    array_push($cards[$template], $card);
                }
                break;
            case 'featured':
                foreach ($templates as $template) {
                    $cardTitle = '';
                    $qid = $record['id'];
                    if($template == 'Person'){
                        $cardTitle = $record['name'][0];
                    } else if($template == 'Event'){
                        $cardTitle = $record['label'];
                    }

                    $countpeople = '1';
                    $countplace = '1';
                    $countevent = '1';
                    $countsource = '1';
                    //Connection html
                    $connection_lists = Array(
                        '<h1>'.$countpeople.' Connected People</h1><ul><li>Person Name <span>(Wife)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Brother brother brother)</span> <div id="arrow"></div></li><li>Person Name <span>(Relation)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Father)</span> <div id="arrow"></div></li><li>Person Name <span>(Mother)</span> <div id="arrow"></div></li><li>View All People Connections <div id="arrow"></div></li></ul>',
                        '<h1>'.$countplace.' Connected Places</h1><ul><li>Place Name <div id="arrow"></div></li><li>Place Name is Longer<div id="arrow"></div></li><li>Place Name <div id="arrow"></div></li><li>View All Place Connections <div id="arrow"></div></li></ul>',
                        '<h1>'.$countevent.' Connected Events</h1><ul><li>Event Name <div id="arrow"></div></li><li>Event Name is Longer<div id="arrow"></div></li><li>Event Name <div id="arrow"></div></li><li>View All Event Connections <div id="arrow"></div></li></ul>',
                        '<h1>'.$countsource.' Connected Sources</h1><ul><li>Source Name <div id="arrow"></div></li><li>Source Name is Longer<div id="arrow"></div></li><li>Source Name <div id="arrow"></div></li><li>View All Source Connections <div id="arrow"></div></li></ul>'
                    );

                    $connections = '<div class="connectionswrap"><p>'.$template.'\'s Connections</p><div class="connections">';
                    	// if (intval($countpeople) > 0){
                        //     $connections .= '<div class="card-icons"><img src="../assets/images/Person-dark.svg"><span>'.$countpeople.'</span><div class="connection-menu">'.$connection_lists[0].'</div></div>';
                        // }
                        if (intval($countplace) > 0){
                            $connections .= '<div class="card-icons"><img src="../assets/images/Place-dark.svg"><span>'.$countplace.'</span><div class="connection-menu">'.$connection_lists[1].'</div></div>';
                        }
                        if (intval($countevent) > 0){
                            $connections .= '<div class="card-icons"><img src="../assets/images/Event-dark.svg"><span>'.$countevent.'</span><div class="connection-menu">'.$connection_lists[2].'</div></div>';
                        }
                        if (intval($countsource) > 0){
                            $connections .= '<div class="card-icons"><img src="../assets/images/Source-dark.svg"><span>'.$countsource.'</span><div class="connection-menu">'.$connection_lists[3].'</div></div>';
                        }
                    $connections .= '</div></div>';


                    $cardType = $template;
                    $iconURL = BASE_IMAGE_URL . $template . "-dark.svg";
                    $link = BASE_URL . "record/" . strtolower($cardType) . "/" . $qid;
                    // $background = "background-image: url(" . BASE_IMAGE_URL . $cardType . "Card.jpg)";
                    $card = <<<HTML
<li class="card card-featured">
    <a href="$link">
        <div class="card-title">
            <img src="$iconURL" alt="Card Icon">
            <h3>$cardTitle</h3>
        </div>
    </a>
</li>
HTML;

                    array_push($cards[$template], $card);
                }
                break;
            default:
                print_r('Error');
                die;
                break;
        }

    }

    $cards['formatted_data'] = $formattedData;


    return json_encode($cards);
}

?>
