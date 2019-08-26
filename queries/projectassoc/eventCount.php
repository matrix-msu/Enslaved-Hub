<?php

$tempQuery = <<<QUERY
SELECT ?project ?projectLabel  (COUNT(*) AS ?eventcount)
    WHERE {
        ?project wdt:$instanceOf wd:$researchProject .         #find projects
        ?item wdt:$instanceOf wd:$event;        #find events
            p:$instanceOf  ?object .
        ?object prov:wasDerivedFrom ?provenance .
        ?provenance pr:$isDirectlyBasedOn ?reference .
        ?reference wdt:$generatedBy ?project;
                    wdt:$generatedBy wd:$qid
        SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }
    }
    GROUP BY ?project ?projectLabel
    ORDER BY ?count
QUERY;
