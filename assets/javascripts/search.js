// USED for Advance Search (Not sure what advance search, so I'm going to leave it as it is)
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
    
    $('b[role="presentation"]').hide();
    $('.select2-selection--multiple').append('<span class="select2-selection__arrow" role="presentation"></span>');

    $(".s2-multiple").on('select2:select', function(e){
        var id = e.params.data.id;
        var option = $(e.target).children('[value='+id+']');
        option.detach();
        $(e.target).append(option).change();
      });
    
    /**************************************************************************************************************/
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
// PAGINATION 
$(document).ready(function(){
    var page = 1
    var _pages = $('span.pagi-last').html();
    var pages = parseInt(_pages)
    var num = document.getElementsByClassName('num')
    if (+page === 1) { // sets pagination on page load
        $('span#pagiLeft').css('opacity', '0.25', 'cursor', 'not-allowed');
        $('span.dotsLeft').hide();
        $('span.pagi-first').addClass('active');
        $('span.one').html(2);
        $('span.two').html(3);
        $('span.three').html(4);
        $('span.four').html(5);
        $('span.five').html(6);
    }
    $('span#pagiRight').click(function (e) {
        e.stopPropagation();
        if (+page === +pages) {
            $('span#pagiRight').css('opacity', '0.25', 'cursor', 'not-allowed');
        } else {
        page = +page + 1;
        paginate();
        }
    });
    $('span.dotsRight').click(function (e) {
        e.stopPropagation();
        if (+pages - +page < 10) {
            page = +pages;
            paginate();
        } else {
        page = +page + 10;
        paginate();
        }
    });
    $('span#pagiLeft').click(function (e) {
        e.stopPropagation();
        if (+page === 1) {
            $('span#pagiLeft').css('opacity', '0.25', 'cursor', 'not-allowed');
        } else {
        page = +page - 1;
        paginate();
        }
    });
    $('span.dotsLeft').click(function (e) {
        e.stopPropagation();
        if (+page - 10 < 0) {
            page = 1;
            paginate();
        } else {
        page = +page - 10;
        paginate();
        }
    });
    $('span.num').click(function (e) {
        e.stopPropagation();
        page = $(this).html(); // set page
        paginate();
    });

    function paginate () {
        if (+page === 1) {
            $('span#pagiLeft').css('opacity', '0.25', 'cursor', 'not-allowed');
            $('span#pagiRight').css('opacity', '', 'cursor', '');
            $('span.dotsLeft').hide();
            $('span.dotsRight').show();
            $('span.pagi-first').show();
            $('span.pagi-first').html(1);
            $('span.one').html(2);
            $('span.two').html(3);
            $('span.three').html(4);
            $('span.four').html(5);
            $('span.num').removeClass('active');
            $('span.pagi-first').addClass('active');
        } else if (+page > 1 && +page <= 10) {
            $('span#pagiLeft').css('opacity','','cursor','');
            $('span.dotsLeft').show();
            $('span.pagi-first').show();
            $('span.one').html(page);
            $('span.two').html(+page + 1);
            $('span.three').html(+page + 2);
            $('span.four').html(+page + 3);
            $('span.num').removeClass('active');
            $('span.one').addClass('active');
        } else if (+pages - +page > 10) {
            $('span#pagiLeft').css('opacity', '', 'cursor', '');
            $('span#pagiRight').css('opacity', '', 'cursor', '');
            $('span.pagi-first').show();
            $('span.dotsLeft').show();
            $('span.dotsRight').show();
            $('span.one').html(+page - 1);
            $('span.two').html(page);
            $('span.three').html(+page + 1);
            $('span.four').html(+page + 2);
            $('span.num').removeClass('active');
            $('span.two').addClass('active');
        } else if (+pages - +page === 10) {
            $('span#pagiRight').css('opacity', '', 'cursor', '');
            $('span.dotsRight').show();
            $('span.dotsLeft').show();
            $('span.one').html(+page - 2);
            $('span.two').html(+page - 1);
            $('span.three').html(page);
            $('span.four').html(+page + 1);
            $('span.num').removeClass('active');
            $('span.three').addClass('active');
        } else if (+pages - +page === 9) {
            $('span#pagiRight').css('opacity', '', 'cursor', '');
            $('span.dotsRight').show();
            $('span.dotsLeft').show();
            $('span.one').html(+page - 3);
            $('span.two').html(+page - 2);
            $('span.three').html(page);
            $('span.four').html(+page + 1);
            $('span.num').removeClass('active');
            $('span.three').addClass('active');
        } else if (+pages - +page === 8) {
            $('span#pagiRight').css('opacity', '', 'cursor', '');
            $('span.dotsRight').show();
            $('span.dotsLeft').show();
            $('span.one').html(+page - 3);
            $('span.two').html(+page - 2);
            $('span.three').html(+page - 1);
            $('span.four').html(page);
            $('span.num').removeClass('active');
            $('span.four').addClass('active');
        } else if (+pages - +page < 8 && +page != +pages) {
            $('span#pagiRight').css('opacity', '', 'cursor', '');
            $('span.dotsRight').show();
            $('span.dotsLeft').show();
            $('span.one').html(+page - 3);
            $('span.two').html(+page - 2);
            $('span.three').html(+page - 1);
            $('span.four').html(page);
            $('span.num').removeClass('active');
            $('span.four').addClass('active');
        } else if (+page === +pages) {
            $('span#pagiLeft').css('opacity', '', 'cursor', '');
            $('span#pagiRight').css('opacity', '0.25', 'cursor', 'not-allowed');
            $('span.dotsRight').hide();
            $('span.dotsLeft').show();
            $('span.one').html(+page - 4);
            $('span.two').html(page - 3);
            $('span.three').html(+page - 2);
            $('span.four').html(+page - 1);
            $('span.num').removeClass('active');
            $('span.pagi-last').addClass('active');
        }
    }
});


function removeEmpty() {
    var form = $('form');
    var allInputs = form.find('input');
    var allSelects = form.find('select');
    var input,select, i, j;

    for(i = 0; input = allInputs[i]; i++) {
        if(input.getAttribute('name') && !input.value) {
            input.setAttribute('name', '');
        }
    }
    for(j = 0; select = allSelects[j]; j++) {
        if(select.getAttribute('name') && !select.value) {
            select.setAttribute('name', '');
        }
    }
}