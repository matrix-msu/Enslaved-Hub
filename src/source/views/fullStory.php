<?php
if (isset($_GET['kid']) && preg_match("/^[0-9A-F]+-[0-9A-F]+-[0-9A-F]+(-[0-9A-F]+)*$/", $_GET['kid'])) {
    // $story = storyContent($_GET['kid']);

    // Getting Story using korawrappper
    $fields = ['Title', 'Images', 'Caption', 'Text', 'Resources', 'Source', 'Creator', 'Contributor', 'Timeline', 'Story_Associator','Contributing Institution', 'Connection'];
    $koraResult = koraWrapperSearch(STORY_SID, $fields, "kid", $_GET['kid']);
    $koraResult = json_decode($koraResult, true);
    if(!array_key_exists("error", $koraResult)) $story = $koraResult['records'][0][ $_GET['kid'] ];


    $images = [];
    $caption = [];
    if (isset($story['Images'])) {
        foreach ($story['Images'] as $image) {
            $images[] = $image['url'];
            $caption[] = $image['caption'];
        }
    }
}
else {

}
  $url = BASE_URL."fullStory/?kid=".$_GET['kid'];
?>
<!-- Full Story page-->
<!-- Heading image and title container-->
<head>
  <meta property="og:url"           content='"'.<?php echo $url ?>.'"' />
  <meta property="og:type"          content="website" />
  <meta property="og:title"         content="Peoples of the Historic Slave Trade" />
  <meta property="og:description"   content="" />
  <meta property="og:image"         content="/assets/images/IMG02.jpg" />

</head>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v6.0"></script>
<div class="container header fullstory">
  <div class="image-container fullStory-page image-only">
    <img class="header-background fullStory-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">
    <div class="container middlewrap">
          <?php
          $Featured_title = "Featured Story Title Goes Here Like This";
          if (isset($story['Title'])) {
              $Featured_title = $story['Title'];
          }
          ?>
          <!-- <h1>Ibrahima Abd al-Rahman</h1>
          <h3>(18th/19th century)</h3> -->

          <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>stories"><span id="previous-title">Stories / </span></a><span id="current-title"><?php echo $Featured_title; ?></span></h4>

          <?php
          if (isset($story['Title'])) {
              echo '<h1>'.$story['Title'].'</h1>';
          }
          ?>
          <!-- <h2>Sub Title</h2> -->

    </div>
    <div class="image-background-overlay"></div>
  </div>
</div>
<!-- Main content (left/right columns)-->
<main class="story-content info">
    <div class="container columnwrap infowrap"> 
        <article class="left-column">
            <section>
                <?php
                if (isset($story['Text'])) {
                    echo $story['Text'];
                }
                ?>
            </section>
            <!--<section class="creator">
                <?php
                if (isset($story['Creator'])) {
                    echo '<h2>Creator</h2>';
                    echo '<p>'.$story['Creator'].'</p>';
                }
                ?>
            </section>-->
            <section class="online-resources">
                <?php
                if (isset($story['Object PDF']) && isset($story['Object PDF'][0])) {
                    $pdf_url = $story['Object PDF'][0]['url'];
                    echo '<p><a target="_blank" href="'.$pdf_url.'">View complete story (pdf)</a></p>';
                }
                ?>                <?php
                if (isset($story['Online Resources'])) {
                    echo '<h2>Online Resources</h2>';
                    echo ''.$story['Online Resources'].'';
                }
                ?>
            </section>
            <section class="bibliography">
                <?php
                if (isset($story['Source'])) {
                    echo '<h2>Bibliography</h2>';
                    echo '<p>'.$story['Source'].'</p>';
                }
                ?>
            </section>
            <section class="editor">
                <?php
                if (isset($story['Contributor'])) {
                    echo '<h2>Adapted by</h2>';
                    echo '<p>'.$story['Contributor'].'</p>';
                }
                ?>
            </section>
            <section class="contributing_institution">
                <?php
                if (isset($story['Contributing Institution'])) {
                    echo '<h2>Contributing Institutions</h2>';
                    echo '<p>'.$story['Contributing Institution'].'</p>';
                }
                ?>
            </section>
        </article>
        <article class="right-column">
            <?php if (isset($images) && count($images) > 0) { ?>
                <div class="container sliderwrap">
                    <div class="slider">
                    </div>
                    <!-- <div class="image-pagination">
                        <img id="prev-arrow" onclick="plusSlides(-1)" src="<?php echo BASE_URL?>assets/images/Arrow3.svg" alt="arrow">
                        <div class="dotwrap" style="text-align:center">
                        </div>
                        <img id="next-arrow" onclick="plusSlides(1)" src="<?php echo BASE_URL?>assets/images/Arrow3.svg" alt="arrow">
                    </div> -->
                    <?php if (isset($images) && count($images) > 1) { ?>
                      <div class="controls">
                          <div class="arrows">
                              <div class="prev" onclick="plusSlides(-1)"><img src="<?php echo BASE_IMAGE_URL?>Arrow3.svg" alt="arrow"></div>
                              <div class="next" onclick="plusSlides(1)"><img src="<?php echo BASE_IMAGE_URL?>Arrow3.svg" alt="arrow"></div>
                          </div>
                          <div class="dots">
                          </div>
                      </div>
                    <?php } ?>
                    <div class="expand modal">
                        <img src="<?php echo BASE_URL?>assets/images/maximize.svg" alt="maximize">
                    </div>
                    <!-- <div class="cation" style="text-align: center; padding-top: 20px; opacity: 0.7;"> -->
                    <div class="caption">
                        <p class="caption-text"><?php if(isset($caption[0])) echo $caption[0]; ?></p>
                    </div>
                </div>
            <?php } ?>

            <!-- Story Connections -->
            <?php
                if(isset($story['Connection'])) {
            ?>
            <script>
                var storyConnectionData = <?php echo json_encode($story['Connection']) ?>;
            </script>
            <div class="story-connections">
                <div class="story-connectionwrap">
                <h2>Story Connections</h2>
                <div class="story-categories">
                    <ul>
                        <li class="story-unselected story-selected" id="people"><div class="person-image"></div>People</li>
                        <li class="story-unselected" id="event"><div class="event-image"></div>Events</li>
                        <li class="story-unselected" id="place"><div class="place-image"></div>Places</li>
                        <li class="story-unselected" id="project"><div class="project-image"></div>Projects</li>
                        <li class="story-unselected" id="source"><div class="source-image"></div>Sources</li>
                        <hr>
                    </ul>
                </div>
                <div class="story-connection-cards">
                    <ul class="story-connect-row">
                    </ul>
                </div>
            </div>
            </div>
            <?php
                }
            ?>

            <div class="key-events">
                <?php
                if (isset($story['Timeline'])) {
                    echo '<h3 class="key-events-title">Key Events</h3>';
                    foreach ($story['Timeline'] as $event) {
                        echo '<h3 class="key-events-date">'.$event['Date'].'</h3>';
                        echo '<p class="key-events-text">'.$event['Description'].'</p>';
                    }
                }
                ?>
                <!-- <h3 class="key-events-title">Key Events</h3>
                <h3 class="key-events-date">c. 1762</h3>
                <p class="key-events-text">Born in Timbo, in the kingdom of Futa Jallon (Guinea).</p>
                <h3 class="key-events-date">c. 1788</h3>
                <p class="key-events-text">Captured and sold to John Nevin, an English slave trader, captain of the slave ship Africa. Abd al-Rahman is transported to the West Indies and then, via New Orleans, to Spanish Natchez, where he is  sold to Thomas Foster, owner of  a small plantation outside of Natchez.</p>
                <h3 class="key-events-date">c. 1794 - December 25</h3>
                <p class="key-events-text">Marries Isabella, a Baptist slave on the plantation. Together, Abd al-Rahman and Isabella have five sons (Simon, Prince, Lee, Abram, and one unknown) as well as four daughters with unknown names.</p>
                <h3 class="key-events-date">c. 1807</h3>
                <p class="key-events-text">John Coates Cox arrives in Natchez. Cox knew Abd al-Rahman from his six-month stay in Timbo, Futa Jallon, where he had stayed as a guest of Abd al-Rahman’s family while recovering from an illness.</p>
                <h3 class="key-events-date">c. 1826</h3>
                <p class="key-events-text">Abd al-Rahman writes a letter home to Futa Jallon, which end sup in the hands of the Moroccan government. Given that he is a Muslim, the Moroccans request his freedom. As a result, Secretary of State Henry Clay advises President John Quincy Adams to ask Thomas Foster for his release.</p>
                <h3 class="key-events-date">c. 1828</h3>
                <p class="key-events-text">Abd al-Rahman is freed by Foster, and agrees to leave the United States. He also raises enough money to free his wife, Isabella. Over the course of 1828 and into early 1829, Abd al-Rahman campaigns for money to redeem his children and bring them back to West Africa. With the help of the American Colonization Society, he visits the White House and Congress. He also holds public events with African American leaders, including John Brown Russwurm, the editor of Freedom’s Journal, the first black newspaper, and David Walker, a Boston abolitionist who would soon publish the radical abolitionist pamphlet, Appeal to the Colored People of the World (1829).</p>
                <h3 class="key-events-date">c. 1829 - February 9</h3>
                <p class="key-events-text">Abd al-Rahman sails to Monrovia, Liberia, along with his wife Isabella and 153 other former slaves, returning to West Africa for the first time in forty years.</p>
                <h3 class="key-events-date">c. 1829 - Late</h3>
                <p class="key-events-text">Abd al-Rahman becomes sick and dies. The following year  his wife Isabella is reunited with some of her children and grandchildren in Liberia. She lived there until at least 1843, when her name appears in the Liberian census.</p>
                <h3 class="key-events-date">c. 1975</h3>
                <p class="key-events-text">Artemus Gaye, a seventh generation descendant of Abd al-Rahman, is born in Liberia. In the 1990s he flees Liberia during that nation’s civil war, finding refuge in America, where he has lectured on his ancestor’s legacy for our own times.</p> -->
            </div>
        </article>
    </div>
</main>


<!-- Related Stories -->
<!--<div class="container card-column related-card">
    <div class="container cardheader-wrap">
        <h2 class="column-header">Featured in these Stories</h2>
    </div>
    <div class="container card-wrap">
        <ul class="card-row">
            <li>
                <a href="<?php echo BASE_URL?>fullStory">
                    <h2 class="card-title">Title of Featured Story Goes Here Like This</h2>
                </a>
                <div class="overlay"></div>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>fullStory">
                    <h2 class="card-title">Title of Featured Story Goes Here Like This</h2>
                </a>
                <div class="overlay"></div>
            </li>
        </ul>
    </div>
</div> -->
<!-- Extra Info -->
</br>
</br>
</br>
<div class="extra-info">
    <div class="copyright">
        <h2>Copyright</h2>
        <a href="https://creativecommons.org/licenses/by/4.0/" target="_blank">
            <img class="cc-by-nc" src="<?php echo BASE_URL;?>assets/images/CC-BY.svg" alt="copyrights"/>
        </a>
    </div>
</div>
<!-- Modal View -->
<div class="modal-view">
    <div class="modal-image">
    </div>
</div>

<style>
.twitter-share-button[style] { vertical-align: text-bottom !important; }
</style>

<script>
var captions = <?php echo json_encode($caption); ?>;
var result_array = <?php echo json_encode($images); ?>;
var recordform = "Story";
</script>


<!-- Add Story Imagery and Kora Alt Text Caption + Slider Functionality -->
<script>
$.each(result_array,function ( index, value ) {
    $('<img class="mySlides fade" src="'+value+'" alt="'+captions[index]+'">').appendTo("div.slider"); //add images to the slider
    // $('<p class="key-events-text">Cation goes here</p>').appendTo("div.slider"); //add images to the slider
    if (result_array.length > 1){
        $('<span class="dot" onclick="currentSlide('+(index+1)+')"></span>').appendTo("div.dots");
    } else{
        $('div.image-pagination').css('display','none');
    }
});

var slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dot");

    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    if(slideIndex <= captions.length){$(".caption-text").text(captions[slideIndex-1])}
    // console.log(slideIndex);
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
    }

    slides[slideIndex-1].style.display = "block";

    if (result_array.length > 1){
        dots[slideIndex-1].className += " active";
    }

}
</script>


<script src="<?php echo BASE_URL;?>assets/javascripts/modal.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/storyConnections.js"></script>
