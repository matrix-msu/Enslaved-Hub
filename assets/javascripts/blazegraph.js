$(".previous-query").css('cursor','pointer').click(function(e){
    $('textarea').val( $(this).html() );
});
$('#submit').click(function(e){
    e.preventDefault();
    $.ajax({
        url: "api/blazegraph",
        type: "GET",
        data: {query: $('textarea').val() },
        success: function (data) {
            $(".blazegraph-records").html(data);
        }
    });
});
$('.delete').click(function(e){
    e.preventDefault();
    $id = $(this).data('id');
    $.ajax({
        url: "api/blazegraph",
        type: "GET",
        data: {delete: $id },
        success: function (data) {
            window.location.href = window.location.href;
        }
    });
});
$('#results').on('click', 'uri', function(){
    var win = window.open($(this).html(), '_blank');
    win.focus();
});

$('.query-options button').click(function(e){
    var clickedClass = $(this).attr('class');
    var queryOption;

    switch (clickedClass){
        case 'people-query-btn':
            queryOption = 'people';
            break;
        case 'places-query-btn':
            queryOption = 'places';
            break;
        case 'events-query-btn':
            queryOption = 'events';
            break;
        case 'sources-query-btn':
            queryOption = 'sources';
            break;
        case 'projects-query-btn':
            queryOption = 'projects';
            break;
        default:
            return;
    }
    console.log(queryOption)

    e.preventDefault();
    $.ajax({
        url: "api/blazegraph",
        type: "GET",
        data: { preset: queryOption },
        success: function (data) {
            $(".blazegraph-records").html(data);
        }
    });
});