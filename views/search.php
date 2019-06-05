<!-- Author: Drew Schineller-->
<!-- Heading image and title container-->
<div class="container header">
    <div class="container middlewrap">
        <div class="search-title">
            <h1>Search</h1>
            <a href="<?php echo BASE_URL;?>advancedSearch"><h2>Go To Advanced Search<div class="arrow"></div></h2></a>
        </div>
        <div class="heading-search">
            <h3>Search across 54,375,213 records from the Atlantic Slave Trade ...</h3>
            <form class="search-form"  action="<?php echo BASE_URL;?>search/all">
                <label for="searchbar" class="sr-only">searchbar</label>
                <input id="searchbar" class="search-field main-search" type="text" name="searchbar" placeholder="eg: People, Places, Events, Sources, Projects, Captains, Ships, Voyages, etc."/>
                <button class="search-icon-2" type="submit"><img src="<?php echo BASE_URL;?>/assets/images/Search.svg" alt="search-icon"></button>
                <!-- <img class="search-close" src="<?php echo BASE_URL;?>/assets/images/Close.svg"/> -->
            </form>
        </div>
    </div>
</div>
<main class="direct-search">
    <div class="searchwrap">
        <form  action="<?php echo BASE_URL;?>search/all" method="get" onsubmit="removeEmpty()">
            <h2>Direct Search</h2>
            <div class="search-section">
                <div class="inputwrap">
                    <label for="person">Person Name</label>
                    <input class="input-field" id="person" name="person" type="text" placeholder="Enter Person Name"/>
                </div>
                <div class="inputwrap">
                    <label for="place">Place Name</label>
                    <input class="input-field" id="place" name="place" type="text" placeholder="Enter Place Name"/>
                </div>
                <div class="inputwrap">
                    <label for="year">Year</label>
                    <input class="input-field" id="year" name="year" type="text" placeholder="Enter Year" onkeypress='validate(event)' maxlength="4"/>
                </div>
                <div class="inputwrap">
                    <label for="life-event">Life Event</label>
                    <select class="s2-multiple" name="event" id="life-event" multiple="multiple">
                        <option value=""></option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                </div>
            </div>
            <div class="buttonwrap">
                <button id="direct-submit" type="submit" data-submit="...Sending">Search</button>
            </div>
        </form>
    </div>
</main>
<script src="<?php echo BASE_URL;?>assets/javascripts/search.js"></script>
