// use ajax to get full record html.
$(document).ready(function () {
    // name, details, timeline, connections, featured stories
    $.ajax({
        url: BASE_URL + "api/getFullRecordHtml",  // in exploreFunctions.php
        type: "GET",
        data: {
            QID: QID,
            type: recordform
        },
        'success': function (json) {
            var html = JSON.parse(json);
            console.log(html);
            $('.middlewrap').html(html.header);
            $('.infowrap').html(html.description);
            $('.detail-section').html(html.details);
            if (recordform == 'person') {
                var timelineStr = html.timeline;
                $('.timeline-holder').html(timelineStr);
                initializeTimeline(); //function in timeline.js
            }
        },
        'error': function (xhr, status, error) {
            console.log('fail');
        }
    });
});
