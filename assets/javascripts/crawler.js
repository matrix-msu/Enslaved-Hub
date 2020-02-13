var card_limit = 12;
var card_offset = 0;
var total_length = 12;
var sort_direction = 'DESC';
var search_terms = '';
var tag_filter_ids = [];
var all_tags = [];
var tab_type = '';
var seed_urls = [];
var google_search_url = 'https://www.google.com/search?hl=en&num=100&q=';
var mozilla_http_status_url = 'https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/';

$(document).ready(function(){

	getTags();
	getAllSeeds();
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
        tab_type = $(this).attr('id');

        $('.result-container').removeClass('show');
		$('.result-container#'+tab_type).addClass('show');

		$('.result-container').find('.result').not('#keep').off().remove(); //.not('#keep')


		var type = {};
		var count_type = '';

		if(tab_type == "results"){
			type['get_results'] = 'ok';
			count_type = 'count_results';
			$('.create-seed').removeClass('show');
		}
		else if(tab_type == "broken"){
			type['get_links'] = 'ok';
			count_type = 'count_links';
			$('.create-seed').removeClass('show');
		}
		else if(tab_type == "seeds"){
			type['get_seeds'] = 'ok';
			count_type = 'count_seeds';
			$('.create-seed').addClass('show');
		}
		else if(tab_type == "results_visible"){
			type['get_results_visible'] = 'ok';
			count_type = 'count_results_visible';
			$('.create-seed').removeClass('show');
		}

		showResults(type, count_type);

	});

	//Trigger click on results when page loads
	$('.crawler-tabs li#results').trigger('click');
	$('.crawler-tabs li#results_visible').trigger('click');

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

//Need to uniform with original code
$('.create-seed').click(function(){
	$('.create-seed-modal').css("display", "flex");
	setTimeout(function(){
		$('.create-seed-modal .canvas').css('opacity', '1');
		$('.create-seed-modal').css('background', 'rgba(0, 0, 0, 0.7)');
	}, 50);
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
	get_data['tag_ids'] = tag_filter_ids;

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

function getTags() {
	$.ajax({
		method:'POST',
		url: BASE_URL + "api/getCrawlerResults",
		data: {'get_tags': 'ok'},
		dataType: "JSON",
		success:function(data){
			if(data){
				all_tags = data;
			}
		},
		error:function(xhr, status, error){
			console.log(xhr.responseText);
		}
	});
}

function getAllSeeds() {
	$.ajax({
		method:'POST',
		url: BASE_URL + "api/getCrawlerResults",
		data: {'get_seed_urls': 'ok'},
		dataType: "JSON",
		success:function(data){
			seed_urls = data;
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
				if (tab_type === 'results' || tab_type === 'results_visible')
					html = populateCrawlerResults(data);
				if (tab_type === 'seeds')
					html = populateCrawlerSeeds(data);
				if (tab_type === 'broken')
					html = populateCrawlerBrokenLinks(data);
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

function populateCrawlerBrokenLinks(data) {
	html = '';
	for (var i = 0; i < data.length; i++) {
		result = data[i];
		url = result['link_url'];
		error_code = result['error_code'];
		error = 'Unknown Issue';
		if (error_code.toString()[0] === '3')
			error = 'Redirection';
		if (error_code.toString()[0] === '4')
			error = 'Client error';
		if (error_code.toString()[0] === '5')
			error = 'Server error';
		html += `
		<div class="result" id="r${i+1}">
			<div style="display:none" id="hid${i+1}">${url}</div>
			<div class="link-wrap">
				<a class="link" href="${url}" target="_blank">${url}</a>
				<div class="right">
					<div class="trash crawler-modal-open" id="delete-link">
						<img class="trash-icon" src="./assets/images/Delete.svg">
					</div>
					<div class="update crawler-modal-open" id="update-link">
						<p>Update Link</p>
					</div>
				</div>
			</div>
			<div class="message">
				<p>${error}, <a href="${url}" target="_blank">check website.</a></p>
				<p><a href="${mozilla_http_status_url + error_code}" target="_blank">${error_code}</a></p>
			</div>
		</div>`;
	}
	return html;
}

function populateCrawlerSeeds(data) {
	html = '';
	for (var i = 0; i < data.length; i++) {
		result = data[i];
		html += `
		<div class="result" id="r${i+1}">
			<div class="link-wrap">
				<p><span>URL:</span><a class="link" id="${result['id']}" href="${result['htmlURL']}" target="_blank">${result['htmlURL']}</a></p>
				<div class="right">
					<div class="trash crawler-modal-open" id="delete-seed">
						<img class="trash-icon" src="./assets/images/Delete.svg">
					</div>
					<div class="update crawler-modal-open" id="update-seed">
						<p>Update Seed</p>
					</div>
				</div>
			</div>
			<div class="details">
				<div class="detail-row">
					<div class="cell">
						<p><span class="label">NAME:</span>${result['text_name']}</p>
					</div>
					<div class="cell">
						<p><span class="label">TITLE:</span>${result['title']}</p>
					</div>
				</div>
				<div class="detail-row">
					<div class="cell">
						<p><span class="label">TWITTER:</span><a href="" target="_blank">${result['twitter_handle']}</a></p>
					</div>
					<div class="cell">
						<p><span class="label">RSS:</span><a href="" target="_blank">${result['xmlURL']}</a></p>
					</div>
				</div>
			</div>
		</div>`;
	}
	return html;
}

function populateCrawlerResults(data) {
	html = '';
	for (var i = 0; i < data['keywords'].length; i++) {
		result = data['keywords'][i];
		k_id = result['keyword_id'];
		tag_names = [];
		tag_ids = [];

		if ($.inArray(k_id in data['tags']) && data['tags'][k_id].length > 0) {
    		$.each(data['tags'][k_id], function (_, tag) {
    			tag_names.push(tag['tag_name']);
    			tag_ids.push(tag['tag_id']);
    		});
    	}

		html += `
			<div class="result" id="r${i+1}">
				<div class="link-name">
					<a class="name" href="${google_search_url}${result['keyword']}"target="_blank">${result['keyword']}</a>
				</div>
				<div class="link-wrap">
					<a class="link" target="_blank" href="${result['url']}">${result['url']}</a>
					<div class="update crawler-modal-open" id="update-link">
						<img class="update-icon" src="./assets/images/edit.svg"></div>`;
		if (location.href.match(/crawler/)) {
	        html += `
	        	<div class="right">
	        		<div class="trash crawler-modal-open" id="delete-link">
	        			<img class="trash-icon" src="./assets/images/Delete.svg"></div>
						<div class="add-seed">`;
			if ($.inArray(result['url'], seed_urls) >= 0) {
				html += `<p>In Seeds</p>`;
			} else {
				html += `
					<p>Add to Seeds</p>
					<form action="submit">
						<input type="hidden" name="add_seed" value="${result['url']}">
						<input type="hidden" name="name" value="${result['keyword']}">
					</form>`;
			}
			html += `
						</div>
						<div class="add-tag" id="add-tag">
							<span id="show-tag">Add Tag
								<ul id="sortmenu" data-id="${k_id}">`;
			$.each(all_tags, function(_, tag) {
				checked = '';
				if (tag_ids.length > 0 && tag_ids.includes(Number(tag['tag_id']))) {
					checked = ' checked';
				}
				html += `<li data-id="${tag['tag_id']}"><input type="checkbox"${checked}>${tag['tag_name']}</li>`
			});
			html += '</ul></span></div></div>';
	    } else {
	    	html += '<div class="right"><div class="display-tag"><span>';
	    	if(tag_names.length > 0) {
	    		html += tag_names.join(', ');
	    	}
	    	html += '</span></div></div>';
	    }
	    html += '</div></div>';
	}
	return html;
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
			var url = $(this).parent().parent().find('.link-wrap a.link').text();
			var keyword = $(this).parent().parent().find('.link-name a.name').text();
			var keyword_id = $(this).parent().parent().find('[data-id]').data('id');

			$('.'+ modalType +'-modal .keyword-id').attr('value', keyword_id);
			$('.'+ modalType +'-modal p.name').text(keyword);
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
		var keyword = $(this).find('p.name').text();
		var url = $(this).find('p.link').text();
		var id = $(this).find('.keyword-id').val();
		$('input.name-info').val(keyword);
		$('input.link-info').val(url);
		$('input.keyword-id').val(id);
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
		// }
	});

	$(".add-tag").off().click(function (e) {
		e.stopPropagation();
        $(this).find("#sortmenu").toggleClass('show');
    });

    $(".add-tag ul li").off().click(function (e) {
		keyword_tag_filter_ids = [];
		k_id = $(this).parent().data('id');
        $(this).parent().children().each(function () {
        	if ($(this).find("input[type=checkbox]").prop("checked")) {
        		keyword_tag_filter_ids.push($(this).data('id'));
        	}
        });

        $.ajax({
			type: "POST",
			url: BASE_URL + "api/getCrawlerResults",
			data: {
				'update_tags': 'ok',
				'keyword_id': k_id,
				'tag_ids': keyword_tag_filter_ids
			},
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

	//Listeners for adding result to seeds
	$('.add-seed').off().click(function(){
		$(this).find('form').submit();
	});

	$('.add-seed form').off().submit(function(e){
		e.preventDefault();

		$.ajax({
			type: "POST",
			url: BASE_URL + "api/getCrawlerResults",
			data: $(this).serialize(),
			dataType: "JSON",
			success:function(data){
				//after ajax refresh tab
				getAllSeeds();
				$('.crawler-tabs li.tabbed').trigger('click');
			},
			error:function(xhr, status, error){
				console.log(xhr.responseText);
			}
		});
	});
}
