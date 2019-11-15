<?php

$tempQuery = <<<QUERY
SELECT
?name ?project ?pname ?type ?secondarysource
 WHERE
{
 VALUES ?source { $wd:$qid } #Q number needs to be changed for every source.
     OPTIONAL{
         ?source $wdt:$generatedBy ?project.
         ?project $rdfs:label ?pname
     }.

  ?source $rdfs:label ?name.
  OPTIONAL{
      ?source $wdt:$hasOriginalSourceType ?sourcetype.
      ?sourcetype $rdfs:label ?type
  }.
  OPTIONAL{?source $wdt:$reportsOn ?event}.

  OPTIONAL{?source $wdt:$availableFrom ?secondarysource}.
  OPTIONAL {?event $wdt:$atPlace ?place.}

}GROUP BY ?name ?project ?pname ?type ?secondarysource
QUERY;
