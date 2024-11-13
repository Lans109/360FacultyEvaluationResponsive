<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Load Google Charts Library -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Load the Visualization API and the corechart package.
        google.charts.load('current', {'packages':['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            // Set Data
            const data = google.visualization.arrayToDataTable([
                ['Price', 'Size'],
                [50, 7], [60, 8], [70, 8], [80, 9], [90, 9], 
                [100, 9], [110, 10], [120, 11], [130, 14], 
                [140, 14], [150, 15]
            ]);

            // Set Options
            const options = {
                title: 'House Prices vs Size',
                hAxis: {title: 'Square Meters'},
                vAxis: {title: 'Price in Millions'},
                legend: 'none'
            };

            // Draw Chart
            const chart = new google.visualization.LineChart(document.getElementById('myChart'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>
    <div id="myChart" style="width: 600px; height: 400px;"></div>
</body>
</html>
