<?php

$tempQuery = <<<QUERY
SELECT ?name ?desc ?located  ?type ?date ?endDate
(group_concat(distinct ?refName; separator = "||") as ?sources)
(group_concat(distinct ?pname; separator = "||") as ?researchprojects)
(group_concat(distinct ?rolename; separator = "||") as ?roles)
(group_concat(distinct ?participantname; separator = "||") as ?participant)
(group_concat(distinct ?participant; separator = "||") as ?pq)

WHERE
{
VALUES ?event {$wd:$qid} #Q number needs to be changed for every event.
?event $wdt:$instanceOf $wd:$event;
		 ?property  ?object .
?object $prov:wasDerivedFrom ?provenance .
?provenance $pr:$isDirectlyBasedOn ?source .
?source $rdfs:label ?refName;
        $wdt:$generatedBy ?project.
?project $rdfs:label ?pname.
?event $rdfs:label ?name.
?event $wdt:$hasEventType ?eventtype.
?eventtype $rdfs:label ?type.
OPTIONAL{ ?event schema:description ?desc}.
OPTIONAL{?event $wdt:$atPlace ?place.
        ?place $rdfs:label ?located}.
OPTIONAL{ ?event $wdt:$startsAt ?datetime.
        BIND(xsd:date(?datetime) AS ?date)}
 OPTIONAL{ ?event $wdt:$endsAt ?endDatetime.
         BIND(xsd:date(?endDatetime) AS ?endDate)}

 OPTIONAL{
  ?event $p:$providesParticipantRole ?statement .
	?statement $ps:$providesParticipantRole ?roles .
	?roles $rdfs:label ?rolename.
	?statement $pq:$hasParticipantRole ?participant.
	?participant $rdfs:label ?participantname}.

SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}GROUP BY ?name ?desc ?located  ?type ?date ?endDate
QUERY;
