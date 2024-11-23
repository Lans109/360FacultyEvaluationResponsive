<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        
        var evaluation_completion = <?php echo json_encode($evaluation_completed); ?>;
        var evaluation_daily_completion = <?php echo json_encode($daily_completed); ?>;

        google.charts.load('current', {'packages':['corechart']});

        google.charts.setOnLoadCallback(drawChartMonitoring);
        
        google.charts.setOnLoadCallback(function () {
            drawChartMonitoring(evaluation_completion, 'Evaluation Completion Tracking', 'Date', 'completion_chart');
            drawChartMonitoring(evaluation_daily_completion, 'Daily Completion Tracking - <?php echo $date?>', 'Time', 'completion_daily_chart');
        });

        function drawChartMonitoring(dataArray, header, type, chartName) {
            const data = google.visualization.arrayToDataTable([
                [type, 'Completed Evaluation'], ...dataArray
            ]);

            const options = {
                backgroundColor: 'transparent',
                title: header,
                colors: ['#923534'], // Updated colors
                fontName: 'myFont2', // Custom font name
                fontSize: 14,
                titleTextStyle: {
                    fontSize: 15,   // Adjust font size of the header title
                    color: '#666', // Change the color of the title header
                },
                hAxis: {
                    title: '', // Remove title from x-axis
                    titleTextStyle: { fontSize: 15, color: '#666' },
                    textStyle: { fontSize: 15, color: '#ddd' },
                    gridlines: {
                        count: 5 // Limits horizontal gridlines to 5
                    },
                    slantedTextAngle: 45, // Angle of the rotated text
                    titlePosition: 'out' // Position the title outside the chart area
                },
                
                vAxis: {
                    title: '', // Remove title from y-axis
                    titleTextStyle: { fontSize: 16, color: '#666' },
                    textStyle: { fontSize: 12, color: '#ddd' },
                    gridlines: {
                        color: '#ddd',
                        count: 5 // Limits vertical gridlines to 5
                    },
                    minValue: 0,
                    maxValue: <?php echo json_encode($total_students); ?>,
                    format: '0',
                    titlePosition: 'out' // Position the title outside the chart area
                },
                legend: {
                    position: 'none' // Hides the legend completely
                },
                chartArea: {
                    left: 20,
                    top: 50,
                    right: 0,
                    bottom: 50,
                    width: '100%',
                    height: '100%'
                },
                curveType: 'function', // Enable smooth curves
                bar: { 
                    groupWidth: '50%' 
                },
                animation: {
                    duration: 1000,
                    easing: 'inAndOut'
                }
            };

            const chart = new google.visualization.LineChart(document.getElementById(chartName));
            chart.draw(data, options);
        }







        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            // Set Data
            const data = google.visualization.arrayToDataTable([
                ['Status', 'Percentage'],
                ['Evaluated', <?php echo json_encode($total_evaluated); ?>],
                ['Not Evaluated', <?php echo json_encode($total_not_evaluated); ?>],
            ]);

            // Set Options
            const options = {
                backgroundColor: 'transparent',
                title: 'Evaluation Percentage',
                titleTextStyle: {
                    fontSize: 15,   // Adjust title size to match the line chart title
                    color: '#666',  // Use the same header color as before
                },
                colors: ['#923534', '#ddd'],  // Use the same color scheme as the line chart
                fontName: 'myFont2', // Custom font name, if the line chart uses a specific font
                fontSize: 14,
                legend: {
                    position: 'bottom',  // Place the legend below the chart
                    alignment: 'center',  // Center the legend horizontally
                    textStyle: { fontSize: 14, color: '#666' }  // Adjust legend text style
                },
                chartArea: {
                    left: 20,
                    top: 50,
                    right: 20,
                    bottom: 50,
                    width: '100%', // Use full width of the container
                    height: '100%' // Use full height of the container
                },
                pieSliceText: 'none',  // Hide percentage numbers
                pieSliceTextStyle: { fontSize: 12, color: '#666'},  // Optional, adjust the font size and color for any text
                animation: {
                    duration: 1000,
                    easing: 'inAndOut'
                },
                pieHole: .6  // Similar hole size in the center for a donut chart effect
            };

            // Draw
            const chart = new google.visualization.PieChart(document.getElementById('myChart'));
            chart.draw(data, options);
        }


    </script>
</head>
<body>
    <div id="myChart" style="width: 100%; height: 250px;"></div>
    <div id="completion_chart" style="height: 350px;"></div> 
    <div id="completion_daily_chart" style="height: 350px;"></div>
</body>
</html>
