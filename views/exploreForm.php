<!-- Page author: Drew Schineller-->
<!-- Heading image and title container-->
<?php
//var_dump(EXPLORE_FORM);
//var_dump($GLOBALS["FILTER_ARRAY"]);
$upper = ucfirst(EXPLORE_FORM);

// Get Title and Description from cache file
$cache_Data = Json_GetData_ByTitle($upper);
?>

<div class="container header explore-header people-page">
    <div class="container middlewrap">
        <h1><?php echo $cache_Data['title'] ?></h1>
    </div>
</div>
<!-- info container-->
<div class="container info">
    <div class="container infowrap">
        <p> <?php echo $cache_Data['descr'] ?></p>
        <a href="" class="view-more">View All <?php echo $upper;?></a>
    </div>
</div>
<!-- explore by -->
<div class="container explore-by">
    <h1>Explore By</h1>
    <ul class="cards">
        <?php foreach ($GLOBALS["FILTER_ARRAY"][EXPLORE_FORM] as $type) { ?>
                <li>
                    <a href="<?php echo BASE_URL?>explore/<?php echo EXPLORE_FORM.'/'.strtolower(str_replace(" ", "_", $type))?>">
                        <p class='type-title'><?php echo $type?></p>
                        <div id="arrow"></div>
                    </a>
                </li>
        <?php } ?>
    </ul>
</div>
<!-- Featured People -->
<div class="card-slider explore-featured">
    <h2>Featured <?=$upper?></h2>
    <div class="cardwrap">
        <div class="cardwrap2">
            <ul class="cards-featured">
            </ul>
        </div>
    </div>
    <!-- <div class="controls">
        <div class="arrows">
            <div class="prev"><img src="<?php echo BASE_IMAGE_URL?>Arrow3.svg" alt="arrow"></div>
            <div class="next"><img src="<?php echo BASE_IMAGE_URL?>Arrow3.svg" alt="arrow"></div>
        </div>
        <div class="dots">
        </div>
    </div>-->
</div>
<!-- Search Bar -->
<!-- <div class="explore-search">
    <h2>Find <?=$upper?></h2>
    <p>Search across 2,213 people records</p>
    <form class="search-form" action="<?php echo BASE_URL;?>search/all" method="get">
        <label for="searchbar" class="sr-only">searchbar</label>
        <input id="searchbar" class="search-field main-search" type="text" name="searchbar" placeholder="Start Searching for <?=$upper?> By Name, Origin, Role, Etc."/>
        <button class="search-icon-2" type="submit"><img src="<?php echo BASE_URL;?>/assets/images/Search.svg" alt="search-icon"></button>
    </form>
</div> -->
<!-- Visualize People -->
<!-- <div class="explore-visualize visualize-hide">
    <h2 class="column-header">Visualize <?=$upper?></h2>
    <div class="cardwrap">
        <ul class="row">
            <li id="byspace">
                <a href="<?php echo BASE_URL?>fullStory">
                    <div class="cards">
                        <div class="test">
                            <img src="<?php echo BASE_URL?>assets/images/BySpace.svg" alt="space"/>
                        </div>
                        <p>By Space</p>
                    </div>
                </a>
            </li>
            <li id="bytime">
                <a href="<?php echo BASE_URL?>fullStory">
                    <div class="cards">
                        <div class="test">
                            <img src="<?php echo BASE_URL?>assets/images/ByTime.svg" alt="time"/>
                        </div>
                        <p>By Time</p>
                    </div>
                </a>
            </li>
            <li id="bydata">
                <a href="<?php echo BASE_URL?>fullStory">
                    <div class="cards">
                        <div class="test">
                            <img src="<?php echo BASE_URL?>assets/images/ByData.svg" alt="data"/>
                        </div>
                        <p>By Data</p>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div> -->

<script src="<?php echo BASE_URL;?>assets/javascripts/cardSlider.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/explore.js"></script>
