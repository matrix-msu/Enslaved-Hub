$(document).ready(function () {
    // Fill in the counters
    $.ajax({
        url: BASE_URL + "api/getHomePageCounters",
        type: "GET",
        'success': function (data) {
            result_array = JSON.parse(data);
            for (var key in result_array) {
                $("#count-"+key).html(result_array[key])
            }
        }
    });

    // Create the 2 stories cards
    $.ajax({
        url: BASE_URL + "api/blazegraph",
        type: "GET",
        data: {
            preset: 'stories',
            filters:  {limit: 2},
            templates: ['homeCard']
        },
        'success': function (data) {
            result_array = JSON.parse(data);
            console.log(result_array);
            result_array['homeCard'].forEach(function (card) {
                $(card).appendTo("#stories-list");
            });
        }
    });

    // // Create the 2 projects cards
    // $.ajax({
    //     url: BASE_URL + "api/blazegraph",
    //     type: "GET",
    //     data: {
    //         preset: 'projects',
    //         filters:  {limit: 2},
    //         templates: ['homeCard']
    //     },
    //     'success': function (data) {
    //         result_array = JSON.parse(data);
    //         result_array['homeCard'].forEach(function (card) {
    //             $(card).appendTo("#projects-list");
    //         });
    //     }
    // });
});
