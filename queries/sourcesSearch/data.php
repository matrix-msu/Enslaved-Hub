<?php

$tempQuery = <<<QUERY
SELECT DISTINCT ?source ?sourceLabel ?projectLabel ?sourcetypeLabel ?secondarysource ?desc
 
 (count(distinct ?agent) as ?countpeople)
 (count(distinct ?event) as ?countervent)
 (count(distinct ?place) as ?countplace)
 (count(distinct ?source) as ?countsource)
{
  VALUES ?source { $qidList }
  ?source $wdt:$hasOriginalSourceType ?sourcetype.
  ?source $wdt:$generatedBy ?project.
  OPTIONAL{?source $wdt:$reportsOn ?event}.  # TODO: DO NOT KEEP AS OPTIONAL
  OPTIONAL{?event $wdt:$atPlace ?place}.
  OPTIONAL{?source $wdt:$hasOriginalSourceDepository ?secondarysource}.
  OPTIONAL {?source schema:description ?desc}.

  ?agent $wdt:$instanceOf/$wdt:$subclassOf $wd:$agent; #agent or subclass of agent
  		?property  ?object .
  ?object $prov:wasDerivedFrom ?provenance .
  ?provenance $pr:$isDirectlyBasedOn ?source .
  
 
   SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" . }
           
}group by ?source ?sourceLabel ?projectLabel ?sourcetypeLabel ?secondarysource ?desc
order by ?sourceLabel

QUERY;
