<?php

$tempQuery = <<<QUERY
SELECT ?source WHERE {
    ?source wdt:$instanceOf wd:$entityWithProvenance. #entity with provenance
    $eventIdFilter
    $sourceTypeIdFilter
}
$limitQuery
$offsetQuery
QUERY;
