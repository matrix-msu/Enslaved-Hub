<!-- Page author: Drew Schineller-->
<!-- Heading image and title container-->
<div class="container header place-page">
    <div class="container middlewrap">
        <h1>Places</h1>
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
        <li>
            <a href="<?php echo BASE_URL?>peopleSub2">Place Type<div id="arrow"></div></a>
        </li>
        <li>
            <a href="<?php echo BASE_URL?>peopleSub2">City<div id="arrow"></div></a>
        </li>
        <li>
            <a href="<?php echo BASE_URL?>peopleSub2">Province<div id="arrow"></div></a>
        </li>
    </ul>
</div>
<!-- Large Map, currently hidden -->
<div class="mapwrap" style="display: none">
    <div id="map-large"></div>
</div>
<!-- Featured Places -->
<div class="explore-featured">
    <h2>Featured Places</h2>
    <div class="connection-cards">
        <ul class="connect-row places">
            <li>
                <a href="<?php echo BASE_URL?>recordPlace">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Place-light.svg" alt="loction icon">
                        <h3>Place Name</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordPlace">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Place-light.svg" alt="loction icon">
                        <h3>Place Name</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordPlace">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Place-light.svg" alt="loction icon">
                        <h3>Place Name</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordPlace">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Place-light.svg" alt="loction icon">
                        <h3>Place Name</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordPlace">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Place-light.svg" alt="loction icon">
                        <h3>Place Name</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordPlace">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Place-light.svg" alt="loction icon">
                        <h3>Place Name</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordPlace">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Place-light.svg" alt="loction icon">
                        <h3>Place Name</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordPlace">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Place-light.svg" alt="loction icon">
                        <h3>Place Name</h3>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- Search Bar -->
<div class="explore-search">
    <h3>Find Places</h3>
    <form class="search-form">
        <label for="searchbar" class="sr-only">searchbar</label>
        <input id="searchbar" class="search-field main-search" type="text" name="searchbar" placeholder="Start Searching for Places By Name, City, Province, Etc."/>
        <a href="<?php echo BASE_URL;?>search"><div class="search-icon-2"><img src="<?php echo BASE_URL;?>/assets/images/Search.svg" alt="search icon"></div></a>
    </form>
</div>
<!-- Visualize People -->
<div class="explore-visualize visualize-hide">
    <h2 class="column-header">Visualize Places</h2>
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

<script src="<?php echo BASE_URL;?>assets/javascripts/explore.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/map.js"></script>
