<!-- Author: Amy Kim-->
<!-- Project & Scholar Directory page-->
<?php $cache_data = Json_GetData_ByTitle("Project & Scholar Directory") ?>
<!-- Heading image and title container-->
<div class="container header about-header directory-page">
     <div class="container middlewrap">
        <h4 class="last-page-header">
            <a id="last-page" href="<?php echo BASE_URL;?>about">
              <span class="previous-title">About / </span></a>
            <a id="last-page" href="<?php echo BASE_URL;?>getInvolved">
              <span class="previous-title">Get Involved / </span></a>
            <span id="current-title"><?php echo $cache_data['title'] ?></span>
        </h4>
        <h1><?php echo $cache_data['title'] ?></h1>
    </div>
</div>

<div class="container directory-textwrap">
    <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
    </p>
</div>

<div class="container card-column projectcard">
    <div id="all-header" class="container cardheader-wrap">
        <div class="container display-projects">
            <h2 class="column-header">Projects & Scholars<img class="sort-arrow" src="<?php echo BASE_URL?>assets/images/directory_chevron.svg" alt="sort projects button"></h2>
            <ul id="submenu" class="display-menu">
                <li class="display-option" id="both" >Projects & Scholars</li>
                <li class="display-option" id="project">Projects</li>
                <li class="display-option" id="scholar">Scholars</li>
            </ul>
        </div>
        <div class="sort-search">
            <div class="container sort-projects">
                <span class="sort-projects-text">Sort By<img class="sort-arrow" src="<?php echo BASE_URL?>assets/images/chevron.svg" alt="sort projects button"></span>
                <ul id="submenu" class="sorting-menu">
                    <li class="sort-option" data-field="title" data-direction="asc">Alphabetically by Name (A-Z)</li>
                    <li class="sort-option" data-field="title" data-direction="desc">Alphabetically by Name (Z-A)</li>
                    <li class="sort-option" data-field="start date" data-direction="desc">Date Added (Most Recent)</li>
                    <li class="sort-option" data-field="start date" data-direction="asc">Date Added (Least Recent)</li>
                </ul>
            </div>
            <div class="container search">
                <form action="submit">
                    <label for="searchbar" class="sr-only">searchbar</label>
                    <input id="searchbar" class="search-field" type="text" name="searchbar" placeholder="Search By name or keyword"/>
                    <button class="search-icon-2" type="submit"><img src="<?php echo BASE_URL;?>/assets/images/Search-dark.svg" alt="search-icon"></button>
                </form>
            </div>
        </div>
    </div>
    <div class="container card-wrap">
        <div class="card directory project">
            <p class="classifier">Project</p>
            <h2 class="title">Project Name</h2>
            <div class="row one">
                <div class="detail one">
                    <p class="title">Developer(s)</p>
                    <p class="content">Developer Name, Developer Name</p>
                </div>
                <div class="detail two">
                    <p class="title">Affiliation</p>
                    <p class="content">Affiliation Title</p>
                </div>
                <div class="detail three">
                    <p class="title">Location</p>
                    <p class="content">Location, State</p>
                </div>
                <div class="detail four">
                    <p class="title">URL</p>
                    <p class="content">websitename.com</p>
                </div>
            </div>
            <div class="row two">
                <div class="detail five">
                    <p class="title">Notes</p>
                    <p class="content">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
                </div>
            </div>
        </div>
        <div class="card directory scholar">
            <p class="classifier">Scholar</p>
            <h2 class="title">Firstname Lastname</h2>
            <div class="row one">
                <div class="detail one">
                    <p class="title">Affiliation</p>
                    <p class="content">Affiliation Title</p>
                </div>
                <div class="detail two">
                    <p class="title">E-mail</p>
                    <p class="content">email@email.com</p>
                </div>
                <div class="detail three">
                    <p class="title">Personal Website</p>
                    <p class="content">websitename.com</p>
                </div>
            </div>
            <div class="row two">
                <div class="detail four">
                    <p class="title">Research and Specialization</p>
                    <p class="content">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card directory project">
        <p class="classifier">Project</p>
        <h2 class="title">Project Name</h2>
        <div class="row one">
            <div class="detail one">
                <p class="title">Developer(s)</p>
                <p class="content">Developer Name, Developer Name</p>
            </div>
            <div class="detail two">
                <p class="title">Affiliation</p>
                <p class="content">Affiliation Title</p>
            </div>
            <div class="detail three">
                <p class="title">Location</p>
                <p class="content">Location, State</p>
            </div>
            <div class="detail four">
                <p class="title">URL</p>
                <p class="content">websitename.com</p>
            </div>
        </div>
        <div class="row two">
            <div class="detail five">
                <p class="title">Notes</p>
                <p class="content">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
            </div>
        </div>
    </div>
    <div class="card directory scholar">
        <p class="classifier">Scholar</p>
        <h2 class="title">Firstname Lastname</h2>
        <div class="row one">
            <div class="detail one">
                <p class="title">Affiliation</p>
                <p class="content">Affiliation Title</p>
            </div>
            <div class="detail two">
                <p class="title">E-mail</p>
                <p class="content">email@email.com</p>
            </div>
            <div class="detail three">
                <p class="title">Personal Website</p>
                <p class="content">websitename.com</p>
            </div>
        </div>
        <div class="row two">
            <div class="detail four">
                <p class="title">Research and Specialization</p>
                <p class="content">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
            </div>
        </div>
    </div>
    <div class="card directory project">
        <p class="classifier">Project</p>
        <h2 class="title">Project Name</h2>
        <div class="row one">
            <div class="detail one">
                <p class="title">Developer(s)</p>
                <p class="content">Developer Name, Developer Name</p>
            </div>
            <div class="detail two">
                <p class="title">Affiliation</p>
                <p class="content">Affiliation Title</p>
            </div>
            <div class="detail three">
                <p class="title">Location</p>
                <p class="content">Location, State</p>
            </div>
            <div class="detail four">
                <p class="title">URL</p>
                <p class="content">websitename.com</p>
            </div>
        </div>
        <div class="row two">
            <div class="detail five">
                <p class="title">Notes</p>
                <p class="content">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
            </div>
        </div>
    </div>
    <div class="card directory scholar">
        <p class="classifier">Scholar</p>
        <h2 class="title">Firstname Lastname</h2>
        <div class="row one">
            <div class="detail one">
                <p class="title">Affiliation</p>
                <p class="content">Affiliation Title</p>
            </div>
            <div class="detail two">
                <p class="title">E-mail</p>
                <p class="content">email@email.com</p>
            </div>
            <div class="detail three">
                <p class="title">Personal Website</p>
                <p class="content">websitename.com</p>
            </div>
        </div>
        <div class="row two">
            <div class="detail four">
                <p class="title">Research and Specialization</p>
                <p class="content">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
            </div>
        </div>
    </div>
    <div class="container pagiwrap">
        <div class="sort-pages">
            <p><span class="per-page-text">24</span> Per Page <img class="sort-arrow" src="<?php echo BASE_URL?>assets/images/chevron.svg" alt="sort projects button"/></p>
            <ul id="submenu" class="pagenum-menu">
                <li class="count-option" data-count="6"><span>6</span> Per Page</li>
                <li class="count-option" data-count="12"><span>12</span> Per Page</li>
                <li class="count-option" data-count="18"><span>18</span> Per Page</li>
                <li class="count-option" data-count="24"><span>24</span> Per Page</li>
            </ul>
        </div>

        <div class="pagination-container">
            <div class="pagination-prev btn-prev no-select">
                <img class="chevron" src="<?php echo BASE_URL;?>assets/images/chevron.svg" alt="Previous Featured Biography">
            </div>

            <ul class="page-select">
                <li class="active">1</li>
                <li data-page="2">2</li>
                <li data-page="3">3</li>
                <li data-page="4">4</li>
                <li data-page="5">5</li>
                <li>...</li>
                <li data-page="310">310</li>
            </ul>

            <div class="pagination-next btn-next no-select">
                <img class="chevron" src="<?php echo BASE_URL;?>assets/images/chevron-light.svg" alt="Next Featured Biography">
            </div>
        </div>
    </div>
</div>


<script src="<?php echo BASE_URL;?>assets/javascripts/directory.js"></script>
