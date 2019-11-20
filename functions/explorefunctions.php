<?php

function callAPI($url,$limit,$offset){
  $url.='&format=json';
    // Create a stream
    $opts = array(
      'http'=>array(
        'method'=>"GET",
        'header'=>"User-Agent: Enslaved.org/Frontend"
      )
    );

    $context = stream_context_create($opts);

    // Open the file using the HTTP headers set above
    $json = file_get_contents($url,false,$context);
    return $json;

}








//get all agents numbers
function queryAllAgentsCounter(){
    include BASE_LIB_PATH."variableIncluder.php";

    $query="SELECT  (COUNT(distinct ?agent) AS ?count)
        WHERE {
                ?agent $wdt:$instanceOf/$wdt:$subclassOf $wd:$agent. #agent or subclass of agent
            MINUS{?agent $wdt:$hasParticipantRole $wd:$researcher}
            SERVICE wikibase:label { bd:serviceParam wikibase:language \"[AUTO_LANGUAGE],en\" . }
        }

        ORDER BY ?count
    ";

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
    include BASE_LIB_PATH."variableIncluder.php";

    $query="SELECT (COUNT(distinct ?item) AS ?count) WHERE {?item $wdt:$instanceOf $wd:$event .}";

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
    include BASE_LIB_PATH."variableIncluder.php";

    $query="SELECT (count( distinct ?item) AS ?count) WHERE {?item $wdt:$instanceOf $wd:$place .}";
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
    include BASE_LIB_PATH."variableIncluder.php";

    $query="SELECT (count( distinct ?item) AS ?count) WHERE {?item $wdt:$instanceOf $wd:$researchProject .}";
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
    include BASE_LIB_PATH."variableIncluder.php";

    $query="SELECT (count( distinct ?item) AS ?count) WHERE {?item $wdt:$instanceOf $wd:$entityWithProvenance .}";
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
    include BASE_LIB_PATH."variableIncluder.php";
    $query="SELECT (count( distinct ?item) AS ?count) WHERE
    {
    {?item $wdt:$instanceOf $wd:$researchProject .}
    UNION{ ?item $wdt:$instanceOf/$wdt:$subclassOf $wd:$agent .}
    UNION{ ?item $wdt:$instanceOf $wd:$entityWithProvenance .}
    UNION{ ?item $wdt:$instanceOf $wd:$place .}
    UNION{ ?item $wdt:$instanceOf $wd:$event .}
    }";
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
function counterOfAllGenders($filters=array()){
    include BASE_LIB_PATH."variableIncluder.php";
    if (isset($filters['gender'])){
        unset($filters['gender']);
    }
    $queryFilters = createQueryFilters("people", $filters);

    $query="SELECT ?sex ?sexLabel (count( distinct ?agent) AS ?count) WHERE{
        ?agent $wdt:$instanceOf/$wdt:$subclassOf $wd:$agent .
        ?agent $wdt:$hasSex ?sex.
        $queryFilters
        SERVICE wikibase:label { bd:serviceParam wikibase:language \"[AUTO_LANGUAGE],en\". }
        } GROUP BY ?sex ?sexLabel
        ORDER BY DESC(?count)";

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
function counterOfRole($filters=array()){
    include BASE_LIB_PATH."variableIncluder.php";
    if (isset($filters['role_types'])){
        unset($filters['role_types']);
    }
    $queryFilters = createQueryFilters("people", $filters);

    $query= "SELECT ?role ?roleLabel (count( distinct ?agent) AS ?count) WHERE
    {  ?agent $wdt:$instanceOf $wd:$person.
        ?agent $wdt:$hasParticipantRole ?role.
        $queryFilters
            SERVICE wikibase:label { bd:serviceParam wikibase:language \"[AUTO_LANGUAGE],en\". }
    }GROUP BY ?role ?roleLabel
    ORDER BY DESC(?count)";

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

function counterOfStatus($filters=array()){
    include BASE_LIB_PATH."variableIncluder.php";
    if (isset($filters['status'])){
        unset($filters['status']);
    }
    $queryFilters = createQueryFilters("people", $filters);

    $query= "SELECT ?status ?statusLabel (count( distinct ?agent) AS ?count) WHERE
    {  ?agent $wdt:$instanceOf $wd:$person.
        ?agent $wdt:$hasPersonStatus ?status.
        $queryFilters
            SERVICE wikibase:label { bd:serviceParam wikibase:language \"[AUTO_LANGUAGE],en\". }
    } GROUP BY ?status ?statusLabel
    ORDER BY DESC(?count)";

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


function counterOfOccupation($filters=array()){
    include BASE_LIB_PATH."variableIncluder.php";
    if (isset($filters['occupation'])){
        unset($filters['occupation']);
    }
    $queryFilters = createQueryFilters("people", $filters);

    $query= "SELECT ?occupation ?occupationLabel (count( distinct ?agent) AS ?count) WHERE
    {  ?agent $wdt:$instanceOf $wd:$person.
        ?agent $wdt:$hasOccupation ?occupation.
        $queryFilters
            SERVICE wikibase:label { bd:serviceParam wikibase:language \"[AUTO_LANGUAGE],en\". }
    } GROUP BY ?occupation ?occupationLabel
    ORDER BY DESC(?count)";

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
function counterOfAge($filters=array()){
    include BASE_LIB_PATH."variableIncluder.php";
    if (isset($filters['age_category'])){
        unset($filters['age_category']);
    }
    $queryFilters = createQueryFilters("people", $filters);

    $ageCategoryQuery ="SELECT ?agecategory ?agecategoryLabel (count( distinct ?agent) as ?count) where{
                            ?agecategory $wdt:$instanceOf $wd:$ageCategory.
                            ?agent $wdt:$instanceOf $wd:$person.
                            ?agent $wdt:$hasAgeCategory ?agecategory.
                            $queryFilters
                            SERVICE wikibase:label { bd:serviceParam wikibase:language \"[AUTO_LANGUAGE],en\" . }

                        }group by ?agecategory ?agecategoryLabel
                        ";

    // print_r($ageCategoryQuery);die;

    $encode=urlencode($ageCategoryQuery);
    $call=API_URL.$encode;
    $ageCategoryResult=callAPI($call,'','');
    $ageCategoryResult = json_decode($ageCategoryResult);

    return json_encode($ageCategoryResult->results->bindings);
}

function counterOfEthnodescriptor($filters=array()){
    include BASE_LIB_PATH."variableIncluder.php";
    if (isset($filters['ethnodescriptor'])){
        unset($filters['ethnodescriptor']);
    }
    $queryFilters = createQueryFilters("people", $filters);

    $query="SELECT ?ethno ?ethnoLabel (count( distinct ?agent) as ?count)
        {?agent $wdt:$instanceOf $wd:$person.
        ?agent $wdt:$hasEthnolinguisticDescriptor ?ethno.
        $queryFilters
        SERVICE wikibase:label { bd:serviceParam wikibase:language \"[AUTO_LANGUAGE],en\". }
        }GROUP BY ?ethno ?ethnoLabel
        ORDER BY ?ethnoLabel";

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



function counterOfEventType($filters=array()){
    include BASE_LIB_PATH."variableIncluder.php";
    if (isset($filters['event_type'])){
        unset($filters['event_type']);
    }
    $queryFilters = createQueryFilters("events", $filters);

    $query="SELECT ?eventType ?eventTypeLabel (count( distinct ?event) AS ?count)  WHERE{
        ?event $wdt:$instanceOf $wd:$event.
        ?event $wdt:$hasEventType ?eventType.
        $queryFilters
        SERVICE wikibase:label { bd:serviceParam wikibase:language \"[AUTO_LANGUAGE],en\". }
        } GROUP BY ?eventType ?eventTypeLabel
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
    include BASE_LIB_PATH."variableIncluder.php";
    $query="SELECT ?place ?placeLabel ?(count( distinct ?event) AS ?count) WHERE {
                  ?event $wdt:$instanceOf $wd:$event.
                  ?event $wdt:$atPlace ?place.

              SERVICE wikibase:label { bd:serviceParam wikibase:language \"[AUTO_LANGUAGE],en\". }
            }GROUP BY ?place ?placeLabel
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

function counterOfPlaceType($filters=array()){
    include BASE_LIB_PATH."variableIncluder.php";
    if (isset($filters['place_type'])){
        unset($filters['place_type']);
    }
    $queryFilters = createQueryFilters("places", $filters);

    $query= "
      SELECT DISTINCT ?type ?typeLabel (count( distinct ?place) AS ?count) WHERE {
          ?place $wdt:$instanceOf $wd:$place; #it's a place
              $wdt:$hasPlaceType ?type.
              $queryFilters
      SERVICE wikibase:label { bd:serviceParam wikibase:language \"en\" .}
  }GROUP BY ?type ?typeLabel
      order by ?typeLabel
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

function counterOfSourceType($filters=array()){
    include BASE_LIB_PATH."variableIncluder.php";
    if (isset($filters['source_type'])){
        unset($filters['source_type']);
    }
    $queryFilters = createQueryFilters("places", $filters);

     $query="SELECT ?sourcetype ?sourcetypeLabel (count( distinct ?source) AS ?count)  WHERE{
         ?source $wdt:$instanceOf $wd:$entityWithProvenance.
         ?source $wdt:$hasOriginalSourceType ?sourcetype.
         $queryFilters
         SERVICE wikibase:label { bd:serviceParam wikibase:language \"[AUTO_LANGUAGE],en\". }
    } GROUP BY ?sourcetype ?sourcetypeLabel
    ORDER BY DESC(?count)";

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
// used for counters on explore pages
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
        if ($type == "Date"){
            return getEventDateRange();
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
    }

    if($category == "Places") {
        if ($type == "Place Type"){
          return counterOfPlaceType();
        }
    }

    if($category == "Sources") {
      if ($type == "Source Type"){
        return counterOfSourceType();
      }
  }
}

function getHomePageCounters(){
    $counters = array(
            'all' => counterofAllitems(),
            'agents' => queryAllAgentsCounter(),
            'events' => queryEventCounter(),
            'places' => queryPlaceCounter(),
            // 'projects' => queryProjectsCounter(),
            'sources' => querySourceCounter(),
    );

    return json_encode($counters);
}


function getSearchFilterCounters(){
    $searchType = '';   // what we are searching for
    $filters = array(); // array of filters
    $filterTypes = array(); // types of filter counters to return

    if (isset($_GET['search_type'])){
        $searchType = $_GET['search_type'];
    }

    if (isset($_GET['filters'])){
        $filters = $_GET['filters'];
    }

    if (isset($_GET['filter_types'])){
        $filterTypes = $_GET['filter_types'];
    }

    $counters = array();
    foreach ($filterTypes as $filterType) {
        if ($filterType == "people"){
            $queryFilters = createQueryFilters("people", $filters);
            $peopleFilters = array(
                'Gender' => counterOfAllGenders($filters),
                'Age Category' => counterOfAge($filters),
                'Ethnodescriptor' => counterOfEthnodescriptor($filters),
                'Role Types' => counterOfRole($filters),
                'Status' => counterOfStatus($filters),
                'Occupation' => counterOfOccupation($filters),
            );
            $counters['People'] = $peopleFilters;
        } else if ($filterType == "events" || $filterType == "event"){
            $eventFilters = array(
                'Event Type' => counterOfEventType($filters)
            );
            $counters['Event'] = $eventFilters;
        } else if ($filterType == "places" || $filterType == "place"){
            $placeFilters = array(
                'Place Type' => counterOfPlaceType($filters)
            );
            $counters['Place'] = $placeFilters;
        } else if ($filterType == "sources" || $filterType == "source"){
            $sourceFilters = array(
                'Source Type' => counterOfSourceType($filters)
            );
            $counters['Source'] =$sourceFilters;
        }
    }

    return json_encode($counters);
}

function getEventDateRange() {
    include BASE_LIB_PATH."variableIncluder.php";

    $fullResults = [];
    $query="SELECT ?year WHERE {
            ?event $wdt:$instanceOf $wd:$event; #event
                   $wdt:$startsAt ?date.
              BIND(str(YEAR(?date)) AS ?year).
            }ORDER BY DESC(?year)
          LIMIT 1";

    $encode=urlencode($query);
    $call=API_URL.$encode;
    $res=callAPI($call,'','');
    $res= json_decode($res);
    // print_r($res);die;
    if (!empty($res)){
        $fullResults['max'] = $res->results->bindings;
    }else{
        $fullResults['max'] = $res;
    }
    $fullResults['max'] = $fullResults['max'][0]->year->value;

    $query="SELECT ?year WHERE {
            ?event $wdt:$instanceOf $wd:$event; #event
                   $wdt:$startsAt ?date.
              BIND(str(YEAR(?date)) AS ?year).
            }ORDER BY ASC(?year)
          LIMIT 1";

    $encode=urlencode($query);
    $call=API_URL.$encode;
    $res=callAPI($call,'','');

    $res= json_decode($res);

    if (!empty($res)){
        $fullResults['min'] = $res->results->bindings;
    }else{
        $fullResults['min'] = $res;
    }
    $fullResults['min'] = $fullResults['min'][0]->year->value;

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

//finish to display ranks here. Error now it only displays PI with higher rank.
function getProjectFullInfo() {
    include BASE_LIB_PATH."variableIncluder.php";

    $qid = $_GET['qid'];
    $query = "SELECT  ?title ?desc ?link
             (group_concat(distinct ?pinames; separator = \"||\") as ?piNames)
             (group_concat(distinct ?contributor; separator = \", \") as ?contributor)
            WHERE
            {
             VALUES ?project { $wd:$qid } #Q number needs to be changed for every project.
              ?project $wdt:$instanceOf $wd:$researchProject. #all projects
              OPTIONAL{?project $wdt:$hasLink ?link. }
              ?project schema:description ?desc.
              ?project $rdfs:label ?title.
              OPTIONAL{ ?project $wdt:$hasContributor ?contributor.}
              ?project $wdt:$hasPI ?pi.
              ?pi $rdfs:label ?pinames.
              SERVICE wikibase:label { bd:serviceParam wikibase:language \"[AUTO_LANGUAGE]\". }
            }GROUP BY ?title ?desc ?link";

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

function createDetailHtml($statement,$label){
  $baseurl = BASE_URL;
  $upperlabel = $label;
  $lowerlabel = strtolower($label);
  $html = '';

  // don't show the label if it is empty
  if (empty($statement)){
    return "";
  }


  if($label === "RolesA"){
    //Multiple roles in the roles array so match them up with the participant
    $lowerlabel = "roles";
    $upperlabel = "Roles";
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
      if (!isset($pq[$i]) || !isset($participants[$i]) || !isset($roles[$i])){
        continue;
      }

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
    $upperlabel = "Roles";
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
        if (!isset($eventRoleUrls[$i]) || !isset($eventRoleLabels[$i]) ){
          continue;
        }
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
    $upperlabel = "Close Match";

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
        if (!isset($matchLabels[$i]) ){
          continue;
        }

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
    $upperlabel = "Source";

    $source = $statement;

    $html .= <<<HTML
<div class="detail $lowerlabel">
  <h3>$upperlabel</h3>

<div class="detail-bottom">
    <a>$source</a>
</div>
</div>
HTML;
} else if ($label == "External References"){
    $lowerlabel = "external references";
    $upperlabel = "External References";
    $references = explode('||', $statement);

    $html .= "<div class=\"detail $lowerlabel\">
            <h3>$upperlabel</h3>
            <div class='detail-bottom'>
            ";

foreach ($references as $ref) {
    $html .= "<a href=\"$ref\" target='_blank'>$ref<a>
        <br>";
}
    $html .= "</div>
    </div>";
} else if ($label == "relationshipsA"){
    // match relationships with people

    //Multiple roles in the roles array so match them up with the participant
    $lowerlabel = "relationships";
    $upperlabel = "Relationships";
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
        if (!isset($relationshipUrls[$i]) || !isset($relationshipLabels[$i])){
          continue;
        }

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
    $upperlabel = "Contributing Project(s)";

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
        if (!isset($projectNames[$i]) ){
          continue;
        }

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
    $lowerlabel = "ethnolinguistic descriptor - place of origin";
    $upperlabel = "Ethnolinguistic Descriptor - Place Of Origin";

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
        if (!isset($originLabels[$i]) ){
          continue;
        }

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
    $upperlabel = "Status";

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
    for($i=0; $i < sizeof($statusEventUrls); $i++){
      if (!isset($eventstatusLabels[$i]) || !isset($statuses[$i])){
        continue;
      }

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

function getFullRecordHtml(){
    include BASE_LIB_PATH."variableIncluder.php";
    $qid = $_REQUEST['QID'];
    $type = $_REQUEST['type'];

    //QUERY FOR RECORD INFO
    $query = [];
    include BASE_PATH."queries/fullRecord/".$type.".php";
    $query['query'] = $tempQuery;
    print_r($query);die;
    $result = blazegraphSearch($query);
    // print_r($result);die;
    if (empty($result)){
      echo json_encode(Array());
      die;
    }

    $record = $result[0];

    //Get variables from query
    $recordVars = [];

    //Name
    $recordVars['Name'] = $record['name']['value'];

    // First Name
    if (isset($record['firstname']) && isset($record['firstname']['value']) ){
        $recordVars['First Name'] = $record['firstname']['value'];
    }

    // Surname
    if (isset($record['surname']) && isset($record['surname']['value']) ){
        $recordVars['Surname'] = $record['surname']['value'];
    }

    // Alternate name
    if (isset($record['altname']) && isset($record['altname']['value']) ){
        $recordVars['Alternate Name'] = $record['altname']['value'];
    }

    //Sex
    if (isset($record['sextype']) && isset($record['sextype']['value']) && $record['sextype']['value'] != '' ){
      $recordVars['Sex'] = $record['sextype']['value'];
    }

    //Race
    if (isset($record['race']) && isset($record['race']['value']) && $record['race']['value'] != '' ){
      $recordVars['Race'] = $record['race']['value'];
    }

    // descriptions for items
    if (isset($record['description']) && isset($record['description']['value']) ){
        $recordVars['Description'] = $record['description']['value'];
    }

    // descriptions for items
    if (isset($record['extref']) && isset($record['extref']['value']) ){
        $recordVars['External References'] = $record['extref']['value'];
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

    if (isset($record['locatedIn']) && isset($record['locatedIn']['value'])  && $record['locatedIn']['value'] != '' ){
      $recordVars['Located In'] = $record['locatedIn']['value'];
    }

    //Sex
    if (isset($record['sextype']) && isset($record['sextype']['value']) && $record['sextype']['value'] != '' ){
      $recordVars['Sex'] = $record['sextype']['value'];
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
    <a id='last-page' href="$url"><span id=previous-title>$recordform / </span></a>
    <span id='current-title'>$name</span>
</h4>
<h1>$name</h1>
<h2 class='date-range'><span>$dateRange</span></h2>
HTML;

    $htmlArray['header'] = $html;

    //Description section
    $html = '';

    $htmlArray['description'] = $html;

    //Detail section
    $html = '';

    $html .= '<div class="detailwrap">';

// print_r($recordVars);die;
    foreach($recordVars as $key => $value){
      $html .= createDetailHtml($value, $key);
    }
    $html .= '</div>';

    $htmlArray['details'] = $html;

    //Timeline section

    //Timeline
    // Code for creating events on Timeline
    // Replace with Kora 3 events
    $events = [];

    // print_r($record);

    //Creating the events array for the timeline
    if (isset($record['allevents']) && isset($record['allevents']['value']) &&  $record['allevents']['value'] != '' ){
        if(isset($record['alleventslabel']) && isset($record['alleventslabel']['value']) && $record['alleventslabel']['value'] != ''){
            $allEventUrls = explode('||', $record['allevents']['value']);
            $allEventLabels = explode('||', $record['alleventslabel']['value']);

            $allEventQids = array();
            foreach($allEventUrls as $url){
                $explode = explode('/', $url);
                $eventQ = end($explode);
                array_push($allEventQids, $eventQ);
            }

            $allEventStartYears = array();
            $allEventTypes = array();

            $eventsAndStartYears = array();
            if (isset($record['startyear']) && isset($record['startyear']['value']) && $record['startyear']['value'] != '' ){
                $eventsAndStartYears = explode('||', $record['startyear']['value']);
            }

            //placeTypes
            if (isset($record['placetype']) && isset($record['placetype']['value']) && $record['placetype']['value'] != ''  ){
                $placeTypes = explode('||', $record['placetype']['value']);
                $allPlacesToTypesMap = array();
                foreach($placeTypes as $matchString){
                    $parts = explode(' - ', $matchString);
                    $placeUrl = $parts[0];
                    $placeQ = explode('/', $placeUrl);
                    $placeQ = end($placeQ);
                    $placeType = $parts[1];

                    // group the place Qids with their types
                    $allPlacesToTypesMap[$placeQ] = array('placeQ' => $placeQ, 'placeType' => $placeType);
                }
            }

            // all places
            if (isset($record['allplaces']) && isset($record['allplaces']['value']) && $record['allplaces']['value'] != ''  ){
                if (isset($record['allplaceslabel']) && isset($record['allplaceslabel']['value']) && $record['allplaceslabel']['value'] != '' ){
                    $allPlaceLabels = explode('||', $record['allplaceslabel']['value']);
                    $allPlaceUrls = explode('||', $record['allplaces']['value']);

                    $allEventPlaces = explode('||', $record['eventplace']['value']);
                    $allEventToPlaceMap = array();
                    foreach($allEventPlaces as $matchString){
                        $parts = explode(' - ', $matchString);
                        $eventUrl = $parts[0];
                        $eventQ = explode('/', $eventUrl);
                        $eventQ = end($eventQ);
                        $placeName = $parts[1];

                        $placeUrlIndex = array_search($placeName, $allPlaceLabels);
                        $placeUrl = $allPlaceUrls[$placeUrlIndex];
                        $placeQ = explode('/', $placeUrl);
                        $placeQ = end($placeQ);

                        $placeType = "";
                        if (isset($allPlacesToTypesMap[$placeQ]) && isset($allPlacesToTypesMap[$placeQ]['placeType'])){
                            $placeType = $allPlacesToTypesMap[$placeQ]['placeType'];
                        }

                        // group the place name and q value with their events
                        $allEventToPlaceMap[$eventQ] = array('name' => $placeName, 'placeQ' => $placeQ, 'placeType' => $placeType);
                    }
                }
            }

            // match events to start years
            // there is also an event type in this string but its not being used right now
            foreach ($eventsAndStartYears as $eventInfo){
                $pieces = explode(' - ', $eventInfo);
                $eName = $pieces[0];
                $eType = $pieces[1];
                $year = end($pieces);
                $allEventStartYears[$eName] = $year;
                $allEventTypes[$eName] = $eType;
            }

            // end year stuff hasn't been tested or working yet
            if (isset($record['endyear']) && isset($record['endyear']['value']) ){
            $allEventEndYears = explode('||', $record['endyear']['value']);
            }

            // descriptions for timeline events
            if (isset($record['desc']) && isset($record['desc']['value']) ){
            $allEventDescritions = explode('||', $record['desc']['value']);
            }

            // roles per event
            if (isset($recordVars['eventRolesA'])){
                $allRoles = explode('||', $recordVars['eventRolesA']['roles']);
                $allLabels = explode('||', $recordVars['eventRolesA']['eventRoleLabels']);
                $allEventRoles = array();

                // match roles with event labels
                for($i=0; $i < sizeof($allRoles); $i++){
                    if (isset($allLabels[$i]) && isset($allRoles[$i])){
                        $allEventRoles[$allLabels[$i]] = $allRoles[$i];
                    }
                }
            }

            // statuses per event
            if (isset($recordVars['StatusA'])){
                $allStatuses= explode('||', $recordVars['StatusA']['statuses']);
                $allLabels = explode('||', $recordVars['StatusA']['eventstatusLabels']);
                $allEventStatuses = array();

                // match statuses with event labels
                for($i=0; $i < sizeof($allStatuses); $i++){
                    if (isset($allLabels[$i]) && isset($allStatuses[$i])){
                        $allEventStatuses[$allLabels[$i]] = $allStatuses[$i];
                    }
                }
            }

            // create the events array
            foreach($allEventQids as $i => $eventQ){
                if (!isset($allEventLabels[$i])){
                  continue;
                }

                // event label
                $eventLabel = '';
                if (isset($allEventLabels[$i])){
                    $eventLabel = $allEventLabels[$i];
                }

                // start year
                $eventStartYear = '';
                if (isset($allEventStartYears[$eventLabel])){
                    $eventStartYear = $allEventStartYears[$eventLabel];
                    if ($eventStartYear == 1854){ $eventStartYear = '';}
                }
                // end year
                $eventEndYear = '';
                if (isset($allEventEndYears[$eventLabel])){
                    $eventEndYear = $allEventEndYears[$eventLabel];
                }
                // event type
                $eventType = '';
                if (isset($allEventTypes[$eventLabel])){
                    $eventType = $allEventTypes[$eventLabel];
                }
                // event descriptions
                $eventDesc = '';
                if (isset($allEventDescritions[$eventLabel])){
                    $eventDesc = $allEventDescritions[$eventLabel];
                }
                // event roles
                $eventRole = '';
                if (isset($allEventRoles[$eventLabel])){
                    $eventRole = $allEventRoles[$eventLabel];
                }

                // event status
                $eventStatus = '';
                if (isset($allEventStatuses[$eventLabel])){
                    $eventStatus = $allEventStatuses[$eventLabel];
                }

                // event places
                $eventPlaces = '';
                if (isset($allEventToPlaceMap[$eventQ])){
                    $eventPlaces = $allEventToPlaceMap[$eventQ];
                }

                // save info per event
                $eventArray = [
                    'kid' => $eventQ,
                    'title' => $eventLabel,
                    'description' => $eventDesc,
                    'startYear' => $eventStartYear,
                    'endYear' => $eventEndYear,
                    'type' => $eventType,
                    'role' => $eventRole,
                    'status' => $eventStatus,
                    'place' => $eventPlaces
                ];
                array_push($events, $eventArray);
            }
        }
    }


    // dont do timeline stuff if there are less than 3 events
    if (count($events) < 3){
        return json_encode($htmlArray);
    }

    // print_r($events);die;

    $timeline_event_dates = [];
    $unknownEvents = [];    // events without dates

    foreach ($events as $event) {
        // If there are months and days, put the year into decimal format
        // Ex: March 6, 1805 = 1805.18
        if (isset($event['startYear']) && $event['startYear'] != ''){
            array_push($timeline_event_dates, $event['startYear']);

        } else {
            array_push($unknownEvents, $event);
        }
    }

    // echo 'here  ';
    // print_r($unknownEvents);
    // echo 'aae  ';
    // print_r($timeline_event_dates);die;

    if (!empty($timeline_event_dates)){
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
    }

    $html = '
    <div class="timelinewrap">
    <section class="fr-section timeline-section">
    <h2 class="section-title">Person Timeline</h2>

    <div class="timeline-info-container">
    <div class="arrow-pointer-bottom"></div>
    <div class="arrow-pointer-top"></div>';

    $html .= '<div class="info-header">';
    $timeline_event_dates = array_unique($timeline_event_dates);
    foreach ($timeline_event_dates as $year) {
        $yearUniquePlaces = array(); // all of the places for this year

        // set the event info select buttons
        foreach ($events as $event) {
            if (isset($event['startYear']) && $event['startYear'] == $year) {
                $kid = $event['kid'];

                $html .= '
                    <div
                    class="info-select info-select-event"
                    data-select="event"
                    data-year="'.$year.'"
                    data-kid="'.$kid.'"
                    >
                    <p>Event</p>
                    <p class="large-text">'.$event['type'].'</p>
                    </div>';


                //get all unique places for this year
                $eventPlace = $event['place'];
                if (is_array($eventPlace)){
                    $yearUniquePlaces[$eventPlace['name']] = $eventPlace['placeQ'];
                }
            }
        }

        // set the place info select buttons for this year
        // foreach ($yearUniquePlaces as $placeName => $placeQ) {
        //         $html .= '
        //             <div
        //             class="info-select info-select-place"
        //             data-select="place"
        //             data-year="'.$year.'"
        //             data-placeqid="'.$placeQ.'"
        //             data-eventkid="'.$kid.'"
        //             >
        //             <p>Place</p>
        //             <p class="large-text">'.$placeName.'</p>
        //             </div>';
        // }
    }

    foreach ($events as $index => $event) {
        if ($event['startYear'] == '') {
            unset($events[$index]);
        }
    }

    $unknownPlaces = array();
    foreach ($unknownEvents as $event) {
        $kid = $event['kid'];
        $typeText = "Event";

        $html .= '
            <div
            class="info-select info-select-event"
            data-select="event"
            data-kid="'.$kid.'"
            >';

        if ($event['type'] != ''){
            $html .= '
                <p>Event</p>
                <p class="large-text">'.$event['type'].'</p>
            ';
        } else {
            $html .= '
                <p class="large-text">Event</p>
            ';
        }

        $html .= '</div>';


        //get all unique places for this year
        $eventPlace = $event['place'];
        if (is_array($eventPlace)){
            $unknownPlaces[$eventPlace['name']] = $eventPlace['placeQ'];
        }
    }

    // set the place info select buttons for this year
    // foreach ($unknownPlaces as $placeName => $placeQ) {
    //     $html .= '
    //         <div
    //         class="info-select info-select-place"
    //         data-select="place"
    //         data-placeqid="'.$placeQ.'"
    //         data-eventkid="'.$kid.'"
    //         >
    //         <p>Place</p>
    //         <p class="large-text">'.$placeName.'</p>
    //         </div>';
    // }



    $html .= '</div>';

    // put the events in order to be displayed
    $dates = array_column($events, 'startYear');
    array_multisort($dates, SORT_ASC, $events);
    $events = array_merge($events, $unknownEvents);

    $activeSet = false; // set the first event with a date as active

    // print_r($events);die;
    foreach($events as $index => $event) {
        $eventQ = $event['kid'];

        if (!$activeSet && isset($event['startYear']) && $event['startYear'] != ''){
            $html .= '<div class="event-info-'.$eventQ.' infowrap active">';
            $activeSet = true;
        } else {
            $html .= '<div class="event-info-'.$eventQ.' infowrap">';
        }


        // title html
        $titleHtml = "";
        if (isset($event['title']) && $event['title'] != ''){
            $titleHtml = "<p class='large-text'>".$event['title']."</p>";
        }
        // date html
        $dateHtml = "";
        if (isset($event['startYear']) && $event['startYear'] != ''){
            if (isset($event['endYear']) && $event['endYear'] != ''){
                $dateHtml = "
                    <p><span class='bold'>Start Date: </span>".$event['startYear']."</p>
                    <p><span class='bold'>End Date: </span>".$event['endYear']."</p>";
            } else {
                $dateHtml = "<p><span class='bold'>Date: </span>".$event['startYear']."</p>";
            }
        }
        // event type html
        $eventTypeHtml = "";
        if (isset($event['type']) && $event['type'] != ''){
            $eventTypeHtml = "<p><span class='bold'>Event Type: </span>".$event['type']."</p>";
        }
        // event description html
        $eventDescHtml = "";
        if (isset($event['description']) && $event['description'] != ''){
            $eventDescHtml = "<p><span class='bold'>Description: </span>".$event['description']."</p>";
        }
        // event role html
        $eventRoleHtml = "";
        if (isset($event['role']) && $event['role'] != ''){
            $eventRoleHtml = "<p><span class='bold'>Role: </span>".$event['role']."</p>";
        }
        // event status html
        $eventStatusHtml = "";
        if (isset($event['status']) && $event['status'] != ''){
            $eventStatusHtml = "<p><span class='bold'>Status: </span>".$event['status']."</p>";
        }
        // event place html
        $eventPlaceHtml = "";
        $placeTypeHtml = "";
        if (isset($allEventToPlaceMap[$eventQ]) && $allEventToPlaceMap[$eventQ]['name'] != ''){
            $placeName = $allEventToPlaceMap[$eventQ]['name'];
            $placeQ = $allEventToPlaceMap[$eventQ]['placeQ'];
            $placeUrl = BASE_URL . 'record/place/' . $placeQ;
            $eventPlaceHtml = "<p><span class='bold'>Place: </span><a id='place-associator' target='_blank' href='$placeUrl' data-placeqid='$placeQ'>".$placeName."</a></p>";

            if (isset($allEventToPlaceMap[$eventQ]['placeType'])){
                $placeType = $allEventToPlaceMap[$eventQ]['placeType'];
                $placeTypeHtml = "<p><span class='bold'>Place Type: </span>".$placeType."</p>";
            }
        }

        $html .= '<div class="info-column">';
        $html .= "
                $titleHtml
                $dateHtml
                $eventTypeHtml
                $eventDescHtml";
        $html .= '
            </div><div class="info-column">
            '.$eventRoleHtml.'
            '.$eventStatusHtml.'
            '.$eventPlaceHtml.'
            '.$placeTypeHtml.'
            </div>
        </div>';
    }

    $html .= '</div>';

    $html .= '<div class="timeline-container">';

    $timelineIndex = 0;

    if (!empty($timeline_event_dates)){
        $html .= '<div class="timeline">
          <div class="line"></div>
          <div class="hash-container" data-start="'.$first_date_hash.'" data-end="'.$final_date_hash.'">';


        foreach ($hashes as $index => $year) {
            $html .= '<div class="hash" style="left:calc('.($index / ($hash_count - 1)) * 100 .'% - 14px)"><p>'.$year.'</p></div>';
        }

        $html .= '
            </div>
            <div class="points-container">';

            $yearsFound = array();  // make sure no duplicate years
            foreach ($events as $index => $event) {
                if (in_array($event['startYear'], $yearsFound) || $event['startYear'] == ''){
                    continue;
                }
                $yearsFound[] = $event['startYear'];
                // Convert year, month, day into decimal form
                $left = ($event['startYear'] - $first_date_hash) * 100 / $hash_range;

                $html .= '
                <div class="event-point no-select '.($index == 0 ? 'active' : '').'"
                style="left:calc('.$left.'% - 5px)"
                data-kid="'.$event['kid'].'"
                data-year="'.$event['startYear'].'"
                data-index="'.$index.'">
                <span class="event-title">'.$event['title'].' - '.$event['startYear'].'</span>
                </div>';

                $timelineIndex++;
          }

        $html .= '</div>
                </div>';
    }

    // events with unknown dates todo
    $html .= '
        <div class="timeline dates-unknown">
            <div class="line"></div>
            <div class="points-container">';

    foreach ($unknownEvents as $event) {
        $kid = $event['kid'];

        $html .= '
            <div
                class="event-point no-select '.($timelineIndex == 0 ? 'active' : '').'"
                data-index="'.$timelineIndex.'"
                data-kid="'.$kid.'"
            >
                <span class="event-title">Unknown Events</span>
            </div>';
        $timelineIndex++;
    }

    $html .= '
    </div></div>
    <div class="timeline-controls">
      <div class="timeline-prev no-select"><img src="'.BASE_URL.'assets/images/chevron.svg" alt="Previous Arrow"></div>
      <div class="timeline-next no-select"><img src="'.BASE_URL.'assets/images/chevron.svg" alt="Next Arrow"></div>
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
} else if ($recordform == 'place') {
    return getPlacePageConnections($QID);
  } else {
    return '';
  }
}



// connections for the person full record page
function getPersonPageConnections($QID) {
    include BASE_LIB_PATH."variableIncluder.php";
    $connections = array();

    $personQuery['query'] = "
SELECT DISTINCT ?relationslabel ?people ?peoplename(SHA512(CONCAT(STR(?people), STR(RAND()))) as ?random)

 WHERE
{
 VALUES ?agent { $wd:$QID} #Q number needs to be changed for every person.
 	?agent $p:$hasInterAgentRelationship ?staterel .
	?staterel $ps:$hasInterAgentRelationship ?relations .
  	?relations $rdfs:label ?relationslabel.
	?staterel $pq:$isRelationshipTo ?people.
  	?people $rdfs:label ?peoplename.

  SERVICE wikibase:label { bd:serviceParam wikibase:language \"[AUTO_LANGUAGE]\". }
}ORDER BY ?random";

    $result = blazegraphSearch($personQuery);
    $connections['Person-count'] = count($result);
    $connections['Person'] = array_slice($result, 0, 8);  // return the first 8 results

    // places connected to a person
    $placeQuery['query'] = <<<QUERY
SELECT DISTINCT ?place ?placelabel (SHA512(CONCAT(STR(?place), STR(RAND()))) as ?random)

 WHERE
{
 VALUES ?agent { $wd:$QID} #Q number needs to be changed for every person.

    OPTIONAL {
      ?agent $p:$hasParticipantRole ?statementrole.
      ?statementrole $ps:$hasParticipantRole ?roles.
      ?statementrole $pq:$roleProvidedBy ?roleevent.

 		  ?roleevent $wdt:$atPlace ?place.
		  ?place $rdfs:label ?placelabel.
    }.

  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}ORDER BY ?random
QUERY;

    $result = blazegraphSearch($placeQuery);
    $connections['Place-count'] = count($result);
    $connections['Place'] = array_slice($result, 0, 8);  // return the first 8 results



  $closeMatchQuery['query'] = <<<QUERY
SELECT DISTINCT ?match ?matchlabel (SHA512(CONCAT(STR(?match), STR(RAND()))) as ?random)

 WHERE
{
 VALUES ?agent { $wd:$QID} #Q number needs to be changed for every person.
 	?agent $wdt:$closeMatch ?match.
    ?match $rdfs:label ?matchlabel

  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}ORDER BY ?random
QUERY;

    $result = blazegraphSearch($closeMatchQuery);
    $connections['CloseMatch-count'] = count($result);
    $connections['CloseMatch'] = array_slice($result, 0, 8);  // return the first 8 results

    //events connected to a person
    $eventQuery['query'] = <<<QUERY
SELECT DISTINCT ?event ?eventlabel (SHA512(CONCAT(STR(?event), STR(RAND()))) as ?random)

 WHERE
{
 VALUES ?agent { $wd:$QID} #Q number needs to be changed for every source.
  ?agent $p:$hasName ?statement.
  ?statement $ps:$hasName ?name.
  OPTIONAL{ ?statement $pq:$recordedAt ?recordeAt.
            bind(?recordedAt as ?event)}
  OPTIONAL {?agent $p:$hasParticipantRole ?statementrole.
           ?statementrole $ps:$hasParticipantRole ?roles.
           ?statementrole $pq:$roleProvidedBy ?roleevent.
           bind(?roleevent as ?event)

         }.

 OPTIONAL {?agent $p:$hasPersonStatus ?statstatus.
           ?statstatus $ps:$hasPersonStatus ?status.
           ?statstatus $pq:$hasStatusGeneratingEvent ?statusevent.
          bind(?statusevent as ?event)}.
  ?event $rdfs:label ?eventlabel.

  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}ORDER BY ?random

QUERY;

    $result = blazegraphSearch($eventQuery);
    $connections['Event-count'] = count($result);
    $connections['Event'] = array_slice($result, 0, 8);  // return the first 8 results

    //sources connected to a person
    $sourceQuery['query'] = <<<QUERY
    SELECT DISTINCT ?source ?sourcelabel (SHA512(CONCAT(STR(?source), STR(RAND()))) as ?random)
     WHERE
    {
     VALUES ?agent { $wd:$QID} #Q number needs to be changed for every person.
      ?agent ?property  ?object .
      ?object prov:wasDerivedFrom ?provenance .
      ?provenance $pr:$isDirectlyBasedOn ?source .
    	?source $rdfs:label ?sourcelabel

      SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
    }ORDER BY ?random

QUERY;

    // print_r($sourceQuery);die;
    $result = blazegraphSearch($sourceQuery);
    $connections['Source-count'] = count($result);
    $connections['Source'] = array_slice($result, 0, 8);  // return the first 8 results


    return json_encode($connections);
}



// connections for the source full record page
function getSourcePageConnections($QID) {
    include BASE_LIB_PATH."variableIncluder.php";
    $connections = array();

  // people connections
  $peopleQuery['query'] = <<<QUERY
SELECT DISTINCT ?people ?peoplename (SHA512(CONCAT(STR(?people), STR(RAND()))) as ?random)

 WHERE
{
 VALUES ?source { $wd:$QID} #Q number needs to be changed for every source.
  ?source $wdt:$instanceOf $wd:$entityWithProvenance.
  ?people $wdt:$instanceOf/$wdt:$subclassOf $wd:$agent; #agent or subclass of agent
  		?property  ?object .
  ?object $prov:wasDerivedFrom ?provenance .
  ?provenance $pr:$isDirectlyBasedOn ?source .
  ?people $rdfs:label ?peoplename

  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}ORDER BY ?random
QUERY;

    // print_r($peopleQuery);die;

    $result = blazegraphSearch($peopleQuery);
    $connections['Person-count'] = count($result);
    $connections['Person'] = array_slice($result, 0, 8);  // return the first 8 results

  // events connections
  $eventsQuery['query'] = <<<QUERY
SELECT DISTINCT ?event ?eventlabel ?source (SHA512(CONCAT(STR(?event), STR(RAND()))) as ?random)

 WHERE
{
 VALUES ?source { $wd:$QID} #Q number needs to be changed for every source.
  ?source $wdt:$reportsOn ?event.
  ?event $rdfs:label ?eventlabel

  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}ORDER BY ?random
QUERY;

    // print_r($eventsQuery);die;
    $result = blazegraphSearch($eventsQuery);
    $connections['Event-count'] = count($result);
    $connections['Event'] = array_slice($result, 0, 8);  // return the first 8 results

  // place connections
  $placeQuery['query'] = <<<QUERY
SELECT DISTINCT ?place ?placelabel (SHA512(CONCAT(STR(?place), STR(RAND()))) as ?random)

 WHERE
{
 VALUES ?source { $wd:$QID} #Q number needs to be changed for every source.
  ?source $wdt:$reportsOn ?event.
  ?event $wdt:$atPlace ?place.
  ?place $rdfs:label ?placelabel

  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}ORDER BY ?random
QUERY;
// print_r($placeQuery);die;

    $result = blazegraphSearch($placeQuery);
    $connections['Place-count'] = count($result);
    $connections['Place'] = array_slice($result, 0, 8);  // return the first 8 results

    return json_encode($connections);
}


// connections for the event full record page
function getEventPageConnections($QID) {
    include BASE_LIB_PATH."variableIncluder.php";
    $connections = array();

  // people connections
  $peopleQuery['query'] = <<<QUERY
SELECT DISTINCT ?people ?peoplename (SHA512(CONCAT(STR(?people), STR(RAND()))) as ?random)

 WHERE
{
 VALUES ?event { $wd:$QID} #Q number needs to be changed for every event.
  ?event $wdt:$instanceOf $wd:$event.
  ?event $p:$providesParticipantRole ?statement.
  ?statement $ps:$providesParticipantRole ?name.
  ?statement $pq:$hasParticipantRole ?people.
  ?people $rdfs:label ?peoplename.
  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}ORDER BY ?random
QUERY;

    $result = blazegraphSearch($peopleQuery);
    $connections['Person-count'] = count($result);
    $connections['Person'] = array_slice($result, 0, 8);  // return the first 8 results

  // project connections
  $projectQuery['query'] = <<<QUERY
SELECT DISTINCT ?source ?refName ?project ?projectName (SHA512(CONCAT(STR(?source), STR(RAND()))) as ?random)

 WHERE
{
VALUES ?event { $wd:$QID} #Q number needs to be changed for every event.
  ?event $wdt:$instanceOf $wd:$event;
  		?property  ?object .
  ?object $prov:wasDerivedFrom ?provenance .
  ?provenance $pr:$isDirectlyBasedOn ?source .
  ?source $rdfs:label ?refName;
          $wdt:$generatedBy ?project.
  ?project $rdfs:label ?projectName.
  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}ORDER BY ?random
QUERY;

    $result = blazegraphSearch($projectQuery);
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
 VALUES ?event { $wd:$QID} #Q number needs to be changed for every event.
  ?event $wdt:$atPlace ?place.
  ?place $rdfs:label ?placelabel

  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}ORDER BY ?random
QUERY;

    $result = blazegraphSearch($placesQuery);
    $connections['Place-count'] = count($result);
    $connections['Place'] = array_slice($result, 0, 8);  // return the first 8 results

    // source connections
  $sourceQuery['query'] = <<<QUERY
SELECT DISTINCT ?source ?sourcelabel (SHA512(CONCAT(STR(?source), STR(RAND()))) as ?random)

 WHERE
{
 VALUES ?event { $wd:$QID} #Q number needs to be changed for every event.
  ?event $wdt:$instanceOf $wd:$event;
          ?property  ?object .
  	?object $prov:wasDerivedFrom ?provenance .
  	?provenance $pr:$isDirectlyBasedOn ?source .
	?source $rdfs:label ?sourcelabel

  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}ORDER BY ?random
QUERY;
// print_r($sourceQuery);die;
    $result = blazegraphSearch($sourceQuery);
    $connections['Source-count'] = count($result);
    $connections['Source'] = array_slice($result, 0, 8);  // return the first 8 results


    return json_encode($connections);
}



// connections for the place full record page
function getPlacePageConnections($QID) {
    include BASE_LIB_PATH."variableIncluder.php";
    $connections = array();

  // people connections
  $peopleQuery['query'] = <<<QUERY
  SELECT DISTINCT ?people ?peoplename (SHA512(CONCAT(STR(?people), STR(RAND()))) as ?random)
  {
  	VALUES ?place { $wd:$QID }.
    ?event $wdt:$instanceOf $wd:$event.
   	?event $wdt:$atPlace ?place.
    	?event $p:$providesParticipantRole ?role.
    	?role  $ps:$providesParticipantRole ?participant.
    	?role  $pq:$hasParticipantRole ?people.
    	?people $rdfs:label ?peoplename
  }ORDER BY ?random
QUERY;
    $result = blazegraphSearch($peopleQuery);
    $connections['Person-count'] = count($result);
    $connections['Person'] = array_slice($result, 0, 8);  // return the first 8 results

    // events connections
    $eventsQuery['query'] = <<<QUERY
   SELECT DISTINCT ?event ?eventlabel (SHA512(CONCAT(STR(?event), STR(RAND()))) as ?random)

   WHERE
   {
       VALUES ?place { $wd:$QID }.
       ?event $wdt:$instanceOf $wd:$event.
       ?event $wdt:$atPlace ?place.
       ?event $rdfs:label ?eventlabel
   }ORDER BY ?random
   QUERY;
      $result = blazegraphSearch($eventsQuery);
      $connections['Event-count'] = count($result);
      $connections['Event'] = array_slice($result, 0, 8);  // return the first 8 results

      // source connections
    $sourceQuery['query'] = <<<QUERY
    SELECT DISTINCT ?source ?sourcelabel (SHA512(CONCAT(STR(?source), STR(RAND()))) as ?random) {
    	VALUES ?place { $wd:$QID }.
        ?place $p:$instanceOf  ?object .
     	 ?object $prov:wasDerivedFrom ?provenance .
      	 ?provenance $pr:$isDirectlyBasedOn ?source.
      ?source $rdfs:label ?sourcelabel
    }ORDER BY ?random
  QUERY;
  // print_r($sourceQuery);die;
      $result = blazegraphSearch($sourceQuery);
      $connections['Source-count'] = count($result);
      $connections['Source'] = array_slice($result, 0, 8);  // return the first 8 results


    return json_encode($connections);
}




function debugfunc($debugobject){?>
    <pre><?php echo var_dump($debugobject);?></pre>
<?php }
?>
