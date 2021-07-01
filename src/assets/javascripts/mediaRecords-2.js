var tabs = $('.tab'); // get all tabs

$('.tab').click(function () {
    // scroll selected tab into view
    var scrollTo = $(this);
    $(this).parent().parent().animate({
        scrollLeft: scrollTo.offset().left - $('.list-wrap').offset().left + $('.list-wrap').scrollLeft() - 50 // neg = element moves right
    })
})

$('.arrow.right').click(function(event) {
    event.preventDefault();
    event.stopPropagation();
    $(this).prev().animate({
        scrollLeft: $('.list-wrap').scrollLeft() + 300
    }, 800);
});

$('.arrow.left').click(function(event) {
    event.preventDefault();
    event.stopPropagation();
    $(this).next().animate({
        scrollLeft: $('.list-wrap').scrollLeft() - 300
    }, 800);
});

$('.list-wrap').scroll(function() {
    var fb = $(this);
    if (fb.scrollLeft() + fb.innerWidth() >= fb[0].scrollWidth) {
        $(this).next().addClass('hidden');
    } else {
        $(this).next().removeClass('hidden');
    }
    if (fb.scrollLeft() <= 20) {
        $(this).prev().addClass('hidden');
    } else {
        $(this).prev().removeClass('hidden');
    }
});

$('.record').click(function () {
    window.open('http://www.google.com', '_blank');
});
