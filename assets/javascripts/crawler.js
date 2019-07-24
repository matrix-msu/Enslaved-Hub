$(document).ready(function(){
    var card_limit = 12;

    $(document).click(function () { // close things with clicked-off
        $('span.results-per-page').find("img:first").removeClass('show');
        $('span.results-per-page #sortmenu').removeClass('show');
        $('span.sort-by #sortmenu').removeClass('show');
        $('span.sort-by').find("img:first").removeClass('show');
    });
    
    $(".sorting-dropdowns .align-center").click(function (e) { // toggle show/hide per-page submenu
        e.stopPropagation();
        $(this).find("img:first").toggleClass('show');
        $(this).find("#sortmenu").toggleClass('show');
    });
    
    $('span.results-per-page > span').html(card_limit);
    $("ul.results-per-page li").click(function (e) { // set the per-page value
        e.stopPropagation();
        card_limit = $(this).find('span:first').html();
        localStorage.setItem('display_amount', card_limit);
        //card_offset = 0; //reset offset to 0 when changing results-per-page to go to first page
        $('span.results-per-page > span').html(card_limit);
        $(document).trigger('click');
    });

    $('.crawler-tabs li').click(function(){
        $('.crawler-tabs li').removeClass('tabbed');
        $(this).addClass('tabbed');
        var name = $(this).attr('id');

        $('.result-container').removeClass('show');
		$('.result-container#'+name).addClass('show');
		
		console.log(name);
		if(name == "results"){
			show_results();
		}
		else if(name == "broken"){
			show_broken();
		}
		else if(name == "seeds"){
			show_seeds();
		}
		
	});

	//Trigger click on results when page loads
	$('.crawler-tabs li#results').trigger('click');
	
	function show_results()
	{
		$('.results-wrap').find('.result').not('#keep').remove();
		var x="sure";
		$.ajax({
			method:'POST',
			url: BASE_URL + "api/getCrawlerResults",
			data:{'get_results':x},
			dataType: "JSON",
			success:function(data){
				if(data != "no more data"){
					$(".results-wrap").append(data);
					installModalListeners(); //install the modal listeners after content is generated
				}
				else {
					console.log("No more results");
					$('#no-more-results').show();
				}
			},
			'error':function(xhr, status, error){
				console.log(xhr.responseText);
			}
		});//ajax
	}

    function show_broken()
	{
		$('.broken-wrap').find('.result').not('#keep').remove();
		var x="sure";
		$.ajax({
			method:'POST',
			url: BASE_URL + "api/getCrawlerResults",
			data:{'get_links':x},
			dataType: "JSON",
			success:function(data){
				if(data != "no more data"){
					$(".broken-wrap").append(data);
					installModalListeners(); //install the modal listeners after content is generated
				}
				else {
					console.log("No more broken links");
					$('#no-more-broken').show();
				}
			},
			'error':function(xhr, status, error){
				console.log(xhr.responseText);
			}
		});//ajax
	}

	// function to append the seeds to the page
	function show_seeds()
	{
		$('.seed-wrap').find('.result').not('#keep').remove();
		var x="sure";
		$.ajax({
			method:'POST',
			url: BASE_URL + "api/getCrawlerResults",
			data:{'get_seeds':x},
			dataType: "JSON",
			success:function(data){
				if(data != "no more data"){
					$(".seed-wrap").append(data);
					installModalListeners(); //install the modal listeners after content is generated
				}
				else {
					console.log("No more seeds");
					$('#no-more-seeds').show();
				}  
			},
			'error':function(xhr, status, error){
				console.log(xhr.responseText);
			}
		});//ajax
	}

	// Modals
	$('.crawler-modal .canvas').css('opacity', '0');
	$('.crawler-modal').css('background', 'rgba(0, 0, 0, 0.0)');

    $('.canvas').click(function (e) {
        e.stopPropagation();
    });
    
    $('.crawler-modal').click(function () {
        $('.crawler-modal .close').trigger('click');
    });
    
    $('.crawler-modal .close').click(function () {
        $('.crawler-modal .canvas').css('opacity', '0');
        $('.crawler-modal').css('background', 'rgba(0, 0, 0, 0.0)');
        setTimeout(function(){
            $('.crawler-modal').css('display', 'none');
        }, 400);
	});
	
});

function installModalListeners(){
	var crawlerModalButton = $('.crawler-modal-open');
	
	crawlerModalButton.on('click', function(){
		var modalType = $(this).attr('id');

		$('.'+ modalType +'-modal').css("display", "flex");
		setTimeout(function(){
			$('.'+ modalType +'-modal .canvas').css('opacity', '1');
			$('.'+ modalType +'-modal').css('background', 'rgba(0, 0, 0, 0.7)');
		}, 50);
	});
}