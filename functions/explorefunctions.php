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

function createDetailHtml($statement,$label,$link=''){
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
      $pqurl = $baseurl . 'record/event/' . $pqid;
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
    $html .= '</div><br>';

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
    $html .= '</div><br>';
} else if ($label == "matches"){
    $lowerlabel = "match";
    $upperlabel = "Match";

    $matchUrls = explode('||', $statement['matchUrls']);
    $matchLabels = explode('||', $statement['matchLabels']);
      $matchtype = explode('||', $statement['matchtype']);

    if (end($matchUrls) == '' || end($matchUrls) == ' '){
      array_pop($matchUrls);
    }
    if (end($matchLabels) == '' || end($matchLabels) == ' '){
      array_pop($matchLabels);
    }
    if (end($matchtype) == '' || end($matchtype) == ' '){
      array_pop($matchtype);
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
    <a href='$matchUrl' class='highlight'>$matchtype[$i]$matchLabels[$i]</a>
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
    foreach($projectNames as $projectName){
            $html .= <<<HTML
    <div class="detail-bottom">
        <p>$projectName</p>
    HTML;
    }
    $html .= '</div></div>';
} else if ($label == "ecvoA"){
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
}else if ($label == "AgeA"){
    // match statuses with events
    $lowerlabel = "age";
    $upperlabel = "Age";

    //Array for ststueses means there are events and labels match
    $statuses = explode('||', $statement['ages']);
    $statusEventUrls = explode('||', $statement['ageEvents']);
    $eventstatusLabels = explode('||', $statement['agestatusLabels']);

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
    } elseif($label == "Occupation")
    {
        $statementArr = explode('||', $statement);
      if (end($statementArr) == '' || end($statementArr) == ' '){
        array_pop($statementArr);
      }
    }elseif($label == "Descriptive Occupation")
    {
        $statementArr = explode('||', $statement);
        $upperlabel = 'Other Information';
      if (end($statementArr) == '' || end($statementArr) == ' '){
        array_pop($statementArr);
      }

    }
    else{
      //Splits the statement(detail) up into multiple parts for multiple details, also trims whitespace off end
      $statementArr = explode('||', $statement);
      if (end($statementArr) == '' || end($statementArr) == ' '){
        array_pop($statementArr);
      }
      if($label === "Located In"){
        $statementArr = $statement;
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
          $html .= '<a target="_blank" href="http://www.geonames.org/' . $statementArr[0] . '/">';
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
            $html .= '<a href="' . $baseurl . 'record/place/' . $locationQ . '">' . $statementArr[$x] . "</a>";
            continue;
          }
        }

        $detailname = $statementArr[$x];
        if($label == 'Located In'){
          $html .= "<div><a href='" . BASE_URL . "record/place/" . $link[$x] . "'>" . $detailname . "</a></div><br>";
          continue;
        }
        else{

          $html .= "<div>" . $detailname;
        }
        if($label == 'Geoname Identifier'){
          $html .= "<div><a></a></div><br>";
        }
        if(array_key_exists($detailname,controlledVocabulary)){
          $detailinfo = ucfirst(controlledVocabulary[$detailname]);
          $html .= "<div class='detail-menu'> <h1>$detailname</h1> <p>$detailinfo</p> </div>";
        }
        $html .= "</div></a>";

        if( ($label=="Descriptive Occupation"||$label=="Coordinates") && $x != (count($statementArr) - 1 )){
            $html.= "<br>";
        }
        else if ($x != (count($statementArr) - 1)){
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
    // echo ($query['query']);die;
    $result = blazegraphSearch($query);

    if(empty($result)){
      echo json_encode(Array());
      die;
    }
    $record = $result[0];
    // var_dump($record);die;
    //Get variables from query
    $recordVars = [];

    $recordVars['Label'] = $record['label']['value'];

    //Name
    if (isset($record['name']) && isset($record['name']['value']) ){
      $recordVars['Name'] = $record['name']['value'];
    }
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
    //AGE
    if (isset($record['age']) && isset($record['age']['value']) && $record['age']['value'] != ''){
      if(isset($record['agerecordedat']) && isset($record['agerecordedat']['value']) &&
         isset($record['agerecordedatlabel']) && isset($record['agerecordedatlabel']['value']) &&
         $record['agerecordedatlabel']['value'] != '' && $record['agerecordedat']['value'] ){
        if (empty($record['age']['value'])) {
          $recordVars['AgeA'] = [];
        } else {
          $ageArr = ['ages' => $record['age']['value'],
                        'ageEvents' => $record['agerecordedat']['value'],
                        'agestatusLabels' => $record['agerecordedatlabel']['value']
                      ];
          $recordVars['AgeA'] = $ageArr;
        }
      }
      else{
        $recordVars['Age'] = $record['age']['value'];
      }
    }
    //occupation

    if (isset($record['occupation']) && isset($record['occupation']['value']) && $record['occupation']['value'] != '' ){
      $recordVars['Occupation'] = $record['occupation']['value'];
    }
    // descriptive occupation
    if (isset($record['descriptive_Occupation']) && isset($record['descriptive_Occupation']['value']) ){
        $recordVars['Descriptive Occupation'] = $record['descriptive_Occupation']['value'];
    }

    //Race
    if (isset($record['race']) && isset($record['race']['value']) && $record['race']['value'] != '' ){
      $recordVars['Race'] = $record['race']['value'];
    }

    // descriptions for items
    if (isset($record['description']) && isset($record['description']['value']) ){
        $s = preg_replace('/(?<!href="|">)(?<!src=\")((http|ftp)+(s)?:\/\/[^<>\s]+)/is', '<a href="\\1" target="_blank">\\1</a>', $record['description']['value']);
        $recordVars['Description'] = $s;
    }

    // descriptions for items

    if (isset($record['extref']) && isset($record['extref']['value']) && $record['extref']['value']!='' ){
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
        $recordVars['Ethnolisguistic Descriptor'] = $record['ecvo']['value'];
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

    //available from
    if (isset($record['availableFrom']) && isset($record['availableFrom']['value']) && $record['availableFrom']['value'] != '' ){
      $recordVars['Available From'] = $record['availableFrom']['value'];
    }

    //Geonames
    if (isset($record['geonames']) && isset($record['geonames']['value']) && $record['geonames']['value'] != '' ){
      $recordVars['Geoname Identifier'] = $record['geonames']['value'];
    }

    //Code
    if (isset($record['code']) && isset($record['code']['value']) && $record['code']['value'] != ''){
      $recordVars['Modern Country Code'] = $record['code']['value'];
    }

    //Coordinates
    if (isset($record['coordinates']) && isset($record['coordinates']['value']) && $record['coordinates']['value'] != ''){
        $coors = explode('||',$record['coordinates']['value']);
        foreach($coors as $i => $cor){
            $cor = explode(' ', $cor);
            $lat = $cor[1];
            $lat = substr($lat,0,-1);
            $long = $cor[0];
            $long = substr($long,6);
            $coors[$i] = "Point($lat $long)";
        }
        $coors = implode('||',$coors);
      $recordVars['Coordinates'] = $coors;
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

    //CloseMatch
    if (isset($record['match']) && isset($record['match']['value']) && $record['match']['value'] != ''  ){
      if(isset($record['matchlabel']) && isset($record['matchlabel']['value']) &&
         $record['matchlabel']['value'] != '' ){
        $closeMatchArr = ['matchLabels' => $record['matchlabel']['value'],
                           'matchUrls' => $record['match']['value'],
                           'matchtype' => $record['matchtype']['value']
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
      $locatedIn = [];
      foreach($result as $res){
        if (strpos($res['locatedIn']['value'], '||') !== false) {
          $locatedIn = array_merge($locatedIn, explode('||', $res['locatedIn']['value']));
        } else
          array_push($locatedIn, $res['locatedIn']['value']);
      }
      $locatedIn = array_unique($locatedIn);
      $recordVars['Located In'] = $locatedIn;
    }

    if (isset($record['locIn']) && isset($record['locIn']['value'])  && $record['locIn']['value'] != '' ){
      $locIn = [];
      foreach($result as $res){
        if (strpos($res['locIn']['value'], '||') !== false) {
          $locIn = array_merge($locIn, explode('||', $res['locIn']['value']));
        } else
          array_push($locIn, $res['locIn']['value']);
      }
      $recordVars['Loc In'] = $locIn;
    }

    //Sex
    if (isset($record['sextype']) && isset($record['sextype']['value']) && $record['sextype']['value'] != '' ){
      $recordVars['Sex'] = $record['sextype']['value'];
    }

    //Occurs Before
    if (isset($record['occursbefore']) && isset($record['occursbefore']['value']) && $record['occursbefore']['value'] != '' ){
      $recordVars['Occurs Before'] = $record['occursbefore']['value'];
    }

    //Occurs Before
    if (isset($record['occursafter']) && isset($record['occursafter']['value']) && $record['occursafter']['value'] != '' ){
      $recordVars['Occurs After'] = $record['occursafter']['value'];
    }

    //Circa
    if (isset($record['circa']) && isset($record['circa']['value']) && $record['circa']['value'] != '' ){
      $recordVars['Circa'] = $record['circa']['value'];
    }

    //Roles for events
    //Gets the roles, participants, and pqID if they exist and matches them together
    if (isset($record['roles']) && isset($record['roles']['value']) &&  $record['roles']['value'] != ''){
      if(isset($record['roleevent']) && isset($record['roleevent']['value']) &&
         $record['roleevent']['value'] != '' &&  $record['roleeventlabel']['value'] != '' ){
        //There are participants to match with their roles and qIDs
        $rolesArr = ['roles' => $record['roles']['value'],
                     'participant' => $record['roleeventlabel']['value'],
                     'pq' => $record['roleevent']['value']
                    ];
        $recordVars['RolesA'] = $rolesArr;
      }
    } else if(isset($record['roleevent']) && isset($record['roleevent']['value'])){
        if(isset($record['roleeventlabel']) && isset($record['roleeventlabel']['value']) &&
            $record['roleeventlabel']['value'] != '' && $record['roleevent']['value'] != '' ){
          //There are participants to match with their roles and qIDs
          $rolesArr = ['roles' => $record['roles']['value'],
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

    $label= $recordVars['Label'];
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

    foreach($recordVars as $key => $value){
      if($key == "Label") continue;

      if($key == "Located In"){
        $Qid = [];
        foreach($recordVars['Loc In'] as $Q){
          $urlQ = explode("/", $Q);
          $urlQ = end($urlQ);
          array_push($Qid, $urlQ);
        }

        $html .= createDetailHtml($value, $key, $Qid);
      }
      else if($key == "Loc In"){
        continue;
      }
      else if($key == "Modern Country Code"){
        continue;
      }
      else{
        $html .= createDetailHtml($value, $key);
      }
    }
    $html .= '</div>';

    $htmlArray['details'] = $html;

    //Timeline section

    //Timeline
    // Code for creating events on Timeline
    // Replace with Kora 3 events
    $events = [];

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
    $html .= '</div>';

    // put the events in order to be displayed
    $dates = array_column($events, 'startYear');
    array_multisort($dates, SORT_ASC, $events);
    $events = array_merge($events, $unknownEvents);

    $activeSet = false; // set the first event with a date as active

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
            $titleHtml = "<p class='large-text'>".$event['f']."</p>";
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

    $personQuery['query'] = <<<QUERY
SELECT DISTINCT ?relationslabel ?people ?peoplename(SHA512(CONCAT(STR(?people), STR(RAND()))) as ?random)

 WHERE
{
 VALUES ?agent { $wd:$QID}.
 	?agent $p:$hasInterAgentRelationship ?staterel .
	?staterel $ps:$hasInterAgentRelationship ?relations .
  	?relations $rdfs:label ?relationslabel.
	?staterel $pq:$isRelationshipTo ?people.
  	?people $wdt:$hasName ?peoplename.
  }ORDER BY ?random
QUERY;
    $result = blazegraphSearch($personQuery);
    $connections['Person-count'] = count($result);
    $connections['Person'] = array_slice($result, 0, 8);  // return the first 8 results
    // places connected to a person
    $placeQuery['query'] = <<<QUERY
SELECT DISTINCT ?place ?placelabel

 WHERE
{
 VALUES ?agent { $wd:$QID}.

  {
  OPTIONAL {
    ?agent $p:$hasName ?statement.
    ?statement $ps:$hasName ?name.
    ?statement $pq:$recordedAt ?event.
    ?event  $wdt:$atPlace ?place.
    ?place $wdt:$hasName ?placelabel.
   }
 }
 UNION {
    OPTIONAL {
      ?agent $p:$hasParticipantRole ?statementrole.
      ?statementrole $ps:$hasParticipantRole ?roles.
      ?statementrole $pq:$roleProvidedBy ?roleevent.
 	  ?roleevent $wdt:$atPlace ?place.
        ?place $wdt:$hasName ?placelabel.
    }.
  }

}LIMIT 8
QUERY;
    $result = blazegraphSearch($placeQuery);
      if (empty($result[0])) $result=array();
    $connections['Place-count'] = count($result);
    $connections['Place'] = array_slice($result, 0, 8);  // return the first 8 results



  $closeMatchQuery['query'] = <<<QUERY
SELECT DISTINCT ?match ?matchlabel ?matchtype (SHA512(CONCAT(STR(?match), STR(RAND()))) as ?random)

 WHERE
{
 VALUES ?agent { $wd:$QID}. #Q number needs to be changed for every person.
 ?agent $p:$hasMatch ?statementname.
  ?statementname $ps:$hasMatch ?matcht.
  ?matcht $rdfs:label ?matchtype.
  ?statementname $pq:$matches ?match.
  ?match $wdt:$hasName ?matchlabel

}ORDER BY ?random
QUERY;

    $result = blazegraphSearch($closeMatchQuery);
    $connections['CloseMatch-count'] = count($result);
    $connections['CloseMatch'] = array_slice($result, 0, 8);  // return the first 8 results
  //print_r($result);
    //events connected to a person
    $eventQuery['query'] = <<<QUERY
SELECT DISTINCT ?event ?eventlabel

 WHERE
{
 VALUES ?agent { $wd:$QID}.
 {
 OPTIONAL {
   ?agent $p:$hasName ?statement.
   ?statement $ps:$hasName ?name.
   ?statement $pq:$recordedAt ?event.
   ?event $wdt:$hasName ?eventlabel.
  }
}
union{
  OPTIONAL {?agent $p:$hasParticipantRole ?statementrole.
           ?statementrole $ps:$hasParticipantRole ?roles.
           ?statementrole $pq:$roleProvidedBy ?event.
             ?event $wdt:$hasName ?eventlabel.}.
}
union{
 OPTIONAL {?agent $p:$hasPersonStatus ?statstatus.
           ?statstatus $ps:$hasPersonStatus ?status.
           ?statstatus $pq:$hasStatusGeneratingEvent ?event.
           ?event $wdt:$hasName ?eventlabel.}.
}
}LIMIT 8

QUERY;
    $result = blazegraphSearch($eventQuery);
    if (empty($result[0])) $result=array();
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
    	?source $wdt:$hasName ?sourcelabel
  }ORDER BY ?random

QUERY;

    $result = blazegraphSearch($sourceQuery);
      //print_r($result);
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
 VALUES ?source { $wd:$QID}
  ?source $wdt:$instanceOf $wd:$entityWithProvenance.
  ?people $wdt:$instanceOf/$wdt:$subclassOf $wd:$agent;
  		?property  ?object .
  ?object $prov:wasDerivedFrom ?provenance .
  ?provenance $pr:$isDirectlyBasedOn ?source .
  ?people $wdt:$hasName ?peoplename
}LIMIT 8
QUERY;
$peoplecounter['query'] = <<<QUERY
SELECT DISTINCT (count(?people) as ?count)
WHERE
{
VALUES ?source { $wd:$QID}
?people $wdt:$instanceOf/$wdt:$subclassOf $wd:$agent;
        $p:$hasName  ?object .
?object $prov:wasDerivedFrom ?provenance .
?provenance $pr:$isDirectlyBasedOn ?source .
}
QUERY;

    $result = blazegraphSearch($peopleQuery);
    $counter= blazegraphSearch($peoplecounter);
    $connections['Person'] = $result;  // return the first 8 results
    $connections['Person-count'] = $counter[0]['count']['value'];
  // events connections
  $eventsQuery['query'] = <<<QUERY
SELECT DISTINCT ?event ?eventlabel ?source (SHA512(CONCAT(STR(?event), STR(RAND()))) as ?random)
 WHERE
{
 VALUES ?source { $wd:$QID}
 ?event $wdt:$instanceOf $wd:$event;
          $p:$hasName  ?object .
 ?object $prov:wasDerivedFrom ?provenance .
 ?provenance $pr:$isDirectlyBasedOn ?source .
   ?event $wdt:$hasName ?eventlabel
}LIMIT 8

QUERY;
$eventscounter['query'] = <<<QUERY
SELECT DISTINCT (count(?event) as ?count)
WHERE
{
VALUES ?source { $wd:$QID}
?event $wdt:$instanceOf $wd:$event;
        $p:$hasName  ?object .
?object $prov:wasDerivedFrom ?provenance .
?provenance $pr:$isDirectlyBasedOn ?source .

}
QUERY;
    $result = blazegraphSearch($eventsQuery);
    $counter= blazegraphSearch($eventscounter);
    $connections['Event-count'] = $counter[0]['count']['value'];
    $connections['Event']=$result;
  // place connections
  $placeQuery['query'] = <<<QUERY
SELECT DISTINCT ?place ?placelabel (SHA512(CONCAT(STR(?place), STR(RAND()))) as ?random)

 WHERE
{
 VALUES ?source { $wd:$QID} #Q number needs to be changed for every source.
 ?place $wdt:$instanceOf $wd:$place;
          $p:$hasName  ?object .
 ?object $prov:wasDerivedFrom ?provenance .
 ?provenance $pr:$isDirectlyBasedOn ?source .
  ?place $wdt:$hasName ?placelabel
}
QUERY;

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
SELECT DISTINCT ?people ?peoplename ?role (SHA512(CONCAT(STR(?people), STR(RAND()))) as ?random)

 WHERE
{
 VALUES ?event { $wd:$QID} #Q number needs to be changed for every event.
  ?event $wdt:$instanceOf $wd:$event.
  ?event $p:$providesParticipantRole ?statement.
  ?statement $ps:$providesParticipantRole ?name.
  ?statement $pq:$hasParticipantRole ?people.
  ?people $wdt:$hasName ?peoplename.
  ?name $rdfs:label ?role.
  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}ORDER BY ?random
QUERY;

    $result = blazegraphSearch($peopleQuery);
    $connections['Person-count'] = count($result);
    $connections['Person'] = array_slice($result, 0, 8);  // return the first 8 results


    // places connections
  $placesQuery['query'] = <<<QUERY
SELECT DISTINCT ?place ?placelabel (SHA512(CONCAT(STR(?place), STR(RAND()))) as ?random)

 WHERE
{
 VALUES ?event { $wd:$QID} #Q number needs to be changed for every event.
  ?event $wdt:$atPlace ?place.
  ?place $wdt:$hasName ?placelabel

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
	?source $wdt:$hasName ?sourcelabel

  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}ORDER BY ?random
QUERY;

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
  $peoplecounter['query'] = <<<QUERY
   SELECT DISTINCT (COUNT(?people) as ?counter)
   {
   	VALUES ?place { $wd:$QID }.
     ?event $wdt:$instanceOf $wd:$event.
    	?event $wdt:$atPlace ?place.
     	?event $p:$providesParticipantRole ?role.
     	?role  $ps:$providesParticipantRole ?participant.
     	?role  $pq:$hasParticipantRole ?people.
     	?people $wdt:$hasName ?peoplename
   }
  QUERY;
 $peopleQuery['query'] = <<<QUERY
  SELECT DISTINCT ?people ?peoplename
  {
  	VALUES ?place { $wd:$QID }.
    ?event $wdt:$instanceOf $wd:$event.
   	?event $wdt:$atPlace ?place.
    	?event $p:$providesParticipantRole ?role.
    	?role  $ps:$providesParticipantRole ?participant.
    	?role  $pq:$hasParticipantRole ?people.
    	?people $wdt:$hasName ?peoplename
  }LIMIT 8
QUERY;

   $result = blazegraphSearch($peopleQuery);
   $result_counter = blazegraphSearch($peoplecounter);

    $connections['Person-count'] = $result_counter[0]['counter']['value'];
    $connections['Person'] = $result;  // return the first 8 results

   // events connections
   $eventsCounter['query'] = <<<QUERY
  SELECT DISTINCT (COUNT(?event) as ?counter)

  WHERE
  {
      VALUES ?place { $wd:$QID }.
      ?event $wdt:$instanceOf $wd:$event.
      ?event $wdt:$atPlace ?place.
      ?event $wdt:$hasName ?eventlabel
  }ORDER BY ?random
  QUERY;
    $eventsQuery['query'] = <<<QUERY
   SELECT DISTINCT ?event ?eventlabel

   WHERE
   {
       VALUES ?place { $wd:$QID }.
       ?event $wdt:$instanceOf $wd:$event.
       ?event $wdt:$atPlace ?place.
       ?event $wdt:$hasName ?eventlabel
   }LIMIT 8
   QUERY;
      $result = blazegraphSearch($eventsQuery);
      $result_counter = blazegraphSearch($eventsCounter);
      $connections['Event-count'] = $result_counter[0]['counter']['value'];
      $connections['Event'] =$result;  // return the first 8 results

      // source connections
    $sourceQuery['query'] = <<<QUERY
    SELECT DISTINCT ?source ?sourcelabel (SHA512(CONCAT(STR(?source), STR(RAND()))) as ?random) {
    	VALUES ?place { $wd:$QID }.
        ?place $p:$hasName  ?object .
     	 ?object $prov:wasDerivedFrom ?provenance .
      	 ?provenance $pr:$isDirectlyBasedOn ?source.
      ?source $wdt:$hasName ?sourcelabel
    }ORDER BY ?random
  QUERY;
      $result = blazegraphSearch($sourceQuery);
      $connections['Source-count'] = count($result);
      $connections['Source'] = array_slice($result, 0, 8);  // return the first 8 results

      // place connections
      //placebucket query add a new tab
   $relatedPlaces['query'] = <<<QUERY
    SELECT DISTINCT ?relatedPlace ?otherp (SHA512(CONCAT(STR(?place), STR(RAND()))) as ?random)
    WHERE {
      VALUES ?place { $wd:$QID}.
      ?place $wdt:$hasBroader ?bucket.
      ?otherp $wdt:$hasBroader ?bucket.
         FILTER (?otherp != ?place).
      ?place $wdt:$hasName ?placeLabel.
      ?otherp $wdt:$hasName ?placels.
      ?otherp $wdt:$hasPlaceType ?ptypes.
      ?ptypes $wdt:$hasName ?types.
        BIND(CONCAT(?placels," - " )  AS ?placelabels ) .
        BIND(CONCAT(?types," - " )  AS ?placeTypes ) .
        BIND(CONCAT(?placelabels,?types )  AS ?relatedPlace ) .
    }ORDER BY ?random
  QUERY;
  $result = blazegraphSearch($relatedPlaces);
  $connections['RelatedPlace-count'] = count($result);
  $connections['RelatedPlace'] = array_slice($result, 0, 8);  // return the first 8 results
/*
  foreach ($result as $key => $value) {
    $result[$key]['place'] = $result[$key]['otherp'];
    unset($result[$key]['otherp']);
    $result[$key]['placelabel'] = $result[$key]['relatedPlaces'];
    unset($result[$key]['relatedPlaces']);
  }*/


  $placeQuery['query'] = <<<QUERY
  SELECT ?place ?placelabel (SHA512(CONCAT(STR(?places), STR(RAND()))) as ?random)
  WHERE{
      	VALUES ?plid { $wd:$QID }.
        ?place $wdt:$locatedIn ?plid.
        ?place $wdt:$hasName ?placelabel.
  }ORDER BY ?random
  QUERY;

  $result = blazegraphSearch($placeQuery);
  $connections['Place-count'] = count($result);
  $connections['Place'] = array_slice($result, 0, 8);  // return the first 8 results
    return json_encode($connections);
}

?>
