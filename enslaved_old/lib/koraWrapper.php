<?php
/*
Note, there are currently flaws with some of these functions. Please remove them
from this list when they are fixed:
multiple form searches are wrong - they should only search once. 'forms' should
point to an array with each query in it, then kora will return the results for
each form with the one call.
realnames: true should probably be set by default, as we basically always want that.
This needs done for each function.
AddOptional functionality currently requires each form and query to have something
in the optional array, even if just an empty array. Could change code to check
if there is a value for that index instead, which would allow the optional array
to have something like [1 => [stuff], 3 => [stuff]] if that's all that's needed
*/

//Kora call function, get some results from one form
//BrowseOneForm(token, nameSchemeID, 'ALL', [], 0, 10, ['data' => false]);
function BrowseOneForm($k3url, $token, $form, $fields, $sort = NULL, $start = NULL, $count = NULL, $optional = []) {
    $query = [];
    $query['form'] = $form;                 //form id/slug to search through
    $query['token'] = $token;               //authentication token
    $query['fields'] = $fields;             //fields to return information for in the record
    if (count($sort) > 0){
        $query['sort'] = $sort;             //ordering records
    }
    $query['count'] = $count;           //number of records to be returned
    $query['index'] = $start;               //starting index for the array of results
    $query = AddOptional($query, $optional);
    $query = '['.json_encode($query).']';   //json string of the query
    $data = ['forms' => $query];
    $ch = curl_init($k3url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


//Kora call function, get some results for multiple forms. All inputs, except for the token, must be in equal sized arrays
//BrowseMultipleForms(token, [nameSchemeID, eventSchemeID], ['ALL','ALL'], [[],[]], [0,0], [2,5], [[],[]]);
function BrowseMultipleForms($k3url, $token, $forms, $fields, $sort = NULL, $start = NULL, $count = NULL, $optional = []) {
    $query = [];
    for ($i = 0; $i<count($forms); $i++){       //will create different searches with input information/put into the $query variable
        $pre = [];
        $pre['form'] = $forms[$i];
        $pre['token'] = $token;
        $pre['fields'] = $fields[$i];
        if (count($sort[$i]) > 0){
            $pre['sort'] = $sort[$i];
        }
        $pre = AddOptional($pre, $optional[$i]);
        $pre['count'] = $count[$i];
        $pre['index'] = $start[$i];
        $pre = '['.json_encode($pre).']';
        $query[] = $pre;
    }
    $fin = [];
    foreach($query as $search){                 //does each search and returns all the results
        $data = ['forms' => $search];
        $ch = curl_init($k3url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        $fin[] = $result;
    }
    return $fin;
}


//Kora call function, search one form with search query. Need string of keywords separated by space for search query to work
//SearchOneForm(pid, nameSchemeID, 'ALL', ['Name_137_796_'], "cuba", [], 0, 10, [], []);
//KID search Example: SearchOneForm(pid, nameSchemeID, 'ALL', 'kid', ["10-30-172","10-30-173"]);
function SearchOneForm($pid, $form, $fields, $query_fields = [], $keys = "", $sort = [], $start = NULL, $count = NULL, $optional = [], $optional_query = []){
    $fin = [];
    $fin['form'] = $form;
    $fin['token'] = $GLOBALS['TOKEN_ARRAY'][$pid];
    $fin['fields'] = $fields;
    $fin['realnames'] = true;
    if (count($sort) > 0){
        $fin['sort'] = $sort;
    }
    $fin['index'] = $start;
    $fin['count'] = $count;
    if ($keys != ""){                   //check if search keys exist, if so create query
        if( is_array($keys) && ($query_fields=='kid' || $query_fields=='KID') ){
            $query['search'] = 'kid';
            $query['kids'] = $keys;
        }else {
            $query['search'] = 'keyword';
            $query['keys'] = $keys;
            // $query['fields'] = $query_fields;      //adds search fields, different from return fields
            if (!is_array($query_fields) || !empty($query_fields)) {
                $query['fields'] = $query_fields;      //adds search fields, different from return fields
            }
        }
        $query = AddOptional($query, $optional_query);
        $query = [$query];
    }
    else{
        $query = NULL;
    }
    //print_r($query);die;
    $fin['query'] = $query;
    $fin = AddOptional($fin, $optional);
    //print_r($fin);echo '<br><br><br>';die;
    $fin = '['.json_encode($fin).']';   //json string of the query
    $data = ['forms' => $fin];

    $ch = curl_init(KORA_SEARCH_URL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


//Kora call function, search one form with search query. Need string of keywords separated by space for search query to work
//SearchOneForm(pid, nameSchemeID, 'ALL', ['Name_137_796_'], "cuba", [['Name_137_796_'],'ASC'], 0, 10, [], []);
//KID search Example: SearchOneForm(pid, nameSchemeID, 'ALL', 'kid', ["10-30-172","10-30-173"]);
//Multiple date queries can be passed through $optional with 'datesArray' => array( 'dateField1'=>array(date1,date2), 'dateField2'=>array(date3,date4) )
//Multi date 'optional' array ex: array('size' => 'true', 'datesArray' => array('Date_Original_59_191_' => $datesArray) )
function AdvancedSearchOneForm($pid, $form, $fields, $queries = [], $sort = [],
                               $start = NULL, $count = NULL, $optional = [], $optional_query = []){
    $fin = [];
    $fin['form'] = $form;
    $fin['token'] = $GLOBALS['TOKEN_ARRAY'][$pid];
    $fin['fields'] = $fields;
    //$fin['realnames'] = true;
    if (count($sort) > 0){
        $fin['sort'] = $sort;
    }
    $fin['index'] = $start;
    $fin['count'] = $count;
    if ( !empty($queries) ){
        if (isset($optional['datesArray'])){
            foreach( $optional['datesArray'] as $key => $dateArray){
                //this takes the same query and turns it into multiple with different dates
                foreach($dateArray as $date){
                    $queries[$key] = $date;
                    $query[] = array(
                        'search' => 'advanced',
                        'fields' => $queries
                    );
                }
            }
        }else{
            $query['search'] = 'advanced';
            $query['fields'] = $queries;
            $query = AddOptional($query, $optional_query);
            $query = [$query];
        }
    }
    else{
        $query = NULL;
    }
    $fin['query'] = $query;
    $fin = AddOptional($fin, $optional);
    $fin = '['.json_encode($fin).']';   //json string of the query
    $data = ['forms' => $fin];

    $ch = curl_init(KORA_SEARCH_URL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


//Kora call function, get some results for multiple forms, and multiple search querys. All inputs, except for the token, must be in equal sized arrays
//SearchMultipleForms(token,[nameSchemeID, eventSchemeID], ['ALL','ALL'], [[],[]], [0,0], [10,10], [[],[]], ['cuba','negrito'], [], [['not' => true],['not' => true]]);
function SearchMultipleForms($k3url, $token, $forms, $fields, $sort = NULL, $start = NULL, $count = NULL, $query_fields = [], $keys = '', $optional = [], $optional_query = []){
    $temp = [];
    for ($i = 0; $i<count($forms); $i++){
        $pre = [];
        $pre['form'] = $forms[$i];
        $pre['token'] = $token;
        $pre['fields'] = $fields[$i];
        if (count($sort[$i]) > 0){
            $pre['sort'] = $sort[$i];
        }
        $pre['count'] = $count[$i];
        $pre['index'] = $start[$i];
        if (count($optional_query) > 0){
            $pre = AddOptional($pre, $optional_query[$i]);
        }
        if (count($optional) > 0){
            $pre = AddOptional($pre, $optional[$i]);
        }
        if ($keys != '' and $keys[$i] != ""){                   //check if search keys exist, if so create query
            $query['search'] = 'keyword';
            $query['keys'] = $keys[$i];
            // $query['fields'] = $query_fields[$i];      //adds search fields, different from return fields
            if (!is_array($query_fields) || !empty($query_fields)) {
                $query['fields'] = $query_fields;      //adds search fields, different from return fields
            }
            $query = [$query];
        }
        else{
            $query = NULL;
        }
        $pre['query'] = $query;
        $pre = '['.json_encode($pre).']';
        $temp[] = $pre;
    }
    $fin = [];
    foreach($temp as $search){                 //does each search and returns all the results
        $data = ['forms' => $search];
        $ch = curl_init($k3url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        $fin[] = $result;
    }
    return $fin;
}


//Kora call function, Allows for you to search with filters. Takes in array full of filters and creates logic for the search comparing them.
//SearchWithFilters(token, nameSchemeID, 'ALL', ['Name_137_796_'], "cuba cudjoe oda awussa", [], 0, 10, [array('cudjoe' => 'Name_137_796_'),array('oda' => 'Name_137_796_'), array('137-799-2297937' => 'Name_137_796_'), array('awussa' => 'Name_137_796_')], [], [['not' => true],['not' => true],['not' => true],['not' => true],['not' => true],['not' => true]]);
function SearchWithFilters($k3url, $token, $form, $fields, $query_fields = [], $keys = '', $sort = [], $start = NULL, $count = NULL, $filter = [], $optional = [], $optional_query = []){
    $fin = [];
    $number = 0;
    $fin['form'] = $form;
    $fin['token'] = $token;
    $fin['fields'] = $fields;
    if (count($sort) > 0){
        $fin['sort'] = $sort;
    }
    $fin['index'] = $start;
    $fin['count'] = $count;
    if ($keys != ""){                   //check if search keys exist, if so create query
        $query['search'] = 'keyword';
        $query['keys'] = $keys;
        if (!is_array($query_fields) || !empty($query_fields)) {
            $query['fields'] = $query_fields;      //adds search fields, different from return fields
        }
        //$query = AddOptional($query, $optional_query);
        $query = $query;
    }
    else{
        $query = NULL;
    }
    $complete_query = [];
    if ($query != null) {
        $complete_query[] = $query;
    }
    foreach($filter as $stuffs){            //creates the filters used for the search
        $temp_query = [];
        $temp_query['method'] = 'EXACT';            //defaulted to exact
        $temp_query['keys'] = key($stuffs);
        $temp_query['fields'] = [$stuffs[key($stuffs)]];
        $temp_query['search'] = 'keyword';
        $temp_query = AddOptional($temp_query, $optional_query[$number]);
        $number = $number + 1;
        $complete_query[] = $temp_query;
    }
    if (!empty($complete_query)) {
        $fin['query'] = $complete_query;
    }
    else {
        $fin['query'] = null;
    }
    //$fin['query'] = AddOptional($fin['query'], $optional_query);
    $fin = AddOptional($fin, $optional);
    $index = [0];
    $or_logic = [];
    $or_index = [];
    $logic = [];
    if (count($complete_query) > 1){              //checks to see if there is enough for comparison
        for ($i = 1; $i < count($complete_query); $i++){
            $index[$i] = $i;
            for ($x = ($i+1); $x < count($complete_query); $x++){
                if ($i == $x){                      //eliminates duplicates
                    continue;
                }
                if ($complete_query[$i]['fields'] == $complete_query[$x]['fields']){
                    $or_logic[] = [$i, 'OR', $x];
                    if (!in_array($i, array_values($or_logic))){
                        $or_index[$i] = $i;
                    }
                    if (!in_array($x, array_values($or_logic))){
                        $or_index[$x] = $x;
                    }
                }
            }
        }
    }
    foreach($or_index as $thing){                   //creates AND logic index
        if (($key = array_search($thing, $index)) !== false){
            unset($index[$key]);
        }
    }
    $new_index = [];
    foreach($index as $x){                          //changes keys to be easier to work with
        $new_index[] = $x;
    }
    $index = $new_index;
    $fin_or = [];
    $current_index = [];
    if (count($or_logic) > 1){
        foreach($or_logic as $stuff){
            if (count($fin_or) == 0){               //current logic index's
                $fin_or = $stuff;
                $current_index[] = $stuff[0];
                $current_index[] = $stuff[2];
            }
            else{
                if((in_array($stuff[0], array_values($current_index))) and (in_array($stuff[2], array_values($current_index)))){
                    continue;                       //if both indexes are already used, skips the logic
                }
                else{
                    $fin_or = CreateLogic($fin_or, $stuff, $or_index);
                    if (!in_array($stuff[0], array_values($current_index))){
                        $current_index[] = $stuff[0];
                    }
                    if (!in_array($stuff[2], array_values($current_index))){
                        $current_index[] = $stuff[2];
                    }
                }
            }
        }
    }
    else{
        $fin_or = $or_logic;            //there is only one or logic
    }
    if ((count($logic) == 0) and (count($index) > 1)){      //creates first AND logic
         $logic = array($index[0],'AND',$index[1]);
        if (count($index) > 2){
            for($i=2; $i < count($index); $i++){
                $logic = AndLogic($logic, $index[$i]);
            }
        }
        if ($fin_or){                                           //adds final OR logic to the final logic
            $logic = AndLogic($logic, $fin_or);
        }
    }
    else{
        if ($fin_or){
            $logic = array(0, 'AND', $fin_or);
        }
    }
    if (count($logic) > 0) {
        $fin['logic'] = $logic;
    }
    $fin = '['.json_encode($fin).']';   //json string of the query
    $data = ['forms' => $fin];
    $ch = curl_init($k3url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


//Kora call function, does search using only filters. No search query. works just like SearchWithFilters.
//BrowseWithFilters(token, nameSchemeID, 'ALL', [], 0, 10, [array('cudjoe' => 'Name_137_796_'),array('oda' => 'Name_137_796_'), array('science' => 'Name_137_796_'), array('awussa' => 'Name_137_796_')], ['data' => false, 'meta' => true, 'size' => true], [['not' => true],['not' => true],['not' => true],['not' => true],['not' => true]]);
function BrowseWithFilters($k3url, $token, $form, $fields, $sort = NULL, $start = NULL, $count = NULL, $filter = [], $optional = [], $optional_query = []){
    $fin = [];
    $number = 0;
    $fin['form'] = $form;
    $fin['token'] = $token;
    $fin['fields'] = $fields;
    if (count($sort) > 0){
        $fin['sort'] = $sort;
    }
    $fin['index'] = $start;
    $fin['count'] = $count;
    $complete_query = [];
    foreach($filter as $stuffs){            //creates the filters used for the search
        $temp_query = [];
        $temp_query['method'] = 'EXACT';            //defaulted to exact
        $temp_query['keys'] = key($stuffs);
        $temp_query['fields'] = [$stuffs[key($stuffs)]];
        $temp_query['search'] = 'keyword';
        $temp_query = AddOptional($temp_query, $optional_query[$number]);
        $number = $number + 1;
        $complete_query[] = $temp_query;
    }
    //$complete_query = AddOptional($complete_query, $optional_query);
    $fin['query'] = $complete_query;
    $fin = AddOptional($fin, $optional);
    if (count($complete_query) == 1){
        $fin['logic'] = NULL;
    }
    else{
        $index = [];
        $or_index = [];
        $or_logic = [];
        $logic = [];
        if (count($complete_query) > 1){              //checks to see if there is enough for comparison
            for ($i = 0; $i < count($complete_query); $i++){
                $index[$i] = $i;
                for ($x = ($i+1); $x < count($complete_query); $x++){
                    if ($i == $x){                      //eliminates duplicates
                        continue;
                    }
                    if ($complete_query[$i]['fields'] == $complete_query[$x]['fields']){
                        $or_logic[] = [$i, 'OR', $x];
                        if (!in_array($i, array_values($or_logic))){
                            $or_index[$i] = $i;
                        }
                        if (!in_array($x, array_values($or_logic))){
                            $or_index[$x] = $x;
                        }
                    }
                }
            }
        }
        foreach($or_index as $thing){                   //creates AND logic index
            if (($key = array_search($thing, $index)) !== false){
                unset($index[$key]);
            }
        }
        $new_index = [];
        foreach($index as $x){                          //changes keys to be easier to work with
            $new_index[] = $x;
        }
        $index = $new_index;
        $fin_or = [];
        $current_index = [];
        if (count($or_logic) > 1){
            foreach($or_logic as $stuff){
                if (count($fin_or) == 0){               //current logic index's
                    $fin_or = $stuff;
                    $current_index[] = $stuff[0];
                    $current_index[] = $stuff[2];
                }
                else{
                    if((in_array($stuff[0], array_values($current_index))) and (in_array($stuff[2], array_values($current_index)))){
                        continue;                       //if both indexes are already used, skips the logic
                    }
                    else{
                        $fin_or = CreateLogic($fin_or, $stuff, $or_index);
                        if (!in_array($stuff[0], array_values($current_index))){
                            $current_index[] = $stuff[0];
                        }
                        if (!in_array($stuff[2], array_values($current_index))){
                            $current_index[] = $stuff[2];
                        }
                    }
                }
            }
        }
        else{
            $fin_or = $or_logic;            //there is only one or logic
        }
        if ((count($logic) == 0) and (count($index) > 1)){      //creates first AND logic
            $logic = array($index[0],'AND',$index[1]);
            if (count($index) > 2){
                for($i=2; $i < count($index); $i++){
                    $logic = AndLogic($logic, $index[$i]);
                }
            }
            if ($fin_or){                                          //adds final OR logic to the final logic
                $logic = AndLogic($logic, $fin_or);
            }
        }
        else{
            if (count($index) == 0){
                $logic = $fin_or;
            }
            if (count($index) == 1){
                $logic = array($index[0], 'AND', $fin_or);
            }
        }
    }
    if (count($logic) > 0) {
        $fin['logic'] = $logic;
    }
    $fin = '['.json_encode($fin).']';   //json string of the query
    $data = ['forms' => $fin];
    $ch = curl_init($k3url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_exec($ch);
    curl_close($ch);
    return $result;
}

//Kora call function. Lets you search with filters through multiple forms.
//SearchMultiFilters(token, [nameSchemeID, eventSchemeID, objectSchemeID, placeSchemeID], ['ALL','ALL', 'ALL', 'ALL'], [[],[],[],[]], [0,0,0,0], [10,10,10,10], [['Name_137_796_'],['Event_Type_137_799_'],['Object_Type_137_797_'],['Place_Type_137_798_']], ['cuba','','cuba cudjoe oda awussa','cuba cudjoe oda awussa'], [[],[],[],[]], [[],[],[],[]], [[],[],[],[]]);
function SearchMultiFilters($k3url, $token, $forms, $fields, $sort = NULL, $start = NULL, $count = NULL, $query_fields = [], $keys = '', $filter = [], $optional = [], $optional_query = []){
    $temp = [];
    for ($i = 0; $i<count($forms); $i++){
        $pre = [];
        $number = 0;
        $pre['form'] = $forms[$i];
        $pre['token'] = $token;
        $pre['fields'] = $fields[$i];
        if (count($sort[$i]) > 0){
            $pre['sort'] = $sort[$i];
        }
        $pre['count'] = $count[$i];
        $pre['index'] = $start[$i];
        if ($keys != ""){                   //check if search keys exist, if so create query
            $query['search'] = 'keyword';
            $query['keys'] = $keys[$i];
            // $query['fields'] = $query_fields[$i];      //adds search fields, different from return fields
            if (!is_array($query_fields) || !empty($query_fields)) {
                $query['fields'] = $query_fields;      //adds search fields, different from return fields
            }
            //$query = AddOptional($query, $optional_query);
            $query = $query;
        }
        else{
            $query = NULL;
        }
        $complete_query = [];
        if ($query != null) {
            $complete_query[] = $query;
        }
        foreach($filter[$i] as $stuffs){            //creates the filters used for the search
            $temp_query = [];
            $temp_query['method'] = 'EXACT';            //defaulted to exact
            $temp_query['keys'] = key($stuffs);
            $temp_query['fields'] = [$stuffs[key($stuffs)]];
            $temp_query['search'] = 'keyword';
            $temp_query = AddOptional($temp_query, $optional_query[$i][$number]);
            $number = $number + 1;
            $complete_query[] = $temp_query;
        }
        $pre['query'] = $complete_query;
        $pre = AddOptional($pre, $optional[$i]);
        $index = [];
        $or_index = [];
        $or_logic = [];
        $logic = [];
        $fin_or = [];
        $current_index = [];
        if (count($complete_query) == 1){
            $pre['logic'] = NULL;
        }
        else{
            if (count($complete_query) > 1){
                for ($x = 0; $x < count($complete_query); $x++){
                    $index[$x] = $x;
                    for ($j = ($x+1); $j < count($complete_query); $j++){
                        if ($x == $j){                      //eliminates duplicates
                            continue;
                        }
                        if ($complete_query[$x]['fields'] == $complete_query[$j]['fields']){
                            $or_logic[] = [$x, 'OR', $j];
                            if (!in_array($x, array_values($or_logic))){
                                $or_index[$x] = $x;
                            }
                            if (!in_array($j, array_values($or_logic))){
                                $or_index[$j] = $j;
                            }
                        }
                    }
                }
            }
            foreach($or_index as $thing){                   //creates AND logic index
                if (($key = array_search($thing, $index)) !== false){
                    unset($index[$key]);
                }
            }
            $new_index = [];
            foreach($index as $x){                          //changes keys to be easier to work with
                $new_index[] = $x;
            }
            $index = $new_index;
            if (count($or_logic) > 1){
                foreach($or_logic as $stuff){
                    if (count($fin_or) == 0){               //current logic index's
                        $fin_or = $stuff;
                        $current_index[] = $stuff[0];
                        $current_index[] = $stuff[2];
                    }
                    else{
                        if((in_array($stuff[0], array_values($current_index))) and (in_array($stuff[2], array_values($current_index)))){
                            continue;                       //if both indexes are already used, skips the logic
                        }
                        else{
                            $fin_or = CreateLogic($fin_or, $stuff, $or_index);
                            if (!in_array($stuff[0], array_values($current_index))){
                                $current_index[] = $stuff[0];
                            }
                            if (!in_array($stuff[2], array_values($current_index))){
                                $current_index[] = $stuff[2];
                            }
                        }
                    }
                }
            }
            else{
                $fin_or = $or_logic;            //there is only one or logic
            }
            if ((count($logic) == 0) and (count($index) > 1)){      //creates first AND logic
                $logic = array($index[0],'AND',$index[1]);
                if (count($index) > 2){
                    for($i=2; $i < count($index); $i++){
                        $logic = AndLogic($logic, $index[$i]);
                    }
                }
                if ($fin_or){                                          //adds final OR logic to the final logic
                    $logic = AndLogic($logic, $fin_or);
                }
            }
            else{
                if (count($index) == 0){
                    $logic = $fin_or;
                }
                if (count($index) == 1){
                    $logic = array($index[0], 'AND', $fin_or);
                }
            }
            if (count($logic) > 0) {
                $pre['logic'] = $logic;
            }
        }
        $pre = '['.json_encode($pre).']';
        $temp[] = $pre;
    }
    foreach($temp as $search){                 //does each search and returns all the results
        $data = ['forms' => $search];
        //print_r($data);
        $ch = curl_init(k3url."api/search");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);
        $fin[] = $result;
    }
    return $fin;
}

//Function to grab a single record by a kid
//kid_SingleRecord(token, nameSchemeID, '137-796-2208671', []);
function singleRecord($k3url, $token, $form, $kid, $fields){
    $query = [];
    $query['form'] = $form;                 //form id/slug to search through
    $query['token'] = $token;
    $temp_query = [];
    $temp_query['search'] = 'kid';
    $temp_query['kids'] = array($kid);
    if (count($fields) > 0){
        $temp_query['fields'] = $fields;
    }
    $query['query'] = array($temp_query);
    $query = '['.json_encode($query).']';   //json string of the query
    $data = ['forms' => $query];
    $ch = curl_init($k3url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}



//Function for getting records associated to a particular kid
// associated_kid_records(token, nameSchemeID, '137-796-2208671', []);
function associated_kid_records($k3url, $token, $form, $kids, $fields){
    $query = [];
    $query['form'] = $form;
    $query['token'] = $token;
    $temp_query = [];
    $temp_query['search'] = 'keyword';
    $temp_query['keys'] = $kids;
    if (count($fields) > 0){
        $temp_query['fields'] = $fields;
    }
    $query['query'] = array($temp_query);
    $query = '['.json_encode($query).']';   //json string of the query
    $data = ['forms' => $query];
    $ch = curl_init($k3url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


//FUNCTIONS USED TO ASSIST IN KORA CALL FUNCTIONS

//input a current logic list, and a logic you want to add to the logic list. Recursivly goes through arrays
function CreateLogic($current_logic, $add_logic, $index){
    if (is_array($current_logic[0])){
        $current_logic[0] = CreateLogic($current_logic[0], $add_logic, $index);
        return $current_logic;
    }
    elseif (is_array($current_logic[2])){
        $current_logic[2] = CreateLogic($current_logic[2], $add_logic, $index);
        return $current_logic;
    }
    else{
        if ($current_logic[0] == $add_logic[0]){
            $current_logic[0] = $add_logic;
            return $current_logic;
        }
        if ($current_logic[2] == $add_logic[2]){
            $current_logic[2] = $add_logic;
            return $current_logic;
        }
        if ($current_logic[0] == $add_logic[2]){
            $current_logic[0] = $add_logic;
            return $current_logic;
        }
        if ($current_logic[2] == $add_logic[0]){
            $current_logic[2] = $add_logic;
            return $current_logic;
        }
    }
}

//function that creates And Logic for logic variable.
function AndLogic($logic, $index){
    if (is_array($logic[2])){
        $logic[2] = AndLogic($logic[2], $index);
        return($logic);
    }
    else{
        $logic[2] = array($logic[2], 'AND', $index);
        return($logic);
    }
}

//function adds optional rules to an array
function AddOptional($array, $optional){
    if (count($optional) > 0){
        foreach($optional as $key => $value){
            $array[$key] = $value;
        }
    }
    return $array;
}