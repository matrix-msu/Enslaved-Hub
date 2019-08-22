<?php

$tempQuery = <<<QUERY
SELECT DISTINCT (COUNT(?source) as ?count)
WHERE {
    ?source wdt:$instanceOf wd:$entityWithProvenance. #entity with provenance
    $eventIdFilter
    $sourceTypeIdFilter
}
QUERY;
