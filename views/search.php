<!-- Author: Drew Schineller-->
<!-- Heading image and title container-->
<div class="container header">
    <div class="image-container search-page">
	    <div class="container middlewrap search-page">
        <div class="search-title">
            <h1 class="no-shadow">Search</h1>
        </div>
        <div class="heading-search">
            <p>Start a search across <?php echo counterofAllitems();?> records from the Atlantic Slave Trade <a class="text-link show-desktop-only" href="">Go to Advanced Search</a></p>
            <p class="hide-desktop-only mt-xs"><a class="text-link" href="<?php echo BASE_URL;?>advancedSearch">Go to Advanced Search</a></p>
            <form class="search-form" action="<?= BASE_URL ?>search/all">
                <label for="searchbar" class="sr-only">searchbar</label>
                <input id="searchbar" class="search-field main-search" type="text" name="searchbar" placeholder="eg: People, Places, Events, Sources, Projects, Captains, Ships, Voyages, etc."/>
                <button class="search-icon-2" type="submit"><img src="<?php echo BASE_URL;?>/assets/images/Search.svg" alt="search-icon"></button>
                <!-- <img class="search-close" src="<?php echo BASE_URL;?>/assets/images/Close.svg"/> -->
            </form>
        </div>
    </div>
      <div class="image-background-overlay  search-page"></div>
      <img class="header-background search-page" src="<?php echo BASE_URL;?>assets/images/enslaved-header-bg3.jpg" alt="Enslaved Background Image"></div>
</div>
<main class="direct-search">
    <div class="searchwrap">
        <form  action="<?php echo BASE_URL;?>search/people" method="get" onsubmit="handleSubmit()">
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
                        <?php foreach (eventTypes as $type => $qid) { ?>
                            <option value="<?php echo $qid;//strtolower(str_replace(" ", "_", $type)); ?>"><?php echo $type; ?></option>
                        <?php } ?>
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
