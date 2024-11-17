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
                [type, 'Completed Evaluation'],...dataArray
            ]);

            const options = {
                title: header,
                colors: ['#a2252f', '#e6693e', '#ec8f6e', '#f3b49f', '#f6c7b6'],  
                fontName: 'Times New Roman',
                fontSize: 14,
                hAxis: {
                    title: type,
                    titleTextStyle: { fontSize: 16 },
                    textStyle: { fontSize: 15 },
                    gridlines: { color: '#e0e0e0' }
                },
                vAxis: {
                    title: 'No. of Students',
                    titleTextStyle: { fontSize: 16 },
                    textStyle: { fontSize: 12 },
                    gridlines: { color: '#e0e0e0' },
                    minValue: 0,
                    maxValue: <?php echo json_encode($total_students); ?>,
                    format: '0'
                },
                legend: {
                    position: 'top',
                    alignment: 'center',
                    textStyle: { fontSize: 15 }
                },
                chartArea: {
                    left: 40,
                    top: 150,
                    right: 40,
                    bottom: 50
                },
                bar: { groupWidth: '50%' },
                pointSize: 5,
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
                title: 'Evaluation Percentage',
                colors: ['#a2252f', 'grey'],
                fontName: 'Times New Roman',
                fontSize: 14,
                legend: {
                    position: 'top',
                    alignment: 'center',
                    textStyle: { fontSize: 15 }
                },
                chartArea: {
                    left: 40,
                    top: 150,
                    right: 40,
                    bottom: 50
                },
                pieSliceText: 'percentage',
                pieSliceTextStyle: { fontSize: 12 },
                animation: {
                    duration: 1000,
                    easing: 'inAndOut'
                },
                pieHole: 0.5
            };

            // Draw
            const chart = new google.visualization.PieChart(document.getElementById('myChart'));
            chart.draw(data, options);
            }
    </script>
</head>
<body>
    <div id="myChart" style="width: 100%; height: 400px;"></div>
    <div id="completion_chart" style="width: 100%; height: 400px;"></div>
    <div id="completion_daily_chart" style="width: 100%; height: 400px;"></div>
</body>
</html>
