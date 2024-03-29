<?php
/*
 *	Extract data from kora and store data in a json file
 *	Two json Files:
 *		webPages.json => Store all data from kora with Display set to True
 * 		navContents.json => Store a well formated json data of main and sub navigations
*/
function Kora_GetNavigationData()
{
	// Get Kora data with Display set to True
	$koraResults = koraWrapperSearch(
		WEBPAGES_FORM,
		"ALL",
		array("Display"),
		"TRUE",
		array(
			['Navigation Order' => 'ASC'],
			['Sub Navigation Order' => 'ASC']
		)
	);

	// Error checking
	if(!$koraResults) return json_encode("failed");
	$decode_results = json_decode($koraResults, true);
	if(array_key_exists("error", $decode_results)) return json_encode("failed");

	// put content to webPages.json file
	file_put_contents( "./source/cache/webPages.json", json_encode(json_decode($koraResults)->records[0]));
	define("DATA_BY_TITLE", json_encode(json_decode($koraResults)->records[0]));

	$navs = [];
	$found = [];

	// print_r($decode_results["records"][0]);die;
	// Extract All main navigations and sub navigations
	foreach ($decode_results["records"][0] as $result)
	{
		if(!array_key_exists("Navigation", $result) || (array_key_exists("Navigation Order", $result) && $result["Navigation Order"] == 0)) continue;
		$nav = $result["Navigation"][0];

		if(!in_array($nav, $found))
		{
			array_push($navs, [$nav, []]);
			$found[] = $nav;
		}

		if(!array_key_exists("SubNavigation", $result) || array_key_exists("SubNavigation Display", $result) &&
			$result["SubNavigation Display"] == "FALSE" ||  $result["SubNavigation"] == "") continue;

		foreach ($navs as $index => $subArray){
			if ($subArray[0] == $nav){
				array_push($navs[$index][1], $result["SubNavigation"]);
				break;
			}
		}
	}
	// put navigations to navContents.json file
	file_put_contents( "./source/cache/navContents.json", json_encode($navs));
	define("NAV_CONTENT", json_encode($navs));

	return json_encode("updated");
}

// Read navigations from file navContents.json
function Json_GetNavigationData()
{
	// Read data from Json cache File
	if(file_exists(BASE_PATH . "cache/navContents.json") ){ //exists
		$navContents = file_get_contents(BASE_PATH . "cache/navContents.json");
	}else{  //doesn't exist
		$navContents = NAV_CONTENT;
	}
	$navContents = json_decode($navContents, true);
	$formattedContents = array();
	foreach( $navContents as $content ){
		if( $content[0] != null ){
			$formattedContents[] = $content;
		}
	}
	//var_dump($formattedContents);die;
	return $formattedContents;
}

function Json_GetData_ByTitle($title, $all_matches = false)
{
	// Dynamically pull data from cache file (webPages.json)
	if(file_exists(BASE_PATH . "cache/webPages.json") ){ //exists
		$cached_data = file_get_contents(BASE_PATH . "cache/webPages.json");
	}else{  //doesn't exist
		$cached_data = DATA_BY_TITLE;
	}
	$cached_data = json_decode($cached_data, true); // Convert the json string to a php array

	$output = ['title' => $title, 'descr' => ""];
	$description = "";
	foreach ($cached_data as $content) {
		// Data for main description field
		if($content["Title"] == "Contribute"){
			if(isset($content["Description"])){
				$explodeDescription = explode('.', $content["Description"],2);
				$output["contributeTitle"] = $explodeDescription[0] .= ".";
				$output["contributeSplit"] = $explodeDescription[1];
			}
		}
	    if($content["Title"] == $title && array_key_exists("SubNavigation Display", $content) && $content["SubNavigation Display"] != "FALSE")
	    {
	    	// Only results for date with provided title
	    	$output['title'] = $content["Title"];
		    $output['descr'] = $content["Description"];

	    	if(!$all_matches) break;
	    	continue;
	    }

	    if(!$all_matches) continue;

		// echo '<br>'.$title;
		// var_dump($content);die;
	    // return all data with provided title and all those navigation equals the provided title
	    if(array_key_exists("Navigation", $content) && isset($content["Navigation"]) && $content["Navigation"][0] == $title)
	    	$output[ $content["Title"]] = $content["Description"];
	}
	return $output;
}
