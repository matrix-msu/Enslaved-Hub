<?php

$tempQuery = <<<QUERY
SELECT (count(?agent) as ?peoplecount)(count(?event) as ?eventscount)(count(?place) as ?placescount)(count(?source) as ?sourcescount){
  {
     SELECT ?agent WHERE
     {
        ?agent $wdt:$instanceOf/$wdt:$subclassOf $wd:$agent. #agent or subclass of agent
        MINUS{?agent $wdt:$hasParticipantRole $wd:$researcher}
        $peopleFilters
     }
    } UNION {
        SELECT DISTINCT ?event WHERE
        { ?event $wdt:$instanceOf $wd:$event.
         $eventFilters
       }
    }

  UNION {
        SELECT ?place WHERE
        { ?place $wdt:$instanceOf $wd:$place.
         $placeFilters
       }
    }

   UNION {
        SELECT ?source WHERE
        { ?source $wdt:$instanceOf $wd:$entityWithProvenance.
         $sourceFilters
       }
    }
}
QUERY;
