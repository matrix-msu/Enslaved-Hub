///******************************************************************* */
/// Global and _GET variables and ajax function to get and set initial HTML for cards
///******************************************************************* */

//Global vars used on whole page
var view;
var result_array;
var total_length = 0;
var card_offset = 0;
var card_limit = 12;
var presets = {};
var filters = {};

// Get params from url
var $_GET = {};
var $_GET_length = 0;
if(document.location.toString().indexOf('?') !== -1) {
    var query = document.location
        .toString()
        // get the query string
        .replace(/^.*?\?/, '')
        .replace(/#.*$/, '')
        .split('&');

    for(var i=0; i < query.length; i++) {
        var aux = decodeURIComponent(query[i]).split('=');
        $_GET[aux[0]] = aux[1];
        $_GET_length++;
    }
}

// Set all the filters from the URL
for(var i=0; i < $_GET_length; i++){
    var type = Object.keys($_GET)[i];
    var filter = $_GET[type];

    if (typeof(filter) == "undefined"){
        filter = '';
    }
    filters[type] = filter;
    console.log("Filter: " + type + " = " + filter);
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
function searchResults(preset, limit = 12, offset = 0){
    filters['limit'] = limit;
    card_limit = limit;
    filters['offset'] = offset;
    card_offset = offset;

    var templates = ['gridCard', 'tableCard'];

    $.ajax({
        url: BASE_URL + "api/blazegraph",
        type: "GET",
        data: {
            preset: preset,
            filters: filters,
            templates: templates
        },
        'success': function (data) {
            // console.log(data);
            result_array = JSON.parse(data);
            
            console.log(result_array);

            var result_length = result_array['gridCard'].length;
            total_length = result_array['total'];

            searchBarFilter = filter != undefined ? filter : '';
            searchBarPlaceholder = "Search Across " + total_length + " " + searchBarFilter + " Results";
            $('.main-search').attr("placeholder", searchBarPlaceholder);

            var showingResultsText = '';
            if (result_length < card_limit) {
                showingResultsText = "Showing " + result_length + " of " + total_length + " Results";
            } else {
                showingResultsText = "Showing " + card_limit + " of " + total_length + " Results";
            }
            $('.showing-results').html(showingResultsText);

            //Wait till doc is ready
            $(document).ready(function(){
                appendCards();
                setPagination(total_length, card_limit, card_offset);
            });
            
        }
    });
}

///******************************************************************* */
/// Append Cards
///******************************************************************* */

function appendCards(){
    $("ul.row").empty(); //empty row before appending more
    result_array['gridCard'].forEach(function (card) {
        $(card).appendTo("ul.row");
    });

    $("tbody").empty(); //empty grid before appending more
    result_array['tableCard'].forEach(function (card) {
        $(card).appendTo("tbody");
    });

}

//Generate cards
searchResults('events');

//Document load
$(document).ready(function() {
    ///******************************************************************* */
    /// Set Filter Checkboxes
    ///******************************************************************* */
    
    //For form type
    var upperForm = JS_EXPLORE_FORM.charAt(0).toUpperCase() + JS_EXPLORE_FORM.slice(1);
    $(".filter-menu ul.catmenu li").each(function(){
        if( $(this).find("p").html() === upperForm){
            //Check a checkbox if EXPLORE_FORM is set to this type
            $(this).find("input").prop('checked', true);
        }
        else if(upperForm === 'All'){
            //Set all checkboxes to checked
            $(this).find("input").prop('checked', true);
        }
    });
    $(".filter-menu ul#mainmenu").each(function(){
        $(this).find(".filter-cat").each(function(){
            if($(this).attr("name") === "gender"){
                $(this).find("input").prop('checked', true);
            }
        });
        // if( $(this).find("p").html() === upperForm){
        //     //Check a checkbox if EXPLORE_FORM is set to this type
        //     $(this).find("input").prop('checked', true);
        // }
        // else if(upperForm === 'All'){
        //     //set all checkboxes to checked
        //     $(this).find("input").prop('checked', true);
        // }
    });

    //Put setting of other filters here



    ///******************************************************************* */
    /// Event Handlers for the page
    ///******************************************************************* */

    //Change in current-page input so call searchResults function
    $('#pagination .current-page').change(function(){
        var val = $('#pagination .current-page').val();
        console.log("Value: " + val);
        //Call searchResults normally except calculate new offset
        searchResults('people', card_limit, (val - 1) * card_limit);
    });

    //SearchBar placeholder text
    var searchBarPlaceholder = "Search Across " + filters[0] + " Results";
    $('.main-search').attr("placeholder", searchBarPlaceholder); 

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
    $("ul.results-per-page li").click(function (e) { // set the per-page value
        e.stopPropagation();
        card_limit = $(this).find('span:first').html();
        localStorage.setItem('display_amount', card_limit);
        card_offset = 0; //reset offset to 0 when changing results-per-page to go to first page
        searchResults('people', card_limit, card_offset);
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
                centerStuffWithFilter();
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

    //Trigger filter to show on page load
    var pageURL = $(location).attr("href");
    if (pageURL.includes("search")){
        $(".show-filter").trigger("click");
    }

});