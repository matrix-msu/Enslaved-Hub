///******************************************************************* */
/// Global and _GET variables and ajax function to get and set initial HTML for cards
///******************************************************************* */

//Global vars used on whole page
var view;
var result_array;
var total_length = 0;
var card_offset = 0;
var card_limit = 12;
var page = 1;
var pages = 1;
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
 * \param presets : Array of presets that determines type of query call (ex: 'person', 'event')
 * \param limit : limit to the number of cards per page : default value = 12
 * \param offset : number of cards offset from the first card (with 0 being the first card) : default value = 0
*/
function searchResults(preset, limit = 12, offset = 0){
    filters['limit'] = limit;
    card_limit = limit;
    filters['offset'] = offset;
    card_offset = offset;

    var templates = ['searchCard', 'gridCard'];

    $.ajax({
        url: BASE_URL + "api/blazegraph",
        type: "GET",
        data: {
            preset: preset,
            filters: filters,
            templates: templates
        },
        'success': function (data) {
            console.log(data);
            result_array = JSON.parse(data);
            
            console.log(result_array);

            var result_length = result_array['searchCard'].length;
            total_length = result_array['total'];

            searchBarPlaceholder = "Search Across " + total_length + " " + filter + " Results";
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
                setPagination();
            });
            
        }
    });
}

///******************************************************************* */
/// Append Cards
///******************************************************************* */

function appendCards(){
    $("ul.row").empty(); //empty row before appending more
    result_array['searchCard'].forEach(function (card) {
        $(card).appendTo("ul.row");
    });

    $("tbody").empty(); //empty grid before appending more
    result_array['gridCard'].forEach(function (card) {
        $(card).appendTo("tbody");
    });

}

///******************************************************************* */
/// Pagination functions
///******************************************************************* */

function setPagination() {
    pages = Math.ceil(total_length / card_limit);
    page = Math.ceil(card_offset / card_limit) + 1;

    $('span.pagi-last').html(pages); //last pagination number to number of pages
    console.log("Test:" + total_length + ' ' + card_offset + ' ' + card_limit);

    if (pages < 2) { // sets pagination on page load
        $('span#pagiLeft').css('opacity', '0.25', 'cursor', 'not-allowed');
        $('span#pagiRight').css('opacity', '0.25', 'cursor', 'not-allowed');
        $('span.dotsLeft').hide();
        $('span.dotsRight').hide();
        $('span.pagi-first').hide();
        $('span.one').hide();
        $('span.two').hide();
        $('span.three').hide();
        $('span.four').hide();
        $('span.five').hide();
        $('span.pagi-last').hide();
    } else {
        $('span.dotsLeft').hide();
        $('span.dotsRight').hide();
        $('span.pagi-first').show();
        $('span.pagi-first').html(1);
        $('span.pagi-last').hide();
        $('span.one').hide();
        $('span.two').hide();
        $('span.three').hide();
        $('span.four').hide();
        $('span.five').hide();
        $('span.one').show();
        $('span.one').html(2);

        if (pages > 2) {
            $('span.two').show();
            $('span.two').html(3);
        }
        if (pages > 3) {
            $('span.three').show();
            $('span.three').html(4);
        }
        if (pages > 4) {
            $('span.four').show();
            $('span.four').html(5);
        }
        if (pages > 5) {
            $('span.five').show();
            $('span.five').html(6);
        }
        if (pages > 6) {
            $('span.dotsRight').show();
            $('span.pagi-last').show();
        }


        $('span#pagiLeft').css('opacity', '0.25', 'cursor', 'not-allowed');
        $('span#pagiRight').css('opacity', '', 'cursor', '');
        $('span.num').removeClass('active');
        $('span.pagi-first').addClass('active');
    }
    paginate();
}

function paginate() {
    if (page === 1) {

        $('span.dotsLeft').hide();
        // $('span.dotsRight').hide();
        $('span.pagi-first').show();
        $('span.pagi-first').html(1);
        $('span.one').hide();
        $('span.two').hide();
        $('span.three').hide();
        $('span.four').hide();
        $('span.five').hide();
        $('span.one').show();
        $('span.one').html(2);

        if (pages > 2) {
            $('span.two').show();
            $('span.two').html(3);
        }
        if (pages > 3) {
            $('span.three').show();
            $('span.three').html(4);
        }
        if (pages > 4) {
            $('span.four').show();
            $('span.four').html(5);
        }
        if (pages > 5) {
            $('span.five').show();
            $('span.five').html(6);
        }
        if (pages > 6) {
            $('span.dotsRight').show();
            $('span.pagi-last').show();
        }

        $('span#pagiLeft').css('opacity', '0.25', 'cursor', 'not-allowed');
        $('span#pagiRight').css('opacity', '', 'cursor', '');
        $('span.num').removeClass('active');
        $('span.pagi-first').addClass('active');

    } else if (page >= 2 && page <= 5) {
        $('span.dotsLeft').hide();
        // $('span.dotsRight').hide();
        $('span.pagi-first').show();
        $('span.pagi-first').html(1);
        $('span.one').hide();
        $('span.two').hide();
        $('span.three').hide();
        $('span.four').hide();
        $('span.five').hide();
        $('span.one').show();
        $('span.one').html(2);

        if (pages > 2) {
            $('span.two').show();
            $('span.two').html(3);
        }
        if (pages > 3) {
            $('span.three').show();
            $('span.three').html(4);
        }
        if (pages > 4) {
            $('span.four').show();
            $('span.four').html(5);
        }
        if (pages > 5) {
            $('span.five').show();
            $('span.five').html(6);
        }
        if (pages > 6) {
            $('span.dotsRight').show();
            $('span.pagi-last').show();
        }

        if(page == pages){
            $('span#pagiRight').css('opacity', '0.25', 'cursor', 'not-allowed');
        }else{
            $('span#pagiRight').css('opacity', '', 'cursor', '');
        }
        $('span#pagiLeft').css('opacity', '', 'cursor', '');
        $('span.num').removeClass('active');
        $('#pagination').find('.num').eq(page - 1).addClass('active');
    } else if (pages - page >= 5) {
        $('span#pagiLeft').css('opacity', '', 'cursor', '');
        $('span#pagiRight').css('opacity', '', 'cursor', '');
        $('span.pagi-first').show();
        $('span.dotsLeft').show();
        $('span.dotsRight').show();
        $('span.one').html(page - 2);
        $('span.two').html(page - 1);
        $('span.three').html(page);
        $('span.four').html(page + 1);
        $('span.five').html(page + 2);
        $('span.num').removeClass('active');
        $('span.three').addClass('active');
    } else if (pages - page < 6 && page != pages) {
        $('span#pagiLeft').css('opacity', '', 'cursor', '');
        $('span#pagiRight').css('opacity', '', 'cursor', '');
        $('span.one').html(pages - 5);
        $('span.two').html(pages - 4);
        $('span.three').html(pages - 3);
        $('span.four').html(pages - 2);
        $('span.five').html(pages - 1);
        $('span.num').removeClass('active');
        $('#pagination').find('.num').eq(6 - (pages - page)).addClass('active');
        $('span.dotsLeft').show();
        $('span.dotsRight').hide();
    } else if (page === pages) {
        if(page == 6){
            //Special case where there are 6 pages and the active is the last page

            $('span.dotsLeft').hide();
            // $('span.dotsRight').hide();
            $('span.pagi-first').show();
            $('span.pagi-first').html(1);
            $('span.one').hide();
            $('span.two').hide();
            $('span.three').hide();
            $('span.four').hide();
            $('span.five').hide();
            $('span.one').show();
            $('span.one').html(2);
    
            if (pages > 2) {
                $('span.two').show();
                $('span.two').html(3);
            }
            if (pages > 3) {
                $('span.three').show();
                $('span.three').html(4);
            }
            if (pages > 4) {
                $('span.four').show();
                $('span.four').html(5);
            }
            if (pages > 5) {
                $('span.five').show();
                $('span.five').html(6);
            }
            $('span#pagiLeft').css('opacity', '', 'cursor', '');
            $('span#pagiRight').css('opacity', '0.25', 'cursor', 'not-allowed');
            $('span.num').removeClass('active');
            $('#pagination').find('.num.five').addClass('active');
        }
        else{
            //Last page and greater than 6
            $('span#pagiLeft').css('opacity', '', 'cursor', '');
            $('span#pagiRight').css('opacity', '0.25', 'cursor', 'not-allowed');
            $('span.dotsRight').hide();
            $('span.dotsLeft').show();
            $('span.one').html(page - 5);
            $('span.two').html(page - 4);
            $('span.three').html(page - 3);
            $('span.four').html(page - 2);
            $('span.five').html(page - 1);
            $('span.num').removeClass('active');
            $('span.pagi-last').addClass('active');
        }
    }
}

//Generate cards
searchResults('people');

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
        searchResults(['people'], card_limit, card_offset);
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
            if (result) {
                result_array.length = result
            }
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


    ///******************************************************************* */
    /// PAGINATION HANDLERS
    ///******************************************************************* */

    $('span#pagiRight').click(function(e) {
        // e.stopPropagation();
        if (page === pages) {
            $('span#pagiRight').css('opacity', '0.25', 'cursor', 'not-allowed');
        } else {
            $('.num.active').nextAll('.num').first().click();
        }
    });
    $('span.dotsRight').click(function(e) {
        // e.stopPropagation();
        if (pages - page < 10) {
            return;
        } else {
            page = page + 10;
            paginate();
            $('.num.active').click();
        }
    });
    $('span#pagiLeft').click(function(e) {
        // e.stopPropagation();
        if (page === 1) {
            $('span#pagiLeft').css('opacity', '0.25', 'cursor', 'not-allowed');
        } else {
            $('.num.active').prevAll('.num').first().click();
        }
    });
    $('span.dotsLeft').click(function(e) {
        // e.stopPropagation();
        if (page - 10 < 0) { // this check, and the other +10 check may not be needed
            return; // since the dots are hidden at instances when they
        } else { // would normally break the pagination
            page = page - 10;
            paginate();
            $('.num.active').click();
        }
    });
    $('span.num').click(function(e) {
        // e.stopPropagation();
        page = $(this).html(); // set page
        page = parseInt(page);
        searchResults('people', card_limit, (page - 1) * card_limit);
        paginate();
    });

});

