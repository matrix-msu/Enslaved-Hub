<?php

$tempQuery = <<<QUERY
SELECT ?name ?desc ?project ?pname ?type ?secondarysource

 WHERE
{
 VALUES ?source { $wd:$qid } #Q number needs to be changed for every source.
  ?source $wdt:$instanceOf $wd:$entityWithProvenance;
         $wdt:$generatedBy ?project.
  ?project $rdfs:label ?pname.

  ?source $rdfs:label ?name.
  ?source $wdt:$hasOriginalSourceType ?sourcetype.
  ?sourcetype $rdfs:label ?type.
  OPTIONAL{?source $wdt:$hasOriginalSourceDepository ?secondarysource}.
  OPTIONAL {?source schema:description ?desc}.

  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}GROUP BY ?name ?desc ?project ?pname ?type ?secondarysource
QUERY;
