<!-- Author: Drew Schineller-->
<!-- Main page-->
<!-- Heading image and title container-->
<div class="container header">
    <div class="container middlewrap">
    <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>search/"><span id="previous-title">Search // </span></a><span id="current-title">Advanced Search</span></h4>
        <div class="advanced-title">
            <h1>Advanced Search</h1>
        </div>
    </div>
</div>
<main class="direct-search">
    <div class="searchwrap">
        <form action="<?php echo BASE_URL;?>searchResults/">
            <!-- PERSON -->
            <h2>Person</h2>
            <div class="search-section">
                <div class="inputwrap">
                    <label for="person">Name</label>
                    <input class="input-field" id="person" name="person" type="text" placeholder="Enter Name"/>
                </div>
                <div class="inputwrap">
                    <label for="status">Person Status</label>
                    <select class="s2-single" id="status">
                        <option value""></option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                </div>
                <div class="inputwrap">
                    <label for="origin">Origin</label>
                    <select class="s2-multiple" id="origin" multiple="multiple">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                </div>
                <div class="inputwrap">
                    <label for="sex">Sex</label>
                    <select class="s2-single" id="sex">
                        <option value""></option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                </div>
                <div class="inputwrap">
                    <label for="age">Age</label>
                    <input class="input-field" id="age" name="age" type="text" placeholder="Enter Numerical Age"/>
                </div>
                <div class="inputwrap">
                    <label for="color">Color</label>
                    <input class="input-field" id="color" name="color" type="text" placeholder="Enter Color"/>
                </div>
                <div class="inputwrap">
                    <label for="occupation">Occupation</label>
                    <select class="s2-multiple" id="occupation" multiple="multiple">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                </div>
                <div class="inputwrap datewrap">
                    <label for="person-from">Date Range</label>
                    <select class="s2-single date-from" id="person-from">
                        <option value""></option>
                        <option value="1800">1800</option>
                        <option value="1900">1900</option>
                        <option value="2000">2000</option>
                    </select>
                    <label for="person-to" class="sr-only">dropdown</label>
                    <select class="s2-single date-to" id="person-to">
                        <option value""></option>
                        <option value="1800">1800</option>
                        <option value="1900">1900</option>
                        <option value="2000">2000</option>
                    </select>
                </div>
            </div>
            <!-- EVENT -->
            <h2>Event</h2>
            <div class="search-section">
                <div class="inputwrap">
                    <label for="event">Event Name</label>
                    <input class="input-field" id="event" name="event" type="text" placeholder="Enter Event Name"/>
                </div>
                <div class="inputwrap">
                    <label for="type">Type</label>
                    <select class="s2-multiple" name="type" id="type" multiple="multiple">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                </div>
                <div class="inputwrap datewrap">
                    <label for="event-from">Date Range</label>
                    <select class="s2-single date-from" id="event-from">
                        <option value""></option>
                        <option value="1800">1800</option>
                        <option value="1900">1900</option>
                        <option value="2000">2000</option>
                    </select>
                    <label for="event-to" class="sr-only">dropdown</label>
                    <select class="s2-single date-to" id="event-to">
                        <option value""></option>
                        <option value="1800">1800</option>
                        <option value="1900">1900</option>
                        <option value="2000">2000</option>
                    </select>
                </div>
            </div>
            <!-- PLACE -->
            <h2>Place</h2>
            <div class="search-section">
                <div class="inputwrap">
                    <label for="place">Name</label>
                    <input class="input-field" id="place" name="place" type="text" placeholder="Enter Place Name"/>
                </div>
                <div class="inputwrap">
                    <label for="city">City</label>
                    <select class="s2-multiple" id="city" multiple="multiple">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                </div>
                <div class="inputwrap">
                    <label for="state">Province, State, Colony</label>
                    <select class="s2-multiple" id="state" multiple="multiple">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                </div>
                <div class="inputwrap">
                    <label for="region">Enslaved Region</label>
                    <select class="s2-multiple" id="region" multiple="multiple">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                </div>
                <div class="inputwrap">
                    <label for="country">Country</label>
                    <select class="s2-multiple" id="country" multiple="multiple">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                </div>
                <div class="inputwrap datewrap">
                    <label for="place-from">Date Range</label>
                    <select class="s2-single date-from" id="place-from">
                        <option value""></option>
                        <option value="1800">1800</option>
                        <option value="1900">1900</option>
                        <option value="2000">2000</option>
                    </select>
                    <label for="place-to" class="sr-only">dropdown</label>
                    <select class="s2-single date-to" id="place-to">
                        <option value""></option>
                        <option value="1800">1800</option>
                        <option value="1900">1900</option>
                        <option value="2000">2000</option>
                    </select>
                </div>
            </div>

            <div class="buttonwrap">
                <button id="direct-submit" name="submit" type="submit" data-submit="...Sending">Search</button>
            </div>
        </form>
    </div>
</main>
<script src="<?php echo BASE_URL;?>assets/javascripts/search.js"></script>
