<!-- Page author: Drew Schineller-->
<?php include 'header.php';?>
<!-- Heading image and title container-->
<div class="container header stories">
    <div class="container middlewrap">
        <h1>People</h1>
    </div>
</div>
<!-- info container-->
<div class="container info">
    <div class="container infowrap">
        <p>Brief info on Section. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur a tempor incididunt ut labore et dolore magna Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur.</p>
    </div>
</div>
<!-- explore by -->
<div class="container explore-by">
    <h1>Explore By</h1>
    <ul class="cards">
        <li>
            <a href="<?php echo BASE_URL?>peopleSub/">Gender<div id="arrow"></div></a>
        </li>
        <li>
            <a href="<?php echo BASE_URL?>peopleSub2/">Age Category<div id="arrow"></div></a>
        </li>
        <li>
            <a href="<?php echo BASE_URL?>peopleSub2/">Color<div id="arrow"></div></a>
        </li>
        <li>
            <a href="<?php echo BASE_URL?>peopleSub2/">Role Types<div id="arrow"></div></a>
        </li>
        <li>
            <a href="<?php echo BASE_URL?>peopleSub2/">Time<div id="arrow"></div></a>
        </li>
        <li>
            <a href="<?php echo BASE_URL?>peopleSub2/">Place<div id="arrow"></div></a>
        </li>
    </ul>
</div>
<!-- Featured People -->
<div class="explore-featured">
    <h2>Featured People</h2>
    <div class="connection-cards">
        <ul class="connect-row people">
            <li>
                <a href="<?php echo BASE_URL?>recordPerson/?item=Q503">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Person-light.svg">
                        <h3>Firstname Lastname</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordPerson/">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Person-light.svg">
                        <h3>Firstname Lastname</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordPerson/">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Person-light.svg">
                        <h3>Firstname Lastname</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordPerson/">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Person-light.svg">
                        <h3>Firstname Lastname</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordPerson/">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Person-light.svg">
                        <h3>Firstname Lastname</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordPerson/">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Person-light.svg">
                        <h3>Firstname Lastname</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordPerson/">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Person-light.svg">
                        <h3>Firstname Lastname</h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>recordPerson/">
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Person-light.svg">
                        <h3>Firstname Lastname</h3>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- Search Bar -->
<div class="explore-search">
    <h3>Find People</h3>
    <form class="search-form">
        <input class="search-field main-search" type="text" name="searchbar" placeholder="Start Searching for People By Name, Origin, Role, Etc."/>
        <a href="<?php echo BASE_URL;?>search/"><div class="search-icon"></div></a>
    </form>
</div>
<!-- Visualize People -->
<div class="explore-visualize">
    <h2 class="column-header">Visualize People</h2>
    <div class="cardwrap">
        <ul class="row">
            <li id="byspace">
                <a href="<?php echo BASE_URL?>fullstory/">
                    <div class="cards">
                        <div class="test">
                            <img src="<?php echo BASE_URL?>assets/images/BySpace.svg"/>
                        </div>
                        <p>By Space</p>
                    </div>
                </a>
            </li>
            <li id="bytime">
                <a href="<?php echo BASE_URL?>fullstory/">
                    <div class="cards">
                        <div class="test">
                            <img src="<?php echo BASE_URL?>assets/images/ByTime.svg"/>
                        </div>
                        <p>By Time</p>
                    </div>
                </a>
            </li>
            <li id="bydata">
                <a href="<?php echo BASE_URL?>fullstory/">
                    <div class="cards">
                        <div class="test">
                            <img src="<?php echo BASE_URL?>assets/images/ByData.svg"/>
                        </div>
                        <p>By Data</p>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>

<script src="<?php echo BASE_URL;?>assets/javascripts/explore.js"></script>

<?php include 'footer.php';?>
