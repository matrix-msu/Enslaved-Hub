var connectionsArray;   // array of all connections

$(document).ready( function() {
    //when page loads trigger click on first category to load cards
    loadConnections();
});
//Global variables for the card type(CARDT) and card amount(CARDA)
var CARDT;
var CARDA;  //going to be removed, the connection queries will have the limit

$('li.unselected').click(function(){
    $('.selected').removeClass('selected');
    $(this).addClass('selected');
    $(".load-more").removeClass('loaded');
    removeConnections();
    //People
    if($("#people").hasClass("selected")){
        CARDT="Person";
        CARDA = 10;
        displayConnections(CARDT,CARDA);
    }
    //Events
    if($("#event").hasClass("selected")){
        CARDT="Event";
        CARDA = 3;
        displayConnections(CARDT,CARDA);
    }
    //Places
    if($("#place").hasClass("selected")){
        CARDT="Place";
        CARDA = 3;
        displayConnections(CARDT,CARDA);
    }
    //Projects
    if($("#project").hasClass("selected")){
        CARDT="Project";
        CARDA = 2;
        displayConnections(CARDT,CARDA);
    }
    //Sources
    if($("#source").hasClass("selected")){
        CARDT="Source";
        CARDA = 15;
        displayConnections(CARDT,CARDA);
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

    var connections = connectionsArray[cardType];
    console.log(connections);

    if( cardType == "Person"){
        for (var i in connections){
            var conn = connections[i];
            var name = conn['agentlabel']['value'];
            var agentQ = conn['agent']['value'];
            agentQ = agentQ.substring(agentQ.lastIndexOf('/') + 1);
            var personUrl = BASE_URL + 'record/person/' + agentQ;
            $('.connect-row').append('<li><a href=' + personUrl + '><div class="cards"><img src="' + BASE_IMAGE_URL + cardType + '-light.svg" alt="' + cardType + ' icon"><h3>' + name +'</h3></div></a></li>');
        }
    }else{
        for(i = 0; i < displayAmount; i++){
            $('.connect-row').append('<li><div class="cards"><img src="'+BASE_IMAGE_URL+cardType+'-light.svg" alt="'+cardType+' icon"><h3>'+cardType+' Name</h3></div></li>');
        }
    }
    $('.connect-row li').css("background-image", "url("+BASE_IMAGE_URL+cardType+"Card.jpg)");
    //There does need to be a certain naming convention for the image names due to this function
}
function removeConnections(){
    $('.connect-row li').remove();
}


function loadConnections(){
    console.log(QID, recordform)

    $.ajax({
        url: BASE_URL+'api/getFullRecordConnections',
        type: "GET",
        data: {
                Qid: QID,
                recordForm: recordform
              },
        success: function (data) {
            connectionsArray = JSON.parse(data);
            console.log('success', connectionsArray);
            $('#people').trigger('click');  // start off on the people connections tab
        }
    });
}
