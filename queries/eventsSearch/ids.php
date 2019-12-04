<?php

$tempQuery = <<<QUERY
SELECT DISTINCT ?event WHERE {
    ?event $wdt:$instanceOf $wd:$event.
    $queryFilters
    $sourceIdFilter
    $personIdFilter
    $placeIdFilter
}
$limitQuery
$offsetQuery
QUERY;
