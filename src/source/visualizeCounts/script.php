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
$hasSex = 'P31';
$male = 'Q296';
$female = 'Q294';
$hasAge = 'P42';
$propertiesToCount = array(
    'P33',$hasAge,'P46',$hasSex,$instanceOfPid,'P22','P23',
    'P30',$sourceToProjectConnector, 'P77', 'P24', 'P32'
);

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
// $count = 0;
while( $next !== false ){ // && $count < 2000  ){
    // $count++;
    if( $nextNext !== false ){ //the last line doesn't have a comma
        $line = substr($line, 0, -2);
    }
    $item = json_decode($line, true);
    $labels[$item['id']] = $item['labels']['en']['value']; //add to labels
    if($item['type'] == 'item'){
        $formattedArray = array();
        foreach($propertiesToCount as $pid){
            if(isset($item['claims'][$pid])){
                $formattedArray[$pid] = array();
                $claim = $item['claims'][$pid];
                foreach($claim as $claimObj){
                    if(isset($claimObj['mainsnak']['datavalue']['value']['id'])){ //is qid
                        $value = $claimObj['mainsnak']['datavalue']['value']['id'];
                        if($pid == $instanceOfPid && $value == $isProjectQid ){ //is project
                            $projectToSources[$item['id']] = array();
                        }
                    }else{
                        $value = $claimObj['mainsnak']['datavalue']['value']; //is string
                    }
                    $formattedArray[$pid][] = $value;
                }
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
        $projectQid = $properties[$sourceToProjectConnector][0];
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

//count all
$counts = array();
$allLabel = 'All Projects';
$counts[$allLabel] = array();
// echo json_encode($labels);die;
// echo json_encode($formattedData);die;
foreach($formattedData as $id => $properties){
    foreach($properties as $pid => $valuesArray){
        $valueNum = 0;
        for($i=0; $i<count($valuesArray); $i++){
            $value = $valuesArray[$i];
            $label = $value;
            if( isset($labels[$value]) ){ //no labels for string values
                $label = $labels[$value];
            }
            $property = $labels[$pid];
            if($pid == $hasAge && isset($properties[$hasSex]) && $valueNum == 0 ){
                if($properties[$hasSex][0] == $male){
                    $property = "Male_Ages";
                }elseif($properties[$hasSex][0] == $female){
                    $property = "Female_Ages";
                }
                $valuesArray[] = $value;
            }
            if(!isset($counts[$allLabel][$property])){
                $counts[$allLabel][$property] = array($label=>0);
            }elseif(!isset($counts[$allLabel][$property][$label])){
                $counts[$allLabel][$property][$label] = 0;
            }
            $counts[$allLabel][$property][$label]++;
            $valueNum++;
        }
    }
}

//count by project
foreach($projectToItems as $projectQid => $items){
    $projectLabel = $labels[$projectQid];
    if(count($items)>0){
        $counts[$projectLabel] = array();
    }
    foreach($items as $id){
        $properties = $formattedData[$id];
        foreach($properties as $pid => $valuesArray){
            $valueNum = 0;
            for($i=0; $i<count($valuesArray); $i++){
                $value = $valuesArray[$i];
                $label = $value;
                if( isset($labels[$value]) ){ //no labels for string values
                    $label = $labels[$value];
                }
                $property = $labels[$pid];
                if($pid == $hasAge && isset($properties[$hasSex]) && $valueNum == 0 ){
                    if($properties[$hasSex][0] == $male){
                        $property = "Male_Ages";
                    }elseif($properties[$hasSex][0] == $female){
                        $property = "Female_Ages";
                    }
                    $valuesArray[] = $value;
                }
                if(!isset($counts[$projectLabel][$property])){
                    $counts[$projectLabel][$property] = array($label=>0);
                }elseif(!isset($counts[$projectLabel][$property][$label])){
                    $counts[$projectLabel][$property][$label] = 0;
                }
                $counts[$projectLabel][$property][$label]++;
                $valueNum++;
            }
        }
    }
}

$formattedCounts = array();
foreach($counts as $projectLabel => $object){
    $formattedCounts[$projectLabel] = array();
    foreach($object as $pid => $values){
        $formattedCounts[$projectLabel][$pid] = array();
        foreach($values as $value => $count){
            $formattedCounts[$projectLabel][$pid][] = array($value,$count);
        }
    }
}

// $counts['time'] = printInfo($start);
// var_dump($counts);die;
file_put_contents('counts.json', json_encode($formattedCounts));
echo "Counts Script Finished!\n";

function printInfo($start){
    $timeMins = (microtime(true) - $start)/60;
    return "Current total time is: $timeMins minutes";
}
