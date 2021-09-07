var limit = 10;
var offset = 1;
var result = {};
var searchQuery = '';
var filters = '';
var lastCategory = '';
var sort = "A - Z";

var href = window.location.href
var hrefSplit = href.split('/')
var address = hrefSplit[hrefSplit.length-1]
var addressSplit = address.split('?')
var keyword = '';
var searchString = addressSplit[1]
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
    setPagination(result.length, limit, 0);
    sortResults();
    pickShowTypeAndCreateHtml();
    // updateUrlParams();
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
    var sortField = "Title";
    if(sort == "A - Z"){
        result = result.sort(compareAtoZ(sortField));
    }else if(sort == "Z - A"){
        result = result.sort(compareZtoA(sortField));
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

function pickShowTypeAndCreateHtml(){
    $('#AllStoriesContainer').empty();
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
    $('#AllStoriesContainer').append(searchCardHtml);
}

$('form').submit(function(e){
    e.preventDefault();
    console.log('form submit')
    searchQuery = $('.search-field').val();
    search(searchQuery);
});

$(document).click(function () { // close things with clicked-off
    $('span.sort-stories-text').find("img:first").removeClass('show');
    $('span.sort-stories-text').next().removeClass('show');
    $('.sort-pages p').find("img:first").removeClass('show');
    $('.sort-pages p').next().removeClass('show');
});

$("span.sort-stories-text").click(function (e) { // toggle show/hide page category submenu
    e.stopPropagation();
    $(this).find("img:first").toggleClass('show');
    $(this).next().toggleClass('show');
});

$(".sort-pages p").click(function (e) { // toggle show/hide per-page submenu
    e.stopPropagation();
    $(this).find("img:first").toggleClass('show');
    $(this).next().toggleClass('show');
});

$('.count-option').click(function(){
    limit = $(this).find('span').html();
    $('.categories').find('li.selected').click();
});
