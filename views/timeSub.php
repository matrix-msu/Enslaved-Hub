<?php
if (isset($_GET['prev'])){
    $prev = $_GET['prev'];
} else {
    //Default to people page
    $prev = 'People';
}
?>
<!-- Page author: Drew Schineller-->
<!-- Heading image and title container-->
<div class="container header stories">
    <div class="container middlewrap">
        <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>explore/<?php echo EXPLORE_FORM;?>"><span id="previous-title"><?php echo ucwords(EXPLORE_FORM);?> // </span></a><span id="current-title">Date</span></h4>
        <h1>Date</h1>
    </div>
</div>
<!-- info container-->
<div class="container info timeinfo">
    <div class="container infowrap">
        <p>
            Date Range
        </p>
    </div>
</div>
<!-- Time Select -->
<main class="direct-search timesub">
    <div class="searchwrap">
        <form action="<?php echo BASE_URL;?>search/events" onsubmit="handleSubmit()">
            <div class="search-section">
                <div class="inputwrap">
                    <label for="startYear">Start Year</label>
                    <input type="text" name="startyear" maxlength="4" id="startyear" pattern="\d{4}" required/>
                </div>
                <div class="inputwrap">
                    <label for="endYear">End Year</label>
                    <input type="text" name="endyear" maxlength="4" id="endyear" pattern="\d{4}" required/>
                </div>
                <input class="event-date-range" type="hidden" name="date" value=""/>
            </div>


            <div class="buttonwrap">
                <button id="direct-submit" type="submit" data-submit="...Sending">Search</button>
            </div>
        </form>
    </div>
</main>
<script src="<?php echo BASE_URL;?>assets/javascripts/search.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/explore.js"></script>
