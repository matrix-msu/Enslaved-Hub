<?php

$tempQuery = <<<QUERY
SELECT ?project ?projectLabel  (COUNT(distinct ?agent) AS ?count)
    WHERE {
        ?project $wdt:$instanceOf $wd:$researchProject .         #find projects
        ?agent $wdt:$instanceOf/$wdt:$subclassOf $wd:$agent;        #find agents
            $p:$instanceOf  ?object .
        ?object $prov:wasDerivedFrom ?provenance .
        ?provenance $pr:$isDirectlyBasedOn ?reference .
        ?reference $wdt:$generatedBy ?project
        SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }
    }
    GROUP BY ?project ?projectLabel
    ORDER BY ?count
QUERY;
