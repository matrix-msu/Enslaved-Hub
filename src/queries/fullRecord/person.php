<?php

$tempQuery = <<<QUERY
SELECT ?label ?description ?project ?roles
(group_concat(distinct ?name1; separator = "||") as ?name)
(group_concat(distinct ?altname1; separator = "||") as ?altname)
(group_concat(distinct ?age1; separator = "||") as ?age)
(group_concat(distinct ?agerecordedat1; separator = "||") as ?agerecordedat)
(group_concat(distinct ?agerecordedatlabel1; separator = "||") as ?agerecordedatlabel)
(group_concat(distinct ?firstname1; separator = "||") as ?firstname)
(group_concat(distinct ?surname1; separator = "||") as ?surname)
(group_concat(distinct ?sextype1; separator = "||") as ?sextype)
(group_concat(distinct ?race1; separator = "||") as ?race)
(group_concat(distinct ?statuslabel; separator = "||") as ?status)
(group_concat(distinct ?ecvo1; separator = "||") as ?ecvo)
(group_concat(distinct ?placeofOrigin1; separator = "||") as ?placeofOrigin)
(group_concat(distinct ?placeOriginlabel1; separator = "||") as ?placeOriginlabel)
(group_concat(distinct ?occupationlabel; separator = "||") as ?occupation)
(group_concat(distinct ?descOccupation; separator = "||") as ?descriptive_Occupation)
(group_concat(distinct ?roleevent1; separator = "||") as ?roleevent)
(group_concat(distinct ?roleeventlabel1; separator = "||") as ?roleeventlabel)
(group_concat(distinct ?drole; separator = "||") as ?droles)
(group_concat(distinct ?droleevent1; separator = "||") as ?droleevent)
(group_concat(distinct ?droleeventlabel1; separator = "||") as ?droleeventlabel)
(group_concat(distinct ?statusevent1; separator = "||") as ?statusevent)
(group_concat(distinct ?eventstatuslabel1; separator = "||") as ?eventstatuslabel)
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
 OPTIONAL{ ?agent $p:$hasAge ?ageuri.
            ?ageuri $ps:$hasAge ?ageuri2.
            ?ageuri2 $wdt:$hasAgeValue ?age1.
            ?ageuri $pq:$recordedAt ?agerecordedat1.
            ?agerecordedat1 $wdt:$hasName ?agerecordedatlabel1}.
 OPTIONAL{?agent $wdt:$hasSex ?sex.
         ?sex $rdfs:label ?sextype1}.
 OPTIONAL{?agent $wdt:$hasRace ?race1}.
 OPTIONAL {?agent $p:$hasEthnolinguisticDescriptor  ?statement.
          ?statement $ps:$hasEthnolinguisticDescriptor ?ethnodescriptor.
          ?ethnodescriptor $rdfs:label ?ecvo1.
          OPTIONAL{?statement $pq:$referstoPlaceofOrigin ?placeofOrigin1.
          ?placeofOrigin1 $wdt:$hasName ?placeOriginlabel1}.
          }.
 OPTIONAL {?agent $wdt:$hasOccupation ?occupation1.
         ?occupation1 $rdfs:label ?occupationlabel}.
OPTIONAL {?agent $wdt:$descriptiveOccupation ?descOccupation}.
OPTIONAL {?agent $p:$hasParticipantRole ?staterole.
         ?staterole $ps:$hasParticipantRole ?role;
                   $pq:$roleProvidedBy ?roleevent1.
         ?role $rdfs:label ?roles.
         ?roleevent1 $wdt:$hasName ?roleeventlabel1.
         bind(?roleevent1 as ?allevents).
      }.
OPTIONAL {?agent $p:$hasDescriptiveRole ?dstaterole.
         ?dstaterole $ps:$hasDescriptiveRole ?drole;
                   $pq:$roleProvidedBy ?droleevent1.
         ?droleevent1 $wdt:$hasName ?droleeventlabel1.
         bind(?droleevent1 as ?allevents).
      }.
 OPTIONAL {?agent $p:$hasPersonStatus ?statstatus.
           ?statstatus $ps:$hasPersonStatus ?status1.
           ?statstatus $pq:$hasStatusGeneratingEvent ?statusevent1.
           ?status1 $rdfs:label ?statuslabel.
           ?statusevent1 $wdt:$hasName ?eventstatuslabel1.
           bind(?statusevent1 as ?allevents)}.
OPTIONAL {?agent $p:$hasName ?statementname.
            ?statementname $ps:$hasName ?person.
            ?statementname $pq:$recordedAt ?rec.
            bind(?rec as ?allevents)}.
BIND(exists{?allevents $wdt:$hasName ?alleventslabel1} as ?alleventslabel1).
OPTIONAL {BIND(exists{?allevents $wdt:$atPlace ?places} as ?places).
          BIND(exists{?allevents $wdt:$hasName ?evlabel} as ?evlabel).
          BIND(exists{?places $wdt:$hasName ?placeslabel} as ?placeslabel).
         BIND(CONCAT(str(?allevents)," - ",str(?placeslabel)) as ?eventplace1).
         }.

OPTIONAL {BIND(exists{?allevents	$wdt:$startsAt ?startdate} as ?startdate).
        BIND(CONCAT(str(?elabel)," - ",str(?etypelabel)," - ",str(YEAR(?startdate))) AS ?startyear1).
        BIND(exists{?allevents $wdt:$endsAt ?enddate} as ?enddate).
                BIND(str(YEAR(?enddate)) AS ?endyear1)}.

OPTIONAL {?agent $p:$hasName ?object .
          ?object $prov:wasDerivedFrom ?provenance .
          ?provenance $pr:$isDirectlyBasedOn ?source .
          ?source $wdt:$generatedBy ?proj.
          ?proj $rdfs:label ?project.
          OPTIONAL {?provenance $pr:$hasExternalReference ?extref1}}.


}GROUP BY ?label ?description ?project ?roles
QUERY;
?>
