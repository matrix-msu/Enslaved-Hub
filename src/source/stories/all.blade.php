@extends('_layouts.main')

@section('body')

<!-- Heading image and title container-->

<div class="container header search-page">
    <div class="image-container search-page">
        <div class="container middlewrap search-page">
            <h4 class="last-page-header">
                <a id="last-page" class="prev1" href="<?php echo $link1 ?>">
                    <span id="previous-title"><?php echo $upperForm ?></span>
                </a>
                <a id="last-page" class="prev2" href="<?php echo $link2 ?>">
                    <span id="previous-title"><span class="sr-only">Secondary Breadcrumb Link</span><?php echo ($typeTitle != "") ? "/ " . $typeTitle : "" ?></span>
                </a>
                <span id="current-title"><?php echo ($currentTitle != "") ? "/ " . $currentTitle : "" ?></span>
            </h4>
            <div class="search-title">
                <h1>Search Stories<?php //echo $currentTitle;?></h1>
            </div>
            <?php //if(!$fromBrowse) { ?>
            <div class="heading-search">
                <!-- use all counts instead of counterofAllitems() -->
            <p>Start a search across <?php //echo counterofAllitems();?> stories from the historical slave trade</p>
            <!-- <p class="hide-desktop-only mt-xs"><a class="text-link" href="<?php echo BASE_URL;?>advancedSearch">Go to Advanced Search</a></p> -->
            <form class="search-form" action="<?= BASE_URL ?>search/all">
                <label for="searchbar" class="sr-only">searchbar</label>
                <input id="searchbar" class="search-field main-search" type="text" name="searchbar" placeholder="Find a Story By Title or Keyword"/>
                <button class="search-icon-2" type="submit"><img src="<?php echo BASE_URL;?>/assets/images/Search.svg" alt="search-icon"></button>
                <!-- <img class="search-close" src="<?php echo BASE_URL;?>/assets/images/Close.svg"/> -->
            </form>
            </div>
            <?php //} ?>
        </div>
        <div class="image-background-overlay  search-page"></div>
        <img class="header-background search-page" src="<?php echo BASE_URL;?>assets/images/enslaved-header-bg5.jpg" alt="Enslaved Background Image"></div>
        <!-- <img class="header-background search-page" src="<?php echo BASE_URL;?>assets/images/enslaved-header-bg3.jpg" alt="Enslaved Background Image"></div> -->
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

            <li class="cat-cat"></li>
            <ul id="mainmenu">

                <!-- < ?php
                $fullArray = $GLOBALS["FILTER_ARRAY"]['people'];
                $extraCats = ['Status'];
                foreach($extraCats as $extra){
                    $fullArray[] = $extra;
                }
                foreach ($fullArray as $type) {
                    $catLower = strtolower(str_replace(" ", "_", $type)); ?>
                <li class="filter-cat" name="< ?php echo $catLower; ?>">< ?php echo $type; ?><span class="align-right"><img src="<?php echo BASE_IMAGE_URL;?>chevron.svg" alt="drop arrow"></span>

                    <ul id="submenu">
                        < ?php
                        $typeCats = array();
                        if (array_key_exists($type, $GLOBALS['FILTER_TO_FILE_MAP'])){
                            $typeCats = $GLOBALS['FILTER_TO_FILE_MAP'][$type];
                        }
                        foreach ($typeCats as $category => $qid) { ?>
                            <li class="hide-category">
                                <label class="< ?php echo $catLower; ?>">
                                    <input id="checkBox" type="checkbox" value="< ?php echo $category; ?>" data-qid="<?php echo $qid; ?>" data-category="<?php echo $type; ?>">
                                    <p>< ?php echo $category; ?> <em></em></p>
                                    <span></span>
                                </label>
                            </li>
                        < ?php } ?>
                    </ul>
                </li>
                < ?php } ?> -->

            </ul>
        </ul>
    </div>


    <div id="searchResults" style="width:100%">
        <h2 class="showing-results"></h2>
        <div class="filter-cards" style="padding-left:45px;">
        </div>
        <div id="search-result-controls" style="padding-right:45px;">
            <!-- <span class="show-filter" class="show-filter"><img src="<?php echo BASE_URL;?>assets/images/arrow-right.svg" alt="show filter menu button"> Show Filter Menu</span> -->
            <span class="view-modes">
            </span>
            <span class="sorting-dropdowns">
                <span class="align-center sort-by">Sort By <img src="<?php echo BASE_URL;?>assets/images/chevron.svg" alt="results per page button">
                    <ul id="sortmenu" class="sort-by" style="z-index:9">
                      <li class="sort-option" data-sort="latest"><span>Recently Updated</span></li>
                      <li class="sort-option" data-sort="A - Z"><span>Alphabetically(A-Z)</span></li>
                      <li class="sort-option" data-sort="Z - A"><span>Alphabetically(Z-A)</span></li>
                      <li class="sort-option" data-sort="Newestdate"><span>Date (Newest First)</span></li>
                      <li class="sort-option" data-sort="Oldestdate"><span>Date (Oldest First)</span></li>
                    </ul>
                </span>
                <span class="align-center results-per-page"><span class="per-page-text">10</span> Per Page <img src="<?php echo BASE_URL;?>assets/images/chevron.svg" alt="results per page button">
                    <ul id="sortmenu" class="results-per-page" style="z-index:9">
                        <li class="count-option"><span>10</span> Per Page</li>
                        <li class="count-option"><span>20</span> Per Page</li>
                        <li class="count-option"><span>50</span> Per Page</li>
                    </ul>
                </span>
            </span>
        </div>

        <div class="show-menu">
            <img src="<?php echo BASE_IMAGE_URL;?>filter.svg" alt="drop arrow"><p id="showfilter">Show Filter Menu</p>
        </div>

        <div id="search-result-wrap">
            <div class="card-column">
                <div class="card-wrap">
                    <ul class="card-row">
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

<!-- <script src="<?php echo BASE_URL;?>assets/javascripts/pagination.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/searchResults.js"></script>
<script src="<?php echo BASE_URL;?>modules/modal/modal.js"></script> -->

<script src="<?php echo BASE_URL;?>assets/javascripts/fuse.min.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/cardSlider.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/pagination.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/searchData.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/stories.js"></script>
<script>
	const storiesOptions = {
	  includeScore: true,
	  useExtendedSearch: true,
	  keys: ['Title', 'Text']
	};
	const storiesFuse = new Fuse(allStoriesRecords, storiesOptions);
</script>
<script src="<?php echo BASE_URL;?>assets/javascripts/explore.js"></script>
<script>
  var JS_EXPLORE_FORM = "stories";
</script>

@endsection
