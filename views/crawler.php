<?php
    /**
     * Template Name: Web Crawler
     */

    // get_header();
    // $isCrawlerAdmin=isCrawlerAdmin($_SESSION['sess_username']);
	$isCrawlerAdmin = true;
    // $user_arrays=perm_roles();
    // $i = 0;
	// $roleIndex = 1;
	// $permissionIndex = 1;
    // $roles=getRoles();
    // $permissions=getPermissions();
    require (BASE_PATH . "webcrawler/config.php");
	require (BASE_PATH . "webcrawler/models/crawler_keywords.php");
    require (BASE_PATH . "webcrawler/models/crawler_deleted_keywords.php");
?>

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
            <li class="tabbed" id="results">Results</li>
            <li id="broken">Broken Links</li>
            <li id="seeds">Seeds</li>
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
    <?php if($isCrawlerAdmin) {	 ?>
        <?php  // hidden field to store seeds value
        $crawler_keywords =new crawler_keywords();
        if(isset($_GET['seeds']))
        {
        ?>
            <input type="text" id="seeds_is_set" value="seeds" style="display:none">
        <?php
        }
        ?>
    
    <div class="results-wrap result-container show" id="results">
        <?php
		//********** results are displayed here ***********//
		// connect to keywords data base and get the first list of keywords
		$limit=20;

        //need to add crawler keywords variable
		$results=$crawler_keywords->get_keywords($limit,0);
		if($results!="no more data")
		echo($results);

		?>
        <div class="result">
            <div class="link-name">
                <p>Name of Link Goes Here</p>
            </div>
            <div class="link-wrap">
                <a class="link" href="google.com">www.nameoflinkgoeshere.com</a>
                <div class="trash">
                    <img class="trash-icon" src="<?php echo BASE_IMAGE_URL;?>Delete.svg">
                </div>
            </div>
        </div>
        <div class="result">
            <div class="link-name">
                <p>Name of Link Goes Here</p>
            </div>
            <div class="link-wrap">
                <a class="link" href="google.com">www.nameoflinkgoeshere.com</a>
                <div class="trash">
                    <img class="trash-icon" src="<?php echo BASE_IMAGE_URL;?>Delete.svg">
                </div>
            </div>
        </div>
        <div class="result">
            <div class="link-name">
                <p>Name of Link Goes Here</p>
            </div>
            <div class="link-wrap">
                <a class="link" href="google.com">www.nameoflinkgoeshere.com</a>
                <div class="trash">
                    <img class="trash-icon" src="<?php echo BASE_IMAGE_URL;?>Delete.svg">
                </div>
            </div>
        </div>
    </div>
    <div class="broken-wrap result-container" id="broken">
        <p class="link-info">The following (12) links are broken. They can be updated or deleted entirely.</p>
        <div class="result">
            <div class="link-wrap">
                <a class="link" href="google.com">www.nameoflinkgoeshere.com</a>
                <div class="right">
                    <div class="trash">
                        <img class="trash-icon" src="<?php echo BASE_IMAGE_URL;?>Delete.svg">
                    </div>
                    <div class="update">
                        <p>Update Link</p>
                    </div>
                </div>
            </div>
            <div class="message">
                <p>No Response from server, check website.</p>
                <p>The server is down or unable to get a response from the server.</p>
            </div>
        </div>
        <div class="result">
            <div class="link-wrap">
                <a class="link" href="google.com">www.nameoflinkgoeshere.com</a>
                <div class="right">
                    <div class="trash">
                        <img class="trash-icon" src="<?php echo BASE_IMAGE_URL;?>Delete.svg">
                    </div>
                    <div class="update">
                        <p>Update Link</p>
                    </div>
                </div>
            </div>
            <div class="message">
                <p>No Response from server, check website.</p>
                <p>The server is down or unable to get a response from the server.</p>
            </div>
        </div>
    </div>
    <div class="seed-wrap result-container" id="seeds">
    <p class="link-info">The following (12) links are broken. They can be updated or deleted entirely.</p>
        <div class="result">
            <div class="link-wrap">
                <p><span>URL:</span><a class="link" href="google.com">www.nameoflinkgoeshere.com</a></p>
                <div class="right">
                    <div class="trash">
                        <img class="trash-icon" src="<?php echo BASE_IMAGE_URL;?>Delete.svg">
                    </div>
                    <div class="update">
                        <p>Update Seed</p>
                    </div>
                </div>
            </div>
            <div class="details">
                <div class="row">
                    <div class="cell">
                        <p><span>NAME:</span>Pea Soup</p>
                    </div>
                    <div class="cell">
                        <p><span>TITLE:</span>Pea Soup</p>
                    </div>
                </div>
                <div class="row">
                    <div class="cell">
                        <p><span>TWITTER:</span><a href="">@peasoup</a></p>
                    </div>
                    <div class="cell">
                        <p><span>RSS:</span><a href="">www.nameoflinkgoeshere.com</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } else { ?>
		<p><strong id="permissions_inv">Whoops, this is embarassing...<br>
			You don't have the permissions to view this page, sorry!</strong></p>
    <?php } ?>


    <div id="pagination">
        <input class="current-page" type="hidden" value="1">
        <span id="pagiLeft" class="align-left"><div id="pagiLeftArrow"></div></span>
        <div class="page-numbers">
            <span class="num pagi-first">1</span>
            <span class="dotsLeft">...</span>
            <span class="num one"></span>
            <span class="num two"></span>
            <span class="num three"></span>
            <span class="num four"></span>
            <span class="num five"></span>
            <span class="dotsRight">...</span>
            <span class="num pagi-last">310</span>
        </div>
        <span id="pagiRight" class="align-right"><div id="pagiRightArrow"></div></span>
    </div>
</div>
<script src="<?php echo BASE_URL;?>assets/javascripts/crawler.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/pagination.js"></script>

<?php if($isCrawlerAdmin) { ?>
    <script src="<?php echo BASE_URL;?>webcrawler/js/webcrawler.js"></script>
    <script src="<?php echo BASE_URL;?>webcrawler/js/crawler_results.js"></script>
	<script src="<?php echo BASE_URL;?>webcrawler/js/crawler_seeds.js"></script>
	<script src="<?php echo BASE_URL;?>webcrawler/js/crawler_broken_links.js"></script>
<?php } ?>