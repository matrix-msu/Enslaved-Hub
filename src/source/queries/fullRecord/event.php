<?php

$tempQuery = <<<QUERY
SELECT ?name ?type ?endDate ?occursbefore ?occursafter ?circa ?project ?date
(group_concat(distinct ?rolename; separator = "||") as ?roles)
(group_concat(distinct ?participantname; separator = "||") as ?participant)
(group_concat(distinct ?part; separator = "||") as ?pq)
(group_concat(distinct ?extref1; separator = "||") as ?extref)
(group_concat(distinct ?place; separator = "||") as ?locIn)
(group_concat(distinct ?located; separator = "||") as ?locatedIn)
(group_concat(distinct ?date; separator = "||") as ?eventDates)
(group_concat(distinct ?description; separator = "||") as ?eventDescriptions)
WHERE
{
VALUES ?event { $wd:$qid }.
OPTIONAL {?event $p:$hasEventType  ?object .
?object prov:wasDerivedFrom ?provenance .
?provenance $pr:$isDirectlyBasedOn ?source .
?source $wdt:$generatedBy ?proj.
?proj $rdfs:label ?project.
OPTIONAL {?provenance $pr:$hasExternalReference ?extref1}}.
?event $wdt:$hasName ?name.
OPTIONAL {?event $wdt:$hasEventType ?eventtype.
?eventtype $rdfs:label ?type}.
OPTIONAL{ ?event $wdt:$hasDescription ?description}.

OPTIONAL{
?event $wdt:$startsAt ?datetime.
?event $p:$startsAt/$psv:$startsAt ?node1.
?node1 wikibase:timePrecision ?precision .
BIND(IF(?precision=9,YEAR(?datetime),IF(?precision=10,MONTH(?datetime),xsd:date(?datetime))) AS ?startDate)}.

OPTIONAL{ ?event $wdt:$endsAt ?endDatetime.
?event $p:$endsAt/$psv:$endsAt ?node2.
?node2 wikibase:timePrecision ?precision.
BIND(IF(?precision=9,YEAR(?endDatetime),IF(?precision=10,MONTH(?endDatetime),xsd:date(?endDatetime))) AS ?endDate)}.

OPTIONAL{ ?event $wdt:$date ?eventdate.
?event $p:$date/$psv:$date ?node3.
?node3 wikibase:timePrecision ?precision.
BIND(IF(?precision=9,YEAR(?eventdate),IF(?precision=10,MONTH(?eventdate),xsd:date(?eventdate))) AS ?date)}.

OPTIONAL{ ?event $wdt:$occursBefore ?ob.
?event $p:$occursBefore/$psv:$occursBefore ?node4.
?node4 wikibase:timePrecision ?precision.
BIND(IF(?precision=9,YEAR(?ob),IF(?precision=10,MONTH(?ob),xsd:date(?ob))) AS ?occursbefore)}.

OPTIONAL{ ?event $wdt:$occursAfter ?oa.
?event $p:$occursAfter/$psv:$occursAfter ?node5.
?node5 wikibase:timePrecision ?precision.
BIND(IF(?precision=9,YEAR(?oa),IF(?precision=10,MONTH(?oa),xsd:date(?oa))) AS ?occursafter)}.

OPTIONAL{ ?event $wdt:P75 ?c.
?event $p:P75/$psv:P75 ?node6.
?node6 wikibase:timePrecision ?precision.
BIND(IF(?precision=9,YEAR(?c),IF(?precision=10,MONTH(?c),xsd:date(?c))) AS ?circa)}.

OPTIONAL{
?event $p:$providesParticipantRole ?statement .
?statement $ps:$providesParticipantRole ?role.
?role $rdfs:label ?rolename.
?statement $pq:$hasParticipantRole ?part.
?part $rdfs:label ?participantname}.
SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}GROUP BY ?name ?type ?endDate ?occursbefore ?occursafter ?circa ?project ?date
QUERY;
