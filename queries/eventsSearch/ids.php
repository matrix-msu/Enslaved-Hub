<?php

$tempQuery = <<<QUERY
SELECT ?event WHERE {
    ?event $wdt:$instanceOf $wd:$event.
    $queryFilters
    $sourceIdFilter
}
$limitQuery
$offsetQuery
QUERY;
