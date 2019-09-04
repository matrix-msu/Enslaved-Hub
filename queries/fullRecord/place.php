<?php

$tempQuery = <<<QUERY
SELECT ?name ?desc ?located  ?type ?geonames ?code
(group_concat(distinct ?refName; separator = "||") as ?sourceLabel)
(group_concat(distinct ?pname; separator = "||") as ?projectlabel)
(group_concat(distinct ?source; separator = "||") as ?source)
(group_concat(distinct ?project; separator = "||") as ?project)

  WHERE
{
  VALUES ?place {$wd:$qid} #Q number needs to be changed for every place.
  ?place $wdt:$instanceOf $wd:$place;
        ?property  ?object .
  ?object $prov:wasDerivedFrom ?provenance .
  ?provenance $pr:$isDirectlyBasedOn ?source .
  ?source $rdfs:label ?refName;
          $wdt:$generatedBy ?project.
  ?project $rdfs:label ?pname.
  ?place schema:description ?desc.
  ?place $rdfs:label ?name.
  ?place $wdt:$hasPlaceType ?placetype.
  ?placetype $rdfs:label ?type.
  OPTIONAL{?place $wdt:$locatedIn ?locatedIn.
          ?locatedIn $rdfs:label ?located}.
  OPTIONAL{ ?place $wdt:$geonamesID ?geonames.}
    OPTIONAL{ ?place $wdt:$modernCountryCode ?code.}

  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}GROUP BY ?name ?desc ?located  ?type ?geonames ?code
QUERY;
