<!-- Page author: Drew Schineller-->
<?php include 'header.php';?>
<?php
include 'functions/kora.php';
$stories = getStories();
$featured = [];
foreach ($stories['records'][0] as $kid => $story) {
    if (isset($story['Featured']) && $story['Featured']['value'] == 'TRUE') {
        $featured[$kid] = $story;
    }
}
?>
<!-- Stories page-->
<!-- Heading image and title container-->
<div class="container main stories">
    <div class="container middlewrap">
        <h1>Stories</h1>
    </div>
</div>
<!-- info container-->
<div class="container info">
    <div class="container infowrap">
        <p>Brief info on Section. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur a tempor incididunt ut labore et dolore magna Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur.</p>
    </div>
</div>
<!-- featured stories container-->
<div class="container column storycard">
    <div class="container cardheader-wrap">
        <h2 class="column-header">Featured Stories</h2>
    </div>
    <div class="container cardwrap" id="featured">
        <ul class="row">
            <?php
            foreach ($featured as $kid => $story) {
                echo '<li><a href="'.BASE_URL.'fullstory?kid='.$kid.'">';
                echo '<div class="container cards">';
                echo '<p class="card-title">'.$story['Title']['value'].'</p>';
                echo '<h4 class="card-view-story">View Story <div class="view-arrow"></div></h4>';
                echo '</div></a></li>';
            }
            ?>
            <li>
                <a href="<?php echo BASE_URL?>fullstory">
                    <div class="container cards">
                        <p class="card-title">Title of Featured Story Goes Here Like This</p>
                        <h4 class="card-view-story">View Story <div class="view-arrow"></div></h4>
                    </div>
                </a>
            </li>
            <!-- <li>
                <a href="<?php echo BASE_URL?>fullstory">
                    <div class="container cards">
                        <p class="card-title">Title of Featured Story Goes Here Like This</p>
                        <h4 class="card-view-story">View Story <div class="view-arrow"></h4>
                    </div>
                </a>
            </li> -->
        </ul>
    </div>
</div>
<!-- all stories container-->
<div class="container column storycard">
    <div id="all-header" class="container cardheader-wrap">
        <div class="container header-search">
            <h2 class="column-header">All Stories</h2>
            <div class="container search">
                <form action="submit">
                    <input class="search-field" type="text" name="searchbar" placeholder="Find a Story By Title or Keyword"/>
                </form>
            </div>
        </div>
    </div>
    <div class="container cardwrap" id="allStories">
        <div class="container sort-stories">
            <span class="sort-stories-text">Sort Stories By <img class="sort-arrow" src="<?php echo BASE_URL?>assets/images/Arrow2.svg" alt="sort stories button"></span>
            <ul id="submenu" class="sorting-menu">
                <li>Alphabetical (A-Z)</li>
                <li>Alphabetical (Z-A)</li>
                <li>Newest to Oldest</li>
                <li>Oldest to Newest</li>
            </ul>
        </div>
        <ul class="row">
            <?php
            foreach ($stories['records'][0] as $kid => $story) {
                echo '<li><a href="'.BASE_URL.'fullstory?kid='.$kid.'">';
                echo '<div class="container cards">';
                echo '<p class="card-title">'.$story['Title']['value'].'</p>';
                echo '<h4 class="card-view-story">View Story <div class="view-arrow"></div></h4>';
                echo '</div></a></li>';
            }
            ?>
            <!-- <li>
                <a href="<?php echo BASE_URL?>fullstory">
                    <div class="container cards">
                        <p class="card-title">Title of Featured Story Goes Here Like This</p>
                        <h4 class="card-view-story">View Story <div class="view-arrow"></h4>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>fullstory">
                    <div class="container cards">
                        <p class="card-title">Title of Featured Story Goes Here Like This</p>
                        <h4 class="card-view-story">View Story <div class="view-arrow"></h4>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>fullstory">
                    <div class="container cards">
                        <p class="card-title">Title of Featured Story Goes Here Like This</p>
                        <h4 class="card-view-story">View Story <div class="view-arrow"></h4>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>fullstory">
                    <div class="container cards">
                        <p class="card-title">Title of Featured Story Goes Here Like This</p>
                        <h4 class="card-view-story">View Story <div class="view-arrow"></h4>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>fullstory">
                    <div class="container cards">
                        <p class="card-title">Title of Featured Story Goes Here Like This</p>
                        <h4 class="card-view-story">View Story <div class="view-arrow"></h4>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>fullstory">
                    <div class="container cards">
                        <p class="card-title">Title of Featured Story Goes Here Like This</p>
                        <h4 class="card-view-story">View Story <div class="view-arrow"></h4>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>fullstory">
                    <div class="container cards">
                        <p class="card-title">Title of Featured Story Goes Here Like This</p>
                        <h4 class="card-view-story">View Story <div class="view-arrow"></h4>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>fullstory">
                    <div class="container cards">
                        <p class="card-title">Title of Featured Story Goes Here Like This</p>
                        <h4 class="card-view-story">View Story <div class="view-arrow"></h4>
                    </div>
                </a>
            </li> -->
        </ul>
    </div>
    <div class="container pagiwrap">
        <div class="container sort-pages">
            <p><span>X</span> Per Page <img class="sort-arrow" src="<?php echo BASE_URL?>assets/images/Arrow2.svg" alt="sort stories button"/></p>
            <ul id="submenu" class="pagenum-menu">
                <li>8 Per Page</li>
                <li>12 Per Page</li>
                <li>16 Per Page</li>
                <li>20 Per Page</li>
            </ul>
        </div>
        <div id="pagination">
            <span id="pagiLeft" class="align-left"><div id="pagiLeftArrow"></div></span>
            <div class="page-numbers">
                <span class="num pagi-first">1</span>
                <span class="dotsLeft">...</span>
                <span class="num one"></span>
                <span class="num two"></span>
                <span class="num three"></span>
                <span class="num four"></span>
                <span class="dotsRight">...</span>
                <span class="num pagi-last">310</span>
            </div>
            <span id="pagiRight" class="align-right"><div id="pagiRightArrow"></div></span>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL;?>assets/javascripts/stories.js"></script>
