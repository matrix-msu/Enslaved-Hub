var tabs = $('.tab'); // get all tabs
var displays = $('main'); // get all <main> elements
if (displays) {
    displays.css('display', 'none');
    displays[0].style.display = 'block'
}

$('.tab').click(function () {
    $('.tab').removeClass('active');
    $(this).addClass('active');
    setDisplay ($(this).index());
    // scroll selected tab into view
    var scrollTo = $(this);
    $('.arrow-wrap').animate({
        scrollLeft: scrollTo.offset().left - $('.arrow-wrap').offset().left + $('.arrow-wrap').scrollLeft() - 50 // neg = element moves right
    });
});

$('.arrow.right').click(function(event) {
    event.preventDefault();
    $('.arrow-wrap').animate({
        scrollLeft: $('.arrow-wrap').scrollLeft() + 300
    }, 800);
});

$('.arrow.left').click(function(event) {
    event.preventDefault();
    $('.arrow-wrap').animate({
        scrollLeft: $('.arrow-wrap').scrollLeft() - 300
    }, 800);
});

$('.arrow-wrap').scroll(function() {
    var fb = $('.arrow-wrap');
    if (fb.scrollLeft() + fb.innerWidth() >= fb[0].scrollWidth) {
        $('.arrow.right').addClass('hidden');
    } else {
        $('.arrow.right').removeClass('hidden');
    }
    if (fb.scrollLeft() <= 20) {
        $('.arrow.left').addClass('hidden');
    } else {
        $('.arrow.left').removeClass('hidden');
    }
});

function setDisplay (i) {
    displays.css('display', 'none');
    if (!displays[i]){
        displays[0].style.display = 'block'
    } else {
        displays[i].style.display = 'block'
    }
}

// http://whatamericaate.org/js/newRecipes.js