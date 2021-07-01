$(document).ready(function(){

   //limits number of featured stories to 6
    // var featStories = $('#featured .row li').size(); //number of featured stories
    // if (featStories > 6){
    //     $('#featured .row li:gt(5)').remove();
    // }

    $('.cards li').click(function(){
        console.log("clicked");
        window.location = $(this).find("a").attr("href");
    });

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

    installFeaturedListeners('.featured-stories');

    //If we used the search bar, then scroll down to the results
    var isSearched = new RegExp('[\?&]searchbar=([^&#]*)').exec(window.location.href);
    if(isSearched !== null) {
        $([document.documentElement, document.body]).animate({
            scrollTop: $("#all-header-scroll").offset().top
        }, 100);
    }
});
