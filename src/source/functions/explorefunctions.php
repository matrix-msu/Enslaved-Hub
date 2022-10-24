<?php
require_once('createDetailHtml.php');

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

function valueNotEmpty($record, $name){
    if (isset($record[$name]) && isset($record[$name]['value']) && $record[$name]['value'] != '' ){
        return true;
    }
    return false;
}

function getFullRecordHtml(){
    include BASE_LIB_PATH."variableIncluder.php";
    $qid = $_REQUEST['QID'];
    $formType = $_REQUEST['type'];

    //QUERY FOR RECORD INFO
    $query = [];
    include BASE_PATH."queries/fullRecord/".$formType.".php";
    $query['query'] = $tempQuery;
    // echo $query['query'];die;
    $result = blazegraphSearch($query);

    if(empty($result)){
      echo json_encode(Array());
      die;
    }
    $record = $result[0];
    $recordVars = [];
	// echo json_encode($record);die;

    $prettyNames = array(
        'name' => ['name','Name'],
        'firstname' => ['standard','First Name'],
        'surname' => ['standard','Surname'],
        'altname' => ['standard','Alternate Name'],
        'sextype' => ['standard','Sex'],
        'age' => ['standardArray','Age','AgeA',['agerecordedat','agerecordedatlabel'],['ages','ageEvents','agestatusLabels']],
        'occupation' => ['standard','Occupation'],
        'ageCategory' => ['standard','Age_Category'],
        'descriptive_Occupation' => ['standard','Descriptive Occupation'],
        'race' => ['standard','Race'],
        'description' => ['description','Description'],
        'status' => ['status','status'],
        'ecvo' => ['standard','Ethnolinguistic Descriptor'],
        'placeOriginlabel' => ['standard','Place of Origin'],
        'raceorcolor' => ['standard','Race or Color'],
        // 'ecvo' => ['standardArray','Ethnolinguistic Descriptor','ecvoA',['placeofOrigin','placeOriginlabel'],['ecvo','placeofOrigin','placeOriginlabel']],
        'date' => ['standard','Date'],
        'eventDates' => ['standard','Date'],
        'eventDescriptions' => ['standard','Description'],
        'dateStart' => ['dateRange','Date Range'],
        'located' => ['standard','Location'],
        'type' => ['standard','Type'],
        'availableFrom' => ['standard','Available From'],
        'geonames' => ['standard','Geoname Identifier'],
        'code' => ['standard','Modern Country Code'],
        'coordinates' => ['coordinates','Coordinates'],
        'sourceLabel' => ['sourceLabel','Sources'],
        'match' => ['standardArray','Match','closeMatchA',['matchlabel','matchtype'],['matchUrls','matchLabels','matchtype']],
        'projectlabel' => ['project','Contributing Projects'],
        'extref' => ['standard','Project References'],
        'projref' => ['projref','Project References'],
        'locatedIn' => ['locatedIn','Located In'],
        'locIn' => ['locatedIn','Loc In'],
        'occursbefore' => ['standard','Occurs Before'],
        'occursafter' => ['standard','Occurs After'],
        'circa' => ['standard','Circa'],
		'relationships' => ['relationships','Relationships'],
        'roles' => ['roles','roles'],
        'droles' => ['droles','droles'],
    );
    $linksToSearch = array(
		'Name' => 'search/all?searchbar=REPLACE&display=people',
		'Sex' => 'search/all?gender=REPLACE&display=people',
		'AgeA' => 'search/all?age_category=REPLACE&display=people',
		'StatusA' => 'search/all?status=REPLACE&display=people',
		'StatusA' => 'search/all?status=REPLACE&display=people',
		'RolesA' => 'search/all?role_types=REPLACE&display=people',
		'Occupation' => 'search/all?occupation=REPLACE&display=people',
		'Ethnolinguistic Descriptor' => 'search/all?ethnodescriptor=REPLACE&display=people',
		'Type' => 'search/all?event_type=REPLACE&display=events',
		'Date' => 'search/all?date=REPLACE&display=events',
		'Place Type' => 'search/all?place_type=REPLACE&display=places',
		'Source Type' => 'search/all?source_type=REPLACE&display=sources',
	);
	define('LINKSTOSEARCH', $linksToSearch);
    if(isset($record['label'])){
        $recordVars['Label'] = $record['label']['value'];
    }

    foreach($prettyNames as $name => $config){
        $type = $config[0];
        $prettyName = $config[1];

        if($type == 'standard' && valueNotEmpty($record, $name)){
            $recordVars[$prettyName] = $record[$name]['value'];
        }elseif($type == 'name' && valueNotEmpty($record, $name)){
            $UntouchedName = $record[$name]['value'];
            $recordVars[$prettyName] = str_replace('||', '<br>', $record[$name]['value']);
        }elseif($type == 'standardArray' && valueNotEmpty($record, $name)){
            $term2 = $config[3][0];
            $term3 = $config[3][1];
            if(valueNotEmpty($record,$term2) && valueNotEmpty($record,$term3) && !empty($record[$name]['value'])){
                $recordVars[$config[2]] = [
                    $config[4][0] => $record[$name]['value'],
                    $config[4][1] => $record[$term2]['value'],
                    $config[4][2] => $record[$term3]['value']
                ];
            }else{
                $recordVars[$prettyName] = $record[$name]['value'];
            }
        }elseif($type == 'description' && valueNotEmpty($record, $name)){
            $s = preg_replace('/(?<!href="|">)(?<!src=\")((http|ftp)+(s)?:\/\/[^<>\s]+)/is', '<a href="\\1" target="_blank">\\1</a>', $record['description']['value']);
            $recordVars[$prettyName] = $s;
        }elseif($type == 'dateRange' && valueNotEmpty($record, $name)){
            $recordVars[$prettyName] = $record[$name]['value'];
            if(valueNotEmpty($record, 'endsAt')){
              $recordVars[$prettyName] .= " - " . $record['endsAt']['value'];
            }
        }elseif($type == 'coordinates' && valueNotEmpty($record, $name)){
            $coors = explode('||',$record['coordinates']['value']);
            foreach($coors as $i => $cor){
                $cor = explode(' ', $cor);
                $lat = $cor[1];
                $lat = substr($lat,0,-1);
                $long = $cor[0];
                $long = substr($long,6);
                $coors[$i] = "Point($lat $long)";
            }
            $recordVars[$prettyName] = implode('||',$coors);
        }elseif($type == 'sourceLabel' && valueNotEmpty($record, $name)){
            if(valueNotEmpty($record, 'source')){
                $recordVars[$prettyName] = [
                    'label' => $record[$name]['value'],
                    'qid' => $record['source']['value']
                ];
            }else{
                $recordVars[$prettyName] = $record[$name]['value'];
            }
        }elseif($type == 'project'){
            if(valueNotEmpty($record, 'projectlabel')){
                if(valueNotEmpty($record, 'project')){
                    $recordVars[$prettyName] = [
                        'label' => $record['projectlabel']['value'],
                        'qid' => $record['project']['value']
                    ];
                }else{
                    $recordVars[$prettyName] = $record['projectlabel']['value'];
                }
            }elseif(valueNotEmpty($record, 'project') && valueNotEmpty($record, 'pname')){
                $recordVars['projectsA'] = [
                    'projectUrl' => $record['project']['value'],
                    'projectName' => $record['pname']['value']
                ];
            }
        }elseif($type == 'projref' && valueNotEmpty($record, $name)){
            if(isset($recordVars[$prettyName])){
                $recordVars[$prettyName] .= "||" . $record[$name]['value'];
            }else{
                $recordVars[$prettyName] = $record[$name]['value'];
            }
        }elseif($type == 'locatedIn' && valueNotEmpty($record, $name)){
            $values = [];
            foreach($result as $res){
                if (strpos($res[$name]['value'], '||') !== false) {
                    $values = array_merge($values, explode('||', $res[$name]['value']));
                }else{
                    array_push($values, $res[$name]['value']);
                }
            }
            $recordVars[$prettyName] = array_unique($values);
        }elseif($type == 'roles'){
            foreach($result as $r){
                if(valueNotEmpty($r, 'roles')){
                    if(valueNotEmpty($r,'roleevent') && valueNotEmpty($r,'roleeventlabel')){
                        $recordVars['RolesA'][] = [
                            'roles' => $r['roles']['value'],
                            'participant' => $r['roleeventlabel']['value'],
                            'pq' => $r['roleevent']['value']
                        ];
                    }
                }elseif(valueNotEmpty($r,'roleevent')){
                    if(ivalueNotEmpty($r,'roleeventlabel')){
                        $recordVars['eventRolesA'][] = [
                            'roles' => $r['roles']['value'],
                            'eventRoles' => $r['roleevent']['value'],
                            'eventRoleLabels' => $r['roleeventlabel']['value']
                        ];
                    }
                }
            }
          }elseif($type == 'status'){
            // 'standardArray','Status','StatusA',['statusevent','eventstatuslabel'],['statuses','statusEvents','eventstatusLabels']
              foreach($result as $r){
                  if(valueNotEmpty($r, 'status')){
                      if(valueNotEmpty($r,'statusevent') && valueNotEmpty($r,'eventstatuslabel')){
                          $recordVars['StatusA'][] = [
                              'statuses' => $r['status']['value'],
                              'statusEvents' => $r['statusevent']['value'],
                              'eventstatusLabels' => $r['eventstatuslabel']['value']
                          ];
                      }
                  }
              }
		  }elseif($type == 'relationships'){
              if(valueNotEmpty($r, 'relationships')){
                  if(valueNotEmpty($r,'relationshipperson') && valueNotEmpty($r,'relationshippersonlabel')){
                      $recordVars['RelationshipsA'][] = [
                          'relationships' => $r['relationships']['value'],
                          'relationshipperson' => $r['relationshipperson']['value'],
                          'relationshippersonlabel' => $r['relationshippersonlabel']['value']
                      ];
                  }
              }
          }elseif($type == 'droles'){
            foreach($result as $r){
                if(valueNotEmpty($r, 'droles')){
                    if(valueNotEmpty($r,'droleevent') && valueNotEmpty($r,'droleeventlabel')){
                        $recordVars['DRolesA'][] = [
                            'droles' => $r['droles']['value'],
                            'dparticipant' => $r['droleeventlabel']['value'],
                            'dpq' => $r['droleevent']['value']
                        ];
                    }
                }
            }
        }
    }
	// echo json_encode($recordVars);die;

    // create the html based on the type of results
    $htmlArray = [];

    //Header w/ date range
    $html = '';
    if($formType == "person"){
      $formType = "people";
    }
    else{
      $formType = $formType . 's';
    }
    $url = BASE_URL . "explore/" . $formType;
    $recordform = ucfirst($formType);
    $name = $UntouchedName;
    $nameNewline = str_replace('||','<br>',$name);
    $nameSlash = str_replace('||',' | ',$name);

    $dateRange = '';
    $html .= <<<HTML
<h4 class='last-page-header'>
    <a id='last-page' href="$url"><span id=previous-title>$recordform / </span></a>
    <span id='current-title'>$nameSlash</span>

</h4>
<h1>$nameNewline</h1>
<!--<h2 class='date-range'><span>$dateRange</span></h2>-->
HTML;

    $htmlArray['header'] = $html;

    //Description section
    $html = '';

    $htmlArray['description'] = $html;
    //Detail section
    $html = '';
    $html .= '<div class="detailwrap">';

    // echo json_encode($recordVars);return;

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
	  else if($key == "Place of Origin"){
		  if(isset($record['placeofOrigin'])){
			  $originUrl = $record['placeofOrigin']['value'];
	          $urlQ = explode("/", $originUrl);
	          $urlQ = end($urlQ);
			  $urlQ = array($urlQ);
	          $html .= createDetailHtml($value, $key, $urlQ);
		  }else{
			  $html .= createDetailHtml($value, $key);
		  }
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
 ?agent $p:$hasMatchType ?statementname.
  ?statementname $ps:$hasMatchType ?matcht.
  ?matcht $rdfs:label ?matchtype.
  ?statementname $pq:$matches ?match.
  ?match $wdt:$hasName ?matchlabel

}ORDER BY ?random
QUERY;

    $result = blazegraphSearch($closeMatchQuery);
    $connections['CloseMatch-count'] = count($result);
    $connections['CloseMatch'] = array_slice($result, 0, 8);  // return the first 8 results
  //print_r($result);
  // echo $closeMatchQuery['query'];die;
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
 OPTIONAL {?agent $p:$hasPersonStatus ?statstatus.
           ?statstatus $ps:$hasPersonStatus ?status.
           ?statstatus $pq:$hasStatusGeneratingEvent ?event.
           ?event $wdt:$hasName ?eventlabel.}.
}
}

QUERY;
	// echo $eventQuery['query'];die;
	$result = blazegraphSearch($eventQuery);
	$filterEvents = array();
	foreach($result as $array){
		if(!empty($array)) $filterEvents[] = $array;
	}
	$result = $filterEvents;
	// echo json_encode($result);die;
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
	// echo $peopleQuery['query'];die;
   $result = blazegraphSearch($peopleQuery);
   $result_counter = blazegraphSearch($peoplecounter);

    $connections['Person-count'] = $result_counter[0]['counter']['value'];
    $connections['Person'] = $result;  // return the first 8 results

	// people connections through ethnodescriptor
	$peoplecounter['query'] = <<<QUERY
	SELECT DISTINCT (COUNT(?people) as ?counter)
	{
		VALUES ?place { $wd:$QID }.
		?people $wdt:$instanceOf $wd:$person.
		?people $p:$hasEthnolinguisticDescriptor ?ethnodesc.
		?ethnodesc  $pq:$referstoPlaceofOrigin ?place2.
		FILTER (?place2 = ?place).
		?people $wdt:$hasName ?peoplename
	}
	QUERY;
	$peopleQuery['query'] = <<<QUERY
	SELECT DISTINCT ?people ?peoplename
	{
		VALUES ?place { $wd:$QID }.
		?people $wdt:$instanceOf $wd:$person.
		?people $p:$hasEthnolinguisticDescriptor ?ethnodesc.
		?ethnodesc  $pq:$referstoPlaceofOrigin ?place2.
		FILTER (?place2 = ?place).
		?people $wdt:$hasName ?peoplename
	}LIMIT 8
	QUERY;

	if($connections['Person-count'] < 8){         //only grab more people if the
		$result += blazegraphSearch($peopleQuery);//other query had less than 8
		$connections['Person'] = $result;
	}
	$result_counter2 = blazegraphSearch($peoplecounter); //add them to count reguardless
	$connections['Person-count'] += $result_counter2[0]['counter']['value'];

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
// echo $eventsQuery['query'];die;
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

	// var_dump($result);die;
	foreach ($result as $key => $value) {
		// $result[$key]['place'] = $result[$key]['otherp'];
		// unset($result[$key]['otherp']);
		// $result[$key]['placelabel'] = $result[$key]['relatedPlaces'];
		// unset($result[$key]['relatedPlaces']);
		$relatedQid = $value['place']['value'];
		$relatedQid = explode('/', $relatedQid);
		$relatedQid = array_pop($relatedQid);
		// var_dump($relatedQid);die;
		$eventsCounter['query'] = <<<QUERY
		SELECT DISTINCT (COUNT(?event) as ?counter)

		WHERE
		{
		VALUES ?place { $wd:$relatedQid }.
		?event $wdt:$instanceOf $wd:$event.
		?event $wdt:$atPlace ?place.
		?event $wdt:$hasName ?eventlabel
		}ORDER BY ?random
		QUERY;
		$eventsQuery['query'] = <<<QUERY
		SELECT DISTINCT ?event ?eventlabel

		WHERE
		{
		VALUES ?place { $wd:$relatedQid }.
		?event $wdt:$instanceOf $wd:$event.
		?event $wdt:$atPlace ?place.
		?event $wdt:$hasName ?eventlabel
		}LIMIT 8
		QUERY;
		$result = blazegraphSearch($eventsQuery);
		$result_counter = blazegraphSearch($eventsCounter);
		$connections['Event-count'] += $result_counter[0]['counter']['value'];
		$connections['Event'] = array_merge($connections['Event'],$result);

		$peoplecounter['query'] = <<<QUERY
		SELECT DISTINCT (COUNT(?people) as ?counter)
		{
		VALUES ?place { $wd:$relatedQid }.
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
		VALUES ?place { $wd:$relatedQid }.
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
		$connections['Person-count'] += $result_counter[0]['counter']['value'];
		$connections['Person'] = array_merge($connections['Person'],$result);
	}
	$connections['Event'] = array_slice($connections['Event'], 0, 8);  // return the first 8 results
	$connections['Person'] = array_slice($connections['Person'], 0, 8);  // return the first 8 results

	return json_encode($connections);
}

?>
