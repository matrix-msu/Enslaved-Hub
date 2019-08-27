<?php

$tempQuery = <<<QUERY
SELECT ?place WHERE {
	?place wdt:$instanceOf wd:$place.
	?place wdt:$hasPlaceType ?type.
	$placeTypeIdFilter
}
$limitQuery
$offsetQuery
QUERY;
