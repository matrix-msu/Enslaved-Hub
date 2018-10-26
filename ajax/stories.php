<?php
require_once '../config.php';
require_once BASE_FILE_PATH.'functions/kora.php';

if (isset($_POST['page'])){
    $page = $_POST['page'];
}
if (isset($_POST['count'])){
    $count = $_POST['count'];
}
$results = getStories($page, $count);
echo json_encode($results);
?>