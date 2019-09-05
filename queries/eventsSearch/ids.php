<?php

$tempQuery = <<<QUERY
SELECT ?event WHERE {
    ?event $wdt:$instanceOf $wd:$event. 
    $eventTypeIdFilter
    $sourceIdFilter
    $dateRangeIdFilter
}
$limitQuery
$offsetQuery
QUERY;
