<?php
require_once('config.php');
globaljsvars;
print globaljsvars;
?>
<!DOCTYPE html>
<html>

    <head>
        <!-- meta -->
        <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <!-- stylesheet -->
        <link rel="stylesheet" href="<?php echo BASE_URL;?>assets/stylesheets/style.css" type="text/css">
        <script language="JavaScript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
    </head>

    <body>
        <!-- Header/Navigation -->
        <header class="nav-header">
            <div class="headerwrap">
                <div class="leftnav">
                    <h1>Enslaved</h1>
                </div>
                <div class="rightnav">
                    <ul class="nav-menu">
                        <li class="nav-item"><a class="nav-link unselected" id="search" href="<?php echo BASE_URL;?>index">Search</a></li>
                        <li class="nav-item"><a class="nav-link unselected" id="explore" href="<?php echo BASE_URL;?>index">Explore</a></li>
                        <li class="nav-item"><a class="nav-link unselected" id="visualize" href="<?php echo BASE_URL;?>index">Visualize</a></li>
                        <li class="nav-item"><a class="nav-link unselected" id="stories" href="<?php echo BASE_URL;?>index">Stories</a></li>
                        <li class="nav-item"><a class="nav-link unselected" id="projects" href="<?php echo BASE_URL;?>index">Projects</a></li>
                        <li class="nav-item"><a class="nav-link unselected" id="about" href="<?php echo BASE_URL;?>index">About</a></li>
                    </ul>
                </div>
            </div>
        </header>