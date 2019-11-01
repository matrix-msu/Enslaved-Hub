<?php

$tempQuery = <<<QUERY
SELECT
(count(distinct ?people) as ?countpeople)
(count(distinct ?event) as ?countevent)
(count(distinct ?place) as ?countplace)
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

  OPTIONAL{?source $wdt:$hasOriginalSourceRepository ?secondarysource}.
  OPTIONAL {?event $wdt:$atPlace ?place.}

  ?people ?property  ?object .
        ?object $prov:wasDerivedFrom ?provenance .
        ?provenance $pr:$isDirectlyBasedOn ?source .

}GROUP BY ?name ?project ?pname ?type ?secondarysource
QUERY;
