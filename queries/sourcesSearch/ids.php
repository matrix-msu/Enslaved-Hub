<?php

$tempQuery = <<<QUERY
SELECT DISTINCT ?source WHERE {
    ?source $wdt:$instanceOf $wd:$entityWithProvenance. #entity with provenance
    $queryFilters
    $eventIdFilter
    $personIdFilter
}
$limitQuery
$offsetQuery
QUERY;
