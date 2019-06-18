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
  $query='SELECT  (COUNT(distinct ?agent) AS ?count)
    WHERE {
        ?agent wdt:P3/wdt:P2 wd:Q2;        #find agents{
        MINUS{ ?agent wdt:P39 wd:Q536 }. #remove all researchers
        SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }
    }

    ORDER BY ?count
    ';
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

  $agecategoryQuery ='SELECT  ?agecategoryLabel (count(?agent) as ?count) where{
                        ?agecategory wdt:P3 wd:Q604.
                        ?agent wdt:P3 wd:Q602.
                        ?agent wdt:P32 ?agecategory.
                        SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }

                      }group by ?agecategoryLabel
                      ';

  $encode=urlencode($agecategoryQuery);
  $call=API_URL.$encode;
  $agecategoryResult=callAPI($call,'',''); 
  $agecategoryResult = json_decode($agecategoryResult);

  return json_encode($agecategoryResult->results->bindings);

/*
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

*/
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
  $baseurl = BASE_URL;
  $upperlabel = strtoupper($label);
  $lowerlabel = strtolower($label);
  $html = '';

  // don't show the label if it is empty
  if (empty($statement)){
    return "";
  }


  if($label === "RolesA"){
    //Multiple roles in the roles array so match them up with the participant
    $lowerlabel = "roles";
    $upperlabel = "ROLES";
    //Array for Roles means there are participants and pQIDs to match
    $roles = explode('||', $statement['roles']);
    $participants = explode('||', $statement['participant']);
    $pq = explode('||', $statement['pq']);

    //Remove whitespace from end of arrays
    if (end($roles) == '' || end($roles) == ' '){
      array_pop($roles);
    }
    if (end($participants) == '' || end($participants) == ' '){
      array_pop($participants);
    }
    if (end($pq) == '' || end($pq) == ' '){
      array_pop($pq);
    }

    $html .= <<<HTML
<div class="detail $lowerlabel">
  <h3>$upperlabel</h3>
HTML;

    //Loop through and match up
    $matched = '';
    for($i=0; $i < sizeof($roles); $i++){
      $explode = explode('/', $pq[$i]);
      $pqid = end($explode);
      $pqurl = $baseurl . 'record/person/' . $pqid;
      $matched = $roles[$i] . ' - ' . $participants[$i];

      $html .= <<<HTML
<div class="detail-bottom">
    <div>$roles[$i]
HTML;

      // roles tool tip
      if(array_key_exists($roles[$i],controlledVocabulary)){
          $detailinfo = ucfirst(controlledVocabulary[$roles[$i]]);
          $html .= "<div class='detail-menu'> <h1>$roles[$i]</h1> <p>$detailinfo</p> </div>";
      }

      $html .= "</div> - <a class='highlight' href='$pqurl'>$participants[$i]</a></div>";
    }
    $html .= '</div>';

} else if ($label == "eventRolesA"){
    // match roles with events

    //Multiple roles in the roles array so match them up with the participant
    $lowerlabel = "roles";
    $upperlabel = "ROLES";
    //Array for Roles means there are participants and pQIDs to match
    $roles = explode('||', $statement['roles']);
    $eventRoleUrls = explode('||', $statement['eventRoles']);
    $eventRoleLabels = explode('||', $statement['eventRoleLabels']);

    //Remove whitespace from end of arrays
    if (end($roles) == '' || end($roles) == ' '){
      array_pop($roles);
    }
    if (end($eventRoleUrls) == '' || end($eventRoleUrls) == ' '){
      array_pop($eventRoleUrls);
    }
    if (end($eventRoleLabels) == '' || end($eventRoleLabels) == ' '){
      array_pop($eventRoleLabels);
    }

    $html .= <<<HTML
<div class="detail $lowerlabel">
  <h3>$upperlabel</h3>
HTML;

    //Loop through and match up
    $matched = '';
    for($i=0; $i < sizeof($roles); $i++){
        $explode = explode('/', $eventRoleUrls[$i]);
        $eventQid = end($explode);
        $eventUrl = $baseurl . 'record/event/' . $eventQid;
        $matched = $roles[$i] . ' - ' . $eventRoleLabels[$i];

        $html .= <<<HTML
<div class="detail-bottom">
    <div>$roles[$i]
HTML;

        // roles tool tip
        if(array_key_exists($roles[$i],controlledVocabulary)){
            $detailinfo = ucfirst(controlledVocabulary[$roles[$i]]);
            $html .= "<div class='detail-menu'> <h1>$roles[$i]</h1> <p>$detailinfo</p> </div>";
        }

        $html .= "</div> - <a href='$eventUrl' class='highlight'>$eventRoleLabels[$i]</a></div>";
    }
    $html .= '</div>';
} else if ($label == "closeMatchA"){
    $lowerlabel = "close match";
    $upperlabel = "CLOSE MATCH";
    
    $matchUrls = explode('||', $statement['matchUrls']);
    $matchLabels = explode('||', $statement['matchLabels']);

    if (end($matchUrls) == '' || end($matchUrls) == ' '){
      array_pop($matchUrls);
    }
    if (end($matchLabels) == '' || end($matchLabels) == ' '){
      array_pop($matchLabels);
    }

    $html .= <<<HTML
<div class="detail $lowerlabel">
  <h3>$upperlabel</h3>
HTML;

    //Loop through and match up
    $matched = '';
    for($i=0; $i < sizeof($matchLabels); $i++){
        $explode = explode('/', $matchUrls[$i]);
        $personQ = end($explode);
        $matchUrl = $baseurl . 'record/person/' . $personQ;
        $matched = $matchLabels[$i];

        $html .= <<<HTML
<div class="detail-bottom">
    <a href='$matchUrl' class='highlight'>$matchLabels[$i]</a>
</div>
HTML;

    }
    $html .= '</div>';
} else if ($label == "Secondary Source"){
    $lowerlabel = "source";
    $upperlabel = "SOURCE";
    
    $source = $statement;

    $html .= <<<HTML
<div class="detail $lowerlabel">
  <h3>$upperlabel</h3>

<div class="detail-bottom">
    <a>$source</a>
</div>
</div>
HTML;
} else if ($label == "relationshipsA"){
    // match relationships with people

    //Multiple roles in the roles array so match them up with the participant
    $lowerlabel = "relationships";
    $upperlabel = "RELATIONSHIPS";
    //Array for relationships means there are people to match
    $relationships = explode('||', $statement['relationships']);
    $relationshipUrls = explode('||', $statement['qrelationUrls']);
    $relationshipLabels = explode('||', $statement['relationshipLabels']);

    //Remove whitespace from end of arrays
    if (end($relationships) == '' || end($relationships) == ' '){
      array_pop($relationships);
    }
    if (end($relationshipUrls) == '' || end($relationshipUrls) == ' '){
      array_pop($relationshipUrls);
    }
    if (end($relationshipLabels) == '' || end($relationshipLabels) == ' '){
      array_pop($relationshipLabels);
    }

    $html .= <<<HTML
<div class="detail $lowerlabel">
  <h3>$upperlabel</h3>
HTML;


    //Loop through and match up
    $matched = '';
    for($i=0; $i < sizeof($relationships); $i++){
        $explode = explode('/', $relationshipUrls[$i]);
        $personQ = end($explode);
        $personUrl = $baseurl . 'record/person/' . $personQ;
        $matched = $relationships[$i] . ' - ' . $relationshipLabels[$i];

        $html .= <<<HTML
<div class="detail-bottom">
    <div>$relationships[$i]
HTML;

// print_r(controlledVocabulary);die;
        // relationship tool tip
        if(array_key_exists($relationships[$i],controlledVocabulary)){
            $detailinfo = ucfirst(controlledVocabulary[$relationships[$i]]);
            $html .= "<div class='detail-menu'> <h1>$relationships[$i]</h1> <p>$detailinfo</p> </div>";
        }

        $html .= "</div> - <a href='$personUrl' class='highlight'>$relationshipLabels[$i]</a></div>";
    }
    $html .= '</div>';
} else if ($label == "projectsA"){
    $lowerlabel = "contributing project(s)";
    $upperlabel = "CONTRIBUTING PROJECT(S)";

    $projectUrls = explode('||', $statement['projectUrl']);
    $projectNames = explode('||', $statement['projectName']);

    if (end($projectUrls) == '' || end($projectUrls) == ' '){
      array_pop($projectUrls);
    }
    if (end($projectNames) == '' || end($projectNames) == ' '){
      array_pop($projectNames);
    }

    $html .= <<<HTML
<div class="detail $lowerlabel">
  <h3>$upperlabel</h3>
HTML;

    //Loop through and match up
    $matched = '';
    for($i=0; $i < sizeof($projectUrls); $i++){
        $explode = explode('/', $projectUrls[$i]);
        $projectQ = end($explode);
        $projectUrl = $baseurl . 'project/' . $projectQ;
        $matched = $projectNames[$i];

        $html .= <<<HTML
<div class="detail-bottom">
    <a href='$projectUrl'>$projectNames[$i]</a>
HTML;
    }
    $html .= '</div></div>';
} else if ($label == "ecvoA"){
    // print_r($statement);die;
    $lowerlabel = "ecvo - place of origin";
    $upperlabel = "ECVO - PLACE OF ORIGIN";

    $ecvos = explode('||', $statement['ecvo']);
    $originUrls = explode('||', $statement['placeofOrigin']);
    $originLabels = explode('||', $statement['placeOriginlabel']);

    if (end($ecvos) == '' || end($ecvos) == ' '){
      array_pop($ecvos);
    }
    if (end($originUrls) == '' || end($originUrls) == ' '){
      array_pop($originUrls);
    }
    if (end($originLabels) == '' || end($originLabels) == ' '){
      array_pop($originLabels);
    }

    $html .= <<<HTML
<div class="detail $lowerlabel">
  <h3>$upperlabel</h3>
HTML;

    //Loop through and match up
    for($i=0; $i < sizeof($originUrls); $i++){
        $explode = explode('/', $originUrls[$i]);
        $originQ = end($explode);
        $placeUrl = $baseurl . 'record/place/' . $originQ;

        $html .= <<<HTML
<div class="detail-bottom">
    <div>$ecvos[$i]
HTML;

        // ecvo tool tip
        if(array_key_exists($ecvos[$i],controlledVocabulary)){
            $detailinfo = ucfirst(controlledVocabulary[$ecvos[$i]]);
            $html .= "<div class='detail-menu'> <h1>$ecvos[$i]</h1> <p>$detailinfo</p> </div>";
        }

        $html .= "</div> - <a href='$placeUrl' class='highlight'>$originLabels[$i]</a></div>";
    }
    $html .= '</div>';
}else if ($label == "StatusA"){
    // match statuses with events
    $lowerlabel = "status";
    $upperlabel = "STATUS";

    //Array for ststueses means there are events and labels match
    $statuses = explode('||', $statement['statuses']);
    $statusEventUrls = explode('||', $statement['statusEvents']);
    $eventstatusLabels = explode('||', $statement['eventstatusLabels']);

    //Remove whitespace from end of arrays
    if (end($statuses) == '' || end($statuses) == ' '){
      array_pop($statuses);
    }
    if (end($statusEventUrls) == '' || end($statusEventUrls) == ' '){
      array_pop($statusEventUrls);
    }
    if (end($eventstatusLabels) == '' || end($eventstatusLabels) == ' '){
      array_pop($eventstatusLabels);
    }

    $html .= <<<HTML
<div class="detail $lowerlabel">
  <h3>$upperlabel</h3>
HTML;

    //Loop through and match up
    $matched = '';
    for($i=0; $i < sizeof($statuses); $i++){
        $explode = explode('/', $statusEventUrls[$i]);
        $eventQid = end($explode);
        $eventUrl = $baseurl . 'record/event/' . $eventQid;
        $matched = $statuses[$i] . ' - ' . $eventstatusLabels[$i];

        $html .= <<<HTML
<div class="detail-bottom">
    <div>$statuses[$i]
HTML;

        // status tool tip
        if(array_key_exists($statuses[$i],controlledVocabulary)){
            $detailinfo = ucfirst(controlledVocabulary[$statuses[$i]]);
            $html .= "<div class='detail-menu'> <h1>$statuses[$i]</h1> <p>$detailinfo</p> </div>";
        }

        $html .= "</div> - <a href='$eventUrl' class='highlight'>$eventstatusLabels[$i]</a></div>";
    }
    $html .= '</div>';
} else{
    //Default for details without special behavior

    //QID given for sources and projects to link to them
    if($label === "Sources" || $label === "Contributing Projects"){

      $statementArr = explode('||', $statement['label']);
      if (end($statementArr) == '' || end($statementArr) == ' '){
        array_pop($statementArr);
      }

      $qidArr = [];
      $qidurlArr = explode('||', $statement['qid']);
      if (end($qidurlArr) == '' || end($qidurlArr) == ' '){
        array_pop($qidurlArr);
      }
      //Loop through urls and get the qids from the end
      foreach($qidurlArr as $qidurl){
        $urlArr = explode('/', $qidurl);
        $qid = end($urlArr);
        array_push($qidArr, $qid);
      }
    }
    else{
      //Splits the statement(detail) up into multiple parts for multiple details, also trims whitespace off end
      $statementArr = explode('||', $statement);
      if (end($statementArr) == '' || end($statementArr) == ' '){
        array_pop($statementArr);
      }
    }

    $html .= <<<HTML
  <div class="detail $lowerlabel">
    <h3>$upperlabel</h3>
    <div class="detail-bottom">
HTML;

    //For each detail to add create it in seperate divs with a detail menu in each
    for ($x = 0; $x <= (count($statementArr) - 1); $x++){
        if($label === "Name"){
          $detailname = $statementArr[$x];
          $html .= "<div>" . $detailname;
          if(array_key_exists($detailname,controlledVocabulary)){
            $detailinfo = ucfirst(controlledVocabulary[$detailname]);
            $html .= "<div class='detail-menu'> <h1>$detailname</h1> <p>$detailinfo</p> </div>";
          }
          $html .= "</div>";
          continue;
        }
        else if($label === "Geoname Identifier"){
          $html .= '<a href="http://www.geonames.org/' . $statementArr[0] . '/">';
        }
        else if($label === "Sources"){
          $html .= '<a href="' . $baseurl . 'record/source/' . $qidArr[$x] . '">';
        }
        else if($label === "Contributing Projects"){
          $html .= '<a href="' . $baseurl . 'project/' . $qidArr[$x] . '">';
        }
        else if($label === "Location"){
          $locationQ = '';
          $locationName = $statementArr[$x];

          if (array_key_exists($locationName, places) ){
            $locationQ = places[$locationName];
          }

          if ($locationQ != ''){
            $html .= '<a href="' . $baseurl . 'record/place/' . $locationQ . '">';
          }
        }
        else if ($label === 'Roles'){
        }
        else if ($label === 'Modern Country Code'){
            $countryCode = $statementArr[$x];
            $html .= "<div>" . $countryCode;
            if(array_key_exists($countryCode,countrycode)){
              $countryName = ucfirst(countrycode[$countryCode]);
              $html .= "<div class='detail-menu'> <h1>$countryCode</h1> <p>$countryName</p> </div>";
            }
            $html .= "</div>";
            continue;
        }
        else{
          $html .= '<a href="' . $baseurl . 'search/all?' . $lowerlabel . '=' . $statementArr[$x] . '">';
        }
        $detailname = $statementArr[$x];
        $html .= "<div>" . $detailname;
        if(array_key_exists($detailname,controlledVocabulary)){
          $detailinfo = ucfirst(controlledVocabulary[$detailname]);
          $html .= "<div class='detail-menu'> <h1>$detailname</h1> <p>$detailinfo</p> </div>";
        }
        $html .= "</div></a>";

        if ($x != (count($statementArr) - 1)){
            $html.= "<h4> | </h4>";
        }
    }

    $html .= '</div></div>';
  }


  return $html;
}


function getPersonRecordHtml(){
    $qid = $_REQUEST['QID'];
    $type = $_REQUEST['type'];

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
      $query['query'] = <<<QUERY
SELECT ?name ?desc ?sextype  ?race
(group_concat(distinct ?refName; separator = "||") as ?sources)
(group_concat(distinct ?pname; separator = "||") as ?researchprojects)
(group_concat(distinct ?roleslabel; separator = "||") as ?roleslabel)
(group_concat(distinct ?roleevent; separator = "||") as ?roleevent)
(group_concat(distinct ?roleeventlabel; separator = "||") as ?roleeventlabel)
(group_concat(distinct ?statuslabel; separator = "||") as ?status)
(group_concat(distinct ?statusevent; separator = "||") as ?statusevent)
(group_concat(distinct ?eventstatuslabel; separator = "||") as ?eventstatuslabel)

(group_concat(distinct ?ecvo; separator = "||") as ?ecvo)
(group_concat(distinct ?placeofOrigin; separator = "||") as ?placeofOrigin)
(group_concat(distinct ?placeOriginlabel; separator = "||") as ?placeOriginlabel)

(group_concat(distinct ?occupationlabel; separator = "||") as ?occupation)
(group_concat(distinct ?relationslabel; separator = "||") as ?relationships)
(group_concat(distinct ?relationname; separator = "||") as ?qrelationname)
(group_concat(distinct ?relationagentlabel; separator = "||") as ?relationagentlabel)
(group_concat(distinct ?match; separator = "||") as ?match)
(group_concat(distinct ?matchlabel; separator = "||") as ?matchlabel)


 WHERE
{
 VALUES ?agent {wd:$qid} #Q number needs to be changed for every event. 
  ?agent wdt:P3/wdt:P2 wd:Q2; #agent or subclass of agent
  		 ?property  ?object .
  ?object prov:wasDerivedFrom ?provenance .
  ?provenance pr:P35 ?source .
  ?source rdfs:label ?refName;
          wdt:P7 ?project.
  ?project rdfs:label ?pname.
  ?agent wdt:P82 ?name.
  OPTIONAL{?agent schema:description ?desc}.
  OPTIONAL{?agent wdt:P17 ?sex. 
          ?sex rdfs:label ?sextype}.
  OPTIONAL{?agent wdt:P37 ?race}.
  
  OPTIONAL {?agent wdt:P24 ?status.
           ?status rdfs:label ?statuslabel}.
 
  OPTIONAL {?agent p:P86 ?statement.
           ?statement ps:P86 ?ethnodescriptor.
           ?ethnodescriptor rdfs:label ?ecvo.
           OPTIONAL{?statement pq:P31 ?placeofOrigin.
           ?placeofOrigin rdfs:label ?placeOriginlabel.}
           }.
  OPTIONAL {?agent wdt:P21 ?occupation.
           ?occupation rdfs:label ?occupationlabel}.
  OPTIONAL {?agent wdt:P88 ?match}.
OPTIONAL {?agent p:P39 ?statementrole.
           ?statementrole ps:P39 ?roles.
           ?roles rdfs:label ?roleslabel.
           ?statementrole pq:P98 ?roleevent.
           ?roleevent rdfs:label ?roleeventlabel}.
  
 OPTIONAL {?agent p:P24 ?statstatus.
           ?statstatus ps:P24 ?status.
           ?status rdfs:label ?statuslabel.
           ?statstatus pq:P99 ?statusevent.
           ?statusevent rdfs:label ?eventstatuslabel}.
  
  OPTIONAL{
    ?agent p:P25 ?staterel .            
	?staterel ps:P25 ?relations .
  	?relations rdfs:label ?relationslabel.
	?staterel pq:P104 ?relationname.
  	?relationname rdfs:label ?relationagentlabel}.
  OPTIONAL {?agent wdt:P88 ?match.
            ?match rdfs:label ?matchlabel}.
        
  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}GROUP BY ?name ?desc ?sextype  ?race
QUERY;
    }
    else if($type === "place"){
        $query['query'] = <<<QUERY
SELECT ?name ?desc ?located  ?type ?geonames ?code
(group_concat(distinct ?refName; separator = "||") as ?sourceLabel)
(group_concat(distinct ?pname; separator = "||") as ?projectlabel)
(group_concat(distinct ?source; separator = "||") as ?source)
(group_concat(distinct ?project; separator = "||") as ?project)

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
(group_concat(distinct ?participantname; separator = "||") as ?participant)
(group_concat(distinct ?participant; separator = "||") as ?pq)

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
?event rdfs:label ?name.
?event wdt:P81 ?eventtype.
?eventtype rdfs:label ?type.
OPTIONAL{ ?event schema:description ?desc}.
OPTIONAL{?event wdt:P12 ?place.
        ?place rdfs:label ?located}.
OPTIONAL{ ?event wdt:P13 ?datetime.
        BIND(xsd:date(?datetime) AS ?date)}
 OPTIONAL{ ?event wdt:P14 ?endDatetime.
         BIND(xsd:date(?endDatetime) AS ?endDate)}

 OPTIONAL{
  ?event p:P38 ?statement .
	?statement ps:P38 ?roles .
	?roles rdfs:label ?rolename.
	?statement pq:P39 ?participant.
	?participant rdfs:label ?participantname}.



SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}GROUP BY ?name ?desc ?located  ?type ?date ?endDate
QUERY;
    }
    else if ($type == 'source'){
        $query['query'] = <<<QUERY
SELECT ?name ?desc ?project ?pname ?type ?secondarysource

 WHERE
{
 VALUES ?source {wd:$qid} #Q number needs to be changed for every source.
  ?source wdt:P3 wd:Q16;
         wdt:P7 ?project.
  ?project rdfs:label ?pname.

  ?source rdfs:label ?name.
  ?source wdt:P9 ?sourcetype.
  ?sourcetype rdfs:label ?type.
  OPTIONAL{?source wdt:P84 ?secondarysource}.
  OPTIONAL {?source schema:description ?desc}.

  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}GROUP BY ?name ?desc ?project ?pname ?type ?secondarysource
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
// print_r($result);die;

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

    //Sex
    if (isset($record['sextype']) && isset($record['sextype']['value']) && $record['sextype']['value'] != '' ){
      $recordVars['Sex'] = $record['sextype']['value'];
    }

    //Race
    if (isset($record['race']) && isset($record['race']['value']) && $record['race']['value'] != '' ){
      $recordVars['Race'] = $record['race']['value'];
    }

    //Status
    if (isset($record['status']) && isset($record['status']['value']) && $record['status']['value'] != ''){
      if(isset($record['statusevent']) && isset($record['statusevent']['value']) &&
         isset($record['eventstatuslabel']) && isset($record['eventstatuslabel']['value']) &&
         $record['eventstatuslabel']['value'] != '' && $record['statusevent']['value'] ){
        if (empty($record['status']['value'])) {
          $recordVars['StatusA'] = [];
        } else {
          $statusArr = ['statuses' => $record['status']['value'],
                        'statusEvents' => $record['statusevent']['value'],
                        'eventstatusLabels' => $record['eventstatuslabel']['value']
                      ];
          $recordVars['StatusA'] = $statusArr;
        }
      }
      else{
        $recordVars['Status'] = $record['status']['value'];
      }
    }



    //ECVO
    if (isset($record['ecvo']) && isset($record['ecvo']['value']) && $record['ecvo']['value'] != ''){
      if(isset($record['placeofOrigin']) && isset($record['placeofOrigin']['value']) &&
         isset($record['placeOriginlabel']) && isset($record['placeOriginlabel']['value']) &&
         $record['placeofOrigin']['value'] != '' && $record['placeOriginlabel']['value'] ){
        if (empty($record['ecvo']['value'])) {
          $recordVars['ecvoA'] = [];
        } else {
          $ecvoArr = ['ecvo' => $record['ecvo']['value'],
                        'placeofOrigin' => $record['placeofOrigin']['value'],
                        'placeOriginlabel' => $record['placeOriginlabel']['value']
                      ];
          $recordVars['ecvoA'] = $ecvoArr;
        }
      }
      else{
        $recordVars['ECVO'] = $record['ecvo']['value'];
      }
    }

    //Date
    if (isset($record['date']) && isset($record['date']['value']) && $record['date']['value'] != '' ){
      $recordVars['Date'] = $record['date']['value'];
    }

    //Location
    if (isset($record['located']) && isset($record['located']['value']) && $record['located']['value'] != '' ){
      $recordVars['Location'] = $record['located']['value'];
    }

    //Type
    if (isset($record['type']) && isset($record['type']['value']) && $record['type']['value'] != '' ){
      $recordVars['Type'] = $record['type']['value'];
    }

    //Geonames
    if (isset($record['geonames']) && isset($record['geonames']['value']) && $record['geonames']['value'] != '' ){
      $recordVars['Geoname Identifier'] = $record['geonames']['value'];
    }

    //Code
    if (isset($record['code']) && isset($record['code']['value']) && $record['code']['value'] != ''){
      $recordVars['Modern Country Code'] = $record['code']['value'];
    }

    //Source
    if (isset($record['sourceLabel']) && isset($record['sourceLabel']['value']) && $record['sourceLabel']['value'] != '' ){
      if(isset($record['source']['value'])){
        $sourceArr = ['label' => $record['sourceLabel']['value'],
                      'qid' => $record['source']['value']
                     ];
        $recordVars['Sources'] = $sourceArr;
      }
      else{
        $recordVars['Sources'] = $record['sourceLabel']['value'];
      }
    }

    //Relationships
    if (isset($record['relationships']) && isset($record['relationships']['value']) && $record['relationships']['value'] != '' ){
      if(isset($record['qrelationname']) && isset($record['qrelationname']['value']) && isset($record['relationagentlabel']) && isset($record['relationagentlabel']['value'])){
        if (empty($record['relationships']['value']) ){
            $recordVars['relationshipsA'] = [];
        } else {
            $relationsipArr = ['relationships' => $record['relationships']['value'],
                              'qrelationUrls' => $record['qrelationname']['value'],
                              'relationshipLabels' => $record['relationagentlabel']['value']
                              ];
            $recordVars['relationshipsA'] = $relationsipArr;
        }
      }
    }

    //CloseMatch
    if (isset($record['match']) && isset($record['match']['value']) && $record['match']['value'] != ''  ){
      if(isset($record['matchlabel']) && isset($record['matchlabel']['value']) &&  
         $record['matchlabel']['value'] != '' ){
        $closeMatchArr = ['matchLabels' => $record['matchlabel']['value'],
                           'matchUrls' => $record['match']['value']
                          ];
        $recordVars['closeMatchA'] = $closeMatchArr;
      }
    }

    //Project
    if (isset($record['projectlabel']) && isset($record['projectlabel']['value'])  && $record['projectlabel']['value'] != '' ){
        if(isset($record['project']) && isset($record['project']['value'])  && $record['project']['value'] != '' ){
            $projectArr = ['label' => $record['projectlabel']['value'],
                           'qid' => $record['project']['value']
                          ];
            $recordVars['Contributing Projects'] = $projectArr;
        }
        else{
            $recordVars['Contributing Projects'] = $record['projectlabel']['value'];
        }
    } else if (isset($record['project']) && isset($record['project']['value'])  && $record['project']['value'] != '' ){     // projects for source page
        if (isset($record['pname']) && isset($record['pname']['value'])  && $record['pname']['value'] != '' ) {
            $projectArr = ['projectUrl' => $record['project']['value'],
                           'projectName' => $record['pname']['value']
                          ];
            $recordVars['projectsA'] = $projectArr;
        }  
    }

    //secondarysource
    if (isset($record['secondarysource']) && isset($record['secondarysource']['value'])  && $record['secondarysource']['value'] != '' ){
      $recordVars['Secondary Source'] = $record['secondarysource']['value'];
    }


    //Roles
    //Gets the roles, participants, and pqID if they exist and matches them together
    if (isset($record['roles']) && isset($record['roles']['value']) &&  $record['roles']['value'] != ''){
      if(isset($record['participant']) && isset($record['participant']['value']) && 
         $record['participant']['value'] != '' &&  $record['pq']['value'] != '' ){
        //There are participants to match with their roles and qIDs
        $rolesArr = ['roles' => $record['roles']['value'],
                     'participant' => $record['participant']['value'],
                     'pq' => $record['pq']['value']
                    ];
        $recordVars['RolesA'] = $rolesArr;
      }
    } else if(isset($record['roleevent']) && isset($record['roleevent']['value'])){
        if(isset($record['roleeventlabel']) && isset($record['roleeventlabel']['value']) &&
            $record['roleeventlabel']['value'] != '' && $record['roleevent']['value'] != '' ){
          //There are participants to match with their roles and qIDs
          $rolesArr = ['roles' => $record['roleslabel']['value'],
                        'eventRoles' => $record['roleevent']['value'],
                        'eventRoleLabels' => $record['roleeventlabel']['value']
                      ];
          $recordVars['eventRolesA'] = $rolesArr;
        }
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

    // print_r($recordVars);die;
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
}

function getFullRecordConnections(){
  if (!isset($_REQUEST['Qid']) || !isset($_REQUEST['recordForm'])){
    echo 'missing params';
    return;
  }

  $QID = $_REQUEST['Qid'];
  $recordform = $_REQUEST['recordForm'];
  // echo $QID.' '.$recordform;die;

  // these need to be filled in for each type of form
  if ($recordform == 'source'){
    return getSourcePageConnections($QID);
  } else if ($recordform == 'event') {
    return getEventPageConnections($QID);
  } else if ($recordform == 'person') {
    return getPersonPageConnections($QID);
  } else {
    return '';
  }




}



// connections for the person full record page
function getPersonPageConnections($QID) {
  $connections = array();


}

// connections for the source full record page
function getSourcePageConnections($QID) {
  $connections = array();


  // people connections
  $peopleQuery['query'] = <<<QUERY
SELECT DISTINCT ?people ?peoplename (SHA512(CONCAT(STR(?people), STR(RAND()))) as ?random)

 WHERE
{
 VALUES ?source {wd:$QID} #Q number needs to be changed for every source. 
  ?source wdt:P3 wd:Q16.
  ?people wdt:P3/wdt:P2 wd:Q2; #agent or subclass of agent
  		?property  ?object .
  ?object prov:wasDerivedFrom ?provenance .
  ?provenance pr:P35 ?source .
  ?people rdfs:label ?peoplename
  
  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}ORDER BY ?random
QUERY;
    

    //Execute query
    $ch = curl_init(BLAZEGRAPH_URL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($peopleQuery));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        'Accept: application/sparql-results+json'
    ));
    $result = curl_exec($ch);
    curl_close($ch);
    //Get result
    $result = json_decode($result, true)['results']['bindings'];
    $connections['Person-count'] = count($result);
    $connections['Person'] = array_slice($result, 0, 8);  // return the first 8 results

  
  // events connections
  $eventsQuery['query'] = <<<QUERY
SELECT DISTINCT ?event ?eventlabel ?source (SHA512(CONCAT(STR(?event), STR(RAND()))) as ?random)

 WHERE
{
 VALUES ?source {wd:$QID} #Q number needs to be changed for every source. 
  ?source wdt:P8 ?event.
  ?event rdfs:label ?eventlabel
  
  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}ORDER BY ?random
QUERY;
    

    //Execute query
    $ch = curl_init(BLAZEGRAPH_URL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($eventsQuery));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        'Accept: application/sparql-results+json'
    ));
    $result = curl_exec($ch);
    curl_close($ch);
    //Get result
    $result = json_decode($result, true)['results']['bindings'];
    $connections['Event-count'] = count($result);
    $connections['Event'] = array_slice($result, 0, 8);  // return the first 8 results


  // place connections
  $placeQuery['query'] = <<<QUERY
SELECT DISTINCT ?place ?placelabel (SHA512(CONCAT(STR(?place), STR(RAND()))) as ?random)

 WHERE
{
 VALUES ?source {wd:$QID} #Q number needs to be changed for every source. 
  ?source wdt:P8 ?event.
  ?event wdt:P12 ?place.
  ?place rdfs:label ?placelabel
  
  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}ORDER BY ?random
QUERY;
    

    //Execute query
    $ch = curl_init(BLAZEGRAPH_URL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($placeQuery));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        'Accept: application/sparql-results+json'
    ));
    $result = curl_exec($ch);
    curl_close($ch);
    //Get result
    $result = json_decode($result, true)['results']['bindings'];
    $connections['Place-count'] = count($result);
    $connections['Place'] = array_slice($result, 0, 8);  // return the first 8 results


    return json_encode($connections);
}


// connections for the event full record page
function getEventPageConnections($QID) {
  $connections = array();

  // people connections
  $peopleQuery['query'] = <<<QUERY
SELECT DISTINCT ?people ?peoplename (SHA512(CONCAT(STR(?people), STR(RAND()))) as ?random)

 WHERE
{
 VALUES ?event {wd:$QID} #Q number needs to be changed for every event. 
  ?event wdt:P3 wd:Q34.
  ?event p:P38 ?statement.
  ?statement ps:P38 ?name. 
  ?statement pq:P39 ?people.
  ?people rdfs:label ?peoplename.
  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}ORDER BY ?random
QUERY;
    

    //Execute query
    $ch = curl_init(BLAZEGRAPH_URL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($peopleQuery));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        'Accept: application/sparql-results+json'
    ));
    $result = curl_exec($ch);
    curl_close($ch);
    //Get result
    $result = json_decode($result, true)['results']['bindings'];
    $connections['Person-count'] = count($result);

    $connections['Person'] = array_slice($result, 0, 8);  // return the first 8 results


  // project connections
  $projectQuery['query'] = <<<QUERY
SELECT DISTINCT ?source ?refName ?project ?projectName (SHA512(CONCAT(STR(?source), STR(RAND()))) as ?random)

 WHERE
{
VALUES ?event {wd:$QID} #Q number needs to be changed for every event. 
  ?event wdt:P3 wd:Q34;
  		?property  ?object .
  ?object prov:wasDerivedFrom ?provenance .
  ?provenance pr:P35 ?source .
  ?source rdfs:label ?refName;
          wdt:P7 ?project.
  ?project rdfs:label ?projectName.
  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}ORDER BY ?random
QUERY;
    

    //Execute query
    $ch = curl_init(BLAZEGRAPH_URL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($projectQuery));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        'Accept: application/sparql-results+json'
    ));
    $result = curl_exec($ch);
    curl_close($ch);
    //Get result
    $result = json_decode($result, true)['results']['bindings'];


    $projectConnections = array();

    // clean up the data
    foreach ($result as $res){
        if (isset($res['project']) && isset($res['projectName'])){
          $projectConnections[] = array('project' => $res['project'], 'projectName' => $res['projectName']);
        }
    }

    $connections['Project-count'] = count($projectConnections);
    $connections['Project'] = array_slice($projectConnections, 0, 8);  // return the first 8 results



    // places connections
  $placesQuery['query'] = <<<QUERY
SELECT DISTINCT ?place ?placelabel (SHA512(CONCAT(STR(?place), STR(RAND()))) as ?random)

 WHERE
{
 VALUES ?event {wd:$QID} #Q number needs to be changed for every event. 
  ?event wdt:P12 ?place.
  ?place rdfs:label ?placelabel
  
  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}ORDER BY ?random
QUERY;
    

    //Execute query
    $ch = curl_init(BLAZEGRAPH_URL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($placesQuery));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        'Accept: application/sparql-results+json'
    ));
    $result = curl_exec($ch);
    curl_close($ch);
    //Get result
    $result = json_decode($result, true)['results']['bindings'];
    $connections['Place-count'] = count($result);

    $connections['Place'] = array_slice($result, 0, 8);  // return the first 8 results


    // source connections
  $sourceQuery['query'] = <<<QUERY
SELECT DISTINCT ?source ?sourcelabel (SHA512(CONCAT(STR(?source), STR(RAND()))) as ?random)

 WHERE
{
 VALUES ?event {wd:$QID} #Q number needs to be changed for every event. 
  ?event wdt:P3 wd:Q34;
          ?property  ?object .
  	?object prov:wasDerivedFrom ?provenance .
  	?provenance pr:P35 ?source .
	?source rdfs:label ?sourcelabel
  
  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}ORDER BY ?random
QUERY;
    

    //Execute query
    $ch = curl_init(BLAZEGRAPH_URL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($sourceQuery));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
        'Accept: application/sparql-results+json'
    ));
    $result = curl_exec($ch);
    curl_close($ch);
    //Get result
    $result = json_decode($result, true)['results']['bindings'];
    $connections['Source-count'] = count($result);
    $connections['Source'] = array_slice($result, 0, 8);  // return the first 8 results



    return json_encode($connections);
}


function debugfunc($debugobject){?>
    <pre><?php echo var_dump($debugobject);?></pre>
<?php }
?>
