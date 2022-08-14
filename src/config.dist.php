<?php
ini_set("memory_limit", "-1");
set_time_limit(0);
require_once("./source/config.php");

//include the lib files
require_once( "./source/lib/configFunctions.php" );
require_once( "./source/lib/koraWrapper.php" );
require_once( "./source/lib/mySqlWrapper.php" );
require_once( "./source/functions/explorefunctions.php");
require_once( "./source/functions/storyfunctions.php");
require_once( "./source/functions/functions.php");
require_once( "./source/lib/koraSearchRemote.php");
// require_once( "./source/functions/search.php");

//generate the webpages file
Kora_GetNavigationData();

// Get all Stories using KORA_Search
// $fields =  ['Title', 'Featured', 'Images'];
// $fields = ['Title', 'Images', 'Caption', 'Text', 'Resources', 'Source', 'Creator', 'Contributor', 'Timeline', 'Story_Associator','Contributing Institution', 'Connection'];
// $clause = new KORA_Clause("Display", "=", "True");
// $stories = KORA_Search(TOKEN, PID, STORY_SID, $clause, "ALL");
// unset($stories["count"]);

// $fields = ['Title', 'Images', 'Caption', 'Text', 'Resources', 'Source', 'Creator', 'Contributor', 'Timeline', 'Story_Associator','Contributing Institution', 'Connection'];
$stories = koraWrapperSearch(STORY_SID, 'ALL', "Display", 'True');
$stories = json_decode($stories, true);
$stories = $stories['records'][0];

$fileName= "./source/assets/javascripts/searchData.js";
$script = "var allStoriesRecords = JSON.parse('".addslashes(json_encode(array_values($stories), true))."');";
file_put_contents($fileName, $script);
$gzScript = gzencode($script);
file_put_contents($fileName.'.gz', $gzScript);

$storyKids = array();
foreach($stories as $kid => $record){
    $storyKids[] = array(
        'title' => $kid,
        'content' => $kid
    );
}

define("ALL_STORIES", $stories);

$generatedConstants = file_get_contents('https://manta.matrix.msu.edu/msumatrix/public/exports/enslaved.org/visualizeCounts/cvconstants.php');
$fileName= "./source/generatedConstants.php";
file_put_contents($fileName, $generatedConstants);

return [
    'production' => false,
    'baseUrl' => '/~christj2/enslaved-static/build_local',
    'title' => 'Enslaved Static',
    'description' => 'Test project for website builder',
    'collections' => [
        'stories' => [
            'extends' => '_layouts.story',
            'path' => 'fullStory/{title}',
            'items' => $storyKids,
        ]
    ]
];
