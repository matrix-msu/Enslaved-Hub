$(document).ready(function () {

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

    
    $.ajax({
        url: BASE_URL+"api/blazegraph",
        type: "GET",
        data: {preset: 'projects2', templates: ['homeCard']},
        success: function (data) {
            data = JSON.parse(data);
            data['homeCard'].forEach(function (e) {
                $('.container.card-wrap > .card-row').append(e);
            })
            addCardListener();
        }
    })
});

function addCardListener(){
    $('.card-row li.card').click(function(){
        console.log("clicked");
        window.location = $(this).find("a").attr("href");
    });
}