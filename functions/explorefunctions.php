<?php
require_once(dirname(__FILE__).'/../config.php');
require_once(dirname(__FILE__).'/../wikiconstants/properties.php');

function callAPI($url,$limit,$offset){
    $url.='&format=json';
    $json = file_get_contents($url);
  return $json;
}

//get all agents numbers
function queryAllAgentsCounter(){
  $query='SELECT (COUNT(?item) AS ?count) WHERE {?item wdt:P3/wdt:P2 wd:Q2 .}';
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
function counterOfGender($gender){
  $query="SELECT (COUNT(?item) AS ?count) WHERE {
    ?item wdt:P3/wdt:P2  wd:Q2 .
  	?item wdt:P17 wd:".$gender."}";
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
  return $onestatement;

}
function getpersonfullInfo($baseuri,$qitem){
  $url=$baseuri.$qitem.".json";
  $person=getJsonInfo($url);
  $allStatements=[];
  $allStatements['PersonInfo']='';
  $allStatements['Events']='';
  $allStatements['Provenance']='';
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

  $onestatement=getInfoperStatement($baseuri,$roles,"Roles","hasParticipantRoleRecord",qroleTypes);





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
    $allStatements['PersonInfo']=array_merge($allStatements['PersonInfo'],$onestatement['PersonInfo']);
    $allStatements['Events']=array_merge((array)$allStatements['Events'],$onestatement['Events']);
    $allStatements['Provenance']=array_merge((array)$allStatements['Provenance'],$onestatement['Provenance']);

  //  $allStatements=array_merge($allStatements,$onestatement);

  debugfunc($allStatements);
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

function detailPerson($statement,$label){?>
  <a href="<?php echo BASE_URL.'explorePeople/?seach='.$statement?>">
      <div class="detail">
          <div class="detail-top">
              <h3><?php echo $statement;?></h3>
              <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/>
          </div>
          <p class="detail-bottom"><?php echo $label;?></p>
      </div>
  </a>
<?php
}

function debugfunc($debugobject){?>
    <pre><?php echo var_dump($debugobject);?></pre>
<?php }
?>
