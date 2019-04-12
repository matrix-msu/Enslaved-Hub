$(document).ready(function(){
    if( typeof JS_EXPLORE_FILTERS === 'undefined' ){
        return;
    }
    console.log(JS_EXPLORE_FILTERS);
    if (JS_EXPLORE_FILTERS == "Time") {
        $.ajax({
            url: BASE_URL + 'api/getDateRange',
            method: "GET",
            data: {type: JS_EXPLORE_FILTERS,  category:JS_EXPLORE_FORM},
            'success': function (data) {
                data = JSON.parse(data);
                console.log('data', data);
                var min = data['min'][0]['startyear']['value'];
                var max = data['max'][0]['startyear']['value'];
                for (var i = min; i <= max; i++) {
                    console.log(i);
                    $("#startYear").append("<option value='"+i+"'>"+i+"</option>");
                    $("#endYear").append("<option value='"+i+"'>"+i+"</option>");
                }
            }
        });
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
