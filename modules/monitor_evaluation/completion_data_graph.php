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

        google.charts.load('current', { 'packages': ['corechart'] });

        google.charts.setOnLoadCallback(drawChartMonitoring);

        google.charts.setOnLoadCallback(function () {
            drawChartMonitoring(evaluation_completion, 'Evaluation Completion Tracking', 'Date', 'completion_chart');
            drawChartMonitoring(evaluation_daily_completion, 'Daily Completion Tracking - <?php echo $date ?>', 'Time', 'completion_daily_chart');
        });

        function drawChartMonitoring(dataArray, header, type, chartName) {
            const data = google.visualization.arrayToDataTable([
                [type, 'Completed Evaluation'], ...dataArray
            ]);

            // Determine the number of data points
            const dataSize = dataArray.length;

            // Adjust options based on data size
            let hAxisOptions = {
                title: '', // Remove title from x-axis
                titleTextStyle: { fontSize: 15, color: '#666', bold: true },
                textStyle: { fontSize: 15, color: '#ddd' },
                gridlines: {
                    color: '#e0e0e0',
                    count: Math.min(10, dataSize) // Limit gridlines to 10 or fewer
                },
                textPosition: 'out' // Position axis labels outside the chart
            };

            // Add slanted text only if data size exceeds a threshold
            if (dataSize > 10) {
                hAxisOptions.slantedText = true;
                hAxisOptions.slantedTextAngle = Math.min(60, 15 + dataSize / 5); // Adjust angle based on size
            }

            const options = {
                backgroundColor: 'transparent',
                title: header,
                titleTextStyle: {
                    fontSize: 15,   // Adjust font size of the header title
                    color: '#666',  // Change the color of the title header
                    bold: true,     // Make the title bold
                    italic: false,  // Disable italics
                },
                colors: [
                    '#923534', // Maroon (primary color)
                    '#b2493b', // Reddish-brown (complementary to maroon)
                    '#ff8c42', // Soft amber (to add warmth and contrast)
                    '#4a3f35', // Dark brown (for depth and grounding)
                    '#ffb6b6', // Soft blush pink (light and subtle)
                    '#2c3e50', // Dark blue-gray (providing contrast)
                    '#e1c8b1', // Beige (soft neutral to balance boldness)
                ],
                fontName: 'myFont2', // Custom font name
                fontSize: 14,
                hAxis: hAxisOptions,
                vAxis: {
                    title: '', // Remove title from y-axis
                    titleTextStyle: { fontSize: 16, color: '#666', bold: true },
                    textStyle: { fontSize: 12, color: '#ddd' },
                    gridlines: {
                        color: '#ddd', // Gridline color
                        count: 5 // Limits vertical gridlines to 5
                    },
                    format: '0', // Format y-axis labels as integers
                },
                chartArea: {
                    left: 50, // Adjust padding and margins
                    top: 50,
                    right: 20,
                    bottom: 50,
                    width: '90%', // Adjust chart area width
                    height: '80%' // Adjust chart area height
                },
                tooltip: { isHtml: true }, // HTML tooltips
                animation: {
                    duration: 1000, // Smooth animation duration
                    easing: 'inAndOut' // Easing effect
                },
                focusTarget: 'category', // Highlight category on hover
                enableInteractivity: true, // Allow interaction
                annotations: {
                    textStyle: { fontSize: 12, color: '#444', bold: true }, // Annotation text style
                    alwaysOutside: true, // Place annotations outside
                },
                legend: {
                    position: 'none' // Hides the legend completely
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
                ['Evaluated', <?php echo json_encode($students_completed); ?>],
                ['Not Evaluated', <?php echo json_encode($students_not_completed); ?>],
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
                tooltip: {
                    isHtml: true
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
                pieSliceTextStyle: { fontSize: 12, color: '#666' },  // Optional, adjust the font size and color for any text
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