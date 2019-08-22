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
   // if (isset($_GET['delete'])) {
   //     $path = "functions/queries.json";
   //     $contents = file_get_contents($path);
   //     $contents = json_decode($contents, true);
   //     unset($contents[$_GET['delete']]);
   //     $contents = array_values($contents);
   //     $contents = json_encode($contents);
   //     echo file_put_contents($path, $contents);
   //     die;
   // }

    if (isset($_GET['filters'])){
        $filtersArray = $_GET['filters'];
        // print_r($filtersArray);die;

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

    // print_r($filtersArray);die;

    $templates = $_GET['templates'];

    $record_total = 0;
    $queryArray = array();
    if (isset($_GET['preset'])) {
        $preset = $_GET['preset'];

        foreach(properties as $property => $pId){
            $property = ucwords($property);
            $property = str_replace(" ", "", $property);
            $property = lcfirst($property);
            $$property = $pId;
        }

        foreach(classes as $class => $qId){
            $class = ucwords($class);
            $class = str_replace(" ", "", $class);
            $class = lcfirst($class);
            $$class = $qId;
        }

        switch ($preset){
            case 'singleProject':
                // QID is mandatory
                if(!isset($_GET["qid"]) || empty($_GET["qid"])) return false;

                $Q_ID = $_GET["qid"];
                $Q_limit = 10;
                $Q_offset = 0;

                // Get Limit and offset from GET
                if(isset($_GET["limit"]) && !empty($_GET["limit"])) $Q_limit = $_GET["limit"];
                if(isset($_GET["offset"]) && !empty($_GET["offset"])) $Q_offset = $_GET["offset"];


                $query = array('query' => "");
                $query['query'] = <<<QUERY
SELECT DISTINCT ?agent
(group_concat(distinct ?startyear; separator = "||") as ?startyear) #daterange
(group_concat(distinct ?endyear; separator = "||") as ?endyear)

(group_concat(distinct ?name; separator = "||") as ?name) #name
(group_concat(distinct ?placelab; separator = "||") as ?place) #place
(group_concat(distinct ?statuslab; separator = "||") as ?status) #status
(group_concat(distinct ?sexlab; separator = "||") as ?sex) #Sex


(count(distinct ?relations) as ?countpeople)
(count(distinct ?event) as ?counterevent)
(count(distinct ?place) as ?countplace)
(count(distinct ?reference) as ?countsource)
WHERE {

  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }

  ?agent wdt:$instanceOf/wdt:$subclassOf wd:$agent;

         wdt:$hasName ?name; #name is mandatory
            p:$instanceOf  ?object .
  ?object prov:wasDerivedFrom ?provenance .
  ?provenance pr:$isDirectlyBasedOn ?reference .
  ?reference wdt:$generatedBy wd:$Q_ID #include here the Q number of the project


  MINUS{ ?agent wdt:$hasParticipantRole wd:$researcher }. #remove all researchers

  OPTIONAL { ?agent wdt:$hasPersonStatus ?status.
            ?status rdfs:label ?statuslab}

  OPTIONAL { ?agent wdt:$hasSex ?sex.
            ?sex rdfs:label ?sexlab}

  OPTIONAL { ?agent wdt:$hasInterAgentRelationship ?relations}.
  OPTIONAL { ?agent wdt:$closeMatch ?relations}.

  OPTIONAL{ ?reference wdt:$reportsOn ?event.
            ?event  wdt:$startsAt ?startdate.
           BIND(str(YEAR(?startdate)) AS ?startyear).
           OPTIONAL {?event wdt:$endsAt ?enddate.
           BIND(str(YEAR(?enddate)) AS ?endyear)}.
           OPTIONAL {?event wdt:$atPlace ?place.
                    ?place rdfs:label ?placelab}

          }.


} group by ?agent
order by ?agent
limit $Q_limit
offset $Q_offset
QUERY;

                array_push($queryArray, $query);

                $query = array('query' => "");
                $query['query'] = <<<QUERY
SELECT DISTINCT ?agent ?startyear ?endyear
(group_concat(distinct ?name; separator = "||") as ?name) #name

(group_concat(distinct ?placelab; separator = "||") as ?place) #place

(group_concat(distinct ?statuslab; separator = "||") as ?status) #status

(group_concat(distinct ?sexlab; separator = "||") as ?sex) #Sex

(group_concat(distinct ?match; separator = "||") as ?closeMatch)

WHERE {

    SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }

    ?agent wdt:$instanceOf/wdt:$subclassOf wd:$agent;

        wdt:$hasName ?name; #name is mandatory
            p:$instanceOf  ?object .
    ?object prov:wasDerivedFrom ?provenance .
    ?provenance pr:$isDirectlyBasedOn ?reference .
    ?reference wdt:$generatedBy wd:$Q_ID

    OPTIONAL{?agent  wdt:$hasParticipantRole ?role}. #optional role
    MINUS{ ?agent wdt:$hasParticipantRole wd:$researcher }. #remove all researchers

    OPTIONAL { ?agent wdt:$hasPersonStatus ?status.
            ?status rdfs:label ?statuslab}

    OPTIONAL { ?agent wdt:$hasSex ?sex.
            ?sex rdfs:label ?sexlab}

    OPTIONAL { ?agent wdt:$closeMatch ?match}.

    ?agent p:$hasName ?statement.
    ?statement ps:$hasName ?name.
    OPTIONAL{ ?statement pq:$recordedAt ?event.
            ?event  wdt:$startsAt ?startdate.
            BIND(str(YEAR(?startdate)) AS ?startyear).
            OPTIONAL {?event wdt:$endsAt ?enddate.
        BIND(str(YEAR(?enddate)) AS ?endyear)}.
            OPTIONAL {?event wdt:$atPlace ?place.
                    ?place rdfs:label ?placelab}

            }.


} group by ?agent ?event ?startyear ?endyear
order by ?agent
QUERY;

                array_push($queryArray, $query);
                break;

            case 'people':
                ///*********************************** */
                /// PEOPLE
                ///*********************************** */
                //Query with limit and offset
                $query = array('query' => "");

                //Filtering for Query

                // filter by gender
                $genderIdFilter = "";
                if (isset($filtersArray['gender'])) {
                    $genders = $filtersArray['gender'];

                    foreach ($genders as $gender){
                        if (array_key_exists($gender, sexTypes)){
                            $qGender = sexTypes[$gender];
                            $genderIdFilter .= "?agent wdt:$hasSex wd:$qGender. ";
                        }
                    }
                }

                // filter by name
                //TODO: FIX NAME FILTER
                $nameQuery = "";
                if (isset($filtersArray['person'])){
                    $name = $filtersArray['person'][0];
                    $nameQuery = "FILTER regex(?name, '^$name', 'i') .";
                }


                //filter by source
                //TODO: FIX SOURCE FILTER
                $sourceQuery = "";
                if (isset($filtersArray['source']) && $filtersArray['source'] != ''){
                    $sourceQ = $filtersArray['source'][0];
                    $sourceQuery = "VALUES ?source {wd:$sourceQ} #Q number needs to be changed for every source.
                                    ?source wdt:$instanceOf wd:$entityWithProvenance.
                                    ?people wdt:$instanceOf/wdt:$subclassOf wd:$agent; #agent or subclass of agent
                                            ?property  ?object .
                                    ?object prov:wasDerivedFrom ?provenance .
                                    ?provenance pr:$isDirectlyBasedOn ?source .
                                    ?people rdfs:label ?peoplename";
                }

                // filter by age category
                $ageQuery = "";
                $ageIdFilter = "";
                if (isset($filtersArray['age_category'])){
                    $ages = $filtersArray['age_category'];

                    foreach ($ages as $age){
                        if (array_key_exists($age, ageCategory)){
                            $qAge = ageCategory[$age];
                            $ageIdFilter .= "?agent wdt:$hasAgeCategory wd:$qAge . ";
                        }
                    }
                }

                // filter by status
                $statusIdFilter = "";
                if (isset($filtersArray['status'])){
                    $statuses = $filtersArray['status'];

                    foreach ($statuses as $status){
                        if (array_key_exists($status, personstatus)){
                            $qStatus = personstatus[$status];
                            $statusIdFilter .= "?agent wdt:$hasPersonStatus wd:$qStatus . ";
                        }
                    }
                }

                // filter by ethnodescriptor
                $ethnoIdFilter = "";
                if (isset($filtersArray['ethnodescriptor'])){
                    $ethnos = $filtersArray['ethnodescriptor'];
                    foreach ($ethnos as $ethno){
                        if (array_key_exists($ethno, ethnodescriptor)){
                            $qEthno = ethnodescriptor[$ethno];
                            $ethnoIdFilter .= "?agent wdt:$hasECVO wd:$qEthno . ";
                        }
                    }
                }

                // filter by role
                $roleIdFilter = "";
                if (isset($filtersArray['role_types'])){
                    $roles = $filtersArray['role_types'];

                    foreach ($roles as $role){
                        if (array_key_exists($role, roleTypes)){
                            $qRole = roleTypes[$role];
                            $roleIdFilter .= "?agent wdt:$hasParticipantRole wd:$qRole . ";
                        }
                    }
                }

                // filter by occupation
                $occupationIdFilter = "";
                if (isset($filtersArray['occupation'])){
                    $occupations = $filtersArray['occupation'];

                    foreach ($occupations as $occupation){
                        if (array_key_exists($occupation, occupation)){
                            $qOccupation = occupation[$occupation];
                            $occupationIdFilter .= "?agent wdt:$hasOccupation wd:$qOccupation . ";
                        }
                    }
                }


                //todo: get the new filter for this
                // people connected to an event
                $eventQuery = "";
                if (isset($filtersArray['event']) && $filtersArray['event'] != ''){
                    $eventQ = $filtersArray['event'][0];

                    $query['query'] = <<<QUERY
SELECT DISTINCT ?agent ?name (SHA512(CONCAT(STR(?people), STR(RAND()))) as ?random)

 WHERE
{
 VALUES ?event {wd:$eventQ} #Q number needs to be changed for every event.
  ?event wdt:$instanceOf wd:$event.
  ?event p:$providesParticipantRole ?statement.
  ?statement ps:$providesParticipantRole ?personname.
  ?statement pq:$hasParticipantRole ?agent.
  ?agent rdfs:label ?name.

  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}
QUERY;
                    array_push($queryArray, $query);
                    break;
                }


                include BASE_PATH."queries/peopleSearch/count.php";
                $resultCountQuery['query'] = $tempQuery;

                $ch = curl_init(BLAZEGRAPH_URL);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($resultCountQuery));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                    'Accept: application/sparql-results+json'
                ));
                $result = curl_exec($ch);
                curl_close($ch);

                $result = json_decode($result, true)['results']['bindings'];

                $record_total = 0;
                if (isset($result[0]) && isset($result[0]['count'])){
                    $record_total = $result[0]['count']['value'];
                }

                // no more searching if we know there are 0 results
                if ($record_total <= 0){
                    return createCards([], $templates, $preset, 0);
                }

                include BASE_PATH."queries/peopleSearch/ids.php";
                $idQuery['query'] = $tempQuery;

                $ch = curl_init(BLAZEGRAPH_URL);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($idQuery));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                    'Accept: application/sparql-results+json'
                ));
                $result = curl_exec($ch);
                curl_close($ch);

                $result = json_decode($result, true)['results']['bindings'];

                // get the qids from each url
                $urls = (array_column(array_column($result, 'agent'), 'value'));
                $qids = [];
                foreach($urls as $url){
                    $qids[] = end(explode('/', $url));
                }
                
                // create the line in the query with the ids to search for
                $qidList = "";
                foreach($qids as $qid){
                    $qidList .= "wd:$qid ";
                }
                
                include BASE_PATH."queries/eventsSearch/data.php";
                $query['query'] = $tempQuery;
                $dataQuery['query'] = $tempQuery;

                array_push($queryArray, $query);
                break;

            case 'places':
                ///*********************************** */
                /// PLACES
                ///*********************************** */

                $typeQuery = "";
                if (isset($filtersArray['place_type'])){
                    $type = $filtersArray['place_type'][0];
                    if (array_key_exists($type, placeTypes)){
                        $qType = placeTypes[$type];
                        $typeQuery = "?place wdt:$hasPlaceType wd:$qType .";
                    }
                }

                $query = array('query' => "");

                $query['query'] = <<<QUERY
SELECT ?place ?placeLabel ?locatedInLabel ?type ?geonames ?code
(count(distinct ?person) as ?countpeople)
(count(distinct ?event) as ?countevent)
(count(distinct ?source) as ?countsource)

WHERE {
    ?event wdt:$instanceOf wd:$event;
    ?property  ?object .
    ?object prov:wasDerivedFrom ?provenance .
    ?provenance pr:$isDirectlyBasedOn ?source .

    ?event wdt:$atPlace ?place;
    p:$providesParticipantRole ?statement.
    ?statement ps:$providesParticipantRole ?role.
    ?statement pq:$hasParticipantRole ?person.


    ?place rdfs:label ?placeLabel.

    ?place wdt:$hasPlaceType ?placetype.
    ?placetype rdfs:label ?type.

    $typeQuery

    OPTIONAL{ ?place wdt:$geonamesID ?geonames.}
    OPTIONAL{ ?place wdt:$moderncountrycode ?code.}
    OPTIONAL {?place wdt:$locatedIn ?locatedIn}.
    SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
}GROUP BY ?place ?placeLabel ?locatedInLabel ?type ?geonames ?code
order by ?placeLabel
$limitQuery
$offsetQuery
QUERY;

                array_push($queryArray, $query);
                break;
            case 'events':
                ///*********************************** */
                /// EVENTS
                ///*********************************** */

                //Filtering for Query

                // filtering for event type
                $eventTypeIdFilter = "";
                if (isset($filtersArray['event_type'])){
                    $types = $filtersArray['event_type'];

                   foreach ($types as $type){
                        if (array_key_exists($type, eventTypes)){
                            $qType = eventTypes[$type];
                            $eventTypeIdFilter .= "?event wdt:$hasEventType wd:$qType . ";
                        }
                    }

                }

                // filtering for dateRange
                $dateRangeIdFilter = "";
                if (isset($filtersArray['date'])){
                    $dateRange = $filtersArray['date'][0];
                    $dateArr = explode('-', $dateRange);

                    $from = '';
                    if (isset($dateArr[0])){
                        $from = $dateArr[0];
                    }

                    $to = '';
                    if (isset($dateArr[1])){
                        $to = $dateArr[1];
                    }

                    $dateRangeQuery = $dateRange;

                    if ($from != ''){
                        $dateRangeIdFilter .= "
                            ?event wdt:$startsAt ?startYear.
                            FILTER (?startYear >= \"".$from."-01-01T00:00:00Z"."\"^^xsd:dateTime) . 
                        ";
                    }

                    if ($to != ''){
                        $dateRangeIdFilter .= "
                            ?event wdt:$endsAt ?endYear.
                            FILTER (?endYear <= \"".$to."-01-01T00:00:00Z"."\"^^xsd:dateTime) . 
                        ";                    }
                }


                // filter for events connected to a source
                $sourceIdFilter = "";
                if (isset($filtersArray['source'])){
                    $sourceQids = $filtersArray['source'];

                    foreach ($sourceQids as $sourceQid){
                        $sourceIdFilter .= "
                            VALUES ?source {wd:$sourceQid} #Q number needs to be changed for every source.
                                ?source wdt:$instanceOf wd:$entityWithProvenance.
                                ?source wdt:$reportsOn ?event. 
                        ";                    
                    }
                }

                include BASE_PATH."queries/eventsSearch/count.php";
                $resultCountQuery['query'] = $tempQuery;
                
                $ch = curl_init(BLAZEGRAPH_URL);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($resultCountQuery));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                    'Accept: application/sparql-results+json'
                ));
                $result = curl_exec($ch);
                curl_close($ch);
                
                $result = json_decode($result, true)['results']['bindings'];
                
                $record_total = 0;
                if (isset($result[0]) && isset($result[0]['count'])){
                    $record_total = $result[0]['count']['value'];
                }

                // no more searching if we know there are 0 results
                if ($record_total <= 0){
                    return createCards([], $templates, $preset, 0);
                }
            

                include BASE_PATH."queries/eventsSearch/ids.php";
                $idQuery['query'] = $tempQuery;
                
                $ch = curl_init(BLAZEGRAPH_URL);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($idQuery));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                    'Accept: application/sparql-results+json'
                ));
                $result = curl_exec($ch);
                curl_close($ch);
                
                $result = json_decode($result, true)['results']['bindings'];
                
                // get the qids from each url
                $urls = (array_column(array_column($result, 'event'), 'value'));
                $qids = [];
                foreach($urls as $url){
                    $qids[] = end(explode('/', $url));
                }
                
                // create the line in the query with the ids to search for
                $qidList = "";
                foreach($qids as $qid){
                    $qidList .= "wd:$qid ";
                }
                
                include BASE_PATH."queries/eventsSearch/data.php";
                $query['query'] = $tempQuery;
                $dataQuery['query'] = $tempQuery;
                
                // print_r($query);die;
                array_push($queryArray, $query);
                break;

            case 'sources':
                ///*********************************** */
                /// SOURCES
                ///*********************************** */
                $query = array('query' => "");

                //todo: get these working correctly
                // filter for source types
                $sourceTypeIdFilter = "";
                if (isset($filtersArray['source_type'])){
                    $types = $filtersArray['source_type'];

                   foreach ($types as $type){
                        if (array_key_exists($type, sourceTypes)){
                            $qType = sourceTypes[$type];
                            $sourceTypeIdFilter .= "?source wdt:$hasOriginalSourceType wd:$qType . ";
                        }
                    }
                }

                // filter for sources connected to an event
                $eventIdFilter = "";
                if (isset($filtersArray['event']) && $filtersArray['event'] != ''){
                    $eventQ = $filtersArray['event'][0];
                    $eventIdFilter = "
                        VALUES ?event {wd:$eventQ} #Q number needs to be changed for every event.
                        ?source wdt:$instanceOf wd:$entityWithProvenance. #entity with provenance
                        ?source wdt:$hasOriginalSourceType ?sourcetype.
                        ?source wdt:$generatedBy ?project.
                        ?source wdt:$reportsOn ?event.
                     ";
                }

                include BASE_PATH."queries/sourcesSearch/count.php";
                $resultCountQuery['query'] = $tempQuery;

                $ch = curl_init(BLAZEGRAPH_URL);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($resultCountQuery));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                    'Accept: application/sparql-results+json'
                ));
                $result = curl_exec($ch);
                curl_close($ch);
                
                $result = json_decode($result, true)['results']['bindings'];
                
                $record_total = 0;
                if (isset($result[0]) && isset($result[0]['count'])){
                    $record_total = $result[0]['count']['value'];
                }

                // no more searching if we know there are 0 results
                if ($record_total <= 0){
                    return createCards([], $templates, $preset, 0);
                }
                
                include BASE_PATH."queries/sourcesSearch/ids.php";
                $idQuery['query'] = $tempQuery;
                
                $ch = curl_init(BLAZEGRAPH_URL);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($idQuery));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                    'Accept: application/sparql-results+json'
                ));
                $result = curl_exec($ch);
                curl_close($ch);
                
                $result = json_decode($result, true)['results']['bindings'];
                
                // get the qids from each url
                $urls = (array_column(array_column($result, 'source'), 'value'));
                $qids = [];
                foreach($urls as $url){
                    $qids[] = end(explode('/', $url));
                }
                
                // create the line in the query with the ids to search for
                $qidList = "";
                foreach($qids as $qid){
                    $qidList .= "wd:$qid ";
                }
                
                include BASE_PATH."queries/sourcesSearch/data.php";
                $query['query'] = $tempQuery;
                $dataQuery['query'] = $tempQuery;
                // print_r($dataQuery);die;

                array_push($queryArray, $query);
                break;
            case 'projects':
                $query = array('query' => "");
//                $query['query'] =
//                    'SELECT ?project ?projectLabel  WHERE {
//                      ?project wdt:$instanceOf wd:$researchProject 
//
//                      SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
//                    }
//                ';
                $query['query'] = <<<QUERY
SELECT ?person ?personLabel ?name ?originLabel
    (group_concat(distinct ?status; separator = "||") as ?status)
    (group_concat(distinct ?place; separator = "||") as ?place)
    (group_concat(distinct ?startyear; separator = "||") as ?startyear)
    (group_concat(distinct ?endyear; separator = "||") as ?endyear)
    WHERE {
        SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
        ?person wdt:$instanceOf wd:$person.
        ?person wdt:$hasSex wd:$female.
        OPTIONAL {?person wdt:$instanceOf wd:$agent.}
        OPTIONAL {?person wdt:$hasName ?name.}
        OPTIONAL {?person wdt:$hasOriginRecord ?origin.}
        OPTIONAL {?name wdt:$recordedAt ?event.
                ?event wdt:$startsAt ?startdate.}
        BIND(str(YEAR(?startdate)) AS ?startyear).

        OPTIONAL {?event wdt:$endsAt ?enddate.}
        BIND(str(YEAR(?enddate)) AS ?endyear).
        OPTIONAL {?event wdt:$atPlace ?place.}
        OPTIONAL { ?person wdt:$hasSex ?sex. }
        OPTIONAL { ?person wdt:$hasPersonStatus ?status. }
        OPTIONAL { ?person wdt:$hasOwner ?owner. }
        OPTIONAL { ?person wdt:$closeMatch ?match. }

    } group by ?person ?personLabel ?name ?originLabel
    $limitQuery
QUERY;

                array_push($queryArray, $query);
                break;
            case 'projectAssoc':
                $qid = $_GET['qid'];
                $query = array('query' => "");
                $query['query'] = <<<QUERY
SELECT DISTINCT ?project ?projectLabel (count(distinct ?agent) as ?agentcount)
    WHERE {
        VALUES ?project {wd:$qid}
        ?agent wdt:$instanceOf/wdt:$subclassOf wd:$agent;        #find agents
                p:$instanceOf  ?object .
        ?object prov:wasDerivedFrom ?provenance .
        ?provenance pr:$isDirectlyBasedOn ?reference .
        ?reference wdt:$generatedBy ?project

        SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }
    }
    GROUP BY ?project ?projectLabel
QUERY;
                array_push($queryArray, $query);
                $query = array('query' => "");
                $query['query'] = <<<QUERY
SELECT ?project ?projectLabel  (COUNT(*) AS ?eventcount)
    WHERE {
        ?project wdt:$instanceOf wd:$researchProject .         #find projects
        ?item wdt:$instanceOf wd:$event;        #find events
            p:$instanceOf  ?object .
        ?object prov:wasDerivedFrom ?provenance .
        ?provenance pr:$isDirectlyBasedOn ?reference .
        ?reference wdt:$generatedBy ?project;
                    wdt:$generatedBy wd:$qid
        SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }
    }
    GROUP BY ?project ?projectLabel
    ORDER BY ?count
QUERY;
                array_push($queryArray, $query);
                $query = array('query' => "");
                $query['query'] = <<<QUERY
SELECT ?project ?projectLabel  (COUNT(*) AS ?placecount)
    WHERE {
        ?project wdt:$instanceOf wd:$researchProject .         #find projects
        ?item wdt:$instanceOf wd:$place;        #find places
            p:$instanceOf  ?object .
        ?object prov:wasDerivedFrom ?provenance .
        ?provenance pr:$isDirectlyBasedOn ?reference .
        ?reference wdt:$generatedBy ?project;
                    wdt:$generatedBy wd:$qid
        SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }
    }
    GROUP BY ?project ?projectLabel
    ORDER BY ?count
QUERY;
                array_push($queryArray, $query);
                break;
            case 'projects2':
                $query = array('query' => "");
                $query['query'] = <<<QUERY
SELECT ?project ?projectLabel
    WHERE {
    ?project wdt:$instanceOf wd:$researchProject          #find projects
    SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }
    }
QUERY;
                array_push($queryArray, $query);
                $query = array('query' => "");
                $query['query'] = <<<QUERY
SELECT ?project ?projectLabel  (COUNT(distinct ?agent) AS ?count)
    WHERE {
        ?project wdt:$instanceOf wd:$researchProject .         #find projects
        ?agent wdt:$instanceOf/wdt:$subclassOf wd:$agent;        #find agents
            p:$instanceOf  ?object .
        ?object prov:wasDerivedFrom ?provenance .
        ?provenance pr:$isDirectlyBasedOn ?reference .
        ?reference wdt:$generatedBy ?project
        SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }
    }
    GROUP BY ?project ?projectLabel
    ORDER BY ?count
QUERY;
                array_push($queryArray, $query);
                $query = array('query' => "");
                $query['query'] = <<<QUERY
SELECT ?project ?projectLabel  (COUNT(*) AS ?count)
    WHERE {
        ?project wdt:$instanceOf wd:$researchProject .         #find projects
        ?item wdt:$instanceOf wd:$event;        #find events
            p:$instanceOf  ?object .
        ?object prov:wasDerivedFrom ?provenance .
        ?provenance pr:$isDirectlyBasedOn ?reference .
        ?reference wdt:$generatedBy ?project
        SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }
    }
    GROUP BY ?project ?projectLabel
    ORDER BY ?count
QUERY;
                array_push($queryArray, $query);
                $query = array('query' => "");
                $query['query'] = <<<QUERY
SELECT ?project ?projectLabel  (COUNT(*) AS ?count)
    WHERE {
        ?project wdt:$instanceOf wd:$researchProject .         #find projects
        ?item wdt:$instanceOf wd:$place;        #find places
            p:$instanceOf  ?object .
        ?object prov:wasDerivedFrom ?provenance .
        ?provenance pr:$isDirectlyBasedOn ?reference .
        ?reference wdt:$generatedBy ?project
        SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }
    }
    GROUP BY ?project ?projectLabel
    ORDER BY ?count
QUERY;
                array_push($queryArray, $query);
                break;
            case 'stories':
                $query = array('query' => "");
                $query['query'] = <<<QUERY
SELECT ?person ?personLabel ?name ?originLabel
    (group_concat(distinct ?status; separator = "||") as ?status)
    (group_concat(distinct ?place; separator = "||") as ?place)
    (group_concat(distinct ?startyear; separator = "||") as ?startyear)
    (group_concat(distinct ?endyear; separator = "||") as ?endyear)
    WHERE {
        SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
        ?person wdt:$instanceOf wd:$atPlace.
        ?person wdt:$hasSex wd:$female.
        OPTIONAL {?person wdt:$instanceOf wd:$agent.}
        OPTIONAL {?person wdt:$hasName ?name.}
        OPTIONAL {?person wdt:$hasOriginRecord ?origin.}
        OPTIONAL {?name wdt:$recordedAt ?event.
                ?event wdt:$startsAt ?startdate.}
        BIND(str(YEAR(?startdate)) AS ?startyear).

        OPTIONAL {?event wdt:$endsAt ?enddate.}
        BIND(str(YEAR(?enddate)) AS ?endyear).
        OPTIONAL {?event wdt:$atPlace ?place.}
        OPTIONAL { ?person wdt:$hasSex ?sex. }
        OPTIONAL { ?person wdt:$hasPersonStatus ?status. }
        OPTIONAL { ?person wdt:$hasOwner ?owner. }
        OPTIONAL { ?person wdt:$closeMatch ?match. }

    } group by ?person ?personLabel ?name ?originLabel
    $limitQuery
QUERY;
                array_push($queryArray, $query);
                break;
            case 'featured':
                //Feature Cards on the Explore Form page
                if($templates[0] == 'Person'){
                    $query = array('query' => "");
                    $query['query'] = <<<QUERY
SELECT DISTINCT ?agent ?agentLabel (SHA512(CONCAT(STR(?agent), STR(RAND()))) as ?random) WHERE {
?agent wdt:$instanceOf/wdt:$subclassOf wd:$agent . #all agents and people
?agent wikibase:statements ?statementcount . #with at least 4 core fields
FILTER (?statementcount >3  ).
?agent wdt:$closeMatch ?match. #and they have a match
SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }
} ORDER BY ?random
LIMIT 8
QUERY;
                }
                if($templates[0] == 'Place'){
                    $query = array('query' => "");
                    $query['query'] = <<<QUERY
SELECT DISTINCT ?place ?placeLabel (SHA512(CONCAT(STR(?place), STR(RAND()))) as ?random) WHERE {
?place wdt:$instanceOf wd:$place .
?place wikibase:statements ?statementcount .
        FILTER (?statementcount >3  )
SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }
} ORDER BY ?random
LIMIT 8
QUERY;
                }
                if($templates[0] == 'Event'){
                    $query = array('query' => "");
                    $query['query'] = <<<QUERY
SELECT DISTINCT ?type (SAMPLE(?event) AS ?event) (SAMPLE(?elabel) AS ?label)
(SHA512(CONCAT(STR(?event), STR(RAND()))) as ?random) WHERE {

    ?event wdt:$instanceOf wd:$event;
            rdfs:label ?elabel;
                wdt:$hasEventType ?type;
            wikibase:statements ?statementcount .
        FILTER (?statementcount >3  ).
    SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }
    }
GROUP BY ?type
ORDER BY ?random
LIMIT 8
QUERY;
                }

                array_push($queryArray, $query);
                break;

            default:
                return json_encode(["gridCard"=>array(), "tableCard" => array(), "total" => 0]);
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

    // print_r($queryArray);die;

    foreach ($queryArray as $i => $query) {
        $ch = curl_init(BLAZEGRAPH_URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'Accept: application/sparql-results+json'
        ));
        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result, true)['results']['bindings'];

        if(!$result) continue;

        $presetToCounterFunction = [
            'place' => 'queryPlaceCounter',
            'singleProject' => 'queryProjectsCounter'
        ];

        $count = 0;
        if(!isset($presetToCounterFunction[$preset])){
            $count = $record_total;
        } else {
            $count = $presetToCounterFunction[$preset]();
        }

        if ($first){
            $resultsArray = $result;
            $first = false;

            if ($oneQuery){ // this is needed for the search page counter to be working
                $record_total = $count;
            }
        } else {
            if($preset == 'people' || $preset == 'places' || $preset == 'events' || $preset == 'sources' || $preset == 'singleProject'){
                //Get the count of all the results
                //for people, places, events, sources, and singleProject
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


    // print_r($resultsArray);die;
    // var_dump($resultsArray);
    // $path = "functions/queries.json";
    // $contents = file_get_contents($path);
    // $contents = json_decode($contents, true);
    // $contents[] = $query['query'];
    // $contents = json_encode($contents);
    // file_put_contents($path, $contents);

    //Get HTML for the cards
    return createCards($resultsArray, $templates, $preset, $record_total);
}

/**
 * Creates the HTML for type of cards specified in $templates
 *
 * \param $results : Array of results that the query returned
 * \param $templates : Array of the type of cards to make
 * \param $preset :
 */
function createCards($results, $templates, $preset = 'default', $count = 0){
//    print_r($results);die;
    $cards = Array();
    $formattedData = array();   // data formatted to be turned into csv

    foreach ($templates as $template) {
        $cards[$template] = array();
    }
    $cards['total'] =  $count;

    // use same people display for people in single project
    if($preset == "singleProject") $preset = "people";


    $first = true;  // need to know if first to add table headers

    foreach ($results as $index => $record) {  ///foreach result
        switch ($preset){
            case 'people':
                // print_r($record);die;
                //Person Name
                $name = $record['name1']['value'];
                // $nameArray = explode(' ', $name);
                // $firstName = preg_replace('/\W\w+\s*(\W*)$/', '$1', $name);
                // $lastName = $nameArray[count($nameArray)-1];

                //Person QID
                $personUrl = $record['agent']['value'];
                $xplode = explode('/', $personUrl);
                $personQ = end($xplode);
                $person_url = BASE_URL . "record/person/" . $personQ;


                //Person Sex
                $sex = "";
                if (isset($record['sex1']) && isset($record['sex1']['value'])){
                    if($record['sex1']['value'] != ''){
                        $sex = $record['sex1']['value'];
                    }
                }

                //Person Status
                $status = '';
                $statusCount = 0;
                if (isset($record['status1']) && isset($record['status1']['value'])){
                    $statusArray = explode('||', $record['status1']['value']);

                    foreach ($statusArray as $stat) {
                        if (!empty($stat)){
                            if ($statusCount > 0){
                                $status .= ", $stat";
                            } else {
                                $status .= "$stat";
                            }
                            $statusCount++;
                        }
                    }
                }

                //Person location
                $places = '';
                $placesCount = 0;
                if (isset($record['place1']) && isset($record['place1']['value'])){
                    $placesArray = explode('||', $record['place1']['value']);

                    foreach ($placesArray as $place) {
                        if (!empty($place)){
                            if ($placesCount > 0){
                                $places .= ", $place";
                            } else {
                                $places .= "$place";
                            }
                            $placesCount++;
                        }
                    }
                }

                //Date Range
                $startYear = '';
                if (isset($record['startyear1']) && isset($record['startyear1']['value'])){
                    $startYears = explode('||', $record['startyear1']['value']);
                    $startYear = min($startYears);
                }

                $endYear = '';
                if (isset($record['endyear1']) && isset($record['endyear1']['value'])){
                    $endYears = explode('||', $record['endyear1']['value']);
                    $endYear = max($endYears);
                }

                $dateRange = '';
                if ($startYear != '' && $endYear != ''){
                    $dateRange = "$startYear - $endYear";
                } elseif ($endYear == ''){
                    $dateRange = $startYear;
                } elseif ($startYear == '') {
                    $dateRange = $endYear;
                }

                //Connection counts
                if(isset($record['countpeople']) && isset($record['countpeople']['value'])){
                    $countpeople = $record['countpeople']['value'];
                } else {
                    $countpeople = '';
                }
                if(isset($record['countevent']) && isset($record['countevent']['value'])){
                    $countevent = $record['countevent']['value'];
                } else {
                    $countevent = '';
                }
                if(isset($record['countplace']) && isset($record['countplace']['value'])){
                    $countplace = $record['countplace']['value'];
                } else {
                    $countplace = '';
                }
                if(isset($record['countsource']) && isset($record['countsource']['value'])){
                    $countsource = $record['countsource']['value'];
                } else {
                    $countsource = '';
                }

                //Connection HTML
                $connection_lists = Array(
                    '<h1>'.$countpeople.' Connected People</h1><ul><li>Person Name <span>(Wife)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Brother brother brother)</span> <div id="arrow"></div></li><li>Person Name <span>(Relation)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Father)</span> <div id="arrow"></div></li><li>Person Name <span>(Mother)</span> <div id="arrow"></div></li><li>View All People Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countplace.' Connected Places</h1><ul><li>Place Name <div id="arrow"></div></li><li>Place Name is Longer<div id="arrow"></div></li><li>Place Name <div id="arrow"></div></li><li>View All Place Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countevent.' Connected Events</h1><ul><li>Event Name <div id="arrow"></div></li><li>Event Name is Longer<div id="arrow"></div></li><li>Event Name <div id="arrow"></div></li><li>View All Event Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countsource.' Connected Sources</h1><ul><li>Source Name <div id="arrow"></div></li><li>Source Name is Longer<div id="arrow"></div></li><li>Source Name <div id="arrow"></div></li><li>View All Source Connections <div id="arrow"></div></li></ul>'
                );

                $connections = '<div class="connectionswrap"><div class="connections"><div class="card-icons"><img src="../assets/images/Person-dark.svg"><span>'.$countpeople.'</span><div class="connection-menu">'.$connection_lists[0].
                    '</div></div><div class="card-icons"><img src="../assets/images/Place-dark.svg"><span>'.$countplace.'</span><div class="connection-menu">'.$connection_lists[1].
                    '</div></div><div class="card-icons"><img src="../assets/images/Event-dark.svg"><span>'.$countevent.'</span><div class="connection-menu">'.$connection_lists[2].
                    '</div></div><div class="card-icons"><img src="../assets/images/Source-dark.svg"><span>'.$countsource.'</span><div class="connection-menu">'.$connection_lists[3].
                    '</div></div></div></div>';


                // create the html for each template
                foreach ($templates as $template) {
                    if ($template == 'gridCard'){

                        $sexHtml = '';
                        if ($sex != ''){
                            $sexHtml = "<p><span>Sex: </span>$sex</p>";
                        }

                        $statusHtml = '';
                        // if a person has multiple statuses, display them in a tooltip
                        if ($statusCount == 1){
                            $statusHtml = "<p><span>Person Status: </span>$status</p>";
                        }
                        if ($statusCount > 1){
                            $statusHtml = "<p><span>Person Status: </span><span class='multiple'>Multiple<span class='tooltip'>$status</span></span></p>";
                        }

                        $placesHtml = '';
                        if ($placesCount == 1){
                            $placesHtml = "<p><span>Place: </span>$places</p>";
                        }
                        if ($placesCount > 1){
                            $placesHtml = "<p><span>Place: </span><span class='multiple'>Multiple<span class='tooltip'>$places</span></span></p>";
                        }

                        $dateRangeHtml = '';
                        if ($dateRange != ''){
                            $dateName = ($startYear != '' && $endYear != '') ? "Date Range" : "Date";
                            $dateRangeHtml = "<p><span>$dateName: </span>$dateRange</p>";
                        }

                        $card_icon_url = BASE_IMAGE_URL . 'Person-light.svg';

                        $card = <<<HTML
<li>
    <a href='$person_url'>
        <div class='container card-image'>
            <p>$name</p>
            <img src='$card_icon_url'>
        </div>

        <div class="content-wrap">
            <div class='container cards'>
                <div class='card-info'>
                    $sexHtml
                    $statusHtml
                    $placesHtml
                    $dateRangeHtml
                </div>
            </div>
            $connections
        </div>
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
                // print_r($record);die;

                //Place name
                $name = $record['placeLabel']['value'];

                //Place URL
                $placeUrl = $record['place']['value'];
                $xplode = explode('/', $placeUrl);
                $placeQ = end($xplode); //qid

                //Place Type
                $type = "";
                if (isset($record['placetype']) && isset($record['placetype']['value'])){
                    if($record['placetype']['value'] != ''){
                        $type = $record['placetype']['value'];
                    }
                }

                //Located In
                $located = "";
                if (isset($record['locatedInLabel']) && isset($record['locatedInLabel']['value'])){
                    if($record['locatedInLabel']['value'] != ''){
                        $located = $record['locatedInLabel']['value'];
                    }
                }

                //Counts for connections
                if(isset($record['countpeople']) && isset($record['countpeople']['value'])){
                    $countpeople = $record['countpeople']['value'];
                } else {
                    $countpeople = '';
                }
                if(isset($record['countevent']) && isset($record['countevent']['value'])){
                    $countevent = $record['countevent']['value'];
                } else {
                    $countevent = '';
                }
                if(isset($record['countplace']) && isset($record['countplace']['value'])){
                    $countplace = $record['countplace']['value'];
                } else {
                    $countplace = '';
                }
                if(isset($record['countsource']) && isset($record['countsource']['value'])){
                    $countsource = $record['countsource']['value'];
                } else {
                    $countsource = '';
                }
                 if(isset($record['type']) && isset($record['type']['value'])){
                    $placeType = $record['type']['value'];
                } else {
                    $placeType = '';
                }
                if(isset($record['geonames']) && isset($record['geonames']['value'])){
                    $geonames = $record['geonames']['value'];
                } else {
                    $geonames = '';
                }
                if(isset($record['code']) && isset($record['code']['value'])){
                    $code = $record['code']['value'];
                } else {
                    $code = '';
                }

                //Connection html
                $connection_lists = Array(
                    '<h1>'.$countpeople.' Connected People</h1><ul><li>Person Name <span>(Wife)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Brother brother brother)</span> <div id="arrow"></div></li><li>Person Name <span>(Relation)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Father)</span> <div id="arrow"></div></li><li>Person Name <span>(Mother)</span> <div id="arrow"></div></li><li>View All People Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countplace.' Connected Places</h1><ul><li>Place Name <div id="arrow"></div></li><li>Place Name is Longer<div id="arrow"></div></li><li>Place Name <div id="arrow"></div></li><li>View All Place Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countevent.' Connected Events</h1><ul><li>Event Name <div id="arrow"></div></li><li>Event Name is Longer<div id="arrow"></div></li><li>Event Name <div id="arrow"></div></li><li>View All Event Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countsource.' Connected Sources</h1><ul><li>Source Name <div id="arrow"></div></li><li>Source Name is Longer<div id="arrow"></div></li><li>Source Name <div id="arrow"></div></li><li>View All Source Connections <div id="arrow"></div></li></ul>'
                );

                $connections = '<div class="connectionswrap"><div class="connections"><div class="card-icons"><img src="../assets/images/Person-dark.svg"><span>'.$countpeople.'</span><div class="connection-menu">'.$connection_lists[0].
                    '</div></div><div class="card-icons"><img src="../assets/images/Place-dark.svg"><span>'.$countplace.'</span><div class="connection-menu">'.$connection_lists[1].
                    '</div></div><div class="card-icons"><img src="../assets/images/Event-dark.svg"><span>'.$countevent.'</span><div class="connection-menu">'.$connection_lists[2].
                    '</div></div><div class="card-icons"><img src="../assets/images/Source-dark.svg"><span>'.$countsource.'</span><div class="connection-menu">'.$connection_lists[3].
                    '</div></div></div></div>';


                // create the html for each template
                foreach ($templates as $template) {
                    if ($template == 'gridCard'){

                        $typeHtml = '';
                        if ($placeType != ''){
                            $typeHtml = "<p><span>Type: </span>$placeType</p>";
                        }

                        $locatedHtml = '';
                        if ($located != ''){
                            $locatedHtml = "<p><span>Located In: </span>$located</p>";
                        }

                        $geonamesHtml = '';
                        if ($geonames != ''){
                            $geonames = "<p><span>Geoname Identifier: </span>$geonames</p>";
                        }

                        $codeHtml = '';
                        if ($code != ''){
                            $codeHtml = "<p><span>Modern Country Code: </span>$code</p>";
                        }

                        $card_icon_url = BASE_IMAGE_URL . 'Place-light.svg';
                        $place_url = BASE_URL . "record/place/" . $placeQ;

                        $card = <<<HTML
<li>
    <a href='$place_url'>
        <div class='container card-image'>
            <p>$name</p>
            <img src='$card_icon_url'>
        </div>
        <div class="content-wrap">
            <div class='container cards'>
                <div class='card-info'>
                    $typeHtml
                    $locatedHtml
                    $geonamesHtml
                    $codeHtml
                </div>
            </div>
            $connections
        </div>
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
                $name = $record['eventlab']['value'];

                //Event URL
                $eventUrl = $record['event']['value'];
                $xplode = explode('/', $eventUrl);
                $eventQ = end($xplode); //qid

                //Event Type
                $type = "Unidentified";
                if (isset($record['eventtypeLabel']) && isset($record['eventtypeLabel']['value'])){
                    if($record['eventtypeLabel']['value'] != ''){
                        $type = $record['eventtypeLabel']['value'];
                    }
                }

                //Event Roles
                $roles = '';
                $rolesCount = 0;
                if (isset($record['roles']) && isset($record['roles']['value'])){
                    $rolesArray = explode('||', $record['roles']['value']);

                    foreach ($rolesArray as $role) {
                        if (!empty($role)){
                            if ($rolesCount > 0){
                                $roles .= ", $role";
                            } else {
                                $roles .= "$role";
                            }
                            $rolesCount++;
                        }
                    }
                }

                // Event Places
                $places = '';
                $placesCount = 0;
                if (isset($record['places']) && isset($record['places']['value'])){
                    $placesArray = explode('||', $record['places']['value']);

                    foreach ($placesArray as $place) {
                        if (!empty($place)){
                            if ($placesCount > 0){
                                $places .= ", $place";
                            } else {
                                $places .= "$place";
                            }
                            $placesCount++;
                        }
                    }
                }

                //Event Start Year
                $startYear = '';
                if (isset($record['startyear']) && isset($record['startyear']['value'])){
                    $startYears = explode('||', $record['startyear']['value']);
                    $startYear = min($startYears);
                }

                //Event End Year
                $endYear = '';
                if (isset($record['endyear']) && isset($record['endyear']['value'])){
                    $endYears = explode('||', $record['endyear']['value']);
                    $endYear = max($endYears);
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

                //Counts for connections
                if(isset($record['countpeople']) && isset($record['countpeople']['value'])){
                    $countpeople = $record['countpeople']['value'];
                } else {
                    $countpeople = '';
                }
                if(isset($record['countevent']) && isset($record['countevent']['value'])){
                    $countevent = $record['countevent']['value'];
                } else {
                    $countevent = '';
                }
                if(isset($record['countplace']) && isset($record['countplace']['value'])){
                    $countplace = $record['countplace']['value'];
                } else {
                    $countplace = '';
                }
                if(isset($record['countsource']) && isset($record['countsource']['value'])){
                    $countsource = $record['countsource']['value'];
                } else {
                    $countsource = '';
                }

                //Connection html
                $connection_lists = Array(
                    '<h1>'.$countpeople.' Connected People</h1><ul><li>Person Name <span>(Wife)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Brother brother brother)</span> <div id="arrow"></div></li><li>Person Name <span>(Relation)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Father)</span> <div id="arrow"></div></li><li>Person Name <span>(Mother)</span> <div id="arrow"></div></li><li>View All People Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countplace.' Connected Places</h1><ul><li>Place Name <div id="arrow"></div></li><li>Place Name is Longer<div id="arrow"></div></li><li>Place Name <div id="arrow"></div></li><li>View All Place Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countsource.' Connected Sources</h1><ul><li>Source Name <div id="arrow"></div></li><li>Source Name is Longer<div id="arrow"></div></li><li>Source Name <div id="arrow"></div></li><li>View All Source Connections <div id="arrow"></div></li></ul>'
                );
                //'<h1>'.$countevent.' Connected Events</h1><ul><li>Event Name <div id="arrow"></div></li><li>Event Name is Longer<div id="arrow"></div></li><li>Event Name <div id="arrow"></div></li><li>View All Event Connections <div id="arrow"></div></li></ul>',

                $connections = '<div class="connectionswrap"><div class="connections"><div class="card-icons"><img src="../assets/images/Person-dark.svg"><span>'.$countpeople.'</span><div class="connection-menu">'.$connection_lists[0].
                    '</div></div><div class="card-icons"><img src="../assets/images/Place-dark.svg"><span>'.$countplace.'</span><div class="connection-menu">'.$connection_lists[1].
                    '</div></div><div class="card-icons"><img src="../assets/images/Source-dark.svg"><span>'.$countsource.'</span><div class="connection-menu">'.$connection_lists[2].
                    '</div></div></div></div>';
                //'</div></div><div class="card-icons"><img src="../assets/images/Event-dark.svg"><span>'.$countevent.'</span><div class="connection-menu">'.$connection_lists[2].

                // create the html for each template
                foreach ($templates as $template) {
                    if ($template == 'gridCard'){

                        $typeHtml = "<p><span>Type: </span>$type</p>";

                        $rolesHtml = '';
                        // Check for multiple roles
                        if ($rolesCount == 1){
                            $rolesHtml = "<p><span>Role: </span>$roles</p>";
                        }
                        if ($rolesCount > 1){
                            $rolesHtml = "<p><span>Role: </span><span class='multiple'>Multiple<span class='tooltip'>$roles</span></span></p>";
                        }
                        // Check for multiple places
                        $placesHtml = '';
                        if ($placesCount == 1){
                            $placesHtml = "<p><span>Place: </span>$places</p>";
                        }
                        if ($placesCount > 1){
                            $placesHtml = "<p><span>Place: </span><span class='multiple'>Multiple<span class='tooltip'>$places</span></span></p>";
                        }

                        $dateRangeHtml = '';
                        if ($dateRange != ''){
                            $dateName = ($startYear != '' && $endYear != '') ? "Date Range" : "Date";
                            $dateRangeHtml = "<p><span>$dateName: </span>$dateRange</p>";
                        }

                        $card_icon_url = BASE_IMAGE_URL . 'Event-light.svg';
                        $event_url = BASE_URL . "record/event/" . $eventQ;



                        $card = <<<HTML
<li>
    <a href='$event_url'>
        <div class='container card-image'>
            <p>$name</p>
            <img src='$card_icon_url'>
        </div>
        <div class="content-wrap">
            <div class='container cards'>
                <div class='card-info'>
                    $typeHtml
                    $rolesHtml
                    $placesHtml
                    $dateRangeHtml
                </div>

            </div>
            $connections
        </div>
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
                $name = $record['sourceLabel']['value'];

                //Source URL
                $sourceUrl = $record['source']['value'];
                $xplode = explode('/', $sourceUrl);
                $sourceQ = end($xplode); //qid

                //Source Type
                $type = "Unidentified";
                if (isset($record['sourcetypeLabel']) && isset($record['sourcetypeLabel']['value'])){
                    if($record['sourcetypeLabel']['value'] != ''){
                        $type = $record['sourcetypeLabel']['value'];
                    }
                }

                //Source Project
                $project = "";
                if (isset($record['projectLabel']) && isset($record['projectLabel']['value'])){
                    if($record['projectLabel']['value'] != ''){
                        $project = $record['projectLabel']['value'];
                    }
                }

                // description
                if(isset($record['desc']) && isset($record['desc']['value'])){
                    $desc = $record['desc']['value'];
                } else {
                    $desc = '';
                }

                // secondary source
                if(isset($record['secondarysource']) && isset($record['secondarysource']['value'])){
                    $secondarysource = $record['secondarysource']['value'];
                } else {
                    $secondarysource = '';
                }

                //Counts for connections
                if(isset($record['countpeople']) && isset($record['countpeople']['value'])){
                    $countpeople = $record['countpeople']['value'];
                } else {
                    $countpeople = '';
                }
                if(isset($record['countevent']) && isset($record['countevent']['value'])){
                    $countevent = $record['countevent']['value'];
                } else {
                    $countevent = '';
                }
                if(isset($record['countplace']) && isset($record['countplace']['value'])){
                    $countplace = $record['countplace']['value'];
                } else {
                    $countplace = '';
                }
                if(isset($record['countsource']) && isset($record['countsource']['value'])){
                    $countsource = $record['countsource']['value'];
                } else {
                    $countsource = '';
                }



                //Connection html
                $connection_lists = Array(
                    '<h1>'.$countpeople.' Connected People</h1><ul><li>Person Name <span>(Wife)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Brother brother brother)</span> <div id="arrow"></div></li><li>Person Name <span>(Relation)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Father)</span> <div id="arrow"></div></li><li>Person Name <span>(Mother)</span> <div id="arrow"></div></li><li>View All People Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countplace.' Connected Places</h1><ul><li>Place Name <div id="arrow"></div></li><li>Place Name is Longer<div id="arrow"></div></li><li>Place Name <div id="arrow"></div></li><li>View All Place Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countevent.' Connected Events</h1><ul><li>Event Name <div id="arrow"></div></li><li>Event Name is Longer<div id="arrow"></div></li><li>Event Name <div id="arrow"></div></li><li>View All Event Connections <div id="arrow"></div></li></ul>',
                    '<h1>'.$countsource.' Connected Sources</h1><ul><li>Source Name <div id="arrow"></div></li><li>Source Name is Longer<div id="arrow"></div></li><li>Source Name <div id="arrow"></div></li><li>View All Source Connections <div id="arrow"></div></li></ul>'
                );

                $connections = '<div class="connectionswrap"><div class="connections"><div class="card-icons"><img src="../assets/images/Person-dark.svg"><span>'.$countpeople.'</span><div class="connection-menu">'.$connection_lists[0].
                    '</div></div><div class="card-icons"><img src="../assets/images/Place-dark.svg"><span>'.$countplace.'</span><div class="connection-menu">'.$connection_lists[1].
                    '</div></div><div class="card-icons"><img src="../assets/images/Event-dark.svg"><span>'.$countevent.'</span><div class="connection-menu">'.$connection_lists[2].
                    // '</div></div><div class="card-icons"><img src="../assets/images/Source-dark.svg"><span>'.$countsource.'</span><div class="connection-menu">'.$connection_lists[3].
                    '</div></div></div></div>';


                // create the html for each template
                foreach ($templates as $template) {
                    if ($template == 'gridCard'){

                        $typeHtml = "<p><span>Type: </span>$type</p>";

                        $projectHtml = '';
                        if ($project != ""){
                            $projectHtml = "<p><span>Project: </span>$project</p>";
                        }

                        $descHtml = '';
                        if ($desc != ""){
                            $descHtml = "<p><span>Description: </span>$desc</p>";
                        }

                        
                        $secondarysourceHtml = '';
                        if ($secondarysource != ""){
                            $secondarysourceHtml = "<p><span>Description: </span>$secondarysource</p>";
                        }


                        $card_icon_url = BASE_IMAGE_URL . 'Source-light.svg';
                        $source_url = BASE_URL . "record/source/" . $sourceQ;

                        $card = <<<HTML
<li>
    <a href='$source_url'>
        <div class='container card-image'>
            <p>$name</p>
            <img src='$card_icon_url'>
        </div>
        <div class="content-wrap">
            <div class='container cards'>
                <div class='card-info'>
                    $typeHtml
                    $projectHtml
                    $descHtml
                    $secondarysourceHtml
                </div>

            </div>
            $connections
        </div>
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
    <th class="secondarySource">SECONDARY SOURCE</th>
</tr>
HTML;
                            $cards['tableCard']['headers'] = $headers;
                            $cards['fields'] = ['NAME', 'TYPE', 'PROJECT', 'DESCRIPTION', 'SECONDARY SOURCE'];
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
    <td class='secondarySource'>
        <p><span class='first'>Secondary Source: </span>$secondarysource</p>
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
                            'DESCRIPTION' => $desc,
                            'SECONDARY SOURCE' => $secondarysource
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
            case 'projectAssoc':
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
                array_push($cards['projectAssoc'], $card);
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
                        $card = "<li>
                        <a href='".BASE_URL."project/$project'>
                        <div class='container cards'>
                            <h2 class='card-title'>$fullName</h2>
                            <div class='connections'>
                                $connections
                            </div>
                            <h4 class='card-view-story'>View Project <div class='view-arrow'></h4>
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

                    $connections = '<div class="connectionswrap"><div class="connections"><div class="card-icons"><img src="../assets/images/Person-dark.svg"><span>'.$countpeople.'</span><div class="connection-menu">'.$connection_lists[0].
                        '</div></div><div class="card-icons"><img src="../assets/images/Place-dark.svg"><span>'.$countplace.'</span><div class="connection-menu">'.$connection_lists[1].
                        '</div></div><div class="card-icons"><img src="../assets/images/Event-dark.svg"><span>'.$countevent.'</span><div class="connection-menu">'.$connection_lists[2].
                        '</div></div><div class="card-icons"><img src="../assets/images/Source-dark.svg"><span>'.$countsource.'</span><div class="connection-menu">'.$connection_lists[3].
                        '</div></div></div></div></div>';


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
        <div class="details">
            <div class="detail">
                <p class="detail-title">Person Status</p>
                <p>Enslaved</p>
            </div>
            <div class="detail">
                <p class="detail-title">Sex</p>
                <p>Unidentified</p>
            </div>
            <div class="detail">
                <p class="detail-title">Location</p>
                <p>Location Name</p>
            </div>
            <div class="detail">
                <p class="detail-title">Origin</p>
                <p>Location Name</p>
            </div>
            <div class="detail">
                <p class="detail-title">Date Range</p>
                <p>1840-1864</p>
            </div>
        </div>
        $connections
    </a>
</li>
HTML;

                    array_push($cards[$template], $card);
                }
                break;
            default:
                print_r($results);
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
