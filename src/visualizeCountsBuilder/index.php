<html>
<head>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">

    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    var counts = JSON.parse('<?php echo file_get_contents('counts.json');?>');

    function drawChart() {
        $.each(counts, function(pid,count){
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Topping');
            data.addColumn('number', 'Slices');
            counts[pid].sort(function(a, b) {
                return b[1] - a[1];
            });
            data.addRows(counts[pid]);

            // var options = {'title':pid,'width':'100%','height':400,'sliceVisibilityThreshold':.001};
            // pid = pid.replace(' ', '_');
            // $('body').append('<div id="chart_div_'+pid+'"></div>');
            // var chart = new google.visualization.PieChart($('#chart_div_'+pid)[0]);
            // chart.draw(data, options);

            var options = {'title':pid,'width':'100%','height':400,'sliceVisibilityThreshold':.001,is3D:true};
            pid = pid.replace(' ', '_');
            $('body').append('<div id="chart_div_3d_'+pid+'"></div>');
            var chart = new google.visualization.PieChart($('#chart_div_3d_'+pid)[0]);
            chart.draw(data, options);

            // var options = {'title':pid+' Hole','width':'100%','height':400,'sliceVisibilityThreshold':.001,pieHole:.55};
            // pid = pid.replace(' ', '_');
            // $('body').append('<div id="chart_div_hole_'+pid+'"></div>');
            // var chart = new google.visualization.PieChart($('#chart_div_hole_'+pid)[0]);
            // chart.draw(data, options);
        });
    }
    </script>
</head>

<body>
    <!--Div that will hold the pie chart-->
    <!-- <div id="chart_div"></div> -->
</body>
</html>
