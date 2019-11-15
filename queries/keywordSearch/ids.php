<?php

$tempQuery = <<<QUERY
SELECT (count(?agent) as ?agentcount)(count(?event) as ?eventcount)(count(?place) as ?placecount)(count(?source) as ?sourcecount){
  {
     SELECT DISTINCT ?agent WHERE
            { ?agent wdt:P82  ?name.
         FILTER regex(?name, "Charles").
         ?agent wdt:P24 wd:Q199. #if somebody typed enslaved we should get enslaved person as a status
         ?agent wdt:P25 wd:Q200. #if somebody typed master or if they type they should get the cv for relationship
         $peopleFilters


       }
    } UNION {
        SELECT DISTINCT ?event WHERE
        { ?event wdt:P3 wd:Q34.
          ?event rdfs:label ?eventname.
         FILTER regex(?eventname, "Charles").
         $eventFilters


       }
    }

  UNION {
        SELECT DISTINCT ?place WHERE
        { ?place wdt:P3 wd:Q50.
          ?place rdfs:label ?placename.
         FILTER regex(?placename, "Charles").
         $placeFilters

       }
    }

   UNION {
        SELECT DISTINCT ?source WHERE
        { ?source wdt:P3 wd:Q16.
          ?source rdfs:label ?sourcename.
         FILTER regex(?sourcename, "Charles").
         $sourceFilters

       }
    }
}
QUERY;
