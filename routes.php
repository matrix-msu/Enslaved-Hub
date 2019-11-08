<?php

$GLOBALS['api_routes'] = array(
    'api/getWebPages' => array('configFunctions.php', 'Kora_GetNavigationData'),
    'api/testing' => array('functions.php', 'testingFunction'),
    'api/printEpisodes' => array('functions.php', 'printEpisodes'),
    'api/admin' => array('functions.php', 'admin'),
    'api/blazegraph' => array('functions.php', 'blazegraph'),
    'api/updateConstants' => array('functions.php', 'updateConstants'),
	'api/counterOfGender' => array('explorefunctions.php', 'counterOfGender'),
	'api/counterOfType' => array('explorefunctions.php', 'counterOfType'),
	'api/getSearchFilterCounters' => array('explorefunctions.php', 'getSearchFilterCounters'),
	'api/getHomePageCounters' => array('explorefunctions.php', 'getHomePageCounters'),
	'api/getFullRecordHtml' => array('explorefunctions.php', 'getFullRecordHtml'),
    'api/getDateRange' => array('explorefunctions.php', 'getEventDateRange'),
    'api/getProjectFullInfo' => array('explorefunctions.php', 'getProjectFullInfo'),
    'api/getFullRecordConnections' => array('explorefunctions.php', 'getFullRecordConnections'),
    'api/getCrawlerResults' => array('crawler_jquery.php', ''),
    'api/getQidValue' => array('functions.php','getQidValue')
);

$GLOBALS['routes'] = array(
    '' => 'home.php',
    'essays' => 'essays.php',
    'browse' => 'browse.php',
    'blog' => 'blog.php',
    'search' => 'search.php',
    'searchResults' => 'searchResults.php',
    'carousel' => 'carousel.php',
    'contributors' => 'contributors.php',
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
	'ourPartners' => 'ourPartners.php',
    'contactUs' => 'contactUs.php',
    'ourTeam' => 'ourTeam.php',
    'references' => 'references.php',
    'advancedSearch' => 'advancedSearch.php',
    'fullStory' => 'fullStory.php',
    'project' => 'fullProject.php',
    'timeSub' => 'timeSub.php',
    'enslavedOntology' => 'ontology.php',
    'projects' => 'projects.php',
    'crawler' => 'crawler.php',
    'current' => 'current.php',
    'links' => 'links.php',
    'directory' => 'scholarProjectDirectory.php'
);


function url_origin( $s, $use_forwarded_host = false )
{
    $ssl      = true;
    $sp       = strtolower( $s['SERVER_PROTOCOL'] );
    $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
    $port     = $s['SERVER_PORT'];
    $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
    $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
    $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
}

function full_url( $s, $use_forwarded_host = false )
{
    return url_origin( $s, $use_forwarded_host ) . $s['REQUEST_URI'];
}
$actualLink = full_url( $_SERVER );

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
if($fileArray[0] == 'explore' && count($fileArray) > 1 && $fileArray[1] == 'sources'){
    $fileArray[2] = 'source_type';
}
if($fileArray[0] == 'explore' && count($fileArray) > 1 && $fileArray[1] == 'places'){
    $fileArray[2] = 'place_type';
}

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

}elseif ($fileArray[0] == 'explore' && count($fileArray) > 1){ //form
    define('EXPLORE_FORM', $fileArray[1]);
    $currentFile = 'exploreForm';
    $EXPLORE_JS_VARS = "<script type='text/javascript'>var JS_EXPLORE_FORM = '".ucwords(str_replace("_", " ", EXPLORE_FORM))."';</script>\n";
}elseif ($fileArray[0] == 'search' && count($fileArray) > 1){ //search
    define('EXPLORE_FORM', $fileArray[1]);
    $currentFile = 'exploreResults';
    $EXPLORE_JS_VARS = "<script type='text/javascript'>var JS_EXPLORE_FORM = '".ucwords(str_replace("_", " ", EXPLORE_FORM))."';</script>\n";
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
