$(document).ready(function(){
    $('.lead-card').click(function(){
        window.location = $(this).find("a").attr("href");
        return false;
    });

    $.ajax({
        url: BASE_URL+"api/getProjectFullInfo",
        type: "GET",
        data: {qid: QID},
        success: function (data) {
            data = JSON.parse(data);
            console.log(data);
            $(".project-headers > h1").html(data.title.value);
            $("#current-title").html(data.title.value);
            $(".container.infowrap").html(data.desc.value);
            if ('link' in data) {
                $('#details').click(function () {
                    document.location.href = data.link.value;
                });
            }
            else {
                $('.project-button').hide();
            }
            var pis = data.piNames.value.split("||");
            pis.forEach(function(name) {
                $('.leads').append('<div class="lead-card"><div class="lead-text"><h3>'+name+'</h3></div></div>');
            });
            if ('contributor' in data) {
                var contributors = data.contributor.value.split("||");
                contributors.forEach(function(name) {
                    $('.contributors').append('<div class="contributor-card"><div class="contributor-text"><h3>'+name+'</h3></div></div>');
                });
            }
        }
    });

    $.ajax({
        url: BASE_URL+"api/blazegraph",
        type: "GET",
        data: {preset: 'projectAssoc', templates: ['projectAssoc'], qid: QID},
        success: function (data) {
            data = JSON.parse(data);
            var str = "";
            data['projectAssoc'].forEach(function (e) {
                str += e;
            });
            $(".project-headers > h2").html(str);
        }
    });

    // Get Cards
    var templates = ['searchCard', 'gridCard'];
    var filters = {};
    var offset = 0;
    var limit = 10;

    $.ajax({
        url: BASE_URL + "api/blazegraph",
        type: "GET",
        data: {
            preset: 'singleProject',
            filters: filters,
            templates: templates,
            qid: QID,
            limit: limit,
            offset: offset
        },
        success: function (data) {
          
          console.log("results: ", data);

           $("span.grid-view").trigger("click");
        }
    });


});
