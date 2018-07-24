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

$(".row li").click(function() {
    window.location = $(this).find("a").attr("href");
    return false;
});

$(document).ready(function(){ //limits number of featured stories to 6
    var featStories = $('#featured .row li').size(); //number of featured stories
    if (featStories > 6){
        $('#featured .row li:gt(5)').remove();
    }
});

// ~~~~~~~~~~ //
// PAGINATION //
// ~~~~~~~~~~ //

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