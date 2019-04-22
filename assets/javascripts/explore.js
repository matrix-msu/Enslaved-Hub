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

    displayFeatured(JS_EXPLORE_FORM);

    //Keep at bottom it messes up Explore Form pages
    //Get counts only if on explorefilter page
    if( typeof JS_EXPLORE_FILTERS !== 'undefined' ){
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
                            label = record[key]['value'];
                        }
                    }
                    if (label != ""){
                        var count = record['count']['value'];
                        var span = $("a:contains("+label+")").find('span');
                        if ($(span).length > 0){
                            $(span).html(count);
                        }
                    }
                });
                $(".cards li").each(function(){
                    console.log($(this).find("span").html());
                    if($(this).find("span").html() != 0){
                        $(this).removeClass("hide-category");
                    }
                });
            }
        });
    }

    // //Go through category cards and hide the ones with counts of 0
    // $(".cards li").each(function(){
    //     console.log($(this).find("span").html());
    //     if($(this).find("span").html() == 0){
    //         $(this).addClass("hide-category");
    //     }
    // });
});

function displayFeatured(type){
    console.log(JS_EXPLORE_FORM);
    cardcount = 8;
    if( type == "People"){
        type = "Person";
        for(i = 0; i < cardcount; i++){
            $('.connect-row').append('<li><a href="'+BASE_URL+'recordPerson/?item=Q503"><div class="cards"><img src="'+BASE_IMAGE_URL+type+'-light.svg" alt="'+type+' icon"><h3>Firstname Lastname</h3></div></a></li>');
        }
    }else{
        type = type.slice(0, -1); //remove s on the end
        for(i = 0; i < cardcount; i++){
            $('.connect-row').append('<li><a href="'+BASE_URL+'recordPerson/?item=Q503"><div class="cards"><img src="'+BASE_IMAGE_URL+type+'-light.svg" alt="'+type+' icon"><h3>'+type+' Name</h3></div></a></li>');
        }
    }
    $('.connect-row li').css("background-image", "url("+BASE_IMAGE_URL+type+"Card.jpg)");
}