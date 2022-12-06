<?php
// require_once('src/source/config.php');
// require_once('src/source/functionIncluder.php');
require_once('src/source/config.dist.php');
require_once('src/source/functions/functions.php');
require_once('src/source/generatedConstants.php');
require_once('src/source/functions/explorefunctions.php');

$placeQuery['query'] = <<<QUERY
SELECT ?place ?label
WHERE {
  ?place edt:P1 ed:Q303403.
  ?place rdfs:label ?label.
}
QUERY;

// $skipQids = array("Q311","Q343","Q303406","Q487732","Q487684");
$skipQids = array();
$results = blazegraphSearch($placeQuery);
$placeResults = array();
foreach($results as $result){
	$explode = explode('/',$result['place']['value']);
	$qid = array_pop($explode);
	echo $qid. PHP_EOL;
	if(in_array($qid,$skipQids)){
		echo 'skipping'.PHP_EOL.PHP_EOL;
		continue;
	}
	$placeResults[$qid] = getPlacePageConnections($qid, true);
	echo PHP_EOL.PHP_EOL;
}

file_put_contents('prerenderedPlaces.json', json_encode($placeResults));
