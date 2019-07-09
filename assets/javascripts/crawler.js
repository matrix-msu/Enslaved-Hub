$(document).ready(function(){
    var card_limit = 12;

    $(document).click(function () { // close things with clicked-off
        $('span.results-per-page').find("img:first").removeClass('show');
        $('span.results-per-page #sortmenu').removeClass('show');
        $('span.sort-by #sortmenu').removeClass('show');
        $('span.sort-by').find("img:first").removeClass('show');
    });
    
    $(".sorting-dropdowns .align-center").click(function (e) { // toggle show/hide per-page submenu
        e.stopPropagation();
        $(this).find("img:first").toggleClass('show');
        $(this).find("#sortmenu").toggleClass('show');
    });
    
    $('span.results-per-page > span').html(card_limit);
    $("ul.results-per-page li").click(function (e) { // set the per-page value
        e.stopPropagation();
        card_limit = $(this).find('span:first').html();
        localStorage.setItem('display_amount', card_limit);
        //card_offset = 0; //reset offset to 0 when changing results-per-page to go to first page
        $('span.results-per-page > span').html(card_limit);
        $(document).trigger('click');
    });

    $('.crawler-tabs li').click(function(){
        $('.crawler-tabs li').removeClass('tabbed');
        $(this).addClass('tabbed');
        var name = $(this).attr('id');

        $('.result-container').removeClass('show');
        $('.result-container#'+name).addClass('show');
    });
});
