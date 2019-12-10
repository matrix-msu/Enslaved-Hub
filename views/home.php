<!-- Author: Drew Schineller-->
<?php $cache_data = Json_GetData_ByTitle("Home", true);
$bg = ['enslaved-header-bg.jpg','enslaved-header-bg2.jpg',
        'enslaved-header-bg3.jpg','enslaved-header-bg4.jpg',
        'enslaved-header-bg5.jpg','enslaved-header-bg6.jpg',
        'enslaved-header-bg7.jpg'];
$randIndex = array_rand($bg);

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
// print_r($featuredResults);die;

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
                <img class="logo-main" src="<?php echo BASE_IMAGE_URL;?>Logo-Landing.svg" width="780" height="99" alt="Enslaved People of Historic Slave Trade"/>
                <img class="logo-mobile" src="<?php echo BASE_IMAGE_URL;?>Logo-Landing-Mobile.svg" width="409" height="132" alt="Enslaved People of Historic Slave Trade"/>
            </div>
            <p><?php echo $cache_data["descr"] ?> </p>
        </div>
        <div class="heading-search">
            <p>Start a search across <span id="count-all"></span> records from the Atlantic Slave Trade <a class="text-link show-desktop-only" href="<?php echo BASE_URL;?>advancedSearch">Go to Advanced Search</a></p>
            <p class="hide-desktop-only mt-xs"><a class="text-link" href="<?php echo BASE_URL;?>advancedSearch">Go to Advanced Search</a></p>
            <form class="search-form" action="<?= BASE_URL ?>search/all">
                <label for="searchbar" class="sr-only">searchbar</label>
                <input id="searchbar" class="search-field main-search" type="text" name="searchbar" placeholder="eg: People, Places, Events, Sources, Projects, Captains, Ships, Voyages, etc."/>
                <button class="search-icon-2" type="submit"><img src="<?php echo BASE_URL;?>/assets/images/Search.svg" alt="search-icon"></button>
            </form>
        </div>
    </div>
    <div class="image-background-overlay home-page"></div>
    <div class="cache-header-images">
        <?php foreach ($bg as $background){
            echo "<img src=".BASE_URL."assets/images/".$background.">";
        }?>
    </div>
    <img class="header-background home-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image"></div>
</div>
<main class="home">
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
                        <p class="count" id="count-events"></p>
                        <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg" alt="link arrow"/>
                    </a>
                    <a class="content-link" href="<?php echo BASE_URL;?>search/places">
                        <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Place-dark.svg" alt="location icon"/>
                        <p class="type">Places</p>
                        <p class="count" id="count-places"></p>
                        <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg" alt="link arrow"/>
                    </a>
                    <a class="content-link" href="<?php echo BASE_URL;?>search/sources">
                        <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Source-dark.svg" alt="source icon"/>
                        <p class="type">Sources</p>
                        <p class="count" id="count-sources"></p>
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
                    <a class="story-card" href="<?=BASE_URL;?>fullStory?kid=<?=$randomStory1;?>">
                        <img src="<?=$story1Image?>" alt="Story Image">
                        <h2 class="card-title"><?=$randomTitle1?></h2>
                        <div class="cover"></div>
                    </a>

                    <a class="story-card" href="<?=BASE_URL;?>fullStory?kid=<?=$randomStory2;?>">
                        <img src="<?=$story2Image?>" alt="Story Image">
                        <h2 class="card-title"><?=$randomTitle2?></h2>
                        <div class="cover"></div>
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
                    <p class="paragraph">Enslaved links together data from participating projects, allowing students, researchers, and the general public to search over numerous datasets at once in order to better reconstruct the lives of the people involved in the historical slave trade. You can search or browse interconnected data, generate visualizations, and explore short biographies of enslaved people. </p>
                    <p class="mt-lg"><a class="text-link" href="<?php echo BASE_URL;?>">Learn More</a></p>
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
                    <h2 class="section-title">The Enslaved Publishing Platform</h2>
                    <!--<p class="paragraph"><?php echo array_key_exists("Explore", $cache_data) ? $cache_data["Explore"] : "" ?></p>-->
                    <p class="paragraph">Brief info on Section. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et.</p>
                    <p class="mt-lg"><a class="text-link" href="http://dev2.matrix.msu.edu/enslaved-publishing-platform/" target="_blank">Go to the Enslaved Publishing Platform</a></p>
            </div>
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
</main>

<script src="<?php echo BASE_URL;?>assets/javascripts/home.js"></script>
