<!-- Author: Drew Schineller-->
<?php
$baseuri='https://sandro-16.matrix.msu.edu/entity/';
$qitem='Q503';
$allStatements=getpersonfullInfo($baseuri,$qitem);
$person_array=$allStatements['PersonInfo'];
?>
<!-- Source Full Record page-->
<!-- Heading image and title container-->
<div class="container header source-page">
    <div class="container middlewrap">
        <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>exploreSources/"><span id="previous-title">Sources // </span></a><span id="current-title">Source name</span></h4>
        <h1>Source Name</h1>
    </div>
</div>
<div class="jump-buttons">
    <div class="jumpwrap">
        <!-- <button class="jump-button" id="timeline">Jump to Person Timeline</button> -->
        <button class="jump-button" id="details">Jump to Source Details</button>
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
        <?php
        foreach($person_array as $tag=>$data){
        if($data!='' && $tag!='Description' && !is_array($data)){
            detailPerson($person_array[$tag],$tag);
        }else if(is_array($data)){
            foreach ($data as $key => $value) {
            detailPerson($person_array[$key],$key);
            }
        }
        }?>
        <a href="<?php echo BASE_URL;?>explorePeople/">
            <div class="detail">
                <h3>CONTRIBUTING PROJECT</h3>
                <div class="detail-bottom">
                    <div>Lastname
                        <div class="detail-menu">
                            <h1>Metadata</h1>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                            </p>
                        </div>
                    </div>
                    <h4> | </h4>
                    <div>Second
                        <div class="detail-menu">
                            <h1>Metadata</h1>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </a>
    </div>
</div>

<!-- Extra Info -->
<div class="extra-info">
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
