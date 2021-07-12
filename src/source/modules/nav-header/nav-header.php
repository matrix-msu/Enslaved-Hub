<script type="text/javascript" src="<?php echo BASE_MODULE_URL;?>nav-header/nav-header.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo BASE_MODULE_URL;?>nav-header/nav-header.css">

<header class="nav-header">
    <div class="container-fluid">
        <nav class="navbar navbar-nav">
            <div class="left-section">
                <a href="<?php echo BASE_URL;?>"><img class="logo-link" src="<?php echo BASE_IMAGE_URL;?>logo.svg" alt="logo.svg"></a>
            </div>
            <div class="dropdown-menu">
                <div class="responsive-menu">
                    <a class="nav-link menu-button">Menu <img class="hamburger" src="<?php echo BASE_IMAGE_URL;?>hamburger.svg" alt="hambrger.svg"/></a>
                </div>
            </div>
            <div class="right-section">
                <ul class="nav" id="nav-menu">
                    <li class="nav-item"><a class="nav-link unselected" id="index" href="<?php echo BASE_URL;?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link unselected" id="about" href="#">About</a></li>
                    <li class="nav-item"><a class="nav-link unselected" id="browse" href="<?php echo BASE_URL;?>browse">Browse</a></li>
                    <li class="nav-item" id="drop-link">
                        <a class="nav-link unselected" id="more">More</a>
                        <span class="drop-carat">
                                    <img src="<?php echo BASE_URL;?>assets/images/chevron-down.svg" alt="down-arrow.svg"/>
                                </span>
                        <ul>
                            <li class="browse-sub">
                                <a class="nav-sublink" href="<?php echo BASE_URL;?>blog">Blog</a>
                                <a class="nav-sublink" href="<?php echo BASE_URL;?>carousel">Carousel</a>
                                <a class="nav-sublink" href="<?php echo BASE_URL;?>drawers">Drawers</a>
                                <a class="nav-sublink" href="<?php echo BASE_URL;?>fullRecord">Full Record</a>
                                <a class="nav-sublink" href="<?php echo BASE_URL;?>fullRecord-2">Full Record Alt</a>
                                <a class="nav-sublink" href="<?php echo BASE_URL;?>imageCardGrid">Image Card Grid</a>
                                <a class="nav-sublink" href="<?php echo BASE_URL;?>imageCardGrid-2">Image Card Grid Alt</a>
                                <a class="nav-sublink" href="<?php echo BASE_URL;?>mediaRecords">Media Records</a>
                                <a class="nav-sublink" href="<?php echo BASE_URL;?>mediaRecords-2">Media Records Alt</a>
                                <a class="nav-sublink" href="<?php echo BASE_URL;?>mediaRecord-one-image-ex">Media Record (single image)</a>
                                <a class="nav-sublink" href="<?php echo BASE_URL;?>mediaRecord">Media Record</a>
                                <a class="nav-sublink" href="<?php echo BASE_URL;?>tabs-2">Tabs</a>
                                <a class="nav-sublink" href="<?php echo BASE_URL;?>tabs-many">Many Tabs</a>
                                <a class="nav-sublink" href="<?php echo BASE_URL;?>text-modal">Text Modal</a>
                                <a class="nav-sublink" href="<?php echo BASE_URL;?>text-with-nav">Text with Navigation</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link unselected" id="essays" href="<?php echo BASE_URL;?>essays">Essays</a></li>
                    <li class="nav-item"><a class="nav-link unselected" id="search" href="<?php echo BASE_URL;?>search">Search</a></li>
                </ul>
            </div>
        </nav>
    </div>
</header>
