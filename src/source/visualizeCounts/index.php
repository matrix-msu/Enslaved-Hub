<html>
<head>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">

    google.charts.load('current', {'packages':['corechart','table']});
    google.charts.setOnLoadCallback(drawChart);

    var counts = String.raw`<?php echo file_get_contents('https://manta.matrix.msu.edu/msumatrix/public/exports/enslaved.org/visualizeCounts/counts.json');?>`;
    counts = JSON.parse(counts);
    var config = `<?php echo file_get_contents('config.json');?>`;
    config = JSON.parse(config);

    // $.each(counts, function(project,projectCounts){
    //     $.each(projectCounts, function(pid,count){
    //         counts[project][pid] = {type:'PieChart',width:"490",height:"400",title:""}
    //     });
    // });
    // console.log(JSON.stringify(counts));

    function drawChart() {
        $.each(config, function(project,projectConfig){
            var escapedProject = project.replaceAll(' ', '_').replaceAll(':', '').replaceAll(',', '');
            $('body').append(`<h1>${project}</h1><hr><div class="pcontainer" id="pcontainer_${escapedProject}"></div>`);
            $.each(projectConfig, function(pid,typeConfig){
                if(typeConfig['type'] == 'none'){
                    return;
                }
                var data = new google.visualization.DataTable();
                var title = pid;
                if(typeConfig['title'] != ''){
                    title = typeConfig['title'];
                }
                data.addColumn('string', title);
                data.addColumn('number', 'Count');
                var options = {'title':title,'width':typeConfig['width'],'height':typeConfig['height'],'sliceVisibilityThreshold':.001,is3D:true};
                // console.log(project, pid)
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

                pid = pid.replaceAll(' ', '_');
                $(`#pcontainer_${escapedProject}`).append('<div class="chartcontainer" id="chart_div_'+escapedProject+pid+'"></div>');
                var chart = new google.visualization[typeConfig['type']]($('#chart_div_'+escapedProject+pid)[0]);
                chart.draw(data, options);
            });
        });
    }
    </script>
    <style>
        .pcontainer{
            width: 980px;
            /* height: 570px; */
            border: 2px solid black;
            margin: 0 auto;
        }
        .chartcontainer{
            display: inline-block;
            margin: 5px;
        }
    </style>
</head>

<body>
    <!--Div that will hold the pie chart-->
    <!-- <div id="chart_div"></div> -->
</body>
</html>
