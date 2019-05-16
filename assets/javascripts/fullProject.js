$(document).ready(function(){
    /*
        Get project information and display in views (replace placeholders in views)
        Info includes, project name, description, contribution, and link to project site
    */
    $.ajax({
        url: BASE_URL+"api/getProjectFullInfo",
        type: "GET",
        data: {qid: QID},
        success: function (data) {
            data = JSON.parse(data);
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

    /*
        Get resources number to be displayed next to the title (aka project name)
        Data shows how much people, events, places, and sources are associated with the project
    */
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

    /* 
        Get data (return data embedded in html format both in grid (cards) and table view (table))
        Replace blazegraph-records class innerHTML content with cards returned
        Replace search-result-table class innerHTML content with table returned
    */
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
           if(data)
           {
                data = JSON.parse(data);
                gridDisplay(data['gridCard']);
                tableDisplay(data['searchCard']);
           }
        }
    });


    function gridDisplay(data)
    {
        // Display cards
        $(".blazegraph-records").html("");
        data.forEach(function (card) {
            $(card).appendTo(".blazegraph-records");
        });

        $("span.grid-view").trigger("click");
    }

    function tableDisplay(data)
    {
        let thead = '\
        <tr>\
            <th class="meta">FIRSTNAME</th>\
            <th class="meta">LASTNAME</th>\
            <th class="meta">ORIGIN</th>\
            <th class="meta">STATUS</th>\
            <th class="meta">STRAT YEAR</th>\
            <th class="meta">END YEAR</th>\
            <th class="meta">SEX</th>\
            <th class="meta">LOCATION</th>\
        <tr>';
        $("thead").html(thead);

        // Display table rows
        $("tbody").html("");
        data.forEach(function (card) {
            $(card).appendTo("tbody");
        });
    }
});
