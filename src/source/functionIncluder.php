<?php
//include the lib files
require_once( BASE_LIB_PATH . "configFunctions.php" );
require_once( BASE_LIB_PATH . "koraWrapper.php" );
require_once( BASE_LIB_PATH . "mySqlWrapper.php" );
require_once( BASE_FUNCTIONS_PATH . "explorefunctions.php");
require_once( BASE_FUNCTIONS_PATH . "storyfunctions.php");
require_once( BASE_FUNCTIONS_PATH . "functions.php");
require_once( BASE_FUNCTIONS_PATH . "search.php");
require_once( BASE_PATH . "generatedConstants.php");

//includes all the php files from constants directory
foreach(glob($GLOBALS['CONSTANTS_FILE_ARRAY'][LOD_CONFIG] . "/*.php") as $file){
    require_once $file;
}

$GLOBALS['FILTER_TO_FILE_MAP'] = Array(
    "Gender" => sexTypes,
    "Age Category" => ageCategory,
    "Ethnodescriptor" => ethnodescriptor,
    "Role Types" => roleTypes,
    "Event Type" => eventTypes,
    "Place Type" => placeTypes,
    "Source Type" => sourceTypes,
    "Status" => personstatus,
    "Occupation" => occupation,
    "Projects" => projects,
    "Modern Countries" => countrycode
);
