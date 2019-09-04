<?php

$tempQuery = <<<QUERY
SELECT DISTINCT ?place ?placeLabel (SHA512(CONCAT(STR(?place), STR(RAND()))) as ?random) WHERE {
?place $wdt:$instanceOf $wd:$place .
?place wikibase:statements ?statementcount .
        FILTER (?statementcount >3  )
SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }
} ORDER BY ?random
LIMIT 8
QUERY;
