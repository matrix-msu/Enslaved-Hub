<?php
//user defined
define("BASE_URL",  "ENVIRONMENTBASEURL");
define("BASE_PATH",  "ENVIRONMENTBASEPATH");
define("KORA_BASE_URL", "https://kora.enslaved.org/");
define("BASE_WIKI_URL", "https://lod.enslaved.org/");
define("BASE_BLAZEGRAPH_URL", "https://bg1.dev.matrix.msu.edu/bigdata/");


//project specific urls - you should never use relative paths
define("BASE_JS_URL", BASE_URL . "assets/javascripts/");
define("BASE_AJAX_URL", BASE_URL . "ajax/");
define("BASE_VIEW_URL", BASE_URL . "views/");
define("BASE_IMAGE_URL", BASE_URL . "assets/images/");
define("BASE_CSS_URL", BASE_URL . "assets/stylesheets/style.css");
define("BASE_MODULE_URL", BASE_URL . "modules/");
define("BASE_LEAFLET_URL", BASE_URL . "assets/leaflet/");
define("BASE_ONTOLOGY_URL", BASE_URL . "assets/ontology/");

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

//elasticsearch
define('ELASTICSEARCH_URL', 'enslaved-es:9200');
define('ELASTICSEARCH_INDEX_NAME', 'enslaved-index');

//kora project information - change these for your project
define('TOKEN', 'SECRETTOKENGOESHERE');
define('STORY_SID', 23);
define('PID', 16);
define('WEBPAGES_FORM', 49);

define('LOD_CONFIG', 'enslaved');

$GLOBALS['FILTER_ARRAY'] = Array(
    "events" => array(
        "Event Type",
        "Date"
    ),
    "people" => array(
        "Gender",
        "Age Category",
        "Ethnodescriptor",
        "Role Types",
        "Occupation"
    ),
    "places" => array(
        "Place Type"
    ),
    "sources" => array(
        "Source Type"
    )
);

$GLOBALS['CONSTANTS_FILE_ARRAY'] = Array(
    "enslaved" => "cvconstants"
);


$GLOBALS['PREFIX_ARRAY'] = Array(
    "enslaved" => array(
        "wdata" => "edata",
        "wd" => "ed",
        "wdt" => "edt",
        "s" => "es",
        "ref" => "eref",
        "v" => "ev",
        "t" => "et",
        "p" => "ep",
        "ps"  => "eps",
        "psv" => "epsv",
        "psn" => "epsn",
        "pq" => "epq",
        "pqv" => "epqv",
        "pqn" => "epqn",
        "pr" => "epr",
        "prv" => "eprv",
        "prn" => "eprn",
        "no" => "eno",
        "prov" => "prov",
        "rdfs" => "rdfs"
    ),
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
        "var BASE_ONTOLOGY_URL ='".BASE_ONTOLOGY_URL."';" .
    "</script>\n"
);

$bg = ['enslaved-header-bg.jpg','enslaved-header-bg2.jpg',
        'enslaved-header-bg3.jpg','enslaved-header-bg4.jpg',
        'enslaved-header-bg5.jpg','enslaved-header-bg6.jpg',
        'enslaved-header-bg7.jpg'];
$GLOBALS['bg'] = $bg;
$GLOBALS['randIndex'] = array_rand($bg);
