var allTerms = [];	// Variable to keep track of search terms.
var searchCategories = ["Title","Description","Subject","Transcript","Translation"];
var uniqueLatitudes = [];
var uniqueLongitudes = [];
var uniqueCountries = [];
var countryLatPairs = {};
var countryCounts = {};

$(document).ready(function() {
	// Initialize the searchbox.
	initializeSearch();

	// Initialize the map of Africa.
	initializeMap();

	console.log(mapContent);
});
// End document ready.



// Initialize the map of Africa.
function initializeMap() {
	// Set map access token from Mapbox account.
	mapboxgl.accessToken = 'pk.eyJ1IjoibWF0cml4LW1zdSIsImEiOiJmU1NPbUFjIn0.MWCWCMSJ8Ar-6KZtNPzy4w';

	// Initialize map with style and location.
	var map = new mapboxgl.Map({
		container: 'map',
		style: 'mapbox://styles/mapbox/light-v9',
		center: [15, 5],
		zoom: 2.5
	});

	// Get all coordinates for map.
	var mapInfo = formatMapContent();
	var mapCoords = mapInfo[0];
	var mapCountries = mapInfo[1];

	// Load map sources/layers.
	map.on('load', function () {
		var coords = [];

		for (var j=0;j<mapCoords.length;j++) {
			var latLong = mapCoords[j].split(',');
			var latitude = latLong[0];
			var longitude = latLong[1];
			var country = mapCountries[j];
			var testJson = {};

			if (uniqueLatitudes.indexOf(latitude)==-1) {
				uniqueLatitudes.push(latitude);
				uniqueCountries.push(country);
				uniqueLongitudes.push(longitude);
				countryLatPairs[latitude] = country;
			}
			else if (uniqueCountries.indexOf(country)==-1) {
				tempCountry = countryLatPairs[latitude];
				countryCounts[tempCountry]+=1;
			}
			if (countryCounts[country]==undefined) {
				countryCounts[country]=1;
			}
			else {
				countryCounts[country]+=1;
			}
		}
		for (var j=0;j<uniqueLatitudes.length;j++) {
			var testJson = {};

			testJson["type"] = "Feature";
			testJson["properties"] = {"name": "circle" + j, "country": uniqueCountries[j], "count": countryCounts[uniqueCountries[j]]};
			testJson["geometry"] = {"type": "Point", "coordinates": [uniqueLongitudes[j], uniqueLatitudes[j]]};

			coords.push(testJson);
		}

		// Add data as new source.
		map.addSource("symbols", {
			"type": "geojson",
			"data": {
				"type": "FeatureCollection",
				"features": coords
			}
		});

		map.addLayer({
			"id": "unclustered-points",
			"type": "symbol",
			"source": "symbols",
			"layout": {
				"icon-image": "marker-15"
			}
		});

		// map.addLayer({
		// 	"id": "cluster-circle-border",
		// 	"type": "circle",
		// 	"source": "symbols",
		// 	"paint": {
		// 		"circle-color": "#3F3F3F",
		// 		"circle-radius": 23.5,
		// 		"circle-opacity": .6
		// 	},
		// });
		// Add another slightly larger circle that will
		// act as the border when hovering over a circle.
		map.addLayer({
			"id": "cluster-circle-hover-border",
			"type": "circle",
			"source": "symbols",
			"paint": {
				"circle-color": "#FFFFFF",
				"circle-radius": 23.5,
				"circle-opacity": 0
			},
			"filter": ["==", "name", ""]
		});
		// Layer to add circle over record clusters.
		map.addLayer({
			"id": "cluster-circle",
			"type": "circle",
			"source": "symbols",
			"paint": {
				"circle-color": "#3F3F3F",
				"circle-radius": 22.5,
				"circle-opacity": .5
			},
		});
		// Layer to change the circle color on hover.
		map.addLayer({
			"id": "circle-fills",
			"type": "circle",
			"source": "symbols",
			"layout": {},
			"paint": {
				"circle-color": "#CC523D",
				"circle-radius": 22.5
			},
			"filter": ["==", "name", ""]
		});
		// Add a layer for the clusters' count labels
		map.addLayer({
			"id": "cluster-count",
			"type": "symbol",
			"source": "symbols",
			"layout": {
				"text-field": "{count}",
				"text-font": [
					"DIN Offc Pro Medium",
					"Arial Unicode MS Bold"
				],
				"text-size": 12
			}, // Shifted this to white as well
			"paint": {
				"text-color": "#FFFFFF"
			}
		});
		// Layer to change text color to white on hover.
		map.addLayer({
			"id": "cluster-count-white",
			"type": "symbol",
			"source": "symbols",
			"layout": {
				"text-field": "{count}",
				"text-font": [
					"DIN Offc Pro Medium",
					"Arial Unicode MS Bold"
				],
				"text-size": 12
			},
			"paint": {
				"text-color": "#FFFFFF"
			}
		});

		// If the mouse is over a set of records, activate hover styling.
		// Otherwise, remove hover styling.
		map.on("mousemove", function(e) {
			var features = map.queryRenderedFeatures(e.point, { layers: ["cluster-circle"] });
			if (features.length) {
				map.setFilter("circle-fills", ["==", "name", features[0].properties.name]);
				map.setFilter("cluster-count-white", ["==", "name", features[0].properties.name]);
				map.setFilter("cluster-circle-hover-border", ["==", "name", features[0].properties.name]);
				map.getCanvas().style.cursor = 'pointer';
			}
			else {
				map.setFilter("circle-fills", ["==", "name", ""]);
				map.setFilter("cluster-count-white", ["==", "name", ""]);
				map.setFilter("cluster-circle-hover-border", ["==", "name", ""]);
				map.getCanvas().style.cursor = 'default';
			}
		});

		// Reset the circle-fills layer's filter when the mouse leaves the map.
		map.on("mouseout", function() {
			map.setFilter("circle-fills", ["==", "name", ""]);
			map.setFilter("cluster-count-white", ["==", "name", ""]);
			map.setFilter("cluster-circle-hover-border", ["==", "name", ""]);
		});

	});

	// Set filters so that they are not active on page load.
	map.on("load", function(e) {
		map.setFilter("circle-fills", ["==", "name", ""]);
		map.setFilter("cluster-count-white", ["==", "name", ""]);
		map.setFilter("cluster-circle-hover-border", ["==", "name", ""]);
		$(".loading").hide();
	});

	// Redirect to search when records are clicked.
	map.on("click", function(e) {
		var features = map.queryRenderedFeatures(e.point, { layers: ["cluster-circle"] });

		if (features!='') {
			var mapLat = parseFloat(features[0]['geometry']['coordinates'][1]).toFixed(1);
			var mapLong = parseFloat(features[0]['geometry']['coordinates'][0]).toFixed(1);

			for (var i=0;i<uniqueLatitudes.length;i++) {
				if (parseFloat(uniqueLatitudes[i]).toFixed(1)==mapLat && parseFloat(uniqueLongitudes[i]).toFixed(1)==mapLong) {
					var country = uniqueCountries[i];

					window.location = "search.php?Country=" + country + "&currSearches=" + allTerms;
				}
			}
		}
	});
}

// Get the latitude, longitude, and country of origin for each record.
function formatMapContent() {
	var latLongs = [];
	var countries = [];

	var filteredRecords = filterRecords();

	for (var i=0;i<filteredRecords.length;i++) {
		if (filteredRecords[i]['Coverage_Spatial_LatLong']!='' && filteredRecords[i]['Country']!='') {

			currLatLong = filteredRecords[i]['Coverage_Spatial_LatLong'];
			currCountry = filteredRecords[i]['Country'];

			latLongs.push(currLatLong);
			countries.push(currCountry);
		}
	}
	var content = [latLongs, countries];

	return content;
}

// Filter KORA results by search terms.
function filterRecords() {
	var filtRecords = [];

	if (allTerms.length>0) {
		for (var i=0;i<mapContent.length;i++) {
			for (var j=0;j<searchCategories.length;j++) {
				for (var k=0;k<allTerms.length;k++) {

					if (typeof(mapContent[i][searchCategories[j]])=="object") {
						var recordObj = mapContent[i][searchCategories[j]];
						for (var l=0;l<recordObj.length;l++) {
							var recordStr = recordObj[l].toLowerCase();
							var toCompare = allTerms[k].toLowerCase();

							if (recordStr.indexOf(toCompare)!==-1) {
								filtRecords.push(mapContent[i]);
								l=recordObj.length;
								k=allTerms.length;
								j=searchCategories.length;
							}
						}
					}
					else {
						var recordStr = mapContent[i][searchCategories[j]].toLowerCase();
						var toCompare = allTerms[k].toLowerCase();

						if (recordStr.indexOf(toCompare)!==-1) {
							filtRecords.push(mapContent[i]);
							k=allTerms.length;
							j=searchCategories.length;
						}
					}
				}
			}
		}
		return filtRecords;
	}
	else {
		return mapContent;
	}
}

// Add new search term to list if necessary.
function initializeSearch() {
	if (searchTerms!='') {
		$('.searchIcon').addClass("hasTerms");

		searchTerms = searchTerms.split(',');

		for (var i=0;i<searchTerms.length;i++) {
			allTerms.push(searchTerms[i]);
		}
	}

	$('#currSearches').val(allTerms);

	if (allTerms[0]!='') {
		for (var i=0;i<allTerms.length;i++) {
			var nextTerm = '<div id="tag' + i + '" class="searchTerm" contenteditable="false">' + allTerms[i] +
								'<span class="remove" contenteditable="false"></span>' +
							'</div>';

			$('#searchDiv').prepend(nextTerm);
		}
	}
}

// Handles removal of search term.
$(document).on("click", ".remove", function(e) {

	// Get tag, then remove from the search list.
	var parentID = $(this).parents()[0].id;

	var tag = $($('#' + parentID)[0]).text();

	// Remove tag from search box.
	$(this).parent().remove();

	var index = allTerms.indexOf(tag);

	if (index!==-1) {
		allTerms.splice(index,1);
	}
	if (allTerms=='') {
		$('.searchIcon').removeClass('hasTerms');
	}

	$('#currSearches').val(allTerms);
});

// Submits new search term.
$(document).keydown(function(e) {
	var key = e.keyCode;
	var activeElementID = e.currentTarget.activeElement.id;

	if (key == 13 && activeElementID=='newSearch') {
		$('.searchTerm').remove();
		var newTerm = $('#newSearch').text();
		if (newTerm!="") {
			allTerms.push(newTerm);
		}
		$('#currSearches').val(allTerms);
		$('#submit').trigger('click');
		return false;
	}
});

$(".searchIcon").click(function() {
	$('.searchTerm').remove();
	var newTerm = $('#newSearch').text();
	if (newTerm!="") {
		allTerms.push(newTerm);
	}
	$('#currSearches').val(allTerms);
	$('#submit').trigger('click');
});
