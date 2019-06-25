// use ajax to get full record html. 
$(document).ready(function () {
    // name, details, timeline, connections, featured stories
    $.ajax({
        url: BASE_URL + "api/getPersonRecordHtml",  // in exploreFunctions.php
        type: "GET",
        data: {
            QID: QID,
            type: recordform
        },
        'success': function (json) {
            var html = JSON.parse(json);
            $('.middlewrap').html(html.header);
            $('.infowrap').html(html.description);
            $('.detail-section').html(html.details);
            if (recordform == 'person') {
                $('.timeline-container').html(html.timeline);
                initializeTimeline(); //function in timeline.js
            }
        },
        'error': function (xhr, status, error) {
            console.log('fail');
        }
    });

});
