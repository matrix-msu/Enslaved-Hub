<?php

$tempQuery = <<<QUERY
SELECT DISTINCT ?place ?placelabel ?geonames ?code
 (count(distinct ?person) as ?countpeople)
 (count(distinct ?event) as ?countevent)
 (count(distinct ?source) as ?countsource)
 (count(distinct ?locatedIn) as ?countplace)
 (group_concat(distinct ?typelab; separator = "||") as ?types)
 (group_concat(distinct ?locatedLabel; separator = ", ") as ?locatedIn)

WHERE {

  VALUES ?place { $qidList }.
    ?place ?property  ?object .
  	?object $prov:wasDerivedFrom ?provenance .
  	?provenance $pr:$isDirectlyBasedOn ?source .
  	?place $rdfs:label ?placelabel.
  OPTIONAL{?event $wdt:$atPlace ?place;
           	   $p:$providesParticipantRole ?statement.
  	?statement $ps:$providesParticipantRole ?role.
  	?statement $pq:$hasParticipantRole ?person}.


    ?place $rdfs:label ?placeLabel.
    ?place $wdt:$hasPlaceType ?type.
     ?type $rdfs:label ?typelab.
  OPTIONAL {?place $wdt:$locatedIn ?locIn.
           ?locIn $rdfs:label ?locatedLabel}.
  OPTIONAL{ ?place $wdt:$geonamesID ?geonames.}
  OPTIONAL{ ?place $wdt:$modernCountryCode ?code.}

 }GROUP BY ?place ?placelabel ?geonames ?code
order by ?placeLabel
QUERY;
