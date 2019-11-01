<?php

$tempQuery = <<<QUERY
SELECT
(group_concat(distinct ?name; separator = "||") as ?name)
(group_concat(distinct ?sextype; separator = "||") as ?sextype)
(group_concat(distinct ?race; separator = "||") as ?race)

(group_concat(distinct ?refName; separator = "||") as ?sources)
(group_concat(distinct ?pname; separator = "||") as ?researchprojects)
(group_concat(distinct ?roleslabel; separator = "||") as ?roleslabel)

(group_concat(distinct ?roleevent; separator = "||") as ?roleevent1)
(group_concat(distinct ?roleeventlabel; separator = "||") as ?roleeventlabel1)
(group_concat(distinct ?statuslabel; separator = "||") as ?status)
(group_concat(distinct ?statusevent; separator = "||") as ?statusevent)
(group_concat(distinct ?eventstatuslabel; separator = "||") as ?eventstatuslabel)

(group_concat(distinct ?ecvo; separator = "||") as ?ecvo)
(group_concat(distinct ?placeofOrigin; separator = "||") as ?placeofOrigin)
(group_concat(distinct ?placeOriginlabel; separator = "||") as ?placeOriginlabel)

(group_concat(distinct ?occupationlabel; separator = "||") as ?occupation)
(group_concat(distinct ?relationslabel; separator = "||") as ?relationships)
(group_concat(distinct ?relationname; separator = "||") as ?qrelationname)
(group_concat(distinct ?relationagentlabel; separator = "||") as ?relationagentlabel)
(group_concat(distinct ?match; separator = "||") as ?match)
(group_concat(distinct ?matchlabel; separator = "||") as ?matchlabel)
(group_concat(distinct ?allevents; separator = "||") as ?allevents)
(group_concat(distinct ?allevents1; separator = "||") as ?allevents1)

(group_concat(distinct ?alleventslabel; separator = "||") as ?alleventslabel)
(group_concat(distinct ?startyear; separator = "||") as ?startyear)
(group_concat(distinct ?endyear; separator = "||") as ?endyear)
(group_concat(distinct ?allplaces; separator = "||") as ?allplaces)
(group_concat(distinct ?allplaceslabel; separator = "||") as ?allplaceslabel)
(group_concat(distinct ?placetype; separator = "||") as ?placetype)
(group_concat(distinct ?eventplace; separator = "||") as ?eventplace)


 WHERE
{
 VALUES ?agent { $wd:$qid }. #Q number needs to be changed for every event.
  ?agent ?property  ?object .
  ?object $prov:wasDerivedFrom ?provenance .
  ?provenance $pr:$isDirectlyBasedOn ?source .
  ?source $rdfs:label ?refName;
  OPTIONAL {?source $wdt:$generatedBy ?project.
  			?project $rdfs:label ?pname}.

 ?agent $wdt:$hasName ?name.

  OPTIONAL{?agent $wdt:$hasSex ?sex.
          ?sex $rdfs:label ?sextype}.
  OPTIONAL{?agent $wdt:$hasRace ?race}.

  OPTIONAL {?agent $wdt:$hasPersonStatus ?status.
           ?status $rdfs:label ?statuslabel}.

  OPTIONAL {?agent $p:$hasECVO ?statement.
           ?statement $ps:$hasECVO ?ethnodescriptor.
           ?ethnodescriptor $rdfs:label ?ecvo.
           OPTIONAL{?statement $pq:$referstoPlaceofOrigin ?placeofOrigin.
           ?placeofOrigin $rdfs:label ?placeOriginlabel}.
           }.
  OPTIONAL {?agent $wdt:$hasOccupation ?occupation.
          ?occupation $rdfs:label ?occupationlabel}.

    OPTIONAL {?agent $p:$hasParticipantRole ?staterole.
            ?staterole $ps:$hasParticipantRole ?roles;
                      $pq:$roleProvidedBy ?roleevent.

                  ?roles $rdfs:label ?roleslabel.
                  ?roleevent $rdfs:label ?roleeventlabel.
                  bind(?roleevent as ?allevents).
         }.

 OPTIONAL {?agent $p:$hasPersonStatus ?statstatus.
             ?statstatus $ps:$hasPersonStatus ?status.
           ?status $rdfs:label ?statuslabel.
           ?statstatus $pq:$hasStatusGeneratingEvent ?statusevent.
     ?statusevent $rdfs:label ?eventstatuslabel.
      bind(?statusevent as ?allevents)}.

      OPTIONAL {?agent $p:$instanceOf ?statementname.
                  ?statementname $ps:$instanceOf ?person.
                  ?statementname $pq:$recordedAt ?rec.
                  bind(?rec as ?allevents)}.

  OPTIONAL{
    ?agent $p:$hasInterAgentRelationship ?staterel .
	?staterel $ps:$hasInterAgentRelationship ?relations .
  	?relations $rdfs:label ?relationslabel.
	?staterel $pq:$isRelationshipTo ?relationname.
  	?relationname $rdfs:label ?relationagentlabel}.
  OPTIONAL {?agent $wdt:$closeMatch ?match.
            ?match $rdfs:label ?matchlabel}.
  ?allevents $rdfs:label ?alleventslabel.
  OPTIONAL {?allevents $wdt:$atPlace ?allplaces.
            ?allevents $rdfs:label ?evlabel.
            ?allplaces $rdfs:label ?allplaceslabel.
?allplaces $wdt:$hasPlaceType ?allplacetypes.
?allplacetypes $rdfs:label ?allplacetypeslabel.
           BIND(CONCAT(str(?allplaces)," - ",str(?allplacetypeslabel)) as ?placetype).
           BIND(CONCAT(str(?allevents)," - ",str(?allplaceslabel)) as ?eventplace).
           }.

  OPTIONAL {?allevents	wdt:$startsAt ?startdate.
            ?allevents $rdfs:label ?elabel.
            ?allevents $wdt:$hasEventType ?etype.
            ?etype $rdfs:label ?etypelabel.
           BIND(CONCAT(str(?elabel)," - ",str(?etypelabel)," - ",str(YEAR(?startdate))) AS ?startyear).
           OPTIONAL {?allevents $wdt:$endsAt ?enddate.
                   BIND(str(YEAR(?enddate)) AS ?endyear)}}.
}
QUERY;
