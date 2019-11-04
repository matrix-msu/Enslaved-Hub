<?php

$tempQuery = <<<QUERY
SELECT DISTINCT ?source ?sourceLabel ?projectLabel ?secondarysource

 (group_concat(distinct ?sourcetypeLabel; separator = "||") as ?sourcetypeLabel) #source type labels
 (count(distinct ?agent) as ?countpeople)
 (count(distinct ?event) as ?countevent)
 (count(distinct ?place) as ?countplace)
 (count(distinct ?source) as ?countsource)
 (group_concat(distinct ?sourcetype; separator = "||") as ?sourcetype) #source type
{
  VALUES ?source { $qidList }
  ?source $wdt:$generatedBy ?project.
  ?source $rdfs:label ?sourceLabel.
  ?project $rdfs:label ?projectLabel.
  OPTIONAL {?source $wdt:$hasOriginalSourceType ?sourcetype.
         ?sourcetype rdfs:label ?sourcetypeLabel}.

  OPTIONAL{?source $wdt:$reportsOn ?event.
           ?event $wdt:$atPlace ?place.
              ?event $p:$providesParticipantRole ?statement.
              ?statement $ps:$providesParticipantRole ?role.
              ?statement $pq:$hasParticipantRole ?agent}.
  OPTIONAL{?source $wdt:$hasOriginalSourceRepository ?secondarysource}.

}group by ?source ?sourceLabel ?projectLabel ?secondarysource
order by ?sourceLabel
QUERY;
