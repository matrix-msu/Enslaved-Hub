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


function searchTermParser($filters){
    $terms = $filters['searchbar'];
    unset($filters['searchbar']);

    foreach ($terms as $term) {
        // skip words that are not important
        if (in_array($term, stopwords)){
            continue;
        }

        $found = false;
        $termLength = strlen($term);

        if (ctype_digit($term)){    // check if it's an int
            $filters['date'][] = $term."-";
            continue;
        }

        foreach ($GLOBALS['FILTER_TO_FILE_MAP'] as $type => $constantsArray) {
            if ($type == 'Modern Countries'){   // for countries we want to check the value not the keys
                $countries = array_values($constantsArray);
                foreach ($countries as $countryName) {
                    if ($termLength <= 3){
                        $position = (strtolower($countryName) == strtolower($term));
                    } else {
                        $position = stripos($countryName, $term);
                    }
                    if ($position !== false){
                        $found = true;
                        // echo 'found '.$term.' in the '.$type.' array. the match was with '.$key;
                        // map the file type to a known filter and add it to the $filters array
                        $filterType = strtolower(str_replace(' ', '_', $type));
                        if (!isset($filters[$filterType]) || !in_array($countryName, $filters[$filterType])){
                            $filters[$filterType][] = $countryName;
                        }
                    }
                }
            } else {
                $keys = array_keys($constantsArray);
                foreach ($keys as $key) {
                    if ($termLength <= 3 || $type == "Gender"){
                        $position = (strtolower($key) == strtolower($term));
                    } else {
                        $position = stripos($key, $term);
                    }
                    if ($position !== false){
                        $found = true;
                        // echo 'found '.$term.' in the '.$type.' array. the match was with '.$key;die;
                        // map the file type to a known filter and add it to the $filters array
                        $filterType = strtolower(str_replace(' ', '_', $type));
                        if (!isset($filters[$filterType]) || !in_array($key, $filters[$filterType])){
                            $filters[$filterType][] = $key;
                        }
                    }
                }
            }
        }

        if (!$found){
            // echo "there were no matches, $term must be a name";
            if (!isset($filters['name']) || !in_array($term, $filters['name'])){
                $filters['name'][] = $term;
            }
        }
    }

    return $filters;
}


// create filters for queries - doing this in a function so it can also be used for search filter counters
// within filter group - OR logic
// different filter group filters - AND logic
function createQueryFilters($searchType, $filters)
{
    include BASE_LIB_PATH."variableIncluder.php";
    $queryFilters = "";

    if (isset($filters["searchbar"])){
        $filters = searchTermParser($filters);
    }

    foreach ($filters as $filterType => $filterValues) {
        if ($filterType == "limit" || $filterType == "offset" || !is_array($filterValues)) continue;

        $filterCount = count($filterValues) - 1;

        foreach ($filterValues as $index => $value) {
            switch ($searchType) {
                case 'people':  // people filters
                {
                    if ($filterType == "name"){
                        $queryFilters .= "?agent $wdt:$hasName ?name.
                            FILTER regex(?name, '$value', 'i') .
                            ";
                    }

                    if ($filterType == "gender"){
                        if (array_key_exists($value, sexTypes)){
                            $qGender = sexTypes[$value];
                            if ($index == 0){
                                $queryFilters .= "?agent $wdt:$hasSex ?sex
                                    VALUES ?sex { $wd:$qGender ";
                            } else {
                                $queryFilters .= "$wd:$qGender ";
                            }
                            if ($index >= $filterCount) {
                                $queryFilters .= "} .
                                ";
                            }
                        }
                    }

                    if ($filterType == "age_category"){
                        if (array_key_exists($value, ageCategory)){
                            $qAge = ageCategory[$value];

                            if ($index == 0){
                                $queryFilters .= "?agent $wdt:$hasAgeCategory ?agecat
                                    VALUES ?agecat { $wd:$qAge ";
                            } else {
                                $queryFilters .= "$wd:$qAge ";
                            }
                            if ($index >= $filterCount) {
                                $queryFilters .= "} .
                                ";
                            }
                        }
                    }

                    if ($filterType == "ethnodescriptor"){
                        if (array_key_exists($value, ethnodescriptor)){
                            $qEthno = ethnodescriptor[$value];

                            if ($index == 0){
                                $queryFilters .= "?agent $wdt:$hasECVO ?ecvo
                                    VALUES ?ecvo { $wd:$qEthno ";
                            } else {
                                $queryFilters .= "$wd:$qEthno ";
                            }
                            if ($index >= $filterCount) {
                                $queryFilters .= "} .
                                ";
                            }
                        }
                    }

                    if ($filterType == "role_types"){
                        if (array_key_exists($value, roleTypes)){
                            $qRole = roleTypes[$value];

                            if ($index == 0){
                                $queryFilters .= "?agent $wdt:$hasParticipantRole ?roleType
                                    VALUES ?roleType { $wd:$qRole ";
                            } else {
                                $queryFilters .= "$wd:$qRole ";
                            }
                            if ($index >= $filterCount) {
                                $queryFilters .= "} .
                                ";
                            }
                        }
                    }

                    if ($filterType == "status"){
                        if (array_key_exists($value, personstatus)){
                            $qStatus = personstatus[$value];

                            if ($index == 0){
                                $queryFilters .= "?agent $wdt:$hasPersonStatus ?status
                                    VALUES ?status { $wd:$qStatus ";
                            } else {
                                $queryFilters .= "$wd:$qStatus ";
                            }
                            if ($index >= $filterCount) {
                                $queryFilters .= "} .
                                ";
                            }
                        }
                    }

                    if ($filterType == "occupation"){
                        if (array_key_exists($occupation, occupation)){
                            $qOccupation = occupation[$occupation];

                            if ($index == 0){
                                $queryFilters .= "?agent $wdt:$hasOccupation ?occupation
                                    VALUES ?occupation { $wd:$qOccupation ";
                            } else {
                                $queryFilters .= "$wd:$qOccupation ";
                            }
                            if ($index >= $filterCount) {
                                $queryFilters .= "} .
                                ";
                            }
                        }
                    }

                    if ($filterType == "event_type"){
                        if (array_key_exists($value, eventTypes)){
                            $qType = eventTypes[$value];

                            if ($index == 0){
                                $queryFilters .= "?agent $p:$hasParticipantRole ?statementrole.
                                    ?statementrole $ps:$hasParticipantRole ?role.
                                    ?statementrole $pq:$roleProvidedBy ?event.
                                    ?event $wdt:$hasEventType ?eventType
                                    VALUES ?eventType { $wd:$qType ";
                            } else {
                                $queryFilters .= "$wd:$qType ";
                            }
                            if ($index >= $filterCount) {
                                $queryFilters .= "} .
                                ";
                            }
                        }
                    }

                    if ($filterType == "date"){
                        $dateRange = $value;
                        $dateArr = explode('-', $dateRange);
                        $from = '';
                        if (isset($dateArr[0])){
                            $from = $dateArr[0];
                        }
                        $to = '';
                        if (isset($dateArr[1])){
                            $to = $dateArr[1];
                        }

                        $queryFilters .= "?agent $p:$hasParticipantRole ?statementrole.
                            ?statementrole $ps:$hasParticipantRole ?role.
                            ?statementrole $pq:$roleProvidedBy ?event.
                            ";

                        if ($from != ''){
                            $queryFilters .= "
                                ?event $wdt:$startsAt ?startYear.
                                FILTER (?startYear >= \"".$from."-01-01T00:00:00Z"."\"^^xsd:dateTime) .
                            ";
                        }
                        if ($to != ''){
                            $queryFilters .= "
                                ?event $wdt:$endsAt ?endYear.
                                FILTER (?endYear <= \"".$to."-01-01T00:00:00Z"."\"^^xsd:dateTime) .
                            ";
                        }
                    }

                    if ($filterType == "place_type"){
                        if (array_key_exists($value, placeTypes)){
                            $qType = placeTypes[$value];

                            if ($index == 0){
                                $queryFilters .= "?agent $p:$hasParticipantRole ?statementrole.
                                ?statementrole $ps:$hasParticipantRole ?role.
                                ?statementrole $pq:$roleProvidedBy ?event.
                                ?event $wdt:$atPlace ?place.
                                ?place $wdt:$instanceOf $wd:$place;
                                $wdt:$hasPlaceType ?placeType
                                VALUES ?placetype { $wd:$qType ";
                            } else {
                                $queryFilters .= "$wd:$qType ";
                            }
                            if ($index >= $filterCount) {
                                $queryFilters .= "} .
                                ";
                            }
                        }
                    }

                    if ($filterType == "modern_countries"){
                        $code = array_search(strtolower($value), array_map('strtolower', countrycode));
                        if ($code){
                            if ($index == 0){
                                $queryFilters .= "?agent $p:$hasParticipantRole ?statementrole.
                                ?statementrole $ps:$hasParticipantRole ?role.
                                ?statementrole $pq:$roleProvidedBy ?event.
                                ?event $wdt:$atPlace ?place.
                                ?place $wdt:$instanceOf $wd:$place;
                                $wdt:$modernCountryCode ?countryCode
                                VALUES ?countryCode { \"$code\" ";
                            } else {
                                $queryFilters .= "\"$code\" ";
                            }
                            if ($index >= $filterCount) {
                                $queryFilters .= "} .
                                ";
                            }
                        }
                    }

                    if ($filterType == "source_type"){
                        if (array_key_exists($value, sourceTypes)){
                            $qType = sourceTypes[$value];

                            if ($index == 0){
                                $queryFilters .= "?agent ?property  ?object .
                                    ?object $prov:wasDerivedFrom ?provenance .
                                    ?provenance $pr:$isDirectlyBasedOn ?source .
                                    ?source $wdt:$hasOriginalSourceType ?sourceType
                                VALUES ?sourceType { $wd:$qType ";
                            } else {
                                $queryFilters .= "$wd:$qType ";
                            }
                            if ($index >= $filterCount) {
                                $queryFilters .= "} .
                                ";
                            }
                        }
                    }

                    if ($filterType == "projects"){
                        if (array_key_exists($value, projects)){
                            $projectQ = projects[$value];

                            if ($index == 0){
                                $queryFilters .= "?agent ?property  ?object .
                                    ?object $prov:wasDerivedFrom ?provenance .
                                    ?provenance $pr:$isDirectlyBasedOn ?source .
                                    ?source $wdt:$generatedBy ?project
                                    VALUES ?project { $wd:$projectQ ";
                            } else {
                                $queryFilters .= "$wd:$projectQ ";
                            }
                            if ($index >= $filterCount) {
                                $queryFilters .= "} .
                                ";
                            }
                        }
                    }
                }
                    break;
                case 'events':  // events filters
                {
                    if ($filterType == "name"){
                        $queryFilters .= "?event $wdt:$hasName ?eventName.
                            FILTER regex(?eventName, '$value', 'i') .
                            ";
                    }

                    if ($filterType == "event_type"){
                        if (array_key_exists($value, eventTypes)){
                            $qType = eventTypes[$value];

                            if ($index == 0){
                                $queryFilters .= "?event $wdt:$hasEventType ?eventType
                                    VALUES ?eventType { $wd:$qType ";
                            } else {
                                $queryFilters .= "$wd:$qType ";
                            }
                            if ($index >= $filterCount) {
                                $queryFilters .= "} .
                                ";
                            }
                        }
                    }

                    if ($filterType == "date"){
                        $dateRange = $value;
                        $dateArr = explode('-', $dateRange);
                        $from = '';
                        if (isset($dateArr[0])){
                            $from = $dateArr[0];
                        }
                        $to = '';
                        if (isset($dateArr[1])){
                            $to = $dateArr[1];
                        }
                        if ($from != ''){
                            $queryFilters .= "
                                ?event $wdt:$startsAt ?startYear.
                                FILTER (?startYear >= \"".$from."-01-01T00:00:00Z"."\"^^xsd:dateTime) .
                            ";
                        }
                        if ($to != ''){
                            $queryFilters .= "
                                ?event $wdt:$endsAt ?endYear.
                                FILTER (?endYear <= \"".$to."-01-01T00:00:00Z"."\"^^xsd:dateTime) .
                            ";
                        }
                    }

                    if ($filterType == "place_type"){
                        if (array_key_exists($value, placeTypes)){
                            $qType = placeTypes[$value];

                            if ($index == 0){
                                $queryFilters .= "?event $wdt:$atPlace ?place.
                                    ?place $wdt:$instanceOf $wd:$place;
                                    $wdt:$hasPlaceType ?placeType
                                    VALUES ?placetype { $wd:$qType ";
                            } else {
                                $queryFilters .= "$wd:$qType ";
                            }
                            if ($index >= $filterCount) {
                                $queryFilters .= "} .
                                ";
                            }
                        }
                    }

                    if ($filterType == "modern_countries"){
                        $code = array_search(strtolower($value), array_map('strtolower', countrycode));
                        if ($code){
                            if ($index == 0){
                                $queryFilters .= "?event $wdt:$atPlace ?place.
                                    ?place $wdt:$instanceOf $wd:$place;
                                    $wdt:$modernCountryCode ?countryCode
                                    VALUES ?countryCode { \"$code\" ";
                            } else {
                                $queryFilters .= "\"$code\" ";
                            }
                            if ($index >= $filterCount) {
                                $queryFilters .= "} .
                                ";
                            }
                        }
                    }

                    if ($filterType == "source_type"){
                        if (array_key_exists($value, sourceTypes)){
                            $qType = sourceTypes[$value];

                            if ($index == 0){
                                $queryFilters .= "
                                    ?event $wdt:$instanceOf $wd:$event;
                                    ?property  ?object .
                                    ?object prov:wasDerivedFrom ?provenance .
                                    ?provenance $pr:$isDirectlyBasedOn ?source.
                                    ?source $wdt:$hasOriginalSourceType ?sourceType
                                    VALUES ?sourceType { $wd:$qType ";
                            } else {
                                $queryFilters .= "$wd:$qType ";
                            }
                            if ($index >= $filterCount) {
                                $queryFilters .= "} .
                                ";
                            }
                        }
                    }

                    if ($filterType == "projects"){
                        if (array_key_exists($value, projects)){
                            $projectQ = projects[$value];

                            if ($index == 0){
                                $queryFilters .= "
                                    ?event $wdt:$instanceOf $wd:$event;
                                    ?property  ?object .
                                    ?object prov:wasDerivedFrom ?provenance .
                                    ?provenance $pr:$isDirectlyBasedOn ?source.
                                    ?source $wdt:$generatedBy ?project.
                                    VALUES ?project { $wd:$projectQ ";
                            } else {
                                $queryFilters .= "$wd:$projectQ ";
                            }
                            if ($index >= $filterCount) {
                                $queryFilters .= "} .
                                ";
                            }
                        }
                    }
                }
                    break;
                case 'places':  // places filters
                {
                    if ($filterType == "name"){
                        $queryFilters .= "?place $wdt:$hasName ?placeName.
                            FILTER regex(?placeName, '$value', 'i') .
                            ";
                    }

                    if ($filterType == "place_type"){
                        if (array_key_exists($value, placeTypes)){
                            $qType = placeTypes[$value];

                            if ($index == 0){
                                $queryFilters .= "VALUES ?type { $wd:$qType ";
                            } else {
                                $queryFilters .= "$wd:$qType ";
                            }
                            if ($index >= $filterCount) {
                                $queryFilters .= "} .
                                ";
                            }
                        }
                    }

                    if ($filterType == "modern_countries"){
                        $code = array_search(strtolower($value), array_map('strtolower', countrycode));
                        if ($code){
                            if ($index == 0){
                                $queryFilters .= "?place $wdt:$modernCountryCode ?countryCode
                                VALUES ?countryCode { \"$code\" ";
                            } else {
                                $queryFilters .= "\"$code\" ";
                            }
                            if ($index >= $filterCount) {
                                $queryFilters .= "} .
                                ";
                            }
                        }
                    }

                    if ($filterType == "source_type"){
                        if (array_key_exists($value, sourceTypes)){
                            $qType = sourceTypes[$value];

                            if ($index == 0){
                                $queryFilters .= "
                                    ?place $wdt:$instanceOf $wd:$place;
                                    ?property  ?object .
                                    ?object prov:wasDerivedFrom ?provenance .
                                    ?provenance $pr:$isDirectlyBasedOn ?source.
                                    ?source $wdt:$hasOriginalSourceType ?sourceType
                                    VALUES ?sourceType { $wd:$qType ";
                            } else {
                                $queryFilters .= "$wd:$qType ";
                            }
                            if ($index >= $filterCount) {
                                $queryFilters .= "} .
                                ";
                            }
                        }
                    }

                    if ($filterType == "projects"){
                        if (array_key_exists($value, projects)){
                            $projectQ = projects[$value];

                            if ($index == 0){
                                $queryFilters .= "
                                    ?place $wdt:$instanceOf $wd:$place;
                                    ?property  ?object .
                                    ?object prov:wasDerivedFrom ?provenance .
                                    ?provenance $pr:$isDirectlyBasedOn ?source.
                                    ?source $wdt:$generatedBy ?project.
                                    VALUES ?project { $wd:$projectQ ";
                            } else {
                                $queryFilters .= "$wd:$projectQ ";
                            }
                            if ($index >= $filterCount) {
                                $queryFilters .= "} .
                                ";
                            }
                        }
                    }
                }
                    break;
                case 'sources': // sources filters
                {
                    if ($filterType == "name"){
                        $queryFilters .= "?source $wdt:$hasName ?sourceName.
                            FILTER regex(?sourceName, '$value', 'i') .
                            ";
                    }

                    if ($filterType == "source_type"){
                        if (array_key_exists($value, sourceTypes)){
                            $qType = sourceTypes[$value];

                            if ($index == 0){
                                $queryFilters .= "?source $wdt:$hasOriginalSourceType ?sourceType
                                    VALUES ?sourceType { $wd:$qType ";
                            } else {
                                $queryFilters .= "$wd:$qType ";
                            }
                            if ($index >= $filterCount) {
                                $queryFilters .= "} .
                                ";
                            }
                        }
                    }

                    if ($filterType == "projects"){
                        if (array_key_exists($value, projects)){
                            $projectQ = projects[$value];

                            if ($index == 0){
                                $queryFilters .= "?source $wdt:$generatedBy ?project
                                    VALUES ?project { $wd:$projectQ ";
                            } else {
                                $queryFilters .= "$wd:$projectQ ";
                            }
                            if ($index >= $filterCount) {
                                $queryFilters .= "} .
                                ";
                            }
                        }
                    }
                }
                    break;
                default:
                    // code...
                    break;
            }
        }
    }
return $queryFilters;
}

function getKeywordSearchCounters($filters){
    include BASE_LIB_PATH."variableIncluder.php";

    $peopleFilters = createQueryFilters("people", $filters);
    $eventFilters = createQueryFilters("events", $filters);
    $placeFilters = createQueryFilters("places", $filters);
    $sourceFilters = createQueryFilters("sources", $filters);

    include BASE_PATH."queries/keywordSearch/counters.php";
    $query['query'] = $tempQuery;
    $result = blazegraphSearch($query);
    return json_encode($result[0]);
}

function blazegraph()
{
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
    $isKeywordSearch = false;

    if (isset($_GET['preset'])) {
        $preset = $_GET['preset'];
        include BASE_LIB_PATH."variableIncluder.php";

        if ($preset == 'all'){
            $isKeywordSearch = true;
            if (isset($_GET['display'])){
                $preset = $_GET['display'];
            }
            // return keywordSearch($filtersArray);
        }




        $queryFilters = createQueryFilters($preset, $filtersArray);

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

            case 'people':
                //filter by source id
                $sourceIdFilter = "";
                if (isset($filtersArray['source']) && $filtersArray['source'] != ''){
                    $sourceQ = $filtersArray['source'][0];
                    $sourceIdFilter = "VALUES ?source { $wd:$sourceQ} #Q number needs to be changed for every source.
                                    ?source $wdt:$instanceOf $wd:$entityWithProvenance.
                                    ?agent $wdt:$instanceOf/$wdt:$subclassOf $wd:$agent; #agent or subclass of agent
                                            ?property  ?object .
                                    ?object $prov:wasDerivedFrom ?provenance .
                                    ?provenance $pr:$isDirectlyBasedOn ?source .";
                }

                // people connected to an event id
                $eventIdFilter = "";
                if (isset($filtersArray['event']) && $filtersArray['event'] != ''){
                    $eventQ = $filtersArray['event'][0];
                    $eventIdFilter = "
                        VALUES ?event { $wd:$eventQ} #Q number needs to be changed for every event.
                        ?event $wdt:$instanceOf $wd:$event.
                        ?event $p:$providesParticipantRole ?statement.
                        ?statement $ps:$providesParticipantRole ?personname.
                        ?statement $pq:$hasParticipantRole ?agent.
                        ?agent $rdfs:label ?name.
                    ";
                }

                // filter people by place id
                $placeIdFilter = "";
                if (isset($filtersArray['place']) && isset($filtersArray['place'][0]) ){
                    $placeQ = $filtersArray['place'][0];
                    $placeIdFilter .= "
                            ?agent $p:$hasParticipantRole ?statementrole.
                            ?statementrole $ps:$hasParticipantRole ?role.
                            ?statementrole $pq:$roleProvidedBy ?event.
                            ?event $wdt:$atPlace $wd:$placeQ .   #this number will change for every place
                        ";
                }

                break;
            case 'places':
                //filter by source
                $sourceIdFilter = "";
                if (isset($filtersArray['source']) && $filtersArray['source'] != ''){
                    $sourceQ = $filtersArray['source'][0];
                    $sourceIdFilter = "VALUES ?source { $wd:$sourceQ} #Q number needs to be changed for every source.
                                        ?source $wdt:$reportsOn ?event.
                                        ?event $wdt:$atPlace ?place.
                                        ?place $rdfs:label ?placelabel . ";
                }
                break;
            case 'events':
                // filter for events connected to a source
                $sourceIdFilter = "";
                if (isset($filtersArray['source'])){
                    $sourceQids = $filtersArray['source'];
                    foreach ($sourceQids as $sourceQid){
                        $sourceIdFilter .= "
                            VALUES ?source { $wd:$sourceQid} #Q number needs to be changed for every source.
                                ?source $wdt:$instanceOf $wd:$entityWithProvenance.
                                ?source $wdt:$reportsOn ?event.
                        ";
                    }
                }
                break;
            case 'sources':
                // filter for sources connected to an event
                $eventIdFilter = "";
                if (isset($filtersArray['event']) && $filtersArray['event'] != ''){
                    $eventQ = $filtersArray['event'][0];
                    $eventIdFilter = "
                        VALUES ?event { $wd:$eventQ} #Q number needs to be changed for every event.
                        ?source $wdt:$instanceOf $wd:$entityWithProvenance. #entity with provenance
                        ?source $wdt:$hasOriginalSourceType ?sourcetype.
                        ?source $wdt:$generatedBy ?project.
                        ?source $wdt:$reportsOn ?event.
                     ";
                }
                break;
            case 'projects':
                //todo: projects filters
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
            case 'featured':
                //Feature Cards on the Explore Form page
                $query = array('query' => "");
                include BASE_PATH."queries/".$preset."/".strtolower($templates[0]).".php";
                $query['query'] = $tempQuery;
                array_push($queryArray, $query);
                break;
            case 'all':
                // if (isset($filtersArray['searchbar'])){
                // }
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

    // map search types to their blazegraph name
    $searchTypes = [
        'people' => 'agent',
        'events' => 'event',
        'places' => 'place',
        'projects' => 'project',
        'sources' => 'source'
    ];
    if (array_key_exists($preset, $searchTypes)){
        if (!$isKeywordSearch){
            include BASE_PATH."queries/".$preset."Search/count.php";
            $resultCountQuery['query'] = $tempQuery;
            $result = blazegraphSearch($resultCountQuery);

            if (isset($result[0]) && isset($result[0]['count'])){
                $record_total = $result[0]['count']['value'];
            }
            // no more searching if we know there are 0 results
            if ($record_total <= 0){
                return createCards([], $templates, $preset, 0);
            }
        } else {
            // get the count for all search types for keyword search
            $record_total = getKeywordSearchCounters($filtersArray);
        }

        include BASE_PATH."queries/".$preset."Search/ids.php";
        $idQuery['query'] = $tempQuery;
        $result = blazegraphSearch($idQuery);


        // get the qids from each url
        $urls = (array_column(array_column($result, $searchTypes[$preset]), 'value'));
        $qids = [];
        foreach($urls as $url){
		$tempQids = explode('/', $url);
            $qids[] = end($tempQids);
        }

        // create the line in the query with the ids to search for
        $qidList = "";
        foreach($qids as $qid){
            $qidList .= "$wd:$qid ";
        }

        include BASE_PATH."queries/".$preset."Search/data.php";
        $dataQuery['query'] = $tempQuery;
        $resultsArray = blazegraphSearch($dataQuery);
    } else {
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
function createCards($results, $templates, $preset = 'default', $count = 0){
    if (!is_array($results)){
        $results = array();
    }

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
        $record = $record['_source'];
        $card = '';
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
                // if (isset($record['place1']) && isset($record['place1']['value'])){
                //     $placesArray = explode('||', $record['place1']['value']);
                //     foreach ($placesArray as $place) {
                //         if (!empty($place)){
                //             if ($placesCount > 0){
                //                 $places .= ", $place";
                //             } else {
                //                 $places .= "$place";
                //             }
                //             $placesCount++;
                //         }
                //     }
                // }
                //Date Range
                $startYear = '';
                // if (isset($record['startyear1']) && isset($record['startyear1']['value'])){
                //     $startYears = explode('||', $record['startyear1']['value']);
                //     $startYear = min($startYears);
                // }
                $endYear = '';
                // if (isset($record['endyear1']) && isset($record['endyear1']['value'])){
                //     $endYears = explode('||', $record['endyear1']['value']);
                //     $endYear = max($endYears);
                // }
                $dateRange = '';
                // if ($startYear != '' && $endYear != ''){
                //     $dateRange = "$startYear - $endYear";
                // } elseif ($endYear == ''){
                //     $dateRange = $startYear;
                // } elseif ($startYear == '') {
                //     $dateRange = $endYear;
                // }
                //Connection counts
                $countpeople = '';
                if (array_key_exists('countpeople', $record))
                    $countpeople = $record['countpeople'];
                $countevent = '';
                if (array_key_exists('countevent', $record))
                    $countevent = $record['countevent'];
                $countplace = '';
                if (array_key_exists('countplace', $record))
                    $countplace = $record['countplace'];
                $countsource = '';
                if (array_key_exists('countsource', $record))
                    $countsource = $record['countsource'];
                //Connection HTML
                $connection_lists = Array(
                    '<h1>'.$countpeople.' Connected People</h1><ul><li>Person Name <span>(Wife)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Brother brother brother)</span> <div id="arrow"></div></li><li>Person Name <span>(Relation)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Father)</span> <div id="arrow"></div></li><li>Person Name <span>(Mother)</span> <div id="arrow"></div></li><li>View All People Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countplace.' Connected Places</h1><ul><li>Place Name <div id="arrow"></div></li><li>Place Name is Longer<div id="arrow"></div></li><li>Place Name <div id="arrow"></div></li><li>View All Place Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countevent.' Connected Events</h1><ul><li>Event Name <div id="arrow"></div></li><li>Event Name is Longer<div id="arrow"></div></li><li>Event Name <div id="arrow"></div></li><li>View All Event Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countsource.' Connected Sources</h1><ul><li>Source Name <div id="arrow"></div></li><li>Source Name is Longer<div id="arrow"></div></li><li>Source Name <div id="arrow"></div></li><li>View All Source Connections <div id="arrow"></div></li></ul>'
                );
                $connections = '<div class="connectionswrap"><div class="connections">';
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
                            $statusHtml = "<div class='detail'><p class='detail-title'>Person Status</p><p class='multiple'>Multiple<span class='tooltip'>$status</span></p></div>";
                        }

                        $placesHtml = '';
                        if ($placesCount == 1){
                            $placesHtml = "<div class='detail'><p class='detail-title'>Place</p><p>$places</p></div>";
                        }
                        if ($placesCount > 1){
                            $placesHtml = "<div class='detail'><p class='detail-title'>Place</p><p class='multiple'>Multiple<span class='tooltip'>$places</span></p></div>";
                        }

                        $dateRangeHtml = '';
                        if ($dateRange != ''){
                            $dateName = ($startYear != '' && $endYear != '') ? "Date Range" : "Date";
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

                            $headers = <<<HTML
<tr>
    <th class="name">NAME</th>
    <th class="gender">GENDER</th>
    <th class="age">AGE</th>
    <th class="status">STATUS</th>
    <th class="origin">ORIGIN</th>
    <th class="location">LOCATION</th>
    <th class="dateRange">DATE RANGE</th>
</tr>
HTML;
                            $cards['tableCard']['headers'] = $headers;
                            $cards['fields'] = ['NAME', 'GENDER', 'AGE', 'STATUS', 'ORIGIN', 'LOCATION', 'DATE RANGE'];
                        }


                        $card = <<<HTML
<tr class='tr' data-qid='$personQ'>
    <td class='name td-name'>
        <span>$name</span>
    </td>
    <td class='gender'>
        <p><span class='first'>Gender: </span>$sex</p>
    </td>
    <td class='age'>
        <p><span class='first'>Age: </span></p>
    </td>
    <td class='status'>
        <p><span class='first'>Status: </span>$status</p>
    </td>
    <td class='origin'>
        <p><span class='first'>Origin: </span></p>
    </td>
    <td class='location'>
        <p><span class='first'>Location: </span>$places</p>
    </td>
    <td class='dateRange'>
        <p><span class='first'>Date Range: </span>$dateRange</p>
    </td>
    <td class='meta'>
        <a href='$person_url'>
    </td>
</tr>
HTML;
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
                $located = "";
                if (is_array($record['located_in']) && count($record['located_in']) > 0)
                    $located = $record['located_in'][0];

                //Counts for connections
                $countpeople = '';
                if (array_key_exists('countpeople', $record))
                    $countpeople = $record['countpeople'];
                $countevent = '';
                if (array_key_exists('countevent', $record))
                    $countevent = $record['countevent'];
                $countplace = '';
                if (array_key_exists('countplace', $record))
                    $countplace = $record['countplace'];
                $countsource = '';
                if (array_key_exists('countsource', $record))
                    $countsource = $record['countsource'];

                $placeType = '';
                if (is_array($record['place_type']) && count($record['place_type']) > 0)
                    $placeType = $record['place_type'][0];

                $geonames = '';
                if (is_array($record['geoname_id']) && count($record['geoname_id']) > 0)
                    $geonames = $record['geoname_id'][0];

                $code = '';
                if (is_array($record['modern_country_code']) && count($record['modern_country_code']) > 0)
                    $code = $record['modern_country_code'][0];

                //Connection html
                $connection_lists = Array(
                    '<h1>'.$countpeople.' Connected People</h1><ul><li>Person Name <span>(Wife)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Brother brother brother)</span> <div id="arrow"></div></li><li>Person Name <span>(Relation)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Father)</span> <div id="arrow"></div></li><li>Person Name <span>(Mother)</span> <div id="arrow"></div></li><li>View All People Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countplace.' Connected Places</h1><ul><li>Place Name <div id="arrow"></div></li><li>Place Name is Longer<div id="arrow"></div></li><li>Place Name <div id="arrow"></div></li><li>View All Place Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countevent.' Connected Events</h1><ul><li>Event Name <div id="arrow"></div></li><li>Event Name is Longer<div id="arrow"></div></li><li>Event Name <div id="arrow"></div></li><li>View All Event Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countsource.' Connected Sources</h1><ul><li>Source Name <div id="arrow"></div></li><li>Source Name is Longer<div id="arrow"></div></li><li>Source Name <div id="arrow"></div></li><li>View All Source Connections <div id="arrow"></div></li></ul>'
                );

                $connections = '<div class="connectionswrap"><div class="connections">';
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

                        $locatedHtml = '';
                        if ($located != ''){
                            $locatedHtml = "<div class='detail'><p class='detail-title'>Located In</p><p>$located</p></div>";
                        }

                        $geonamesHtml = '';
                        if ($geonames != ''){
                            $geonames = "<div class='detail'><p class='detail-title'>Geoname Identifier</p><p>$geonames</p></div>";
                        }

                        $codeHtml = '';
                        if ($code != ''){
                            $codeHtml = "<div class='detail'><p class='detail-title'>Modern Country Code</p><p>$code</p></div>";
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
            $locatedHtml
            $geonamesHtml
            $codeHtml
        </div>
        $connections
    </a>
</li>
HTML;

                    } elseif ($template == 'tableCard'){
                        if ($first) {
                            //todo create the correct place headers
                            $first = false;

                            $headers = <<<HTML
<tr>
    <th class="name">NAME</th>
    <th class="gender">TYPE</th>
    <th class="located">LOCATED</th>
    <th class="geoname">GEONAME IDENTIFIER</th>
    <th class="code">MODERN COUNTRY CODE</th>
</tr>
HTML;
                            $cards['tableCard']['headers'] = $headers;
                            $cards['fields'] = ['NAME', 'TYPE', 'LOCATED', 'GEONAME IDENTIFIER', 'MODERN COUNTRY CODE'];

                        }


                        $card = <<<HTML
<tr class='tr' data-qid='$placeQ'>
    <td class='name td-name'>
        <span>$name</span>
    </td>
    <td class='type'>
        <p><span class='first'>Type: </span>$placeType</p>
    </td>
    <td class='located'>
        <p><span class='first'>Located: </span>$located</p>
    </td>
    <td class='geoname'>
        <p><span class='first'>Located: </span>$geonames</p>
    </td>
    <td class='code'>
        <p><span class='first'>Located: </span>$code</p>
    </td>
    <td class='meta'>

    </td>
</tr>
HTML;

                        // format this row for csv download
                        $formattedData[$placeQ] = array(
                            'NAME' => $name,
                            'TYPE' => $placeType,
                            'LOCATED' => $located,
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
                if (is_array($record['at_place']) && count($record['at_place']) > 0) {
                    $places = implode(', ', $record['at_place']);
                    $placesCount = count($record['at_place']);
                }

                //Event Start Year
                $startYear = '';
                if ($record['date'] != 0000) {
                    $startYear = $record['date'][0];
                }

                //Event End Year
                $endYear = '';
                if ($record['end_date'] != 0000) {
                    $startYear = $record['end_date'][0];
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

                $countpeople = '';
                if (array_key_exists('countpeople', $record))
                    $countpeople = $record['countpeople'];
                $countevent = '';
                if (array_key_exists('countevent', $record))
                    $countevent = $record['countevent'];
                $countplace = '';
                if (array_key_exists('countplace', $record))
                    $countplace = $record['countplace'];
                $countsource = '';
                if (array_key_exists('countsource', $record))
                    $countsource = $record['countsource'];

                //Connection html
                $connection_lists = Array(
                    '<h1>'.$countpeople.' Connected People</h1><ul><li>Person Name <span>(Wife)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Brother brother brother)</span> <div id="arrow"></div></li><li>Person Name <span>(Relation)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Father)</span> <div id="arrow"></div></li><li>Person Name <span>(Mother)</span> <div id="arrow"></div></li><li>View All People Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countplace.' Connected Places</h1><ul><li>Place Name <div id="arrow"></div></li><li>Place Name is Longer<div id="arrow"></div></li><li>Place Name <div id="arrow"></div></li><li>View All Place Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countsource.' Connected Sources</h1><ul><li>Source Name <div id="arrow"></div></li><li>Source Name is Longer<div id="arrow"></div></li><li>Source Name <div id="arrow"></div></li><li>View All Source Connections <div id="arrow"></div></li></ul>'
                );
                //'<h1>'.$countevent.' Connected Events</h1><ul><li>Event Name <div id="arrow"></div></li><li>Event Name is Longer<div id="arrow"></div></li><li>Event Name <div id="arrow"></div></li><li>View All Event Connections <div id="arrow"></div></li></ul>',

                $connections = '<div class="connectionswrap"><div class="connections">';
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
                            $rolesHtml = "<div class='detail'><p class='detail-title'>Role</p><p class='multiple'>Multiple<span class='tooltip'>$roles</span></p></div>";
                        }
                        // Check for multiple places
                        $placesHtml = '';
                        if ($placesCount == 1){
                            $placesHtml = "<div class='detail'><p class='detail-title'>Place</p><p>$places</p></div>";
                        }
                        if ($placesCount > 1){
                            $placesHtml = "<div class='detail'><p class='detail-title'>Place</p><p class='multiple'>Multiple<span class='tooltip'>$places</span></p></div>";
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

                            $headers = <<<HTML
<tr>
    <th class="name">NAME</th>
    <th class="type">TYPE</th>
    <th class="places">PLACES</th>
    <th class="dateRange">DATE RANGE</th>
</tr>
HTML;
                            $cards['tableCard']['headers'] = $headers;
                            $cards['fields'] = ['NAME', 'TYPE', 'PLACES', 'DATE RANGE'];
                        }

                        $card = <<<HTML
<tr class='tr'  data-qid='$eventQ'>
    <td class='name td-name'>
        <span>$name</span>
    </td>
    <td class='type'>
        <p><span class='first'>Type: </span>$type</p>
    </td>
    <td class='places'>
        <p><span class='first'>Places: </span>$places</p>
    </td>
    <td class='dateRange'>
        <p><span class='first'>Date Range: </span>$dateRange</p>
    </td>
    <td class='meta'>

    </td>
</tr>
HTML;
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

                $countpeople = '';
                if (array_key_exists('countpeople', $record))
                    $countpeople = $record['countpeople'];
                $countevent = '';
                if (array_key_exists('countevent', $record))
                    $countevent = $record['countevent'];
                $countplace = '';
                if (array_key_exists('countplace', $record))
                    $countplace = $record['countplace'];
                $countsource = '';
                if (array_key_exists('countsource', $record))
                    $countsource = $record['countsource'];

                //Connection html
                $connection_lists = Array(
                    '<h1>'.$countpeople.' Connected People</h1><ul><li>Person Name <span>(Wife)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Brother brother brother)</span> <div id="arrow"></div></li><li>Person Name <span>(Relation)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Father)</span> <div id="arrow"></div></li><li>Person Name <span>(Mother)</span> <div id="arrow"></div></li><li>View All People Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countplace.' Connected Places</h1><ul><li>Place Name <div id="arrow"></div></li><li>Place Name is Longer<div id="arrow"></div></li><li>Place Name <div id="arrow"></div></li><li>View All Place Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countevent.' Connected Events</h1><ul><li>Event Name <div id="arrow"></div></li><li>Event Name is Longer<div id="arrow"></div></li><li>Event Name <div id="arrow"></div></li><li>View All Event Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countsource.' Connected Sources</h1><ul><li>Source Name <div id="arrow"></div></li><li>Source Name is Longer<div id="arrow"></div></li><li>Source Name <div id="arrow"></div></li><li>View All Source Connections <div id="arrow"></div></li></ul>'
                );

                $connections = '<div class="connectionswrap"><div class="connections">';
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
                            $typeHtml = "<div class='detail'><p class='detail-title'>Type</p><p class='multiple'>Multiple<span class='tooltip'>$type</span></p></div>";
                        }

                        $projectHtml = '';
                        if ($project != ""){
                            $projectHtml = "<div class='detail'><p class='detail-title'>Project</p><p>$project</p></div>";
                        }

                        $descHtml = '';
                        if ($desc != ""){
                            $descHtml = "<div class='detail'><p class='detail-title'>Description</p><p>$desc</p></div>";
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
            $descHtml
        </div>
        $connections
    </a>
</li>
HTML;

                    } elseif ($template == 'tableCard'){
                        if ($first) {
                            $first = false;

                            $headers = <<<HTML
<tr>
    <th class="name">NAME</th>
    <th class="type">TYPE</th>
    <th class="project">PROJECT</th>
    <th class="desc">DESCRIPTION</th>
</tr>
HTML;
                            $cards['tableCard']['headers'] = $headers;
                            $cards['fields'] = ['NAME', 'TYPE', 'PROJECT', 'DESCRIPTION'];
                        }

                        $card = <<<HTML
<tr class='tr' data-qid='$sourceQ'>
    <td class='name td-name'>
        <span>$name</span>
    </td>
    <td class='type'>
        <p><span class='first'>Type: </span>$type</p>
    </td>
    <td class='project'>
        <p><span class='first'>Project: </span>$project</p>
    </td>
    <td class='desc'>
        <p><span class='first'>Description: </span>$desc</p>
    </td>
    <td class='meta'>

    </td>
</tr>
HTML;

                        // format this row for csv download
                        $formattedData[$sourceQ] = array(
                            'NAME' => $name,
                            'TYPE' => $type,
                            'PROJECT' => $project,
                            'DESCRIPTION' => $desc
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
                    $qid = '';
                    if($template == 'Person'){
                        $cardTitle = $record['agentLabel']['value'];
                        $uri = $record['agent']['value'];
                        $uriarr = explode('/', $uri);
                        $qid = end($uriarr);
                    }
                    else if($template == 'Place'){
                        $cardTitle = $record['placeLabel']['value'];
                        $uri = $record['place']['value'];
                        $uriarr = explode('/', $uri);
                        $qid = end($uriarr);
                    }
                    else if($template == 'Event'){
                        $cardTitle = $record['label']['value'];
                        $uri = $record['event']['value'];
                        $uriarr = explode('/', $uri);
                        $qid = end($uriarr);
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

                    $connections = '<div class="connectionswrap"><div class="connections">';
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
