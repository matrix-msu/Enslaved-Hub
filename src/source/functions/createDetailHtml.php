<?php

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
        // echo json_encode($statement);die;
      //Multiple roles in the roles array so match them up with the participant
      $lowerlabel = "roles";
      $upperlabel = "Roles";

      $html .= <<<HTML
      <div class="detail $lowerlabel">
        <h3>$upperlabel</h3>
      HTML;

      foreach($statement as $statementArr){

          //Array for Roles means there are participants and pQIDs to match
          $roles = explode('||', $statementArr['roles']);
          $participants = explode('||', $statementArr['participant']);
          $pq = explode('||', $statementArr['pq']);

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

          //Loop through and match up
          $matched = '';
          for($i=0; $i < sizeof($participants); $i++){
              if (!isset($pq[$i]) || !isset($participants[$i]) ){
                  continue;
              }

              $explode = explode('/', $pq[$i]);
              $pqid = end($explode);
              $pqurl = $baseurl . 'record/event/' . $pqid;
              $matched = $roles[$i] . ' - ' . $participants[$i];

              $hiddenStyle = '';
              if($i > 0){
                  $hiddenStyle = ' style="visibility:hidden"';
              }

              $html .= <<<HTML
              <div class="detail-bottom">
                  <div$hiddenStyle>$roles[0]
              HTML;

              // roles tool tip
              if(array_key_exists($roles[0],controlledVocabulary)){
                $detailinfo = ucfirst(controlledVocabulary[$roles[0]]);
                $html .= "<div class='detail-menu' id='tooltip'> <h1>$roles[0]</h1> <p>$detailinfo</p> </div>";
              }

              if($i >= 8){
                  $html .= '</div> &nbsp; <a class="search-all" href="'.BASE_URL.'search/events?person='.$_REQUEST['QID'].'">View All Events</a>';
                  break;
              }

              $html .= "</div> - <a class='highlight' href='$pqurl'>$participants[$i]</a></div>";
          }
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
              $html .= "<div class='detail-menu' id='tooltip'> <h1>$roles[$i]</h1> <p>$detailinfo</p> </div>";
          }

          $html .= "</div> - <a href='$eventUrl' class='highlight'>$eventRoleLabels[$i]</a></div>";
      }
      $html .= '</div><br>';
    } else if($label === "DRolesA"){
      //Multiple roles in the roles array so match them up with the participant
      $lowerlabel = "descriptive roles";
      $upperlabel = "Descriptive Roles";

      $html .= <<<HTML
      <div class="detail $lowerlabel">
          <h3>$upperlabel</h3>
      HTML;

      foreach($statement as $statementArr){

          //Array for Roles means there are participants and pQIDs to match
          $roles = explode('||', $statementArr['droles']);
          $participants = explode('||', $statementArr['dparticipant']);
          $pq = explode('||', $statementArr['dpq']);

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


          //Loop through and match up
          $matched = '';
          for($i=0; $i < sizeof($participants); $i++){
              if (!isset($pq[$i]) || !isset($participants[$i]) ){
                  continue;
              }

              $explode = explode('/', $pq[$i]);
              $pqid = end($explode);
              $pqurl = $baseurl . 'record/event/' . $pqid;
              $matched = $roles[0] . ' - ' . $participants[$i];

              $hiddenStyle = '';
              if($i > 0){
                  $hiddenStyle = ' style="visibility:hidden"';
              }

              $html .= <<<HTML
              <div class="detail-bottom">
                  <div$hiddenStyle>$roles[0]
              HTML;

              // roles tool tip
              if(array_key_exists($roles[0],controlledVocabulary)){
                $detailinfo = ucfirst(controlledVocabulary[$roles[0]]);
                $html .= "<div class='detail-menu' id='tooltip'> <h1>$roles[0]</h1> <p>$detailinfo</p> </div>";
              }

              if($i >= 8){
                  $html .= '</div> &nbsp; <a class="search-all" href="'.BASE_URL.'search/events?person='.$_REQUEST['QID'].'">View All Events</a>';
                  break;
              }

              $html .= "</div> - <a class='highlight' href='$pqurl'>$participants[$i]</a></div>";
          }
      }
      $html .= '</div><br>';

    }else if ($label == "matches"){
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
    } else if ($label == "Project References"){
      $lowerlabel = "project references";
      $upperlabel = "Project References";
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
              $html .= "<div class='detail-menu' id='tooltip'> <h1>$relationships[$i]</h1> <p>$detailinfo</p> </div>";
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
              $html .= "<div class='detail-menu' id='tooltip'> <h1>$ecvos[$i]</h1> <p>$detailinfo</p> </div>";
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
              $html .= "<div class='detail-menu' id='tooltip'> <h1>$statuses[$i]</h1> <p>$detailinfo</p> </div>";
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
              $html .= "<div class='detail-menu' id='tooltip'> <h1>$statuses[$i]</h1> <p>$detailinfo</p> </div>";
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
      } elseif($label == "Age Category")
      {
          $statementArr = explode('||', $statement);
        if (end($statementArr) == '' || end($statementArr) == ' '){
          array_pop($statementArr);
        }
        $upperlabel = "Age Category";
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
        if(is_string($statement)){
            $statementArr = explode('||', $statement);
        }
        if($label === "Located In"){
            $statementArr = $statement;
        }
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
              $html .= "<div class='detail-menu' id='tooltip'> <h1>$detailname</h1> <p>$detailinfo</p> </div>";
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
            $html .= "<div class='detail-menu' id='tooltip'> <h1>$detailname</h1> <p>$detailinfo</p> </div>";
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
