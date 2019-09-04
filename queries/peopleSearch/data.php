<?php

$tempQuery = <<<QUERY
SELECT DISTINCT ?agent
(count(distinct ?people) as ?countpeople)
(count(distinct ?roleevent) as ?countevent)
(count(distinct ?place) as ?countplace)
(count(distinct ?source) as ?countsource)

(group_concat(distinct ?name; separator = "||") as ?name1) #name
(group_concat(distinct ?statuslabel; separator = "||") as ?status1) #status
(group_concat(distinct ?sexlab; separator = "||") as ?sex1) #Sex
(group_concat(distinct ?startyear; separator = "||") as ?startyear1)
(group_concat(distinct ?endyear; separator = "||") as ?endyear1)
(group_concat(distinct ?placelab; separator = "||") as ?place1) #place

WHERE {
  VALUES ?agent { $qidList }.
        ?agent ?property  ?object .
        ?object $prov:wasDerivedFrom ?provenance .
        ?provenance $pr:$isDirectlyBasedOn ?source .

        ?agent $wdt:$hasName ?name.
OPTIONAL{?agent $wdt:$hasPersonStatus ?status.
         ?status $rdfs:label ?statuslabel}.

OPTIONAL{
        ?agent $p:$hasParticipantRole ?statementrole.
        ?statementrole $ps:$hasParticipantRole ?role.
        ?statementrole $pq:$roleProvidedBy ?roleevent.
        OPTIONAL{?roleevent $wdt:$startsAt ?startdate.
        BIND(str(YEAR(?startdate)) AS ?startyear)}.
        OPTIONAL{?roleevent $wdt:$endsAt ?enddate.
        BIND(str(YEAR(?enddate)) AS ?endyear)}.
        OPTIONAL{?roleevent $wdt:$atPlace ?place.
        ?place $rdfs:label ?placelab}}.
            
OPTIONAL { ?agent $wdt:$hasSex ?sex.
                ?sex $rdfs:label ?sexlab}

OPTIONAL {?agent $wdt:$hasInterAgentRelationship ?people}.

} group by ?agent

QUERY;
