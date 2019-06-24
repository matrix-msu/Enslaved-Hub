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
	$koraResults = koraWrapperSearch(WEBPAGES_FORM, "ALL", array("Display_16_49_"), "TRUE", array('NavigationOrder_16_49_','ASC','SubNavigationOrder_16_49_','ASC'));
	
	// Error checking
	if(!$koraResults) return json_encode("failed");
	$decode_results = json_decode($koraResults, true);
	if(array_key_exists("error", $decode_results)) return json_encode("failed");

	// Read from the webPages file and compare to the kora results
	$cached_data = file_get_contents(BASE_PATH . "/wikiconstants/webPages.json");
	if($cached_data == json_encode(json_decode($koraResults)->records[0])) return json_encode("similar");

	// put content to webPages.json file
	file_put_contents( BASE_PATH . "/wikiconstants/webPages.json", json_encode(json_decode($koraResults)->records[0]));

	$navs = [];
	$prev = "";
	$index = -1;

	// Extract All main navigations and sub navigations
	foreach ($decode_results["records"][0] as $result)
	{
		if(!array_key_exists("Navigation", $result)) continue;
		$nav = $result["Navigation"]["value"][0];

		if($nav != $prev)
		{
			array_push($navs, [$nav, []]);
			$prev = $nav;
			$index ++;
		}

		if(!array_key_exists("SubNavigation", $result) || array_key_exists("SubNavigation Display", $result) && 
			$result["SubNavigation Display"]["value"] == "FALSE") continue;
		
		array_push($navs[$index][1], $result["SubNavigation"]["value"]);
	}
	// echo '<script>console.log('.json_encode($navs).')</script>';
	// put navigations to navContents.json file
	file_put_contents( BASE_PATH . "/wikiconstants/navContents.json", json_encode($navs));

	return json_encode("updated");
}

// Read navigations from file navContents.json
function Json_GetNavigationData()
{
	// Read from Json File
	$navContents = file_get_contents(BASE_PATH . "/wikiconstants/navContents.json");
	// echo '<script>console.log('.$navContents.')</script>';
	$navContents = json_decode($navContents, true);
	return $navContents;
}