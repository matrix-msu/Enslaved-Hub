<!-- Heading image and title container-->

<div class="container header search-page">
    <div class="image-container search-page">
        <div class="container middlewrap search-page">
            <?php
            $typeTitle = "";
            $typeLower = "";
            $currentTitle = ucfirst(EXPLORE_FORM) . " Results";
            $prevPage = explode("/",$_SERVER["HTTP_REFERER"]);
            $prevPage = end($prevPage);
            $link1 = BASE_URL. 'explore/' .EXPLORE_FORM;
            $link2;
            $upperForm = ucfirst(EXPLORE_FORM);

            include BASE_LIB_PATH."variableIncluder.php";

            if (count($_GET) >= 0){
                if($prevPage == "search"){
                  $currentTitle = "Results";
                  $typeLower = $prevPage;
                  $typeTitle = "";

                  $link1 = BASE_URL . $typeLower;
                  $upperForm = "Search";
                }
                else if ($prevPage == "advancedSearch"){
                  $currentTitle = "Results";
                  $typeLower = $prevPage;
                  $typeTitle = "";
                  $link1 = BASE_URL . $typeLower;
                  $upperForm = "Advanced Search";
                }
                else if ($prevPage == ""){
                  $currentTitle = "Results";
                  $typeLower = $prevPage;
                  $typeTitle = "";
                  $link1 = BASE_URL;
                  $link2 = BASE_URL . $typeLower;
                  $upperForm = "Home";
                }
                else if (EXPLORE_FORM == "places" || EXPLORE_FORM == "sources"){
                  $currentTitle = ucfirst(EXPLORE_FORM) . " Results";
                  $typeLower = array_keys($_GET)[0];

                  $currentQ = $_GET[$typeLower];
                  $currentTitle = ucfirst(EXPLORE_FORM) . " Results";
                  $link2 = BASE_URL. 'explore/'. EXPLORE_FORM . '/' . $typeLower;
                  $upperForm = ucfirst(EXPLORE_FORM);
                }
                else{
                  $currentTitle = ucfirst(EXPLORE_FORM) . " Results";
                  $typeLower = array_keys($_GET)[0];
                  $typeTitle = ucwords(str_replace('_', ' ', $typeLower));

                  $currentQ = $_GET[$typeLower];
                  $currentTitle = ucfirst(EXPLORE_FORM) . " Results";
                  $link2 = BASE_URL. 'explore/'. EXPLORE_FORM . '/' . $typeLower;
                  $upperForm = ucfirst(EXPLORE_FORM);
                }
            }

            $showPath = false;
            $fromBrowse = false;

            //Conditions to put the previous page header in (Not being used right now)
            if(EXPLORE_FORM != null && EXPLORE_FORM != 'all' && EXPLORE_FORM != 'results' && EXPLORE_FORM != 'category'){
                $showPath = true;
                $fromBrowse = true;
            }
            ?>
            <h4 class="last-page-header">
                <a id="last-page" class="prev1" href="<?php echo $link1 ?>">
                    <span id="previous-title"><?php echo $upperForm ?></span>
                </a>
                <a id="last-page" class="prev2" href="<?php echo $link2 ?>">
                    <span id="previous-title"><span class="sr-only">Secondary Breadcrumb Link</span><?php echo ($typeTitle != "") ? "/ " . $typeTitle : "" ?></span>
                </a>
                <span id="current-title"><?php echo ($currentTitle != "") ? "/ " . $currentTitle : "" ?></span>
                
                <div class="more-info">
                    <a class="modal">How Does Searching Work?</a>
                </div>
            </h4>
            
            <div class="search-title">
                <h1>Search Results<?php //echo $currentTitle;?></h1>
            </div>
            
            <?php //if(!$fromBrowse) { ?>
            <div class="heading-search">
                <!-- use all counts instead of counterofAllitems() -->
            <p>Start a search across <?php //echo counterofAllitems();?> records from the historical slave trade<a class="text-link show-desktop-only" href="<?php echo BASE_URL;?>advancedSearch">Go to Advanced Search</a></p>
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
        <img class="header-background search-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image"></div>
        <!-- <img class="header-background search-page" src="<?php echo BASE_URL;?>assets/images/enslaved-header-bg3.jpg" alt="Enslaved Background Image"></div> -->
    </div>
</div>

<main class="search-results">
    
    
    <div class="modal-view">
        <div class="config-table-modal">
            <div class="config-table-modal-content-wrap">
            <div class="close"><img src="<?php echo BASE_URL;?>assets/images/x.svg" alt="close modal button"></div>
            <h1 class="mb-20">Searching & Search Results on Enslaved.org</h2>
            <p> It is important to understand that each record represented as a card or row within the search results shown is a representation of a person, event, place, or source within the many datasets that are contributed to Enslaved.org. Multiple projects may have records for the same person, event, or place. </p>
                
                <p>Enslaved.org is doing the hard work to make links between possibly connected records through the use of the controlled vocabularies, “match,” which indicates the same entity across multiple records, and “close match,” which indicates a possible connection between two or more entities. These connections can be found within the Related Records section of the person record. </p>
                
                <p>To see an example of these connections, we suggest you search the name “Valentin LeBlanc.” </p>
                
                <p>We are always inviting subject area experts to review these connections between their data and other records within Enslaved.org. If you are interested in contributing to this work please contact us! </p>
                
                <p>Because many records cover and represent the same geospatial places, the Enslaved.org ontology leverages the use of the predicate “hasBroader” to link to a connecting place record. We use this broader record to connect the place records from the many, many datasets that have contributed places to Enslaved.org like Cuba, Africa, Rio de Janeiro, New Orleans, et al. This feature can be used to jump between projects or datasets that cover the same geospatial location or have the same place name.</p>
            </div>
        </div>
    </div>
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
                $extraCats = ['Status'];
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
                                    <input id="checkBox" type="checkbox" value="<?php echo $category; ?>" data-qid="<?php echo $qid; ?>" data-category="<?php echo $type; ?>">
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
                                    <input id="checkBox" type="checkbox" value="<?php echo $category; ?>" data-qid="<?php echo $qid; ?>"
                                    data-category="<?php echo $type; ?>">
                                    <p><?php echo $category; ?> <em></em></p>
                                    <span></span>
                                </label>
                            </li>
                        <?php }
                        if ($catLower == 'date'){
                            echo '<div class="inputwrap">
                                <label for="startyear">Start Year</label>
                                <input class="nofold" type="number" onKeyPress="if(this.value.length==4) return false;" id="startyear" placeholder="Year"/>
                                </div>
                                <div class="to-field">To</div>
                                <div class="inputwrap">
                                <label for="endyear">End Year </label>
                                <input class="nofold" type="number" onKeyPress="if(this.value.length==4) return false;" id="endyear" placeholder="Year"/>
                                </div>
                                <input id="date-go-btn" type="button" value="Go">';
                        }?>
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
                                        <input id="checkBox" type="checkbox" value="<?php echo $category; ?>" data-countryCode="<?php echo $countryCode; ?>"
                                        data-category="<?php echo $type; ?>">
                                        <p><?php echo $countryName; ?> <em></em></p>
                                        <span></span>
                                    </label>
                                </li>
                            <?php }
                        } else {
                            foreach ($typeCats as $category => $qid) { ?>
                                <li class="hide-category">
                                    <label class="<?php echo $catLower; ?>">
                                        <input id="checkBox" type="checkbox" value="<?php echo $category; ?>" data-qid="<?php echo $qid; ?>"
                                        data-category="<?php echo $type; ?>">
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
                                    <input id="checkBox" type="checkbox" value="<?php echo $category; ?>" data-qid="<?php echo $qid; ?>"
                                    data-category="<?php echo $type; ?>">
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
                                    <input id="checkBox" type="checkbox" value="<?php echo $category; ?>" data-qid="<?php echo $qid; ?>"
                                    data-category="<?php echo $type; ?>">
                                    <p><?php echo $category; ?> <em></em></p>
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
        <div class="filter-cards">
            <div class="option-wrap">
                <p>Option Title</p>
                <img class="remove" src="<?php echo BASE_IMAGE_URL;?>x-dark.svg" />
            </div>
        </div>
        <div id="search-result-controls">
            <!-- <span class="show-filter" class="show-filter"><img src="<?php echo BASE_URL;?>assets/images/arrow-right.svg" alt="show filter menu button"> Show Filter Menu</span> -->
            <span class="view-modes">
                <span class="grid-view view-toggle">
                    <img class="grid-icon show" src="../assets/images/List.svg" alt="grid view button">
                    <p class="tooltip">Card View</p>
                </span>
                <span class="table-view view-toggle">
                    <img class="table-icon" src="../assets/images/Table.svg" alt="table view button">
                    <p class="tooltip">Table View</p>
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
                      <li><span>A - Z</span></li>
                      <li><span>Z - A</span></li>
                    </ul>
                </span>
                <span class="align-center results-per-page"><span>9</span> Per Page <img src="<?php echo BASE_URL;?>assets/images/chevron.svg" alt="results per page button">
                    <ul id="sortmenu" class="results-per-page">
                        <li><span>20</span> Per Page</li>
                        <li><span>50</span> Per Page</li>
                        <li><span>100</span> Per Page</li>
                        <li><span>250</span> Per Page</li>
                        <li><span>500</span> Per Page</li>
                    </ul>
                </span>
            </span>
        </div>

        <div class="search-record-connections">
            <div class="connectionwrap">
                <div class="categories"></div>

                <div class="connection-cards">
                    <ul class="connect-row">
                        <span class="align-left">
                            <a class="modal">Configure Table Columns</a>
                        </span>
                        <span class="align-right"><span id="view_visual">View Project Visualization</span></span>
                    </ul>
                    <a class="search-all"></a>
                </div>
            </div>
        </div>

        <div class="show-menu">
            <img src="<?php echo BASE_IMAGE_URL;?>filter.svg" alt="drop arrow"><p id="showfilter">Show Filter Menu</p>
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
            <div class="first-prev">
	            <div class="pagi-first" value="1"><p>First</p></div>
	            <div class="pagi-left"><img src="<?php echo BASE_IMAGE_URL; ?>chevron.svg" alt="Arrow Left"/></div>
            </div>
            <div class="page-numbers">
                <span class="dotsLeft">...</span>
                <span class="num one"></span>
                <span class="num two"></span>
                <span class="num three"></span>
                <span class="num four"></span>
                <span class="num five"></span>
                <span class="dotsRight">...</span>
            </div>
            <div class="last-next">
            	<div class="pagi-right"><img src="<?php echo BASE_IMAGE_URL; ?>chevron.svg" alt="Arrow Right"/></div>
				<div class="pagi-last" value="1"><p>Last</p></div>
            </div>
        </div>
    </div>
</main>

<div class="modal-view">
    <div class="config-table-modal">
        <div class="config-table-modal-content-wrap">
            <div class="close"><img src="<?php echo BASE_URL;?>assets/images/x.svg" alt="close modal button"></div>
            <h4>Configure Table Columns</h4>
            <div>
                <div class="left-col">
                    <p>Available Columns</p>
                    <div>
                        <ul id="available-cols"></ul>
                    </div>
                </div>
                <div class="arrow-wrap">
                    <img src="<?php echo BASE_URL;?>assets/images/chevron.svg" alt="add item">
                    <img src="<?php echo BASE_URL;?>assets/images/chevron.svg" alt="remove item">
                </div>
                <div class="right-col">
                    <p>Selected Columns</p>
                    <div>
                        <ul id="selected-cols"></ul>
                        <img class="down" src="<?php echo BASE_URL;?>assets/images/chevron.svg" alt="move down">
                        <img class="up" src="<?php echo BASE_URL;?>assets/images/chevron.svg" alt="move up">
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
