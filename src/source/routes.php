<?php

$GLOBALS['api_routes'] = array(
    'api/getWebPages' => array('configFunctions.php', 'Kora_GetNavigationData'),
    'api/printEpisodes' => array('functions.php', 'printEpisodes'),
    'api/admin' => array('functions.php', 'admin'),
    'api/blazegraph' => array('functions.php', 'blazegraph'),
    'api/updateConstants' => array('functions.php', 'updateConstants'),
	'api/getFullRecordHtml' => array('explorefunctions.php', 'getFullRecordHtml'),
    'api/getDateRange' => array('search.php', 'get_date_range'),
    'api/getProjectFullInfo' => array('explorefunctions.php', 'getProjectFullInfo'),
    'api/getFullRecordConnections' => array('explorefunctions.php', 'getFullRecordConnections'),
    'api/getCrawlerResults' => array('crawler_jquery.php', ''),
    'api/keywordSearch' => array('search.php', 'get_keyword_search_results'),
    'api/checkCSV' => array('createCSV.php', 'check_csv'),
    'api/downloadCSV' => array('createCSV.php', 'download_csv'),
    'api/getTypeCounts' => array('search.php', 'get_type_counts'),
    'api/filteredCounts' => array('search.php', 'get_field_counts'),
    'api/searchFilterCounts' => array('search.php', 'get_search_filters'),
    'api/getFeatured' => array('search.php', 'get_featured_records'),
    'api/getColumns' => array('search.php', 'get_columns'),
    'api/status' => array('status.php', 'status'),
);

$GLOBALS['routes'] = array(
    '' => 'home.php',
    'essays' => 'essays.php',
    'browse' => 'browse.php',
    'blog' => 'blog.php',
    'search' => 'search.php',
    'searchResults' => 'searchResults.php',
    'carousel' => 'carousel.php',
    'contribute' => 'contributors.php',
    'data' => 'data.php',
    'learn' => 'educators.php',
    'projectHistory' => 'learn.php',
    'fAQ' => 'faq.php',
    'featuredNews' => 'news.php',
    'statementofEthics' => 'ethicsStatement.php',
    'statement%20of%20Ethics' => 'ethicsStatement.php',
    'forEducators' => 'educators.php',
    'drawers' => 'drawers.php',
    'fullRecord' => 'fullrecord.php',
    'fullRecord-2' => 'fullRecord-2.php',
    'imageCardGrid' => 'imageCardGrid.php',
    'imageCardGrid-2' => 'imageCardGrid-2.php',
    'mediaRecords' => 'mediaRecords.php',
    'mediaRecords-2' => 'mediaRecords-2.php',
    'mediaRecord-one-image-ex' => 'mediaRecord-one-image-ex.php',
    'mediaRecord' => 'mediaRecord.php',
    'tabs-2' => 'tabs-2.php',
    'tabs-many' => 'tabs-many.php',
    'text-modal' => 'text-modal.php',
    'text-with-nav' => 'text-with-nav.php',
    'blazegraph' => 'blazegraph.php',
    'admin' => 'admin.php',
    'module-test' => 'module-test.php',
	'searchbar-results' => '../modules/searchbar/searchbar-results.php',
    'explore' => 'explore.php',
    'exploreForm' => 'exploreForm.php',
    'exploreFilters' => 'exploreFilters.php',
    'exploreResults' => 'exploreResults.php',
    'recordForm' => 'exploreRecord.php',
	'stories' => 'stories.php',
	'about' => 'about.php',
	'getInvolved' => 'getInvolved.php',
    'projectSubmission' => 'projectSubmission.php',
    'scholarSubmission' => 'scholarSubmission.php',
	'foundingPartners' => 'foundingPartners.php',
    'contactUs' => 'contactUs.php',
    'ourTeam' => 'ourTeam.php',
    'ontology' => '../ontology/index-en.php',
    'references' => 'references.php',
    'advancedSearch' => 'advancedSearch.php',
    'fullStory' => 'fullStory.php',
    'project' => 'fullProject.php',
    'timeSub' => 'timeSub.php',
    'enslavedOntology' => 'ontology.php',
    'projects' => 'projects.php',
    'crawler' => 'crawler.php',
    'current' => 'current.php',
    'support-our-mission' => 'support-our-mission.php',
    'resources' => 'links.php',
    'visualize' => 'visualize.php',
    'visualizedata' => 'visualizeByData.php'
);

if( !isset($_SERVER['HTTP_HOST']) ){
    define('CURRENT_VIEW', 'home.php');
    return;
}

require_once( BASE_LIB_PATH . "xss.php" );
$_GET = xss_clean($_GET);
$_POST = xss_clean($_POST);
//echo $_GET['test']; //a sample xss test. only uncomment for testing

//$location = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$location = "https://$_SERVER[HTTP_HOST]";
$path = parse_url($_SERVER['REQUEST_URI'])['path'];
$actualLink = $location . $path;
$currentFile = str_replace(BASE_URL, '', $actualLink);
if( substr($currentFile, -1) == '/' ){
    $currentFile = rtrim($currentFile,"/");
}

$filterToFileMap = $GLOBALS['FILTER_TO_FILE_MAP'];

if( $currentFile == 'exploreResults' && isset($_GET)){
    //check if the get value is real.

    if (count($_GET) > 0){
        $filterType = array_keys($_GET)[0];
        $filterVal = $_GET[$filterType];
        $filterType = ucwords(str_replace("_", " ", array_keys($_GET)[0]));

        if (!(array_key_exists($filterType, $filterToFileMap) && array_key_exists($filterVal, $filterToFileMap[$filterType]))){
            $currentFile = '';
        }
    }
}


$fileArray = explode('/', $currentFile);

if ($fileArray[0] == 'record' && count($fileArray) > 2){
    define('RECORD_FORM', $fileArray[1]);
    define('QID', $fileArray[2]);
    $currentFile = 'recordForm';
}
if ($fileArray[0] == 'project'){
    $projectQ = $fileArray[1];
    define('QID', $projectQ);
    $currentFile = $fileArray[0];
}
// Note: this disables the all source and all places page
// if($fileArray[0] == 'explore' && count($fileArray) > 1 && $fileArray[1] == 'sources'){
//     $fileArray[2] = 'source_type';
// }
// if($fileArray[0] == 'explore' && count($fileArray) > 1 && $fileArray[1] == 'places'){
//     $fileArray[2] = 'place_type';
// }

$EXPLORE_JS_VARS = '';
if ($fileArray[0] == 'explore' && count($fileArray) > 2){ //filter
    define('EXPLORE_FORM', $fileArray[1]);
    define('EXPLORE_FILTER', $fileArray[2]);
    $currentFile = 'exploreFilters';
    //GET RID OF THIS AS SOON AS FUNCTIONS TO HANDLE TIME PROPERLY EXIST
    if ($fileArray[2] == "date") {
        $currentFile = 'timeSub';
    }
    $EXPLORE_JS_VARS = "<script type='text/javascript'>var JS_EXPLORE_FORM = '".ucwords(str_replace("_", " ", EXPLORE_FORM))."';var JS_EXPLORE_FILTERS = '".ucwords(str_replace("_", " ", EXPLORE_FILTER))."';</script>\n";

}elseif( $fileArray[0] == 'explore' && count($fileArray) > 1 && $fileArray[1] == 'projects' ){
    $currentFile = 'projects';

}elseif( $fileArray[0] == 'explore' && count($fileArray) > 1 && $fileArray[1] == 'visualizations' ){
    $currentFile = 'visualizedata';

}elseif ($fileArray[0] == 'explore' && count($fileArray) > 1){ //form
    define('EXPLORE_FORM', $fileArray[1]);
    $currentFile = 'exploreForm';
    $EXPLORE_JS_VARS = "<script type='text/javascript'>var JS_EXPLORE_FORM = '".ucwords(str_replace("_", " ", EXPLORE_FORM))."';</script>\n";
}elseif ($fileArray[0] == 'search' && count($fileArray) > 1){ //search
    define('EXPLORE_FORM', $fileArray[1]);
    $currentFile = 'exploreResults';
    $EXPLORE_JS_VARS = "<script type='text/javascript'>var JS_EXPLORE_FORM = '".ucwords(str_replace("_", " ", EXPLORE_FORM))."';</script>\n";
	if($fileArray[1] == 'stories'){
		$currentFile = 'searchStories';
	}
}
define('EXPLORE_JS_VARS', $EXPLORE_JS_VARS);

if( isset($GLOBALS['api_routes'][$currentFile]) ){
    $currentApiFile = $GLOBALS['api_routes'][$currentFile];
    if($currentApiFile[0] == 'configFunctions.php') include_once(BASE_LIB_PATH.$currentApiFile[0]);
    else include_once(BASE_FUNCTIONS_PATH.$currentApiFile[0]);

    if($currentApiFile[1] !== '') echo $currentApiFile[1]();

    die;
}elseif( !isset($GLOBALS['routes'][$currentFile]) ){
    header('HTTP/1.0 404 Not Found');
    define('CURRENT_VIEW', '404.php');
}else{
    define('CURRENT_VIEW', $GLOBALS['routes'][$currentFile]);
}

if($currentFile == 'documentation'){
  header("Location: https://docs.enslaved.org/");
}
