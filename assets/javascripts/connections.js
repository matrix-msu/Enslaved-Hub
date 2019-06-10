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

    //People
    if($("#people").hasClass("selected")){
        CARDT="Person";
        SEARCHTYPE="people";
        displayConnections(CARDT);
        $('.search-all').html('View All People Connections');
    }
    //Events
    if($("#event").hasClass("selected")){
        CARDT="Event";
        SEARCHTYPE = "events";
        displayConnections(CARDT);
        $('.search-all').html('View All Event Connections');
    }
    //Places
    if($("#place").hasClass("selected")){
        CARDT="Place";
        SEARCHTYPE = "places";
        displayConnections(CARDT);
        $('.search-all').html('View All Place Connections');
    }
    //Projects
    if($("#project").hasClass("selected")){
        CARDT="Project";
        SEARCHTYPE = "projects";
        displayConnections(CARDT);
        $('.search-all').html('View All Project Connections');
    }
    //Sources
    if($("#source").hasClass("selected")){
        CARDT="Source";
        SEARCHTYPE = "sources";
        displayConnections(CARDT);
        $('.search-all').html('View All Source Connections');
    }

    // set the search all button url
    $('.search-all').attr('href', BASE_URL + 'search/' + SEARCHTYPE);
});

//since cardType and cardAmount were changed to global variables, they could technically be removed here and replaced with the globals
//but this is clear enought at the moment
function displayConnections(cardType){
    var connections = connectionsArray[cardType];
    console.log(connections);

    if( cardType == "Person"){
        for (var i in connections){
            var conn = connections[i];
            var name = conn['peoplename']['value'];
            var agentQ = conn['people']['value'];
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
    } else if (cardType == "Project") {
        for (var i in connections) {
            var conn = connections[i];
            var name = conn['projectName']['value'];
            var projectQ = conn['project']['value'];
            projectQ = projectQ.substring(projectQ.lastIndexOf('/') + 1);
            var projectUrl = BASE_URL + 'project/' + projectQ;
            $('.connect-row').append('<li><a href=' + projectUrl + '><div class="cards"><img src="' + BASE_IMAGE_URL + cardType + '-light.svg" alt="' + cardType + ' icon"><h3>' + name + '</h3></div></a></li>');
        }
    } else if (cardType == "Source") {
        for (var i in connections) {
            var conn = connections[i];
            var name = conn['sourceName']['value'];
            var sourceQ = conn['source']['value'];
            sourceQ = sourceQ.substring(sourceQ.lastIndexOf('/') + 1);
            var sourceUrl = BASE_URL + 'record/source/' + sourceQ;
            $('.connect-row').append('<li><a href=' + sourceUrl + '><div class="cards"><img src="' + BASE_IMAGE_URL + cardType + '-light.svg" alt="' + cardType + ' icon"><h3>' + name + '</h3></div></a></li>');
        }
    } else {

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
                } else if (form == 'Project') {
                    $('#project').html('<div class="project-image"></div>' + connectionsArray[form].length + ' Projects');
                } else if (form == 'Source') {
                    $('#source').html('<div class="source-image"></div>' + connectionsArray[form].length + ' Sources');
                }
            }
        }
    });
}
