$(document).ready(function(){
    $('.cards li').click(function(){
        window.location = $(this).find("a").attr("href");
        return false;
    });

    $(".sort-cards p").click(function (e) { // toggle show/hide per-page submenu
        e.stopPropagation();
        $(this).find("img:first").toggleClass('show');
        $(this).next().toggleClass('show');
    });

    $(document).click(function () { // close things with clicked-off
        $('.sort-cards p').find("img:first").removeClass('show');
        $('.sort-cards p').next().removeClass('show');
    });
});