$(document).ready(function(){

	function show_broken(data)
	{

		if(data!="no more data")
		{$("#brokenLinks").append(data);}
		else {$('#no-more-broken').show();}

	}

	// function to append the seeds to the page
	function show_seeds(data)
	{
		if(data!="no more data")
		{$("#crawlSeeds").append(data);}
		else {$('#no-more-seeds').show();}

	}

	$('#no-more').hide();
	$('#no-more-seeds').hide();
	$('#no-more-broken').hide();
	// if seeds_is_set available show seeds by simulating a click on it

	if($('#seeds_is_set').length) {

		display_seeds();
	}
	else{
		//initial setup of tabs and show/hide associated with tabs
		$("#webCrawlResults").addClass("active");
		$("#webCrawlBroken").removeClass("active");
		$("#webCrawlSeeds").removeClass("active");
		$("#resultsWeb").show();
		$("#crawlSeeds").hide();
		$("#brokenLinks").hide();

		$("#seeds_results_related").hide();
	}

	// $("#sortDate").SumoSelect();
	//for mobile
	if ($(window).width() <= 1200) {
		var contWidth = $("#resultsWeb").width();

		$(".linkWeb").width(contWidth);

		$(window).resize( function() {
			if ($(window).width() <= 1200) {
				var contWidth = $("#resultsWeb").width();
				$(".linkWeb").width(contWidth);
			}
		});
	}

	//on click for results tab
	$("#webCrawlResults").click(function(){
		$('#no-more-broken').hide();
		$("#webCrawlResults").addClass("active");
		$("#webCrawlBroken").removeClass("active");
		$("#webCrawlSeeds").removeClass("active");
		$("#resultsWeb").show();
		$("#brokenLinks").hide();
		$("#crawlSeeds").hide();
		$("#seeds_results_related").hide();
		$("#results_related").show();
		$('#no-more-seeds').hide();
		//for mobile
		if ($(window).width() <= 1200) {
			var contWidth = $("#resultsWeb").width();

			$(".linkWeb").width(contWidth);

			$(window).resize( function() {
				if ($(window).width() <= 1200) {
					var contWidth = $("#resultsWeb").width();
					$(".linkWeb").width(contWidth);
				}
			});
		}
	});

	//on click for broken links tab
	$("#webCrawlBroken").click(function(){
		var x="sure";
		jQuery.ajax({
		method:'POST',
		url:"ajax/crawler_jquery.php",
		data:{get_links:x},
		success:function(data){show_broken(data);  },
		dataType: "JSON",
	});//ajax
	$("#results_related").hide();
	$("#seeds_results_related").hide();
		$("#webCrawlBroken").addClass("active");
		$("#webCrawlResults").removeClass("active");
		$("#webCrawlSeeds").removeClass("active");
		$("#resultsWeb").hide();
		$("#brokenLinks").show();
		$("#crawlSeeds").hide();
		$('#no-more-seeds').hide();
		//get crawler links;
		//for mobile
		if ($(window).width() <= 1000) {
			var contWidth1 = $("#brokenLinks").width();
			$(".urlWeb").width(contWidth1);

			$(window).resize( function() {
				if ($(window).width() <= 1000) {
					var contWidth1 = $("#brokenLinks").width();
					$(".urlWeb").width(contWidth1);
				}
			});
		}
	});
	// on click seeds tab
	$("#webCrawlSeeds").click(function()  {
		display_seeds();
	});
	function display_seeds()
	{
		var x="sure";
		jQuery.ajax({
			method:'POST',
			url:"ajax/crawler_jquery.php",
			data:{get_seeds:x},
			success:function(data){show_seeds(data);  },
			dataType: "JSON",
		});//ajax
	$('#no-more-broken').hide();
		$("#webCrawlSeeds").addClass("active");
		$("#webCrawlResults").removeClass("active");
		$("#webCrawlBroken").removeClass("active");
		$("#crawlSeeds").show();
		$("#resultsWeb").hide();
		$("#brokenLinks").hide();
		$("#results_related").hide();
		$("#seeds_results_related").show();
		if ($(window).width() <= 1000) {
			var contWidth1 = $("#main").width();
			//console.log(contWidth1);
			$(".result").width(contWidth1);

			$(window).resize( function() {
				if ($(window).width() <= 1000) {
					var contWidth1 = $("#main").width();
					$(".result").width(contWidth1);
				}
			});
		}
	}

});//document ready
