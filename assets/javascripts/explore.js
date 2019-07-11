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

    //Get counts only if on explorefilter page
    if( typeof JS_EXPLORE_FILTERS !== 'undefined' ){
        if (JS_EXPLORE_FILTERS == "Date") {
            $.ajax({
                url: BASE_URL + 'api/getDateRange',
                method: "GET",
                data: {type: JS_EXPLORE_FILTERS,  category:JS_EXPLORE_FORM},
                'success': function (data) {
                    data = JSON.parse(data);
                    console.log('data', data);
                    // var min = data['min'][0]['startyear']['value'];
                    // var max = data['max'][0]['startyear']['value'];
                    var min = data['min'][0]['year']['value'];
                    var max = data['max'][0]['year']['value'];
                    if (parseInt(max) < parseInt(data['max'][1]['yearend']['value'])) {
                        max = data['max'][1]['yearend']['value'];
                    }
                    for (var i = min; i <= max; i++) {
                        console.log(i);
                        $("#event-from").append("<option value='"+i+"'>"+i+"</option>");
                        $("#event-to").append("<option value='"+i+"'>"+i+"</option>");
                    }
                }
            });
            return;
        }
        else{
            $.ajax({
                url: BASE_URL + 'api/counterOfType',
                method: "GET",
                data: {type: JS_EXPLORE_FILTERS,  category:JS_EXPLORE_FORM},
                'success': function (data) {
                    data = JSON.parse(data);
                    
                    data.forEach(function(record) {
                        var label = "";
                        var count = "";
                        console.log(record);
                        for(var key in record) {
                            if(key.match("Label$")) {
                                label = record[key]['value'];
                            }
                            if(key.match("count$")) {
                                count = record[key]['value'];
                            }
                            else if(key.match("Count$")) {
                                if(count !== ""){
                                    var count2 = record[key]['value'];
                                    count = +count + +count2;
                                }
                                else{
                                    count = record[key]['value'];
                                }
                                
                            }
                        }
                        if (label != ""){
                            
                            var span = $("a:contains("+label+")").find('span');
                            if ($(span).length > 0){
                                $(span).html(count);
                            }
                        }
                    });
                    $(".cards li").each(function(){
                        if($(this).find("span").html() != 0){
                            $(this).removeClass("hide-category");
                        }
                    });
                }
            });
        }
    }
    else if(typeof JS_EXPLORE_FORM !== 'undefined'){
        //person, place, event, source
        var type = JS_EXPLORE_FORM;
        if( type == "People"){
            type = "Person";
        }
        else{
            type = type.slice(0, -1); //remove s on the end
        }

        $.ajax({
            url: BASE_URL+"api/blazegraph",
            type: "GET",
            data: {preset: 'featured', templates: [type]},
            success: function (data) {
                data = JSON.parse(data);
                data[type].forEach(function (e) {
                    $('.connect-row').append(e);
                });
            }
        });

    }

});