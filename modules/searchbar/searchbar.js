$(document).ready(function(){
    search_term = window.localStorage.getItem('searched_terms');
    if (search_term && location.href.match(/searchResults/)) {
        search_term = search_term.split(',');
        localStorage.removeItem('searched_terms');
        createCards(search_term);
    }
});

// collect searchInput values
var search_term;
var term_card;
var padding_left = 0;
var search_field = window.document.getElementById('search-field');
$('.search-submit').click(function() {
    search_term = search_field.value;
    search_term = search_term.split(',');
    localStorage.setItem('searched_terms', search_term);
    // redirect to search results page, if not already there
    if (!location.href.match(/searchbar-results/)) {
        location.href = "" + BASE_URL + "searchbar-results";
    }
    search_field.value = '';
    createCards(search_term);
});

// listen for user pressing 'enter'
// change this to jquery
$('#search-field').keyup(function (e) {
    e.stopPropagation();
    if (e.keyCode == 13) {
        $(".search-submit").trigger('click');
    }
});

var createCards = function(terms) {
    if (terms.length === 1) {
        // append the term to the div, append div to parent element
        $("<div class='searched-term'>" + terms + "<img class='close' src='" + BASE_IMAGE_URL + "x.svg' alt='close button'></div>").appendTo("div.search-field");
    } else if (terms.length > 1) {
        for (var i = 0; i < terms.length; i++) {
            // append the term to the div, append div to parent element
            $("<div class='searched-term'>" + terms[i] + "<img class='close' src='" + BASE_IMAGE_URL + "x.svg' alt='close button'></div>").appendTo("div.search-field");
        }
    }
    setPositioning ()
}

var setPositioning = function() {
    term_card = window.document.getElementsByClassName('searched-term')
    if (term_card.length != 0) {
        search_field.placeholder = 'Add another search term here';
    } else {
        search_field.placeholder = 'Search for whatever it is you want in life';
    }
    setPadding()
}

var setPadding = function() {
    padding_left = 0
    if (term_card.length > 0) {
        $('div.search-field').css('padding-left', padding_left);
    } else {
        $('div.search-field').css('padding-left', '');
    }
}

$('form').on('click', 'img.close', function() {
    $(this).parent(".searched-term").remove();
    setPositioning();
});
