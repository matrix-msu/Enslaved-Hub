<?php

$tempQuery = <<<QUERY
SELECT DISTINCT ?source ?sourceLabel ?projectLabel ?desc ?secondarysource

 (group_concat(distinct ?sourcetypeLabel; separator = "||") as ?sourcetypeLabel) #source type labels
 (count(distinct ?agent) as ?countpeople)
 (count(distinct ?event) as ?countevent)
 (count(distinct ?place) as ?countplace)
 (count(distinct ?source) as ?countsource)
 (group_concat(distinct ?sourcetype; separator = "||") as ?sourcetype) #source type
{
  VALUES ?source { $qidList }
  ?source $wdt:$hasOriginalSourceType ?sourcetype.
  ?source $wdt:$generatedBy ?project.
  ?source $wdt:$reportsOn ?event

  OPTIONAL{?event $wdt:$atPlace ?place}.
  OPTIONAL{?source $wdt:$hasOriginalSourceDepository ?secondarysource}.
  OPTIONAL {?source schema:description ?desc}.
  ?agent ?property  ?object .
  ?object $prov:wasDerivedFrom ?provenance .
  ?provenance $pr:$isDirectlyBasedOn ?source .
  ?source $rdfs:label ?sourceLabel.
  ?project $rdfs:label ?projectLabel.
  ?sourcetype $rdfs:label ?sourcetypeLabel

}group by ?source ?sourceLabel ?projectLabel ?desc ?secondarysource
order by ?sourceLabel

QUERY;
