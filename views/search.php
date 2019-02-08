<!-- Author: Drew Schineller-->
<!-- Heading image and title container-->
<div class="container header">
    <div class="container middlewrap">
        <div class="search-title">
            <h1>Search</h1>
            <a href="<?php echo BASE_URL;?>advancedSearch/"><h3>Go To Advanced Search<div class="arrow"></div></h3></a>
        </div>
        <div class="heading-search">
            <h3>Search across 54,375,213 records from the Atlantic Slave Trade ...</h3>
            <form class="search-form"  action="<?php echo BASE_URL;?>searchResults/">
                <input class="search-field main-search" type="text" placeholder="eg: People, Places, Events, Sources, Projects, Captains, Ships, Voyages, etc."/>
                <a href="<?php echo BASE_URL;?>searchResults/"><div class="search-icon"></div></a>
                <!-- <img class="search-close" src="<?php echo BASE_URL;?>/assets/images/Close.svg"/> -->
            </form>
        </div>
    </div>
</div>
<main class="direct-search">
    <div class="searchwrap">
        <form  action="<?php echo BASE_URL;?>searchResults/">
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
                    <input class="input-field" id="year" name="year" type="text" placeholder="Enter Year"/>
                </div>
                <div class="inputwrap">
                    <label for="event">Life Event</label>
                    <input class="input-field" id="event" name="event" type="text" placeholder="Enter Life Event"/>
                </div>
            </div>
            <div class="buttonwrap">
                <button id="direct-submit" name="submit" type="submit" data-submit="...Sending">Search</button>
            </div>
        </form>
    </div>
</main>