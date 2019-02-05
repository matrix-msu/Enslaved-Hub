<?php
	$pagename = 'Map';
	include('includes/head.php');
	include('includes/header.php');
	include('includes/config.php');
	include_once('/matrix/www/kora/kora/includes/koraSearch.php');

	// Get KORA content that will populate the map.
	$mapContent = getMapContent();
	$searchInfo = getSearchTerms();
	$newSearchTerm = $searchInfo[0];
	$searchTerms = $searchInfo[1];
?>

<script src='https://api.mapbox.com/mapbox-gl-js/v0.25.0/mapbox-gl.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v0.25.0/mapbox-gl.css' rel='stylesheet'/>

<div id='main'>
	<div class="loading">
		<div class="spinner">
			<div class="bounce1"></div>
			<div class="bounce2"></div>
			<div class="bounce3"></div>
		</div>
    </div>
	<form class="search-box">
		<div id="searchForm">
			<div id="searchDiv">
				<div type="text" id="newSearch" name="search" placeholder="Filter results by search term..." contenteditable="true"></div>
			</div>
			<input id="currSearches" type="text" name="currSearches" style="display:none">
			<input type="submit" id="submit" style="display:none">
			<img class="searchIcon" src='assets/svgs/Search.svg'/>
		</div>
	</form>
	<div id="map"></div>
</div>

<!--Modals Include-->
<?php
	include('includes/modals.php');
?>

<!--Scripts & Footer-->
<script src='js/modals.js'></script>
<script src='js/map.js'></script>

<script type="text/javascript">
    var mapContent = <?php echo json_encode($mapContent); ?>;
	var newSearchTerm = <?php echo json_encode($newSearchTerm); ?>;
	var searchTerms = <?php echo json_encode($searchTerms); ?>;
</script>

<?php

	// Get content to populate the map.
	function getMapContent() {
		$mapContent = array();

		foreach (COLLECTIONS as $key => $value) {
			$projID = $value;
			$schemeID = SCHEMES[$key];

			$projContent = KORA_Search(TOKEN,$projID,$schemeID,new KORA_Clause('kid','!=',''),array('ALL'),array(),0,0);

			if ($projContent!=null) {
				foreach ($projContent as $record) {
					array_push($mapContent, $record);
				}
			}
		}

		return $mapContent;
	}

	// Get search terms.
	function getSearchTerms() {
		if (isset($_GET['search'])) {
			$newSearchTerm = $_GET['search'];
		}
		else {
			$newSearchTerm = '';
		}
		if (isset($_GET['currSearches'])) {
			$searchTerms = $_GET['currSearches'];
		}
		else {
			$searchTerms = '';
		}
		return [$newSearchTerm, $searchTerms];
	}

	//include('includes/footer.php');
?>
