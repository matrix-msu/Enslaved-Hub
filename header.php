<!-- Header/Navigation -->
<header class="nav-header">
    <div class="headerwrap">
        <div class="leftnav">
            <div class="logo"><a href="<?php echo BASE_URL;?>"><img src="<?php echo BASE_IMAGE_URL;?>Logo.svg" width="157" height="42" alt="Enslaved People of Historic Slave Trade"/></a></div>
        </div>
        <div class="dropdown-menu">
            <div class="responsive-menu">
                <a><a class="nav-link" id="menu-button">Menu</a><span class="dropdown-button"><img class="hamburger" src="<?php echo BASE_URL;?>assets/images/hamburger.svg" alt="hambrger.svg"/></span></a>
            </div>
        </div>
        <div class="rightnav">
            <ul class="nav-menu">
                <li class="nav-item"><img class="search-icon" src="<?php echo BASE_IMAGE_URL;?>Search.svg" alt="search icon"/><a class="nav-link unselected" id="search" href="<?php echo BASE_URL;?>search">Search</a></li>
                <li class="nav-item drop-link">
                    <a class="nav-link unselected" id="explore" href="<?php echo BASE_URL;?>explore">Explore</a>
                    <span class="drop-carat"><img src="<?php echo BASE_IMAGE_URL;?>Arrow.svg" alt="dropdown carrat"/></span>
                    <ul class="sub-list">
                        <li class="subwrap" id="explore-sub">
                            <a class="nav-sublink" href="<?php echo BASE_URL;?>explore/people">People</a>
                            <a class="nav-sublink" href="<?php echo BASE_URL;?>explore/events">Events</a>
                            <a class="nav-sublink" href="<?php echo BASE_URL;?>explore/places">Places</a>
                            <a class="nav-sublink" href="<?php echo BASE_URL;?>explore/sources">Sources</a>
                            <a class="nav-sublink" href="<?php echo BASE_URL;?>explore/projects">Projects</a>
                        </li>
                    </ul>
                </li>
                <!-- <li class="nav-item drop-link visualize-hide">
                    <a class="nav-link unselected" id="visualize" href="<?php echo BASE_URL;?>">Visualize</a>
                    <span class="drop-carat"><img src="<?php echo BASE_IMAGE_URL;?>Arrow.svg"/></span>
                    <ul class="sub-list">
                        <li class="subwrap" id="visualize-sub">
                            <a class="nav-sublink" href="<?php echo BASE_URL;?>">Space</a>
                            <a class="nav-sublink" href="<?php echo BASE_URL;?>">Time</a>
                            <a class="nav-sublink" href="<?php echo BASE_URL;?>">Data</a>
                        </li> 
                    </ul>
                </li> -->
                <li class="nav-item"><a class="nav-link unselected" id="stories" href="<?php echo BASE_URL;?>stories">Stories</a></li>
                <!-- <li class="nav-item"><a class="nav-link unselected" id="projects" href="<?php echo BASE_URL;?>projects">Projects</a></li> -->
                <li class="nav-item drop-link">
                    <a class="nav-link unselected" id="about" href="<?php echo BASE_URL;?>about">About</a>
                    <span class="drop-carat"><img src="<?php echo BASE_IMAGE_URL;?>Arrow.svg" alt="dropdown arrow"/></span>
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
