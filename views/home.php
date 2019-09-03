<!-- Author: Drew Schineller-->
<?php $cache_data = Json_GetData_ByTitle("Home", true) ?>
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
            <p>Start a search across <?php echo counterofAllitems();?> records from the Atlantic Slave Trade <a class="text-link show-desktop-only" href="<?php echo BASE_URL;?>advancedSearch">Go to Advanced Search</a></p>
            <p class="hide-desktop-only mt-xs"><a class="text-link" href="<?php echo BASE_URL;?>advancedSearch">Go to Advanced Search</a></p>
            <form class="search-form" action="<?= BASE_URL ?>search/all">
                <label for="searchbar" class="sr-only">searchbar</label>
                <input id="searchbar" class="search-field main-search" type="text" name="searchbar" placeholder="eg: People, Places, Events, Sources, Projects, Captains, Ships, Voyages, etc."/>
                <button class="search-icon-2" type="submit"><img src="<?php echo BASE_URL;?>/assets/images/Search.svg" alt="search-icon"></button>
                <!-- <img class="search-close" src="<?php echo BASE_URL;?>/assets/images/Close.svg"/> -->
            </form>
        </div>
    </div>
    <div class="image-background-overlay home-page"></div>
      <img class="header-background home-page" src="<?php echo BASE_URL;?>assets/images/enslaved-header-bg.jpg" alt="Enslaved Background Image"></div>
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
                        <p class="count"><?php echo queryAllAgentsCounter();?></p>
                        <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg" alt="link arrow"/>
                    </a>
                    <a  class="content-link"href="<?php echo BASE_URL;?>search/events">
                        <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Event-dark.svg" alt="event icon"/>
                        <p class="type">Events</p>
                        <p class="count"><?php echo queryEventCounter();?></p>
                        <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg" alt="link arrow"/>
                    </a>
                    <a class="content-link" href="<?php echo BASE_URL;?>search/places">
                        <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Place-dark.svg" alt="location icon"/>
                        <p class="type">Places</p>
                        <p class="count"><?php echo queryPlaceCounter();?></p>
                        <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg" alt="link arrow"/>
                    </a>
                    <!-- <a class="content-link" href="<?php echo BASE_URL;?>search/projects">
                        <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Project-dark.svg" alt="project icon"/>
                        <p class="type">Projects</p>
                        <p class="count"><?php echo queryProjectsCounter();?></p>
                        <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg" alt="link arrow"/>
                    </a> -->
                    <a class="content-link" href="<?php echo BASE_URL;?>search/sources">
                        <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Source-dark.svg" alt="source icon"/>
                        <p class="type">Sources</p>
                        <p class="count"><?php echo querySourceCounter();?></p>
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
                    <a class="story-card" href="#story">
                        <img src="<?php echo BASE_URL;?>assets/images/market-stand.jpg" alt="Story Image">
                        <h2 class="card-title">Title of Featured Story Goes Here Like This.</h2>
                        <div class="cover"></div>
                    </a>

                    <a class="story-card" href="#story">
                        <img src="<?php echo BASE_URL;?>assets/images/market-stand.jpg" alt="Story Image">
                        <h2 class="card-title">Title of Featured Story Goes Here Like This.</h2>
                        <div class="cover"></div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="section no-padding section-project">
        <div class="section-content full-width">
            <div class="row">
                <div class="col col-text py-xl">
                    <p class="paragraph">Enslaved links together data from participating projects, allowing students, researchers, and the general public to search over numerous datasets at once in order to better reconstruct the lives of the people involved in the historical slave trade. You can search or browse interconnected data, generate visualizations, and explore short biographies of enslaved people. </p>
                    <p class="mt-lg"><a class="text-link" href="<?php echo BASE_URL;?>">Learn More</a></p>
                </div>

                <div class="col col-image">
                    <img class="background-image" src="<?php echo BASE_URL;?>assets/images/market-stand.jpg" alt="Project Image of Market Stand">
                </div>
            </div>
        </div>
    </div>

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

<script>

    $(document).ready(function () {
        // Create the 2 stories cards
        $.ajax({
            url: BASE_URL + "api/blazegraph",
            type: "GET",
            data: {
                preset: 'stories',
                filters:  {limit: 2},
                templates: ['homeCard']

            },
            'success': function (data) {
                result_array = JSON.parse(data);
                console.log(result_array);
                result_array['homeCard'].forEach(function (card) {
                    $(card).appendTo("#stories-list");
                });
            }
        });

        // Create the 2 projects cards
        $.ajax({
            url: BASE_URL + "api/blazegraph",
            type: "GET",
            data: {
                preset: 'projects',
                filters:  {limit: 2},
                templates: ['homeCard']

            },
            'success': function (data) {
                result_array = JSON.parse(data);
                result_array['homeCard'].forEach(function (card) {
                    $(card).appendTo("#projects-list");
                });
            }
        });

        // Update json cache files for Navigations and webpages
        $.ajax({
            url: BASE_URL + "api/getWebPages",
            type: "GET",
            data: {update: true},
            'success': function (data) {
                data = JSON.parse(data);
                if(data === "updated") console.log("webpages and navcontent cache files updated successfully");
                else if(data === "similar") console.log("webpages and navcontent cache files are up to date");
                else console.log("Failed to update webpages and navcontent cache files");
            }
        });
    });

</script>
