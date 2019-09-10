<?php

$tempQuery = <<<QUERY
SELECT (COUNT(DISTINCT ?place) as ?count)
WHERE {
	?place $wdt:$instanceOf $wd:$place.
	?place $wdt:$hasPlaceType ?type.
	$placeTypeIdFilter
	$sourceIdFilter
}
QUERY;
