$(document).ready(function(){


    $('.jump-button#timeline').click(function(){
        // Figure out element to scroll to
        var target = $('.timelinewrap');
        //target = target.length ? target : $('[name=' + '.person-timeline'.slice(1) + ']');
        $('html, body').animate({ scrollTop: target.offset().top - 100 }, 1000);
        return false;
    });
    $('.jump-button#details').click(function(){
        // Figure out element to scroll to
        var target = $('.detail-section');
        $('html, body').animate({ scrollTop: target.offset().top - 50 }, 1000);
        return false;
    });
    
});