<?php

$tempQuery = <<<QUERY
SELECT DISTINCT ?type (SAMPLE(?event) AS ?event) (SAMPLE(?elabel) AS ?label)
(SHA512(CONCAT(STR(?event), STR(RAND()))) as ?random) WHERE {

    ?event wdt:$instanceOf wd:$event;
            rdfs:label ?elabel;
                wdt:$hasEventType ?type;
            wikibase:statements ?statementcount .
        FILTER (?statementcount >3  ).
    SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }
    }
GROUP BY ?type
ORDER BY ?random
LIMIT 8
QUERY;
