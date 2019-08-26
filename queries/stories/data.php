<?php

$tempQuery = <<<QUERY
SELECT ?person ?personLabel ?name ?originLabel
    (group_concat(distinct ?status; separator = "||") as ?status)
    (group_concat(distinct ?place; separator = "||") as ?place)
    (group_concat(distinct ?startyear; separator = "||") as ?startyear)
    (group_concat(distinct ?endyear; separator = "||") as ?endyear)
    WHERE {
        SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
        ?person wdt:$instanceOf wd:$atPlace.
        ?person wdt:$hasSex wd:$female.
        OPTIONAL {?person wdt:$instanceOf wd:$agent.}
        OPTIONAL {?person wdt:$hasName ?name.}
        OPTIONAL {?person wdt:$hasOriginRecord ?origin.}
        OPTIONAL {?name wdt:$recordedAt ?event.
                ?event wdt:$startsAt ?startdate.}
        BIND(str(YEAR(?startdate)) AS ?startyear).

        OPTIONAL {?event wdt:$endsAt ?enddate.}
        BIND(str(YEAR(?enddate)) AS ?endyear).
        OPTIONAL {?event wdt:$atPlace ?place.}
        OPTIONAL { ?person wdt:$hasSex ?sex. }
        OPTIONAL { ?person wdt:$hasPersonStatus ?status. }
        OPTIONAL { ?person wdt:$hasOwner ?owner. }
        OPTIONAL { ?person wdt:$closeMatch ?match. }

    } group by ?person ?personLabel ?name ?originLabel
    $limitQuery
QUERY;
