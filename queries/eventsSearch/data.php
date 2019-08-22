<?php

$tempQuery = <<<QUERY
SELECT ?event ?eventlab ?startyear ?endyear ?type ?eventtypeLabel
 (count(distinct ?people) as ?countpeople)
 (count(distinct ?event) as ?countervent)
 (count(distinct ?place) as ?countplace)
 (count(distinct ?source) as ?countsource)
 (group_concat(distinct ?placelabel; separator = "||") as ?places)
 
 
WHERE {
   VALUES ?event { $qidList }.
    ?event ?property  ?object .
  	?object prov:wasDerivedFrom ?provenance .
  	?provenance pr:$isDirectlyBasedOn ?source .
    ?event rdfs:label ?eventlab.
    ?event wdt:$hasEventType ?type .
    ?type rdfs:label ?eventtypeLabel

  		 
   OPTIONAL {?event wdt:$atPlace ?place.
           ?place rdfs:label ?placelabel}.
  OPTIONAL {?event wdt:$startsAt ?date.
           BIND(str(YEAR(?date)) AS ?startyear)
 	 		OPTIONAL {?event wdt:$endsAt ?endDate
           BIND(str(YEAR(?endDate)) AS ?endyear)}.
            }.
 }GROUP BY ?event ?eventlab ?startyear ?endyear ?type ?eventtypeLabel
QUERY;
