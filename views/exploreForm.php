<!-- Page author: Drew Schineller-->
<!-- Heading image and title container-->
<?php
//var_dump(EXPLORE_FORM);
//var_dump($GLOBALS["FILTER_ARRAY"]);
$upper = ucfirst(EXPLORE_FORM);
?>

<div class="container header stories">
    <div class="container middlewrap">
        <h1><?=$upper?></h1>
    </div>
</div>
<!-- info container-->
<div class="container info">
    <div class="container infowrap">
        <p>Brief info on Section. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur a tempor incididunt ut labore et dolore magna Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur.</p>
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
<script src="<?php echo BASE_URL;?>assets/javascripts/explore.js"></script>
<!-- Featured People -->
<div class="explore-featured">
    <h2>Featured People</h2>
    <div class="connection-cards">
        <ul class="connect-row people">
            <li>
                <a href="<?php echo BASE_URL?>recordPerson/?item=Q503">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Person-light.svg" alt="person icon">
                        <h3>Firstname Lastname</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordPerson">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Person-light.svg" alt="person icon">
                        <h3>Firstname Lastname</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordPerson">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Person-light.svg" alt="person icon">
                        <h3>Firstname Lastname</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordPerson">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Person-light.svg" alt="person icon">
                        <h3>Firstname Lastname</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordPerson">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Person-light.svg" alt="person icon">
                        <h3>Firstname Lastname</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordPerson">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Person-light.svg" alt="person icon">
                        <h3>Firstname Lastname</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordPerson">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Person-light.svg" alt="person icon">
                        <h3>Firstname Lastname</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordPerson">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Person-light.svg" alt="person icon">
                        <h3>Firstname Lastname</h3>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- Search Bar -->
<div class="explore-search">
    <h3>Find People</h3>
    <form class="search-form" action="<?php echo BASE_URL;?>search/<?php echo EXPLORE_FORM ?>" method="get">
        <label for="searchbar" class="sr-only">searchbar</label>
        <input id="searchbar" class="search-field main-search" type="text" name="searchbar" placeholder="Start Searching for People By Name, Origin, Role, Etc."/>
        <button class="search-icon-2" type="submit"><img src="<?php echo BASE_URL;?>/assets/images/Search-dark.svg" alt="search-icon"></button>
    </form>
</div>
<!-- Visualize People -->
<div class="explore-visualize visualize-hide">
    <h2 class="column-header">Visualize People</h2>
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
</div>


