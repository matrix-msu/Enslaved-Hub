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

$(document).ready(function(){ //limits number of featured stories to 6
    var featStories = $('#featured .row li').size(); //number of featured stories
    if (featStories > 6){
        $('#featured .row li:gt(5)').remove();
    }
});
