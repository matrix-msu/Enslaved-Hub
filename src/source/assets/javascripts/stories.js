const advancedRecordOptions = {
	includeScore: true,
	useExtendedSearch: true,
	keys: ['Sex','Place','Life Events']
};
const advancedFuse = new Fuse(allStoriesRecords, advancedRecordOptions);


var limit=10;
var offset = 1;
var result = {};
var searchQuery = '';
var filters = {};
var lastCategory = '';
var sort = "latest";

var href = window.location.href
var hrefSplit = href.split('/')
var address = hrefSplit[hrefSplit.length-1]
var addressSplit = address.split('?')
var keyword = '';
var searchString = addressSplit[1]
var showFirstFilters = false;
if(searchString != undefined){
    var searches = searchString.split('&')
    var advanced = '';
    var categories = '';
    $.each(searches,function(key,value){
        var eachSearch = value.split('=')
        if(eachSearch[0] == 'searchbar'){
            keyword = eachSearch[1]
            if(keyword !== ''){
                searchQuery = keyword.replace('+',' ')
            }
        }
        if(eachSearch[0] == 'categories'){
            categories = eachSearch[1]
        }
        if(eachSearch[0] == 'sort'){
            sort = eachSearch[1].replaceAll('_', ' ');
        }
        if(eachSearch[0] == 'filters'){
			showFirstFilters = true;
            filters = JSON.parse(unescape(eachSearch[1]).replace('+', ' '));
        }
        if(eachSearch[0] == 'advanced'){
            advanced = eachSearch[1]
        }
    });
}
$(document).ready(function () {
    $('.cards li').click(function(){
        console.log("clicked");
        window.location = $(this).find("a").attr("href");
    });
    $('.pagi-first').click(function(){
        $('.num.active').click();
    });

    $('.pagi-last').click(function(){
        $('.num.active').click();
    });
    installFeaturedListeners('.featured-stories');
    search(searchQuery);
});

function search(searchQuery){
    if(searchQuery != ''){
        result = storiesFuse.search("'"+searchQuery);
    }else{
        result = returnAllRecords(allStoriesRecords);
    }
	result = advancedSearch(result, advancedFuse, filters, searchQuery);

    setPagination(result.length, limit, 0);
    sortResults();
    pickShowTypeAndCreateHtml();
    // updateUrlParams();
	createFilters();
}

function advancedSearch(searchResults, advancedFuse, groupedFilters, searchString){
	if(!$.isEmptyObject(groupedFilters)){
		var formattedFilters = [];
		for(var key in groupedFilters){
			var fieldFilters = [];
			$.each(groupedFilters[key], function(i, filter){
				var valueFilter = {};
				var searchType = '=';
				// if(filterTextFields.includes(key)){
				// 	searchType = "'";
				// }
				valueFilter[key] = searchType+'"'+filter+'"';
				fieldFilters.push(valueFilter);
			});
			formattedFilters.push({$or: fieldFilters})
		}
		formattedFilters = {$and: formattedFilters};
		var advancedJson = advancedFuse.search( formattedFilters );
		// for(let key in advancedJson)
		// 	advancedJson[key] = advancedJson[key].item;
		if(searchString != ""){
			searchResults = findIntersection(searchResults, advancedJson);
		}else{
			searchResults = advancedJson;
		}
	}
	return searchResults;
}

function findIntersection(array1, array2){
    return array1.filter(function(n) {
        for(var i=0; i < array2.length; i++){
            if(n.kid == array2[i].kid){
                return true;
            }
        }
        return false;
    });
}

function createFilters(){
	var options = {'Sex':[],'Life_Events':[],'Place':[]};
	$.each(result, function(index,item){
		var gender = item.item['Sex'];
		if(!options['Sex'].includes(gender) && gender != null)
			options['Sex'].push(gender);
		var lifeEvents = item.item['Life Events'];
		if(lifeEvents){
			$.each(lifeEvents, function(i,option){
				if(!options['Life_Events'].includes(option))
					options['Life_Events'].push(option);
			});
		}
		var places = item.item['Place'];
		if(places){
			$.each(places, function(i,option){
				if(!options['Place'].includes(option))
					options['Place'].push(option);
			});
		}
	});
	options['Sex'].sort();
	options['Life_Events'].sort();
	options['Place'].sort();
	var html = '';
	for(var cat in options){
		var show = '';
		var prettyName = cat;
		if( prettyName == 'Life_Events') prettyName = "Life Events";
		if( prettyName == 'Sex') prettyName = "Gender";
		if($(`#${cat}`).find('.align-right').hasClass('show')) show = ' show';
		if(showFirstFilters && (cat in filters || prettyName in filters)) show = ' show';
		html += `
			<li class="filter-cat" id="${cat}" name="${cat}">
			${prettyName}
			<span class="align-right${show}">
			<img src="https://enslaved.org/assets/images/chevron.svg" alt="drop arrow"></span>
			<ul id="submenu" class="${show}">`;
		$.each(options[cat], function(i,option){
			var checked = '';
			if( prettyName == 'Gender') prettyName = "Sex";
			if(prettyName in filters && filters[prettyName].includes(option)) checked = ' checked="checked"';
			html += `
				<li class="">
					<label class="${cat}">
						<input class="checkBox" type="checkbox" value="${option}" data-category="${cat}"${checked}>
						<p>${option} <em></em></p>
						<span></span>
					</label>
				</li>`;
		});
		html += `</ul></li>`;
	}
	showFirstFilters = false;
	$('#mainmenu').html(html);
}

$('.page-numbers').on('click', '.num', function(){
    pickShowTypeAndCreateHtml();
    // updateUrlParams();
})

function updateUrlParams(){
    const params = new URLSearchParams(location.search);
    if(searchQuery != ''){
        params.set('searchbar', searchQuery);
    }
    params.set('display', $('.categories').find('li.selected').attr('data-cat'));
    // params.set('fields', JSON.stringify(selected_fields));
    params.set('sort', sort.replaceAll(' ', '_'));
    params.set('offset', $('.page-numbers').find('.active').html());
    params.set('limit', limit);
    if(filters != ""){
        params.set('filters', JSON.stringify(filters));
    }else{
        params.delete('filters');
    }
    var url = location.origin+location.pathname+'?'+params.toString();
    window.history.replaceState(0, "", url);
}

function returnAllRecords(records){
    return records.map((doc, idx) => ({
      item: doc,
      score: 1,
      refIndex: idx
  }));
}

function sortResults(){
    var sortField = "Name";
    if(sort == "A - Z"){
        result = result.sort(compareAtoZ(sortField));
    }else if(sort == "Z - A"){
        result = result.sort(compareZtoA(sortField));
    }else if(sort == "Newestdate"){
        sortField = "Start Date";
        result = result.sort(compareNewdate(sortField));
    }else if(sort == "Oldestdate"){
        sortField = "Start Date";
        result = result.sort(compareOlddate(sortField));
    }else if(sort == "latest"){
        result = result.sort(compareLatest("updated_at"));
    }

}

function compareLatest(sortField) {
    return function( a, b ){
		var d1 = new Date(a.item[sortField]);
		var d2 = new Date(b.item[sortField]);

        if ( d1.getTime() > d2.getTime()){
            return -1;
        }
        return 1;
    }
}

function compareAtoZ(sortField) {
    return function( a, b ){
        if ( a.item[sortField] < b.item[sortField] ){
            return -1;
        }
        if ( a.item[sortField] > b.item[sortField] ){
            return 1;
        }
        return 0;
    }
}

function compareZtoA(sortField) {
    return function( a, b ){
        if ( a.item[sortField] > b.item[sortField] ){
            return -1;
        }
        if ( a.item[sortField] < b.item[sortField] ){
            return 1;
        }
        return 0;
    }
}

function compareNewdate(sortField) {
    return function( a, b ){

        var sortFieldA = sortField;
        var sortFieldB = sortField;
        if (a.item[sortField] == null){
            sortFieldA = "End Date";
        }
        if (b.item[sortField] == null){
            sortFieldB = "End Date";
        }

        if (a.item[sortFieldA] == null && b.item[sortFieldB] == null){
             return 0;
        }else if (a.item[sortFieldA] == null){
             return 1;
        }else if (b.item[sortFieldB] == null){
             return -1;
        }

        if ( a.item[sortFieldA].sort > b.item[sortFieldB].sort ){
            return -1;
        }
        if ( a.item[sortFieldA].sort < b.item[sortFieldB].sort ){
            return 1;
        }
        return 0;
    }
}

function compareOlddate(sortField) {
    return function( a, b ){

        var sortFieldA = sortField;
        var sortFieldB = sortField;
        if (a.item[sortField] == null){
            sortFieldA = "End Date";
        }
        if (b.item[sortField] == null){
            sortFieldB = "End Date";
        }

        if (a.item[sortFieldA] == null && b.item[sortFieldB] == null){
             return 0;
        }else if (a.item[sortFieldA] == null){
             return 1;
        }else if (b.item[sortFieldB] == null){
             return -1;
        }

        if ( a.item[sortFieldA].sort < b.item[sortFieldB].sort ){
            return -1;
        }
        if ( a.item[sortFieldA].sort > b.item[sortFieldB].sort ){
            return 1;
        }
        return 0;
    }
}

function pickShowTypeAndCreateHtml(){
    $('.card-row').empty();
    var page_num = $('.page-numbers').find('.active').html();
    var paginatedRecords = paginateResults(result, limit, page_num);
    createSearchCards(paginatedRecords);
}

function paginateResults(array, page_size, page_number) {
  return array.slice((page_number - 1) * page_size, page_number * page_size);
}

function createSearchCards(paginatedRecords){
    var searchCardHtml = '';
    var bg = ['enslaved-header-bg.jpg','enslaved-header-bg2.jpg',
            'enslaved-header-bg3.jpg','enslaved-header-bg4.jpg',
            'enslaved-header-bg5.jpg','enslaved-header-bg6.jpg',
            'enslaved-header-bg7.jpg'];
    if(paginatedRecords){
        $.each(paginatedRecords, function(index,item){
            var record = item['item'];
            var storyImage = '';
            //get images from records
            if (record["Images"] != null){
                storyImage = record["Images"][0]["url"];
            } else {
                var randomImage = bg[Math.floor(Math.random() * bg.length)];
                storyImage = BASE_IMAGE_URL+randomImage;
            }
            searchCardHtml += '<li class="card" style="background-image: url('+storyImage+')"><a href="'+BASE_URL+'fullStory/'+record.kid+'/">';
            searchCardHtml += '<h2 class="card-title">'+record['Title']+'</h2>';
            searchCardHtml += '</a><div class="overlay"></div></li>';
        });
    }
    $('.card-row').append(searchCardHtml);
}

$('form').submit(function(e){
    e.preventDefault();
    console.log('form submit')
	searchQuery = $('.search-field').val();
	var html = `
		<div class="option-wrap">
			<p>${searchQuery}</p>
		</div>`;
	if(searchQuery == '') html = '';
	$('.filter-cards').html(html);
    search(searchQuery);
});

$(document).click(function () { // close things with clicked-off
    $('span.sort-stories-text').find("img:first").removeClass('show');
    $('span.sort-stories-text').next().removeClass('show');
    $('.sort-pages p').find("img:first").removeClass('show');
    $('.sort-pages p').next().removeClass('show');
});

$(".sort-by").click(function (e) { // toggle show/hide page category submenu
    e.stopPropagation();
    $(this).find("img:first").toggleClass('show');
    $(this).find('ul').toggleClass('show');
});

$(".results-per-page").click(function (e) { // toggle show/hide per-page submenu
    e.stopPropagation();
    $(this).find("img:first").toggleClass('show');
    $(this).find('ul').toggleClass('show');
});

$("#mainmenu").on('click','.checkBox',function (e) { // toggle show/hide per-page submenu
	e.stopPropagation();
	filters = {};
	$('.checkBox').each(function(){
		if($(this)[0].checked){
			var cat = $(this).data('category');
			if(cat == "Life_Events") cat = "Life Events";
			var value = $(this).attr('value');
			if(!(cat in filters)){
				filters[cat] = [];
			}
			filters[cat].push(value);
		}
	});
	searchQuery = $('.search-field').val();
	search(searchQuery);
});

$("#mainmenu").on('click','li',function (e) { // toggle show/hide per-page submenu
    e.stopPropagation();
    $(this).find(".align-right").toggleClass('show');
    $(this).find('ul').toggleClass('show');
});

$('.count-option').click(function(e){
    limit = $(this).find('span').html();
    $('.per-page-text').html(limit);
    $('.pagi-first').click();
    setPagination(result.length, limit, 0);
});

$('.sort-option').click(function(e){
    sort = $(this).attr('data-sort');
    sortResults();
    pickShowTypeAndCreateHtml();
    $('.pagi-first').click();
});
