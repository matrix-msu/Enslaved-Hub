$(document).ready(function(){
    $('.lead-card').click(function(){
        window.location = $(this).find("a").attr("href");
        return false;
    });

    $.ajax({
        url: infourl,
        type: "GET",
        success: function (data) {
            data = data['entities'][QID];
            console.log(data);
            $(".project-headers > h1").html(data.labels.en.value);
            $("#current-title").html(data.labels.en.value);
            $(".container.infowrap").html(data.descriptions.en.value);
            if ('P29' in data.claims) {
                $('#details').click(function () {
                    document.location.href = data.claims.P29[0].mainsnak.datavalue.value;
                });
            }
            else {
                $('.project-button').hide();
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
});
