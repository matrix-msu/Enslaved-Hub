// connections has been renamed as related records 

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
        $('.search-all').html('View All ' + connectionsArray[CARDT + '-count'] + ' People');
    }
    //Events
    if($("#event").hasClass("selected")){
        CARDT="Event";
        SEARCHTYPE = "events";
        displayConnections(CARDT);
        $('.search-all').html('View All ' + connectionsArray[CARDT + '-count'] + ' Events');
    }
    //Places
    if($("#place").hasClass("selected")){
        CARDT="Place";
        SEARCHTYPE = "places";
        displayConnections(CARDT);
        $('.search-all').html('View All ' + connectionsArray[CARDT + '-count'] +  ' Places');
    }
    //Projects
    if($("#project").hasClass("selected")){
        CARDT="Project";
        SEARCHTYPE = "projects";
        displayConnections(CARDT);
        $('.search-all').html('View All ' + connectionsArray[CARDT + '-count'] + ' Projects');
    }
    //Sources
    if($("#source").hasClass("selected")){
        CARDT="Source";
        SEARCHTYPE = "sources";
        displayConnections(CARDT);
        $('.search-all').html('View All ' + connectionsArray[CARDT + '-count']+ ' Sources');
    }
    //Close matches on the person page
    if ($("#closeMatch").hasClass("selected")) {
        CARDT = "CloseMatch";
        SEARCHTYPE = "closeMatch";
        displayConnections(CARDT);
        $('.search-all').html('View All ' + connectionsArray[CARDT + '-count'] + ' Close Matches');
    }
    // set the search all button url
    $('.search-all').attr('href', BASE_URL + 'search/' + SEARCHTYPE + '?' + recordform + '=' + QID);
});

//since cardType and cardAmount were changed to global variables, they could technically be removed here and replaced with the globals
//but this is clear enought at the moment
function displayConnections(cardType){
    var connections = connectionsArray[cardType];
    console.log(connections, cardType);

    if (typeof(connectionsArray[cardType + '-count']) == 'undefined' || connectionsArray[cardType+'-count'] <= 8){
        $('.search-all').hide();
    } else {
        $('.search-all').show();
    }

    // $('.search-all').show();        // ALWAYS SHOWING JUST FOR TESTING


    if( cardType == "Person"){
        for (var i in connections){
            var conn = connections[i];
            var name = conn['peoplename']['value'];
            var agentQ = conn['people']['value'];
            agentQ = agentQ.substring(agentQ.lastIndexOf('/') + 1);
            var personUrl = BASE_URL + 'record/person/' + agentQ;

            var relationshipLabel = '';

            // display a person relationships if they are given
            if (typeof(conn['relationslabel']) != 'undefined'){
                relationshipLabel = conn['relationslabel']['value'];
                $('.connect-row').append('<li><a href=' + personUrl + '><div class="cards"><img src="' + BASE_IMAGE_URL + cardType + '-light.svg" alt="' + cardType + ' icon"><h3>' + name + ' - ' + relationshipLabel+'</h3></div></a></li>');
            } else {
                $('.connect-row').append('<li><a href=' + personUrl + '><div class="cards"><img src="' + BASE_IMAGE_URL + cardType + '-light.svg" alt="' + cardType + ' icon"><h3>' + name + '</h3></div></a></li>');
            }
        }
    } else if (cardType == "Event") {
        for (var i in connections){
            var conn = connections[i];
            var name = conn['eventlabel']['value'];
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
            var name = conn['sourcelabel']['value'];
            var sourceQ = conn['source']['value'];
            sourceQ = sourceQ.substring(sourceQ.lastIndexOf('/') + 1);
            var sourceUrl = BASE_URL + 'record/source/' + sourceQ;
            $('.connect-row').append('<li><a href=' + sourceUrl + '><div class="cards"><img src="' + BASE_IMAGE_URL + cardType + '-light.svg" alt="' + cardType + ' icon"><h3>' + name + '</h3></div></a></li>');
        }
    } else if (cardType == "Place") {
        for (var i in connections) {
            var conn = connections[i];
            var name = conn['placelabel']['value'];
            var placeQ = conn['place']['value'];
            placeQ = placeQ.substring(placeQ.lastIndexOf('/') + 1);
            var placeUrl = BASE_URL + 'record/place/' + placeQ;
            $('.connect-row').append('<li><a href=' + placeUrl + '><div class="cards"><img src="' + BASE_IMAGE_URL + cardType + '-light.svg" alt="' + cardType + ' icon"><h3>' + name + '</h3></div></a></li>');
        }
    } else if (cardType == "CloseMatch") {
        cardType = 'Person';
        for (var i in connections) {
            var conn = connections[i];
            var name = conn['matchlabel']['value'];
            var matchQ = conn['match']['value'];
            matchQ = matchQ.substring(matchQ.lastIndexOf('/') + 1);
            var matchUrl = BASE_URL + 'record/person/' + matchQ;
            
            $('.connect-row').append('<li><a href=' + matchUrl + '><div class="cards"><img src="' + BASE_IMAGE_URL + cardType + '-light.svg" alt="' + cardType + ' icon"><h3>' + name + '</h3></div></a></li>');
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
                console.log(form)
                if (form == 'Person'){
                    $('#people').html('<div class="person-image"></div>'+connectionsArray['Person-count'] + ' People');
                    if (connectionsArray['Person-count'] <= 0){
                        $('#people').hide();
                    }
                } else if (form == 'Event'){
                    $('#event').html('<div class="event-image"></div>' + connectionsArray['Event-count'] + ' Events');
                    if (connectionsArray['Event-count'] <= 0) {
                        $('#event').hide();
                    }
                } else if (form == 'Project') {
                    $('#project').html('<div class="project-image"></div>' + connectionsArray['Project-count'] + ' Projects');
                    if (connectionsArray['Project-count'] <= 0) {
                        $('#project').hide();
                    }
                } else if (form == 'Source') {
                    $('#source').html('<div class="source-image"></div>' + connectionsArray['Source-count'] + ' Sources');
                    if (connectionsArray['Source-count'] <= 0) {
                        $('#source').hide();
                    }
                } else if (form == 'Place') {
                    $('#place').html('<div class="place-image"></div>' + connectionsArray['Place-count'] + ' Places');
                    if (connectionsArray['Place-count'] <= 0) {
                        $('#place').hide();
                    }
                } else if (form == 'CloseMatch') {
                    $('#closeMatch').html('<div class="person-image"></div>' + connectionsArray['CloseMatch-count'] + ' Close Matches');
                    if (connectionsArray['CloseMatch-count'] <= 0) {
                        $('#closeMatch').hide();
                    }
                }
            }
        }
    });
}
