<?php

// echo 'got hereee';
function create_csv(){
    $tempfile=tempnam(sys_get_temp_dir(),'');
    chmod($tempfile, 0775);
    // $csvName = $tempfile.'.csv';
    // rename($tempfile, $csvName);
    // echo $tempfile;die;
    $_GET['csv_name'] = $tempfile;
    $params = escapeshellarg(json_encode($_GET));
    exec("/opt/local/bin/php ".BASE_FUNCTIONS_PATH."search.php $params > /dev/null 2>&1 &");
    // return json_encode($output);
    return $tempfile;
}

function check_csv(){
    if( file_exists($_GET['csvName'].'.csv') ){
        return 'true';
    }
    if( file_exists($_GET['csvName']) ){
        return 'false';
    }
    return 'broken';
}

function download_csv(){
    $file = $_GET['csvName'].'.csv';

    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="data.csv"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        unlink($file);
        exit;
    }
}
