<?php

$tempQuery = <<<QUERY
SELECT ?label ?description ?project
(group_concat(distinct ?name1; separator = "||") as ?name)
(group_concat(distinct ?altname1; separator = "||") as ?altname)
(group_concat(distinct ?age1; separator = "||") as ?age)
(group_concat(distinct ?firstname1; separator = "||") as ?firstname)
(group_concat(distinct ?surname1; separator = "||") as ?surname)
(group_concat(distinct ?sextype1; separator = "||") as ?sextype)
(group_concat(distinct ?race1; separator = "||") as ?race)
(group_concat(distinct ?statuslabel; separator = "||") as ?status)
(group_concat(distinct ?ecvo1; separator = "||") as ?ecvo)
(group_concat(distinct ?placeofOrigin1; separator = "||") as ?placeofOrigin)
(group_concat(distinct ?placeOriginlabel1; separator = "||") as ?placeOriginlabel)
(group_concat(distinct ?occupationlabel; separator = "||") as ?occupation)
(group_concat(distinct ?roleslabel1; separator = "||") as ?roles)
(group_concat(distinct ?roleevent1; separator = "||") as ?roleevent)
(group_concat(distinct ?roleeventlabel1; separator = "||") as ?roleeventlabel)
#(group_concat(distinct ?statuslabel1; separator = "||") as ?statuslabel)
(group_concat(distinct ?statusevent1; separator = "||") as ?statusevent)
(group_concat(distinct ?eventstatuslabel1; separator = "||") as ?eventstatuslabel)
(group_concat(distinct ?relationslabel; separator = "||") as ?relationships)
(group_concat(distinct ?relationname; separator = "||") as ?qrelationname)
(group_concat(distinct ?relationagentlabel1; separator = "||") as ?relationagentlabel)
(group_concat(distinct ?match1; separator = "||") as ?match)
(group_concat(distinct ?matchlabel1; separator = "||") as ?matchlabel)
(group_concat(distinct ?allevents; separator = "||") as ?allevents1)
(group_concat(distinct ?alleventslabel1; separator = "||") as ?alleventslabel)
(group_concat(distinct ?places; separator = "||") as ?allplaces)
(group_concat(distinct ?placeslabel; separator = "||") as ?allplaceslabel)
(group_concat(distinct ?eventplace1; separator = "||") as ?eventplace)
(group_concat(distinct ?startyear1; separator = "||") as ?startyear)
(group_concat(distinct ?endyear1; separator = "||") as ?endyear)
(group_concat(distinct ?extref1; separator = "||") as ?extref)

 WHERE
{
 VALUES ?agent { $wd:$qid }.
  ?agent $rdfs:label ?label.
  ?agent $wdt:$hasName ?name1.
 OPTIONAL{ ?agent $wdt:$hasAlternateName ?altname1}.
 OPTIONAL{ ?agent $wdt:$hasFirstName ?firstname1}.
 OPTIONAL{ ?agent $wdt:$hasSurname ?surname1}.
 OPTIONAL{ ?agent $wdt:$hasDescription ?description}.
 OPTIONAL{ ?agent $wdt:$hasAge ?ageuri.
            ?ageuri $wdt:$hasAgeValue ?age1}.
 OPTIONAL{?agent $wdt:$hasSex ?sex.
         ?sex $rdfs:label ?sextype1}.
 OPTIONAL{?agent $wdt:$hasRace ?race1}.
 OPTIONAL {?agent $p:$hasEthnolinguisticDescriptor  ?statement.
          ?statement $ps:$hasEthnolinguisticDescriptor ?ethnodescriptor.
          ?ethnodescriptor $rdfs:label ?ecvo1.
          OPTIONAL{?statement $pq:$referstoPlaceofOrigin ?placeofOrigin1.
          ?placeofOrigin1 $rdfs:label ?placeOriginlabel1}.
          }.
 OPTIONAL {?agent $wdt:$hasOccupation ?occupation1.
         ?occupation1 $rdfs:label ?occupationlabel}.

OPTIONAL {?agent $p:$hasParticipantRole ?staterole.
         ?staterole $ps:$hasParticipantRole ?role;
                   $pq:$roleProvidedBy ?roleevent1.
         ?role $rdfs:label ?roleslabel1.
         ?roleevent1 $rdfs:label ?roleeventlabel1.
         bind(?roleevent1 as ?allevents).
      }.
 OPTIONAL {?agent $p:$hasPersonStatus ?statstatus.
           ?statstatus $ps:$hasPersonStatus ?status1.
           ?statstatus $pq:$hasStatusGeneratingEvent ?statusevent1.
           ?status1 $rdfs:label ?statuslabel.
           ?statusevent1 $rdfs:label ?eventstatuslabel1.
           bind(?statusevent1 as ?allevents)}.
OPTIONAL {?agent $p:$hasName ?statementname.
            ?statementname $ps:$hasName ?person.
            ?statementname $pq:$recordedAt ?rec.
            bind(?rec as ?allevents)}.
OPTIONAL{
          ?agent $p:$hasInterAgentRelationship ?staterel .
          ?staterel $ps:$hasInterAgentRelationship ?relations .
        	?relations $rdfs:label ?relationslabel.
          ?staterel $pq:$isRelationshipTo ?relationname.
        	?relationname $rdfs:label ?relationagentlabel1}.
OPTIONAL {?agent $wdt:$closeMatch ?match1.
          ?match1 $rdfs:label ?matchlabel1}.
?allevents $rdfs:label ?alleventslabel1.
OPTIONAL {?allevents $wdt:$atPlace ?places.
          ?allevents $rdfs:label ?evlabel.
          ?places $rdfs:label ?placeslabel.
         BIND(CONCAT(str(?allevents)," - ",str(?placeslabel)) as ?eventplace1).
         }.

OPTIONAL {?allevents	$wdt:$startsAt ?startdate.
        BIND(CONCAT(str(?elabel)," - ",str(?etypelabel)," - ",str(YEAR(?startdate))) AS ?startyear1).
        OPTIONAL {?allevents $wdt:$endsAt ?enddate.
                BIND(str(YEAR(?enddate)) AS ?endyear1)}}.

OPTIONAL {?agent $p:$hasName ?object .
          ?object $prov:wasDerivedFrom ?provenance .
          ?provenance $pr:$isDirectlyBasedOn ?source .
          ?source $wdt:$generatedBy ?proj.
          ?proj $rdfs:label ?project.
          OPTIONAL {?provenance $pr:$hasExternalReference ?extref1}}.


}GROUP BY ?label ?description ?project
QUERY;
?>
