$(document).ready(function(){

    $('.cards li').click(function(){
        console.log("clicked");
        window.location = $(this).find("a").attr("href");
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

    //Keep at bottom it messes up Explore Form pages
    if( typeof JS_EXPLORE_FILTERS === 'undefined' ){
        return;
    }
    $.ajax({
        url: BASE_URL + 'api/counterOfType',
        method: "GET",
        data: {type: JS_EXPLORE_FILTERS,  category:JS_EXPLORE_FORM},
        'success': function (data) {
            data = JSON.parse(data);
            console.log('data', data);
            data.forEach(function(record) {
                console.log(record)
                var label = "";
                for(var key in record) {
                    if(key.match("Label$")) {
                        label = record[key]['value']
                    }
                }
                if (label != ""){
                    var count = record['count']['value'];
                    var span = $("a:contains("+label+")").find('span');
                    if ($(span).length > 0){
                        $(span).html(count)
                    }
                }
            });
        }
    });
});