var card_limit = 12;
var card_offset = 0;
var total_length = 12;
var sort_direction = 'ASC';
var search_terms = '';
var tag_filter_ids = [];
var google_search_url = 'https://www.google.com/search?hl=en&num=100&q=';

$(document).ready(function(){

	//Change in current-page input so call searchResults function
    $('#pagination .current-page').change(function(){
		var val = $('#pagination .current-page').val();
		//Calculate new offset and trigger result reload
		card_offset = (val - 1) * card_limit;
		$('.crawler-tabs li.tabbed').trigger('click');
	});

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
        card_offset = 0; //reset offset to 0 when changing results-per-page to go to first page
        $('span.results-per-page > span').html(card_limit);
		$(document).trigger('click');
		$('.crawler-tabs li.tabbed').trigger('click');
    });

    $("ul.tag-filter li").click(function (e) {
        e.stopPropagation();
        tag_filter_ids = [];
        $("ul.tag-filter li").each(function () {
        	if ($(this).find("input[type=checkbox]").prop("checked")) {
        		tag_filter_ids.push($(this).data('id'));
        	}
        });
		$('.crawler-tabs li.tabbed').trigger('click');
    });

    $("ul.sort-by li").click(function (e) { // set the sorting
        e.stopPropagation();
        sort_direction = $(this).data('sort');
        localStorage.setItem('sort_direction', sort_direction);
		$(document).trigger('click');
		$('.crawler-tabs li.tabbed').trigger('click');
    });

	$("#crawler-search").submit(function (e) { // set the sorting
        e.stopPropagation();
        e.preventDefault();
        search_terms = $(this).serializeArray()[0]['value'];
        localStorage.setItem('crawler_search_terms', search_terms);
		$('.crawler-tabs li.tabbed').trigger('click');
    });

    $('.crawler-tabs li').click(function(){
		if(!$(this).hasClass('tabbed')){
			card_offset = 0;
		}
        $('.crawler-tabs li').removeClass('tabbed');
        $(this).addClass('tabbed');
        var name = $(this).attr('id');

        $('.result-container').removeClass('show');
		$('.result-container#'+name).addClass('show');

		$('.result-container').find('.result').not('#keep').off().remove(); //.not('#keep')


		var type = {};
		var count_type = '';

		if(name == "results"){
			type['get_results'] = 'ok';
			count_type = 'count_results';
		}
		else if(name == "broken"){
			type['get_links'] = 'ok';
			count_type = 'count_links';
		}
		else if(name == "seeds"){
			type['get_seeds'] = 'ok';
			count_type = 'count_seeds';
		}

		showResults(type, count_type);

	});

	//Trigger click on results when page loads
	$('.crawler-tabs li#results').trigger('click');

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

	$('.seed-wrap form').submit(function(e){
		e.preventDefault();

		var form = $(this);

		$.ajax({
			type: "POST",
			url: BASE_URL + "api/getCrawlerResults",
			data: form.serialize(),
			dataType: "JSON",
			success:function(data){
				//after ajax refresh tab
				$('.crawler-tabs li.tabbed').trigger('click');
				$('.seed-wrap form input.search-field').val('');
			},
			error:function(xhr, status, error){
				console.log(xhr.responseText);
			}
		});
	});

});

//Main function for showing results, gets total count of results first and then calls the ajax to get the results
function showResults(result_type, count_type)
{
	//Setup data to send for results
	var get_data = result_type;
	get_data['limit'] = card_limit;
	get_data['offset'] = card_offset;
	get_data['sort'] = sort_direction;
	get_data['terms'] = search_terms;

	//Data to send for count
	var count_data = {};
	count_data[count_type] = 'ok';

	//Start with ajax call to get count for total length of selected tab
	$.ajax({
		method:'POST',
		url: BASE_URL + "api/getCrawlerResults",
		data: count_data,
		dataType: "JSON",
		success:function(data){
			//On success make ajax call to get the results
			total_length = data;
			getResults(get_data);
		},
		error:function(xhr, status, error){
			console.log(xhr.responseText);
		}
	});//ajax

}

function setTags() { //might not use this
	$.ajax({
		method:'POST',
		url: BASE_URL + "api/getCrawlerResults",
		data: {'get_tags': 'ok'},
		dataType: "JSON",
		success:function(data){
			if(data){
				html = '';
				$.each(data, function (_, tag) {
					html += `<li><input type="checkbox" data-id="${tag['id']}">${tag['tag_name']}</li>`;
				});
				$("ul.tag-filter").append(html);
			}
		},
		error:function(xhr, status, error){
			console.log(xhr.responseText);
		}
	});
}

//Gets the results for the selected tab
function getResults(get_data)
{
	$.ajax({
		method:'POST',
		url: BASE_URL + "api/getCrawlerResults",
		data: get_data,
		dataType: "JSON",
		success:function(data){
			if(data) {
				html = populateCrawlerResults(data);
				$(".result-container").append(html);
				installModalListeners(); //install the modal listeners after content is generated
				$(document).ready(function(){
					setPagination(total_length, card_limit, card_offset);
				});
			}
		},
		error:function(xhr, status, error){
			console.log(xhr.responseText);
		}
	});//ajax
}

function populateCrawlerResults(data) {
	row = '';
	for (var i = 0; i < data.length; i++) {
		result = data[i];
		row += `<div class="result" id="r${i+1}">`;
		row += `<div class="link-name"><a class="link" href="${google_search_url}${result['keyword']}"target="_blank">${result['keyword']}</a></div>`;
		row += `<div class="link-wrap"><a class="link" target="_blank" href="${result['url']}">${result['url']}</a>`;
		if (location.href.match(/crawler/)) {
	        row += '<div class="right"><div class="trash crawler-modal-open" id="delete-link">';
			row += '<img class="trash-icon" src="./assets/images/Delete.svg"></div>';
			row += '<div class="add-seed"><p>Add to Seeds</p><form action="submit">';
			row += `<input type="hidden" name="add_seed" value="${result['url']}"></form></div>`;
			row += `<div class="add-tag" id="add-tag"><p>Add Tag</p></div></div>`
	    }
	    row += '</div></div>';
	}
	return row;
}

function installModalListeners(){
	var crawlerModalButton = $('.crawler-modal-open');

	// Call off before adding click listener so that the listeners dont stack
	crawlerModalButton.off().on('click', function(){
		var modalType = $(this).attr('id');

		//Dynamically put selected info into correct modal
		if(modalType == "delete-link"){
			var url = '';
			if($(".crawler-tabs li#results").hasClass("tabbed")){
				//On results tab, type = delete result
				var keyword = $(this).parent().parent().parent().find('.link-name a.link').text();
				url = $(this).parent().parent().parent().find('.link-wrap a.link').text();

				$('.'+ modalType +'-modal .link-info').attr('name', 'delete_result');
				$('.'+ modalType +'-modal .link-info').attr('value', keyword);
			}
			else if($(".crawler-tabs li#broken").hasClass("tabbed")){
				//On broken links tab, type = delete link
				url = $(this).parent().parent().parent().find('.link-wrap a.link').text();

				$('.'+ modalType +'-modal .link-info').attr('name', 'delete_link');
				$('.'+ modalType +'-modal .link-info').attr('value', url);
			}

			$('.'+ modalType +'-modal p.link').text(url);
		}
		else if(modalType == "update-link"){
			var url = $(this).parent().parent().parent().find('.link-wrap a.link').text();
			$('.'+ modalType +'-modal p.link').text(url);
			$('.'+ modalType +'-modal .link-info').attr('value', url);
		}
		else if(modalType == "delete-seed"){
			var url = $(this).parent().parent().parent().find('.link-wrap a.link').text();
			var seedid = $(this).parent().parent().parent().find('.link-wrap a.link').attr('id');
			$('.'+ modalType +'-modal p.link').text(url);
			$('.'+ modalType +'-modal .link-info').attr('value', seedid);
		}
		else if(modalType == "update-seed"){
			var url = $(this).parent().parent().parent().find('.link-wrap a.link').text();
			var seedid = $(this).parent().parent().parent().find('.link-wrap a.link').attr('id');
			$('.'+ modalType +'-modal p.link').text(url);
			$('.'+ modalType +'-modal .link-info').attr('value', seedid);
		}


		//Display modal after setting info
		$('.'+ modalType +'-modal').css("display", "flex");
		setTimeout(function(){
			$('.'+ modalType +'-modal .canvas').css('opacity', '1');
			$('.'+ modalType +'-modal').css('background', 'rgba(0, 0, 0, 0.7)');
		}, 50);
	});

	// Call off before adding click listener so that the listeners dont stack
	$('.crawler-modal form').off().submit(function(e){
		e.preventDefault();

		var form = $(this);

		$.ajax({
			type: "POST",
			url: BASE_URL + "api/getCrawlerResults",
			data: form.serialize(),
			dataType: "JSON",
			success:function(data){
				//after ajax close modal and refresh tab
				$('.crawler-modal .close').trigger('click');
				$('.crawler-tabs li.tabbed').trigger('click');
			},
			error:function(xhr, status, error){
				console.log(xhr.responseText);
			}
		});
	});


	//Listeners for adding result to seeds
	$('.add-seed').off().click(function(){
		$(this).find('form').submit();
	});

	$('.add-seed form').off().submit(function(e){
		e.preventDefault();

		var form = $(this);

		$.ajax({
			type: "POST",
			url: BASE_URL + "api/getCrawlerResults",
			data: form.serialize(),
			dataType: "JSON",
			success:function(data){
				//after ajax refresh tab
				$('.crawler-tabs li.tabbed').trigger('click');
			},
			error:function(xhr, status, error){
				console.log(xhr.responseText);
			}
		});
	});
}
