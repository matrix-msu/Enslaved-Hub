<?php

$tempQuery = <<<QUERY
SELECT
 ?label ?name ?project ?pname ?type ?availableFrom ?description ?projref ?dateStart ?endsAt
(group_concat(distinct ?extref1; separator = "||") as ?extref)

 WHERE
{
 VALUES ?source { $wd:$qid }.
    OPTIONAL{ ?source $wdt:$hasDescription ?description}.

     OPTIONAL{
         ?source $wdt:$generatedBy ?project.
         ?project $wdt:$hasName ?pname.
         ?project $wdt:$hasExternalReference ?projref
     }.

     OPTIONAL {?source $wdt:$hasExternalReference ?extref1}.

  ?source $rdfs:label ?label.
  OPTIONAL{
      ?source $wdt:$hasOriginalSourceType ?sourcetype.
      ?sourcetype $rdfs:label ?type
  }.
  OPTIONAL{?source $wdt:$reportsOn ?event}.

  OPTIONAL{?source $wdt:$availableFrom ?availableFrom}.
  OPTIONAL {?source $wdt:$hasName ?name}.
  OPTIONAL {?event $wdt:$atPlace ?place.}

  OPTIONAL{
  ?source $wdt:$startsAt ?datetime.
  ?source $p:$startsAt/$psv:$startsAt ?node.
  ?node wikibase:timePrecision ?precision .
  BIND(IF(?precision=9,YEAR(?datetime),IF(?precision=10,MONTH(?datetime),xsd:date(?datetime))) AS ?dateStart)}.

  OPTIONAL{
  ?source $wdt:$endsAt ?datetime2.
  ?source $p:$endsAt/$psv:$endsAt ?node2.
  ?node2 wikibase:timePrecision ?precision2 .
  BIND(IF(?precision2=9,YEAR(?datetime2),IF(?precision2=10,MONTH(?datetime2),xsd:date(?datetime2))) AS ?endsAt)}.

}GROUP BY ?label ?name ?project ?pname ?type ?availableFrom ?description ?projref ?dateStart ?endsAt
QUERY;
