<!-- Author: Drew Schineller-->
<!-- Main page-->
<!-- Heading image and title container-->
<div class="container header">
    <div class="container middlewrap">
        <h1>Explore</h1>
    </div>
</div>
<main class="explore">
    <div class="section-wrap">
        <div class="section-info">
            <p>Brief info on Section. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur a tempor incididunt ut labore et dolore magna Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur  Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur aadipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum.</p>
        </div>
        <div class="section-content">
            <a href="<?php echo BASE_URL;?>search/people">
                <div class="content-link">
                    <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Person-dark.svg" alt="person icon"/>
                    <h3>People</h3>
                    <span><?php echo queryAllAgentsCounter();?></span>
                    <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg" alt="link arrow"/>
                </div>
            </a>
            <a href="<?php echo BASE_URL;?>search/events">
                <div class="content-link">
                    <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Event-dark.svg" alt="event icon"/>
                    <h3>Events</h3>
                    <span><?php echo queryEventCounter();?></span>
                    <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg" alt="link arrow"/>
                </div>
            </a>
            <a href="<?php echo BASE_URL;?>search/places">
                <div class="content-link">
                    <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Place-dark.svg" alt="location icon"/>
                    <h3>Places</h3>
                    <span><?php echo queryPlaceCounter();?></span>
                    <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg" alt="link arrow"/>
                </div>
            </a>
            <a href="<?php echo BASE_URL;?>projects">
                <div class="content-link">
                    <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Project-dark.svg" alt="project icon"/>
                    <h3>Projects</h3>
                    <span><?php echo queryProjectsCounter();?></span>
                    <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg" alt="link arrow"/>
                </div>
            </a>
            <a href="<?php echo BASE_URL;?>search/sources">
                <div class="content-link">
                    <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Source-dark.svg" alt="source icon"/>
                    <h3>Sources</h3>
                    <span><?php echo querySourceCounter();?></span>
                    <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg" alt="link arrow"/>
                </div>
            </a>
        </div>
    </div>
</main>
