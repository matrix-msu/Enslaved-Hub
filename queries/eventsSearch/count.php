<?php

$tempQuery = <<<QUERY
SELECT (COUNT(DISTINCT ?event) as ?count)
WHERE {
    ?event $wdt:$instanceOf $wd:$event. 
    $eventTypeIdFilter
    $sourceIdFilter
    $dateRangeIdFilter
}
QUERY;
