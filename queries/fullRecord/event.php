<?php

$tempQuery = <<<QUERY
SELECT ?name ?type ?date ?endDate ?occursbefore ?occursafter ?circa ?description
(group_concat(distinct ?rolename; separator = "||") as ?roles)
(group_concat(distinct ?participantname; separator = "||") as ?participant)
(group_concat(distinct ?part; separator = "||") as ?pq)
(group_concat(distinct ?extref1; separator = "||") as ?extref)
(group_concat(distinct ?located; separator = "||") as ?locatedIn)
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
OPTIONAL{
?event $wdt:$startsAt ?datetime.
?event $p:$startsAt/epsv:$startsAt ?node.
?node wikibase:timePrecision ?precision .

BIND(IF(?precision=9,YEAR(?datetime),IF(?precision=10,MONTH(?datetime),xsd:date(?datetime))) AS ?date)}.
OPTIONAL{ ?event $wdt:P63 ?endDatetime.
?event $p:P63/epsv:P63 ?node.
?node wikibase:timePrecision ?precision.

BIND(IF(?precision=9,YEAR(?endDatetime),IF(?precision=10,MONTH(?endDatetime),xsd:date(?endDatetime))) AS ?endDate)
}
OPTIONAL{ ?event $wdt:P69 ?ob.
?event $p:P69/epsv:P69 ?node.
?node wikibase:timePrecision ?precision.

BIND(IF(?precision=9,YEAR(?ob),IF(?precision=10,MONTH(?ob),xsd:date(?ob))) AS ?occursbefore)
}
OPTIONAL{ ?event $wdt:P52 ?oa.
?event $p:P52/epsv:P52 ?node.
?node wikibase:timePrecision ?precision.

BIND(IF(?precision=9,YEAR(?oa),IF(?precision=10,MONTH(?oa),xsd:date(?oa))) AS ?occursafter)
}

OPTIONAL{ ?event $wdt:P75 ?c.
?event $p:P75/epsv:P75 ?node.
?node wikibase:timePrecision ?precision.

BIND(IF(?precision=9,YEAR(?c),IF(?precision=10,MONTH(?c),xsd:date(?c))) AS ?circa)
}
OPTIONAL{
?event $p:$providesParticipantRole ?statement .
?statement eps:$providesParticipantRole ?role.
?role $rdfs:label ?rolename.
?statement $pq:$hasParticipantRole ?part.
?part $rdfs:label ?participantname}.
SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}GROUP BY ?name  ?type ?date ?endDate ?occursbefore ?circa ?description
QUERY;
// SELECT ?name ?description ?located  ?type ?date ?endDate
// (group_concat(distinct ?rolename; separator = "||") as ?roles)
// (group_concat(distinct ?participantname; separator = "||") as ?participant)
// (group_concat(distinct ?part; separator = "||") as ?pq)
// (group_concat(distinct ?extref1; separator = "||") as ?extref)
//
// WHERE
// {
// VALUES ?event { $wd:$qid }.
// OPTIONAL {?event $p:$hasEventType  ?object .
// ?object $prov:wasDerivedFrom ?provenance .
// ?provenance $pr:$isDirectlyBasedOn ?source .
// OPTIONAL {?provenance $pr:$hasExternalReference ?extref1}}.
// ?event $rdfs:label ?name.
// OPTIONAL {?event $wdt:$hasEventType ?eventtype.
// ?eventtype $rdfs:label ?type}.
// OPTIONAL{ ?event $wdt:$hasDescription ?description}.
// OPTIONAL{?event $wdt:$atPlace ?place.
//         ?place $rdfs:label ?located}.
// OPTIONAL{ ?event $wdt:$startsAt ?datetime.
//         BIND(xsd:date(?datetime) AS ?date)}
//  OPTIONAL{ ?event $wdt:$endsAt ?endDatetime.
//          BIND(xsd:date(?endDatetime) AS ?endDate)}
//  OPTIONAL{
//   ?event $p:$providesParticipantRole ?statement .
// 	?statement $ps:$providesParticipantRole ?role.
// 	?role $rdfs:label ?rolename.
// 	?statement $pq:$hasParticipantRole ?part.
// 	?part $rdfs:label ?participantname}.
//
// SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
// }GROUP BY ?name ?description ?located  ?type ?date ?endDate
