<!-- Author: Drew Schineller-->
<!-- Main page-->
<!-- Heading image and title container-->
<div class="container header">
    <div class="container middlewrap">
        <div class="heading-text">
            <div class="heading-title">
                <h1>Enslaved: </h1>
                <h2>People of the Historic Slave Trade</h2>
            </div>
            <p>Project Intro lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore, sed do eiusmod tempor incididunt ut labore. <a href="<?php echo BASE_URL?>about">Learn More</a></p>
        </div>
        <div class="heading-search">
            <h3>Search across <?php echo counterofAllitems();?> records from the Atlantic Slave Trade ...</h3>
            <form class="search-form">
                <input class="search-field main-search" type="text" name="searchbar" placeholder="eg: People, Places, Events, Sources, Projects, Captains, Ships, Voyages, etc."/>
                <a href="<?php echo BASE_URL;?>search"><div class="search-icon"></div></a>
                <!-- <img class="search-close" src="<?php echo BASE_URL;?>/assets/images/Close.svg"/> -->
            </form>
        </div>
    </div>
</div>
<main class="home">
    <div class="section-wrap">
        <div class="section-info">
            <a href="<?php echo BASE_URL;?>explore"><h2>Explore<img src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/></h2></a>
            <p>Brief info on Section. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  …</p>
        </div>
        <div class="section-content">
            <a href="<?php echo BASE_URL;?>explorePeople/">
                <div class="content-link">
                    <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Person-dark.svg"/>
                    <h3>People</h3>
                    <span><?php echo queryAllAgentsCounter();?></span>
                    <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/>
                </div>
            </a>
            <a href="<?php echo BASE_URL;?>exploreEvents/">
                <div class="content-link">
                    <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Event-dark.svg"/>
                    <h3>Events</h3>
                    <span><?php echo queryEventCounter();?></span>
                    <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/>
                </div>
            </a>
            <a href="<?php echo BASE_URL;?>explorePlaces/">
                <div class="content-link">
                    <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Place-dark.svg"/>
                    <h3>Places</h3>
                    <span><?php echo queryPlaceCounter();?></span>
                    <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/>
                </div>
            </a>
            <a href="<?php echo BASE_URL;?>projects/">
                <div class="content-link">
                    <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Project-dark.svg"/>
                    <h3>Projects</h3>
                    <span><?php echo queryProjectsCounter();?></span>
                    <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/>
                </div>
            </a>
            <a href="<?php echo BASE_URL;?>exploreSources/">
                <div class="content-link">
                    <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Source-dark.svg"/>
                    <h3>Sources</h3>
                    <span><?php echo querySourceCounter();?></span>
                    <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/>
                </div>
            </a>
        </div>
    </div>
    <div class="section-wrap visualize-hide">
        <div class="section-info">
            <a href="<?php echo BASE_URL;?>visualize"><h2>Visualize<img src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/></h2></a>
            <p>Brief info on Section. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum dolor tempor aliqua  consectetur  …</p>
        </div>
        <div class="section-content">
            <div class="content-link">
                <img class="icon" src="<?php echo BASE_URL;?>/assets/images/space.svg"/>
                <h3>Space</h3>
                <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/>
            </div>
            <div class="content-link">
                <img class="icon" src="<?php echo BASE_URL;?>/assets/images/time.svg"/>
                <h3>Time</h3>
                <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/>
            </div>
            <div class="content-link">
                <img class="icon" src="<?php echo BASE_URL;?>/assets/images/data.svg"/>
                <h3>Data</h3>
                <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/>
            </div>
        </div>
    </div>
    <div class="section-wrap">
        <div class="section-info">
            <a href="<?php echo BASE_URL;?>stories/"><h2>Stories<img src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/></h2></a>
            <p>Brief info on Section. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  …</p>
        </div>
        <div class="section-content">
            <ul class="row" id="stories-list">
<!--                <li>-->
<!--                    <a href="--><?php //echo BASE_URL?><!--fullStory/">-->
<!--                        <div class="container cards">-->
<!--                            <p class="card-title">Title of Featured Story Goes Here Like This.</p>-->
<!--                            <h4 class="card-view-story">View Story <div class="view-arrow"></h4>-->
<!--                        </div>-->
<!--                    </a>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="--><?php //echo BASE_URL?><!--fullStory/">-->
<!--                        <div class="container cards">-->
<!--                            <p class="card-title">Title of Featured Story Goes Here Like This.</p>-->
<!--                            <h4 class="card-view-story">View Story <div class="view-arrow"></h4>-->
<!--                        </div>-->
<!--                    </a>-->
<!--                </li>-->
            </ul>
        </div>
    </div>
    <div class="section-wrap">
        <div class="section-info">
            <a href="<?php echo BASE_URL;?>projects"><h2>Projects<img src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg"/></h2></a>
            <p>Brief info on Section. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  …</p>
        </div>
        <div class="section-content">
            <ul class="row" id="projects-list">
<!--                <li>-->
<!--                    <a href="--><?php //echo BASE_URL?><!--fullStory/">-->
<!--                        <div class="container cards">-->
<!--                            <p class="card-title">Title of Featured Project Goes Here Like This.</p>-->
<!--                            <h4 class="card-view-story">View Project <div class="view-arrow"></h4>-->
<!--                        </div>-->
<!--                    </a>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="--><?php //echo BASE_URL?><!--fullStory/">-->
<!--                        <div class="container cards">-->
<!--                            <p class="card-title">Title of Featured Project Goes Here Like This.</p>-->
<!--                            <h4 class="card-view-story">View Project <div class="view-arrow"></h4>-->
<!--                        </div>-->
<!--                    </a>-->
<!--                </li>-->
            </ul>
        </div>
    </div>
</main>
<div class="about-preview">
    <div class="preview-wrap">
        <div class="heading">
            <h2>Inspirational, Attention Grabbing Heading Goes Here Like This.</h2>
        </div>
        <div class="description">
            <p>Brief info on Project. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit et dolore magna Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        </div>
        <div class="about-card">
            <a href="<?php echo BASE_URL?>fullStory/">
                <div class="container cards">
                    <p class="card-title">Learn More About the Project.</p>
                    <h4 class="card-view-story">Go to About Page <div class="view-arrow"></h4>
                </div>
            </a>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {

        // Create the 2 stories cards
        $.ajax({
            url: BASE_URL + "api/blazegraph",
            type: "GET",
            data: {
                preset: 'stories',
                filters:  {limit: 2},
                template: 'homeCard'

            },
            'success': function (data) {
                result_array = JSON.parse(data);
                result_array.forEach(function (card) {
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
                template: 'homeCard'

            },
            'success': function (data) {
                result_array = JSON.parse(data);
                result_array.forEach(function (card) {
                    $(card).appendTo("#projects-list");
                });
            }
        });
    });

</script>
