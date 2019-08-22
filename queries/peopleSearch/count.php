<?php

$tempQuery = <<<QUERY
SELECT DISTINCT (COUNT(?agent) as ?count)
WHERE {
    ?agent wdt:$instanceOf/wdt:$subclassOf wd:$agent. #agent or subclass of agent
    MINUS{?agent wdt:$hasParticipantRole wd:$researcher}
    $genderIdFilter
    $ageIdFilter
    $ethnoIdFilter
    $roleIdFilter
    $statusIdFilter
    $occupationIdFilter
}
QUERY;
