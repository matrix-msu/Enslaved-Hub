<?php

$tempQuery = <<<QUERY
SELECT ?place WHERE {
	?place $wdt:$instanceOf $wd:$place.
	?place $wdt:$hasPlaceType ?type.
	$placeTypeIdFilter
	$sourceIdFilter
}
$limitQuery
$offsetQuery
QUERY;
