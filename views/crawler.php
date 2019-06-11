<div class="container header">
    <div class="container middlewrap">
        <div class="advanced-title">
            <h1>Web Crawler</h1>
        </div>
    </div>
</div>
<div class="crawler">
    <div class="project-tab crawler-tabs">
        <ul>
            <li class="tabbed">Results</li>
            <li>Broken Links</li>
            <li>Seeds</li>
            <hr>
        </ul>
    </div>
    <div class="search-filter">
        <div class="crawler-search">
            <form action="submit">
                <label for="searchbar" class="sr-only">searchbar</label>
                <input id="searchbar" class="search-field" type="text" name="searchbar" placeholder="Find a Story By Title or Keyword"/>
                <button class="search-icon-2" type="submit"><img src="<?php echo BASE_URL;?>/assets/images/Search-dark.svg" alt="search-icon"></button>
            </form>
        </div>
        <div class="sorting-dropdowns">
            <span class="align-center sort-by">Sort By <img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="results per page button">
                <ul id="sortmenu" class="sort-by">
                    <li>A - Z</li>
                    <li>Z - A</li>
                </ul>
            </span>
            <span class="align-center results-per-page"><span>9</span> Per Page <img src="<?php echo BASE_URL;?>assets/images/Arrow-dark.svg" alt="results per page button">
                <ul id="sortmenu" class="results-per-page">
                    <li><span>12</span> Per Page</li>
                    <li><span>24</span> Per Page</li>
                    <li><span>36</span> Per Page</li>
                    <li><span>48</span> Per Page</li>
                </ul>
            </span>
        </div>
    </div>
    
    <div class="results-wrap">
        <div class="result">
            <div class="link-wrap">
                <div class="link-name">
                    <p>Name of Link Goes Here</p>
                </div>
                <div class="link">
                    <p><a href="google.com">www.nameoflinkgoeshere.com</a></p>
                </div>
            </div>
            <div class="trash">

            </div>
        </div>
        <div class="result">
            <div class="link-wrap">
                <div class="link-name">
                    <p>Name of Link Goes Here</p>
                </div>
                <div class="link">
                    <p><a href="google.com">www.nameoflinkgoeshere.com</a></p>
                </div>
            </div>
            <div class="trash">

            </div>
        </div>
    </div>
</div>
<script src="<?php echo BASE_URL;?>assets/javascripts/crawler.js"></script>