<?php
//user defined
define("BASE_URL",  "http://dev2.matrix.msu.edu/enslaved/");
define("BASE_PATH",  "/matrix/dev/public_html/enslaved/");
define('BASE_WIKI_URL','https://sandro-16.matrix.msu.edu/');
define('BASE_BLAZEGRAPH_URL', 'https://sandro-33.matrix.msu.edu/');
define("KORA_BASE_URL", "https://enslaved.kora3.matrix.msu.edu/");

//project specific urls - you should never use relative paths
define("BASE_JS_URL", BASE_URL . "assets/javascripts/");
define("BASE_AJAX_URL", BASE_URL . "ajax/");
define("BASE_VIEW_URL", BASE_URL . "views/");
define("BASE_IMAGE_URL", BASE_URL . "assets/images/");
define("BASE_CSS_URL", BASE_URL . "assets/stylesheets/style.css");
define("BASE_MODULE_URL", BASE_URL . "modules/");
define("BASE_LEAFLET_URL", BASE_URL . "assets/leaflet/");

//project specific file paths - you should never use relative paths
define("BASE_LIB_PATH", BASE_PATH . "lib/");
define("BASE_FUNCTIONS_PATH", BASE_PATH . "functions/");
define("BASE_VIEW_PATH", BASE_PATH . "views/");
define("BASE_MODULE_PATH", BASE_PATH . "modules/");

//kora urls - you shouldn't need to change these
define("KORA_SEARCH_URL", KORA_BASE_URL . "api/search");
define("KORA_FILES_URL", KORA_BASE_URL . "public/app/files/");

//wikidata urls
define('WIKI_ENTITY_URL', BASE_WIKI_URL.'entity/');
define('BLAZEGRAPH_URL', BASE_BLAZEGRAPH_URL.'namespace/wdq/sparql');
define('API_URL', BASE_BLAZEGRAPH_URL.'sparql?query=');

//kora project information - change these for your project
define('TOKEN', 'FjOx8EcNE2HS1y3rJiJJ7ha4');
define('STORY_SID', 23);
define('PID', 16);

//useful javascript globals constants and functions
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

//includes all the php files from wikiconstants directory
foreach(glob('wikiconstants' . "/*.php") as $file){
    require_once $file;
}

//include the lib files
require_once( BASE_LIB_PATH . "configFunctions.php" );
require_once( BASE_LIB_PATH . "koraWrapper.php" );
require_once( BASE_LIB_PATH . "mySqlWrapper.php" );
require_once( BASE_FUNCTIONS_PATH . "explorefunctions.php");
require_once( BASE_FUNCTIONS_PATH . "storyfunctions.php");
require_once( BASE_FUNCTIONS_PATH . "functions.php");


//require the routes file
require_once( "routes.php" );
