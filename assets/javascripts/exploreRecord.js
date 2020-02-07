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
            $('.middlewrap').html(html.header);
            $('.infowrap').html(html.description);
            $('.detail-section').html(html.details);
            if (recordform == 'person') {
                var timelineStr = html.timeline;
                $('.timeline-holder').html(timelineStr);
                initializeTimeline(); //function in timeline.js
            }
            condenseRoles();
        },
        'error': function (xhr, status, error) {
            console.log('fail');
        }
    });


});



function condenseRoles(){
    var roles_num = $('.detailwrap .roles .detail-bottom').length;
    console.log(roles_num);
    if(roles_num > 1){
        //Add Show All Roles button, when pressed changes to Condense Roles
        $('.detailwrap .roles .detail-bottom:eq(0)').after('<div class="show-all"><p>Show All Roles</p><img src="'+BASE_IMAGE_URL+'Arrow-colored.svg" alt="Arrow"></div>');
        $('.detailwrap .roles .detail-bottom:last-child').after('<div class="show-all hide"><p>Show All Roles</p><img src="'+BASE_IMAGE_URL+'Arrow-colored.svg" alt="Arrow"></div>');
        $('.detailwrap .roles .detail-bottom:eq(0)').nextAll('.detail-bottom').addClass('hide');
    }

    $('.detailwrap .roles .show-all').click(function(){
        $('.detailwrap .roles .show-all').toggleClass('show');
        if($(this).hasClass('show')){
            $('.detailwrap .roles .show-all').find('p').html('Condense Roles');
            $('.detailwrap .roles .show-all:eq(1)').removeClass('hide');
            $('.detailwrap .roles .detail-bottom').removeClass('hide');
        }
        else{
            $('.detailwrap .roles .show-all').find('p').html('Show All Roles');
            $('.detailwrap .roles .show-all:eq(1)').addClass('hide');
            $('.detailwrap .roles .detail-bottom:eq(0)').nextAll('.detail-bottom').addClass('hide');
        }
    })
}
