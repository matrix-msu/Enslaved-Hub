$(document).ready(function () {
    $.ajax({
        url: BASE_URL+"api/blazegraph",
        type: "GET",
        data: {preset: 'projects2', templates: ['homeCard']},
        success: function (data) {
            data = JSON.parse(data);
            data['homeCard'].forEach(function (e) {
                $('.container.cardwrap > .row').append(e);
            })
        }
    })
});
