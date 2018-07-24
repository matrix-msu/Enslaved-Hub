$(document).ready( function() {
    //when page loads trigger click on first category to load cards
    $('#people').trigger('click');
});
//Global variables for the card type(CARDT) and card amount(CARDA)
var CARDT;
var CARDA;

$('li.unselected').click(function(){
    $('.selected').removeClass('selected');
    $(this).addClass('selected');
    $(".load-more").removeClass('loaded');
    removeConnections();
    //People
    if($("#people").hasClass("selected")){
        $(".person-image").css("background-image", "url(./assets/images/Person-dark.svg)");
        CARDT="Person";
        CARDA = 10;
        displayConnections(CARDT,CARDA);
    }else{
        $(".person-image").css("background-image", "url(./assets/images/Person.svg)");
    }
    //Events
    if($("#event").hasClass("selected")){
        $(".event-image").css("background-image", "url(./assets/images/Event-dark.svg)");
        CARDT="Event";
        CARDA = 3;
        displayConnections(CARDT,CARDA);
    }else{
        $(".event-image").css("background-image", "url(./assets/images/Event.svg)");
    }
    //Places
    if($("#place").hasClass("selected")){
        $(".place-image").css("background-image", "url(./assets/images/Place-dark.svg)");
        CARDT="Place";
        CARDA = 3;
        displayConnections(CARDT,CARDA);
    }else{
        $(".place-image").css("background-image", "url(./assets/images/Place.svg)");
    }
    //Projects
    if($("#project").hasClass("selected")){
        $(".project-image").css("background-image", "url(./assets/images/Project-dark.svg)");
        CARDT="Project";
        CARDA = 2;
        displayConnections(CARDT,CARDA);
    }else{
        $(".project-image").css("background-image", "url(./assets/images/Project.svg)");
    }
    //Sources
    if($("#source").hasClass("selected")){
        $(".source-image").css("background-image", "url(./assets/images/Source-dark.svg)");
        CARDT="Source";
        CARDA = 15;
        displayConnections(CARDT,CARDA);
    }else{
        $(".source-image").css("background-image", "url(./assets/images/Source.svg)");
    }
});

$('.load-more').click(function(){
    $(this).addClass('loaded');
    removeConnections();
    displayConnections(CARDT,CARDA);
});

//since cardType and cardAmount were changed to global variables, they could technically be removed here and replaced with the globals
//but this is clear enought at the moment
function displayConnections(cardType, cardAmount){
    var displayAmount = cardAmount;
    if( cardAmount > 8 && !$(".load-more").hasClass("loaded") ){
        displayAmount = 8;
    }else if( cardAmount < 8 ){
        $(".load-more").addClass('loaded');
    }
    
    if( cardType == "Person"){
        for(i = 0; i < displayAmount; i++){
            $('.connect-row').append('<li><div class="cards"><img src="assets/images/'+cardType+'-light.svg"><h3>Firstname Lastname</h3></div></li>');
        }
    }else{
        for(i = 0; i < displayAmount; i++){
            $('.connect-row').append('<li><div class="cards"><img src="assets/images/'+cardType+'-light.svg"><h3>'+cardType+' Name</h3></div></li>');
        }
    }
    $('.connect-row .cards').css("background-image", "url(./assets/images/"+cardType+"Card.jpg)");
    //There does need to be a certain naming convention for the image names due to this function
}
function removeConnections(){
    $('.connect-row li').remove();
}