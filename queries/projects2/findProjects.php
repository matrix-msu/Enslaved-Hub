<?php

$tempQuery = <<<QUERY
SELECT ?project ?projectLabel
    WHERE {
    ?project wdt:$instanceOf wd:$researchProject          #find projects
    SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }
    }
QUERY;
