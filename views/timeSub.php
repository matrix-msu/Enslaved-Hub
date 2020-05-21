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
  <div class="image-container timeSub-page image-only">
    <img class="header-background timeSub-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">
      <div class="container middlewrap">
          <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>explore/<?php echo EXPLORE_FORM;?>"><span id="previous-title"><?php echo ucwords(EXPLORE_FORM);?> // </span></a><span id="current-title">Date</span></h4>
          <h1>Date</h1>
      </div>
      <div class="image-background-overlay"></div>
    </div>
</div>
<!-- info container-->
<div class="container info timeinfo">
    <div class="container infowrap">
        <p>
            Use the dropdowns to create a date range.
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
                    <!-- <select class="s2-single date-from" id="event-from" name=""> -->
                        <!-- <option value""></option> -->
                        <!-- <option value="1840">1840</option>
                        <option value="1841">1841</option>
                        <option value="1842">1842</option>
                        <option value="1840">1843</option>
                        <option value="1841">1844</option>
                        <option value="1842">1845</option> -->
                    <!-- </select> -->
                    <select class="s2-single" id="event-from" name="" multiple="multiple">
                    </select>
                </div>
                <div class="inputwrap">
                    <label for="endYear">End Year</label>
                    <!-- <select class="s2-single date-to" id="event-to" name=""> -->
                        <!-- <option value""></option> -->
                        <!-- <option value="1840">1840</option>
                        <option value="1841">1841</option>
                        <option value="1842">1842</option>
                        <option value="1840">1843</option>
                        <option value="1841">1844</option>
                        <option value="1842">1845</option> -->
                    <!-- </select> -->
                    <select class="s2-single" id="event-to" name="" multiple="multiple">
                    </select>
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
