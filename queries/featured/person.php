<?php

$tempQuery = <<<QUERY
SELECT DISTINCT ?agent ?agentLabel (SHA512(CONCAT(STR(?agent), STR(RAND()))) as ?random) WHERE {
?agent $wdt:$instanceOf/$wdt:$subclassOf $wd:$agent . #all agents and people
?agent wikibase:statements ?statementcount . #with at least 4 core fields
FILTER (?statementcount >3  ).
?agent $wdt:$closeMatch ?match. #and they have a match
SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }
} ORDER BY ?random
LIMIT 8
QUERY;
