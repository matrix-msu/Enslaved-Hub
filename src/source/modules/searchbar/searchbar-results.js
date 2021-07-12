var setView; // load grid or table view, with # results per page from last page visit on page load
var cards;
var num_of_results;
$(document).ready(function() {
    $('span.results-per-page > span').html(result_array.length);
    setView = window.localStorage.getItem('view');
    if (!setView || setView === 'grid') {
        cards = false;
        $('span.grid-view').trigger('click');
    } else {
        cards = true
        $('span.table-view').trigger('click');
    }
    num_of_results = window.localStorage.getItem('display_amount')
    if (!num_of_results) {
        $('span.results-per-page > span').html('11');
        $('#searchResults-showing >span:first-child').html('11');
    } else {
        $('span.results-per-page > span').html(num_of_results);
        $('#searchResults-showing >span:first-child').html(num_of_results);
    }
    $(".show-filter").trigger('click');
    correctTableHeights()
});

$(document).click(function() { // close things with clicked-off
    $('span.results-per-page').find("img:first").removeClass('show');
    $('span.results-per-page').next().removeClass('show');
    $('span.sort-by').next().removeClass('show');
    $('span.sort-by').find("img:first").removeClass('show');
    if (window.innerWidth < 820 && filter) {
        $(".show-filter").trigger('click');
    }
});

$('div.container.main').click(function(e) {
    e.stopPropagation();
})

$("span.align-center").click(function(e) { // toggle show/hide per-page submenu
    e.stopPropagation();
    $(this).find("img:first").toggleClass('show');
    $(this).next().toggleClass('show');
});

$("ul.results-per-page li").click(function(e) { // set the per-page value
    e.stopPropagation();
    num_of_results = $(this).find('span:first').html();
    localStorage.setItem('display_amount', num_of_results);
    location.reload();
});

var timer
$("span.view-toggle").mouseenter(function() { // show tooltips on hover
    var that = this
    timer = setTimeout(function() {
        $('span p.tooltip').removeClass('hovered');
        $(that).find("p.tooltip").addClass('hovered');
    }, 750);
}).mouseleave(function() {
    $('span p.tooltip').removeClass('hovered');
    clearTimeout(timer)
});

var view
var result
result_array = []
result_array.length = 11
$("span.grid-view").click(function gridView(e) { // grid view
    e.stopPropagation()
    if (cards === false) {
        $('tbody > tr').remove();
        $("#search-result-configure-download-row").hide();
        $("#search-result-table").hide();
        $('span.view-toggle img.hide').show();
        $('span.view-toggle img.show').hide();
        $('<div class="column"><div class="cardwrap"><ul class="row"></ul></div></div>').appendTo("div#search-result-wrap");
        result = parseInt(localStorage.getItem('display_amount'), 10)
        if (result) {
            result_array.length = result
        }
        $.each(result_array, function() {
            $('<li><a><div class="container cards"><p class="card-title">Person Name or Narrative Title</p><p><span>Gender</span>: Male</p><p><span>Born</span>: fl.1845 Virginia, United States</p><p><span>Occupation</span>: Fugitive Slave, Slave Narrative, Occupation..</p><a class="card-learn-more">View Narrative</a></div></a></li>').appendTo("ul.row");
        });
        cards = true
        view = 'grid'
        window.localStorage.setItem('cards', cards)
        window.localStorage.setItem('view', view)
        $('div.column').css('padding', '0', 'margin-top', '-30px', 'margin-bottom', '-15px');
    }
});

$("span.table-view").click(function tableView(e) { // table view
    e.stopPropagation()
    if (cards === true) {
        cards = false
        window.localStorage.setItem('cards', cards)
        $('div.column').remove();
        $('div#search-result-table').show();
        $('span.view-toggle img.hide').hide();
        $('span.view-toggle img.show').show();
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
        $.each(result_array, function() {
            $(
                '<tr class="tr"><td class="name td-name"><span>Name LastName</span></td><td class="gender"><p><span class="first">Gender: </span>Gndr</p></td><td class="age"><p><span class="first">Age: </span>##</p></td><td class="occupation"><p><span class="first">Occupation: </span>Fugitive Slave</p></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><a href="#">View Narrative</a></td></tr>'
            ).appendTo('tbody');
        });
    }
});

// need to be sure the first <td> in each <tr> has a height matching the <tr>
// first <td> is positioned absolutely, so that we may scroll the table without scrolling the first <td>
// absolute positioning makes height behave differently, so this function is needed to ensure height consistency between the first table cell and its respective row
// this element is structured as : <tr> <td.name> <span> ** words go here ** </span></td>
// with 1 line of text (default), height = 13(px)
// if <element>.height > 13 {var = <element>.height; element.parent(tr).height = var}
var tr = window.document.getElementsByClassName('tr');
var td = window.document.getElementsByClassName('td-name');

function correctTableHeights() {
    if (tr) {
        for (var i = 0; i < tr.length; i++) {
            // if row-height != first-cell-height OR if name-height != rowHeight
            // row height is flexable, so set that equal to the non-flexable element
            if (tr[i].offsetHeight != td[i].offsetHeight) {
                var height = tr[i].offsetHeight;
                td[i].style.height = '' + height + '';
                //} else if ($('.td-name span')[i].offsetHeight != td[i].offsetHeight) {
            } else if ($('.td-name span')[i].offsetHeight > 13) {
                var height = $('.td-name span')[i].offsetHeight;
                $('.td-name span')[i].style.paddingBottom = '40px';
                tr[i].style.height = '' + height + '';
            }
        }
        window.setTimeout('correctTableHeights()', 1000 * 1); // function reloads itself every 1 seconds
    }
}

// filter handled below here
var filter;
var tableWidth = 0;
$(".show-filter").click(function(e) { // toggle show/hide filter menu
    e.stopPropagation();
    filter = !filter;
    if (filter) {
        $("div.filter-menu").addClass("show");
        $(this).html('<img src="assets/images/arrow-right.svg" alt="show filter menu button" style="transform:rotate(180deg);"> Hide Filter Menu');
        if (window.innerWidth <= 820) {
            $("#searchResults").removeClass("show");
        } else {
            centerStuffWithFilter();
        }
    } else { // toggle off filter-menu
        $(this).html('<img src="assets/images/arrow-right.svg" alt="show filter menu button"> Show Filter Menu');
        $('div#searchResults').css('max-width', '');
        setTimeout(function() {
            $(".filter-menu").removeClass("show");
            $('div#searchResults').css('width', '');
            $("#searchResults").removeClass("show");
        }, 50);
    }
});

$('div.filter-menu').click(function(e) {
    e.stopPropagation();
});

function centerStuffWithFilter() {
    $("#searchResults").addClass("show");
    if (window.innerWidth <= 820) {
        $('div#searchResults.show').css('width', '');
        $("#searchResults").removeClass("show");
    } else {
        tableWidth = window.innerWidth - 330
        $('div#searchResults').css('max-width', '3000px'); // remove max-width property
        $('div#searchResults.show').css('width', tableWidth); // apply width
    }
}

$(window).resize(function() { // make main content responsive when filter is visible
    if (filter) {
        setTimeout(function() {
            centerStuffWithFilter();
        }, 150);
    }
});

$("li.filter-cat").click(function() { // toggle show/hide filter-by submenus
    $(this).find("span:first").toggleClass("show");
    $(this).next().toggleClass("show");
});

// ~~~~~~~~~~ //
// PAGINATION //
// ~~~~~~~~~~ //

var page = 1;
var _pages = $('span.pagi-last').html();
var pages = parseInt(_pages);
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
$('span#pagiRight').click(function(e) {
    e.stopPropagation();
    if (+page === +pages) {
        $('span#pagiRight').css('opacity', '0.25', 'cursor', 'not-allowed');
    } else {
        page = +page + 1;
        paginate();
    }
});
$('span.dotsRight').click(function(e) {
    e.stopPropagation();
    if (+pages - +page < 10) {
        return;
    } else {
        page = +page + 10;
        paginate();
    }
});
$('span#pagiLeft').click(function(e) {
    e.stopPropagation();
    if (+page === 1) {
        $('span#pagiLeft').css('opacity', '0.25', 'cursor', 'not-allowed');
    } else {
        page = +page - 1;
        paginate();
    }
});
$('span.dotsLeft').click(function(e) {
    e.stopPropagation();
    if (+page - 10 < 0) { // this check, and the other +10 check may not be needed
        return; // since the dots are hidden at instances when they
    } else { // would normally break the pagination
        page = +page - 10;
        paginate();
    }
});
$('span.num').click(function(e) {
    e.stopPropagation();
    page = $(this).html(); // set page
    paginate();
});

function paginate() {
    if (+page === 1) {
        $('span#pagiLeft').css('opacity', '0.25', 'cursor', 'not-allowed');
        $('span#pagiRight').css('opacity', '', 'cursor', '');
        $('span.dotsLeft').hide();
        $('span.dotsRight').show();
        $('span.pagi-first').show();
        $('span.pagi-first').html(page);
        $('span.one').html(2);
        $('span.two').html(3);
        $('span.three').html(4);
        $('span.four').html(5);
        $('span.five').html(6);
        $('span.num').removeClass('active');
        $('span.pagi-first').addClass('active');
    } else if (+page > 1 && +page <= 10) {
        $('span#pagiLeft').css('opacity', '', 'cursor', '');
        $('span.dotsLeft').show();
        $('span.pagi-first').show();
        $('span.pagi-first').html(1);
        $('span.one').html(page);
        $('span.two').html(+page + 1);
        $('span.three').html(+page + 2);
        $('span.four').html(+page + 3);
        $('span.five').html(+page + 4);
        $('span.num').removeClass('active');
        $('span.one').addClass('active');
    } else if (+pages - +page > 10) {
        $('span#pagiLeft').css('opacity', '', 'cursor', '');
        $('span#pagiRight').css('opacity', '', 'cursor', '');
        $('span.pagi-first').show();
        $('span.dotsRight').show();
        $('span.one').html(+page - 1);
        $('span.two').html(page);
        $('span.three').html(+page + 1);
        $('span.four').html(+page + 2);
        $('span.five').html(+page + 3);
        $('span.num').removeClass('active');
        $('span.two').addClass('active');
    } else if (+pages - +page === 10) {
        $('span#pagiRight').css('opacity', '', 'cursor', '');
        $('span.dotsRight').hide();
        $('span.dotsLeft').show();
        $('span.one').html(+page - 2);
        $('span.two').html(+page - 1);
        $('span.three').html(page);
        $('span.four').html(+page + 1);
        $('span.five').html(+page + 2);
        $('span.num').removeClass('active');
        $('span.three').addClass('active');
    } else if (+pages - +page === 9) {
        $('span.one').html(+page - 3);
        $('span.two').html(+page - 2);
        $('span.three').html(+page - 1);
        $('span.four').html(page);
        $('span.five').html(+page + 1);
        $('span.num').removeClass('active');
        $('span.four').addClass('active');
    } else if (+pages - +page === 8) {
        $('span.one').html(+page - 4);
        $('span.two').html(+page - 3);
        $('span.three').html(+page - 2);
        $('span.four').html(+page - 1);
        $('span.five').html(page);
        $('span.num').removeClass('active');
        $('span.five').addClass('active');
    } else if (+pages - +page < 8 && +page != +pages) {
        $('span.one').html(+page - 4);
        $('span.two').html(+page - 3);
        $('span.three').html(+page - 2);
        $('span.four').html(+page - 1);
        $('span.five').html(page);
    } else if (+page === +pages) {
        $('span#pagiLeft').css('opacity', '', 'cursor', '', 'opacity', '0.25', 'cursor', 'not-allowed');
        $('span.one').html(+page - 5);
        $('span.two').html(page - 4);
        $('span.three').html(+page - 3);
        $('span.four').html(+page - 2);
        $('span.five').html(+page - 1);
        $('span.num').removeClass('active');
        $('span.pagi-last').addClass('active');
    }
}
