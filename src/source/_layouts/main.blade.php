<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate" />
    <title>Enslaved.org</title>
    <?=JS_GLOBALS?>
    <?php if(defined('EXPLORE_JS_VARS')){echo EXPLORE_JS_VARS;}?>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="theme-color" content="#0E3232">

    <!-- Link Previews -->
    <meta property="og:title" content="Enslaved: Peoples of the Historical Slave Trade">
    <meta property="og:description" content="Explore or reconstruct the lives of individuals who were enslaved, owned slaves, or participated in the historical trade.">
    <meta property="og:image" content="<?php echo BASE_IMAGE_URL;?>link-preview.jpg">

    <!-- Twitter Link Previews -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="https://enslaved.org">
    <meta name="twitter:title" content="Enslaved: Peoples of the Historical Slave Trade">
    <meta name="twitter:description" content="Explore or reconstruct the lives of individuals who were enslaved, owned slaves, or participated in the historical trade.">
    <meta name="twitter:image" content="<?php echo BASE_IMAGE_URL;?>link-preview.jpg">

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

</head>
<body>
    @include('_partials.header')
    @yield('body')
    @include('_partials.footer')
</body>
</html>
