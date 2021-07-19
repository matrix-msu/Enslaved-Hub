<?php
ini_set('display_errors', 1);
ini_set("memory_limit", "-1");
set_time_limit(0);
$start = microtime(true);

$instanceOfPid = 'P1';
$isProjectQid = 'Q172';
$sourceToProjectConnector = 'P16';
$hasName = 'P20';
$isDirectlyBasedOn = 'P6';
$propertiesToCount = array('P33','P42','P46','P31',$instanceOfPid,'P22','P23','P30',$sourceToProjectConnector);

$formattedData = array();
$labels = array();
$projectToSources = array();
$sourceToItems = array();
$projectToItems = array();

$file = fopen("latest.wikibase.dump.json", "r");
fgets($file); //ignore first line
$line = fgets($file);
$next = fgets($file);
$nextNext = fgets($file);
while( $next !== false ){
    if( $nextNext !== false ){ //the last line doesn't have a comma
        $line = substr($line, 0, -2);
    }
    $item = json_decode($line, true);
    $labels[$item['id']] = $item['labels']['en']['value']; //add to labels
    if($item['type'] == 'item'){
        $formattedArray = array();
        foreach($propertiesToCount as $pid){
            if(isset($item['claims'][$pid])){
                $claim = $item['claims'][$pid];
                if(isset($claim[0]['mainsnak']['datavalue']['value']['id'])){ //is qid
                    $value = $claim[0]['mainsnak']['datavalue']['value']['id'];
                    if($pid == $instanceOfPid && $value == $isProjectQid ){ //is project
                        $projectToSources[$item['id']] = array();
                    }
                }else{
                    $value = $claim[0]['mainsnak']['datavalue']['value']; //is string
                }
                $formattedArray[$pid] = $value;
            }
        }
        if(  //check if the item has a source
            isset($item['claims'][$hasName]) &&
            isset($item['claims'][$hasName][0]['references']) &&
            isset($item['claims'][$hasName][0]['references'][0]['snaks'][$isDirectlyBasedOn])
        ){
            $sourceQid = $item['claims'][$hasName][0]['references'][0]['snaks'][$isDirectlyBasedOn][0]['datavalue']['value']['id'];
            if(!isset($sourceToItems[$sourceQid])){
                $sourceToItems[$sourceQid] = array();
            }
            $sourceToItems[$sourceQid][] = $item['id'];
        }
        $formattedData[$item['id']] = $formattedArray;
    }
    $line = $next;
    $next = $nextNext;
    $nextNext = fgets($file);
}
fclose($file);

foreach($formattedData as $qid => $properties){ //create project to sources
    if(isset($properties[$sourceToProjectConnector])){
        $projectQid = $properties[$sourceToProjectConnector];
        $projectToSources[$projectQid][] = $qid;
        unset($formattedData[$qid][$sourceToProjectConnector]);
    }
}

foreach($projectToSources as $projectQid => $sources){ //create project to items
    $projectToItems[$projectQid] = array();
    foreach($sources as $sourceQid){
        if(isset($sourceToItems[$sourceQid])){
            $projectToItems[$projectQid] = array_merge($projectToItems[$projectQid], $sourceToItems[$sourceQid]);
        }
    }
}

// printInfo($start);
// foreach($projectToItems as $projectQid => $items){ //print item count per project
//     echo $projectQid." ".$labels[$projectQid]."<br>";
//     echo count($items)."<br><br>";
// }
// die;

//count by project
$counts = array();
foreach($projectToItems as $projectQid => $items){
    $projectLabel = $labels[$projectQid];
    if(count($items)>0){
        $counts[$projectLabel] = array();
    }
    foreach($items as $id){
        $properties = $formattedData[$id];
        foreach($properties as $property => $value){
            $label = $labels[$value];
            $property = $labels[$property];
            if(!isset($counts[$projectLabel][$property])){
                $counts[$projectLabel][$property] = array($label=>0);
            }elseif(!isset($counts[$projectLabel][$property][$label])){
                $counts[$projectLabel][$property][$label] = 0;
            }
            $counts[$projectLabel][$property][$label]++;
        }
    }
}

//count all
$counts['all'] = array();
foreach($formattedData as $id => $properties){
    foreach($properties as $property => $value){
        $label = $labels[$value];
        $property = $labels[$property];
        if(!isset($counts['all'][$property])){
            $counts['all'][$property] = array($label=>0);
        }elseif(!isset($counts['all'][$property][$label])){
            $counts['all'][$property][$label] = 0;
        }
        $counts['all'][$property][$label]++;
    }
}

// $formattedCounts = array();
// foreach($counts as $pid => $values){
//     $formattedCounts[$pid] = array();
//     foreach($values as $value => $count){
//         $formattedCounts[$pid][] = array($value,$count);
//     }
// }

// $counts['time'] = printInfo($start);
// echo json_encode($counts);die;
file_put_contents('counts.json', json_encode($counts));

function printInfo($start){
    $timeMins = (microtime(true) - $start)/60;
    return "Current total time is: $timeMins minutes";
}
