// ~~~~~~~~~~~~~~~~~~~~~~ //
//  Search Select Boxes   //
// ~~~~~~~~~~~~~~~~~~~~~~ //

// this is used for advanced search
$(document).ready(function() {
    $('#status').select2({
        placeholder: "Select Status"
    });
    $('#origin').select2({
        placeholder: "Select Origin"
    });
    $('#sex').select2({
        placeholder: "Select Sex"
    });
    $('#occupation').select2({
        placeholder: "Select Occupation"
    });
    $('#type').select2({
        placeholder: "Select Event Type"
    });
    $('#city').select2({
        placeholder: "Select City"
    });
    $('#state').select2({
        placeholder: "Select Province,State,Colony"
    });
    $('#region').select2({
        placeholder: "Select Region"
    });
    $('#country').select2({
        placeholder: "Select Country"
    });
    $('#country').select2({
        placeholder: "Select Country"
    });
    $('.date-from').select2({
        placeholder: "From"
    });
    $('.date-to').select2({
        placeholder: "To"
    });
    $('#startYear').select2({
        placeholder: "Select or Input the Start Year"
    });
    $('#endYear').select2({
        placeholder: "Select or Input the End Year"
    });
    // $('.s2-multiple').select2();
    $('b[role="presentation"]').hide();
    $('.select2-selection--multiple').append('<span class="select2-selection__arrow" role="presentation"></span>');

    // $('.s2-multiple').on('select2:opening select2:closing', function( event ) {
    //     var $searchfield = $(this).parent().find('.select2-search__field');
    //     $searchfield.prop('disabled', true);
    // });
    $(".s2-multiple").on('select2:select', function(e){
        var id = e.params.data.id;
        var option = $(e.target).children('[value='+id+']');
        option.detach();
        $(e.target).append(option).change();
      });
});


/////////////////////////////////////////////////////////////
///// SEARCH RESULTS
///////////////////////////////////////////////////////////////

// jQuery's '.css' inserts css styles as inline-styles
// this can be problematic because it overwrites css styles applied in the stylesheet
// $(element).css('style',''); unsets these inline styles

var setView;     // load grid or table view, with # results per page from last page visit on page load
var cards;
var num_of_results;
var results_per_page = 10;
$(document).ready(function () {
    $('span.results-per-page > span').html(results_per_page)
    setView = window.localStorage.getItem('view')
    //if (!setView || setView === 'grid') {
    //    cards = false
    //    $('span.grid-view').trigger('click');
    //} else {
    //    cards = true
    //    $('span.table-view').trigger('click');
    //}
    // num_of_results = window.localStorage.getItem('display_amount')
    // if (!num_of_results) {
    //     $('span.results-per-page > span').html();
    //     $('#searchResults-showing >span:first-child').html('11');
    // } else {
    //     $('span.results-per-page > span').html(num_of_results);
    //     $('#searchResults-showing >span:first-child').html(num_of_results);
    // }
    var pageURL = $(location).attr("href");
    var urlLength = pageURL.length;
    var testString = pageURL.substring(urlLength-13,urlLength);
    if (testString == "searchResults"){
        $(".show-filter").trigger('click');
    }
    correctTableHeights()
});

$(document).click(function () { // close things with clicked-off
    $('span.results-per-page').find("img:first").removeClass('show');
    $('span.results-per-page #sortmenu').removeClass('show');
    $('span.sort-by #sortmenu').removeClass('show');
    $('span.sort-by').find("img:first").removeClass('show');
    if (window.innerWidth < 820 && filter) {
        $(".show-filter").trigger('click');
    }
});

$('div.container.main').click(function (e) {
    e.stopPropagation();
})

$(".sorting-dropdowns .align-center").click(function (e) { // toggle show/hide per-page submenu
    e.stopPropagation();
    $(this).find("img:first").toggleClass('show');
    $(this).find("#sortmenu").toggleClass('show');
});

// todo: rather than reload, just adjust results?
$("ul.results-per-page li").click(function (e) { // set the per-page value
    e.stopPropagation();
    num_of_results = $(this).find('span:first').html();
    localStorage.setItem('display_amount', num_of_results);
    location.reload();
});

var timer;
$("span.view-toggle").mouseenter(function () { // show tooltips on hover
    var that = this;
    timer = setTimeout(function(){
        $('span p.tooltip').removeClass('hovered');
        $(that).find("p.tooltip").addClass('hovered');
    }, 750);
}).mouseleave(function() {
    $('span p.tooltip').removeClass('hovered');
    clearTimeout(timer)
});


//Generates result cards
var view;
var result;

// Get the query parameters from the url and use ajax to load results
$(document).ready(function () {
    // Get params from url
    var $_GET = {};
    if(document.location.toString().indexOf('?') !== -1) {
        var query = document.location
            .toString()
            // get the query string
            .replace(/^.*?\?/, '')
            .replace(/#.*$/, '')
            .split('&');

        for(var i=0, l=query.length; i<l; i++) {
            var aux = decodeURIComponent(query[i]).split('=');
            $_GET[aux[0]] = aux[1];
        }
    }
    console.log($_GET)

    // The first key of the get params should be the type
    var type = Object.keys($_GET)[0];
    var filter = $_GET[type];

    if (typeof(filter) == "undefined"){
        filter = '';
    }

    var filters = {};
    filters[type] = filter;

    var searchBarPlaceholder = "Search Across " + filter + " Results";
    $('.main-search').attr("placeholder", searchBarPlaceholder);


    var templates = ['searchCard', 'gridCard'];

    console.log('uhh', filters)

    $.ajax({
        url: BASE_URL + "api/blazegraph",
        type: "GET",
        data: {
            preset: 'people',
            filters: filters,
            templates: templates
        },
        'success': function (data) {
            result_array = JSON.parse(data);

            console.log('wat', result_array);


            var result_length = result_array['searchCard'].length;
            searchBarPlaceholder = "Search Across " + result_length + " " + filter + " Results";
            $('.main-search').attr("placeholder", searchBarPlaceholder);

            var showingResultsText = '';

            if (result_length < results_per_page) {
                showingResultsText = "Showing " + result_length + " of " + result_length + " Results";
            } else {
                showingResultsText = "Showing " + results_per_page + " of " + result_length + " Results";

            }

            $('.showing-results').html(showingResultsText);

            appendCards();
        }
    });
});

// put the cards and grids onto the page
function appendCards(){
    console.log('here', result_array)
    result_array['searchCard'].forEach(function (card) {
        $(card).appendTo("ul.row");
    });

    result_array['gridCard'].forEach(function (card) {
        $(card).appendTo("tbody");
    });

}


$("span.grid-view").click(function gridView (e) { // grid view
    e.stopPropagation()
    console.log('call display')
    displayCards();
});

// display the people cards
function displayCards() {
    console.log('display cards')
    //$('tbody > tr').remove();
    cards = false;
    view = 'grid';
    window.localStorage.setItem('cards', cards);
    $(this).next().toggleClass("show");

    if (cards === false) {
        //$('tbody > tr').remove();
        $('.result-column').show();
        $("#search-result-configure-download-row").hide();
        $("#search-result-table").hide();
        $('span.view-toggle img').removeClass('show'); //make all view-toggle icons inactive
        $('span.view-toggle .grid-icon').addClass('show'); //make the grid-icon active
        //$('<div class="result-column"><div class="cardwrap"><ul class="row"></ul></div></div>').appendTo("div#search-result-wrap");
        result = parseInt(localStorage.getItem('display_amount'), 10)
        if (result) {
            result_array.length = result
        }

        cards = true;
        view = 'grid';
        window.localStorage.setItem('cards', cards);
        window.localStorage.setItem('view', view);
        // $('div.result-column').css('padding', '0', 'margin-top', '-30px', 'margin-bottom', '-15px');
    }
}

// display the grid view
$("span.table-view").click(function tableView (e) { // table view
    e.stopPropagation()
    if (cards === true) {
        cards = false
        window.localStorage.setItem('cards', cards)
        $('div.result-column').hide();
        //$('div.result-column').remove();
        $('div#search-result-table').show();
        $('span.view-toggle img').removeClass('show'); //make all view-toggle icons inactive
        $('span.view-toggle .table-icon').addClass('show'); //make the table-icon active
        $(this).addClass("show");
        $("span.grid-view").removeClass("show");
        $("#search-result-configure-download-row").show();
        $('table').css('width', '', 'margin', '');
        var view = 'table'
        window.localStorage.setItem('view', view)
        result = parseInt(localStorage.getItem('display_amount'), 10)
        if (result) {
            result_array.length = result
        }
        // $.each(result_array,function () {
        //     $('<tr class="tr"><td class="name td-name"><span>Name LastName</span></td><td class="gender"><p><span class="first">Gender: </span>Gndr</p></td><td class="age"><p><span class="first">Age: </span>##</p></td><td class="occupation"><p><span class="first">Occupation: </span>Fugitive Slave</p></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><a href="#">View Narrative</a></td></tr>').appendTo('tbody');
        // });
    }
});

// need to be sure the first <td> in each <tr> has a height matching the <tr>
// first <td> is positioned absolutely, so that we may scroll the table without scrolling the first <td>
// absolute positioning makes height behave differently, so this function is needed to ensure height consistency between the first table cell and its respective row
// this element is structured as : <tr> <td.name> <span> ** words go here ** </span></td>
// with 1 line of text (default), height = 13(px)
// if <element>.height > 13 {var = <element>.height; element.parent(tr).height = var}
var tr = window.document.getElementsByClassName('tr')
var td = window.document.getElementsByClassName('td-name')
function correctTableHeights () {
    if (tr){
        for (var i = 0; i < tr.length; i++) {
            // if row-height != first-cell-height OR if name-height != rowHeight
            // row height is flexable, so set that equal to the non-flexable element
            if (tr[i].offsetHeight != td[i].offsetHeight) {
                var height = tr[i].offsetHeight
                td[i].style.height = '' + height + ''
            //} else if ($('.td-name span')[i].offsetHeight != td[i].offsetHeight) {
            } else if ($('.td-name span')[i].offsetHeight > 13) {
                var height = $('.td-name span')[i].offsetHeight
                $('.td-name span')[i].style.paddingBottom = '40px'
                tr[i].style.height = '' + height + ''
            }
        }
        window.setTimeout('correctTableHeights()', 1000*1) // function reloads itself every 1 seconds
    }
}

// filter handled below here
var filter;
var tableWidth = 0;
$(".show-filter").click(function(e){ // toggle show/hide filter menu
    e.stopPropagation();
    filter = !filter;
    if (filter) {
        $("div.filter-menu").addClass("show");
        $(this).html('<img src="../assets/images/arrow-right.svg" alt="show filter menu button" style="transform:rotate(180deg);"> Hide Filter Menu');
        if ( window.innerWidth <= 820 ) {
            $("#searchResults").removeClass("show");
        } else {
            centerStuffWithFilter()
        }
    } else { // toggle off filter-menu
        $(this).html('<img src="../assets/images/arrow-right.svg" alt="show filter menu button"> Show Filter Menu');
        $('div#searchResults').css('max-width', '');
        setTimeout(function () {
            $(".filter-menu").removeClass("show");
            $('div#searchResults').css('width','');
        $("#searchResults").removeClass("show");
        }, 50);
    }
});

function centerStuffWithFilter () {
    $("#searchResults").addClass("show");
    if (window.innerWidth <= 920) {
        $('div#searchResults.show').css('width','');
        $("#searchResults").removeClass("show");
    } else {
        tableWidth = window.innerWidth - 330;
        $('div#searchResults').css('max-width', '3000px');// remove max-width property
        $('div#searchResults.show').css('width', tableWidth); // apply width
    }
}

// Hides filter menu when window is resized below 820px
// $(window).resize(function () {
//     if (filter) {
//         if(window.innerWidth <= 820){
//             $(".show-filter").html('<img src="../assets/images/arrow-right.svg" alt="show filter menu button"> Show Filter Menu');
//             filter = !filter;
//             $('div#searchResults').css('max-width', '');
//             setTimeout(function () {
//                 $(".filter-menu").removeClass("show");
//                 $('div#searchResults').css('width','');
//             $("#searchResults").removeClass("show");
//             }, 50);
//         }
//     }
// });

$(window).resize(function () { // make main content responsive when filter is visible
    if (filter) {
        setTimeout(function () {
            centerStuffWithFilter()
        }, 150);
    }
});

//Main categories
$("li.cat-cat").click(function () { // toggle show/hide filter-by submenus
    $(this).find("span:first").toggleClass("show");
    $(this).next().toggleClass("show");
});

//Sub categories
$("li.filter-cat").click(function () { // toggle show/hide filter-by submenus
    $(this).find("span:first").toggleClass("show");
    $(this).next().toggleClass("show");
});