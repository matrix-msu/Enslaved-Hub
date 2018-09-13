// ~~~~~~~~~~~~~~~~~~ //
// Search Query Cards //
// ~~~~~~~~~~~~~~~~~~ //

// check for searched terms in localstorage on page load
$(document).ready(function(){
    search_term = window.localStorage.getItem('searched_terms')
    if (search_term && location.href.match(/searchResults/)) {
        search_term = search_term.split(',')
        localStorage.removeItem('searched_terms')
        createCards (search_term)
    }
});

// collect searchInput values
var search_term
var term_card
var padding_left = 0
var search_field = window.document.getElementById('search-field')
$('.search-submit').click(function () {
    search_term = search_field.value
    search_term = search_term.split(',')
    localStorage.setItem('searched_terms', search_term);
    // redirect to search results page, if not already there
    if (!location.href.match(/searchResults/)) {
        location.href = "" + BASE_URL + "searchResults.php"
    }
    search_field.value = ''
    createCards (search_term)
});

// listen for user pressing 'enter'
// change this to jquery
$('#search-field').keyup(function (e) {
    e.stopPropagation();
    if (e.keyCode == 13) {
        $(".search-submit").trigger('click');
    }
});

var createCards = function (terms) {
    if (terms.length === 1) {
        // append the term to the div, append div to parent element
        $("<div class='searched-term'>" + terms + "<img class='close' src='" + BASE_URL + "assets/images/x.svg' alt='close button'></div>").appendTo("div.search-field");
    } else if (terms.length > 1) {
        for (var i = 0; i < terms.length; i++) {
            // append the term to the div, append div to parent element
            $("<div class='searched-term'>" + terms[i] + "<img class='close' src='" + BASE_URL + "assets/images/x.svg' alt='close button'></div>").appendTo("div.search-field");
        }
    }
    setPositioning ()
}

var setPositioning = function () {
    term_card = window.document.getElementsByClassName('searched-term')
    if (term_card.length != 0) {
        search_field.placeholder = 'Add another search term here'   
    } else {
        search_field.placeholder = 'Search for whatever it is you want in life'
    }
    setPadding ()
}

var setPadding = function () {
    padding_left = 0
    if (term_card.length > 0) {
        $('div.search-field').css('padding-left', padding_left);
    } else {
        $('div.search-field').css('padding-left', '');
    }
}

$('form').on('click', 'img.close', function () {
    $(this).parent(".searched-term").remove();
    setPositioning ()
});

// ~~~~~~~~~~~~~~~~~~~~~~ //
// End Search Query Cards //
// ~~~~~~~~~~~~~~~~~~~~~~ //


var modals = document.getElementsByClassName('modal')
var hidden_modals = document.getElementsByClassName('modal-view')
var modalImage = $('img.modal-img-view');
var height

for (var i=0; i < modals.length; i++) {
    modals[i].addEventListener('click', showModal(i))
    // change this to jquery
    // i think my previous pagination functions can help here
    // will come back to this
    /*$('.modal').click(function(){
        alert("I'm here");
    })*/
}

function showModal(i) {
    return function(){
        $(document.body).css('overflow', 'hidden');
        hidden_modals[i].style.display = 'block'
        hidden_modals[i].style.height = '100%'
        setTimeout(function(){
            $('.modal-view').css('background', 'rgba(13, 18, 48, 0.7)');
            setTimeout(function(){
                hidden_modals[i].childNodes[1].style.marginTop = "15px"
                height = modalImage.innerHeight()
                if (images) {
                    $('.modal-wrap .arrow-left').css('opacity', '1');
                    $('.modal-wrap .arrow-right').css('opacity', '1');
                }
            }, 100);
        }, 100);
    }
}

// scroll wheel
$(document).ready(function () {
    $('#modal-image').bind('mousewheel', function (e) {
        if (e.originalEvent.wheelDelta /120 > 0) {
            $('.plus').trigger('click'); // scroll up
        } else {
            $('.minus').trigger('click'); // scroll down
        }
    })
});

$(".config-table-modal").click(function (e) {
    e.stopPropagation();
});
$('#modal-img').click(function (e) {
    e.stopPropagation();
})

$(".modal-view").click(closeModal);
$("#modal-dim-background").click(closeModal);
$('div.close').click(closeModal);

function closeModal () {
    $(document.body).css('overflow', '');
    $(".config-table-modal").css('margin-top', '');
    $(".modal-wrap").css('margin-top', '');
    setTimeout(function(){
        $('.modal-view').css('background', 'rgba(13, 18, 48, -0.3)');
        setTimeout(function(){
            $(".modal-view").css('display', '');
            $(".modal-view").css('height', '');
        }, 150);
    }, 100);
}

$('.maximize').click(function () {
    $('.modal').trigger('click');
})

// Zoom / Unzoom buttons //
$('.plus').click(function () {
    modalImage.css('max-width', 'unset');
    height = height + 50
    modalImage.css('height', height)
});
$('.minus').click(function () {
    modalImage.css('max-width', 'unset');
    height = height - 50
    modalImage.css('height', height)
});

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //
// TABLE MODAL HANDLED BELOW HERE //
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

// get all options from left and right columns
var left_col = window.document.getElementsByClassName('left')
var right_col = window.document.getElementsByClassName('right')

// select thing from left column
var selected_items = []
$('#available-cols').on('click', 'li', function (e) {
    e.stopPropagation()
    $(this).css('background-color', 'rgba(39, 173, 136, 0.5)');
    selected_items.push( $(this).html() );
});

// select things on the right column
var other_items = []
$('#selected-cols').on('click', 'li', function (e) {
    e.stopPropagation()
    $(this).css('background-color', 'rgba(39, 173, 136, 0.5)');
    $(this).addClass('selected');
    other_items.push( $(this).html() );
});

// move selected things from left column to right column
$('div.arrow-wrap > img:first-child').click(function (e) {
    e.stopPropagation()
    $('#available-cols > li').css('background', '');
    for ( var i = 0; i < selected_items.length; i++) {
        window.document.getElementById('selected-cols').insertAdjacentHTML('beforeend', '<li class="right">' + selected_items[i] + '</li>')
        for (var x = 0; x < left_col.length; x++) {
            if (selected_items[i] === left_col[x].textContent) {
                left_col[x].remove()
            }
        }
    }
    selected_items.length = 0
});

// move selected things from right to left
$('div.arrow-wrap > img:last-child').click(function (e) {
    e.stopPropagation()
    $('#selected-cols > li').css('background', '');
    for ( var i = 0; i < other_items.length; i++) {
        window.document.getElementById('available-cols').insertAdjacentHTML('beforeend', '<li class="left">' + other_items[i] + '</li>')
        for (var x = 0; x < right_col.length; x++) {
            if (other_items[i] === right_col[x].textContent) {
                right_col[x].remove()
            }
        }
    }
    other_items.length = 0
});

// http://www.slavevoyages.org/voyage/search
// http://www.slavevoyages.org/static/scripts/voyage/voyage-search.js
// ctrl-f 'function move_var(direction)' to find where this is handled

// move selected items down when down arrow is clicked
// first img in html is 'move-down' arrow
$('img.down').click(function (e) {
    e.stopPropagation();
    // move down
    var $other_items = $('#selected-cols li.selected')
    $other_items.last().next().after($other_items);
});

// move selected items up when up arrow is clicked
// last img in html is the 'move-up' arrow
$('img.up').click(function (e) {
    e.stopPropagation();
    // move up
    var $other_items = $('#selected-cols li.selected')
    $other_items.first().prev().before($other_items);
});

// deselect all elements when clicking off of items
$('.config-table-modal').click(function () {
    $('#selected-cols > li').css('background', '');
    $('#available-cols > li').css('background', '');
    $('#selected-cols > li').removeClass('selected');
    $('#available-cols > li').removeClass('selected');
    selected_items.length = 0
    other_items.length = 0
});

// ~~~~~~~~~~~~~~~ //
// DRAGGABLE MODAL //
// ~~~~~~~~~~~~~~~ //

// https://www.w3schools.com/howto/howto_js_draggable.asp
// Make the DIV element draggagle:
if (window.document.getElementById('modal-image')) {
    dragElement(document.getElementById(("modal-image")));

    function dragElement(elmnt) {
      var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
      if (document.getElementById(elmnt.id + "modal-image")) {
        /* if present, the header is where you move the DIV from:*/
        document.getElementById(elmnt.id + "modal-image").onmousedown = dragMouseDown;
      } else {
        /* otherwise, move the DIV from anywhere inside the DIV:*/
        elmnt.onmousedown = dragMouseDown;
      }

      function dragMouseDown(e) {
        e = e || window.event;
        // get the mouse cursor position at startup:
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = closeDragElement;
        // call a function whenever the cursor moves:
        document.onmousemove = elementDrag;
      }

      function elementDrag(e) {
        e = e || window.event;
        // calculate the new cursor position:
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
        // set the element's new position:
        elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
        elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
        elmnt.style.right = 'unset';
        elmnt.style.bottom = 'unset';
      }

      function closeDragElement() {
        /* stop moving when mouse button is released:*/
        document.onmouseup = null;
        document.onmousemove = null;
      }
    }
}

/////////////////////////////////////////////////////////////
///// SEARCH RESULTS
///////////////////////////////////////////////////////////////

// jQuery's '.css' inserts css styles as inline-styles 
// this can be problematic because it overwrites css styles applied in the stylesheet
// $(element).css('style',''); unsets these inline styles

var setView // load grid or table view, with # results per page from last page visit on page load
var cards
var num_of_results
$(document).ready(function () {
    $('span.results-per-page > span').html(result_array.length)
    setView = window.localStorage.getItem('view')
    if (!setView || setView === 'grid') {
        cards = false
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

$(document).click(function () { // close things with clicked-off
    $('span.results-per-page').find("img:first").removeClass('show');
    $('span.results-per-page').next().removeClass('show');
    $('span.sort-by').next().removeClass('show');
    $('span.sort-by').find("img:first").removeClass('show');
    if (window.innerWidth < 820 && filter) {
        $(".show-filter").trigger('click');
    }
});

$('div.container.main').click(function (e) {
    e.stopPropagation();
})

$("span.align-center").click(function (e) { // toggle show/hide per-page submenu
    e.stopPropagation();
    $(this).find("img:first").toggleClass('show');
    $(this).next().toggleClass('show');
});

$("ul.results-per-page li").click(function (e) { // set the per-page value
    e.stopPropagation();
    num_of_results = $(this).find('span:first').html();
    localStorage.setItem('display_amount', num_of_results);
    location.reload();
});

var timer
$("span.view-toggle").mouseenter(function () { // show tooltips on hover
    var that = this
    timer = setTimeout(function(){
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
$("span.grid-view").click(function gridView (e) { // grid view
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
        $.each(result_array,function () {
            $('<li><a><div class="container cards"><p class="card-title">Person Name or Narrative Title</p><p><span>Gender</span>: Male</p><p><span>Born</span>: fl.1845 Virginia, United States</p><p><span>Occupation</span>: Fugitive Slave, Slave Narrative, Occupation..</p><a class="card-learn-more">View Narrative</a></div></a></li>').appendTo("ul.row");
        });
        cards = true
        view = 'grid'
        window.localStorage.setItem('cards', cards)
        window.localStorage.setItem('view', view)
        $('div.column').css('padding', '0', 'margin-top', '-30px', 'margin-bottom', '-15px');
    }
});

$("span.table-view").click(function tableView (e) { // table view
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
        $.each(result_array,function () {
            $('<tr class="tr"><td class="name td-name"><span>Name LastName</span></td><td class="gender"><p><span class="first">Gender: </span>Gndr</p></td><td class="age"><p><span class="first">Age: </span>##</p></td><td class="occupation"><p><span class="first">Occupation: </span>Fugitive Slave</p></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><span class="meta">Metadata Content</span></td><td class="meta"><a href="#">View Narrative</a></td></tr>').appendTo('tbody');
        });
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
var filter
var tableWidth = 0
$(".show-filter").click(function(e){ // toggle show/hide filter menu
    e.stopPropagation();
    filter = !filter
    if (filter) {
        $("div.filter-menu").addClass("show");
        $(this).html('<img src="assets/images/arrow-right.svg" alt="show filter menu button" style="transform:rotate(180deg);"> Hide Filter Menu');
        if ( window.innerWidth <= 820 ) {
            $("#searchResults").removeClass("show");
        } else {
            centerStuffWithFilter()
        }
    } else { // toggle off filter-menu
        $(this).html('<img src="assets/images/arrow-right.svg" alt="show filter menu button"> Show Filter Menu');
        $('div#searchResults').css('max-width', '');
        setTimeout(function () {
            $(".filter-menu").removeClass("show");
            $('div#searchResults').css('width','');
        $("#searchResults").removeClass("show");
        }, 50);
    }
});

$('div.filter-menu').click(function(e){
    e.stopPropagation();
});

function centerStuffWithFilter () {
    $("#searchResults").addClass("show");
    if (window.innerWidth <= 820) {
        $('div#searchResults.show').css('width','');
        $("#searchResults").removeClass("show");
    } else {
        tableWidth = window.innerWidth - 330 
        $('div#searchResults').css('max-width', '3000px');// remove max-width property
        $('div#searchResults.show').css('width', tableWidth); // apply width
    }
}

$(window).resize(function () { // make main content responsive when filter is visible
    if (filter) {
        setTimeout(function () {
            centerStuffWithFilter()
        }, 150);
    }
});

$("li.filter-cat").click(function () { // toggle show/hide filter-by submenus
    $(this).find("span:first").toggleClass("show");
    $(this).next().toggleClass("show");
});