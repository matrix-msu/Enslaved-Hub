<!-- Heading image and title container-->
<div class="container header explore-results">
	<div class="container middlewrap">
        <?php
        $typeTitle = "";
        $typeLower = "";
        $currentTitle = 'Search';

        if (count($_GET) > 0){
            $typeLower = array_keys($_GET)[0];
            $typeTitle = ucwords(str_replace('_', ' ', $typeLower));

            $currentQ = $_GET[$typeLower];
            // $currentQ = str_replace('_', ' ', $currentQitle);
            
            if (array_key_exists($typeTitle, $GLOBALS['FILTER_TO_FILE_MAP'])){
                $typeCategories = $GLOBALS['FILTER_TO_FILE_MAP'][$typeTitle];
                $currentTitle = array_search($currentQ, $typeCategories);

                if(!$currentTitle){
                    //QID not found, shouldn't get here
                    $currentTitle = "QID ERROR";
                }
            }
            else{
                //Must have been a search for a name, daterange
                $currentTitle = $currentQ;

                $query = array('query' => "");

                // get the label for the q value from a quick blazegraph search
                $query['query'] = <<<QUERY
SELECT ?item ?label

 WHERE
{
BIND(wd:$currentQ AS ?item).
    ?item rdfs:label ?label.

  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE]". }
}GROUP BY ?item ?label
QUERY;

                //Execute query
                $ch = curl_init(BLAZEGRAPH_URL);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                    'Accept: application/sparql-results+json'
                ));
                $result = curl_exec($ch);
                curl_close($ch);
                //Get result
                $result = json_decode($result, true)['results']['bindings'];

                if (!empty($result)){
                    if (isset($result[0]['label']) && isset($result[0]['label']['value'])){
                        $currentTitle = $result[0]['label']['value'];
                    }
                }
            }
        }
 
        $upperForm = ucfirst(EXPLORE_FORM);
        $showPath = false;
        $fromBrowse = false;

        //Conditions to put the previous page header in (Not being used right now)
        if(EXPLORE_FORM != null && EXPLORE_FORM != 'all' && EXPLORE_FORM != 'results' && EXPLORE_FORM != 'category'){
             $showPath = true;
             $fromBrowse = true;
        }
       ?>
        <h4 class="last-page-header" style="<?php  echo (!$showPath) ? 'display:none' : '' ?> ">
            <a id="last-page" class="prev1" href="<?php echo BASE_URL. 'explore/' .EXPLORE_FORM ?>">
                <span id="previous-title"><?php echo $upperForm ?> </span>
            </a>
            <a id="last-page" class="prev2" href="<?php echo BASE_URL. 'explore/' .EXPLORE_FORM. '/' .$typeLower ?>">
                <span id="previous-title"><?php echo ($typeTitle != "") ? "//" . $typeTitle : "" ?></span>
            </a>
            <span id="current-title"><?php echo ($currentTitle != "") ? "//" . $currentTitle : "" ?></span>
        </h4>
        <div class="search-title">
            <h1><?php echo $currentTitle;?></h1>
        </div>
        <?php if(!$fromBrowse) { ?>
            <div class="heading-search">
                <form class="search-form">
                    <label for="searchbar" class="sr-only">searchbar</label>
                    <input id="searchbar" class="search-field main-search" type="text" name="searchbar"/>
                    <button class="search-icon-2" type="submit"><img src="<?php echo BASE_URL;?>/assets/images/Search.svg" alt="search-icon"></button>
                    <!-- <img class="search-close" src="<?php echo BASE_URL;?>/assets/images/Close.svg"/> -->
                </form>
            </div>
        <?php } ?>
  </div>
</div>

<main class="search-results">
    <div class="filter-menu">
        <ul>
            <?php if(!$fromBrowse) { ?>
            <h2>Show Results For</h2>
            <ul class="catmenu" id="submenu">
                <li>
                    <label class="category">
                        <input id="checkBox" type="checkbox" value="people">
                        <img src="<?php echo BASE_URL;?>assets/images/Person-dark.svg" alt="person icon">
                        <p>People</p>
                        <span></span>
                    </label>
                </li>
                <li>
                    <label class="category">
                        <input id="checkBox" type="checkbox" value="places">
                        <img src="<?php echo BASE_URL;?>assets/images/Place-dark.svg" alt="location icon">
                        <p>Places</p>
                        <span></span>
                    </label>
                </li>
                <li>
                    <label class="category">
                        <input id="checkBox" type="checkbox" value="events">
                        <img src="<?php echo BASE_URL;?>assets/images/Event-dark.svg" alt="event icon">
                        <p>Events</p>
                        <span></span>
                    </label>
                </li>
                <li>
                    <label class="category">
                        <input id="checkBox" type="checkbox" value="sources">
                        <img src="<?php echo BASE_URL;?>assets/images/Source-dark.svg" alt="source icon">
                        <p>Sources</p>
                        <span></span>
                    </label>
                </li>
                <li>
                    <label class="category">
                        <input id="checkBox" type="checkbox" value="projects">
                        <img src="<?php echo BASE_URL;?>assets/images/Project-dark.svg" alt="project icon">
                        <p>Projects</p>
                        <span></span>
                    </label>
                </li>
            </ul>
            <hr>
            <?php } ?>
            <!-- People Filtering -->
            
            <li class="cat-cat">People Filtering<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="drop arrow"></span>
            </li>
            <ul id="mainmenu">
            
                <?php 
                $fullArray = $GLOBALS["FILTER_ARRAY"]['people'];
                $extraCats = ['Status', 'Occupation'];
                foreach($extraCats as $extra){
                    $fullArray[] = $extra;
                }
                foreach ($fullArray as $type) { 
                    $catLower = strtolower(str_replace(" ", "_", $type)); ?>
                    <li class="filter-cat" name="<?php echo $catLower; ?>"><?php echo $type; ?><span class="align-right"><img src="<?php echo BASE_IMAGE_URL;?>Arrow-dark.svg" alt="drop arrow"></span>
                    </li>
                    <ul id="submenu">
                        <?php
                        $typeCats = array();
                        if (array_key_exists($type, $GLOBALS['FILTER_TO_FILE_MAP'])){
                            $typeCats = $GLOBALS['FILTER_TO_FILE_MAP'][$type];
                        }
                        foreach ($typeCats as $category => $qid) { ?>
                            <li>
                                <label class="<?php echo $catLower; ?>">
                                    <input id="checkBox" type="checkbox" value="<?php echo $qid; ?>">
                                    <p><?php echo $category; ?> <em>(234)</em></p>
                                    <span></span>
                                </label>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } ?>

            </ul>
            <!-- Event Filtering -->
            <hr>
            <li class="cat-cat">Event Filtering<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="drop arrow"></span>
            </li>
            <ul id="mainmenu">

                <?php foreach ($GLOBALS["FILTER_ARRAY"]['events'] as $type) { 
                    $catLower = strtolower(str_replace(" ", "_", $type)); ?>
                    <li class="filter-cat" name="<?php echo $catLower; ?>"><?php echo $type; ?><span class="align-right"><img src="<?php echo BASE_IMAGE_URL;?>Arrow-dark.svg" alt="drop arrow"></span>
                    </li>
                    <ul id="submenu">
                    <?php
                    $typeCats = array();
                    if (array_key_exists($type, $GLOBALS['FILTER_TO_FILE_MAP'])){
                        $typeCats = $GLOBALS['FILTER_TO_FILE_MAP'][$type];
                    }
                    foreach ($typeCats as $category => $qid) { ?>
                        <li>
                            <label class="<?php echo $catLower; ?>">
                                <input id="checkBox" type="checkbox" value="<?php echo $qid; ?>">
                                <p><?php echo $category; ?> <em>(234)</em></p>
                                <span></span>
                            </label>
                        </li>
                    <?php } ?>
                </ul>
                <?php } ?>

            </ul>
            <!-- Place Filtering -->
            <hr>
            <li class="cat-cat">Place Filtering<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="drop arrow"></span>
            </li>
            <ul id="mainmenu">

                <?php foreach ($GLOBALS["FILTER_ARRAY"]['places'] as $type) { 
                    $catLower = strtolower(str_replace(" ", "_", $type)); ?>
                    <li class="filter-cat" name="<?php echo $catLower; ?>"><?php echo $type; ?><span class="align-right"><img src="<?php echo BASE_IMAGE_URL;?>Arrow-dark.svg" alt="drop arrow"></span>
                    </li>
                    <ul id="submenu">
                    <?php
                    $typeCats = array();
                    if (array_key_exists($type, $GLOBALS['FILTER_TO_FILE_MAP'])){
                        $typeCats = $GLOBALS['FILTER_TO_FILE_MAP'][$type];
                    }
                    foreach ($typeCats as $category => $qid) { ?>
                        <li>
                            <label class="<?php echo $catLower; ?>">
                                <input id="checkBox" type="checkbox" value="<?php echo $qid; ?>">
                                <p><?php echo $category; ?> <em>(234)</em></p>
                                <span></span>
                            </label>
                        </li>
                    <?php } ?>
                </ul>
                <?php } ?>

            </ul>
            <!-- Source Filtering -->
            <hr>
            <li class="cat-cat">Source Filtering<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="drop arrow"></span>
            </li>
            <ul id="mainmenu">

                <?php foreach ($GLOBALS["FILTER_ARRAY"]['sources'] as $type) { 
                    $catLower = strtolower(str_replace(" ", "_", $type)); ?>
                    <li class="filter-cat" name="<?php echo $catLower; ?>"><?php echo $type; ?><span class="align-right"><img src="<?php echo BASE_IMAGE_URL;?>Arrow-dark.svg" alt="drop arrow"></span>
                    </li>
                    <ul id="submenu">
                    <?php
                    $typeCats = array();
                    if (array_key_exists($type, $GLOBALS['FILTER_TO_FILE_MAP'])){
                        $typeCats = $GLOBALS['FILTER_TO_FILE_MAP'][$type];
                    }
                    foreach ($typeCats as $category => $qid) { ?>
                        <li>
                            <label class="<?php echo $catLower; ?>">
                                <input id="checkBox" type="checkbox" value="<?php echo $qid; ?>">
                                <p><?php echo $category; ?> <em>(234)</em></p>
                                <span></span>
                            </label>
                        </li>
                    <?php } ?>
                </ul>
                <?php } ?>

            </ul>
            <!-- Project Filtering -->
            <hr>
            <li class="cat-cat">Project Filtering<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="drop arrow"></span>
            </li>
            <ul id="mainmenu">

                <li class="filter-cat">Project<span class="align-right"><img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="drop arrow"></span>
                </li>
                <ul id="submenu">
                    <li>
                        <label class="project">
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label class="project">
                            <input id="checkBox" type="checkbox">
                            <p>Undefined <em>(234)</em></p>
                            <span></span>
                        </label>
                    </li>
                    <li>
                        <label class="project">
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
        <h2 class="showing-results"></h2>
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
                <span class="align-center results-per-page"><span>9</span> Per Page <img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="results per page button">
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