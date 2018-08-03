<!-- Author: Drew Schineller-->
<?php include 'header.php';?>
<!-- Main page-->
<!-- Heading image and title container-->
<div class="container header">
    <div class="container middlewrap">
    <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>search"><span id="previous-title">Search // </span><span id="current-title">Advanced Search</span></a></h4>
        <div class="advanced-title">
            <h1>Advanced Search</h1>
        </div>
    </div>
</div>
<main class="direct-search">
    <div class="searchwrap">
        <form>
            <!-- PERSON -->
            <h2>Person</h2>
            <div class="search-section">
                <div class="inputwrap">
                    <label for="person">Name</label>
                    <input id="person" name="person" type="text" placeholder="Enter Name"/>
                </div>
                <div class="inputwrap">
                    <label for="status">Person Status</label>
                    <select id="status" onchange="document.getElementById('status').style.color = '#253449'"  style="color: #72818C;">
                        <option value"" default selected>Select Status</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                </div>
                <div class="inputwrap">
                    <label for="origin">Origin</label>
                    <select id="origin" onchange="document.getElementById('origin').style.color = '#253449'"  style="color: #72818C;">
                        <option value"" default selected>Select Origin</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                </div>
                <div class="inputwrap">
                    <label for="sex">Sex</label>
                    <select id="sex" onchange="document.getElementById('sex').style.color = '#253449'"  style="color: #72818C;">
                        <option value"" default selected>Select Sex</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                </div>
                <div class="inputwrap">
                    <label for="age">Age</label>
                    <input id="age" name="age" type="text" placeholder="Enter Numerical Age"/>
                </div>
                <div class="inputwrap">
                    <label for="color">Color</label>
                    <input id="color" name="color" type="text" placeholder="Enter Color"/>
                </div>
                <div class="inputwrap">
                    <label for="occupation">Occupation</label>
                    <select id="occupation" onchange="document.getElementById('occupation').style.color = '#253449'"  style="color: #72818C;">
                        <option value"" default selected>Select Occupation</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                </div>
                <div class="inputwrap">
                    <label for="person-from">Date Range</label>
                    <select id="person-from" class="date-select" onchange="document.getElementById('person-from').style.color = '#253449'"  style="color: #72818C;">
                        <option value"" default selected>From</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                    <select id="person-to" class="date-select" onchange="document.getElementById('person-to').style.color = '#253449'"  style="color: #72818C;">
                        <option value"" default selected>To</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                </div>
            </div>
            <!-- EVENT -->
            <h2>Event</h2>
            <div class="search-section">
                <div class="inputwrap">
                    <label for="event">Event Name</label>
                    <input id="event" name="event" type="text" placeholder="Enter Event Name"/>
                </div>
                <div class="inputwrap">
                    <label for="type">Type</label>
                    <select id="type" onchange="document.getElementById('type').style.color = '#253449'"  style="color: #72818C;">
                        <option value"" default selected>Select Event Type</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                </div>
                <div class="inputwrap">
                    <label for="event-from">Date Range</label>
                    <select id="event-from" class="date-select" onchange="document.getElementById('event-from').style.color = '#253449'"  style="color: #72818C;">
                        <option value"" default selected>From</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                    <select id="event-to" class="date-select" onchange="document.getElementById('event-to').style.color = '#253449'"  style="color: #72818C;">
                        <option value"" default selected>To</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                </div>
            </div>
            <!-- PLACE -->
            <h2>Place</h2>
            <div class="search-section">
                <div class="inputwrap">
                    <label for="place">Name</label>
                    <input id="place" name="place" type="text" placeholder="Enter Place Name"/>
                </div>
                <div class="inputwrap">
                    <label for="city">City</label>
                    <select id="city" onchange="document.getElementById('city').style.color = '#253449'"  style="color: #72818C;">
                        <option value"" default selected>Select City</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                </div>
                <div class="inputwrap">
                    <label for="state">Province, State, Colony</label>
                    <select id="state" onchange="document.getElementById('state').style.color = '#253449'"  style="color: #72818C;">
                        <option value"" default selected>Select Province, State, Colony</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                </div>
                <div class="inputwrap">
                    <label for="region">Enslaved Region</label>
                    <select id="region" onchange="document.getElementById('region').style.color = '#253449'"  style="color: #72818C;">
                        <option value"" default selected>Select Region</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                </div>
                <div class="inputwrap">
                    <label for="country">Country</label>
                    <select id="country" onchange="document.getElementById('country').style.color = '#253449'"  style="color: #72818C;">
                        <option value"" default selected>Select Country</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                </div>
                <div class="inputwrap">
                    <label for="place-from">Date Range</label>
                    <select id="place-from" class="date-select" onchange="document.getElementById('place-from').style.color = '#253449'"  style="color: #72818C;">
                        <option value"" default selected>From</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                    <select id="place-to" class="date-select" onchange="document.getElementById('place-to').style.color = '#253449'"  style="color: #72818C;">
                        <option value"" default selected>To</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unidentified">Unidentified</option>
                    </select>
                </div>
            </div>

            <div class="buttonwrap">
                <button id="direct-submit" name="submit" type="submit" data-submit="...Sending">Search</button>
            </div>
        </form>
    </div>
</main>
<?php include 'footer.php';?>