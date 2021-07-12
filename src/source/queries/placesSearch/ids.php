<?php

$tempQuery = <<<QUERY
SELECT DISTINCT ?place WHERE {
	?place $wdt:$instanceOf $wd:$place.
	?place $wdt:$hasPlaceType ?type.
	$queryFilters
	$sourceIdFilter
	$personIdFilter
}
$limitQuery
$offsetQuery
QUERY;
