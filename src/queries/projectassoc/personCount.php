<?php

$tempQuery = <<<QUERY
SELECT DISTINCT ?project ?projectLabel (count(distinct ?agent) as ?agentcount)
    WHERE {
        VALUES ?project { $wd:$qid }
        ?agent $wdt:$instanceOf/$wdt:$subclassOf $wd:$agent;        #find agents
                $p:$instanceOf  ?object .
        ?object $prov:wasDerivedFrom ?provenance .
        ?provenance $pr:$isDirectlyBasedOn ?reference .
        ?reference $wdt:$generatedBy ?project

        SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }
    }
    GROUP BY ?project ?projectLabel
QUERY;
