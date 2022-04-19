google.charts.load('current', {'packages':['corechart','table']});
google.charts.setOnLoadCallback(drawChart);

var concat_url = BASE_URL + "visualizedata";
var url = new URL(concat_url);
var search_params = url.searchParams;

// create the config file
// $.each(counts, function(project,projectCounts){
//     $.each(projectCounts, function(pid,count){
//         counts[project][pid] = {type:'PieChart',width:"490",height:"400",title:""}
//     });
// });
// console.log(JSON.stringify(counts));

$(document).ready( function() {
    const params = new URLSearchParams(window.location.search);
    if(params.has('type')){
        var type = params.get('type');
        $('#chart-type option[value="' + type + '"]').prop('selected', true);
    }
    if(params.has('proj')){
        var proj = params.get('proj');
        $('#chart-project option[value="' + proj + '"]').prop('selected', true);
    }
    updateChartFields();
    if(params.has('field')){
        var field = params.get('field');
        $('#chart-field option[value="' + field + '"]').prop('selected', true);
    }
    $('#chart-type').select2({
        placeholder: "Select Chart Type"
    });
    $('#chart-field').select2({
        placeholder: "Select Chart Field"
    });
    $('#chart-project').select2({
        placeholder: "Select Project"
    });
    $('.select2-selection--multiple').append('<span class="select2-selection__arrow" role="presentation"></span>');
});

$("#chart-project").change(function() {
    updateChartFields();
});

$("#chart-type, #chart-field, #chart-project").change(function() {
    changeIframe();
    $('.datawrap').html('');
    drawChart();
});

function updateChartFields(){
    var project = $('#chart-project').val();
    if( project == "Maranhão Inventories Slave Database"){
        project = "Maranhao Inventories Slave Database";
    }
    var projectConfig = config[project];
    $('#chart-field').html('');
    $('#chart-field').append('<option value="po">Project Overview</option>');
    $.each(projectConfig, function(pid,typeConfig){
        var title = pid;
        if(typeConfig['title'] != ''){
            title = typeConfig['title'];
        }
        $('#chart-field').append(`<option value="${pid}">${title}</option>`);
    });
    $('#chart-field').select2({
        placeholder: "Select Chart Field"
    });
}

function drawChart() {
    // $.each(config, function(project,projectConfig){
        var project = $('#chart-project').val();
        if( project == "Maranhão Inventories Slave Database"){
            project = "Maranhao Inventories Slave Database";
        }
        var projectConfig = config[project];
        var escapedProject = project.replaceAll(' ', '_').replaceAll(':', '').replaceAll(',', '');
        $('.datawrap').append(`<div class="pcontainer" id="pcontainer_${escapedProject}"></div>`);
        var selectedType = $('#chart-type').val();
        var selectedField = $('#chart-field').val();
        $.each(projectConfig, function(pid,typeConfig){
            if(
                typeConfig['type'] == 'none' ||
                (selectedType != "Dashboard" && selectedType != typeConfig['type']) ||
                (selectedField != "po" && selectedField != pid)
            ){
                return;
            }
            var title = pid;
            if(typeConfig['title'] != ''){
                title = typeConfig['title'];
            }
            var escapedPid = pid.replaceAll(' ', '_');
            $(`#pcontainer_${escapedProject}`).append('<div class="chartcontainer" id="chart_div_'+escapedProject+escapedPid+'"></div>');
            if(typeConfig['type'] == 'TotalRecords'){
                var totalRecords = 0;
                var recordsToCount = ['Place','Event','Person','Entity with Provenance'];
                $.each(counts[project]['instance of'], function(index,valueCount){
                    if(recordsToCount.includes(valueCount[0])){
                        totalRecords += valueCount[1];
                    }
                });
                $('#chart_div_'+escapedProject+escapedPid).append(
                    `<p class="customChartTitle">${title}</p><p class="totalNum">${totalRecords}</p><p class="totalLabel">Total Records</p>`
                );
                $('#chart_div_'+escapedProject+escapedPid).css({width:typeConfig['width'],height:typeConfig['height']});
                return;
            }else if(typeConfig['type'] == 'RecordTypes'){
                var typeCounts = {Person:0, Event:0, Place:0, 'Entity with Provenance':0};
                var recordsToCount = ['Person','Event','Place','Entity with Provenance'];
                $.each(counts[project]['instance of'], function(index,valueCount){
                    if(recordsToCount.includes(valueCount[0])){
                        typeCounts[valueCount[0]] += valueCount[1];
                    }
                });
                $('#chart_div_'+escapedProject+escapedPid).append(
                    `<p class="customChartTitle">${title}</p>`+
                    `<p class="typeNum">${typeCounts.Person}</p><p class="typeNum">${typeCounts.Event}</p><p class="typeNum">${typeCounts.Place}</p><p class="typeNum">${typeCounts['Entity with Provenance']}</p>`+
                    `<br><p class="typeLabel">People</p><p class="typeLabel">Events</p><p class="typeLabel">Places</p><p class="typeLabel">Sources</p>`
                );
                $('#chart_div_'+escapedProject+escapedPid).css({width:typeConfig['width'],height:typeConfig['height']});
                return;
            }
            var data = new google.visualization.DataTable();
            data.addColumn('string', title);
            data.addColumn('number', 'Count');
            // var options = {'title':title,'width':typeConfig['width'],'height':typeConfig['height'],'sliceVisibilityThreshold':.001,is3D:true};
            var options = {'title':title,'width':typeConfig['width'],'height':typeConfig['height'],'sliceVisibilityThreshold':0};
			console.log(project,pid)
            counts[project][pid].sort(function(a, b) {
                return b[1] - a[1];
            });
            if(typeConfig['type'] == 'BarChart'){
                counts[project][pid].sort(function(a, b) {
                    var compA = a[0].replaceAll('Age ', '');
                    if(compA.includes("Months") || compA.includes("Month")){
                        compA = compA.replaceAll(' Months', '');
                        compA = compA.replaceAll(' Month', '');
                        compA = compA / 12;
                    }
                    var compB = b[0].replaceAll('Age ', '');
                    if(compB.includes("Months") || compB.includes("Month")){
                        compB = compB.replaceAll(' Months', '');
                        compB = compB.replaceAll(' Month', '');
                        compB = compB / 12;
                    }
                    return parseFloat(compB) - parseFloat(compA);
                });
                options['legend'] = { position: "none" };
            }
            data.addRows(counts[project][pid]);
            var chart = new google.visualization[typeConfig['type']]($('#chart_div_'+escapedProject+escapedPid)[0]);
            chart.draw(data, options);
        });
    // });
}

function changeIframe(){
    var type = $('#chart-type').val();
    var field = $('#chart-field').val();
    var project = $('#chart-project').val()

    //change url
    search_params.set('type', type);
    search_params.set('field', field);
    search_params.set('proj', project);

    url.search = search_params.toString();
    var new_url = url.toString();
    window.history.replaceState(0, "", new_url);


    //Add Dynamic 'View Project Records' Link
    if(project !== "All"){
        document.getElementById("search-records-link").style.display = "block";
    }else{
        document.getElementById("search-records-link").style.display = "none";
    }

    var projLink = document.getElementById("search-records-link");

	var link_params = new URLSearchParams();

	link_params.set('projects', project);
    link_params.set('limit', '20');
    link_params.set('offset', '0');
    link_params.set('sort_field', 'label.sort');
    link_params.set('display', 'people');

    var link_url = BASE_URL+'search/all?'+link_params.toString();

	projLink.setAttribute('href', link_url);
}
