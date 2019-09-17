<?php

$tempQuery = <<<QUERY
SELECT 
(count(distinct ?people) as ?countpeople)
(count(distinct ?event) as ?countevent)
(count(distinct ?place) as ?countplace)
?name ?desc ?project ?pname ?type ?secondarysource
 WHERE
{
 VALUES ?source { $wd:$qid } #Q number needs to be changed for every source.
  ?source $wdt:$generatedBy ?project.
  ?project $rdfs:label ?pname.
  ?source $rdfs:label ?name.
  ?source $wdt:$hasOriginalSourceType ?sourcetype.
  ?sourcetype $rdfs:label ?type.
  ?source $wdt:$reportsOn ?event.

  OPTIONAL{?source $wdt:$hasOriginalSourceDepository ?secondarysource}.
  OPTIONAL {?source schema:description ?desc}.
  OPTIONAL {?event $wdt:$atPlace ?place.}
 
  ?people ?property  ?object .
        ?object $prov:wasDerivedFrom ?provenance .
        ?provenance $pr:$isDirectlyBasedOn ?source .

}GROUP BY ?name ?desc ?project ?pname ?type ?secondarysource
QUERY;
