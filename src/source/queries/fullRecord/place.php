<?php

$tempQuery = <<<QUERY
SELECT ?label ?geonames ?code ?description ?locIn ?project
(group_concat(distinct ?name1; separator = "||") as ?name)
(group_concat(distinct ?alternativename; separator = "||") as ?altname)
(group_concat(distinct ?Ptype; separator = "||") as ?type)
(group_concat(distinct ?locatedLabel; separator = ", ") as ?locatedIn)
(group_concat(distinct ?coordinate; separator="||") AS ?coordinates)
  WHERE
{
  VALUES ?place { $wd:$qid }.
  OPTIONAL{ ?place $wdt:$hasDescription ?description}.
  OPTIONAL {?place ?property  ?object .
  ?object $prov:wasDerivedFrom ?provenance .
  ?provenance $pr:$isDirectlyBasedOn ?source .
  ?source $wdt:$generatedBy ?proj.
  ?proj $rdfs:label ?project.
  OPTIONAL {?provenance $pr:$hasExternalReference ?extref}}.
  ?place $rdfs:label ?label.
  OPTIONAL{?place $wdt:$hasPlaceType ?placet.
  ?placet $rdfs:label ?Ptype}.
  OPTIONAL{?place $wdt:$locatedIn ?locIn.
          ?locIn $rdfs:label ?locatedLabel}.
  OPTIONAL{ ?place $wdt:$geonamesID ?geonames.}
  OPTIONAL{ ?place $wdt:$modernCountryCode ?code.}
  OPTIONAL{?place $wdt:$hasName ?name1}.
  OPTIONAL{?place $wdt:$hasAlternateName ?alternativename}.
  OPTIONAL{?place $wdt:$hasCoordinates ?coordinate}.

}GROUP BY ?label ?geonames ?code ?description ?locIn ?project
QUERY;
