<?php
    require_once ( __DIR__ . '/config.php' ) ;
?>

<!DOCTYPE HTML>
<html>
<head>
    <title><?php echo BASE_URL;?></title>
    <?php
        echo JS_GLOBALS;
    ?>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!-- stylesheet -->
    <link rel="stylesheet" href="<?php echo BASE_CSS_URL;?>" type="text/css">
    <script language="JavaScript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo BASE_JS_URL;?>header.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo BASE_JS_URL;?>modal.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo BASE_JS_URL;?>search.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo BASE_JS_URL;?>searchResults.js"></script>

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

</body>
</html>
