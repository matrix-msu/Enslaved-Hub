<!-- Heading image and title container-->
<div class="container header search-page">
    <div class="image-container search-page">
        <div class="container middlewrap search-page">
            <?php
//die;
            $typeTitle = "";
            $typeLower = "";
            $currentTitle = 'Search';

            include BASE_LIB_PATH."variableIncluder.php";

            if (count($_GET) > 0){
                $typeLower = array_keys($_GET)[0];
                $typeTitle = ucwords(str_replace('_', ' ', $typeLower));

                $currentQ = $_GET[$typeLower];
                $currentTitle = str_replace('_', ' ', $currentQ);
            }

            $upperForm = ucfirst(EXPLORE_FORM);
            $showPath = false;
            $fromBrowse = false;

            //Conditions to put the previous page header in (Not being used right now)
            if(EXPLORE_FORM != null && EXPLORE_FORM != 'all' && EXPLORE_FORM != 'results' && EXPLORE_FORM != 'category'){
                $showPath = true;
                $fromBrowse = true;
            }
//die;
            ?>
            <!-- <h4 class="last-page-header" style="<?php  echo (!$showPath) ? 'display:none' : '' ?> ">
                <a id="last-page" class="prev1" href="<?php echo BASE_URL. 'explore/' .EXPLORE_FORM ?>">
                    <span id="previous-title"><?php echo $upperForm ?></span>
                </a>
                <a id="last-page" class="prev2" href="<?php echo BASE_URL. 'explore/' .EXPLORE_FORM. '/' .$typeLower ?>">
                    <span id="previous-title"><?php echo ($typeTitle != "") ? "/ " . $typeTitle : "" ?></span>
                </a>
                <span id="current-title"><?php echo ($currentTitle != "") ? "/ " . $currentTitle : "" ?></span>
            </h4> -->
            <div class="search-title">
                <h1>Search Results<?php //echo $currentTitle;?></h1>
            </div>
            <?php //if(!$fromBrowse) { ?>
            <div class="heading-search">
            <p>Start a search across <?php //echo counterofAllitems();?> records from the Atlantic Slave Trade <a class="text-link show-desktop-only" href="<?php echo BASE_URL;?>advancedSearch">Go to Advanced Search</a></p>
            <p class="hide-desktop-only mt-xs"><a class="text-link" href="<?php echo BASE_URL;?>advancedSearch">Go to Advanced Search</a></p>
            <form class="search-form" action="<?= BASE_URL ?>search/all">
                <label for="searchbar" class="sr-only">searchbar</label>
                <input id="searchbar" class="search-field main-search" type="text" name="searchbar" placeholder="eg: People, Places, Events, Sources, Projects, Captains, Ships, Voyages, etc."/>
                <button class="search-icon-2" type="submit"><img src="<?php echo BASE_URL;?>/assets/images/Search.svg" alt="search-icon"></button>
                <!-- <img class="search-close" src="<?php echo BASE_URL;?>/assets/images/Close.svg"/> -->
            </form>
            </div>
            <?php //} ?>
        </div>
        <div class="image-background-overlay  search-page"></div>
        <img class="header-background search-page" src="<?php echo BASE_URL;?>assets/images/enslaved-header-bg3.jpg" alt="Enslaved Background Image"></div>
    </div>
</div>

<main class="search-results">
    <div class="filter-menu show">
        <ul>
            <?php if(!$fromBrowse) { ?>
            <!-- <h2>Show Results For</h2> -->
            <ul class="catmenu" id="submenu">
            </ul>
            <!-- <hr> -->
            <?php } ?>
            <!-- People Filtering -->

            <li class="cat-cat">People</li>
            <ul id="mainmenu">

                <?php
                $fullArray = $GLOBALS["FILTER_ARRAY"]['people'];
                $extraCats = ['Status', 'Occupation'];
                foreach($extraCats as $extra){
                    $fullArray[] = $extra;
                }
                foreach ($fullArray as $type) {
                    $catLower = strtolower(str_replace(" ", "_", $type)); ?>
                <li class="filter-cat" name="<?php echo $catLower; ?>"><?php echo $type; ?><span class="align-right"><img src="<?php echo BASE_IMAGE_URL;?>chevron.svg" alt="drop arrow"></span>
                    <ul id="submenu">
                        <?php
                        $typeCats = array();
                        if (array_key_exists($type, $GLOBALS['FILTER_TO_FILE_MAP'])){
                            $typeCats = $GLOBALS['FILTER_TO_FILE_MAP'][$type];
                        }
                        foreach ($typeCats as $category => $qid) { ?>
                            <li class="hide-category">
                                <label class="<?php echo $catLower; ?>">
                                    <input id="checkBox" type="checkbox" value="<?php echo $category; ?>" data-qid="<?php echo $qid; ?>">
                                    <p><?php echo $category; ?> <em></em></p>
                                    <span></span>
                                </label>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
                <?php } ?>

            </ul>
            <!-- Event Filtering -->
            <li class="cat-cat">Event</li>
            <ul id="mainmenu">

                <?php foreach ($GLOBALS["FILTER_ARRAY"]['events'] as $type) {
                    $catLower = strtolower(str_replace(" ", "_", $type)); ?>
                    <li class="filter-cat" name="<?php echo $catLower; ?>"><?php echo $type; ?><span class="align-right"><img src="<?php echo BASE_IMAGE_URL;?>chevron.svg" alt="drop arrow"></span>
                        <ul id="submenu">
                        <?php
                        $typeCats = array();
                        if (array_key_exists($type, $GLOBALS['FILTER_TO_FILE_MAP'])){
                            $typeCats = $GLOBALS['FILTER_TO_FILE_MAP'][$type];
                        }
                        foreach ($typeCats as $category => $qid) { ?>
                            <li class="hide-category">
                                <label class="<?php echo $catLower; ?>">
                                    <input id="checkBox" type="checkbox" value="<?php echo $category; ?> " data-qid="<?php echo $qid; ?>">
                                    <p><?php echo $category; ?> <em></em></p>
                                    <span></span>
                                </label>
                            </li>
                        <?php }
                        if ($catLower == 'date'){
                            echo '<div class="search-section">
                                <div class="inputwrap">
                                <label for="startYear">Start Year</label>
                                <input type="text" name="startyear" maxlength="4" id="startyear" pattern="\d{4}" required/>
                                </div>
                                <div class="inputwrap">
                                <label for="endYear">End Year</label>
                                <input type="text" name="endyear" maxlength="4" id="endyear" pattern="\d{4}" required/>
                                </div>
                                <input class="event-date-range" type="hidden" name="date" value=""/>
                                </div>';
                        }
                        ?>

                        </ul>
                    </li>
                <?php } ?>

            </ul>
            <!-- Place Filtering -->
            <li class="cat-cat">Place</li>
            <ul id="mainmenu">

                <?php foreach ($GLOBALS["FILTER_ARRAY"]['places'] as $type) {
                    $catLower = strtolower(str_replace(" ", "_", $type)); ?>
                    <li class="filter-cat" name="<?php echo $catLower; ?>"><?php echo $type; ?><span class="align-right"><img src="<?php echo BASE_IMAGE_URL;?>chevron.svg" alt="drop arrow"></span>
                        <ul id="submenu">
                        <?php
                        $typeCats = array();
                        if (array_key_exists($type, $GLOBALS['FILTER_TO_FILE_MAP'])){
                            $typeCats = $GLOBALS['FILTER_TO_FILE_MAP'][$type];
                        }

                        if ($type == "Modern Countries"){
                            // use the contry codes instead of qids for countries
                            foreach ($typeCats as $countryCode => $countryName) { ?>
                                <li >
                                    <label class="<?php echo $catLower; ?>">
                                        <input id="checkBox" type="checkbox" value="<?php echo $category; ?>" data-countryCode="<?php echo $countryCode; ?>">
                                        <p><?php echo $countryName; ?> <em></em></p>
                                        <span></span>
                                    </label>
                                </li>
                            <?php }
                        } else {
                            foreach ($typeCats as $category => $qid) { ?>
                                <li class="hide-category">
                                    <label class="<?php echo $catLower; ?>">
                                        <input id="checkBox" type="checkbox" value="<?php echo $category; ?>" data-qid="<?php echo $qid; ?>">
                                        <p><?php echo $category; ?> <em></em></p>
                                        <span></span>
                                    </label>
                                </li>
                            <?php }
                        } ?>

                        </ul>
                    </li>
                <?php } ?>

            </ul>
            <!-- Source Filtering -->
            <li class="cat-cat">Source</li>
            <ul id="mainmenu">

                <?php foreach ($GLOBALS["FILTER_ARRAY"]['sources'] as $type) {
                    $catLower = strtolower(str_replace(" ", "_", $type)); ?>
                        <li class="filter-cat" name="<?php echo $catLower; ?>"><?php echo $type; ?><span class="align-right"><img src="<?php echo BASE_IMAGE_URL;?>chevron.svg" alt="drop arrow"></span>
                        <ul id="submenu">
                        <?php
                        $typeCats = array();
                        if (array_key_exists($type, $GLOBALS['FILTER_TO_FILE_MAP'])){
                            $typeCats = $GLOBALS['FILTER_TO_FILE_MAP'][$type];
                        }
                        foreach ($typeCats as $category => $qid) { ?>
                            <li class="hide-category">
                                <label class="<?php echo $catLower; ?>">
                                    <input id="checkBox" type="checkbox" value="<?php echo $category; ?>" data-qid="<?php echo $qid; ?>">
                                    <p><?php echo $category; ?> <em></em></p>
                                    <span></span>
                                </label>
                            </li>
                        <?php } ?>
                        </ul>
                    </li>
                <?php } ?>

            </ul>
            <!-- Project Filtering -->
            <li class="cat-cat">Project</li>
            <ul id="mainmenu">

                <?php foreach (['Projects'] as $type) {
                    $catLower = strtolower(str_replace(" ", "_", $type)); ?>
                    <li class="filter-cat" name="<?php echo $catLower; ?>"><?php echo $type; ?><span class="align-right"><img src="<?php echo BASE_IMAGE_URL;?>chevron.svg" alt="drop arrow"></span>
                        <ul id="submenu">
                        <?php
                        $typeCats = array();

                        if (array_key_exists($type, $GLOBALS['FILTER_TO_FILE_MAP'])){
                            $typeCats = $GLOBALS['FILTER_TO_FILE_MAP'][$type];
                        }

                        foreach ($typeCats as $category => $qid) { ?>
                            <li>
                                <label class="<?php echo $catLower; ?>">
                                    <input id="checkBox" type="checkbox" value="<?php echo $category; ?>" data-qid="<?php echo $qid; ?>">
                                    <p><?php echo $category; ?></p>
                                    <span></span>
                                </label>
                            </li>
                        <?php } ?>
                        </ul>
                    </li>
                <?php } ?>

            </ul>

        </ul>
    </div>


    <div id="searchResults">
        <h2 class="showing-results"></h2>
        <div id="search-result-controls">
            <!-- <span class="show-filter" class="show-filter"><img src="<?php echo BASE_URL;?>assets/images/arrow-right.svg" alt="show filter menu button"> Show Filter Menu</span> -->
            <span class="view-modes">
                <span class="grid-view view-toggle">
                    <img class="grid-icon show" src="../assets/images/List.svg" alt="grid view button">
                    <p class="tooltip">View Grid</p>
                </span>
                <span class="table-view view-toggle">
                    <img class="table-icon" src="../assets/images/Table.svg" alt="table view button">
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
                <span class="align-center sort-by">Sort By <img src="<?php echo BASE_URL;?>assets/images/chevron.svg" alt="results per page button">
                    <ul id="sortmenu" class="sort-by">
                        <li>A - Z</li>
                        <li>Z - A</li>
                    </ul>
                </span>
                <span class="align-center results-per-page"><span>9</span> Per Page <img src="<?php echo BASE_URL;?>assets/images/chevron.svg" alt="results per page button">
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
            <span class="align-right"><b>Download:</b> <span id="Download_selected">Current View</span> | <span id="Download_all">All Results</span></span>
        </div>

        <div class="filter-cards">
            <div class="option-wrap">
                <p>Option Title</p>
                <img class="remove" src="<?php echo BASE_IMAGE_URL;?>x-dark.svg" />
            </div>
        </div>
        <div class="search-record-connections">
            <div class="connectionwrap">
                <div class="categories"></div>
                <div class="connection-cards">
                    <ul class="connect-row">
                    </ul>
                    <a class="search-all"></a>
                    <!-- <div class="load-more"><h4>Load More</h4></div> -->
                </div>
            </div>
        </div>
        <div id="search-result-wrap">
            <div id="search-result-table">
                <table id="search-results">
                    <thead>
                        <tr>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="result-column">
                <div class="cardwrap">
                    <ul class="cards">
                    </ul>
                </div>
            </div>
        </div>
        <div id="pagination">
            <input class="current-page" type="hidden" value="1">
            <div class="pagi-left"><img class="chevron" src="<?php echo BASE_URL;?>assets/images/chevron.svg" alt="Previous Page"></div>
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
            <div class="pagi-right"><img class="chevron" src="<?php echo BASE_URL;?>assets/images/chevron-light.svg" alt="Next Page"></div>
        </div>
    </div>
</main>

<div class="modal-view">
    <div class="config-table-modal">
        <div class="config-table-modal-content-wrap">
            <div class="close"><img src="<?php echo BASE_URL;?>assets/images/x.svg" alt="close modal button"></div>
            <h4>Configure Table Columns</h4>
            <p>Choose Group of Variables
								<label for="dropdown-select" class="sr-only">dropdown</label>
                <select id="dropdown-select" name="dropdown-select">
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

<script src="<?php echo BASE_URL;?>assets/javascripts/pagination.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/searchResults.js"></script>
<script src="<?php echo BASE_URL;?>modules/modal/modal.js"></script>
