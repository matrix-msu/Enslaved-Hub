<?php

$tempQuery = <<<QUERY
SELECT ?place WHERE {
	?place wdt:$instanceOf wd:$place.
	$placeTypeIdFilter
}
$limitQuery
$offsetQuery
QUERY;
