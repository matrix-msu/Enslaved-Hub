<?php

$tempQuery = <<<QUERY
SELECT DISTINCT ?agent
(group_concat(distinct ?startyear; separator = "||") as ?startyear) #daterange
(group_concat(distinct ?endyear; separator = "||") as ?endyear)

(group_concat(distinct ?name; separator = "||") as ?name) #name
(group_concat(distinct ?placelab; separator = "||") as ?place) #place
(group_concat(distinct ?statuslab; separator = "||") as ?status) #status
(group_concat(distinct ?sexlab; separator = "||") as ?sex) #Sex


(count(distinct ?relations) as ?countpeople)
(count(distinct ?event) as ?counterevent)
(count(distinct ?place) as ?countplace)
(count(distinct ?reference) as ?countsource)
WHERE {

  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }

  ?agent wdt:$instanceOf/wdt:$subclassOf wd:$agent;

         wdt:$hasName ?name; #name is mandatory
            p:$instanceOf  ?object .
  ?object prov:wasDerivedFrom ?provenance .
  ?provenance pr:$isDirectlyBasedOn ?reference .
  ?reference wdt:$generatedBy wd:$Q_ID #include here the Q number of the project


  MINUS{ ?agent wdt:$hasParticipantRole wd:$researcher }. #remove all researchers

  OPTIONAL { ?agent wdt:$hasPersonStatus ?status.
            ?status rdfs:label ?statuslab}

  OPTIONAL { ?agent wdt:$hasSex ?sex.
            ?sex rdfs:label ?sexlab}

  OPTIONAL { ?agent wdt:$hasInterAgentRelationship ?relations}.
  OPTIONAL { ?agent wdt:$closeMatch ?relations}.

  OPTIONAL{ ?reference wdt:$reportsOn ?event.
            ?event  wdt:$startsAt ?startdate.
           BIND(str(YEAR(?startdate)) AS ?startyear).
           OPTIONAL {?event wdt:$endsAt ?enddate.
           BIND(str(YEAR(?enddate)) AS ?endyear)}.
           OPTIONAL {?event wdt:$atPlace ?place.
                    ?place rdfs:label ?placelab}

          }.


} group by ?agent
order by ?agent
limit $Q_limit
offset $Q_offset
QUERY;
