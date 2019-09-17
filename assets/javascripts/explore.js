$(document).ready(function(){

    $('.cards-featured li').click(function(){
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

    // $(window).on('resize', function(){
    //     //Update max scroll width
    //     max_card_scroll = $('.explore-featured .cardwrap').get(0).scrollWidth - $('.explore-featured .cardwrap').get(0).clientWidth;
    //     //Update cards_per_page on window resize
    //     var card_screen_width = $('.explore-featured .cardwrap2').width();     
    //     cards_per_page = Math.floor(card_screen_width/card_width);

    //     if(cards_per_page < 1){
    //         cards_per_page = 1;
    //     }

    //     if(cards_per_page != old_per_page){
    //         old_per_page = cards_per_page;
    //         installFeaturedListeners();
    //     }
    // });

    //Get counts only if on explorefilter page
    // console.log(JS_EXPLORE_FILTERS, JS_EXPLORE_FORM)
    if( typeof JS_EXPLORE_FILTERS !== 'undefined' ){
        if (JS_EXPLORE_FILTERS == "Date") {
            $.ajax({
                url: BASE_URL + 'api/getDateRange',
                method: "GET",
                data: {type: JS_EXPLORE_FILTERS,  category:JS_EXPLORE_FORM},
                'success': function (data) {
                    console.log(data);
                    data = JSON.parse(data);
                    console.log(data);
                    var min = data['min'][0]['year']['value'];
                    var max = '';
                    var yearend = '';
                    //have to do this weird check because year and yearend will randomly switch array positions every time query is executed
                    if(data['max'][0]['year']){
                        max = data['max'][0]['year']['value'];
                        yearend = data['max'][1]['yearend']['value'];
                    }
                    else{
                        max = data['max'][1]['year']['value'];
                        yearend = data['max'][0]['yearend']['value'];
                    }

                    if (parseInt(max) < parseInt(yearend)) {
                        max = yearend;
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
                    console.log(data);
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
                    $(".cards-featured li").each(function(){
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
                    $('.explore-featured .cards-featured').append(e);
                });
                installFeaturedListeners('.explore-featured');
            }
        });

    }

});