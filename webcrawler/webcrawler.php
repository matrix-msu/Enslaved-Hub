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
?>
<?php
	require_once("config.php");
	require_once("models/crawler_keywords.php");
	require_once("models/crawler_deleted_keywords.php");

?>

<?php if($isCrawlerAdmin) { ?>
	<script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/webcrawler.js"></script>
    <script src="js/crawler_results.js"></script>
	<script src="js/crawler_seeds.js"></script>
	<script src="js/crawler_broken_links.js"></script>
<?php } ?>

<div class="fullTopBar" id="webCrawler">
    <div class="innerBar">
        <div class="linkListWebCrawl">
            <a href="javascript:;" id="webCrawlResults">Results</a>
            <a href="javascript:;" id="webCrawlBroken">Broken Links</a>
            <a href="javascript:;" id="webCrawlSeeds">Seeds</a>
        </div>
    </div>
</div>


<div class="se-pre-con"></div>
<div id="main">

    <div id="content">


     <?php if($isCrawlerAdmin) {	 ?>
	 <?php  // hidden field to store seeds value
	 if(isset($_GET['seeds']))
	 {
	 ?>
	 <input type="text" id="seeds_is_set" value="seeds" style="display:none"> </input>
	 <?php
	 }
	 ?>
        <br/><br/>
        <h1>Web Crawling</h1>
		<!-- Bookmarklet -->
        <!-- <a href="javascript:location.href='<?php //bloginfo('wpurl'); ?>/insert-to-source/?url=' + encodeURIComponent(location.href) + '&amp;title=' + encodeURIComponent(document.title);"> -->
            <div class="bookmarklet">
                <!-- <img src="<?php //bloginfo('template_url'); ?>/images/ppjLogo.svg" width="38" height="21" alt="PPJ Logo"/> -->
                <p>ADD TO SEEDS</p>
            </div>
        </a>
        <p id="bookmarkletInstructions">(DRAG AND SAVE TO BOOKMARKS)</p>



        <div id="resultsWeb">

	        <select id="sortDate" placeholder="filter results by week / date">
		        <option selected disabled></option>

				 <?php
				//get a list of dates from database
				$crawler_keywords =new crawler_keywords();
				$dates=$crawler_keywords->get_dates();
				if($dates=="no keywords") $dates="";
				else {
					foreach($dates as $date)
					{

						$oneWeekAgo = strftime("%Y-%m-%d", strtotime($date['keyword_date']) - 60*60*24*6);
						$new_date=date_format(date_create($date['keyword_date']),"m/d/Y");
						$new_oneWeekAgo=date_format(date_create($oneWeekAgo),"m/d/Y");
						echo "<option>".$new_oneWeekAgo." - ".$new_date."</option>";
					}
				}
				?>

	        </select>
		<?php
		//********** results are displayed here ***********//
		// connect to keywords data base and get the first list of keywords
		$limit=20;

		$results=$crawler_keywords->get_keywords($limit,0);
		if($results!="no more data")
		echo($results);

		?>

        </div>

		<div id="brokenLinks">
	       <?php
		   //********* Broken link are displayed here from webcrawler.js  on click of broken links tab*********//

		   ?>
		   	<div id="no-more-broken" style="display:none;">
				No more broken links
			</div>
        </div>

        <div id="crawlSeeds">
		<?php //echo $post->post_content; ?>
	     	<?php
			//******** seeds result is displayed here from webcrawler.js   on click from seeds tab **********//
			?>


        </div>
	<div id="no-more-seeds" style="display:none;" align="center">
				No more seeds
			</div>
			<br>
			<div id="seeds_results_related" style="display:none;">
			<button id="moreSeeds">Load More</button>
			</div>

		<div id="delete_box" style="display:none;">
			<div class="closer"></div>
			<div class="deleteInner">
				<h3>Deactivate Confirmation</h3>
				<p id="delete_text">Are you sure you want to deactivate this user?</p>

				<button id="delete_cancel">CANCEL</button>
				<button id="delete_confirm">DEACTIVATE</button>
			</div>
		</div>

		<div id="activate_box" style="display:none;">
			<div class="closer"></div>
			<div class="activateInner">
				<h3>Activate Confirmation</h3>
				<p id="activate_text">Are you sure you want to activate this user?</p>

				<button id="activate_cancel">CANCEL</button>
				<button id="activate_confirm">ACTIVATE</button>
			</div>
		</div>
		<div id="results_related">
		<div align= "center" id="no-more" style="display:none;">
			No more keywords
		</div>

		<button id="more">Load More</button>
		</div>

<?php } else { ?>
		<p><strong id="permissions_inv">Whoops, this is embarassing...<br>
			You don't have the permissions to view this page, sorry!</strong></p>
<?php } ?>
    </div>
</div>

<?php //get_footer(); ?>
