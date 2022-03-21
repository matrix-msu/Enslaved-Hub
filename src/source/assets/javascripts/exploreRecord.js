// use ajax to get full record html.
$(document).ready(function () {
    // name, details, timeline, connections, featured stories
    $('.spinner').show();
    $('#overlay').css('display', 'block');
    $('#overlay').css('opacity', '1');
    $.ajax({
        url: BASE_URL + "api/getFullRecordHtml",  // in exploreFunctions.php
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
                var timelineStr = html.timeline;
                $('.timeline-holder').html(timelineStr);
                initializeTimeline(); //function in timeline.js
            }
            // condenseRoles();
            changeSize();
            underlineTooltips();
        },
        'error': function (_, _, error) {
            console.log(`Error: ${error}`);
        },
        'complete': function() {
            $('.spinner').hide();
            $('#overlay').css('display', 'none');
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
		if($(this).hasClass('ethnolinguistic') && $(this).hasClass('descriptor')){
			return;
		}
        $(this).find('.detail-bottom div').addClass('detail-text');
        if ($(this).find('.detail-menu').length > 0 ) {
            $(this).find('.detail-bottom div').attr('tabindex', '0');
            $(this).find('.detail-bottom .detail-menu').attr('role', 'tooltip');
            $(this).find('.detail-bottom .detail-menu').css('display', 'none');
            $(this).find('.detail-bottom div').attr('aria-describedby','tooltip');
        }
    });
}
