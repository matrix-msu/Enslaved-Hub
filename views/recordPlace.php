<!-- Author: Drew Schineller-->
<?php
$baseuri='https://sandro-16.matrix.msu.edu/entity/';
$qitem='Q503';
$allStatements=getpersonfullInfo($baseuri,$qitem);
$person_array=$allStatements['PersonInfo'];
$place_array=$allStatements['Places'];
//var_dump($person_array);

// Code for creating events on Timeline
// Replace with Kora 3 events
$events = [
    ['kid' => '1', 'title' => 'birth', 'description' => 'Person was born', 'year' => 1730],
    ['kid' => '2', 'title' => 'event 1', 'description' => 'Example description 1', 'year' => 1739],
    ['kid' => '3', 'title' => 'event 2', 'description' => 'Example description 2', 'year' => 1741],
    ['kid' => '4', 'title' => 'event 3', 'description' => 'Example description 3', 'year' => 1745],
    ['kid' => '5', 'title' => 'event 4', 'description' => 'Example description 4', 'year' => 1756],
    ['kid' => '6', 'title' => 'event 5', 'description' => 'Example description 5', 'year' => 1756.5],
    ['kid' => '7', 'title' => 'event 6', 'description' => 'Example description 6', 'year' => 1760],
    ['kid' => '8', 'title' => 'event 7', 'description' => 'Example description 7', 'year' => 1763],
    ['kid' => '9', 'title' => 'event 8', 'description' => 'Example description 8', 'year' => 1774],
    ['kid' => '10', 'title' => 'event 9', 'description' => 'Example description 9', 'year' => 1789],
    ['kid' => '11', 'title' => 'event 10', 'description' => 'Example description 10', 'year' => 1789.5],
    ['kid' => '12', 'title' => 'event 11', 'description' => 'Example description 11', 'year' => 1794],
    ['kid' => '13', 'title' => 'event 12', 'description' => 'Example description 12', 'year' => 1796],
    ['kid' => '14', 'title' => 'event 13', 'description' => 'Example description 13', 'year' => 1799],
    ['kid' => '15', 'title' => 'event 14', 'description' => 'Example description 14', 'year' => 1800],
    ['kid' => '16', 'title' => 'event 15', 'description' => 'Example description 15', 'year' => 1801],
    ['kid' => '17', 'title' => 'event 16', 'description' => 'Example description 16', 'year' => 1803],
    ['kid' => '18', 'title' => 'event 17', 'description' => 'Example description 17', 'year' => 1804],
    ['kid' => '19', 'title' => 'event 18', 'description' => 'Example description 18', 'year' => 1806],
    ['kid' => '20', 'title' => 'event 19', 'description' => 'Example description 19', 'year' => 1807],
  ];

  $timeline_event_dates = [];
  foreach ($events as $event) {
    // If there are months and days, put the year into decimal format
    // Ex: March 6, 1805 = 1805.18
    array_push($timeline_event_dates, $event['year']);
  }

  $first_date = min($timeline_event_dates);
  $final_date = max($timeline_event_dates);
  $diff = $final_date - $first_date;

  if ($diff < 10) {
      $increment = 1;
  } elseif ($diff < 20) {
      $increment = 2;
  } elseif ($diff < 40) {
      $increment = 5;
  } elseif ($diff < 90) {
      $increment = 10;
  } else {
      $increment = 20;
  }

  // Hash starts at year that is divisible by incrememnt and before the first event
  $first_date_hash = floor($first_date) - (floor($first_date) % $increment) - $increment;
  $final_date_hash = ceil($final_date) - (ceil($final_date) % $increment) + $increment;

  $hashes = range($first_date_hash, $final_date_hash, $increment);
  $hash_count = count($hashes);
  $hash_range = end($hashes) - $hashes[0];
?>
<!-- Place Full Record page-->
<!-- Heading image and title container-->
<div class="container header place-page">
    <div class="container middlewrap">
        <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>explorePlaces/"><span id="previous-title">Places // </span></a><span id="current-title">Place Name</span></h4>
        <h1>Place Name</h1>
    </div>
</div>
<div class="jump-buttons">
    <div class="jumpwrap">
        <!-- <button class="jump-button" id="timeline">Jump to Person Timeline</button> -->
        <button class="jump-button" id="details">Jump to Place Details</button>
    </div>
</div>
<!-- info container-->
<div class="container info">
    <div class="container infowrap">
        <p>Brief info on Section. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur a tempor incididunt ut labore et dolore magna Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur.</p>
    </div>
</div>
<!-- Story Connections -->
<div class="story-connections record-connections">
    <div class="connectionwrap">
        <h2>Connections</h2>
        <div class="categories">
            <ul>
                <li class="unselected selected" id="people"><div class="person-image"></div>10 People</li>
                <li class="unselected" id="event"><div class="event-image"></div>3 Events</li>
                <li class="unselected" id="place"><div class="place-image"></div>3 Places</li>
                <li class="unselected" id="project"><div class="project-image"></div>2 Projects</li>
                <li class="unselected" id="source"><div class="source-image"></div>15 Sources</li>
                <hr>
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
<!-- Large Map -->
<div class="mapwrap">
    <div id="map-large"></div>
</div>
<!-- detail section
<div class="detail-section">
    <div class="detailwrap">
        <div class="right-col">
            <a href="<?php echo BASE_URL;?>explorePlace/">
                <div class="detail">
                    <div class="detail-top">
                        <h3>Place Type</h3>
                        <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/>
                    </div>
                    <p class="detail-bottom">Port</p>
                </div>
            </a>
            <a href="<?php echo BASE_URL;?>explorePlace/">
                <div class="detail">
                    <div class="detail-top">
                        <h3>City</h3>
                        <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/>
                    </div>
                    <p class="detail-bottom">Cabanas</p>
                </div>
            </a>
            <a href="<?php echo BASE_URL;?>explorePlace/">
                <div class="detail">
                    <div class="detail-top">
                        <h3>Province</h3>
                        <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/>
                    </div>
                    <p class="detail-bottom">Cuba</p>
                </div>
            </a>
        </div>
        <div class="left-col">
            <div class="card">
                <a href="<?php echo BASE_URL?>fullStory/">
                    <div class="container cards">
                        <p class="card-title">The record of this place appears in the *Name of Project* Project</p>
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
</div> -->
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
        <a href="<?php echo BASE_URL;?>explorePlace/">
            <div class="detail">
                <h3>PLACE TYPE</h3>
                <div class="detail-bottom">
                    <div>Port
                        <div class="detail-menu">
                            <h1>Metadata</h1>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                            </p>
                        </div>
                    </div>
                    <h4> | </h4>
                    <div>City
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
<script src="<?php echo BASE_URL;?>assets/javascripts/map.js"></script>
