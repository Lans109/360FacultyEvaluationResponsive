<?php

// Get all faculty names and IDs for the dropdown
$query = "SELECT faculty_id, CONCAT(first_name, ' ', last_name) AS faculty_name FROM faculty";
$result = mysqli_query($con, $query);
$facultyList = [];

while ($row = mysqli_fetch_assoc($result)) {
    $facultyList[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Arrays of data per evaluator type
        var studentArray = <?php echo json_encode($array_graph_student); ?>;
        var facultyArray = <?php echo json_encode($array_graph_peer); ?>;
        var chairArray = <?php echo json_encode($array_graph_chair); ?>;
        var selfArray = <?php echo json_encode($array_graph_self); ?>;
        var overallArray = <?php echo json_encode($array_graph_overall); ?>;
        var combinedDataArray = <?php echo json_encode($array_graph_combined); ?>;


        google.charts.load('current', { packages: ['corechart', 'bar'] });
        google.charts.setOnLoadCallback(function () {
            drawChart(studentArray, 'Student Ratings', 'chart_div_student', 'studentImageData');
            drawChart(facultyArray, 'Faculty Ratings', 'chart_div_faculty', 'facultyImageData');
            drawChart(chairArray, 'Chair/Dean Ratings', 'chart_div_chair', 'chairImageData');
            drawChart(selfArray, 'Self Ratings', 'chart_div_self', 'selfImageData');
            drawChart(overallArray, 'Overall Ratings', 'chart_div_overall', 'overallImageData');
            drawChart(combinedDataArray, 'Summary Ratings', 'combined_div_overall', 'combinedImageData');
        });

        function drawChart(dataArray, title, elementId, imageDataId) {
            if (!dataArray || dataArray.length === 0) {
                var data = google.visualization.arrayToDataTable(
                    ['Message', 'Value'],
                    ['No Data Available', 1]);
            } else {
                var data = google.visualization.arrayToDataTable(dataArray);
            }

            // Convert the provided data array into a Google DataTable
            

            var options = {
                backgroundColor: 'transparent',
                title: title,
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
                hAxis: {
                    title: '', // Maintain x-axis title
                    titleTextStyle: { fontSize: 15, color: '#666', bold: true }, // Match x-axis title style
                    textStyle: { fontSize: 15, color: '#666' }, // Adjust x-axis text style
                    gridlines: {
                        color: '#e0e0e0',
                        count: 5 // Limit horizontal gridlines to 5
                    },
                    textPosition: 'out', // Place axis labels outside
                },
                vAxis: {
                    title: '', // Maintain y-axis title
                    titleTextStyle: { fontSize: 16, color: '#666', bold: true }, // Match y-axis title style
                    textStyle: { fontSize: 12, color: '#ddd' }, // Adjust y-axis text style
                    gridlines: {
                        color: '#ddd', // Gridline color
                        count: 5 // Limit vertical gridlines to 5
                    },
                    minValue: 1,
                    maxValue: 5,
                    ticks: [1, 2, 3, 4, 5], // Y-axis ticks
                    format: '0', // Format tick labels as integers
                },
                chartArea: {
                    left: 50, // Match padding and margins
                    top: 50,
                    right: 20,
                    bottom: 50,
                    width: '90%', // Adjust chart area width
                    height: '80%' // Adjust chart area height
                },
                bars: 'vertical', // Keep bars vertical
                isStacked: false, // No stacking
                bar: {
                    groupWidth: '50%' // Adjust bar width
                },
                animation: {
                    duration: 1000, // Smooth animation duration
                    easing: 'inAndOut' // Easing effect
                },
                tooltip: { isHtml: true }, // HTML tooltips
                curveType: 'function', // Enable smooth curves if applicable
                enableInteractivity: true, // Allow interaction like hover and clicks
                focusTarget: 'category', // Highlight category on hover
                annotations: {
                    textStyle: { fontSize: 12, color: '#444', bold: true }, // Annotation text style
                    alwaysOutside: true, // Place annotations outside
                },
                // Legend removed:
                legend: { position: 'none' },
            };



            var chart = new google.visualization.ColumnChart(document.getElementById(elementId));

            google.visualization.events.addListener(chart, 'ready', function () {
                var imageURI = chart.getImageURI();
                document.getElementById(imageDataId).value = imageURI;
            });

            chart.draw(data, options);
        }
    </script>
</head>
</html>
