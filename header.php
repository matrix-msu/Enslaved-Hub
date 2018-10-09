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
        <title>Enslaved</title>
        <!-- stylesheet -->
        <link rel="stylesheet" href="<?php echo BASE_URL;?>assets/stylesheets/style.css" type="text/css">
        <!-- jquery / local scripts -->
        <script language="JavaScript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
        <script language="JavaScript" type ="text/javascript" src="<?php echo BASE_URL;?>assets/javascripts/header.js"></script>
        <!-- select2 cdn links -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
        
    </head>

    <body>
        <!-- Header/Navigation -->
        <header class="nav-header">
            <div class="headerwrap">
                <div class="leftnav">
                    <a href="<?php echo BASE_URL;?>">Enslaved</a>
                </div>
                <div class="dropdown-menu">
                    <div class="responsive-menu">
                        <a><a class="nav-link" id="menu-button">Menu</a><span class="dropdown-button"><img class="hamburger" src="<?php echo BASE_URL;?>assets/images/hamburger.svg" alt="hambrger.svg"/></span></a>
                    </div>
                </div>
                <div class="rightnav">
                    <ul class="nav-menu">
                        <li class="nav-item"><img class="search-icon" src="<?php echo BASE_URL;?>/assets/images/Search.svg"/><a class="nav-link unselected" id="search" href="<?php echo BASE_URL;?>search">Search</a></li>
                        <li class="nav-item drop-link">
                            <a class="nav-link unselected" id="explore" href="<?php echo BASE_URL;?>explore">Explore</a>
                            <span class="drop-carat"><img src="<?php echo BASE_URL;?>/assets/images/Arrow.svg"/></span>
                            <ul class="sub-list">
                                <li class="subwrap" id="explore-sub">
                                    <a class="nav-sublink" href="<?php echo BASE_URL;?>explorePeople">People</a>
                                    <a class="nav-sublink" href="<?php echo BASE_URL;?>exploreEvents">Events</a>
                                    <a class="nav-sublink" href="<?php echo BASE_URL;?>explorePlaces">Places</a>
                                    <a class="nav-sublink" href="<?php echo BASE_URL;?>exploreSources">Sources</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item drop-link">
                            <a class="nav-link unselected" id="visualize" href="<?php echo BASE_URL;?>">Visualize</a>
                            <span class="drop-carat"><img src="<?php echo BASE_URL;?>/assets/images/Arrow.svg"/></span>
                            <ul class="sub-list">
                                <li class="subwrap" id="visualize-sub">
                                    <a class="nav-sublink" href="<?php echo BASE_URL;?>">Space</a>
                                    <a class="nav-sublink" href="<?php echo BASE_URL;?>">Time</a>
                                    <a class="nav-sublink" href="<?php echo BASE_URL;?>">Data</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link unselected" id="stories" href="<?php echo BASE_URL;?>stories">Stories</a></li>
                        <li class="nav-item"><a class="nav-link unselected" id="projects" href="<?php echo BASE_URL;?>projects">Projects</a></li>
                        <li class="nav-item drop-link">
                            <a class="nav-link unselected" id="about" href="<?php echo BASE_URL;?>about">About</a>
                            <span class="drop-carat"><img src="<?php echo BASE_URL;?>/assets/images/Arrow.svg"/></span>
                            <ul class="sub-list">
                                <li class="subwrap" id="about-sub">
                                    <a class="nav-sublink" href="<?php echo BASE_URL;?>getInvolved">Get Involved</a>
                                    <a class="nav-sublink" href="<?php echo BASE_URL;?>ourPartners">Our Partners</a>
                                    <a class="nav-sublink" href="<?php echo BASE_URL;?>contactUs">Contact Us</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </header>