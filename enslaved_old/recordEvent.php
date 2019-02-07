<!-- Author: Drew Schineller-->
<?php include 'header.php';?>
<!-- Event Full Record page-->
<!-- Heading image and title container-->
<div class="container header event-page">
    <div class="container middlewrap">
        <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>exploreEvents"><span id="previous-title">Events // </span></a><span id="current-title">Event Name</span></h4>
        <h1>Event Name</h1>
    </div>
</div>
<div class="jump-buttons">
    <div class="jumpwrap">
        <!-- <button class="jump-button" id="timeline">Jump to Person Timeline</button> -->
        <button class="jump-button" id="details">Jump to Event Details</button>
    </div>
</div>
<!-- info container-->
<div class="container info">
    <div class="container infowrap">
        <p>Brief info on Section. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur a tempor incididunt ut labore et dolore magna Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur.</p>
    </div>
</div>
<!-- Story Connections -->
<div class="story-connections record-connections source-event-connections">
    <div class="connectionwrap">
        <h2>Connections</h2>
        <div class="categories">
            <ul>
                <li class="unselected selected" id="people"><div class="person-image"></div>10 People</li>
                <li class="unselected" id="event"><div class="event-image"></div>3 Events</li>
                <li class="unselected" id="place"><div class="place-image"></div>3 Places</li>
                <li class="unselected" id="project"><div class="project-image"></div>2 Projects</li>
                <li class="unselected" id="source"><div class="source-image"></div>15 Sources</li>
            </ul>
        </div>
        <div class="connection-cards">
            <ul class="connect-row">
                <li>
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Person-light.svg">
                        <h3>Firstname Lastname</h3>
                    </div>
                </li>
            </ul>
            <div class="load-more"><h4>Load More</h4></div>
        </div>
    </div>
</div>
<!-- detail section -->
<div class="detail-section">
    <div class="detailwrap">
        <div class="right-col">
            <a href="<?php echo BASE_URL;?>exploreEvent">
                <div class="detail">
                    <div class="detail-top">
                        <h3>Crew</h3>
                        <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/>
                    </div>
                    <p class="detail-bottom">30</p>
                </div>
            </a>
            <a href="<?php echo BASE_URL;?>exploreEvent">
                <div class="detail">
                    <div class="detail-top">
                        <h3>Africans</h3>
                        <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/>
                    </div>
                    <p class="detail-bottom">534</p>
                </div>
            </a>
            <a href="<?php echo BASE_URL;?>exploreEvent">
                <div class="detail">
                    <div class="detail-top">
                        <h3>Event Type</h3>
                        <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/>
                    </div>
                    <p class="detail-bottom">Voyage</p>
                </div>
            </a>
            <a href="<?php echo BASE_URL;?>exploreEvent">
                <div class="detail">
                    <div class="detail-top">
                        <h3>Rig</h3>
                        <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/>
                    </div>
                    <p class="detail-bottom">Brig</p>
                </div>
            </a>
            <a href="<?php echo BASE_URL;?>exploreEvent">
                <div class="detail">
                    <div class="detail-top">
                        <h3>Captain</h3>
                        <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/>
                    </div>
                    <p class="detail-bottom">Sarria, Francisco Antonio</p>
                </div>
            </a>
            <a href="<?php echo BASE_URL;?>exploreEvent">
                <div class="detail">
                    <div class="detail-top">
                        <h3>Flag</h3>
                        <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/>
                    </div>
                    <p class="detail-bottom">Spain, Spain / Uruguay</p>
                </div>
            </a>
            <a href="<?php echo BASE_URL;?>exploreEvent">
                <div class="detail">
                    <div class="detail-top">
                        <h3>Africans Died</h3>
                        <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/>
                    </div>
                    <p class="detail-bottom">46</p>
                </div>
            </a>
            <a href="<?php echo BASE_URL;?>exploreEvent">
                <div class="detail">
                    <div class="detail-top">
                        <h3>Ship Name</h3>
                        <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/>
                    </div>
                    <p class="detail-bottom">Negrito</p>
                </div>
            </a>
        </div>
        <div class="left-col">
            <div class="card">
                <a href="<?php echo BASE_URL?>fullStory/">
                    <div class="container cards">
                        <p class="card-title">The record of this event appears in the *Name of Project* Project</p>
                        <h4 class="card-view-story">View Original Record <div class="view-arrow"></h4>
                    </div>
                </a>
            </div>
            <div class="copyright">
                <h2>Copyright Info</h2>
                <p>Info on copyright provided</p>
                <img class="cc-by-nc" src="<?php echo BASE_URL;?>/assets/images/CC-BY-NC.svg"/>
            </div>
            <div class="share-links">
                <h2>Share this Record</h2>
                <img src="<?php echo BASE_URL;?>/assets/images/FacebookButtonSmall.svg"/>
                <img src="<?php echo BASE_URL;?>/assets/images/TwitterButtonSmall.svg"/>
                <img src="<?php echo BASE_URL;?>/assets/images/GooglePlusButtonSmall.svg"/>
                <img src="<?php echo BASE_URL;?>/assets/images/PinterestButtonSmall.svg"/>
            </div>
        </div>
    </div>
</div>
<!-- Featured Stories-->
<div class="container column featured-card">
        <div class="container cardheader-wrap">
            <h2 class="column-header">Featured in these Stories</h2>
        </div>
        <div class="container cardwrap">
            <ul class="row">
                <li>
                    <a href="<?php echo BASE_URL?>fullStory/">
                        <div class="container cards">
                            <p class="card-title">Title of Featured Story Goes Here Like This</p>
                            <h4 class="card-view-story">View Story <div class="view-arrow"></div></h4>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL?>fullStory/">
                        <div class="container cards">
                            <p class="card-title">Title of Featured Story Goes Here Like This</p>
                            <h4 class="card-view-story">View Story <div class="view-arrow"></div></h4>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>

<script src="<?php echo BASE_URL;?>assets/javascripts/connections.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/fullRecord.js"></script>

<?php include 'footer.php';?>