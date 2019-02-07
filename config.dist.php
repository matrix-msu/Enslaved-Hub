<?php

//project specific urls - you should never use relative paths
define("BASE_URL",  "http://dev2.matrix.msu.edu/~noah.girard/enslaved/");
define("BASE_JS_URL", BASE_URL . "assets/javascripts/");
define("BASE_AJAX_URL", BASE_URL . "ajax/");
define("BASE_VIEW_URL", BASE_URL . "views/");
define("BASE_IMAGE_URL", BASE_URL . "assets/images/");
define("BASE_CSS_URL", BASE_URL . "assets/stylesheets/style.css");
define("BASE_MODULE_URL", BASE_URL . "modules/");

//project specific file paths - you should never use relative paths
define("BASE_PATH",  "/matrix/home/noah.girard/public_html/enslaved/");
define("BASE_LIB_PATH", BASE_PATH . "lib/");
define("BASE_FUNCTIONS_PATH", BASE_PATH . "functions/");
define("BASE_VIEW_PATH", BASE_PATH . "views/");
define("BASE_MODULE_PATH", BASE_PATH . "modules/");

//kora urls - you shouldn't need to change these
define("KORA_FILES_URL"     ,  "http://dev2.matrix.msu.edu/k3beta/public/app/files/");
define ("KORA_BASE_URL", "https://kora3.matrix.msu.edu/api/");
define ("KORA_SEARCH_URL", KORA_BASE_URL . "search");

define('API_URL','https://sandro-33.matrix.msu.edu/sparql?query=');

//database info
define("DATABASE_HOST",  "rush.matrix.msu.edu");
define("DATABASE_USER",  "arcs_dev");
define("DATABASE_DBNAME",  "uohE4n032x");
define("DATABASE_PASSWORD",  "arcs_dev");

//kora project information - change these for your project
$GLOBALS['PID_ARRAY'] = array(
    11
);
$GLOBALS['PROJECT_SID_ARRAY'] = array(
    11 => 31,
);
$GLOBALS['SEASON_SID_ARRAY'] = array(
    11 => 32,
);
$GLOBALS['SURVEY_SID_ARRAY'] = array(
    11 => 36,
);
$GLOBALS['RESOURCE_SID_ARRAY'] = array(
    11 => 33,
);
$GLOBALS['PAGES_SID_ARRAY'] = array(
    11 => 34,
);
$GLOBALS['SUBJECT_SID_ARRAY'] = array(
    11 => 35,
);
$GLOBALS['TOKEN_ARRAY'] = array(
    11 => "GPjkwxHb2zMss16JEmca2cg0",
);

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

//include the lib files
require_once( BASE_LIB_PATH . "configFunctions.php" );
require_once( BASE_LIB_PATH . "koraWrapper.php" );
require_once( BASE_LIB_PATH . "mySqlWrapper.php" );
require_once( BASE_FUNCTIONS_PATH . "explorefunctions.php");


//require the routes file
require_once( "routes.php" );
