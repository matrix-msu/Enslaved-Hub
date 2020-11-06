<?php
    require_once ( __DIR__ . '/config.php' ) ;
    require_once ( __DIR__ . '/database-config.php' ) ;
    require_once ( __DIR__ . '/routes.php' ) ;
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta name="google" content="notranslate" />
    <title><?php echo BASE_URL;?></title>
    <?=JS_GLOBALS?>
    <?=EXPLORE_JS_VARS?>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!-- favicon -->
    <link rel="icon" href="<?php echo BASE_IMAGE_URL;?>favicon.png">
    <!-- stylesheet -->
    <link rel="stylesheet" href="<?php echo BASE_CSS_URL;?>" type="text/css">
    <!-- jquery / local scripts -->
    <script language="JavaScript" type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo BASE_JS_URL;?>header.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo BASE_JS_URL;?>modal.js"></script>

    <!-- select2 cdn links -->
    <link href="<?php echo BASE_URL;?>assets/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
    <script src="<?php echo BASE_URL;?>assets/select2/4.0.7/js/select2.js"></script>
    <!-- leaflet -->
    <link rel="stylesheet" href="<?php echo BASE_LEAFLET_URL;?>leaflet.css" />
    <script src="<?php echo BASE_LEAFLET_URL;?>leaflet.js"></script>

    <?php
        $path = BASE_PATH . "config.json";
        if( file_exists($path) ) {
            $contents = file_get_contents($path);
            $contents = json_decode($contents, true);
            if (isset($contents['theme']) && $contents['theme'] !== '') {
                echo '<link rel="stylesheet" href="' . BASE_URL . 'assets/stylesheets/themes/' . $contents['theme'] . '.css" type="text/css">';
            }
        }
    ?>

</head>
<body>
    <?php
        include 'header.php';
        include BASE_VIEW_PATH . CURRENT_VIEW;
        include 'footer.php';
    ?>
    <script language="JavaScript" type="text/javascript" src="<?php echo BASE_JS_URL;?>navContents.js"></script>
</body>
</html>
