<?php
    $isCrawlerAdmin = true;
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
                <input id="searchbar" class="search-field" type="text" name="searchbar" placeholder="Find a URL"/>
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
    
    <div class="results-wrap result-container show" id="results">
        <div class="result" id="keep">
            <div class="link-name">
                <a class="link" href="http://www.google.com" target="_blank">Name of Link Goes Here</a>
            </div>
            <div class="link-wrap">
                <a class="link" href="http://www.google.com" target="_blank">www.nameoflinkgoeshere.com</a>
                <div class="trash crawler-modal-open" id="delete-link">
                    <img class="trash-icon" src="<?php echo BASE_IMAGE_URL;?>Delete.svg">
                </div>
            </div>
        </div>
    </div>

    <div class="broken-wrap result-container" id="broken">
        <p class="link-info">The following (12) links are broken. They can be updated or deleted entirely.</p>
        <div class="result" id="keep">
            <div class="link-wrap">
                <a class="link" href="http://www.google.com" target="_blank">www.nameoflinkgoeshere.com</a>
                <div class="right">
                    <div class="trash crawler-modal-open" id="delete-link">
                        <img class="trash-icon" src="<?php echo BASE_IMAGE_URL;?>Delete.svg">
                    </div>
                    <div class="update crawler-modal-open" id="update-link">
                        <p>Update Link</p>
                    </div>
                </div>
            </div>
            <div class="message">
                <p>No Response from server, <a href="http://www.google.com" target="_blank">check website.</a></p>
                <p>The server is down or unable to get a response from the server.</p>
            </div>
        </div>
    </div>

    <div class="seed-wrap result-container" id="seeds">
        <p class="link-info">There are (12) Seeds. They can be updated or deleted entirely.</p>
        <div class="result" id="keep">
            <div class="link-wrap">
                <p><span>URL:</span><a class="link" href="http://www.google.com" target="_blank">www.nameoflinkgoeshere.com</a></p>
                <div class="right">
                    <div class="trash crawler-modal-open" id="delete-seed">
                        <img class="trash-icon" src="<?php echo BASE_IMAGE_URL;?>Delete.svg">
                    </div>
                    <div class="update crawler-modal-open" id="update-seed">
                        <p>Update Seed</p>
                    </div>
                </div>
            </div>
            <div class="details">
                <div class="detail-row">
                    <div class="cell">
                        <p><span class="label">NAME:</span>Pea Soup</p>
                    </div>
                    <div class="cell">
                        <p><span class="label">TITLE:</span>Pea Soup</p>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="cell">
                        <p><span class="label">TWITTER:</span><a href="" target="_blank">@peasoup</a></p>
                    </div>
                    <div class="cell">
                        <p><span class="label">RSS:</span><a href="" target="_blank">www.nameoflinkgoeshere.com</a></p>
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

<!-- Modals -->
<div class="crawler-modal delete-link-modal">
    <div class="canvas">
        <div class="body">
            <h1 class="title">Delete Link?</h1>
            <p class="link">www.nameoflinkgoeshere.com</p>
            <form action="" method="post">
                <input type="hidden" class="link-info" name="" value="">
                <div class="confirm-wrap">
                    <input class="confirm" id="delete" type="submit" value="Delete Link">
                </div>
            </form>
            <div class="close"><img src="<?php echo BASE_IMAGE_URL?>x.svg"/></div>
        </div>
    </div>
</div>
<div class="crawler-modal update-link-modal">
    <div class="canvas">
        <div class="body">
            <h1 class="title">Update Link</h1>
            <p class="link">www.nameoflinkgoeshere.com</p>
            <form action="" method="post">
                <div class="info-inputs">
                    <input type="hidden" class="link-info" name="old_link" value="">
                    <div class="input-wrap url-input">
                        <label for="url">Enter the updated URL here</label>
                        <input id="url" type="text" name="update_link" placeholder="Enter updated URL">
                    </div>
                </div>
                <div class="confirm-wrap">
                    <input class="confirm" id="update" type="submit" value="Update Link">
                </div>
            </form>
            <div class="close"><img src="<?php echo BASE_IMAGE_URL?>x.svg"/></div>
        </div>
    </div>
</div>
<div class="crawler-modal delete-seed-modal">
    <div class="canvas">
        <div class="body">
            <h1 class="title">Delete Seed?</h1>
            <p class="link">www.nameoflinkgoeshere.com</p>
            <form action="" method="post">
                <input type="hidden" class="link-info" name="delete_seed" value="">
                <div class="confirm-wrap">
                    <input class="confirm" id="delete" type="submit" value="Delete Seed">
                </div>
            </form>
            <div class="close"><img src="<?php echo BASE_IMAGE_URL?>x.svg"/></div>
        </div>
    </div>
</div>
<div class="crawler-modal update-seed-modal">
    <div class="canvas">
        <div class="body">
            <h1 class="title">Update Seed</h1>
            <p class="link">www.nameoflinkgoeshere.com</p>
            <form action="" method="post">
                <input type="hidden" class="link-info" name="update_seed" value="">
                <div class="info-inputs">
                    <div class="input-wrap url-input">
                        <label for="url">Enter the updated URL here</label>
                        <input id="url" type="text" name="url" placeholder="Enter updated URL">
                    </div>
                    <div class="input-wrap">
                        <label for="name">Name</label>
                        <input id="name" type="text" name="name" placeholder="Enter updated URL">
                    </div>
                    <div class="input-wrap">
                        <label for="title">Title</label>
                        <input id="title" type="text" name="title" placeholder="Enter updated URL">
                    </div>
                    <div class="input-wrap">
                        <label for="twitter">Twitter</label>
                        <input id="twitter" type="text" name="twitter" placeholder="Enter updated URL">
                    </div>
                    <div class="input-wrap">
                        <label for="rss">RSS</label>
                        <input id="rss" type="text" name="rss" placeholder="Enter updated URL">
                    </div>
                </div>
                <div class="confirm-wrap">
                        <input class="confirm" id="update" type="submit" value="Update Seed">
                </div>
            </form>
            <div class="close"><img src="<?php echo BASE_IMAGE_URL?>x.svg"/></div>
        </div>
    </div>
</div>


<script src="<?php echo BASE_JS_URL;?>crawler.js"></script>
<script src="<?php echo BASE_JS_URL;?>pagination.js"></script>