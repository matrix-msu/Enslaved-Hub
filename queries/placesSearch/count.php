<?php

$tempQuery = <<<QUERY
SELECT DISTINCT (COUNT(?place) as ?count)
WHERE {
	?place wdt:$instanceOf wd:$place.
	$placeTypeIdFilter
}
QUERY;
