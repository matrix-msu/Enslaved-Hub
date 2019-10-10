<!-- Author: Drew Schineller-->
<!-- Explore Full Record page-->
<!-- Heading image and title container-->
<div class="container header record-header people-page">
    <div class="container middlewrap">
<!--        <h4 class="last-page-header"><a id="last-page" href="--><?php //echo BASE_URL;?><!--explorePeople"><span id="previous-title">People // </span></a><span id="current-title">Firstname Lastname</span></h4>-->
<!--        <h1>--><?php //echo $person_array['Name'];?><!--</h1>-->
<!--        <h2 class="date-range"><span>1840</span>-<span>1864</span></h2>-->
    </div>
</div>
<div class="jump-buttons person-buttons">
    <div class="jumpwrap">
        <?php
            if(RECORD_FORM == 'person'){
                echo '<button class="jump-button" id="timeline">Jump to Person Timeline</button>';
            }
        ?>
        <!-- <button class="jump-button" id="details">Jump to <?php echo ucfirst(RECORD_FORM); ?> Details</button> -->
    </div>
</div>
<!-- tabs -->
<div class="project-tab">
    <!-- <h2>VIEW RECORD DETAILS FROM</h2>
    <ul>
        <li class="tabbed">All</li>
        <li>Project Name</li>
        <li>Other Project Name</li>
        <hr>
    </ul> -->
</div>
<!-- info container-->
<div class="container info person-record-info">
    <div class="container infowrap">
    </div>
</div>
<!-- detail section -->
<div class="detail-section">
    <div class="detailwrap">
    </div>
</div>

<div class="advanced-section">
    <div class="advanced-details">
        <h2>Representations:</h2>
        <!-- <p>To see a more granular provenance of this record, you can download the following files:</p> -->
        <div class="advancedwrap">
            <a href="<?php echo BASE_WIKI_URL . "entity/" . QID . ".rdf";?>">Download RDF</a>
            <a href="<?php echo BASE_WIKI_URL . "entity/" . QID . ".ttl";?>">Download Turtle</a>
            <a href="<?php echo BASE_WIKI_URL . "entity/" . QID . ".json";?>">Download JSON</a>
        </div>
    </div>
</div>

<!-- Timeline -->
<div class="timeline-container">
</div>

<!-- Related Records / Connections -->
<div class="record-connections">
    <div class="connectionwrap">
        <h2>Related Records</h2>
        <div class="categories">
            <ul>
            <?php
                // decide which connnections should be displayed based on the type of record
                if(RECORD_FORM == 'source'){
            ?>
                <li class="unselected selected" id="people"><div class="person-image"></div>People</li>
                <li class="unselected" id="event"><div class="event-image"></div>Events</li>
                <li class="unselected" id="place"><div class="place-image"></div>Places</li>
            <?php
                } else if (RECORD_FORM == 'event') {
            ?>
                <li class="unselected selected" id="people"><div class="person-image"></div>People</li>
                <li class="unselected" id="place"><div class="place-image"></div>Places</li>
                <li class="unselected" id="project"><div class="project-image"></div>Projects</li>
                <li class="unselected" id="source"><div class="source-image"></div>Sources</li>
            <?php
                } else if (RECORD_FORM == 'person') {
            ?>
                <li class="unselected selected" id="people"><div class="person-image"></div>People</li>
                <li class="unselected" id="event"><div class="event-image"></div>Events</li>
                <li class="unselected" id="place"><div class="place-image"></div>Places</li>
                <li class="unselected" id="project"><div class="project-image"></div>Projects</li>
                <li class="unselected" id="source"><div class="source-image"></div>Sources</li>
                <li class="unselected" id="closeMatch"><div class="person-image"></div>Close Matches</li>
            <?php
                } else {
            ?>
                <li class="unselected selected" id="people"><div class="person-image"></div>People</li>
                <li class="unselected" id="event"><div class="event-image"></div>Events</li>
                <li class="unselected" id="place"><div class="place-image"></div>Places</li>
                <li class="unselected" id="project"><div class="project-image"></div>Projects</li>
                <li class="unselected" id="source"><div class="source-image"></div>Sources</li>
            <?php
                }
            ?>
                <hr>
            </ul>
        </div>
        <div class="connection-cards">
            <ul class="connect-row">
                <!-- <li>
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Person-light.svg" alt="person icon">
                        <h3>Firstname Lastname</h3>
                    </div>
                </li> -->
            </ul>
            <a class="search-all"></a>
            <!-- <div class="load-more"><h4>Load More</h4></div> -->
        </div>
    </div>
</div>

<!-- Featured Stories-->
<?php
if(RECORD_FORM !== 'place' && RECORD_FORM !== 'source'){ ?>
<div class="container card-column related-card">
    <div class="container cardheader-wrap">
        <h2 class="column-header">Featured in these Stories</h2>
    </div>
    <div class="container card-wrap">
        <ul class="card-row">
            <li>
                <a href="<?php echo BASE_URL?>fullStory">
                    <h2 class="card-title">Title of Featured Story Goes Here Like This</h2>
                </a> 
            </li>
            <li>
                <a href="<?php echo BASE_URL?>fullStory">
                    <h2 class="card-title">Title of Featured Story Goes Here Like This</h2>
                </a>
            </li>
        </ul>
    </div>
</div>
<?php } ?>

<!-- Extra Info -->
<div class="extra-info">
    <div class="share-links">
        <h2>Share this Record</h2>
        <img src="<?php echo BASE_URL;?>/assets/images/Facebook.svg" alt="facebook"/>
        <img src="<?php echo BASE_URL;?>/assets/images/Twitter.svg" alt="twitter"/>
        <!-- <img src="<?php echo BASE_URL;?>/assets/images/GooglePlusButtonSmall.svg" alt="google plus"/>
        <img src="<?php echo BASE_URL;?>/assets/images/PinterestButtonSmall.svg" alt="pintrest"/> -->
    </div>
    <div class="copyright">
        <h2>Copyright</h2>
        <img class="cc-by-nc" src="<?php echo BASE_URL;?>/assets/images/CC-BY-NC.svg" alt="copyrights"/>
    </div>
</div>

<script>
    var QID = "<?php echo QID;?>";
    var recordform = "<?php echo RECORD_FORM ?>";
</script>

<script src="<?php echo BASE_URL;?>assets/javascripts/timeline.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/exploreRecord.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/fullRecord.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/connections.js"></script>
