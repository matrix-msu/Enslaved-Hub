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
$response = Kora_GetNavigationData();

return [
    'production' => false,
    'baseUrl' => '/~christj2/enslaved-static/build_local',
    'title' => 'Enslaved Static',
    'description' => 'Test project for website builder',
    // 'collections' => [
    //     'events' => [
    //         'extends' => '_layouts.post',
    //         'path' => 'record/event/{title}',
    //         'items' => [
    //             ['title'=>'3title', 'content'=>'3content'],
    //             ['title'=>'4title', 'content'=>'4content']
    //         ],
    //     ],
    // ],
];
