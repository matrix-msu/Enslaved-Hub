// Connections for story records (based on connections.js, but heavily modified)

var connectionsArray;   // array of all connections

$(document).ready( function() {
    //when page loads trigger click on first category to load cards
    loadConnections();
    refreshCards();

    $('li.story-unselected').click(function(){
        $('.story-selected').removeClass('story-selected');
        $(this).addClass('story-selected');
        removeConnections();
        refreshCards();
    });
});

function refreshCards() {
    if($("#people").hasClass("story-selected"))
        displayConnections("Person");
    if($("#event").hasClass("story-selected"))
        displayConnections("Event");
    if($("#place").hasClass("story-selected"))
        displayConnections("Place");
    if($("#project").hasClass("story-selected"))
        displayConnections("Project");
    if($("#source").hasClass("story-selected"))
        displayConnections("Source");
}

function displayConnections(cardType) {
    var connections = connectionsArray[cardType];

    for(var i in connections) {
        var conn = connections[i];
        $('.story-connect-row').append('<li class="card"><a href=' + conn["Qid"] + '><div class="card-title"><img src="' + BASE_IMAGE_URL + cardType + '-dark.svg" alt="' + cardType + ' icon"><h3>' + conn["Label"] +'</h3></div>'+'</a></li>');
    }
}

function removeConnections() {
    $('.story-connect-row li').remove();
}

function loadConnections() {
    connectionsArray = { "Person": [], "Event": [], "Place": [], "Project": [], "Source": [] };

    for(var key in storyConnectionData) {
        connect = storyConnectionData[key];

        if(connect['Qid'].includes("person"))
            connectionsArray["Person"].push(connect);
        if(connect['Qid'].includes("event"))
            connectionsArray["Event"].push(connect);
        if(connect['Qid'].includes("place"))
            connectionsArray["Place"].push(connect);
        if(connect['Qid'].includes("project"))
            connectionsArray["Project"].push(connect);
        if(connect['Qid'].includes("source"))
            connectionsArray["Source"].push(connect);
    }

    // display the counts for connections
    if(connectionsArray["Person"].length > 0)
        $('#people').html('<div class="person-image"></div>'+connectionsArray["Person"].length + ' People');
    else
        $('#people').hide();
    if(connectionsArray["Event"].length > 0)
        $('#event').html('<div class="event-image"></div>' + connectionsArray["Event"].length + ' Events');
    else
        $('#event').hide();
    if(connectionsArray["Place"].length > 0)
        $('#place').html('<div class="place-image"></div>' + connectionsArray["Place"].length + ' Places');
    else
        $('#place').hide();
    if(connectionsArray["Project"].length > 0)
        $('#project').html('<div class="project-image"></div>' + connectionsArray["Project"].length + ' Projects');
    else
        $('#project').hide();
    if(connectionsArray["Source"].length > 0)
        $('#source').html('<div class="source-image"></div>' + connectionsArray["Source"].length + ' Sources');
    else
        $('#source').hide();

    // select the first tab with content
    var clickedFirst = false;
    $('.story-categories li').each(function (e) {
        if (!clickedFirst && $(this).css('display') != 'none') {
            $(this).trigger('click');
            clickedFirst = true;
        }
    })
}
