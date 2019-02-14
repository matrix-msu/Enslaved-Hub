<!-- Page author: Drew Schineller-->
<!-- Heading image and title container-->
<div class="container header stories">
    <div class="container middlewrap">
        <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>explorePeople/"><span id="previous-title">People // </span></a><span id="current-title">Time</span></h4>
        <h1>Time</h1>
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
        <form action="<?php echo BASE_URL;?>searchResults/">
            <div class="search-section">
                <div class="inputwrap">
                    <label for="status">Start Year</label>
                    <select class="s2-single" id="startYear">
                        <option value""></option>
                        <option value="1840">1840</option>
                        <option value="1841">1841</option>
                        <option value="1842">1842</option>
                        <option value="1840">1843</option>
                        <option value="1841">1844</option>
                        <option value="1842">1845</option>
                    </select>
                </div>
                <div class="inputwrap">
                    <label for="status">End Year</label>
                    <select class="s2-single" id="endYear">
                        <option value""></option>
                        <option value="1840">1840</option>
                        <option value="1841">1841</option>
                        <option value="1842">1842</option>
                        <option value="1840">1843</option>
                        <option value="1841">1844</option>
                        <option value="1842">1845</option>
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