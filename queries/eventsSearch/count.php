<?php

$tempQuery = <<<QUERY
SELECT DISTINCT (COUNT(?event) as ?count)
WHERE {
    ?event $wdt:$instanceOf $wd:$event. 
    $eventTypeIdFilter
    $sourceIdFilter
    $dateRangeIdFilter
}
QUERY;
