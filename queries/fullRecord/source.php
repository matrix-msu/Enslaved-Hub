<?php

$tempQuery = <<<QUERY
SELECT
 ?label ?name ?project ?pname ?type ?secondarysource ?description
(group_concat(distinct ?extref1; separator = "||") as ?extref)

 WHERE
{
 VALUES ?source { $wd:$qid }.
    OPTIONAL{ ?source $wdt:$hasDescription ?description}.

     OPTIONAL{
         ?source $wdt:$generatedBy ?project.
         ?project $rdfs:label ?pname
     }.

     OPTIONAL {?source $wdt:$hasExternalReference ?extref1}

  ?source $rdfs:label ?label.
  OPTIONAL{
      ?source $wdt:$hasOriginalSourceType ?sourcetype.
      ?sourcetype $rdfs:label ?type
  }.
  OPTIONAL{?source $wdt:$reportsOn ?event}.

  OPTIONAL{?source $wdt:$availableFrom ?secondarysource}.
  OPTIONAL {?source edt:P20 ?name}.
  OPTIONAL {?event $wdt:$atPlace ?place.}

}GROUP BY ?label ?name ?project ?pname ?type ?secondarysource ?description
QUERY;
