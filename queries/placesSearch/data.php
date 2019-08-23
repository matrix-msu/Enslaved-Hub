<?php

$tempQuery = <<<QUERY
SELECT ?place ?placelabel ?locatedInLabel ?geonames ?code
 (count(distinct ?person) as ?countpeople)
 (count(distinct ?event) as ?countevent)
 (count(distinct ?source) as ?countsource)
 (group_concat(distinct ?locationlab; separator = "||") as ?location)
(group_concat(distinct ?typelab; separator = "||") as ?types)
 
WHERE {
  VALUES ?place { $qidList }.
    ?place ?property  ?object .
  	?object prov:wasDerivedFrom ?provenance .
  	?provenance pr:$isDirectlyBasedOn ?source .
  	?place rdfs:label ?placelabel.
  	?event wdt:$atPlace ?place;
              p:$providesParticipantRole ?statement.
  	?statement ps:$providesParticipantRole ?role.
  	?statement pq:$hasParticipantRole ?person.

    ?place wdt:$hasPlaceType ?type.
    ?type rdfs:label ?typelab.
    OPTIONAL {?place wdt:$locatedIn ?locatedIn.
           ?locatedIn rdfs:label ?locationlab}.
    OPTIONAL{ ?place wdt:$geonamesID ?geonames.}
    OPTIONAL{ ?place wdt:$modernCountryCode ?code.}

 }GROUP BY ?place ?placelabel ?placeLabel ?locatedInLabel ?geonames ?code
order by ?placeLabel
QUERY;
