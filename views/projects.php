<!-- Page author: Drew Schineller-->
<?php 

// Dynamically pull data from cache file (webPages.json)
$cached_data = file_get_contents(BASE_PATH . "/wikiconstants/webPages.json");
$cached_data = json_decode($cached_data, true); // Convert the json string to a php array

$title = "Projects";
$description = "";
foreach ($cached_data as $content) {
    if(array_key_exists("SubNavigation Display", $content) && $content["SubNavigation Display"]["value"] == "FALSE") continue;
    if(array_key_exists("Title", $content) && $content["Title"]["value"] == "Projects")
    {
        $title = $content["Title"]["value"];
        $description = $content["Description"]["value"];
        break;
    }
}
?>
<!-- Heading image and title container-->
<div class="container header">
    <div class="container middlewrap">
        <h1><?php echo $title ?></h1>
    </div>
</div>
<!-- info container-->
<div class="container info">
    <div class="container infowrap">
        <p><?php echo $description ?></p>
    </div>
</div>

<!-- all projects container-->
<div class="container column projectcard">
    <div id="all-header" class="container cardheader-wrap">
        <div class="container header-search">
            <div class="container project-search">
                <form action="submit">
                    <label for="searchbar" class="sr-only">searchbar</label>
                    <input id="searchbar" class="search-field" type="text" name="searchbar" placeholder="Find a Project By Title or Keyword"/>
                    <button class="search-icon-2" type="submit"><img src="<?php echo BASE_URL;?>/assets/images/Search-dark.svg" alt="search-icon"></button>
                </form>
            </div>
            <div class="container sort-stories">
                <span class="sort-stories-text">Sort Projects By <img class="sort-arrow" src="<?php echo BASE_URL?>assets/images/Arrow2.svg" alt="sort stories button"></span>
                <ul id="submenu" class="sorting-menu">
                    <li>Alphabetical (A-Z)</li>
                    <li>Alphabetical (Z-A)</li>
                    <li>Newest to Oldest</li>
                    <li>Oldest to Newest</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container cardwrap">
        <ul class="row">
            <!-- <li>
                <a href="<?php echo BASE_URL?>fullProject/">
                    <div class="container cards">
                        <h2 class="card-title">Title of Project Goes Here Like This</h2>
                        <div class="connections">
                            <div class="card-icon">
                                <img src="<?php echo BASE_IMAGE_URL?>Person-light.svg" alt="Card Icon"/>
                                <span>10</span>
                            </div>
                            <div class="card-icon">
                                <img src="<?php echo BASE_IMAGE_URL?>Place-light.svg" alt="Card Icon"/>
                                <span>10</span>
                            </div>
                            <div class="card-icon">
                                <img src="<?php echo BASE_IMAGE_URL?>Event-light.svg" alt="Card Icon"/>
                                <span>10</span>
                            </div>
                        </div>
                        <h3 class="card-view-story">View Project <div class="view-arrow"></div></h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>fullProject">
                    <div class="container cards">
                        <h2 class="card-title">Title of Project Goes Here Like This</h2>
                        <div class="connections">
                            <div class="card-icon">
                                <img src="<?php echo BASE_IMAGE_URL?>Place-light.svg" alt="Card Icon"/>
                                <span>10</span>
                            </div>
                            <div class="card-icon">
                                <img src="<?php echo BASE_IMAGE_URL?>Event-light.svg" alt="Card Icon"/>
                                <span>10</span>
                            </div>
                        </div>
                        <h3 class="card-view-story">View Project <div class="view-arrow"></div></h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>fullProject">
                    <div class="container cards">
                        <h2 class="card-title">Title of Project Goes Here Like This</h2>
                        <div class="connections">
                            <div class="card-icon">
                                <img src="<?php echo BASE_IMAGE_URL?>Person-light.svg" alt="Card Icon"/>
                                <span>10</span>
                            </div>
                        </div>
                        <h3 class="card-view-story">View Project <div class="view-arrow"></div></h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>fullProject">
                    <div class="container cards">
                        <h2 class="card-title">Title of Project Goes Here Like This</h2>
                        <div class="connections">
                            <div class="card-icon">
                                <img src="<?php echo BASE_IMAGE_URL?>Place-light.svg" alt="Card Icon"/>
                                <span>10</span>
                            </div>
                        </div>
                        <h3 class="card-view-story">View Project <div class="view-arrow"></div></h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>fullProject">
                    <div class="container cards">
                        <h2 class="card-title">Title of Project Goes Here Like This</h2>
                        <div class="connections">
                            <div class="card-icon">
                                <img src="<?php echo BASE_IMAGE_URL?>Event-light.svg" alt="Card Icon"/>
                                <span>10</span>
                            </div>
                        </div>
                        <h3 class="card-view-story">View Project <div class="view-arrow"></div></h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>fullProject">
                    <div class="container cards">
                        <h2 class="card-title">Title of Project Goes Here Like This</h2>
                        <div class="connections">
                            <div class="card-icon">
                                <img src="<?php echo BASE_IMAGE_URL?>Person-light.svg" alt="Card Icon"/>
                                <span>10</span>
                            </div>
                            <div class="card-icon">
                                <img src="<?php echo BASE_IMAGE_URL?>Place-light.svg" alt="Card Icon"/>
                                <span>10</span>
                            </div>
                        </div>
                        <h3 class="card-view-story">View Project <div class="view-arrow"></div></h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>fullProject">
                    <div class="container cards">
                        <h2 class="card-title">Title of Project Goes Here Like This</h2>
                        <div class="connections">
                            <div class="card-icon">
                                <img src="<?php echo BASE_IMAGE_URL?>Person-light.svg" alt="Card Icon"/>
                                <span>10</span>
                            </div>
                            <div class="card-icon">
                                <img src="<?php echo BASE_IMAGE_URL?>Place-light.svg" alt="Card Icon"/>
                                <span>10</span>
                            </div>
                        </div>
                        <h3 class="card-view-story">View Project <div class="view-arrow"></div></h3>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL?>fullProject">
                    <div class="container cards">
                        <h2 class="card-title">Title of Project Goes Here Like This</h2>
                        <div class="connections">
                            <div class="card-icon">
                                <img src="<?php echo BASE_IMAGE_URL?>Person-light.svg" alt="Card Icon"/>
                                <span>10</span>
                            </div>
                            <div class="card-icon">
                                <img src="<?php echo BASE_IMAGE_URL?>Place-light.svg" alt="Card Icon"/>
                                <span>10</span>
                            </div>
                        </div>
                        <h3 class="card-view-story">View Project <div class="view-arrow"></div></h3>
                    </div>
                </a>
            </li> -->
        </ul>
    </div>
</div>

<script src="<?php echo BASE_URL;?>assets/javascripts/stories.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/projects.js"></script>
