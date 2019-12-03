<?php

$tempQuery = <<<QUERY
SELECT DISTINCT ?event WHERE {
    ?event $wdt:$instanceOf $wd:$event.
    $queryFilters
    $sourceIdFilter
    $personIdFilter
}
$limitQuery
$offsetQuery
QUERY;
