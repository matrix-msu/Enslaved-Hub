<?php

$tempQuery = <<<QUERY
SELECT DISTINCT ?agent
WHERE {
    ?agent $wdt:$instanceOf/$wdt:$subclassOf $wd:$agent. #agent or subclass of agent
    MINUS{?agent $wdt:$hasParticipantRole $wd:$researcher}
    $queryFilters
    $sourceIdFilter
    $eventIdFilter
    $placeIdFilter
}
$limitQuery
$offsetQuery
QUERY;
