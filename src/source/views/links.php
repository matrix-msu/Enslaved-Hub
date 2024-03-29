<?php
    $cache_data = Json_GetData_ByTitle("Resources");
    require_once(BASE_PATH . "models/crawler_tags.php");

    $isCrawlerAdmin = true;
    $crawler_tags = new crawler_tags();
    $tags = $crawler_tags->get_tags();
?>
<!-- Resources page-->
<!-- Heading image and title container-->
<div class="container header explore-header people-page">
    <div class="image-container search-page image-only">
    <img class="header-background contributors-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">
    <div class="container middlewrap">
        <h1><?php echo $cache_data['title'] ?></h1>
    </div>
    <div class="image-background-overlay"></div>
  </div>
</div>
<!-- info container-->
<div class="container info">
    <div class="container infowrap">
        <p><!-- <?php echo $cache_data['descr'] ?>-->
The following is a list of resources found by our discovery tool that weekly searches the internet for resources about historical slavery.  If you know of sources -- particularly projects and archives with records about those enslaved -- please let us know (as well as about sources that should not be included).  To let us know, go to our <a href="<?php echo BASE_URL;?>about">Contact</a> page.
        </p>
    </div>
</div>

<div class="crawler">
    <div class="project-tab crawler-tabs">
        <ul>
            <li class="tabbed" id="results_visible">Results</li>
            <hr>
        </ul>
    </div>
    <div class="search-filter">
        <div class="crawler-search">
            <form action="submit" id="crawler-search">
                <label for="searchbar" class="sr-only">searchbar</label>
                <input id="searchbar" class="search-field" type="text" name="searchbar" placeholder="Find a URL"/>
                <button class="search-icon-2" type="submit"><img src="<?php echo BASE_URL;?>/assets/images/Search-dark.svg" alt="search-icon"></button>
            </form>
        </div>
        <div class="sorting-dropdowns">
            <span class="align-center sort-by">Sort By <img src="<?php echo BASE_URL;?>assets/images/chevron.svg" alt="results per page button">
                <ul id="sortmenu" class="sort-by">
                    <li data-sort="DESC">Newest</li>
                    <li data-sort="ASC">Oldest</li>
                </ul>
            </span>
            <span class="align-center results-per-page"><span class="sortby-title">9</span> Per Page <img src="<?php echo BASE_URL;?>assets/images/chevron.svg" alt="results per page button">
                <ul id="sortmenu" class="results-per-page">
                    <li><span>12</span> Per Page</li>
                    <li><span>24</span> Per Page</li>
                    <li><span>36</span> Per Page</li>
                    <li><span>48</span> Per Page</li>
                </ul>
            </span>
            <span class="align-center tag-filter">Filter By Tags <img src="<?php echo BASE_URL;?>assets/images/chevron.svg" alt="results per page button">
                <ul id="sortmenu" class="tag-filter">
                    <?php foreach ($tags as $tag) {
                        if ($tag['tag_name'] != 'No Display') {
                            echo '<li data-id="' . $tag['tag_id'] . '"><input type="checkbox">' . $tag['tag_name'] . '</li>';
                        }
                    } ?>
                </ul>
            </span>
        </div>
    </div>

    <?php if($isCrawlerAdmin) {	 ?>
    <div class="results-wrap result-container show" id="results_visible">
        <div class="result_header">
            <div class="title">
                <p>Title</p>
            </div>
            <div class="url">
                <p>URL</p>
                <div class="right">
                    <div class="tag">
                        <span>Tags</span>
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
        <div class="first-prev">
            <div class="pagi-first" value="1"><p>First</p></div>
            <div class="pagi-left"><img src="<?php echo BASE_IMAGE_URL; ?>chevron.svg" alt="Arrow Left"/></div>
        </div>
        <div class="page-numbers">
            <span class="dotsLeft">...</span>
            <span class="num one"></span>
            <span class="num two"></span>
            <span class="num three"></span>
            <span class="num four"></span>
            <span class="num five"></span>
            <span class="dotsRight">...</span>
        </div>
        <div class="last-next">
            <div class="pagi-right"><img src="<?php echo BASE_IMAGE_URL; ?>chevron.svg" alt="Arrow Right"/></div>
            <div class="pagi-last" value="1"><p>Last</p></div>
        </div>
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
