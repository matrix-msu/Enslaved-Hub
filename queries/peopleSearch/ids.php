<?php

$tempQuery = <<<QUERY
SELECT DISTINCT ?agent
WHERE {
    ?agent $wdt:$instanceOf/$wdt:$subclassOf $wd:$agent. #agent or subclass of agent
    MINUS{?agent $wdt:$hasParticipantRole $wd:$researcher}
    $genderIdFilter
    $nameQuery
    $ageIdFilter
    $ethnoIdFilter
    $roleIdFilter
    $statusIdFilter
    $occupationIdFilter
    $sourceTypeIdFilter
    $eventIdFilter
    $eventTypeIdFilter
    $placeIdFilter
    $placeTypeIdFilter
    $cityIdFilter
    $provinceIdFilter
    $projectIdFilter
    
} 
$limitQuery
$offsetQuery
QUERY;
