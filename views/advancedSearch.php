<!-- Author: Drew Schineller-->
<!-- Main page-->
<!-- Heading image and title container-->
<div class="container header">
    <div class="image-container search-page image-only">
	  <div class="container middlewrap">
      <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>search"><span id="previous-title">Search / </span></a><span id="current-title">Advanced Search</span></h4>
          <div class="search-title">
              <h1>Advanced Search</h1>
          </div>
    </div>
    <div class="image-background-overlay"></div>
    <img class="header-background advancedSearch-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">
      <!-- <img class="header-background full-height search-page" src="<?php echo BASE_URL;?>assets/images/enslaved-header-bg2.jpg" alt="Enslaved Background Image"> -->

    </div>
</div>
<main class="direct-search">
    <div class="searchwrap">
        <form action="<?php echo BASE_URL;?>search/all" method="get" onsubmit="handleSubmit()" autocomplete="off">
            <!-- PERSON -->
            <h2>Person</h2>
            <div class="search-section">
                <div class="inputwrap">
                    <label for="person">Name</label>
                    <input class="input-field" id="person" name="name" type="text" placeholder="Enter Name"/>
                </div>
                <div class="inputwrap">
                    <label for="status">Person Status</label>
                    <select class="s2-single" id="status" name="status">
                        <option value=""></option>
                        <?php foreach (personstatus as $type => $qid) { ?>
                            <option value="<?php echo urlencode($type); ?>"><?php echo $type; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="inputwrap">
                    <label for="gender">Sex</label>
                    <select class="s2-single" name="gender" id="sex">
                        <option value=""></option>
                        <?php foreach (sexTypes as $type => $qid) { ?>
                            <option value="<?php echo urlencode($type); ?>"><?php echo $type; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="inputwrap agewrap">
                    <label for="age-from">Age Range</label>
                    <input class="input-field" id="age-from" name="age-from" type="text" placeholder="From"/>
                    <label for="age-to" class="sr-only">text</label>
                    <input class="input-field" id="age-to" name="age-to" type="text" placeholder="To"/>
                    <input class="age-range" type="hidden" name="age" value=""/>
                </div>
                <div class="inputwrap">
                    <label for="ethno">Ethnodescriptor</label>
                    <input class="input-field" id="ethno" name="ethnodescriptor" type="text" placeholder="Enter Ethnodescriptor"/>
                </div>
                <div class="inputwrap">
                    <label for="occupation">Occupation</label>
                    <select class="s2-multiple" id="occupation" name="occupation" multiple="multiple">
                        <?php foreach (occupation as $type => $qid) { ?>
                            <option value="<?php echo urlencode($type); ?>"><?php echo $type; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <!-- EVENT -->
            <h2>Event</h2>
            <div class="search-section">
                <div class="inputwrap">
                    <label for="event_type">Type</label>
                    <select class="s2-multiple" name="event_type" id="event-type" multiple="multiple">
                        <?php foreach (eventTypes as $type => $qid) { ?>
                            <option value="<?php echo urlencode($type); ?>"><?php echo $type; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="inputwrap datewrap">
                    <label for="event-from">Date Range</label>
                    <select class="s2-multiple date-from" id="event-from" name="" multiple="multiple">
                        <option value=""></option>
                    </select>
                    <label for="event-to" class="sr-only">dropdown</label>
                    <select class="s2-multiple date-to" id="event-to" name="" multiple="multiple">
                        <option value=""></option>
                    </select>
                    <input class="event-date-range" type="hidden" name="date" value=""/>
                </div>
            </div>
            <!-- PLACE -->
            <h2>Place</h2>
            <div class="search-section">
                <div class="inputwrap">
                    <label for="place">Place Name</label>
                    <input class="input-field" id="place" name="place_name" type="text" placeholder="Enter Place Name"/>
                </div>
                <div class="inputwrap">
                    <label for="place_type">Place Type</label>
                    <select class="s2-multiple" id="place-type" name="place_type" multiple="multiple">
                        <?php foreach (placeTypes as $type => $qid) { ?>
                            <option value="<?php echo urlencode($type); ?>"><?php echo $type; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="inputwrap">
                    <label for="country_code">Modern Country</label>
                    <select class="s2-multiple" id="country" name="modern_country_code" multiple="multiple">
                        <?php foreach (countrycode as $code => $country) { ?>
                            <option value="<?php echo $country; ?>"><?php echo $country; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <!-- Source -->
            <h2>Source</h2>
            <div class="search-section">
                <!-- <div class="inputwrap">
                    <label for="place">Place Name</label>
                    <input class="input-field" id="place" name="place" type="text" placeholder="Enter Place Name"/>
                </div> -->
                <div class="inputwrap">
                    <label for="source_type">Source Type</label>
                    <select class="s2-multiple" id="source-type" name="source_type" multiple="multiple">
                        <?php foreach (sourceTypes as $type => $qid) { ?>
                            <option value="<?php echo urlencode($type); ?>"><?php echo $type; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="inputwrap">
                    <label for="projects">Projects</label>
                    <select class="s2-multiple" id="project" name="projects" multiple="multiple">
                        <?php foreach (projects as $type => $qid) { ?>
                            <option value="<?php echo urlencode($type); ?>"><?php echo $type; ?></option>
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
<script>
    autocomplete(document.getElementById("place"), [<?php echo '"'.implode('","', array_keys(places)).'"' ?>]);
    autocomplete(document.getElementById("ethno"), [<?php echo '"'.implode('","', array_keys(ethnodescriptor)).'"' ?>]);
    autocomplete(document.getElementById("age"), [<?php echo '"'.implode('","', qages).'"' ?>]);
</script>
