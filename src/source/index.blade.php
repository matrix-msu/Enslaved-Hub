@extends('_layouts.main')

@section('body')
<!-- Author: Drew Schineller-->
<?php $cache_data = Json_GetData_ByTitle("Home", true);

//kora query to get featured stories
$koraResults = koraWrapperSearch(
    STORY_SID,
    'ALL',
    ['Featured'],
    "TRUE",
    [],
    0,
    10
);
$featuredStories = json_decode($koraResults,true);
$featuredResults = $featuredStories['records'][0];

//get keys and randomly select 2
$featuredKeys = array_keys($featuredResults);
$randKeys = array_rand($featuredKeys,2);
//use keys to get records
$randomStory1 = $featuredKeys[$randKeys[0]];
$randomStory2 = $featuredKeys[$randKeys[1]];
//get titles from records
$randomTitle1 = $featuredResults[$randomStory1]["Title"];
$randomTitle2 = $featuredResults[$randomStory2]["Title"];

//get images from records
$story1Images = $featuredResults[$randomStory1]["Images"];
if (isset($story1Images[0])){
    $story1Image = $story1Images[0]['url'];
} else {
    $story1Index = array_rand($bg);
    $story1Image = BASE_URL.'assets/images/'.$bg[$story1Index];
}

$story2Images = $featuredResults[$randomStory2]["Images"];
if (isset($story2Images[0])){
    $story2Image = $story2Images[0]['url'];
} else {
    $story2Index = array_rand($bg);
    $story2Image = BASE_URL.'assets/images/'.$bg[$story2Index];
}
?>
<!-- Main page-->
<!-- Heading image and title container-->
<div class="container header home-page">
  <div class="image-container">
    <div class="container middlewrap home-page">
        <div class="heading-text">
            <div class="heading-title">
                <img class="logo-main" src="<?php echo BASE_IMAGE_URL;?>Logo-Landing.svg" width="780" height="99" alt="Enslaved Peoples of Historical Slave Trade"/>
                <img class="logo-mobile" src="<?php echo BASE_IMAGE_URL;?>Logo-Landing-Mobile.svg" width="409" height="132" alt="Enslaved Peoples of Historical Slave Trade"/>
            </div>
            <p><?php echo $cache_data["descr"] ?> </p>
        </div>
        <div class="heading-search">
            <p>Start a search across <span id="count-all"></span> records from the historical slave trade <a class="text-link show-desktop-only" href="<?php echo BASE_URL;?>advancedSearch">Go to Advanced Search</a></p>
            <p class="hide-desktop-only mt-xs"><a class="text-link" href="<?php echo BASE_URL;?>advancedSearch">Go to Advanced Search</a></p>
            <form class="search-form" action="<?= BASE_URL ?>search/all">
                <label for="searchbar" class="sr-only">searchbar</label>
                <input id="searchbar" class="search-field main-search" type="text" name="searchbar" placeholder="Search People, Events, and Places"/>
                <button class="search-icon-2" type="submit"><img src="<?php echo BASE_URL;?>/assets/images/Search.svg" alt="search-icon"></button>
            </form>
        </div>
    </div>
    <div class="image-background-overlay home-page"></div>
    <img class="header-background home-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $GLOBALS['bg'][$GLOBALS['randIndex']];?>" alt="Enslaved Background Image"></div>
</div>
<main id="home" class="home">
    <section class="section section-explore">
        <div class="section-content">
            <div class="row">
                <div class="col">
                    <h2 class="section-title">Explore</h2>
                    <p class="paragraph"><?php echo array_key_exists("Explore", $cache_data) ? $cache_data["Explore"] : "" ?></p>
                </div>

                <div class="col">
                    <a class="content-link" href="<?php echo BASE_URL;?>search/people">
                        <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Person-dark.svg" alt="person icon"/>
                        <p class="type">People</p>
                        <p class="count" id="count-people"></p>
                        <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg" alt="link arrow"/>
                    </a>
                    <a  class="content-link"href="<?php echo BASE_URL;?>search/events">
                        <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Event-dark.svg" alt="event icon"/>
                        <p class="type">Events</p>
                        <p class="count" id="count-event"></p>
                        <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg" alt="link arrow"/>
                    </a>
                    <a class="content-link" href="<?php echo BASE_URL;?>search/places">
                        <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Place-dark.svg" alt="location icon"/>
                        <p class="type">Places</p>
                        <p class="count" id="count-place"></p>
                        <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg" alt="link arrow"/>
                    </a>
                    <a class="content-link" href="<?php echo BASE_URL;?>search/sources">
                        <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Source-dark.svg" alt="source icon"/>
                        <p class="type">Sources</p>
                        <p class="count" id="count-source"></p>
                        <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg" alt="link arrow"/>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="section dark section-stories">
        <div class="section-content">
            <div class="row">
                <div class="col">
                    <h2 class="section-title">Stories</h2>
                    <p class="paragraph"><?php echo array_key_exists("Stories", $cache_data) ? $cache_data["Stories"] : "" ?></p>
                   <!--<p class="paragraph">Learn about both prominent and everyday people associated with the historical slave trade through short biographical sketches.</p>-->
                    <p class="mt-lg"><a class="text-link" href="<?php echo BASE_URL;?>stories">View All Stories</a></p>
                </div>

                <div class="col">
                    <!-- <a class="story-card" href="#story">
                        <img src="<?php echo BASE_URL;?>assets/images/market-stand.jpg" alt="Story Image">
                        <h2 class="card-title">Title of Featured Story Goes Here Like This.</h2>
                        <div class="cover"></div>
                    </a>

                    <a class="story-card" href="#story">
                        <img src="<?php echo BASE_URL;?>assets/images/market-stand.jpg" alt="Story Image">
                        <h2 class="card-title">Title of Featured Story Goes Here Like This.</h2>
                        <div class="cover"></div>
                    </a> -->
                    <a class="story-card" href="<?=BASE_URL;?>fullStory/<?=$randomStory1;?>/">
                        <img src="<?=$story1Image?>" alt="Story Image">
                        <h2 class="card-title"><?=$randomTitle1?></h2>
                        <div class="overlay"></div>
                    </a>

                    <a class="story-card" href="<?=BASE_URL;?>fullStory/<?=$randomStory2;?>/">
                        <img src="<?=$story2Image?>" alt="Story Image">
                        <h2 class="card-title"><?=$randomTitle2?></h2>
                        <div class="overlay"></div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="section section-project">
        <div class="section-content">
            <div class="row">
                <div class="col">
                  <h2 class="section-title">About</h2>
                    <p class="paragraph"><?php echo array_key_exists("About", $cache_data) ? $cache_data["About"] : "" ?></p>
                    <p class="mt-lg"><a class="text-link" href="<?php echo BASE_URL."about";?>">Learn More</a></p>
                </div>

                <div class="col">
                    <img class="background-image" src="<?php echo BASE_URL;?>assets/images/market-stand.jpg" alt="Project Image of Market Stand">
                </div>
            </div>
        </div>
    </div>

    <section class="section section-epp">
        <div class="section-content">
            <div class="row">
                    <h2 class="section-title">Journal of Slavery and Data Preservation</h2>
                    <p class="paragraph"><?php echo array_key_exists("Journal of Slavery and Data Preservation", $cache_data) ? $cache_data["Journal of Slavery and Data Preservation"] : "" ?></p>
                    <p class="mt-lg"><a class="text-link wrap" href="http://jsdp.enslaved.org" target="_blank">Go to the <em>Journal of Slavery and Data Preservation</em></a></p>
            </div>
        </div>
    </section>

    <section class="section section-news">
        <div class="section-content">

                    <h2 class="section-title">Featured News</h2>
                    <div class="news-block">
                      <a class="news-logo" target="_blank" href="https://msutoday.msu.edu/news/2020/enslaved-new-phase"><img src="<?php echo BASE_URL;?>/assets/images/news-msutoday.svg" alt="MSU Today Logo"/></a>
                      <a class="news-logo" target="_blank" href="https://today.umd.edu/articles/reconstructing-fragmented-lives-88e94570-0f28-4604-ace2-777e74777e37"><img src="<?php echo BASE_URL;?>/assets/images/news-marylandtoday.png" alt="Maryland Today Logo"/></a>
                      <a class="news-logo" target="_blank" href="https://www.smithsonianmag.com/history/sweeping-new-digital-database-emphasizes-enslaved-peoples-individuality-180976513/"><img src="<?php echo BASE_URL;?>/assets/images/news-smithsonian.svg" alt="Smithsonian Logo"/></a>
                      <a class="news-logo" target="_blank" href="https://www.washingtonpost.com/history/2020/12/01/slavery-database-family-genealogy/"><img src="<?php echo BASE_URL;?>/assets/images/news-washingtonpost.svg" alt="Washington Post Logo"/></a>
                      <a class="news-logo" target="_blank" href="https://www.npr.org/2020/12/09/944739710/enslaved-org-shares-lives-and-experiences-of-the-enslaved"><img src="<?php echo BASE_URL;?>/assets/images/news-npr.svg" alt="NPR Logo"/></a>
                      <a class="news-logo" target="_blank" href="https://www.fastcompany.com/90582344/this-massive-database-reveals-the-names-and-stories-behind-the-history-of-slavery"><img src="<?php echo BASE_URL;?>/assets/images/news-fastcompany.svg" alt="Fast Company Logo"/></a>
                    </div>
                    <p class="mt-lg"><a class="text-link wrap" href="<?php echo BASE_URL."featuredNews/";?>">View All Featured News</em></a></p>

        </div>
    </section>

    <div class="section-wrap visualize-hide">
        <div class="section-info">
            <a href="<?php echo BASE_URL;?>visualize"><h2>Visualize<img src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg" alt="arrow"/></h2></a>
            <p><?php echo array_key_exists("Visualize", $cache_data) ? $cache_data["Visualize"] : "" ?></p>
        </div>
        <div class="section-content">
            <div class="content-link">
                <img class="icon" src="<?php echo BASE_URL;?>/assets/images/space.svg" alt="space icon"/>
                <h3>Space</h3>
                <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg" alt="arrow"/>
            </div>
            <div class="content-link">
                <img class="icon" src="<?php echo BASE_URL;?>/assets/images/time.svg" alt="time icon"/>
                <h3>Time</h3>
                <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg" alt="arrow"/>
            </div>
            <div class="content-link">
                <img class="icon" src="<?php echo BASE_URL;?>/assets/images/data.svg" alt="data icon"/>
                <h3>Data</h3>
                <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg" alt="arrow"/>
            </div>
        </div>
    </div>



    <!--<div id='underDev' class='modal-preview'>
      <div>
        <div class="modal-x-btn closeUnderDev">
          <img src='<?php echo BASE_URL;?>/assets/images/x-dark.svg' class="closeUnderDev" />
        </div>
        <div class='modal-content'>
          <h1>Welcome to the launch of <em>Enslaved.org</em></h1>
          <p>As of December 2020, we have built a robust, open-source architecture to discover and explore nearly a half million people records and 5 million data points. From archival fragments and spreadsheet entries, we see the lives of the enslaved in richer detail. Yet there’s much more work to do, and with the help of scholars, educators, and family historians, <em>Enslaved.org</em> will be rapidly expanding in 2021. Don't hesitate to give us feedback by visiting our <a href="<?php echo BASE_URL;?>about">About page</a>.<br><br>We are just getting started.</p>
          <form class="modal-form">
            <div class='modalCheckbox'>
              <input type='checkbox' value='1' id='modalCheckboxInput' name='' />
              <label for='modalCheckboxInput' class='modalCheckboxBox'></label>
              <label for='modalCheckboxInput' class='modalCheckboxText'>Don't Show Again</label>
            </div>
            <button type='button' class='modal-button closeUnderDev'>Start Browsing</button>
          </form>
        </div>
      </div>
    </div> -->

</main>



<script src="<?php echo BASE_URL;?>assets/javascripts/home.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/js.cookie.js"></script>

@endsection
