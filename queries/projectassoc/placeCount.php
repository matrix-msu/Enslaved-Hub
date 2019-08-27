<?php

$tempQuery = <<<QUERY
SELECT ?project ?projectLabel  (COUNT(*) AS ?placecount)
    WHERE {
        ?project wdt:$instanceOf wd:$researchProject .         #find projects
        ?item wdt:$instanceOf wd:$place;        #find places
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
