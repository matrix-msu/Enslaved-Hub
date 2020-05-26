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
            console.log(json);
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
            // condenseRoles();
            changeSize();
            underlineTooltips();
        },
        'error': function (xhr, status, error) {
            console.log('fail');
        }
    });
});


function changeSize(){
  var title_length = $('.middlewrap h1').text().length;
  if (title_length >= 60){
      $('.middlewrap h1').css('font-size','45px');
  }
}

function underlineTooltips(){
    $('.detailwrap .detail').each(function(){
        $(this).find('.detail-bottom div').first().addClass('detail-text');
        if ($(this).find('.detail-menu').length > 0 ) {
            $(this).find('.detail-bottom div').attr('tabindex', '0');
            $(this).find('.detail-bottom .detail-menu').attr('role', 'tooltip');
            $(this).find('.detail-bottom div').first().attr('aria-describedby','tooltip');
            $(this).find('.detail-bottom div').first().css('text-decoration','underline');
        }
    });
}
