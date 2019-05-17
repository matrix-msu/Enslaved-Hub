var templates = ['searchCard', 'gridCard'];
var filters = {};
var offset = 0;
var limit = 10;

$(document).ready(function() {
    // close things with clicked-off
    $(document).click(function () {
        $('span.results-per-page').find("img:first").removeClass('show');
        $('span.results-per-page #sortmenu').removeClass('show');
        $('span.sort-by #sortmenu').removeClass('show');
        $('span.sort-by').find("img:first").removeClass('show');
        if (window.innerWidth < 820 && filter) {
            $(".show-filter").trigger('click');
        }
    });

    // Stop propagating click event to parent or children of main
    $('div.container.main').click(function (e) {
        e.stopPropagation();
    })
    // Toggle sorting and per-page menu
    $(".sorting-dropdowns .align-center").click(function (e) {
        e.stopPropagation();
        $(this).find("img:first").toggleClass('show');
        $(this).find("#sortmenu").toggleClass('show');
    });
    // show tooltips on hover (on top of icons)
    var timer;
    $("span.view-toggle").mouseenter(function () {
        var that = this;
        timer = setTimeout(function(){
            $('span p.tooltip').removeClass('hovered');
            $(that).find("p.tooltip").addClass('hovered');
        }, 750);
    }).mouseleave(function() {
        $('span p.tooltip').removeClass('hovered');
        clearTimeout(timer)
    });

    // Handles click on a table row (redirects page to url attach to last hidden column of the row)
    $("#search-result-table").on("click", "tbody > tr", function() {
        let link = $(this).find("a").attr("href");
        if(link) window.location.href = link;
    });

});
/**************************************************************************************************************/
// FILTER
$(document).ready(function() {
    // toggle show/hide filter menu
    var filter = false;
    var tableWidth = 0;
    $(".show-filter").click(function(e){
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
    // make main content responsive when filter is visible
    $(window).resize(function () {
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
    
    //Trigger filter to show on page load
    var pageURL = $(location).attr("href");
    if (pageURL.includes("search")){
        $(".show-filter").trigger("click");
    }
});
/**************************************************************************************************************/
// Toggle view formats (Grid and Table)
$(document).ready(function(){
    // Grid view
    $("span.grid-view").click(function tableView (e) {
        e.stopPropagation();

        $('.result-column').show();
        $("#search-result-configure-download-row").hide();
        $("#search-result-table").hide();
        $('span.view-toggle img').removeClass('show'); //make all view-toggle icons inactive
        $('span.view-toggle .grid-icon').addClass('show'); //make the grid-icon active
    });

    // Table view
    $("span.table-view").click(function tableView (e) {
        e.stopPropagation();

        $('div.result-column').hide();
        $('div#search-result-table').show();
        $('span.view-toggle img').removeClass('show'); //make all view-toggle icons inactive
        $('span.view-toggle .table-icon').addClass('show'); //make the table-icon active
        $(this).addClass("show");
        $("span.grid-view").removeClass("show");
        $("#search-result-configure-download-row").show();
        $('table').css('width', '', 'margin', '');
    });

    // Show cards onload
    $("span.grid-view").trigger("click");
});
/**************************************************************************************************************/
$(document).ready(function(){
    /*
        Get project information and display in views (replace placeholders in views)
        Info includes, project name, description, contribution, and link to project site
    */
    $.ajax({
        url: BASE_URL+"api/getProjectFullInfo",
        type: "GET",
        data: {qid: QID},
        success: function (data) {
            data = JSON.parse(data);
            $(".project-headers > h1").html(data.title.value);
            $("#current-title").html(data.title.value);
            $(".container.infowrap").html(data.desc.value);
            if ('link' in data) {
                $('#details').click(function () {
                    document.location.href = data.link.value;
                });
            }
            else {
                $('.project-button').hide();
            }
            var pis = data.piNames.value.split("||");
            pis.forEach(function(name) {
                $('.leads').append('<div class="lead-card"><div class="lead-text"><h3>'+name+'</h3></div></div>');
            });
            if ('contributor' in data) {
                var contributors = data.contributor.value.split("||");
                contributors.forEach(function(name) {
                    $('.contributors').append('<div class="contributor-card"><div class="contributor-text"><h3>'+name+'</h3></div></div>');
                });
            }
        }
    });

    /*
        Get resources number to be displayed next to the title (aka project name)
        Data shows how much people, events, places, and sources are associated with the project
    */
    $.ajax({
        url: BASE_URL+"api/blazegraph",
        type: "GET",
        data: {preset: 'projectAssoc', templates: ['projectAssoc'], qid: QID},
        success: function (data) {
            data = JSON.parse(data);
            var str = "";
            data['projectAssoc'].forEach(function (e) {
                str += e;
            });
            $(".project-headers > h2").html(str);
        }
    });

    // Get data
    getProjectData(templates, filters, offset, limit);

    
});

/* 
    Get data (return data embedded in html format both in grid (cards) and table view (table))
    Replace blazegraph-records class innerHTML content with cards returned
    Replace search-result-table class innerHTML content with table returned
*/
function getProjectData(templates, filters, offset, limit)
{
    $.ajax({
        url: BASE_URL + "api/blazegraph",
        type: "GET",
        data: {
            preset: 'singleProject',
            filters: filters,
            templates: templates,
            limit: limit,
            offset: offset,
            qid: QID
        },
        success: function (data) {
           if(data)
           {
                data = JSON.parse(data);
                gridDisplay(data['gridCard']);
                tableDisplay(data['searchCard']);
           }
        }
    });
}

function gridDisplay(data)
{
    // Display cards
    $(".blazegraph-records").html("");
    data.forEach(function (card) {
        $(card).appendTo(".blazegraph-records");
    });

    $("span.grid-view").trigger("click");
}

function tableDisplay(data)
{
    // Display table rows
    $("tbody").html("");
    data.forEach(function (card) {
        $(card).appendTo("tbody");
    });
}