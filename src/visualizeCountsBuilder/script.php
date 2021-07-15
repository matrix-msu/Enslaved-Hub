<?php
ini_set('display_errors', 1);
ini_set("memory_limit", "-1");
set_time_limit(0);
$start = microtime(true);

$instanceOfPid = 'P1';
$isProjectQid = 'Q172';
$sourceToProjectConnector = 'P16';
$propertiesToCount = array('P33','P42','P46','P31',$instanceOfPid,'P22','P23','P30',$sourceToProjectConnector);

$formattedData = array();
$labels = array();
$projectToSources = array();

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
        $formattedData[$item['id']] = $formattedArray;
    }
    $line = $next;
    $next = $nextNext;
    $nextNext = fgets($file);
}
fclose($file);

foreach($formattedData as $qid => $properties){ //add sources
    if(isset($properties[$sourceToProjectConnector])){
        $projectQid = $properties[$sourceToProjectConnector];
        $projectToSources[$projectQid][] = $qid;
    }
}

printInfo($start);
echo "<br>";
foreach($projectToSources as $qid => $sources){
    echo $labels[$qid]."<br>";
    print_r($sources);
    echo "<br>";
}
die;

$counts = array();
foreach($formattedData as $id => $properties){
    foreach($properties as $property => $value){
        $label = $labels[$value];
        $property = $labels[$property];
        if(!isset($counts[$property])){
            $counts[$property] = array($label=>0);
        }elseif(!isset($counts[$property][$label])){
            $counts[$property][$label] = 0;
        }
        $counts[$property][$label]++;
    }
}

$formattedCounts = array();
foreach($counts as $pid => $values){
    $formattedCounts[$pid] = array();
    foreach($values as $value => $count){
        $formattedCounts[$pid][] = array($value,$count);
    }
}

print_r($formattedCounts);
file_put_contents('counts.json', json_encode($formattedCounts));

function printInfo($start){
    $timeMins = (microtime(true) - $start)/60;
    echo "Current total time is: $timeMins minutes\n\n";
}
