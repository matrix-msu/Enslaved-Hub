///******************************************************************* */
/// Global and _GET variables and ajax function to get and set initial HTML for cards
///******************************************************************* */

//Global vars used on whole page
var search_type = JS_EXPLORE_FORM.toLowerCase();
var view;
var result_array;
var total_length = 0;
var card_offset = 0;
var card_limit = 12;
var filters = {};
var display = search_type;
var firstLoad = true;

var has_data = false;
var selected_fields_people = ['Name', 'Sex', 'Person Status', 'Place', 'Date'];
var selected_fields_events = ['Name', 'Event Type', 'Source Type', 'Date', 'Place Type', 'Place'];
var selected_fields_places = ['Name', 'Project', 'Location', 'Place Type'];
var selected_fields_source = ['Name', 'Project', 'Source Type'];
var sort_field = "label.sort";

var projects = {
    'Hutchins Center for African & African American Research': 'Hutchins',
    'Legacies of British Slave-ownership': 'Legacies',
    'Louisiana Slave Database': 'Louisiana',
    'Free Blacks Database': 'Free',
    'Voyages: The Trans-Atlantic Slave Trade Database': 'Voyages',
    'Maranhão Inventories Slave Database' : 'Maranhão'
};

if (search_type == "all"){
    display = 'people';
}

var filtersToSearchType = {
    'people' : ['people', 'event', 'place', 'source', 'project'],
    'events' : ['event', 'place', 'source', 'project'],
    'places' : ['place', 'source', 'project'],
    'sources' : ['source', 'project']
};

var sort_map = {
    'name': 'name.raw',
    'sex': 'sex.raw',
    'person status': 'person_status.raw',
    'place': 'place.raw',
    'date': 'date',
    'role': 'participant_role.raw',
    'event': 'event_type.raw',
    'event type': 'event_type.raw',
    'place type': 'place_type.raw',
    'source type': 'source_type.raw',
    'ethnodescriptor': 'ethnodescriptor.raw',
    'occupation': 'occupation.raw',
    'project': 'generated_by.raw',
    'location': 'located_in.raw',
    'occurs before': 'occurs_before',
    'occurs after': 'occurs_after',
    'circa': 'circa',

}

var showPath = false;
var upperForm = "";
var titleType = "";
var currentTitle = "Search";

var fields = [];    // fields for the table view
var sort = ''; // or desc

var address = document.location.toString().split('/')
var category = address[address.length - 1].split('?')[0]
if(category == 'people' || category == 'events' || category == 'places' || category == 'sources'){
    $(".search-title").html('<h1>'+category.charAt(0).toUpperCase()+category.substr(1).toLowerCase()+'</h1>')
    $("h1").css('line-height', 'normal')
    $(".heading-search").empty()
}

$( ".page-numbers" ).click(function() {
    scrollToTop();
});

function scrollToTop(){
    $(window).scrollTop(320);
}

// Get params from url
if(document.location.toString().indexOf('?') !== -1)
{
    var query = document.location
        .toString()
        // get the query string
        .replace(/^.*?\?/, '')
        .replace(/#.*$/, '')
        .split('&');
    for(var i=0; i < query.length; i++)
    {
        var aux = decodeURIComponent(query[i]).split('=');
        if(!aux || aux[0] == "" || aux[1] == "") continue;
        aux[1] = decodeURIComponent(aux[1].replace(/\+/g, ' '));

        if (aux[0] == "display"){
            display = aux[1];
            continue;
        }

        if (typeof(filters[aux[0]]) == 'undefined'){
            filters[aux[0]] = []
        }

        // Get searchbar keywords
        if(aux[0] == "searchbar")
        {
            filters[aux[0]] = aux[1].split('+');
            continue;
        }
        filters[aux[0]] = filters[aux[0]].concat(aux[1].split(','));
        // Delete sort from url after refresh
        if(aux[0] == "sort"){
          delete filters[aux[0]];
        }
    }
}

function showDisplayType(){
    $categoryTabs = $('.categories').find('li');
    $categoryTabs.each(function(){
        $(this).removeClass('selected');
        if ($(this).attr('id') == display){
            $(this).addClass('selected');
        }
    });
}



/**
 * Takes parameters for an ajax call that sets result_array to an array with
 * the array of Grid View cards html, array of Table View cards html, and the total amount of results found
 * instead of taking in parameters it references global variables
 *
 * \param presets : Array of presets that determines type of query call (ex: 'person', 'event') (singular right now)
 * \param limit : limit to the number of cards per page : default value = 12
 * \param offset : number of cards offset from the first card (with 0 being the first card) : default value = 0
*/

var isSearching  = false;

function searchResults(preset, limit = 20, offset = 0)
{
    if(isSearching) return;
    isSearching = true;
    filters['limit'] = limit;
    card_limit = limit;
    filters['offset'] = offset;
    card_offset = offset;
    if(sort != ''){
      filters['sort'] = sort;
    }
    var templates = ['gridCard', 'tableCard'];
    var selected_fields = [selected_fields_people, selected_fields_events, selected_fields_places, selected_fields_source];
    generateFilterCards();

    $.ajax({
        url: BASE_URL + "api/keywordSearch",
        type: "GET",
        data: {
            preset: preset,
            filters: filters,
            templates: templates,
            display: display,
            fields: selected_fields,
            sort_field: sort_field
        },
        'success': function (data) {
            isSearching = false;
            result_array = JSON.parse(data);

            if (preset == "all"){
                var allCounters = result_array['total'];
                var firstTypeWithResults = '';
                for (var type in filtersToSearchType){
                    var counter = allCounters[type+"count"]["value"];
                    if (firstTypeWithResults == '' && counter > 0){
                        firstTypeWithResults = type;
                    }
                    var $tab = $('.categories #'+type);
                    $tab.find('span').html(counter+" ");
                    if (counter <= 0){
                        $tab.hide();
                    } else {
                        $tab.show();
                    }
                }
                if (firstTypeWithResults != '' && firstLoad){
                    firstLoad = false;
                    $('.categories #'+firstTypeWithResults).click();
                }



                total_length = allCounters[display+"count"]["value"];
                // todo:
                // filter counters need to update too
                // also filters are not working for all
            } else {
                total_length = JSON.parse(result_array['total']);
            }

            var showingResultsText = '';
            if (total_length < card_limit) {
                showingResultsText = "Showing " + total_length + " of " + total_length + " Results";
            } else {
                showingResultsText = "Showing " + (card_limit+offset) + " of " + total_length + " Results";
            }
            $('.showing-results').html(showingResultsText);

            if (total_length <= 0){
                // clear old results
                $("ul.cards").empty();
                $("thead").empty();
                $("tbody").empty();

                // Hide pagination and configure table
                $("#pagination").hide();
                $('.connect-row').css('display', 'none');
                has_data = false;
                return;
            }
            has_data = true;

            if (typeof (result_array['fields']) != 'undefined') {
                fields = result_array['fields'];
            }

            searchBarFilter = filters != undefined ? filters : '';
            searchBarPlaceholder = "Search Across "  + total_length + " Results";
            $('.main-search').attr("placeholder", searchBarPlaceholder);

            // Toggling visualization link
            $('#view_visual').hide();
            console.log(filters)
            if ('projects' in filters && filters['projects'].length === 1 && filters['projects'][0] in projects) {
                $('#view_visual').show();
            }

            //Wait till doc is ready
            $(document).ready(function(){
                appendCards();
                setPagination(total_length, card_limit, card_offset);
                $.ajax({
                    url: BASE_URL + "api/searchFilterCounts",
                    type: "GET",
                    data: {
                        search_type: display,
                        filters: filters,
                        filter_types: filtersToSearchType[display]
                    },
                    'success': function (data) {
                        var allCounters = JSON.parse(data);
                        fillFilterCounters(allCounters);
                    }
                });
            });
        }
    });
}


// fill in the counters next to the filters
function fillFilterCounters(allCounters){
    $(".filter-cat li").each(function(){
        $(this).addClass("hide-category");
    });
    $.each(allCounters, function (type, data) {
        $.each(data, function (category, fields) {
            $.each(fields, function (label, count) {
                if (label != "") {
                    if (category == 'Modern Countries') {
                        var $input = $(`[data-countryCode='${label}'][data-category='${category}']`);
                    } else {
                        var $input = $(`input[value='${label}'][data-category='${category}']`);
                    }
                    var $counter = $input.next().find('em');
                    $counter.html('(' + count + ')');

                    if (count > 0){
                        var $li = $input.parent().parent();
                        $li.removeClass('hide-category')
                    }
                }
            });
        });
    });
}



///******************************************************************* */
/// Append Cards
///******************************************************************* */

function appendCards()
{
    // return;
    $("ul.cards").empty(); //empty row before appending more
    result_array['gridCard'].forEach(function (card) {
        $(card).appendTo("ul.cards");
    });

    $("thead").empty(); //empty headers before adding them
    var headers = result_array['tableCard']['headers'];
    $(headers).appendTo("thead");

    $("tbody").empty(); //empty grid before appending more
    for (var key in result_array['tableCard']){
        if (key != 'headers'){
            var card = result_array['tableCard'][key];
            $(card).appendTo("tbody");
        }
    }
}

//Generate cards
searchResults(search_type);


//Document load
$(document).ready(function() {
    hideFilterCategories();

    // hide filter categories based on hierarchy in filtersToSearchType
    function hideFilterCategories(){
        $filterCats = $(".cat-cat");

        $filterCats.each(function () {
            $category = $(this);
            $catFilters = $category.next();
            $category.hide();
            $catFilters.hide();
            var catType = $category.html().toLowerCase();

            if (typeof(filtersToSearchType[display]) != 'undefined' && filtersToSearchType[display].includes(catType)){
                $category.show();
                $catFilters.show();
            }
        });
    }

    ///******************************************************************* */
    /// Set Filter Checkboxes / Category Headers
    ///******************************************************************* */

    //For form type
    upperForm = JS_EXPLORE_FORM.charAt(0).toUpperCase() + JS_EXPLORE_FORM.slice(1);

    if(upperForm.toString() == 'All'){
        $( ".categories" ).html( "<ul>"+
                                    "<li class='unselected selected' id='people'><div class='person-image'></div><span class='count'></span>People</li>"+
                                    "<li class='unselected' id='events'><div class='event-image'></div><span class='count'></span>Events</li>"+
                                    "<li class='unselected' id='places'><div class='place-image'></div><span class='count'></span>Places</li>"+
                                    "<li class='unselected' id='sources'><div class='source-image'></div><span class='count'></span>Sources</li>"+
                                    "<hr></ul>" );
    }else if(upperForm == 'People'){
        $( ".categories" ).html( "<ul>"+
                                    "<li class='unselected selected' id='people'><div class='person-image'></div>People</li>"+
                                    "<hr></ul>" );
        $( ".categories ul" ).css("overflow-x", "hidden")
    }else if(upperForm == 'Events'){
        $( ".categories" ).html( "<ul>"+
                                    "<li class='unselected selected' id='events'><div class='event-image'></div>Events</li>"+
                                    "<hr></ul>" );
        $( ".categories ul" ).css("overflow-x", "hidden")
    }else if(upperForm == 'Places'){
        $( ".categories" ).html( "<ul>"+
                                    "<li class='unselected selected' id='places'><div class='place-image'></div>Places</li>"+
                                    "<hr></ul>" );
        $( ".categories ul" ).css("overflow-x", "hidden")
    }else if(upperForm == 'Sources'){
        $( ".categories" ).html( "<ul>"+
                                    "<li class='unselected selected' id='sources'><div class='source-image'></div>Sources</li>"+
                                    "<hr></ul>" );
        $( ".categories ul" ).css("overflow-x", "hidden")
    }

    $(".filter-menu ul.catmenu li").each(function(){
        if("categories" in filters && filters["categories"].length > 0)
        {
            if(filters["categories"].indexOf( $(this).find("p").text().toUpperCase() ) > -1)
                $(this).find("input").prop('checked', true);
        }

        //Check a checkbox if EXPLORE_FORM is set to this type
        else if( $(this).find("p").text() == upperForm){
            $(this).find("input").prop('checked', true);
        }

        //Set all checkboxes to checked
        else if(upperForm === 'All'){
            $(this).find("input").prop('checked', true);
        }
    });

    showDisplayType();


    // Show selected
    $.each(filters, function(key, values)
    {
        if(key && key != "limit" && key != "offset") // inputs lable have classes with name as key
        {
            $("label."+key).each(function()
            {
                var that = this;
                if (!Array.isArray(values)){
                    var temp = [values];
                    values = temp;
                }

                $.each(values, function(indx, value){
                    //Looks for input where value = value
                    if($(that).find('input').val() == value) {
                        $(that).find("input").prop("checked", true);
                    }
                    if($(that).parent().parent().parent().attr('name') == 'date'){
                        $(that).parent().parent().parent().find("span:first").addClass("show");
                        $(that).parent().parent().parent().find("ul#submenu").addClass("showdate");
                    }
                    else{
                        $(that).parent().parent().parent().find("span:first").addClass("show");
                        $(that).parent().parent().parent().find("ul#submenu").addClass("show");
                    }
                });
            });
        }
    });


    $('.categories li').on('click', function(){
        var clickedType = $(this).attr('id');
        display = clickedType;
        hideFilterCategories();
        card_offset = 0; //reset offset to 0 when changing results-per-page to go to first page
        $("ul.cards").empty();
        $("thead").empty();
        $("tbody").empty();
        searchResults(search_type);

        showDisplayType();
    });
    ///******************************************************************* */
    /// Event Handlers for the page
    ///******************************************************************* */

    //Change in current-page input so call searchResults function
    $('#pagination .current-page').change(function(){
        var val = $('#pagination .current-page').val();
        //Call searchResults normally except calculate new offset
        searchResults(search_type, card_limit, (val - 1) * card_limit);
    });

    //SearchBar placeholder text
    var searchBarPlaceholder = "Search Across " + filters[0] + " Results";

    if (typeof(filters[0]) != 'undefined'){
        $('.main-search').attr("placeholder", searchBarPlaceholder);
    }

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

    $('span.results-per-page > span').html(card_limit);
    $("ul.sort-by li").click(function (e) { // set the per-page value
        e.stopPropagation();
        sort_by_temp = $(this).find('span:first').html();
        card_offset = 0; //reset offset to 0 when changing results-per-page to go to first page
        if (sort_by_temp == "A - Z"){
          sort = "asc";
        }
        if (sort_by_temp == "Z - A"){
          sort = "desc";
        }
        searchResults(search_type, 12, 0);
        $('span.sort-by > span').html(sort);
        $(document).trigger('click');
    });
    var table = document.getElementById("search-results");
    var thead = table.getElementsByTagName("thead")[0];
    //sorting by headers
    thead.onclick = (function (e) {
       e.stopPropagation();
       e = e || window.event;
       var th = e.target || e.srcElement;  //assumes there are no other elements in the th
       var header = th.className;
       sort_field = sort_map[header];

       //Switch sort direction when clicked
       if(sort == ""){
         sort = "asc";
       }
       else if(sort == "asc"){
         sort = "desc";
       }
       else if(sort == "desc"){
         sort = "asc";
       }

       searchResults(search_type, 12, 0);
    });

    $("ul.results-per-page li").click(function (e) { // set the per-page value
        e.stopPropagation();
        card_limit = parseInt($(this).find('span:first').html());
        localStorage.setItem('display_amount', card_limit);
        card_offset = 0; //reset offset to 0 when changing results-per-page to go to first page
        searchResults(search_type, card_limit, card_offset);
        $('span.results-per-page > span').html(card_limit);
        $(document).trigger('click');
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

    var cards;
    // Display the Grid View
    $("span.grid-view").click(function gridView (e) { // grid view
        e.stopPropagation();
        var $connectRow = $('.connect-row');
        $connectRow.css('display', 'none'); //toggle display for download button
        //$('tbody > tr').remove();
        cards = false;
        view = 'grid';
        window.localStorage.setItem('cards', cards);
        $(this).next().toggleClass("show");

        if (cards === false) {
            //$('tbody > tr').remove();
            $('.result-column').show();
            $("#search-result-table").hide();
            $('span.view-toggle img').removeClass('show'); //make all view-toggle icons inactive
            $('span.view-toggle .grid-icon').addClass('show'); //make the grid-icon active
            //$('<div class="result-column"><div class="cardwrap"><ul class="row"></ul></div></div>').appendTo("div#search-result-wrap");
            result = parseInt(localStorage.getItem('display_amount'), 10)
            // if (result) {
            //     result_array.length = result
            // }
            cards = true;
            view = 'grid';
            window.localStorage.setItem('cards', cards);
            window.localStorage.setItem('view', view);
            // $('div.result-column').css('padding', '0', 'margin-top', '-30px', 'margin-bottom', '-15px');
        }
    });

    // Display the Table View
    $("span.table-view").click(function tableView (e) { // table view
        e.stopPropagation()
        if(has_data) {
          var $connectRow = $('.connect-row');
          $connectRow.css('display', 'flex'); //toggle display for download button
        }
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
            $('table').css('width', '', 'margin', '');
            var view = 'table'
            window.localStorage.setItem('view', view)
            result = parseInt(localStorage.getItem('display_amount'), 10)
            // if (result) {
            //     result_array.length = result
            // }
            // $.each(result_array,function () {
            //     $('<tr class="tr"><td class="name td-name"><span>Name LastName</span></td><td class="gender"><p><span class="first">Gender: </span>Gndr</p></td><td class="age"><p><span class="first">Age: </span>##</p></td><td class="occupation"><p><span class="first">Occupation: </span>Fugitive Slave</p></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><a href="#">View Narrative</a></td></tr>').appendTo('tbody');
            // });
        }
    });

    //not sure what this does exactly
    // need to be sure the first <td> in each <tr> has a height matching the <tr>
    // first <td> is positioned absolutely, so that we may scroll the table without scrolling the first <td>
    // absolute positioning makes height behave differently, so this function is needed to ensure height consistency between the first table cell and its respective row
    // this element is structured as : <tr> <td.name> <span> ** words go here ** </span></td>
    // with 1 line of text (default), height = 13(px)
    // if <element>.height > 13 {var = <element>.height; element.parent(tr).height = var}
    var tr = window.document.getElementsByClassName('tr')
    var td = window.document.getElementsByClassName('td-name')
    function correctTableHeights() {
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
            //window.setTimeout('correctTableHeights()', 1000*1) // function reloads itself every 1 seconds
        }
    }
    //correctTableHeights();

    // load grid or table view, with # results per page from last page visit on page load
    var setView = window.localStorage.getItem('view');
    if (!setView || setView === 'grid') {
       cards = false;
       $('span.grid-view').trigger('click');
    } else {
       cards = true;
       $('span.table-view').trigger('click');
    }
    // num_of_results = window.localStorage.getItem('display_amount')
    // if (!num_of_results) {
    //     $('span.results-per-page > span').html();
    //     $('#searchResults-showing >span:first-child').html('11');
    // } else {
    //     $('span.results-per-page > span').html(num_of_results);
    //     $('#searchResults-showing >span:first-child').html(num_of_results);
    // }


    ///******************************************************************* */
    /// Filter
    ///******************************************************************* */

    var filter = false;
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

    $("#view_visual").click(function(e){
        e.preventDefault();
        if ('projects' in filters && filters['projects'].length === 1) {
            window.location = BASE_URL + `visualizedata?type=dash&field=ps&proj=${projects[filters['projects'][0]]}`;
        }
    });

    function centerStuffWithFilter () {
        $("#searchResults").addClass("show");
        if (window.innerWidth <= 920) {
            $('div#searchResults.show').css('width','');
            $("#searchResults").removeClass("show");
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
                centerStuffWithFilter();
            }, 150);
        }
    });

    //Main categories (always showing now)
    // $("li.cat-cat").each(function(){
    //   $(this).find("span:first").toggleClass("show");
    //     $(this).next().toggleClass("show");
    // });
    // $("li.cat-cat").click(function () { // toggle show/hide filter-by submenus
    //     $(this).find("span:first").toggleClass("show");
    //     $(this).next().toggleClass("show");
    // });



    $('#date-go-btn').on('click', function() {
        var startYear = $('#startyear')[0].value;
        var endYear = $('#endyear')[0].value;
        var dateString = startYear+"-"+endYear;

        if (dateString != "-"){
            filters["date"] = [dateString];
            updateURL();
        }
    });


    //Sub categories
    $("li.filter-cat").click(function () { // toggle show/hide filter-by submenus
        //For drawers that shouldn't fold on click
        $("input").click(function() {
           if ($(this).attr("class") == 'nofold'){
               return false;
           }
        });
        //Date requires exception
        if($(this).attr('name') == 'date'){
            $(this).find("span:first").toggleClass("show");
            $(this).find("ul#submenu").toggleClass("showdate");
        }
        else{
            $(this).find("span:first").toggleClass("show");
            $(this).find("ul#submenu").toggleClass("show");
        }
    });
     //Trigger filter to show on page load
    var pageURL = $(location).attr("href");
    if (pageURL.includes("search")){
        $(".show-filter").trigger("click");
    }

    // searchbar
    $(".search-form").submit(function(e) {
        e.preventDefault();
        // Get search key and value
        var pparam = decodeURIComponent($(this).serialize());
        var splitParam = pparam.split('=');
        splitParam[1] = splitParam[1].replace(/\+/g, ' ');
        filters[splitParam[0]] = splitParam[1].split(' ');

        // update views
        $(".search-title h1").text(splitParam[1]);
        $(".last-page-header #current-title").text("//" + splitParam[1]);
        $(this).find("input").val("");

        // update URL
        var url_address = document.location.href;
        var split_address = url_address.split('?');
        url_address = split_address[0] + '?';

        var counter = 0;
        $.each(filters, function(key, value)
        {
            if(key && value && key != "limit" && key != "offset")
            {
                if(!counter) url_address += key + '=' + value;
                else url_address += '&' + key + '=' + value;
                ++counter;
            }
        });
        window.history.replaceState(0, "", url_address);

        // make ajax request
        searchResults(search_type);
    });


    // click filters
    $(document).on("change", "input[type=checkbox]", function()
    {
        // get filter value and key
        var input_value = $(this).parent().find('p').text();
        let em = $(this).parent().find('p').find("em").text();
        input_value = input_value.replace(em, "").trim();

        // var input_value = $(this).val(); //Changed to check value of checkbox which will be QID

        var input_key = $(this).parent().attr("class");

        // handle categories
        if(input_key == "category")
        {
           var categories = [];
            $(".filter-menu ul.catmenu li").each(function()
            {
                if($(this).find("input").is(":checked"))
                {
                    categories.push($(this).find('p').text());
                }
            });

            input_value = categories;
            input_key = "categories";
        }

        // Add/Remove param from filter
        if(input_key == "categories") filters[input_key] = input_value;
        else if($(this).is(":checked"))
        {
            if(input_key in filters)
            {
                if(filters[input_key].indexOf(input_value) < 0) filters[input_key].push(input_value);
            }
            else filters[input_key] = [input_value];
        }
        // Remove from params
        else if(input_key in filters)
        {
            filters[input_key] = filters[input_key].filter(function(value, index, arr) { return value != input_value; });
            if(filters[input_key].length == 0) delete filters[input_key];
        }

        updateURL();
    });
});

function updateURL(){
    var page_url = document.location.href;

    // Split all parameter
    var split_url = page_url.split('?');
    if("categories" in filters)
    {
        var split_paths = split_url[0].split('/');
        var path = split_paths[split_paths.length - 1];

        if(filters["categories"].length == 1)
        {
            showPath = true;
            upperForm = filters["categories"][0];
            titleType = "";
            currentTitle = "Search";

            search_type = filters["categories"][0].toLowerCase();
            // One category path
            split_url[0] = split_url[0].replace('/' + path, '/' +  filters["categories"][0].toLowerCase());
            delete filters["categories"];
        }
        else if(filters["categories"].length == 5)
        {
            showPath = false;
            search_type = "all";
            // All categories are selected
            split_url[0] = split_url[0].replace('/' + path, '/all');
            delete filters["categories"];
        }
        else // multiple categorise selected
        {
            showPath = false;
            search_type = "categories";
            split_url[0] = split_url[0].replace('/' + path, '/category');
        }
    }
    page_url = split_url[0]+"?";

    // Show path on top of page before title
    if(showPath)
    {
        $(".last-page-header").show();

        if(upperForm != "") $(".last-page-header .prev1 span").text(upperForm).show();
        else $(".last-page-header .prev1 span").hide();

        if(titleType != "") $(".last-page-header .prev2 span").text("//" + titleType).show();
        else $(".last-page-header .prev2 span").hide();

        if(currentTitle != "") $(".last-page-header #current-title").text("//" + currentTitle).show();
        else $(".last-page-header #current-title").hide();

    } else $(".last-page-header").hide();

    // updating url
    var counter = 0;
    $.each(filters, function(key, value)
    {
        if(key && value && key != "limit" && key != "offset")
        {
            if(!counter) page_url += key + '=' + value;
            else page_url += '&' + key + '=' + value;
            ++counter;
        }
    });

    var newstate = (history.state || 0) + 1; // Can also passed data as state objects
    window.history.replaceState(newstate, "", page_url);
    // document.location = page_url; // reload the page to new url

    // make an ajax call now with the new filters
    searchResults(search_type);
}


/*
    Adds a filter card to the filter-cards section
*/
function addFilterCard(filterCategory, filterName){
    var filterHtml = '<div class="option-wrap" id="'+ filterCategory +'"><p>'+ filterName +'</p><img class="remove" src="'+ BASE_IMAGE_URL + 'x-dark.svg' +'" /></div>';
    $('div.filter-cards').append(filterHtml);
}
/*
    Generates the cards for each Filter selected
*/
function generateFilterCards(){
    //Clear filters
    $('div.filter-cards').empty();

    //Generate filters
    $.each(filters, function(key, values)
    {
        if(key && values && key != "limit" && key != "offset" && key != "display")
        {

            if (!Array.isArray(values)){
                temp = [values];
                values = temp;
            }

            //Add filter cards
            $.each(values, function(indx, value)
            {
                addFilterCard(key, value);
            });
        }
    });

    //Click x on filter-cards
    $('.filter-cards .option-wrap img.remove').click(function(){
        var fcat = $(this).parent().attr('id');
        var fname = $(this).parent().find('p').text();
        if(fcat == "sort"){
          delete filters[fcat];
          sort = '';
        }
        else{
          filters[fcat] = filters[fcat].filter(function(value, index, arr) { return value != fname; });
          if(filters[fcat].length == 0) delete filters[fcat];
        }

        $(this).parent().remove();

        updateURL();
        //Trigger the selector in the filter side menu
        $('label.'+fcat+' input[value="'+fname+'"]').trigger('click');
    });
}

var showFilter = 0;
//check window size and display/hide filter-menu
function mediaMode() {
    if($(window).innerWidth() > 600) {
        $('.filter-menu').addClass('show');
    } else {
        $('.filter-menu.show').removeClass('show');
    }
}
//fire function
mediaMode();
//check window size
$(window).bind('resize',function(){
    mediaMode();
});

//change text for Filter Menu
$(".show-menu").click(function(){
    $(".filter-menu").toggleClass('show');
    if ($('#showfilter').text() == 'Show Filter Menu'){
        $('#showfilter').text('Hide Filter Menu')
        showFilter = 1;
    }
    else {
        $('#showfilter').text('Show Filter Menu')
        showFilter = 0;
    }
});

//hide filter on non-filter click
$('div').not('.filter-menu').mouseup(function() {
    if(showFilter == 1){
        $(".filter-menu").toggleClass('show');
        $('#showfilter').text('Show Filter Menu')
        showFilter = 0;
    }
});

//link to record page for table view
$("#search-results tbody").on('click','tr', function(event){
    window.location = $(this).attr('data-url');
});

//submit button in configure tables modals
$('.update-columns-button').click(function(e) {
    category = $('.selected').attr('id');
    if (category == 'people'){
      selected_fields_people = selected_columns;
    }if (category == 'events'){
      selected_fields_events = selected_columns;
    }if (category == 'places'){
      selected_fields_places = selected_columns;
    }if (category == 'source'){
      selected_fields_source = selected_columns;
    }
    // selected_fields = selected_items;
    closeModal();
    searchResults(search_type);
})
