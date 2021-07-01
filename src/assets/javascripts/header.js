$(document).ready(function () {

    // var url = window.location.href.split('/');
    // var last_part = url[url.length-1];
    // if (last_part.indexOf("?searchbar=") >= 0){
    //     last_part = "search";
    // }
    // $("#"+last_part).removeClass("unselected").addClass("h-selected");

    var hamburger_url = BASE_URL+"assets/images/hamburger.svg";
    var x_url = BASE_URL+"assets/images/x.svg";

    // on click dropdown mobile menu appears
    $(".dropdown-button, #menu-button").on("click",function(event){
        $(".rightnav").toggleClass("dropclass");
    });

    $("#menu-button").on("click",function(){
        if($(".rightnav").hasClass("dropclass")){
             $(".hamburger").attr('src',x_url);
         }
         else{
             $(".hamburger").attr('src',hamburger_url);
         }
    });

    // on click of hamburger, it changes to X icon
    $(".dropdown-button").on("click",function(){
        if($(".rightnav").hasClass("dropclass")){
            $(".hamburger").attr('src',x_url);
        }
        else{
            $(".hamburger").attr('src',hamburger_url);
        }
    });

    // when width of browser more than 780px dropdown appears on hover of browse
    $(".drop-link").on("mouseenter",function(){
        if( $(window).width() > 780 ){
            $(this).children(".sub-list").addClass('sub-showing');
            $(this).children('.drop-carat').addClass('carat-up');
        }
    });
    $(".drop-link").on("mouseleave",function(){
        if( $(window).width() > 780 ){
            $(this).children('.sub-list').removeClass('sub-showing');
            $(this).children('.drop-carat').removeClass('carat-up');
        }
    });

    // whens its less than 780px
    $(".drop-link").on("click", function(){
        if( $(window).width() <= 780 ){
            // on click dropdown of browse, sub menu appears
            // and the down carat changes to up carat icon
            if($(this).children(".sub-list").hasClass("sub-showing")){
                $(this).children('.sub-list').removeClass("sub-showing");
                $(this).children('.drop-carat').removeClass('carat-up');
            }
            else{
                $(this).children('.sub-list').addClass("sub-showing");
                $(this).children('.drop-carat').addClass('carat-up');
            }
        }
    });

    // close menu on click outside
    $(document).click(function(event){
        if(!$(event.target).parents(".rightnav").is(".rightnav") && !$(event.target).parents(".dropdown-menu").is(".dropdown-menu")){
            $(".rightnav").removeClass("dropclass");
            if($(".rightnav").hasClass("dropclass")){
                $(".hamburger").attr('src',x_url);
            }
            else{
                $(".hamburger").attr('src',hamburger_url);
            }
        }

    });

    //fix header after landing section
    $(document).on("scroll",function(e){
        if(window.scrollY > 200){
            $(".nav-header").addClass("fixedheader");
        }
        else{
            $(".nav-header").removeClass("fixedheader");
            $(".nav-header").css("top","unset");
        }
        if(window.scrollY > 300){
            $(".fixedheader").css("top","-35px");
        }
        else{
            $(".fixedheader").css("top","-100px");
        }
    });

});
