<?php
ini_set("memory_limit", "-1");
set_time_limit(0);

$urlPath = "/~christj2/enslaved-project-suite/build_local";
define("BASE_URL",  "https://robbie.dev.matrix.msu.edu".$urlPath."/");
define("BASE_PATH",  "/home/christj2/website/enslaved-project-suite/");
define("KORA_BASE_URL", "https://kora.enslaved.org/");

//project specific urls
define("BASE_JS_URL", BASE_URL . "assets/build/js/");
define("BASE_AJAX_URL", BASE_URL . "ajax/");
define("BASE_VIEW_URL", BASE_URL . "views/");
define("BASE_IMAGE_URL", BASE_URL . "assets/build/images/");
define("BASE_CSS_URL", BASE_URL . "assets/build/css/");
define("BASE_MODULE_URL", BASE_URL . "modules/");

//kora urls
define("KORA_SEARCH_URL", KORA_BASE_URL . "api/search");
define("KORA_FILES_URL",   KORA_BASE_URL . "public/app/files/");

//kora config
define("TOKEN", "5ea9a27313a10");
define("PROJECT_ID", 43);
define("CONFIG_FID", 84);
define("WEBPAGES_FID", 85);
define("ESSAYS_FID", 81);
define("DESIGN_FID", 146);

include('koraApiWrapper/koraWrapper.php');
require_once('./functions/configFunctions.php');
require_once('./functions/functions.php');
require_once('./functions/cardSliderAll.php');
require_once('./functions/essaysfunctions.php');
require_once('./functions/fullrecordfunctions.php');
require_once('./functions/searchfunctions.php');
require_once('./functions/dynamicStyles.php');

define("WEBPAGES_DATA", Kora_GetWebpagesData());
define("KORA_CONFIG_DATA", Kora_GetConfigData());
define("ALL_ESSAYS", getAllEssaysUnformatted());
define("ALL_RECORDS", getAllRecordsUnformatted());
define("ALL_EVENTS", getAllEventsUnformatted());
define("ALL_PLACES", getAllPlacesUnformatted());
define("ALL_OBJECTS", getAllObjectsUnformatted());

$fileName="./source/assets/build/js/searchData.js";
$script =
    "var allPeopleRecords = JSON.parse('".addslashes(json_encode(array_values(ALL_RECORDS), true))."');".
    "var allEventRecords = JSON.parse('".addslashes(json_encode(array_values(ALL_EVENTS), true))."');".
    "var allPlaceRecords = JSON.parse('".addslashes(json_encode(array_values(ALL_PLACES), true))."');".
    "var allObjectRecords = JSON.parse('".addslashes(json_encode(array_values(ALL_OBJECTS), true))."');";
file_put_contents($fileName, $script);
$gzScript = gzencode($script);
file_put_contents($fileName.'.gz', $gzScript);

$searchFilterData = searchFilterMenu();
define("FILTER_HTML", $searchFilterData['filterHtml']);
$fileName="./source/assets/build/js/searchFilterData.js";
$script =
    "const searchFilterData = JSON.parse('".addslashes(json_encode($searchFilterData))."');";
file_put_contents($fileName, $script);

$designData = koraWrapperSearch(
    DESIGN_FID,
    "ALL"
);
$designData = json_decode($designData, true)['records'][0];
$designData = array_values($designData)[0];
$logoUrl = $designData['Logo'][0]['url'];
define("DESIGN_DATA", $designData);
define('logoUrl', $logoUrl);

$cssFileName="./source/assets/build/css/dynamicStyles.css";
$css = dynamicStyles();
file_put_contents($cssFileName, $css);

define("JS_GLOBALS",
    "<script type='text/javascript'>" .
    "var BASE_URL ='".BASE_URL."';" .
    "var BASE_JS_URL ='".BASE_JS_URL."';" .
    "var BASE_CSS_URL ='".BASE_CSS_URL."';" .
    "var BASE_AJAX_URL ='".BASE_AJAX_URL."';" .
    "var BASE_VIEW_URL ='".BASE_VIEW_URL."';" .
    "var BASE_IMAGE_URL ='".BASE_IMAGE_URL."';" .
    "</script>"
);

return [
    'production' => false,
    'baseUrl' => $urlPath,
    'title' => 'Enslaved Project Suite',
    'description' => 'Test project for website builder',
    'collections' => [
        'posts' => [
            'extends' => '_layouts.post',
            'path' => 'fullEssay/id={title}',
            'items' => getAllEssays(),
        ],
        'fullrecords' => [
            'extends' => '_layouts.fullRecord',
            'path' => 'explore/id={title}',
            'items' => getAllRecordKids(),
        ],
    ],
];
