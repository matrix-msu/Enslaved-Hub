<?php
//require_once(dirname(__FILE__).'/../config.php');ffds
//require_once(dirname(__FILE__).'/../wikiconstants/properties.php');

function callAPI($url,$limit,$offset){
    $url.='&format=json';
    $json = file_get_contents($url);
  return $json;
}

//get all agents numbers
function queryAllAgentsCounter(){
  $query='SELECT  (count(distinct ?agent) as ?count)
  WHERE {?agent wdt:P3/wdt:P2 wd:Q2;
 		 wdt:P39 ?role;
  FILTER(?role != wd:Q536). #agent cannot be a researcher
  }';
  $encode=urlencode($query);
  $call=API_URL.$encode;
  $res=callAPI($call,'','');

  $res= json_decode($res);
  if (!empty($res)){
    return $res->results->bindings[0]->count->value;
  }else{
    return $res;
  }
}

//get all events counter
function queryEventCounter(){
  $query='SELECT (COUNT(?item) AS ?count) WHERE {?item wdt:P3 wd:Q34 .}';
  $encode=urlencode($query);
  $call=API_URL.$encode;
  $res=callAPI($call,'','');

  $res= json_decode($res);

  if (!empty($res)){
    return $res->results->bindings[0]->count->value;
  }else{
    return $res;
  }
}

//get all places counter
function queryPlaceCounter(){
  $query='SELECT (COUNT(?item) AS ?count) WHERE {?item wdt:P3 wd:Q50 .}';
  $encode=urlencode($query);
  $call=API_URL.$encode;
  $res=callAPI($call,'','');

  $res= json_decode($res);

  if (!empty($res)){
    return $res->results->bindings[0]->count->value;
  }else{
    return $res;
  }
}

//get all contributing projects counter
function queryProjectsCounter(){
  $query='SELECT (COUNT(?item) AS ?count) WHERE {?item wdt:P3 wd:Q264 .}';
  $encode=urlencode($query);
  $call=API_URL.$encode;
  $res=callAPI($call,'','');

  $res= json_decode($res);

  if (!empty($res)){
    return $res->results->bindings[0]->count->value;
  }else{
    return $res;
  }
}

//get entity with provenance  counter
function querySourceCounter(){
  $query='SELECT (COUNT(?item) AS ?count) WHERE {?item wdt:P3 wd:Q16 .}';
  $encode=urlencode($query);
  $call=API_URL.$encode;
  $res=callAPI($call,'','');

  $res= json_decode($res);

  if (!empty($res)){
    return $res->results->bindings[0]->count->value;
  }else{
    return $res;
  }
}

//get counter for people, event, sources, projects...
function counterofAllitems(){
  $query='SELECT (COUNT(?item) AS ?count) WHERE
  {{?item wdt:P3 wd:Q264 .}
   UNION{ ?item wdt:P3/wdt:P2 wd:Q2 .}
   UNION{ ?item wdt:P3 wd:Q16 .}
   UNION{?item wdt:P3 wd:Q50 .}
   UNION{?item wdt:P3 wd:Q34 .}

  }';
  $encode=urlencode($query);
  $call=API_URL.$encode;
  $res=callAPI($call,'','');

  $res= json_decode($res);

  if (!empty($res)){
    return $res->results->bindings[0]->count->value;
  }else{
    return $res;
  }
}
//counter of a specific gender
function counterOfGender(){
  $query="SELECT (COUNT(?item) AS ?count) WHERE {
    ?item wdt:P3/wdt:P2  wd:Q2 .
  	?item wdt:P17 wd:".$_GET['gender']."}";
  $encode=urlencode($query);
  $call=API_URL.$encode;
  $res=callAPI($call,'','');

  $res= json_decode($res);

  if (!empty($res)){
    return $res->results->bindings[0]->count->value;
  }else{
    return $res;
  }
}

//counter of all genders
function counterOfAllGenders(){
    $query="SELECT ?sex ?sexLabel ?count
      WHERE
      {
        {
          SELECT ?sex (COUNT(?human) AS ?count) WHERE {
    		?human wdt:P3/wdt:P2  wd:Q2 .
            ?human wdt:P17 ?sex.
          }
          GROUP BY ?sex
        }
        SERVICE wikibase:label { bd:serviceParam wikibase:language \"[AUTO_LANGUAGE],en\". }
      }
      ORDER BY DESC(?count)
      LIMIT 100";
    $encode=urlencode($query);
    $call=API_URL.$encode;
    $res=callAPI($call,'','');

    $res= json_decode($res);

    if (!empty($res)){
        return json_encode($res->results->bindings);
    }else{
        return $res;
    }
}

//get the roles and their counts
function counterOfRole(){
  $query="SELECT ?role ?roleLabel ?count
      WHERE
      {
        {
          SELECT ?role (COUNT(?human) AS ?count) WHERE {
            ?human wdt:P3 wd:Q602.
            ?human wdt:P39 ?role.
          }
          GROUP BY ?role
        }
        SERVICE wikibase:label { bd:serviceParam wikibase:language \"[AUTO_LANGUAGE],en\". }
      }
      ORDER BY DESC(?count)
      LIMIT 100
      ";
  $encode=urlencode($query);
  $call=API_URL.$encode;
  $res=callAPI($call,'','');

  $res= json_decode($res);

  if (!empty($res)){
    return json_encode($res->results->bindings);
  }else{
    return $res;
  }
}

// Count the number of people in each age category
function counterOfAge(){
//  $adultQueries = '';
//
//  foreach (AgeCategoryAdult as $qAge) {
//    $adultQueries .= "?person wdt:P32 wd:$qAge. ";
//  }
//  echo $adultQueries;
//  die;

  $InfantQuery = 'SELECT ?person ?personLabel ?age ?agecategoryLabel ?name ?originLabel
                        (group_concat(distinct ?status; separator = "||") as ?status)
                        (group_concat(distinct ?place; separator = "||") as ?place)
                        (group_concat(distinct ?startyear; separator = "||") as ?startyear)
                        (group_concat(distinct ?endyear; separator = "||") as ?endyear)
                        WHERE {
                          SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
                          ?person wdt:P3 wd:Q602.
                          ?person wdt:P32 wd:Q68.
                          OPTIONAL {?person wdt:P3 wd:Q2.}
                          OPTIONAL {?person wdt:P33 ?age.}
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

                    } group by ?person ?personLabel ?age ?agecategoryLabel ?name ?originLabel
                ';
  $encode=urlencode($InfantQuery);
  $call=API_URL.$encode;
  $infantRes=callAPI($call,'','');

  $infantRes = json_decode($infantRes);
  $infantCount = count($infantRes->results->bindings);



  $childQuery = 'SELECT ?person ?personLabel ?age ?agecategoryLabel ?name ?originLabel
                        (group_concat(distinct ?status; separator = "||") as ?status)
                        (group_concat(distinct ?place; separator = "||") as ?place)
                        (group_concat(distinct ?startyear; separator = "||") as ?startyear)
                        (group_concat(distinct ?endyear; separator = "||") as ?endyear)
                        WHERE {
                          SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
                          ?person wdt:P3 wd:Q602.
                          ?person wdt:P32 wd:Q69.
                          OPTIONAL {?person wdt:P3 wd:Q2.}
                          OPTIONAL {?person wdt:P33 ?age.}
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

                    } group by ?person ?personLabel ?age ?agecategoryLabel ?name ?originLabel
                ';
  $encode=urlencode($childQuery);
  $call=API_URL.$encode;
  $childRes=callAPI($call,'','');

  $childRes = json_decode($childRes);
  $childCount = count($childRes->results->bindings);


  $AdultQuery='SELECT ?person ?personLabel ?age ?agecategoryLabel ?name ?originLabel
                        (group_concat(distinct ?status; separator = "||") as ?status)
                        (group_concat(distinct ?place; separator = "||") as ?place)
                        (group_concat(distinct ?startyear; separator = "||") as ?startyear)
                        (group_concat(distinct ?endyear; separator = "||") as ?endyear)
                        WHERE {
                          SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
                          ?person wdt:P3 wd:Q602.
                          ?person wdt:P32 wd:Q66.
                          OPTIONAL {?person wdt:P3 wd:Q2.}
                          OPTIONAL {?person wdt:P33 ?age.}
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

                    } group by ?person ?personLabel ?age ?agecategoryLabel ?name ?originLabel
                ';
  $encode=urlencode($AdultQuery);
  $call=API_URL.$encode;
  $adultRes=callAPI($call,'','');

  $adultRes = json_decode($adultRes);
  $adultCount = count($adultRes->results->bindings);


  $oldQuery = 'SELECT ?person ?personLabel ?age ?agecategoryLabel ?name ?originLabel
                        (group_concat(distinct ?status; separator = "||") as ?status)
                        (group_concat(distinct ?place; separator = "||") as ?place)
                        (group_concat(distinct ?startyear; separator = "||") as ?startyear)
                        (group_concat(distinct ?endyear; separator = "||") as ?endyear)
                        WHERE {
                          SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
                          ?person wdt:P3 wd:Q602.
                          ?person wdt:P32 wd:Q71.
                          OPTIONAL {?person wdt:P3 wd:Q2.}
                          OPTIONAL {?person wdt:P33 ?age.}
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

                    } group by ?person ?personLabel ?age ?agecategoryLabel ?name ?originLabel
                ';
  $encode=urlencode($oldQuery);
  $call=API_URL.$encode;
  $oldRes=callAPI($call,'','');

  $oldRes = json_decode($oldRes);
  $oldCount = count($oldRes->results->bindings);

  $ageCounts = Array();

  $ageCounts[0] = Array(
    'count' => Array('value' => $infantCount),
    'agecategoryLabel' => Array('value' => 'Infant')
  );
  $ageCounts[1] = Array(
      'count' => Array('value' => $childCount),
      'agecategoryLabel' => Array('value' => 'Child')
  );
  $ageCounts[2] = Array(
      'count' => Array('value' => $adultCount),
      'agecategoryLabel' => Array('value' => 'Adult')
  );
  $ageCounts[3] = Array(
      'count' => Array('value' => $oldCount),
      'agecategoryLabel' => Array('value' => 'Old')
  );

  return json_encode($ageCounts);
//  if (!empty($res)){
//    return json_encode($res->results->bindings);
//  }else{
//    return $res;
//  }
}

function counterOfEthnodescriptor(){
  $query="SELECT ?ethno ?ethnoLabel ?count
          WHERE
          {
            {
              SELECT ?ethno (COUNT(?human) AS ?count) WHERE {
                ?human wdt:P3 wd:Q602.
                ?human wdt:P86 ?ethno.
              }
              GROUP BY ?ethno
            }
            SERVICE wikibase:label { bd:serviceParam wikibase:language \"[AUTO_LANGUAGE],en\". }
          }
          ORDER BY DESC(?count)
          LIMIT 100
      ";
  $encode=urlencode($query);
  $call=API_URL.$encode;
  $res=callAPI($call,'','');

  $res= json_decode($res);

  if (!empty($res)){
    return json_encode($res->results->bindings);
  }else{
    return $res;
  }
}

function counterOfPeoplePlace() {
  $query= <<<QUERY
SELECT DISTINCT ?placeLabel (COUNT(?agent) as ?count)  WHERE {
  ?place wdt:P3 wd:Q50 . #it's a place
  ?event wdt:P12 ?place.
  ?agent wdt:P3/wdt:P2 wd:Q2;
      p:P82 [ #with property "hasName" mandatory

            pq:P30 ?event #recordeAt Event

        ];

  SERVICE wikibase:label {
      bd:serviceParam wikibase:language "en" .

  }
}GROUP BY ?placeLabel
ORDER BY ?placeLabel

QUERY;

  $encode=urlencode($query);
  $call=API_URL.$encode;
  $res=callAPI($call,'','');

  $res= json_decode($res);

  if (!empty($res)){
      return json_encode($res->results->bindings);
  }else{
      return $res;
  }
}

function counterOfEventType() {
  $query="SELECT ?eventType ?eventTypeLabel ?count
      WHERE
      {
        {
          SELECT ?eventType (COUNT(?event) AS ?count) WHERE {
            ?event wdt:P3 wd:Q34.
            ?event wdt:P81 ?eventType.
          }
          GROUP BY ?eventType
        }
        SERVICE wikibase:label { bd:serviceParam wikibase:language \"[AUTO_LANGUAGE],en\". }
      }
      ORDER BY DESC(?count)
    ";
  $encode=urlencode($query);
  $call=API_URL.$encode;
  $res=callAPI($call,'','');

  $res= json_decode($res);

  if (!empty($res)){
      return json_encode($res->results->bindings);
  }else{
      return $res;
  }
}

function counterOfEventPlace(){
    $query="SELECT ?place ?placeLabel ?count
            WHERE
            {
              {
                SELECT ?place (COUNT(?event) AS ?count) WHERE {
                  ?event wdt:P3 wd:Q34.
                  ?event wdt:P12 ?place.
                }
                GROUP BY ?place
              }
              SERVICE wikibase:label { bd:serviceParam wikibase:language \"[AUTO_LANGUAGE],en\". }
            }
            ORDER BY ASC(?placeLabel)
      ";

    $encode=urlencode($query);
    $call=API_URL.$encode;
    $res=callAPI($call,'','');

    $res= json_decode($res);

    if (!empty($res)){
        return json_encode($res->results->bindings);
    }else{
        return $res;
    }
}

function counterOfPlaceType(){
  $query= <<<QUERY
SELECT ?placeType ?placeTypeLabel (COUNT(?place) AS ?count)
WHERE
{     ?place wdt:P3 wd:Q50.
      ?place wdt:P80 ?placeType.



  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
}GROUP BY ?placeType ?placeTypeLabel
ORDER BY ASC(?placeTypeLabel)
QUERY;

  $encode=urlencode($query);
  $call=API_URL.$encode;
  $res=callAPI($call,'','');

  $res= json_decode($res);

  if (!empty($res)){
      return json_encode($res->results->bindings);
  }else{
      return $res;
  }
}

function counterOfCity(){
  $query= <<<QUERY
SELECT DISTINCT ?city ?cityLabel (COUNT(?place) AS ?count) WHERE {
  ?city wdt:P3 wd:Q50; #it's a place
      wdt:P80 wd:Q29.#?city is a city
OPTIONAL {?place wdt:P10 ?city.} #place is locatedIn a city

SERVICE wikibase:label { bd:serviceParam wikibase:language "en" .}
}GROUP BY ?city ?cityLabel
order by ?cityLabel

QUERY;

  $encode=urlencode($query);
  $call=API_URL.$encode;
  $res=callAPI($call,'','');

  $res= json_decode($res);

  if (!empty($res)){
      return json_encode($res->results->bindings);
  }else{
      return $res;
  }
}

function counterOfProvince(){
  $query= <<<QUERY
SELECT DISTINCT ?provinceLabel (COUNT(?city) as ?cityCount) (COUNT(?place) as ?placeCount) WHERE {
  ?province wdt:P80 wd:Q31 . #it's a province
  ?city wdt:P10 ?province. #city located in province
  OPTIONAL{?place wdt:P10 ?city} #optional places located in city
  SERVICE wikibase:label {
      bd:serviceParam wikibase:language "en" .

  }
}GROUP BY ?provinceLabel
ORDER BY ?provinceLabel


QUERY;

  $encode=urlencode($query);
  $call=API_URL.$encode;
  $res=callAPI($call,'','');

  $res= json_decode($res);

  if (!empty($res)){
      return json_encode($res->results->bindings);
  }else{
      return $res;
  }
}

function counterOfSourceType(){
  $query="SELECT ?sourcetype ?sourcetypeLabel ?count
          WHERE
          {
            {
              SELECT ?sourcetype (COUNT(?source) AS ?count) WHERE {
                ?source wdt:P3 wd:Q16.
                ?source wdt:P9 ?sourcetype.
              }
              GROUP BY ?sourcetype
            }
            SERVICE wikibase:label { bd:serviceParam wikibase:language \"[AUTO_LANGUAGE],en\". }
          }
          ORDER BY ASC(?sourcetypeLabel)
    ";

  $encode=urlencode($query);
  $call=API_URL.$encode;
  $res=callAPI($call,'','');

  $res= json_decode($res);

  if (!empty($res)){
      return json_encode($res->results->bindings);
  }else{
      return $res;
  }
}

// count the number of people with a certain type filter
function counterOfType() {
    $type = '';
    if (isset($_GET['type'])){
        $type = $_GET['type'];
    }

    $category = '';
    if (isset($_GET['category'])){
        $category = $_GET['category'];
    }

    if ($type == '' || $category == ''){
        die;
    }

    if ($category == "Events") {
        if ($type == "Event Type"){
            return counterOfEventType();
        }
        if ($type == "Time"){
            return counterOfTime(); // not real
        }
        if ($type == "Place"){
            return counterOfEventPlace();
        }
    }

    if ($category == "People") {
        if ($type == "Gender"){
            return counterOfAllGenders();
        }
        if ($type == "Role Types"){
            return counterOfRole();
        }
        if ($type == "Age Category"){
            return counterOfAge();
        }
        if ($type == "Ethnodescriptor"){
            return counterOfEthnodescriptor();
        }
        if ($type == "Place"){
            return counterOfPeoplePlace();  // not real
        }
    }

    if($category == "Places") {
        if ($type == "Place Type"){
          return counterOfPlaceType();
        }
        if ($type == "City"){
          return counterOfCity();
        }
        if ($type == "Province"){
          return counterOfProvince();
        }
    }

    if($category == "Sources") {
      if ($type == "Source Type"){
        return counterOfSourceType();
      }
  }

}

function getEventDateRange() {
    $fullResults = [];
    $query='SELECT ?year ?yearend WHERE {
            {SELECT ?year WHERE {
              ?event wdt:P3 wd:Q34; #event
                     wdt:P13 ?date.
                BIND(str(YEAR(?date)) AS ?year).
              }ORDER BY desc(?year)
            LIMIT 1}
            UNION
            {
            select ?yearend where {
              ?event wdt:P3 wd:Q34; #event
                     wdt:P14 ?enddate.
                BIND(str(YEAR(?enddate)) AS ?yearend).
              }ORDER BY desc(?yearend)
            LIMIT 1
            }
            }';
    // $query='SELECT ?startyear ?endyear
    //         WHERE {
    //           SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
    //           ?event wdt:P3 wd:Q34.
    //           ?event wdt:P13 ?startdate.
    //           BIND(str(YEAR(?startdate)) AS ?startyear).
    //
    //           OPTIONAL {?event wdt:P14 ?enddate.}
    //           BIND(str(YEAR(?enddate)) AS ?endyear).
    //
    //
    //         } ORDER BY desc(?startyear) desc(?endyear)
    //         LIMIT 1';
    $encode=urlencode($query);
    $call=API_URL.$encode;
    $res=callAPI($call,'','');

    $res= json_decode($res);

    if (!empty($res)){
        $fullResults['max'] = $res->results->bindings;
    }else{
        $fullResults['max'] = $res;
    }

    $query='SELECT ?year WHERE {
            ?event wdt:P3 wd:Q34; #event
                   wdt:P13 ?date.
              BIND(str(YEAR(?date)) AS ?year).
            }ORDER BY ASC(?year)
          LIMIT 1';
    // $query='SELECT ?startyear ?endyear
    //         WHERE {
    //           SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
    //           ?event wdt:P3 wd:Q34.
    //           ?event wdt:P13 ?startdate.
    //           BIND(str(YEAR(?startdate)) AS ?startyear).
    //
    //           OPTIONAL {?event wdt:P14 ?enddate.}
    //           BIND(str(YEAR(?enddate)) AS ?endyear).
    //
    //
    //         } ORDER BY asc(?startyear) asc(?endyear)
    //         LIMIT 1';
    $encode=urlencode($query);
    $call=API_URL.$encode;
    $res=callAPI($call,'','');

    $res= json_decode($res);

    if (!empty($res)){
        $fullResults['min'] = $res->results->bindings;
    }else{
        $fullResults['min'] = $res;
    }
    return json_encode($fullResults);
}



function getJsonInfo($url){
  $json = file_get_contents($url);
  $jsondecode = json_decode($json,true);
  return $jsondecode;
}
function getLabel($baseuri,$qid){
  $url=$baseuri.$qid.".json";
  $info=getJsonInfo($url);
  return $info['entities'][$qid]['labels']['en']['value'];
}

function getCVLabel($baseuri,$qid,$property,$wikiconstants){
  $url=$baseuri.$qid.".json";
  $info=getJsonInfo($url);

  $tail=$info['entities'][$qid]['claims'];
  $cv_array=[];
  if(isset($tail[$property])){
    foreach($tail[$property] as $statement){


      $cv=$statement['mainsnak']['datavalue']['value']['id'];
      $cv_array[$wikiconstants[$cv]]=$cv;


    }
  }
  return $cv_array;
}
function getURL($baseuri,$property){
  $url=$baseuri.$qid.".json";
  $info=getJsonInfo($url);
  $tail=$info['entities'][$qid]['claims'];
 $links=[];
  if(isset($tail[$property])){
    foreach($tail[$property] as $statement){

      $link=$statement['mainsnak']['datavalue']['value'];

      $links[$property]=$link;

    }
  }

  return $links;
}

function getTimeValue($baseuri,$qid,$property){
  $url=$baseuri.$qid.".json";
  $info=getJsonInfo($url);
  $tail=$info['entities'][$qid]['claims'];
  $time='';
  if(isset($tail[$property])){
    foreach($tail[$property] as $statement){

      $time=$statement['mainsnak']['datavalue']['value']['time'];
      //here we could include an array to add precision, before, after ...


    }
    return $time;
  }
}
function getInfoperStatement($baseuri,$array,$tag,$property,$qcv){
  $onestatement=[];
  $person_array=[];
  $event_array=[];
  $place_array=[];
  $provenance_array=[];
  $person_array[$tag]='';

  foreach($array as $value){

     $type=$value[properties[$property]];

     $event=$value[properties['recordedAt']];
     $provenance=$value[properties['isDirectlyBasedOn']];
     $projecturl=$value[properties['hasExternalReference']];

     if($event!=''){
        $eventLabel=getLabel($baseuri,$event);
        $placeEvent=getCVLabel($baseuri,$event,properties['atPlace'],qPlaces);
        $placeLabel=key($placeEvent);
        $startAtEvent=getTimeValue($baseuri,$event,properties['startsAt']);

        $year = date_format(date_create($startAtEvent), 'Y');

        if (!in_array($event, $event_array)){
          $event_array[$eventLabel]=$event;
          $event_array[$placeLabel]=$event;
          $place_array[$placeLabel]=places[$placeLabel];
          $event_array[$year]=$event;
        }
      }
      if($provenance!=''){
         $sourceLabel=getLabel($baseuri,$provenance);
         $doctypearr=getCVLabel($baseuri,$provenance,properties['hasOriginalSourceType'],qdoctype);
         $doctype=key($doctypearr);
         $project=key(getCVLabel($baseuri,$provenance,properties['generatedBy'],qprojects));
         if (!in_array($provenance, $provenance_array)){
           $provenance_array[$sourceLabel]=$provenance;
           $provenance_array[$doctype]=$provenance;
           $provenance_array[$projecturl]=$provenance;
           $provenance_array[$project]=$provenance;
         }
       }


    $person_array[$tag] .= $qcv[$type]." | ";


  }

  $onestatement['PersonInfo']=$person_array;
  $onestatement['Events']=$event_array;
  $onestatement['Provenance']=$provenance_array;
  $onestatement['Places']=$place_array;
  return $onestatement;

}
function getProjectFullInfo() {
    $query = 'SELECT  ?title ?desc ?link
             (group_concat(distinct ?pinames; separator = "||") as ?piNames)
             (group_concat(distinct ?contributor; separator = ", ") as ?contributor)
            WHERE
            {
             VALUES ?project {wd:'.$_GET['qid'].'} #Q number needs to be changed for every project.
              ?project wdt:P3 wd:Q264. #all projects
              OPTIONAL{?project wdt:P29 ?link. }
              ?project schema:description ?desc.
              ?project rdfs:label ?title.
              OPTIONAL{ ?project wdt:P28 ?contributor.}
              ?project wdt:P95 ?pi.
              ?pi rdfs:label ?pinames.
              SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
            }GROUP BY ?title ?desc ?link';

    $encode=urlencode($query);
    $call=API_URL.$encode;
    $res=callAPI($call,'','');

    $res= json_decode($res);

    if (!empty($res)){
        return json_encode($res->results->bindings[0]);
    }else{
        return $res;
    }
}
function getpersonfullInfo($qitem){
   $baseuri = WIKI_ENTITY_URL;

  $url=$baseuri.$qitem.".json";
  $person=getJsonInfo($url);
  $allStatements=[];
  $allStatements['PersonInfo']='';
  $allStatements['Events']='';
  $allStatements['Provenance']='';
  $allStatements['Places']='';
  $onestatement=[];
  $person_array=[];
  $person_array['Name']=$person['entities'][$qitem]['labels']['en']['value'];
  $person_array['Alternative Name']=$person['entities'][$qitem]['aliases']['en'][0]['value'];
  $person_array['Description']=getStringfromStatement($person,$qitem,properties['hasDescription']);
  $allStatements['PersonInfo']=$person_array;
  $sex=getItemid($person,$qitem,properties['hasSexRecord'],properties['recordedAt'],properties['isDirectlyBasedOn'],properties['hasExternalReference']);
  $ethnodesc=getItemid($person,$qitem,properties['hasEthnodescriptor'],properties['recordedAt'],properties['isDirectlyBasedOn'],properties['hasExternalReference']);
  $age=getItemid($person,$qitem,properties['hasAgeRecord'],properties['recordedAt'],properties['isDirectlyBasedOn'],properties['hasExternalReference']);
  $status=getItemid($person,$qitem,properties['hasPersonStatusRecord'],properties['recordedAt'],properties['isDirectlyBasedOn'],properties['hasExternalReference']);
  $roles=getItemid($person,$qitem,properties['hasParticipantRoleRecord'],properties['recordedAt'],properties['isDirectlyBasedOn'],properties['hasExternalReference']);


  $placeofOrigin =  getQualifierItem($person,$qitem,properties['hasEthnodescriptor'],properties['referstoPlaceofOrigin']);
  $itemRecordeAt = getQualifierItem($person,$qitem,properties['hasPersonStatusRecord'],properties['recordedAt']);


  $onestatement=getInfoperStatement($baseuri,$sex,"Sex","hasSexRecord",qsexTypes);
  $allStatements['PersonInfo']=array_merge($allStatements['PersonInfo'],$onestatement['PersonInfo']);
  $allStatements['Events']=array_merge((array)$allStatements['Events'],$onestatement['Events']);
  $allStatements['Provenance']=array_merge((array)$allStatements['Provenance'],$onestatement['Provenance']);
  $allStatements['Places']=array_merge((array)$allStatements['Places'],$onestatement['Places']);

  $onestatement=getInfoperStatement($baseuri,$roles,"Roles","hasParticipantRoleRecord",qroleTypes);
  $allStatements['PersonInfo']=array_merge($allStatements['PersonInfo'],$onestatement['PersonInfo']);
  $allStatements['Events']=array_merge((array)$allStatements['Events'],$onestatement['Events']);
  $allStatements['Provenance']=array_merge((array)$allStatements['Provenance'],$onestatement['Provenance']);
  $allStatements['Places']=array_merge((array)$allStatements['Places'],$onestatement['Places']);

  $onestatement=getInfoperStatement($baseuri,$status,"Status","hasPersonStatusRecord",qpersonstatus);
  $allStatements['PersonInfo']=array_merge($allStatements['PersonInfo'],$onestatement['PersonInfo']);
  $allStatements['Events']=array_merge((array)$allStatements['Events'],$onestatement['Events']);
  $allStatements['Provenance']=array_merge((array)$allStatements['Provenance'],$onestatement['Provenance']);
  $allStatements['Places']=array_merge((array)$allStatements['Places'],$onestatement['Places']);



  $person_array['Race'] = getStringfromStatement($person,$qitem,properties['hasRaceRecord']);
  //age for later
  //  $person_array['age'] =  getStringfromStatement($person,$qitem,properties['hasRaceRecord']);
/*if($ethnodesc!=null){
    $person_array['Origin'] = qethnodescriptor[$ethnodesc];
    if($placeofOrigin!= null){
      $person_array['Origin'] .=" ". qPlaces[$placeofOrigin];
    }
  }else{
    $person_array['Origin'] = '';
  }

  if($age!=null){
    $person_array['Age'] = qages[$age];
  }else{
    $person_array['Age'] = '';
  }

  if($status!=null){
      $person_array['Person Status']['Person Status'] = qpersonstatus[$status];
      $eventlabel=$eventStatus['entities'][$itemRecordeAt]['labels']['en']['value'];
      $person_array['Person Status']['recordedAt']=$eventlabel;
    }else{
      $person_array['Person Status'] = '';
    }*/

  //  $allStatements=array_merge($allStatements,$onestatement);

  //debugfunc($allStatements);
  return $allStatements;
}



function getItemid($json,$item,$property,$pq,$pr,$pfr){
  $items=[];
  $statements=[];
  $tail=$json['entities'][$item]['claims'];
  if(isset($tail[$property])){
    foreach($tail[$property] as $statement){

      $items[$property]=$statement['mainsnak']['datavalue']['value']['id'];

      if(isset($statement['qualifiers'][$pq])){

        $items[$pq]= $statement['qualifiers'][$pq][0]['datavalue']['value']['id'];
      }

      if(isset($statement['references'][0])){
        $items[$pr]= $statement['references'][0]['snaks'][$pr][0]['datavalue']['value']['id'];
        $items[$pfr]= $statement['references'][0]['snaks'][$pfr][0]['datavalue']['value'];

      }

      array_push($statements,$items);

    }
    return $statements;

  }else{
    return null;
  }

}

function getStringfromStatement($json,$item,$property){
  $tail=$json['entities'][$item]['claims'];
  if(isset($tail[$property])){
    return $tail[$property][0]['mainsnak']['datavalue']['value'];
  }else{
    return null;
  }
}

function getQualifierItem($json,$item,$property,$pq){
  $tail=$json['entities'][$item]['claims'];
  if(isset($tail[$property]) && isset($tail[$property][0]['qualifiers'][$pq])){
    return $tail[$property][0]['qualifiers'][$pq][0]['datavalue']['value']['id'];
  }else{
    return null;
  }
}

function getReferences($json,$item,$property,$pr){
  $tail=$json['entities'][$item]['claims'];
  if(isset($tail[$property]) && isset($tail[$property][0]['references']['snaks'])){
    return $tail[$property][0]['references']['snakes'][$pr][0]['datavalue']['value']['id'];
  }else{
    return null;
  }
}

function detailPerson($statement,$label){
  //Splits the statement(detail) up into multiple parts for multiple details, also trims whitespace off end
  $statementArr = explode('|', $statement);
  if (end($statementArr) == ' '){
    array_pop($statementArr);
  }
  ?>
  <a href="<?php echo BASE_URL.'explorePeople/?search='.$statement?>">
      <div class="detail">
          <h3><?php echo strtoupper($label);?></h3>
          <div class="detail-bottom">
            <?php
            //For each detail to add create it in seperate divs with a detail menu in each
            for ($x = 0; $x <= (count($statementArr) - 1); $x++){
              echo "<div>" . $statementArr[$x];
              echo '<div class="detail-menu"> <h1>Metadata</h1> <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </p> </div>';
              echo "</div>";
              if ($x != (count($statementArr) - 1)){
                echo "<h4> | </h4>";
              }
            }
            ?>

          </div>
      </div>
  </a>

<?php
}

function detailPersonHtml($statement,$label){
    //Splits the statement(detail) up into multiple parts for multiple details, also trims whitespace off end
    $statementArr = explode('||', $statement);
    if (end($statementArr) == ' '){
        array_pop($statementArr);
    }

    $baseurl = BASE_URL;
    $upperlabel = strtoupper($label);
    $lowerlabel = strtolower($label);
    $html = '';

    if($label === "Geoname Identifier"){
      $html .= '<a href="http://www.geonames.org/' . $statementArr[0] . '/">';
    }
    else{
      $html .= '<a href="' . $baseurl . 'search/all?' . $lowerlabel . '=' . $statement . '">';
    }

    $html .= <<<HTML
    <div class="detail $lowerlabel">
        <h3>$upperlabel</h3>
        <div class="detail-bottom">
HTML;


    //For each detail to add create it in seperate divs with a detail menu in each
    for ($x = 0; $x <= (count($statementArr) - 1); $x++){
        $html .= "<div>" . $statementArr[$x];
        $html .= '<div class="detail-menu"> <h1>Metadata</h1> <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </p> </div>';
        $html .= "</div>";

        if ($x != (count($statementArr) - 1)){
            $html.= "<h4> | </h4>";
        }
    }

    $html .= '</div></div></a>';

    return $html;

}





function getPersonRecordHtml(){
    $qid = $_REQUEST['QID'];
    $type = $_REQUEST['type'];

//    $call=WIKI_ENTITY_URL.$personQ.'.json';
//
//    $url = BASE_WIKI_URL."w/api.php?action=wbgetentities&format=json&ids=$personQ";
//
//    $data = getJsonInfo($url);
//    print_r($data);die;


    // get results from queries
    /*
    switch ($type){
        case 'name':
            $query['query'] ='
                SELECT ?person ?personLabel ?age ?agecategoryLabel ?name ?originLabel
                        (group_concat(distinct ?status; separator = "||") as ?status)
                        (group_concat(distinct ?place; separator = "||") as ?place)
                        (group_concat(distinct ?role; separator = "||") as ?role)
                        (group_concat(distinct ?startyear; separator = "||") as ?startyear)
                        (group_concat(distinct ?endyear; separator = "||") as ?endyear)
                        WHERE {
                          SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
                          ?person wdt:P3 wd:Q602.
                          #?person wdt:P17 wd:Q48.
                          #?person wdt:P32 wd:Q66.
                          FILTER ( ?person = <https://sandro-16.matrix.msu.edu/entity/'.$personQ.'> )
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
                    } group by ?person ?personLabel ?age ?agecategoryLabel ?name ?originLabel
                ';


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
            $record = $result[0];


            $fullName = $record['name']['value'];

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

            $dateRange = '';
            if ($startYear != '' && $endYear != ''){
                $dateRange = "$startYear - $endYear";
            } elseif ($endYear == ''){
                $dateRange = $startYear;
            } elseif ($startYear == '') {
                $dateRange = $endYear;
            }
            break;
        case 'details':
            $query['query'] ='
                          SELECT ?person ?personLabel ?personDescription ?age ?agecategoryLabel ?name ?originLabel ?sex ?sexLabel
                        (group_concat(distinct ?status; separator = "||") as ?status)
                        (group_concat(distinct ?place; separator = "||") as ?place)
                        (group_concat(distinct ?role; separator = "||") as ?role)
                        (group_concat(distinct ?startyear; separator = "||") as ?startyear)
                        (group_concat(distinct ?endyear; separator = "||") as ?endyear)
                        WHERE {
                          SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
                          ?person wdt:P3 wd:Q602.
                          #?person wdt:P17 wd:Q48.
                          #?person wdt:P32 wd:Q66.
                          FILTER ( ?person = <https://sandro-16.matrix.msu.edu/entity/'.$personQ.'> )
                          OPTIONAL {?person wdt:P3 wd:Q2.}
                          OPTIONAL {?person wdt:P33 ?age.}
                          OPTIONAL {?person wdt:P39 ?role.}
                          OPTIONAL {?person wdt:P32 ?agecategory.}
                          OPTIONAL {?person wdt:P82 ?name.}
                          OPTIONAL {?person wdt:P20 ?origin.}
                          OPTIONAL {?name wdt:P30 ?event. }
                                    #?event wdt:P13 ?startdate.}
                          #BIND(str(YEAR(?startdate)) AS ?startyear).
                          OPTIONAL {?event wdt:P13 ?startdate.}
                      BIND(str(YEAR(?startdate)) AS ?startyear).
                      OPTIONAL {?event wdt:P14 ?enddate.}
                      BIND(str(YEAR(?enddate)) AS ?endyear).
                      OPTIONAL {?event wdt:P12 ?place.}
                      OPTIONAL { ?person wdt:P17 ?sex. }
                      OPTIONAL { ?person wdt:P24 ?status. }
                      OPTIONAL { ?person wdt:P58 ?owner. }
                      OPTIONAL { ?person wdt:P88 ?match. }
                    } group by ?person ?personLabel ?personDescription ?age ?agecategoryLabel ?name ?originLabel ?sex ?sexLabel
                ';


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
            $record = $result[0];
//            print_r($record);die;


            // save the needed data from the result
            $fullName = $record['name']['value'];


            $description = '';
            if (isset($record['personDescription']) && isset($record['personDescription']['value'])){
                $description = $record['personDescription']['value'];
            }

            $sex = '';
            if (isset($record['sexLabel']) && isset($record['sexLabel']['value'])){
                $sex = $record['sexLabel']['value'];
            }

            $statusUrl = $record['status']['value'];
            $qStatus = end(explode('/', $statusUrl));
            $status = '';
            if (!empty($qStatus)) {
                if (array_key_exists($qStatus, qpersonstatus)){
                    $status = qpersonstatus[$qStatus];
                } else {
                    $status = '';
                }
            }


            if (isset($record['role']) && isset($record['role']['value'])){
                $rolesArray = explode('||', $record['role']['value']);
                $roles = '';

                $roleCount = 0;
                foreach ($rolesArray as $roleUrl) {
                    $qRole = end(explode('/', $roleUrl));
                    if (!empty($qRole)){
                        if (array_key_exists($qRole, qroleTypes)){
                            $roleLabel = qroleTypes[$qRole];
                        } else {
                            $roleLabel = '';
                        }
                        if ($roleCount > 0){
                            $roles .= "|$roleLabel";
                        } else {
                            $roles .= "$roleLabel";

                        }
                        $roleCount++;
                    }
                }
            } else {
                $roles = '';
            }


            break;
        case 'timeline':

            // Code for creating events on Timeline
            // Replace with Kora 3 events
            $events = [
                ['kid' => '1', 'title' => 'birth', 'description' => 'Person was born', 'year' => 1730],
                ['kid' => '2', 'title' => 'event 1', 'description' => 'Example description 1', 'year' => 1739],
                ['kid' => '3', 'title' => 'event 2', 'description' => 'Example description 2', 'year' => 1741],
                ['kid' => '4', 'title' => 'event 3', 'description' => 'Example description 3', 'year' => 1745],
                ['kid' => '5', 'title' => 'event 4', 'description' => 'Example description 4', 'year' => 1756],
                ['kid' => '6', 'title' => 'event 5', 'description' => 'Example description 5', 'year' => 1756.5],
                ['kid' => '7', 'title' => 'event 6', 'description' => 'Example description 6', 'year' => 1760],
                ['kid' => '8', 'title' => 'event 7', 'description' => 'Example description 7', 'year' => 1763],
                ['kid' => '9', 'title' => 'event 8', 'description' => 'Example description 8', 'year' => 1774],
                ['kid' => '10', 'title' => 'event 9', 'description' => 'Example description 9', 'year' => 1789],
                ['kid' => '11', 'title' => 'event 10', 'description' => 'Example description 10', 'year' => 1789.5],
                ['kid' => '12', 'title' => 'event 11', 'description' => 'Example description 11', 'year' => 1794],
                ['kid' => '13', 'title' => 'event 12', 'description' => 'Example description 12', 'year' => 1796],
                ['kid' => '14', 'title' => 'event 13', 'description' => 'Example description 13', 'year' => 1799],
                ['kid' => '15', 'title' => 'event 14', 'description' => 'Example description 14', 'year' => 1800],
                ['kid' => '16', 'title' => 'event 15', 'description' => 'Example description 15', 'year' => 1801],
                ['kid' => '17', 'title' => 'event 16', 'description' => 'Example description 16', 'year' => 1803],
                ['kid' => '18', 'title' => 'event 17', 'description' => 'Example description 17', 'year' => 1804],
                ['kid' => '19', 'title' => 'event 18', 'description' => 'Example description 18', 'year' => 1806],
                ['kid' => '20', 'title' => 'event 19', 'description' => 'Example description 19', 'year' => 1807],
            ];

            $timeline_event_dates = [];
            foreach ($events as $event) {
                // If there are months and days, put the year into decimal format
                // Ex: March 6, 1805 = 1805.18
                array_push($timeline_event_dates, $event['year']);
            }

            $first_date = min($timeline_event_dates);
            $final_date = max($timeline_event_dates);
            $diff = $final_date - $first_date;

            if ($diff < 10) {
                $increment = 1;
            } elseif ($diff < 20) {
                $increment = 2;
            } elseif ($diff < 40) {
                $increment = 5;
            } elseif ($diff < 90) {
                $increment = 10;
            } else {
                $increment = 20;
            }

            // Hash starts at year that is divisible by incrememnt and before the first event
            $first_date_hash = floor($first_date) - (floor($first_date) % $increment) - $increment;
            $final_date_hash = ceil($final_date) - (ceil($final_date) % $increment) + $increment;

            $hashes = range($first_date_hash, $final_date_hash, $increment);
            $hash_count = count($hashes);
            $hash_range = end($hashes) - $hashes[0];

            break;
        case 'connections':

            break;
        case 'featuredStories':

            break;
    } */

    //Timeline
    // Code for creating events on Timeline
    // Replace with Kora 3 events
        $events = [
        ['kid' => '1', 'title' => 'birth', 'description' => 'Person was born', 'year' => 1730],
        ['kid' => '2', 'title' => 'event 1', 'description' => 'Example description 1', 'year' => 1739],
        ['kid' => '3', 'title' => 'event 2', 'description' => 'Example description 2', 'year' => 1741],
        ['kid' => '4', 'title' => 'event 3', 'description' => 'Example description 3', 'year' => 1745],
        ['kid' => '5', 'title' => 'event 4', 'description' => 'Example description 4', 'year' => 1756],
        ['kid' => '6', 'title' => 'event 5', 'description' => 'Example description 5', 'year' => 1756.5],
        ['kid' => '7', 'title' => 'event 6', 'description' => 'Example description 6', 'year' => 1760],
        ['kid' => '8', 'title' => 'event 7', 'description' => 'Example description 7', 'year' => 1763],
        ['kid' => '9', 'title' => 'event 8', 'description' => 'Example description 8', 'year' => 1774],
        ['kid' => '10', 'title' => 'event 9', 'description' => 'Example description 9', 'year' => 1789],
        ['kid' => '11', 'title' => 'event 10', 'description' => 'Example description 10', 'year' => 1789.5],
        ['kid' => '12', 'title' => 'event 11', 'description' => 'Example description 11', 'year' => 1794],
        ['kid' => '13', 'title' => 'event 12', 'description' => 'Example description 12', 'year' => 1796],
        ['kid' => '14', 'title' => 'event 13', 'description' => 'Example description 13', 'year' => 1799],
        ['kid' => '15', 'title' => 'event 14', 'description' => 'Example description 14', 'year' => 1800],
        ['kid' => '16', 'title' => 'event 15', 'description' => 'Example description 15', 'year' => 1801],
        ['kid' => '17', 'title' => 'event 16', 'description' => 'Example description 16', 'year' => 1803],
        ['kid' => '18', 'title' => 'event 17', 'description' => 'Example description 17', 'year' => 1804],
        ['kid' => '19', 'title' => 'event 18', 'description' => 'Example description 18', 'year' => 1806],
        ['kid' => '20', 'title' => 'event 19', 'description' => 'Example description 19', 'year' => 1807],
    ];

    $timeline_event_dates = [];
    foreach ($events as $event) {
        // If there are months and days, put the year into decimal format
        // Ex: March 6, 1805 = 1805.18
        array_push($timeline_event_dates, $event['year']);
    }

    $first_date = min($timeline_event_dates);
    $final_date = max($timeline_event_dates);
    $diff = $final_date - $first_date;

    if ($diff < 10) {
        $increment = 1;
    } elseif ($diff < 20) {
        $increment = 2;
    } elseif ($diff < 40) {
        $increment = 5;
    } elseif ($diff < 90) {
        $increment = 10;
    } else {
        $increment = 20;
    }

    // Hash starts at year that is divisible by incrememnt and before the first event
    $first_date_hash = floor($first_date) - (floor($first_date) % $increment) - $increment;
    $final_date_hash = ceil($final_date) - (ceil($final_date) % $increment) + $increment;

    $hashes = range($first_date_hash, $final_date_hash, $increment);
    $hash_count = count($hashes);
    $hash_range = end($hashes) - $hashes[0];

    //QUERY FOR RECORD INFO
    $query = [];
    if($type === "person"){

    }
    else if($type === "place"){
        $query['query'] = <<<QUERY
SELECT ?name ?desc ?located  ?type ?geonames ?code 
(group_concat(distinct ?refName; separator = "||") as ?sources)
(group_concat(distinct ?pname; separator = "||") as ?researchprojects)
    WHERE
{
    VALUES ?place {wd:$qid} #Q number needs to be changed for every place. 
    ?place wdt:P3 wd:Q50;
            ?property  ?object .
    ?object prov:wasDerivedFrom ?provenance .
    ?provenance pr:P35 ?source .
    ?source rdfs:label ?refName;
            wdt:P7 ?project.
    ?project rdfs:label ?pname.
    ?place schema:description ?desc.
    ?place rdfs:label ?name.
    ?place wdt:P80 ?placetype.
    ?placetype rdfs:label ?type.
    OPTIONAL{?place wdt:P10 ?locatedIn. 
            ?locatedIn rdfs:label ?located}.
    OPTIONAL{ ?place wdt:P71 ?geonames.}
    OPTIONAL{ ?place wdt:P96 ?code.}
    
    SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}GROUP BY ?name ?desc ?located  ?type ?geonames ?code      
QUERY;
    }
    else if($type === "event"){
      $query['query'] = <<<QUERY
SELECT ?name ?desc ?located  ?type ?date ?endDate 
(group_concat(distinct ?refName; separator = "||") as ?sources)
(group_concat(distinct ?pname; separator = "||") as ?researchprojects)
(group_concat(distinct ?rolename; separator = "||") as ?roles)
  WHERE
{
  VALUES ?event {wd:$qid} #Q number needs to be changed for every event. 
  ?event wdt:P3 wd:Q34;
        ?property  ?object .
  ?object prov:wasDerivedFrom ?provenance .
  ?provenance pr:P35 ?source .
  ?source rdfs:label ?refName;
          wdt:P7 ?project.
  ?project rdfs:label ?pname.
  ?event schema:description ?desc.
  ?event rdfs:label ?name.
  ?event wdt:P81 ?eventtype.
  ?eventtype rdfs:label ?type.
  OPTIONAL{?event wdt:P12 ?place. 
          ?place rdfs:label ?located}.
  OPTIONAL{ ?event wdt:P13 ?datetime.
          BIND(xsd:date(?datetime) AS ?date)}
    OPTIONAL{ ?event wdt:P14 ?endDatetime.
            BIND(xsd:date(?endDatetime) AS ?endDate)}
    OPTIONAL{ ?event wdt:P38 ?roles.
            ?roles rdfs:label ?rolename.}
  
  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}GROUP BY ?name ?desc ?located  ?type ?date ?endDate    
QUERY;
    }
    
    //Execute query
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
    //Get result
    $result = json_decode($result, true)['results']['bindings'];
    $record = $result[0];



    //Get variables from query
    $recordVars = [];

    //Name
    $recordVars['Name'] = $record['name']['value'];

    //Description
    if (isset($record['desc']) && isset($record['desc']['value']) ){
      $description = $record['desc']['value'];
    } else {
      $description = '';
    }

    //Checks for start and end years and creates date range
    /*
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

    $dateRange = '';
    if ($startYear != '' && $endYear != ''){
        $dateRange = "$startYear - $endYear";
    } elseif ($endYear == ''){
        $dateRange = $startYear;
    } elseif ($startYear == '') {
        $dateRange = $endYear;
    }
    */

    //Date
    if (isset($record['date']) && isset($record['date']['value']) ){
      $recordVars['Date'] = $record['date']['value'];
    }

    //Location
    if (isset($record['located']) && isset($record['located']['value']) ){
      $recordVars['Location'] = $record['located']['value'];
    }

    //Type
    if (isset($record['type']) && isset($record['type']['value']) ){
      $recordVars['Type'] = $record['type']['value'];
    }

    //Geonames
    if (isset($record['geonames']) && isset($record['geonames']['value']) ){
      $recordVars['Geoname Identifier'] = $record['geonames']['value'];
    }

    //Code
    if (isset($record['code']) && isset($record['code']['value']) ){
      $recordVars['Modern Country Code'] = $record['code']['value'];
    }

    //Source
    if (isset($record['sources']) && isset($record['sources']['value']) ){
      $recordVars['Sources'] = $record['sources']['value'];
    }

    //Project
    if (isset($record['researchprojects']) && isset($record['researchprojects']['value']) ){
      $recordVars['Contributing Projects'] = $record['researchprojects']['value'];
    }

    //Roles
    if (isset($record['roles']) && isset($record['roles']['value']) ){
      $recordVars['Roles'] = $record['roles']['value'];
    }

    // create the html based on the type of results
    $htmlArray = [];

    //Header w/ date range
    $html = '';
    if($type == "person"){
      $type = "people";
    }
    else{
      $type = $type . 's';
    }
    $url = BASE_URL . "explore/" . $type;
    $recordform = ucfirst($type);
    $name = $recordVars['Name'];
    $dateRange = '';

    $html .= <<<HTML
<h4 class='last-page-header'>
    <a id='last-page' href="$url"><span id=previous-title>$recordform // </span></a>
    <span id='current-title'>$name</span>
</h4>
<h1>$name</h1>
<h2 class='date-range'><span>$dateRange</span></h2>
HTML;

    $htmlArray['header'] = $html;

    //Description
    $html = '';

    $html .= '<p class="description">' . $description . '</p>';

    $htmlArray['description'] = $html;

    //Detail section
    $html = '';

    $html .= '<div class="detailwrap">';
    // $html .= detailPersonHtml($name, "Name");
    // $html .= detailPersonHtml($located, "Location");
    // $html .= detailPersonHtml($geoname, "Geoname");
    // $html .= detailPersonHtml($code, "Code");
    // $html .= detailPersonHtml($sources, "Sources");
    // $html .= detailPersonHtml($projects, "Contributing Project");
    foreach($recordVars as $key => $value){
      $html .= detailPersonHtml($value, $key);
    }
    $html .= '</div>';

    $htmlArray['details'] = $html;

    //Timeline section
    $html = '';

    $html = <<<HTML
<div class="timelinewrap">
  <section class="fr-section timeline-section">
  <h2 class="section-title">Person Timeline</h2>

  <div class="timeline-info-container" kid="{$events[0]['kid']}">
      <div class="arrow-pointer-bottom"></div>
      <div class="arrow-pointer-top"></div>

      <div class="info-header">
          <div class="info-select info-select-event active" data-select="event">
              <p>Event</p>
              <p class="large-text">Birth</p>
          </div>
          <div class="info-select info-select-place" data-select="place">
              <p>Place</p>
              <p class="large-text">Batendu</p>
          </div>
      </div>
HTML;

    foreach($events as $index => $event) {
      $html .= '
      <div class="event-info-'.$event['kid'].' infowrap '.($index == 0 ? 'active' : '').'">
          <div class="info-column">
              <p><span class="bold">Start Date:</span> 1804</p>
              <p><span class="bold">End Date:</span> N/A</p>
              <p><span class="bold">Age:</span> 0</p>
              <p><span class="bold">Status:</span> Free</p>
              <p><span class="bold">Age Category:</span> Infant</p>
              <p><span class="bold">Description</span> Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                  sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
          </div><div class="info-column">
              <p><span class="bold">Ocupation:</span> N/A</p>
              <p><span class="bold">Relationship:</span> Son - Kayawon</p>
              <p><span class="bold">Religion:</span> N/A</p>
              <p><span class="bold">Sources:</span> Koelle Polyglotta, 1</p>
              <p><span class="bold">Place:</span> Batendu</p>
              <p><span class="bold">Testing Kid:</span>'.$event['kid'].'</p>
          </div>
      </div>
      <div class="place-info-'.$event['kid'].' infowrap">
          <div class="info-column">
              <p><span class="bold">Place Info:</span> Place Info</p>
              <p><span class="bold">Testing Kid:</span> '.$event['kid'].'</p>
          </div>
      </div>';
    }

    $html .= '</div>';

    $html .= '<div class="timeline-container">
    <div class="timeline">
      <div class="line"></div>
      <div class="hash-container" data-start="'.$first_date_hash.'" data-end="'.$final_date_hash.'">';


    foreach ($hashes as $index => $year) {
      $html .= '<div class="hash" style="left:calc('.($index / ($hash_count - 1)) * 100 .'% - 14px)"><p>'.$year.'</p></div>';
    }

    $html .= '
      </div>
      <div class="points-container">
      ';

      foreach ($events as $index => $event) {
          // Convert year, month, day into decimal form
          $left = ($event['year'] - $first_date_hash) * 100 / $hash_range;

          $html .= '
          <div class="event-point no-select '.($index == 0 ? 'active' : '').'"
          style="left:calc('.$left.'% - 5px)"
          data-kid="'.$event['kid'].'"
          data-index="'.$index.'">
          <span class="event-title">'.$event['title'].' - '.$event['year'].'</span>
          </div>';
      }

    $html .= '
      </div>
    </div>
    <div class="timeline-controls">
      <div class="timeline-prev no-select"><img src="'.BASE_URL.'assets/images/chevron-down-dark.svg" alt="Previous Arrow"></div>
      <div class="timeline-next no-select"><img src="'.BASE_URL.'assets/images/chevron-down-dark.svg" alt="Next Arrow"></div>
    </div>
    </div>

    </section>
    </div>';

    $htmlArray['timeline'] = $html;


    // return $htmlArray;
    return json_encode($htmlArray);

    /*
    switch ($type){
        case 'name':
            $html .= "
                <h4 class='last-page-header'>
                    <a id='last-page' href='".BASE_URL."explorePeople/'><span id=previous-title>People // </span></a>
                    <span id='current-title'>$fullName</span>
                </h4>
                <h1>$fullName</h1>
                <h2 class='date-range'><span>$dateRange</span></h2>
            ";

            break;
        case 'details':
            $htmlArray = array('description' => $description,
                            'details' => '');

            $html .= detailPersonHtml($fullName, "Name");
            $html .= detailPersonHtml($sex, "Sex");
            $html .= detailPersonHtml($roles, "Roles");
            $html .= detailPersonHtml($status, "Status");
            $html .= detailPersonHtml('Projects here', "Contributing Project");

            $htmlArray['details'] = $html;

            return json_encode($htmlArray);

            break;
        case 'timeline':

            $html = '<div class="timelinewrap">
                <section class="fr-section timeline-section">
                <h2 class="section-title">Person Timeline</h2>

                <div class="timeline-info-container" kid="'.$events[0]['kid'].'">
                    <div class="arrow-pointer-bottom"></div>
                    <div class="arrow-pointer-top"></div>

                    <div class="info-header">
                        <div class="info-select info-select-event active" data-select="event">
                            <p>Event</p>
                            <p class="large-text">Birth</p>
                        </div>
                        <div class="info-select info-select-place" data-select="place">
                            <p>Place</p>
                            <p class="large-text">Batendu</p>
                        </div>
                    </div>';


            foreach($events as $index => $event) {
                $html .= '
                <div class="event-info-'.$event['kid'].' infowrap '.($index == 0 ? 'active' : '').'">
                    <div class="info-column">
                        <p><span class="bold">Start Date:</span> 1804</p>
                        <p><span class="bold">End Date:</span> N/A</p>
                        <p><span class="bold">Age:</span> 0</p>
                        <p><span class="bold">Status:</span> Free</p>
                        <p><span class="bold">Age Category:</span> Infant</p>
                        <p><span class="bold">Description</span> Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    </div><div class="info-column">
                        <p><span class="bold">Ocupation:</span> N/A</p>
                        <p><span class="bold">Relationship:</span> Son - Kayawon</p>
                        <p><span class="bold">Religion:</span> N/A</p>
                        <p><span class="bold">Sources:</span> Koelle Polyglotta, 1</p>
                        <p><span class="bold">Place:</span> Batendu</p>
                        <p><span class="bold">Testing Kid:</span>'.$event['kid'].'</p>
                    </div>
                </div>
                <div class="place-info-'.$event['kid'].' infowrap">
                    <div class="info-column">
                        <p><span class="bold">Place Info:</span> Place Info</p>
                        <p><span class="bold">Testing Kid:</span> '.$event['kid'].'</p>
                    </div>
                </div>';
            }

            $html .= '</div></div>';

            $html .= '<div class="timeline-container">
            <div class="timeline">
                <div class="line"></div>
                <div class="hash-container" data-start="'.$first_date_hash.'" data-end="'.$final_date_hash.'">';


            foreach ($hashes as $index => $year) {
                $html .= '<div class="hash" style="left:calc('.($index / ($hash_count - 1)) * 100 .'% - 14px)"><p>'.$year.'</p></div>';
            }

            $html .= '
                </div>
                <div class="points-container">
                ';

                foreach ($events as $index => $event) {
                    // Convert year, month, day into decimal form
                    $left = ($event['year'] - $first_date_hash) * 100 / $hash_range;

                    $html .= '
                    <div class="event-point no-select '.($index == 0 ? 'active' : '').'"
                    style="left:calc('.$left.'% - 5px)"
                    data-kid="'.$event['kid'].'"
                    data-index="'.$index.'">
                    <span class="event-title">'.$event['title'].' - '.$event['year'].'</span>
                    </div>';
                }

            $html .= '
                </div>
            </div>
            <div class="timeline-controls">
                <div class="timeline-prev no-select"><img src="'.BASE_URL.'assets/images/chevron-down-dark.svg" alt="Previous Arrow"></div>
                <div class="timeline-next no-select"><img src="'.BASE_URL.'assets/images/chevron-down-dark.svg" alt="Next Arrow"></div>
            </div>
        </div>

        </section>
        </div>';

            break;
        case 'connections':

            break;
        case 'featuredStories':

            break;
    }

    return $html;
    */
}























function debugfunc($debugobject){?>
    <pre><?php echo var_dump($debugobject);?></pre>
<?php }
?>
