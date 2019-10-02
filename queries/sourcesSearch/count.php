<?php

$tempQuery = <<<QUERY
SELECT (COUNT(DISTINCT ?source) as ?count)
WHERE {
    ?source $wdt:$instanceOf $wd:$entityWithProvenance. #entity with provenance
    $queryFilters
    $eventIdFilter
}
QUERY;
