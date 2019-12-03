<?php

$tempQuery = <<<QUERY
SELECT ?name ?type ?geonames ?code ?description
(group_concat(distinct ?source; separator = "||") as ?source)
(group_concat(distinct ?project; separator = "||") as ?project)
(group_concat(distinct ?locatedLabel; separator = ", ") as ?locatedIn)
(group_concat(distinct ?extref; separator = "||") as ?extref)

  WHERE
{
  VALUES ?place { $wd:$qid } #Q number needs to be changed for every place.
  OPTIONAL{ ?place $wdt:$hasDescription ?description}.

  ?place $wdt:$instanceOf $wd:$place;
        ?property  ?object .
  ?object $prov:wasDerivedFrom ?provenance .
  ?provenance $pr:$isDirectlyBasedOn ?source .
  OPTIONAL {?provenance $pr:$hasExternalReference ?extref}
  ?source $rdfs:label ?refName;
  OPTIONAL {?source $wdt:$generatedBy ?project.
      ?project $rdfs:label ?pname}.

  ?place $rdfs:label ?name.
  ?place $wdt:$hasPlaceType ?placetype.
  ?placetype $rdfs:label ?type.
  OPTIONAL{?place $wdt:$locatedIn ?locIn.
          ?locIn $rdfs:label ?locatedLabel}.
  OPTIONAL{ ?place $wdt:$geonamesID ?geonames.}
    OPTIONAL{ ?place $wdt:$modernCountryCode ?code.}

  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}GROUP BY ?name ?type ?geonames ?code ?description
QUERY;
