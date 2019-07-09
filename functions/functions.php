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

  ?agent wdt:P3/wdt:P2 wd:Q2;

         wdt:P82 ?name; #name is mandatory
            p:P3  ?object .
  ?object prov:wasDerivedFrom ?provenance .
  ?provenance pr:P35 ?reference .
  ?reference wdt:P7 wd:$Q_ID #include here the Q number of the project


  MINUS{ ?agent wdt:P39 wd:Q536 }. #remove all researchers

  OPTIONAL { ?agent wdt:P24 ?status.
            ?status rdfs:label ?statuslab}

  OPTIONAL { ?agent wdt:P17 ?sex.
            ?sex rdfs:label ?sexlab}

  OPTIONAL { ?agent wdt:P25 ?relations}.
  OPTIONAL { ?agent wdt:P88 ?relations}.

  OPTIONAL{ ?reference wdt:P8 ?event.
            ?event  wdt:P13 ?startdate.
           BIND(str(YEAR(?startdate)) AS ?startyear).
           OPTIONAL {?event wdt:P14 ?enddate.
           BIND(str(YEAR(?enddate)) AS ?endyear)}.
           OPTIONAL {?event wdt:P12 ?place.
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

    ?agent wdt:P3/wdt:P2 wd:Q2;

        wdt:P82 ?name; #name is mandatory
            p:P3  ?object .
    ?object prov:wasDerivedFrom ?provenance .
    ?provenance pr:P35 ?reference .
    ?reference wdt:P7 wd:$Q_ID

    OPTIONAL{?agent  wdt:P39 ?role}. #optional role
    MINUS{ ?agent wdt:P39 wd:Q536 }. #remove all researchers

    OPTIONAL { ?agent wdt:P24 ?status.
            ?status rdfs:label ?statuslab}

    OPTIONAL { ?agent wdt:P17 ?sex.
            ?sex rdfs:label ?sexlab}

    OPTIONAL { ?agent wdt:P88 ?match}.

    ?agent p:P82 ?statement.
    ?statement ps:P82 ?name.
    OPTIONAL{ ?statement pq:P30 ?event.
            ?event  wdt:P13 ?startdate.
            BIND(str(YEAR(?startdate)) AS ?startyear).
            OPTIONAL {?event wdt:P14 ?enddate.
        BIND(str(YEAR(?enddate)) AS ?endyear)}.
            OPTIONAL {?event wdt:P12 ?place.
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

                $genderQuery = "";
                if (isset($filtersArray['gender'])) {
                    $genders = $filtersArray['gender'];

                    if(count($filtersArray['gender']) > 1) {
                        // handle multiple gender (Male, Female, and Unidentified)
                    }
                    else { // handle single gender search
                        $gender = $genders[0]; //ex. Male
                        if (array_key_exists($gender, sexTypes)){
                            $qGender = sexTypes[$gender];
                            $genderQuery = "?agent wdt:P17 wd:$qGender .";
                        }
                    }
                }

                $nameQuery = "";
                if (isset($filtersArray['person'])){
                    $name = $filtersArray['person'][0];
                    $nameQuery = "FILTER regex(?name, '^$name', 'i') .";
                }


                $sourceQuery = "";
                if (isset($filtersArray['source']) && $filtersArray['source'] != ''){
                    $sourceQ = $filtersArray['source'][0];
                    $sourceQuery = "VALUES ?source {wd:$sourceQ} #Q number needs to be changed for every source.
                                    ?source wdt:P3 wd:Q16.
                                    ?people wdt:P3/wdt:P2 wd:Q2; #agent or subclass of agent
                                            ?property  ?object .
                                    ?object prov:wasDerivedFrom ?provenance .
                                    ?provenance pr:P35 ?source .
                                    ?people rdfs:label ?peoplename";
                }

                $ageQuery = "";
                if (isset($filtersArray['age_category'])){
                    $age = $filtersArray['age_category'][0];
                    if (array_key_exists($age, ageCategory)){
                        $qAge = ageCategory[$age];
                        $ageQuery = "?agent wdt:P32 wd:$qAge .";
                    }
                    
                }

                $ethnoQuery = "";
                if (isset($filtersArray['ethnodescriptor'])){
                    $ethno = $filtersArray['ethnodescriptor'][0];
                    if (array_key_exists($ethno, ethnodescriptor)){
                        $qEthno = ethnodescriptor[$ethno];
                        $ethnoQuery = "?agent wdt:P86 wd:$qEthno .";
                    }
                }

                $roleQuery = "";
                if (isset($filtersArray['role_types'])){
                    $role = $filtersArray['role_types'][0];
                    if (array_key_exists($role, roleTypes)){
                        $qRole = roleTypes[$role];
                        $roleQuery = "?agent wdt:P39 wd:$qRole .";
                    }
                    
                }

                // people connected to an event
                $eventQuery = "";
                if (isset($filtersArray['event']) && $filtersArray['event'] != ''){
                    $eventQ = $filtersArray['event'][0];
                    // $eventQuery = "VALUES ?event {wd:$eventQ} #Q number needs to be changed for every event.
                    //                 ?event wdt:P3 wd:Q34.
                    //                 ?event p:P38 ?statement.
                    //                 ?statement ps:P38 ?name.
                    //                 ?statement pq:P39 ?people.
                    //                 ?people rdfs:label ?peoplename";

                    $query['query'] = <<<QUERY
SELECT DISTINCT ?agent ?name (SHA512(CONCAT(STR(?people), STR(RAND()))) as ?random)

 WHERE
{
 VALUES ?event {wd:$eventQ} #Q number needs to be changed for every event.
  ?event wdt:P3 wd:Q34.
  ?event p:P38 ?statement.
  ?statement ps:P38 ?personname.
  ?statement pq:P39 ?agent.
  ?agent rdfs:label ?name.

  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}
QUERY;
                    array_push($queryArray, $query);
                    break;
                }



                $query['query'] = <<<QUERY
SELECT DISTINCT ?agent
(count(distinct ?people) as ?countpeople)
(count(distinct ?allevents) as ?countevent)
(count(distinct ?place) as ?countplace)
(count(distinct ?source) as ?countsource)

(group_concat(distinct ?name; separator = "||") as ?name) #name

(group_concat(distinct ?placelab; separator = "||") as ?place) #place

(group_concat(distinct ?statuslab; separator = "||") as ?status) #status

(group_concat(distinct ?sexlab; separator = "||") as ?sex) #Sex

(group_concat(distinct ?match; separator = "||") as ?closeMatch)

(group_concat(distinct ?startyear; separator = "||") as ?startyear)

(group_concat(distinct ?endyear; separator = "||") as ?endyear)

WHERE {

    $sourceQuery
    $eventQuery

    SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }

    ?agent wdt:P3/wdt:P2 wd:Q2; #agent or subclass of agent
            ?property  ?object .
        ?object prov:wasDerivedFrom ?provenance .
        ?provenance pr:P35 ?source .


        ?agent p:P82 ?statement.
    ?statement ps:P82 ?name.
    OPTIONAL{ ?statement pq:P30 ?recordeAt.
            bind(?recordedAt as ?allevents)}

    $genderQuery
    $nameQuery
    $ageQuery
    $ethnoQuery
    $roleQuery

    MINUS{ ?agent wdt:P39 wd:Q536 }. #remove all researchers

    OPTIONAL {?agent p:P39 ?statementrole.
            ?statementrole ps:P39 ?roles.
            ?statementrole pq:P98 ?roleevent.
            bind(?roleevent as ?allevents)

            }.

    OPTIONAL {?agent p:P24 ?statstatus.
            ?statstatus ps:P24 ?status.
            ?status rdfs:label ?statuslabel.
            ?statstatus pq:P99 ?statusevent.
            bind(?statusevent as ?allevents)}.


    OPTIONAL { ?agent wdt:P17 ?sex.
                ?sex rdfs:label ?sexlab}

    OPTIONAL { ?agent wdt:P88 ?match}.


    OPTIONAL{?allevents	wdt:P13 ?startdate.
            BIND(str(YEAR(?startdate)) AS ?startyear).
            OPTIONAL {?allevents wdt:P14 ?enddate.
            BIND(str(YEAR(?enddate)) AS ?endyear)}.
            OPTIONAL {?allevents wdt:P12 ?place.
                        ?place rdfs:label ?placelab}

            }.
    OPTIONAL {?agent wdt:P25 ?people}

} group by ?agent
order by ?agent

$limitQuery
offset $offset
QUERY;

                array_push($queryArray, $query);

                //Query for Total Count
                $query = array('query' => "");
                $query['query'] = <<<QUERY
SELECT DISTINCT ?agent
(count(distinct ?people) as ?countpeople)
(count(distinct ?allevents) as ?countevent)
(count(distinct ?place) as ?countplace)
(count(distinct ?source) as ?countsource)

(group_concat(distinct ?name; separator = "||") as ?name) #name

(group_concat(distinct ?placelab; separator = "||") as ?place) #place

(group_concat(distinct ?statuslab; separator = "||") as ?status) #status

(group_concat(distinct ?sexlab; separator = "||") as ?sex) #Sex

(group_concat(distinct ?match; separator = "||") as ?closeMatch)

(group_concat(distinct ?startyear; separator = "||") as ?startyear)

(group_concat(distinct ?endyear; separator = "||") as ?endyear)

WHERE {

    $sourceQuery
    $eventQuery

    SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }

    ?agent wdt:P3/wdt:P2 wd:Q2; #agent or subclass of agent
  		 ?property  ?object .
  	?object prov:wasDerivedFrom ?provenance .
  	?provenance pr:P35 ?source .


 	 ?agent p:P82 ?statement.
    ?statement ps:P82 ?name.
    OPTIONAL{ ?statement pq:P30 ?recordeAt.
            bind(?recordedAt as ?allevents)}

    $genderQuery
    $nameQuery
    $ageQuery
    $ethnoQuery
    $roleQuery

    MINUS{ ?agent wdt:P39 wd:Q536 }. #remove all researchers

    OPTIONAL {?agent p:P39 ?statementrole.
            ?statementrole ps:P39 ?roles.
            ?statementrole pq:P98 ?roleevent.
            bind(?roleevent as ?allevents)

            }.

    OPTIONAL {?agent p:P24 ?statstatus.
            ?statstatus ps:P24 ?status.
            ?status rdfs:label ?statuslabel.
            ?statstatus pq:P99 ?statusevent.
            bind(?statusevent as ?allevents)}.


    OPTIONAL { ?agent wdt:P17 ?sex.
                ?sex rdfs:label ?sexlab}

    OPTIONAL { ?agent wdt:P88 ?match}.


    OPTIONAL{?allevents	wdt:P13 ?startdate.
            BIND(str(YEAR(?startdate)) AS ?startyear).
            OPTIONAL {?allevents wdt:P14 ?enddate.
            BIND(str(YEAR(?enddate)) AS ?endyear)}.
            OPTIONAL {?allevents wdt:P12 ?place.
                        ?place rdfs:label ?placelab}

            }.
    OPTIONAL {?agent wdt:P25 ?people}

} group by ?agent
order by ?agent
QUERY;

                array_push($queryArray, $query);

                // print_r($queryArray);die;
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
                        $typeQuery = "?place wdt:P80 wd:$qType .";
                    }
                    
                }

                $query = array('query' => "");

                $query['query'] = <<<QUERY
SELECT ?place ?placeLabel ?locatedInLabel
(count(distinct ?person) as ?countpeople)
(count(distinct ?event) as ?countevent)
(count(distinct ?source) as ?countsource)

WHERE {
    ?event wdt:P3 wd:Q34;
        ?property  ?object .
        ?object prov:wasDerivedFrom ?provenance .
        ?provenance pr:P35 ?source .

        ?event wdt:P12 ?place;
            p:P38 ?statement.
        ?statement ps:P38 ?role.
        ?statement pq:P39 ?person.


    ?place rdfs:label ?placeLabel.

    $typeQuery

    OPTIONAL {?place wdt:P10 ?locatedIn}.
    SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
}GROUP BY ?place ?placeLabel ?locatedInLabel
order by ?placeLabel
$limitQuery
offset $offset
QUERY;

                array_push($queryArray, $query);

                $query = array('query' => "");
                $query['query'] = <<<QUERY
SELECT ?place ?placeLabel ?locatedInLabel
(count(distinct ?person) as ?countpeople)
(count(distinct ?event) as ?countevent)
(count(distinct ?source) as ?countsource)

WHERE {
    ?event wdt:P3 wd:Q34;
        ?property  ?object .
        ?object prov:wasDerivedFrom ?provenance .
        ?provenance pr:P35 ?source .

        ?event wdt:P12 ?place;
            p:P38 ?statement.
        ?statement ps:P38 ?role.
        ?statement pq:P39 ?person.


    ?place rdfs:label ?placeLabel.

    $typeQuery

    OPTIONAL {?place wdt:P10 ?locatedIn}.
    SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
}GROUP BY ?place ?placeLabel ?locatedInLabel
order by ?placeLabel
QUERY;

                array_push($queryArray, $query);

                break;
            case 'events':
                ///*********************************** */
                /// EVENTS
                ///*********************************** */

                //Filtering for Query
                $eventQuery = "";
                if (isset($filtersArray['event_type'])){
                    $type = $filtersArray['event_type'][0];
                    if (array_key_exists($type, eventTypes)){
                        $qType = eventTypes[$type];
                        $eventQuery = "?event wdt:P81 wd:$qType .";
                    }

                    // if (array_key_exists($eventType, eventTypes) ){
                    //     $qType = eventTypes[$eventType];
                    //     $eventQuery = "?event wdt:P81 wd:$qType .";
                    // } else {
                    //     continue;   // the event_type was not valid
                    // }
                }

                $dateRangeQuery = "";
                $from = '';
                $to = '';
                if (isset($filtersArray['eventDate'])){
                    $dateRange = $filtersArray['eventDate'][0];
                    //Have date range here ex. 1800-1900 so split it and create the query to add in
                    $dateArr = explode('-', $dateRange);
                    $from = $dateArr[0];
                    $to = $dateArr[1];
                    $dateRangeQuery = $dateRange;
                }

                $sourceQuery = "";
                if (isset($filtersArray['source']) && $filtersArray['source'] != ''){
                    $sourceQ = $filtersArray['source'][0];
                    $sourceQuery = "VALUES ?source {wd:$sourceQ} #Q number needs to be changed for every source.
                                    ?source wdt:P3 wd:Q16.
                                    ?source wdt:P8 ?event.
                                    ?event rdfs:label ?eventname";

                    $query['query'] = <<<QUERY
SELECT ?event ?eventLabel ?startyear ?endyear ?type ?eventtypeLabel
 (count(distinct ?people) as ?countpeople)
 (count(distinct ?event) as ?countervent)
 (count(distinct ?place) as ?countplace)
 (count(distinct ?source) as ?countsource)
 (group_concat(distinct ?placeLabel; separator = "||") as ?places)

WHERE {
  VALUES ?source {wd:$sourceQ} #Q number needs to be changed for every source.
  ?source wdt:P8 ?event.
  ?event rdfs:label ?eventlabel.
  ?event wdt:P81 ?type .
  ?type rdfs:label ?eventtypeLabel

  OPTIONAL {?event wdt:P12 ?place.
           ?place rdfs:label ?placeLabel}.
  OPTIONAL {?event wdt:P13 ?date.
           BIND(str(YEAR(?date)) AS ?startyear)}.
  OPTIONAL {?event wdt:P14 ?endDate
           BIND(str(YEAR(?endDate)) AS ?endyear)}.

    OPTIONAL {?event p:P38 ?roles.
           ?roles ps:P38 ?qualifier.
           ?roles pq:P39 ?people}.

  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
 }GROUP BY ?event ?eventLabel ?startyear ?endyear ?type ?eventtypeLabel
order by ?startyear
limit 12
offset 0

QUERY;

                    array_push($queryArray, $query);
                    break;
                }

                //Bad way of doing this but it works for now
                if($dateRangeQuery !== ""){
                    $query = array('query' => "");

                    $query['query'] = <<<QUERY
SELECT ?event ?eventLabel ?typeLabel ?startyear ?endyear
(count(distinct ?people) as ?countpeople)
(count(distinct ?event) as ?countevent)
(count(distinct ?place) as ?countplace)
(count(distinct ?source) as ?countsource)
(group_concat(distinct ?placeLabel; separator = "||") as ?places)

WHERE {
    ?event wdt:P3 wd:Q34;
        ?property  ?object;
            wdt:P13 ?date.
        ?object prov:wasDerivedFrom ?provenance .
        ?provenance pr:P35 ?source .

    ?event wdt:P81 ?type .

    $eventQuery
    OPTIONAL {?event wdt:P12 ?place.
            ?place rdfs:label ?placeLabel}.


    OPTIONAL {?event p:P38 ?roles.
            ?roles ps:P38 ?qualifier.
            ?roles pq:P39 ?people}.

    OPTIONAL {?event wdt:P14 ?endsAt.
                FILTER (?endsAt <= "1900-01-01T00:00:00Z"^^xsd:dateTime).#include here year range
                    BIND(str(YEAR(?endsAt)) AS ?endYear).
                }.
        FILTER (?date >= "1800-01-01T00:00:00Z"^^xsd:dateTime) .#include here year range
        FILTER (?date <= "1900-01-01T00:00:00Z"^^xsd:dateTime) .#include here year range
        BIND(str(YEAR(?date)) AS ?startyear).

        $sourceQuery

    SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
}GROUP BY ?event ?eventLabel ?typeLabel ?startyear ?endyear
order by ?startyear
$limitQuery
offset $offset
QUERY;

                    array_push($queryArray, $query);

                    $query = array('query' => "");
                    $query['query'] = <<<QUERY
SELECT ?event ?eventLabel ?typeLabel ?startyear ?endyear
(count(distinct ?people) as ?countpeople)
(count(distinct ?event) as ?countevent)
(count(distinct ?place) as ?countplace)
(count(distinct ?source) as ?countsource)
(group_concat(distinct ?placeLabel; separator = "||") as ?places)

WHERE {
    ?event wdt:P3 wd:Q34;
        ?property  ?object;
            wdt:P13 ?date.
        ?object prov:wasDerivedFrom ?provenance .
        ?provenance pr:P35 ?source .

    ?event wdt:P81 ?type .

    $eventQuery
    OPTIONAL {?event wdt:P12 ?place.
            ?place rdfs:label ?placeLabel}.


    OPTIONAL {?event p:P38 ?roles.
            ?roles ps:P38 ?qualifier.
            ?roles pq:P39 ?people}.

    OPTIONAL {?event wdt:P14 ?endsAt.
                FILTER (?endsAt <= "1900-01-01T00:00:00Z"^^xsd:dateTime).#include here year range
                    BIND(str(YEAR(?endsAt)) AS ?endYear).
                }.
        FILTER (?date >= "1800-01-01T00:00:00Z"^^xsd:dateTime) .#include here year range
        FILTER (?date <= "1900-01-01T00:00:00Z"^^xsd:dateTime) .#include here year range
        BIND(str(YEAR(?date)) AS ?startyear).

        $sourceQuery

    SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
}GROUP BY ?event ?eventLabel ?typeLabel ?startyear ?endyear
order by ?startyear
QUERY;

                    array_push($queryArray, $query);
                }
                else{
                    $query = array('query' => "");

                    $query['query'] = <<<QUERY
SELECT ?event ?eventLabel ?startyear ?endyear ?eventtypeLabel
(count(distinct ?people) as ?countpeople)
(count(distinct ?event) as ?countevent)
(count(distinct ?place) as ?countplace)
(count(distinct ?source) as ?countsource)
(group_concat(distinct ?placeLabel; separator = "||") as ?places)

WHERE {
    ?event wdt:P3 wd:Q34;
        ?property  ?object .
        ?object prov:wasDerivedFrom ?provenance .
        ?provenance pr:P35 ?source .
        ?event wdt:P81 ?eventtype .

            $eventQuery
            OPTIONAL {?event wdt:P12 ?place.
            ?place rdfs:label ?placeLabel}.
    OPTIONAL {?event wdt:P13 ?date.
            BIND(str(YEAR(?date)) AS ?startyear)}.
    OPTIONAL {?event wdt:P14 ?endDate
            BIND(str(YEAR(?endDate)) AS ?endyear)}.

    $sourceQuery

    SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
}GROUP BY ?event ?eventLabel ?startyear ?endyear ?eventtypeLabel
order by ?startyear
$limitQuery
offset $offset
QUERY;

                    array_push($queryArray, $query);

                    $query = array('query' => "");
                    $query['query'] = <<<QUERY
SELECT ?event ?eventLabel ?startyear ?endyear ?eventtypeLabel
(count(distinct ?people) as ?countpeople)
(count(distinct ?event) as ?countevent)
(count(distinct ?place) as ?countplace)
(count(distinct ?source) as ?countsource)
(group_concat(distinct ?placeLabel; separator = "||") as ?places)

WHERE {
    ?event wdt:P3 wd:Q34;
        ?property  ?object .
        ?object prov:wasDerivedFrom ?provenance .
        ?provenance pr:P35 ?source .
        ?event wdt:P81 ?eventtype .

            $eventQuery
            OPTIONAL {?event wdt:P12 ?place.
            ?place rdfs:label ?placeLabel}.
    OPTIONAL {?event wdt:P13 ?date.
            BIND(str(YEAR(?date)) AS ?startyear)}.
    OPTIONAL {?event wdt:P14 ?endDate
            BIND(str(YEAR(?endDate)) AS ?endyear)}.

    $sourceQuery

    SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
}GROUP BY ?event ?eventLabel ?startyear ?endyear ?eventtypeLabel
order by ?startyear
QUERY;

                    array_push($queryArray, $query);
                }

                break;
            case 'sources':
                ///*********************************** */
                /// SOURCES
                ///*********************************** */
                $query = array('query' => "");


                // searching for sources connected to an event
                $eventQuery = "";
                if (isset($filtersArray['event']) && $filtersArray['event'] != ''){
                    $eventQ = $filtersArray['event'][0];

                    $query['query'] = <<<QUERY
SELECT DISTINCT ?source ?sourceLabel ?projectLabel ?sourcetypeLabel

 (count(distinct ?agent) as ?countpeople)
 (count(distinct ?event) as ?countervent)
 (count(distinct ?place) as ?countplace)
 (count(distinct ?source) as ?countsource)
{
  VALUES ?event {wd:$eventQ} #Q number needs to be changed for every event.
  ?source wdt:P3 wd:Q16. #entity with provenance
  ?source wdt:P9 ?sourcetype.
  ?source wdt:P7 ?project.
  ?source wdt:P8 ?event.
  OPTIONAL{?event wdt:P12 ?place}.
  ?agent wdt:P3/wdt:P2 wd:Q2; #agent or subclass of agent
  		?property  ?object .
  ?object prov:wasDerivedFrom ?provenance .
  ?provenance pr:P35 ?source .


   SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }

}group by ?source ?sourceLabel ?projectLabel ?sourcetypeLabel
order by ?sourceLabel
QUERY;

                    array_push($queryArray, $query);
                    break;
                }





                $query['query'] = <<<QUERY
SELECT DISTINCT ?source ?sourceLabel ?projectLabel ?sourcetypeLabel

(count(distinct ?agent) as ?countpeople)
(count(distinct ?event) as ?countevent)
(count(distinct ?place) as ?countplace)
(count(distinct ?source) as ?countsource)
{
    ?source wdt:P3 wd:Q16. #entity with provenance
    ?source wdt:P9 ?sourcetype.
    ?source wdt:P7 ?project.
    ?source wdt:P8 ?event.
    OPTIONAL{?event wdt:P12 ?place}.
    ?agent wdt:P3/wdt:P2 wd:Q2; #agent or subclass of agent
            ?property  ?object .
    ?object prov:wasDerivedFrom ?provenance .
    ?provenance pr:P35 ?source .


    SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }

}group by ?source ?sourceLabel ?projectLabel ?sourcetypeLabel
order by ?sourceLabel
limit 12
offset 0
QUERY;

                array_push($queryArray, $query);
                break;
            case 'projects':
                $query = array('query' => "");
//                $query['query'] =
//                    'SELECT ?project ?projectLabel  WHERE {
//                      ?project wdt:P3 wd:Q264
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
        ?agent wdt:P3/wdt:P2 wd:Q2;        #find agents
                p:P3  ?object .
        ?object prov:wasDerivedFrom ?provenance .
        ?provenance pr:P35 ?reference .
        ?reference wdt:P7 ?project

        SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }
    }
    GROUP BY ?project ?projectLabel
QUERY;
                array_push($queryArray, $query);
                $query = array('query' => "");
                $query['query'] = <<<QUERY
SELECT ?project ?projectLabel  (COUNT(*) AS ?eventcount)
    WHERE {
        ?project wdt:P3 wd:Q264.         #find projects
        ?item wdt:P3 wd:Q34;        #find events
            p:P3  ?object .
        ?object prov:wasDerivedFrom ?provenance .
        ?provenance pr:P35 ?reference .
        ?reference wdt:P7 ?project;
                    wdt:P7 wd:$qid
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
        ?project wdt:P3 wd:Q264.         #find projects
        ?item wdt:P3 wd:Q50;        #find places
            p:P3  ?object .
        ?object prov:wasDerivedFrom ?provenance .
        ?provenance pr:P35 ?reference .
        ?reference wdt:P7 ?project;
                    wdt:P7 wd:$qid
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
    ?project wdt:P3 wd:Q264         #find projects
    SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }
    }
QUERY;
                array_push($queryArray, $query);
                $query = array('query' => "");
                $query['query'] = <<<QUERY
SELECT ?project ?projectLabel  (COUNT(distinct ?agent) AS ?count)
    WHERE {
        ?project wdt:P3 wd:Q264.         #find projects
        ?agent wdt:P3/wdt:P2 wd:Q2;        #find agents
            p:P3  ?object .
        ?object prov:wasDerivedFrom ?provenance .
        ?provenance pr:P35 ?reference .
        ?reference wdt:P7 ?project
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
        ?project wdt:P3 wd:Q264.         #find projects
        ?item wdt:P3 wd:Q34;        #find events
            p:P3  ?object .
        ?object prov:wasDerivedFrom ?provenance .
        ?provenance pr:P35 ?reference .
        ?reference wdt:P7 ?project
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
        ?project wdt:P3 wd:Q264.         #find projects
        ?item wdt:P3 wd:Q50;        #find places
            p:P3  ?object .
        ?object prov:wasDerivedFrom ?provenance .
        ?provenance pr:P35 ?reference .
        ?reference wdt:P7 ?project
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
?agent wdt:P3/wdt:P2 wd:Q2 . #all agents and people
?agent wikibase:statements ?statementcount . #with at least 4 core fields
FILTER (?statementcount >3  ).
?agent wdt:P88 ?match. #and they have a match
SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }
} ORDER BY ?random
LIMIT 8
QUERY;
                }
                if($templates[0] == 'Place'){
                    $query = array('query' => "");
                    $query['query'] = <<<QUERY
SELECT DISTINCT ?place ?placeLabel (SHA512(CONCAT(STR(?place), STR(RAND()))) as ?random) WHERE {
?place wdt:P3 wd:Q50 .
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

    ?event wdt:P3 wd:Q34;
            rdfs:label ?elabel;
                wdt:P81 ?type;
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

        if ($first){
            $resultsArray = $result;
            $first = false;

            if ($oneQuery){ // this is needed for the search page counter to be working
                $record_total = count($result);
            }
        } else {
            if($preset == 'people' || $preset == 'places' || $preset == 'events' || $preset == 'sources' || $preset == 'singleProject'){
                //Get the count of all the results
                //for people, places, events, sources, and singleProject
                $record_total += count($result);
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
    // return json_encode($resultsArray);
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
                $name = $record['name']['value'];
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
                if (isset($record['sex']) && isset($record['sex']['value'])){
                    if($record['sex']['value'] != ''){
                        $sex = $record['sex']['value'];
                    }
                }

                //Person Status
                $status = '';
                $statusCount = 0;
                if (isset($record['status']) && isset($record['status']['value'])){
                    $statusArray = explode('||', $record['status']['value']);

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
                if (isset($record['place']) && isset($record['place']['value'])){
                    $placesArray = explode('||', $record['place']['value']);

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
                if (isset($record['startyear']) && isset($record['startyear']['value'])){
                    $startYears = explode('||', $record['startyear']['value']);
                    $startYear = min($startYears);
                }

                $endYear = '';
                if (isset($record['endyear']) && isset($record['endyear']['value'])){
                    $endYears = explode('||', $record['endyear']['value']);
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
                        if ($type != ''){
                            $typeHtml = "<p><span>Type: </span>$type</p>";
                        }


                        $locatedHtml = '';
                        if ($located != ''){
                            $locatedHtml = "<p><span>Located In: </span>$located</p>";
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
</tr>
HTML;
                            $cards['tableCard']['headers'] = $headers;
                            $cards['fields'] = ['NAME', 'TYPE', 'LOCATED'];

                        }


                        $card = <<<HTML
<tr class='tr' data-qid='$placeQ'>
    <td class='name td-name'>
        <span>$name</span>
    </td>
    <td class='type'>
        <p><span class='first'>Type: </span>$type</p>
    </td>
    <td class='located'>
        <p><span class='first'>Located: </span>$located</p>
    </td>
    <td class='meta'>

    </td>
</tr>
HTML;

                        // format this row for csv download
                        $formattedData[$placeQ] = array(
                            'NAME' => $name,
                            'TYPE' => $type,
                            'LOCATED' => $located,
                        );
                    }


                    array_push($cards[$template], $card);
                }
                break;
            case 'events':
                //Event name
                $name = $record['eventLabel']['value'];

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
</tr>
HTML;
                            $cards['tableCard']['headers'] = $headers;
                            $cards['fields'] = ['NAME', 'TYPE', 'PROJECT'];
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
    <td class='meta'>

    </td>
</tr>
HTML;

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
                    $cardType = $template;
                    $iconURL = BASE_IMAGE_URL . $template . "-light.svg";
                    $link = BASE_URL . "record/" . strtolower($cardType) . "/" . $qid;
                    $background = "background-image: url(" . BASE_IMAGE_URL . $cardType . "Card.jpg)";
                    $card = <<<HTML
<li style="$background">
    <a href="$link">
        <div class="cards">
            <img src="$iconURL" alt="Person icon">
            <h3>$cardTitle</h3>
        </div>
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
