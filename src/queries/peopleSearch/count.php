<?php

$tempQuery = <<<QUERY
SELECT (COUNT(DISTINCT ?agent) as ?count)
WHERE {
    ?agent $wdt:$instanceOf/$wdt:$subclassOf $wd:$agent. #agent or subclass of agent
    MINUS{?agent $wdt:$hasParticipantRole $wd:$researcher}
    $queryFilters
    $sourceIdFilter
    $eventIdFilter
    $placeIdFilter
}
QUERY;
