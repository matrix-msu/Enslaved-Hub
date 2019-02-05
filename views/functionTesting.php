<div class="container main">
    <div class="container middlewrap">
        <h1>testing view</h1>
    </div>
</div>

<?php
//    $tableName = 'templateUsers';
//    $params = array('username'=>'newuser');
//    echo insert($tableName, $params);
//    echo update($tableName, array('password'=>'hi'), array('username'=>"newuser"));
//    echo delete($tableName, array('id'=> 28, 'username' => 'asdf'));
//     print_r(select($tableName, array("*"), array('password'=> 'hi')));
    //$return = BrowseOneForm();


//Kora call function, search one form with search query. Need string of keywords seperated by space for search query to work
//SearchOneForm(token, nameSchemeID, 'ALL', ['Name_137_796_'], "cuba", [], 0, 10, [], []);

$pid = 11;

$return = SearchOneForm(
        KORA_SEARCH_URL,
        $GLOBALS['TOKEN_ARRAY'][$pid],
        $GLOBALS['PROJECT_SID_ARRAY'][$pid],
        'ALL',
        ['Country_11_31_'],
        "Greece",
        [],
        NULL,
        NULL,
        [],
        []
);

print_r($return);
?>
