var base_wrap = '';
//Featured Scrolling Functionality
function installFeaturedListeners(base_name){
    //update globals
    base_wrap = base_name;
    var card_width = parseInt($(base_wrap+' .cardwrap li.card').outerWidth()) + parseInt($(base_wrap+' .cardwrap li.card').css('marginRight')); //card width + margin-right (margin-right always = 20px)
    var cardwrap_width = $(base_wrap+' .cardwrap .cardwrap2').outerWidth();
    var cards_per_page = 1 + Math.floor((cardwrap_width/2)/card_width); //minimum = 1

    $(base_wrap+' .cardwrap li.card').last().css('margin-right', cardwrap_width+'px');

    var num_cards = $(base_wrap+' .cardwrap li.card').length;
    var num_dots = Math.ceil(num_cards/cards_per_page);

    //populate dots container with correct amount of dots
    var dots = $(base_wrap+' .controls .dots');

    dots.empty();
    var html = "";
    for(i=1; i<=num_dots; i++){
        html += "<div class='dot' id='d"+i+"'></div>";
    }
    dots.html(html);
    $(base_wrap+' .controls .dots #d1').addClass('active');

    //When the cardwrap scrolls, update the dots
    $(base_wrap+' .cardwrap').off().scroll(function(){
        //get current dot based on scroll position
        var current_dot = Math.ceil(($(this).scrollLeft()+0.1) / (card_width*cards_per_page));

        dots.find('.dot').removeClass('active');
        dots.find('.dot#d'+current_dot).addClass('active');
    });

    //When a dot is clicked, scroll to right position
    $(base_wrap+' .controls .dots .dot').off().click(function(){
        //get clicked dot
        var dotid = $(this).attr('id');
        var page_num = parseInt(dotid.substr(1)); // dotid[1];

        $(base_wrap+' .cardwrap').animate({
            scrollLeft: card_width * (page_num-1) * cards_per_page
        }, 500);
    });

    //When prev or next are clicked, tigger click on new dot
    $(base_wrap+' .controls .prev').off().click(function(){
        //get current active dot
        var dotid = $(base_wrap+' .controls .dots .active').attr('id');
        var prev_page = dotid[1] - 1;
        //trigger click on prev dot
        $(base_wrap+' .controls .dots .dot#d'+prev_page).trigger('click');
    });
    $(base_wrap+' .controls .next').off().click(function(){
        //get current active dot
        var dotid = $(base_wrap+' .controls .dots .active').attr('id');
        var next_page = parseInt(dotid[1]) + 1;
        //trigger click on next dot
        $(base_wrap+' .controls .dots .dot#d'+next_page).trigger('click');
    });
}

$(document).ready(function(){

    //Calls install function 600ms after the last resize event
    var timer;
    $(window).on('resize', function(){

        clearTimeout(timer);
        timer = setTimeout(function(){
            installFeaturedListeners(base_wrap);
        }, 600);
    });

});
