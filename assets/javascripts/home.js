$(document).ready(function () {
    $.ajax({
        url: BASE_URL + "api/getTypeCounts",
        type: "GET",
        'success': function (data) {
            result = JSON.parse(data);
            $("#count-all").html(result['hits']['total']['value'])
            $.each(result['aggregations']['type']['buckets'], function(_, bucket) {
                $("#count-"+bucket['key']).html(bucket['doc_count']);
            });
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
            result_array['homeCard'].forEach(function (card) {
                $(card).appendTo("#stories-list");
            });
        }
    });
});
