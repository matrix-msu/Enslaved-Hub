<!-- Page author: Drew Schineller-->
<!-- Heading image and title container-->
<div class="container header event-page">
    <div class="container middlewrap">
        <h1>Events</h1>
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
            <a href="<?php echo BASE_URL?>peopleSub2">Event Type<div id="arrow"></div></a>
        </li>
        <li>
            <a href="<?php echo BASE_URL?>peopleSub2">Time<div id="arrow"></div></a>
        </li>
        <li>
            <a href="<?php echo BASE_URL?>peopleSub2">Place<div id="arrow"></div></a>
        </li>
    </ul>
</div>
<!-- Featured Events -->
<div class="explore-featured">
    <h2>Featured Events</h2>
    <div class="connection-cards">
        <ul class="connect-row events">
            <li>
                <a href="<?php echo BASE_URL?>recordEvent">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Event-light.svg">
                        <h3>Event Name</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordEvent">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Event-light.svg">
                        <h3>Event Name</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordEvent">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Event-light.svg">
                        <h3>Event Name</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordEvent">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Event-light.svg">
                        <h3>Event Name</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordEvent">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Event-light.svg">
                        <h3>Event Name</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordEvent">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Event-light.svg">
                        <h3>Event Name</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordEvent">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Event-light.svg">
                        <h3>Event Name</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordEvent">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Event-light.svg">
                        <h3>Event Name</h3>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- Search Bar -->
<div class="explore-search">
    <h3>Find Events</h3>
    <form class="search-form">
        <input class="search-field main-search" type="text" name="searchbar" placeholder="Start Searching for Events By Crew, Event Type, Rig, Captain, Etc."/>
        <a href="<?php echo BASE_URL;?>search"><div class="search-icon"></div></a>
    </form>
</div>
<!-- Visualize People -->
<div class="explore-visualize">
    <h2 class="column-header">Visualize Events</h2>
    <div class="cardwrap">
        <ul class="row">
            <li id="byspace">
                <a href="<?php echo BASE_URL?>fullstory">
                    <div class="cards">
                        <div class="test">
                            <img src="<?php echo BASE_URL?>assets/images/BySpace.svg"/>
                        </div>
                        <p>By Space</p>
                    </div>
                </a>
            </li>
            <li id="bytime">
                <a href="<?php echo BASE_URL?>fullstory">
                    <div class="cards">
                        <div class="test">
                            <img src="<?php echo BASE_URL?>assets/images/ByTime.svg"/>
                        </div>
                        <p>By Time</p>
                    </div>
                </a>
            </li>
            <li id="bydata">
                <a href="<?php echo BASE_URL?>fullstory">
                    <div class="cards">
                        <div class="test">
                            <img src="<?php echo BASE_URL?>assets/images/ByData.svg"/>
                        </div>
                        <p>By Data</p>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>

<script src="<?php echo BASE_URL;?>assets/javascripts/explore.js"></script>