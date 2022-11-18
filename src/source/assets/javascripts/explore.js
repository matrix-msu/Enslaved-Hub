$(document).ready(function(){
    if(JS_EXPLORE_FORM == 'Places'){
        JS_EXPLORE_FILTERS = "Place Type";
    }
    if(JS_EXPLORE_FORM == 'Sources'){
        JS_EXPLORE_FILTERS = "Source Type";
    }
    $('.cards-featured li').click(function(){
        window.location = $(this).find("a").attr("href");
    });

    $('.cards li').click(function () {
        window.location = $(this).find("a").attr("href");
    });

    $(".sort-cards p").click(function (e) { // toggle show/hide per-page submenu
        e.stopPropagation();
        $(this).find("img:first").toggleClass('show');
        $(this).next().toggleClass('show');
    });

    $(document).click(function () { // close things with clicked-off
        $('.sort-cards p').find("img:first").removeClass('show');
        $('.sort-cards p').next().removeClass('show');
    });

    //Get counts only if on explorefilter page
    if( typeof JS_EXPLORE_FILTERS !== 'undefined' ){
        if (JS_EXPLORE_FILTERS == "Date") {
            $.ajax({
                url: BASE_URL + 'api/getDateRange',
                method: "GET",
                'success': function (data) {
                    data = JSON.parse(data);
                    dates = []
                    $.each(data, function(_, date) {
                        dates.push(date['value_as_string'])
                    });
                    var min = Math.min.apply(Math, dates);
                    var max = Math.max.apply(Math, dates);
					// Doing this for safety purposes
                    if (min <= max) {
                        for (var i = min; i <= max; i++) {
                            $("#event-from").append("<option value='"+i+"'>"+i+"</option>");
                            $("#event-to").append("<option value='"+i+"'>"+i+"</option>");
                        }
						$('#event-from').select2({
					        placeholder: "Enter Start Year"
					    });
					    $('#event-from').next().find('input[placeholder="Enter Start Year"]').attr('id', 'event-from');
					    $('#event-from').removeAttr('id');

					    $('#event-to').select2({
					        placeholder: "Enter End Year"
					    });
					    $('#event-to').next().find('input[placeholder="Enter End Year"]').attr('id', 'event-to');
					    $('#event-to').removeAttr('id');

						$('.select2-selection--multiple').append('<span class="select2-selection__arrow" role="presentation"></span>');

						$("form").submit(function(e){
							var date = [];
							$('.select2-selection__choice').each(function(){
								date.push($(this).attr('title'));
							})
							var url = $("form").attr('action');
							url += '?date='+date.join('-');
							location.href = url;
							return false;
						});
                    }

                }
            });
            return;
        }
        else{
            $.ajax({
                url: BASE_URL + 'api/filteredCounts',
                method: "GET",
                data: {
                    field: JS_EXPLORE_FILTERS,
                    type: JS_EXPLORE_FORM
                },
                'success': function (data) {
                    data = JSON.parse(data);

                    $.each(data['aggregations'][JS_EXPLORE_FILTERS]['buckets'], function(_, bucket) {
                        var span = $("a:contains("+bucket['key']+")").find('span');
                        if ($(span).length > 0){
                            $(span).html(bucket['doc_count']);
                        }
                    });
                    if (JS_EXPLORE_FILTERS === 'Gender') {
                        var span = $("a:contains(No Sex Recorded)").find('span');
                        if ($(span).length > 0){
                            $(span).html(data['aggregations']['No Sex Recorded']['doc_count']);
                        }
                    }
                    $(".cards li").each(function(){
                        if($(this).find("span").html() != 0){
                            $(this).removeClass("hide-category");
                        }
                    });
                },
                'error': function (data) {
                    console.log(data)
                }
            });
        }
    }
    else if(typeof JS_EXPLORE_FORM !== 'undefined'){
        //person, place, event, source
        var type = JS_EXPLORE_FORM;
        if (type == 'People')
            type = 'Person';
        else
            type = type.slice(0, -1); //remove s on the end

        $.ajax({
            url: BASE_URL + "api/getFeatured",
            type: "GET",
            data: {templates: type},
            success: function (data) {
                data = JSON.parse(data);
                data[type].forEach(function (e) {
                    $('.explore-featured .cards-featured').append(e);
                });
            }
        });

    }

});
