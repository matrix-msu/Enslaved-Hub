var connectionsArray;   // array of all connections

$(document).ready( function() {
    //when page loads trigger click on first category to load cards
    loadConnections();
});
//Global variables for the card type(CARDT)
var CARDT;
var SEARCHTYPE;      // used to create search urls

$('li.unselected').click(function(){
    $('.selected').removeClass('selected');
    $(this).addClass('selected');
    removeConnections();

    var searchAllText = "Search For All ";

    //People
    if($("#people").hasClass("selected")){
        CARDT="Person";
        SEARCHTYPE="people";
        displayConnections(CARDT);
        $('.search-all').html(searchAllText+'People');
    }
    //Events
    if($("#event").hasClass("selected")){
        CARDT="Event";
        SEARCHTYPE = "events";
        displayConnections(CARDT);
        $('.search-all').html(searchAllText + 'Events');
    }
    //Places
    if($("#place").hasClass("selected")){
        CARDT="Place";
        SEARCHTYPE = "places";
        displayConnections(CARDT);
        $('.search-all').html(searchAllText + 'Places');
    }
    //Projects
    if($("#project").hasClass("selected")){
        CARDT="Project";
        SEARCHTYPE = "projects";
        displayConnections(CARDT);
        $('.search-all').html(searchAllText + 'Projects');
    }
    //Sources
    if($("#source").hasClass("selected")){
        CARDT="Source";
        SEARCHTYPE = "sources";
        displayConnections(CARDT);
        $('.search-all').html(searchAllText + 'Sources');
    }

    // set the search all button url
    $('.search-all').attr('href', BASE_URL + 'search/' + SEARCHTYPE);
});

$('.search-all').click(function () {
    var searchUrl = BASE_URL + 'search/' + SEARCHTYPE;
    window.location = searchUrl;
});

//since cardType and cardAmount were changed to global variables, they could technically be removed here and replaced with the globals
//but this is clear enought at the moment
function displayConnections(cardType){
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
    } else if (cardType == "Event") {
        for (var i in connections){
            var conn = connections[i];
            var name = conn['eventname']['value'];
            var eventQ = conn['event']['value'];
            eventQ = eventQ.substring(eventQ.lastIndexOf('/') + 1);
            var eventUrl = BASE_URL + 'record/event/' + eventQ;
            $('.connect-row').append('<li><a href=' + eventUrl + '><div class="cards"><img src="' + BASE_IMAGE_URL + cardType + '-light.svg" alt="' + cardType + ' icon"><h3>' + name +'</h3></div></a></li>');
        }
    }else{

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
            if (data == " "){
                return;
            }
            connectionsArray = JSON.parse(data);
            console.log('success', connectionsArray);
            $('#people').trigger('click');  // start off on the people connections tab
            
            // display the counts for connections
            for (var form in connectionsArray){
                if (form == 'Person'){
                    $('#people').html('<div class="person-image"></div>'+connectionsArray[form].length + ' People');
                } else if (form == 'Event'){
                    $('#event').html('<div class="event-image"></div>' + connectionsArray[form].length + ' Events');
                }
            }
        }
    });
}
