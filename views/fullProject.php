<!-- Page author: Drew Schineller-->
<!-- Heading image and title container-->
<div class="container header full-project">
    <div class="container middlewrap">
        <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>projects"><span id="previous-title">Projects // </span></a><span id="current-title">Project Name</span></h4>
        <div class="project-headers">
            <h1>Project Name</h1>
            <h2><span>234</span> Resources</h2>
        </div>
    </div>
</div>
<div class="jump-buttons project-button">
    <div class="jumpwrap">
        <button class="jump-button" id="details">Go to Project Site</button>
    </div>
</div>
<!-- info container-->
<div class="container info">
    <div class="container infowrap">
        <p>Brief info on Section. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur a tempor incididunt ut labore et dolore magna Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur.</p>
    </div>
</div>
<!-- affiliations -->
<!-- <div class="affiliations">
    <h2>Affiliations</h2>
    <hr>
    <div class="affiliates">
        <img src="<?php echo BASE_URL;?>assets/images/bl_logo_100.png" alt="British Library logo"/>
        <img src="<?php echo BASE_URL;?>assets/images/MSU.svg" alt="MSU logo"/>
    </div>
</div> -->
<!-- project leads -->
<div class="project-leads">
    <div class="leadwrap">
        <h2>Principal Investigators</h2>
        <hr>
        <div class="leads">
            <!-- <div class="lead-card">
                <div class="lead-photo" style="background-image: url(<?php echo BASE_URL;?>assets/images/Oval2.jpg);"></div>
                <div class="lead-text">
                    <h3>Person Name</h3>
                    <div class="view"><a href="#">View Profile <div class="view-arrow"></div></a></div>
                </div>
            </div>
            <div class="lead-card">
                <div class="lead-photo" style="background-image: url(<?php echo BASE_URL;?>assets/images/Oval3.jpg);"></div>
                <div class="lead-text">
                    <h3>Person Name</h3>
                    <div class="view"><a href="#">View Profile <div class="view-arrow"></div></a></div>
                </div>
            </div>
            <div class="lead-card">
                <div class="lead-photo" style="background-image: url(<?php echo BASE_URL;?>assets/images/Oval4.jpg);"></div>
                <div class="lead-text">
                    <h3>Person Name</h3>
                    <div class="view"><a href="#">View Profile <div class="view-arrow"></div></a></div>
                </div>
            </div> -->
        </div>
    </div>
</div>
<!-- Contributors -->
<div class="project-contributors">
    <div class="contributorwrap">
        <h2>Contributors</h2>
        <hr>
        <div class="contributors">
            <!-- <div class="contributor-card">
                <div class="contributor-photo" style="background-image: url(<?php echo BASE_URL;?>assets/images/Oval2.jpg);"></div>
                <div class="contributor-text">
                    <h3>Person Name</h3>
                    <div class="view"><a href="#">View Profile <div class="view-arrow"></div></a></div>
                </div>
            </div>
            <div class="contributor-card">
                <div class="contributor-photo" style="background-image: url(<?php echo BASE_URL;?>assets/images/Oval3.jpg);"></div>
                <div class="contributor-text">
                    <h3>Person Name</h3>
                    <div class="view"><a href="#">View Profile <div class="view-arrow"></div></a></div>
                </div>
            </div>
            <div class="contributor-card">
                <div class="contributor-photo" style="background-image: url(<?php echo BASE_URL;?>assets/images/Oval4.jpg);"></div>
                <div class="contributor-text">
                    <h3>Person Name</h3>
                    <div class="view"><a href="#">View Profile <div class="view-arrow"></div></a></div>
                </div>
            </div> -->
        </div>
    </div>
</div>
<!-- searchbar -->
<form class="search-projects">
    <div class="searchwrap">
        <label for="searchbar" class="sr-only">searhbar</label>
        <input id="searchbar" class="search-field main-search" type="text" name="searchbar" placeholder="Search through the Project's Resources here"/>
        <button class="search-icon-2" type="submit"><img src="<?php echo BASE_URL;?>/assets/images/Search-dark.svg" alt="search-icon"></button>
        <!-- <img class="search-close" src="<?php echo BASE_URL;?>/assets/images/Close.svg"/> -->
    </div>
</form>

<!-- search result area -->
<main class="search-results">
    <div class="filter-menu">
        <ul>
            <h3>Show Results For</h3>
            <ul class="catmenu" id="submenu">
                <li>
                    <label>
                        <input id="checkBox" type="checkbox">
                        <img src="<?php echo BASE_URL;?>assets/images/Person-dark.svg" alt="person icon">
                        <p>People</p>
                        <span></span>
                    </label>
                </li>
                <li>
                    <label>
                        <input id="checkBox" type="checkbox">
                        <img src="<?php echo BASE_URL;?>assets/images/Place-dark.svg" alt="loction icon">
                        <p>Places</p>
                        <span></span>
                    </label>
                </li>
                <li>
                    <label>
                        <input id="checkBox" type="checkbox">
                        <img src="<?php echo BASE_URL;?>assets/images/Event-dark.svg" alt="event icon">
                        <p>Events</p>
                        <span></span>
                    </label>
                </li>
                <li>
                    <label>
                        <input id="checkBox" type="checkbox">
                        <img src="<?php echo BASE_URL;?>assets/images/Source-dark.svg" alt="source icon">
                        <p>Sources</p>
                        <span></span>
                    </label>
                </li>
                <li>
                    <label>
                        <input id="checkBox" type="checkbox">
                        <img src="<?php echo BASE_URL;?>assets/images/Project-dark.svg" alt="project icon">
                        <p>Projects</p>
                        <span></span>
                    </label>
                </li>
            </ul>
            <!-- General Filtering -->
            <hr>
            <li class="cat-cat">General Filtering<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
            </li>
            <ul id="mainmenu">

                <li class="filter-cat">Country<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
                </li>
                <ul id="submenu">
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                </ul>
                <li class="filter-cat">Region<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
                </li>
                <ul id="submenu">
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                </ul>
                <li class="filter-cat">Decade<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
                </li>
                <ul id="submenu">
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                </ul>
                <li class="filter-cat">Date Select<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
                </li>
                <ul id="submenu">
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                </ul>
            </ul>
            <!-- People Filtering -->
            <hr>
            <li class="cat-cat">People Filtering<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
            </li>
            <ul id="mainmenu">
                <li class="filter-cat">Gender<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
                </li>
                <ul id="submenu">
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Unidentified <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Male <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Female <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                </ul>
                <li class="filter-cat">Origin<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
                </li>
                <ul id="submenu">
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                </ul>
                <li class="filter-cat">Age<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
                </li>
                <ul id="submenu">
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Age Range <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Age Range <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Age Range <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                </ul>
                <li class="filter-cat">Age Category<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
                </li>
                <ul id="submenu">
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Age Range <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Age Range <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Age Range <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                </ul>
                <li class="filter-cat">Color<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
                </li>
                <ul id="submenu">
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                </ul>
                <li class="filter-cat">Occupation<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
                </li>
                <ul id="submenu">
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                </ul>
                <li class="filter-cat">Relationship<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
                </li>
                <ul id="submenu">
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                </ul>
                <li class="filter-cat">Role<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span></li>
                <ul id="submenu">
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                </ul>
            </ul>
            <!-- Event Filtering -->
            <hr>
            <li class="cat-cat">Event Filtering<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
            </li>
            <ul id="mainmenu">

                <li class="filter-cat">Event Type<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
                </li>
                <ul id="submenu">
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                </ul>
                <li class="filter-cat">Event Date<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
                </li>
                <ul id="submenu">
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                </ul>
            </ul>
            <!-- Place Filtering -->
            <hr>
            <li class="cat-cat">Place Filtering<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
            </li>
            <ul id="mainmenu">

                <li class="filter-cat">Country<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
                </li>
                <ul id="submenu">
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                </ul>
                <li class="filter-cat">Region<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
                </li>
                <ul id="submenu">
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                </ul>
                <li class="filter-cat">Province<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
                </li>
                <ul id="submenu">
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                </ul>
                <li class="filter-cat">City<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
                </li>
                <ul id="submenu">
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                </ul>
            </ul>
            <!-- Project Filtering -->
            <hr>
            <li class="cat-cat">Project Filtering<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
            </li>
            <ul id="mainmenu">

                <li class="filter-cat">Project<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
                </li>
                <ul id="submenu">
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                </ul>
            </ul>
            <!-- Media Filtering -->
            <hr>
            <li class="cat-cat">Media Filtering<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
            </li>
            <ul id="mainmenu">

                <li class="filter-cat">Media Type<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
                </li>
                <ul id="submenu">
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                </ul>
                <li class="filter-cat">Repository<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
                </li>
                <ul id="submenu">
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                </ul>
                <li class="filter-cat">Contributing Scholar<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="down-arrow"></span>
                </li>
                <ul id="submenu">
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                </ul>
            </ul>


        </ul>
    </div>


    <div id="searchResults">
        <h2 class="showing-results">Showing 24 of 54,375,213 Results</h2>
        <div id="search-result-controls">
            <span class="show-filter" class="show-filter"><img src="<?php echo BASE_URL;?>assets/images/arrow-right.svg" alt="show filter menu button"> Show Filter Menu</span>
            <span class="view-modes">
                <span class="grid-view view-toggle">
                    <img class="grid-icon show" src="../assets/images/List.svg" alt="grid view button">
                    <p class="tooltip">View Grid</p>
                </span>
                <span class="table-view view-toggle">
                    <img class="table-icon" src="../assets/images/table-Active.svg" alt="table view button">
                    <p class="tooltip">View Table</p>
                </span>
                <span class="time-view view-toggle visualize-hide">
                    <img class="time-icon" src="../assets/images/time2.svg" alt="time view button">
                    <p class="tooltip">View Time</p>
                </span>
                <span class="map-view view-toggle visualize-hide">
                    <img class="map-icon" src="../assets/images/map.svg" alt="map view button">
                    <p class="tooltip">View Map</p>
                </span>
            </span>
            <span class="sorting-dropdowns">
                <span class="align-center sort-by">Sort By <img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="results per page button">
                    <ul id="sortmenu" class="sort-by">
                        <li>A - Z</li>
                        <li>Z - A</li>
                    </ul>
                </span>
                <span class="align-center results-per-page"><span>#</span> Per Page <img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="results per page button">
                    <ul id="sortmenu" class="results-per-page">
                        <li><span>12</span> Per Page</li>
                        <li><span>24</span> Per Page</li>
                        <li><span>36</span> Per Page</li>
                        <li><span>48</span> Per Page</li>
                    </ul>
                </span>
            </span>
        </div>
        <div id="search-result-configure-download-row">
            <span class="align-left">
                <a class="modal">Configure Table Columns</a>
            </span>
            <span class="align-right"><b>Download:</b> <span>Current View</span> | <span>All Results</span></span>
        </div>
        <div id="search-result-wrap">
            <div id="search-result-table">
                <table id="search-results">
                    <thead>
                        <tr>
                            <th class="name">NAME</th>
                            <th class="gender">GENDER</th>
                            <th class="status">STATUS</th>
                            <th class="location">LOCATION</th>
                            <th class="dateRange">DATE RANGE</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="result-column">
                <div class="cardwrap">
                    <ul class="row">
                    </ul>
                </div>
            </div>
        </div>
        <div id="pagination">
            <input class="current-page" type="hidden" value="1">
            <span id="pagiLeft" class="align-left"><div id="pagiLeftArrow"></div></span>
            <div class="page-numbers">
                <span class="num pagi-first">1</span>
                <span class="dotsLeft">...</span>
                <span class="num one"></span>
                <span class="num two"></span>
                <span class="num three"></span>
                <span class="num four"></span>
                <span class="num five"></span>
                <span class="dotsRight">...</span>
                <span class="num pagi-last">310</span>
            </div>
            <span id="pagiRight" class="align-right"><div id="pagiRightArrow"></div></span>
        </div>
    </div>
</main>

<div class="modal-view">
    <div class="config-table-modal">
        <div class="config-table-modal-content-wrap">
            <div class="close"><img src="<?php echo BASE_URL;?>assets/images/x.svg" alt="close modal button"></div>
            <h4>Configure Table Columns</h4>
            <p>Choose Group of Variables
                <label for="dropdown-select" class="sr-only">dropdown select</label>
                <select name="dropdown-select" id="dropdown-select">
                    <option value="1">Name of Variable Group</option>
                    <option value="2">####</option>
                    <option value="3">####</option>
                    <option value="4">####</option>
                </select>
            </p>
            <div>
                <div class="left-col">
                    <p>Available Columns</p>
                    <div>
                        <ul id="available-cols">
                            <li class="left">ID</li>
                            <li class="left">Sex</li>
                            <li class="left">Enslaved Role</li>
                            <li class="left">Origin / Ethnicity</li>
                            <li class="left">Color</li>
                            <li class="left">Occupation</li>
                            <li class="left">Column Name</li>
                            <li class="left">Column Name</li>
                            <li class="left">Column Name</li>
                            <li class="left">Column Name</li>
                            <li class="left">Column Name</li>
                            <li class="left">Column Name</li>
                        </ul>
                    </div>
                </div>
                <div class="arrow-wrap">
                    <img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="add item">
                    <img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="remove item">
                </div>
                <div class="right-col">
                    <p>Selected Columns</p>
                    <div>
                        <ul id="selected-cols">
                            <li class="right">Column Name1</li>
                            <li class="right">Column Name2</li>
                            <li class="right">Column Name3</li>
                            <li class="right">Column Name4</li>
                            <li class="right">Column Name5</li>
                            <li class="right">Column Name6</li>
                        </ul>
                        <img class="down" src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="move down">
                        <img class="up" src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="move up">
                    </div>
                </div>
            </div>
            <div class="update-columns-button">Update Table Columns</div>
        </div>
    </div>
</div>

<!-- main site link -->
<div class="project-site">
    <p>Visit the Project's main site at:</p>
    <a href="#">project.past.matrix.msu.edu</a>
</div>

<script>
    var QID = <?php echo json_encode(QID); ?>;
    var infourl = <?php echo json_encode(BASE_WIKI_URL . 'wiki/Special:EntityData/' . QID); ?>;
</script>
<script src="<?php echo BASE_URL;?>assets/javascripts/pagination.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/fullProject.js"></script>
