<?php

$tempQuery = <<<QUERY
SELECT ?name ?description ?located  ?type ?date ?endDate
(group_concat(distinct ?rolename; separator = "||") as ?roles)
(group_concat(distinct ?participantname; separator = "||") as ?participant)
(group_concat(distinct ?part; separator = "||") as ?pq)
(group_concat(distinct ?extref1; separator = "||") as ?extref)

WHERE
{
VALUES ?event { $wd:$qid }.
OPTIONAL {?event $p:$hasEventType  ?object .
?object $prov:wasDerivedFrom ?provenance .
?provenance $pr:$isDirectlyBasedOn ?source .
OPTIONAL {?provenance $pr:$hasExternalReference ?extref1}}.
?event $rdfs:label ?name.
OPTIONAL {?event $wdt:$hasEventType ?eventtype.
?eventtype $rdfs:label ?type}.
OPTIONAL{ ?event $wdt:$hasDescription ?description}.
OPTIONAL{?event $wdt:$atPlace ?place.
        ?place $rdfs:label ?located}.
OPTIONAL{ ?event $wdt:$startsAt ?datetime.
        BIND(xsd:date(?datetime) AS ?date)}
 OPTIONAL{ ?event $wdt:$endsAt ?endDatetime.
         BIND(xsd:date(?endDatetime) AS ?endDate)}
 OPTIONAL{
  ?event $p:$providesParticipantRole ?statement .
	?statement $ps:$providesParticipantRole ?role.
	?role $rdfs:label ?rolename.
	?statement $pq:$hasParticipantRole ?part.
	?part $rdfs:label ?participantname}.

SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}GROUP BY ?name ?description ?located  ?type ?date ?endDate
QUERY;
