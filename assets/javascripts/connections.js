// connections has been renamed as related records

var connectionsArray;   // array of all connections

//Global variables for the card type(CARDT)
var CARDT;
var SEARCHTYPE;      // used to create search urls

$(document).ready( function() {
    //when page loads trigger click on first category to load cards
    loadConnections();

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

    var test_count = 1;
    //Connection html
    var connection_lists = Array(
        '<h1>'+test_count+' Connected People</h1><ul><li>Person Name <span>(Wife)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Brother brother brother)</span> <div id="arrow"></div></li><li>Person Name <span>(Relation)</span> <div id="arrow"></div></li><li>Person Name is Longer <span>(Father)</span> <div id="arrow"></div></li><li>Person Name <span>(Mother)</span> <div id="arrow"></div></li><li>View All People Connections <div id="arrow"></div></li></ul>',
        '<h1>'+test_count+' Connected Places</h1><ul><li>Place Name <div id="arrow"></div></li><li>Place Name is Longer<div id="arrow"></div></li><li>Place Name <div id="arrow"></div></li><li>View All Place Connections <div id="arrow"></div></li></ul>',
        '<h1>'+test_count+' Connected Events</h1><ul><li>Event Name <div id="arrow"></div></li><li>Event Name is Longer<div id="arrow"></div></li><li>Event Name <div id="arrow"></div></li><li>View All Event Connections <div id="arrow"></div></li></ul>',
        '<h1>'+test_count+' Connected Sources</h1><ul><li>Source Name <div id="arrow"></div></li><li>Source Name is Longer<div id="arrow"></div></li><li>Source Name <div id="arrow"></div></li><li>View All Source Connections <div id="arrow"></div></li></ul>'
    );

    var connection_html = '<div class="connectionswrap"><div class="connections"><div class="card-icons"><img src="'+BASE_IMAGE_URL+'Person-dark.svg"><span>'+test_count+'</span><div class="connection-menu">'+connection_lists[0]+
        '</div></div><div class="card-icons"><img src="'+BASE_IMAGE_URL+'Place-dark.svg"><span>'+test_count+'</span><div class="connection-menu">'+connection_lists[1]+
        '</div></div><div class="card-icons"><img src="'+BASE_IMAGE_URL+'Event-dark.svg"><span>'+test_count+'</span><div class="connection-menu">'+connection_lists[2]+
        '</div></div><div class="card-icons"><img src="'+BASE_IMAGE_URL+'Source-dark.svg"><span>'+test_count+'</span><div class="connection-menu">'+connection_lists[3]+
        '</div></div></div></div></div>';

    var details = '<div class="details"><div class="detail"><p class="detail-title">Person Status</p><p>Enslaved</p></div><div class="detail"><p class="detail-title">Sex</p><p>Unidentified</p></div><div class="detail"><p class="detail-title">Location</p><p>Location Name</p></div><div class="detail"><p class="detail-title">Origin</p><p>Location Name</p></div><div class="detail"><p class="detail-title">Date Range</p><p>1840-1864</p></div></div>';


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
                $('.connect-row').append('<li class="card"><a href=' + personUrl + '><div class="card-title"><img src="' + BASE_IMAGE_URL + cardType + '-dark.svg" alt="' + cardType + ' icon"><h3>' + name + ' - ' + relationshipLabel+'</h3></div>'+'</a></li>');
            } else {
                $('.connect-row').append('<li class="card"><a href=' + personUrl + '><div class="card-title"><img src="' + BASE_IMAGE_URL + cardType + '-dark.svg" alt="' + cardType + ' icon"><h3>' + name + '</h3></div>'+'</a></li>');
            }
        }
    } else if (cardType == "Event") {
        for (var i in connections){
            var conn = connections[i];
            var name = conn['eventlabel']['value'];
            var eventQ = conn['event']['value'];
            eventQ = eventQ.substring(eventQ.lastIndexOf('/') + 1);
            var eventUrl = BASE_URL + 'record/event/' + eventQ;
            $('.connect-row').append('<li class="card"><a href=' + eventUrl + '><div class="card-title"><img src="' + BASE_IMAGE_URL + cardType + '-dark.svg" alt="' + cardType + ' icon"><h3>' + name +'</h3></div>'+'</a></li>');
        }
    } else if (cardType == "Project") {
        for (var i in connections) {
            var conn = connections[i];
            var name = conn['projectName']['value'];
            var projectQ = conn['project']['value'];
            projectQ = projectQ.substring(projectQ.lastIndexOf('/') + 1);
            var projectUrl = BASE_URL + 'project/' + projectQ;
            $('.connect-row').append('<li class="card"><a href=' + projectUrl + '><div class="card-title"><img src="' + BASE_IMAGE_URL + cardType + '-dark.svg" alt="' + cardType + ' icon"><h3>' + name + '</h3></div>'+'</a></li>');
        }
    } else if (cardType == "Source") {
        for (var i in connections) {
            var conn = connections[i];
            var name = conn['sourcelabel']['value'];
            var sourceQ = conn['source']['value'];
            sourceQ = sourceQ.substring(sourceQ.lastIndexOf('/') + 1);
            var sourceUrl = BASE_URL + 'record/source/' + sourceQ;
            $('.connect-row').append('<li class="card"><a href=' + sourceUrl + '><div class="card-title"><img src="' + BASE_IMAGE_URL + cardType + '-dark.svg" alt="' + cardType + ' icon"><h3>' + name + '</h3></div>'+'</a></li>');
        }
    } else if (cardType == "Place") {
        for (var i in connections) {
            var conn = connections[i];
            var name = conn['placelabel']['value'];
            var placeQ = conn['place']['value'];
            placeQ = placeQ.substring(placeQ.lastIndexOf('/') + 1);
            var placeUrl = BASE_URL + 'record/place/' + placeQ;
            $('.connect-row').append('<li class="card"><a href=' + placeUrl + '><div class="card-title"><img src="' + BASE_IMAGE_URL + cardType + '-dark.svg" alt="' + cardType + ' icon"><h3>' + name + '</h3></div>'+'</a></li>');
        }
    } else if (cardType == "CloseMatch") {
        cardType = 'Person';
        for (var i in connections) {
            var conn = connections[i];
            var name = conn['matchlabel']['value'];
            var matchQ = conn['match']['value'];
            matchQ = matchQ.substring(matchQ.lastIndexOf('/') + 1);
            var matchUrl = BASE_URL + 'record/person/' + matchQ;

            $('.connect-row').append('<li class="card"><a href=' + matchUrl + '><div class="card-title"><img src="' + BASE_IMAGE_URL + cardType + '-dark.svg" alt="' + cardType + ' icon"><h3>' + name + '</h3></div>'+'</a></li>');
        }
    } else {

    }
    //$('.connect-row li').css("background-image", "url("+BASE_IMAGE_URL+cardType+"Card.jpg)");
    //There does need to be a certain naming convention for the image names due to this function
}
function removeConnections(){
    $('.connect-row li').remove();
}


function loadConnections(){
    if (typeof(QID) == 'undefined'){

        function getUrlVars() {
            var vars = {};
            var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
                vars[key] = value;
            });
            return vars;
        }

        // get story kid
        QID = getUrlVars()["kid"];
        recordform = 'Story';
    }
    console.log(recordform)


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

            // display the counts for connections
            for (var form in connectionsArray){
                if (form == 'Person'){
                    $('#people').html('<div class="person-image"></div>'+connectionsArray['Person-count'] + ' People');
                } else if (form == 'Event'){
                    $('#event').html('<div class="event-image"></div>' + connectionsArray['Event-count'] + ' Events');
                } else if (form == 'Project') {
                    $('#project').html('<div class="project-image"></div>' + connectionsArray['Project-count'] + ' Projects');
                } else if (form == 'Source') {
                    $('#source').html('<div class="source-image"></div>' + connectionsArray['Source-count'] + ' Sources');
                } else if (form == 'Place') {
                    $('#place').html('<div class="place-image"></div>' + connectionsArray['Place-count'] + ' Places');

                } else if (form == 'CloseMatch') {
                    $('#closeMatch').html('<div class="person-image"></div>' + connectionsArray['CloseMatch-count'] + ' Close Matches');
                }
            }

            // hide tabs when they have no results
            if (!connectionsArray['Person-count']) {
                $('#people').hide();
            }
            if (!connectionsArray['Event-count']) {
                $('#event').hide();
            }
            if (!connectionsArray['Project-count']) {
                $('#project').hide();
            }
            if (!connectionsArray['Source-count']) {
                $('#source').hide();
            }
            if (!connectionsArray['Place-count']) {
                $('#place').hide();
            }
            if (!connectionsArray['CloseMatch-count']) {
                $('#closeMatch').hide();
            }

            // select the first tab with content
            var clickedFirst = false;
            $('.categories li').each(function (e) {
                if (!clickedFirst && $(this).css('display') != 'none') {
                    $(this).trigger('click');
                    clickedFirst = true;
                }
            })

        }
    });
}
