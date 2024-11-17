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
                colors: ['#a2252f', '#e6693e', '#ec8f6e', '#f3b49f', '#f6c7b6'],
                fontName: 'Times New Roman',
                fontSize: 14,
                hAxis: {
                    title: 'Evaluators',
                    titleTextStyle: { fontSize: 16 },
                    textStyle: { fontSize: 15 },
                    gridlines: { color: '#e0e0e0' }
                },
                vAxis: {
                    title: 'Rating',
                    titleTextStyle: { fontSize: 16 },
                    textStyle: { fontSize: 12 },
                    gridlines: { color: '#e0e0e0' },
                    minValue: 1,
                    maxValue: 5,
                    ticks: [1, 2, 3, 4, 5]
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
                bars: 'vertical',
                isStacked: false,
                bar: { groupWidth: '50%' },
                animation: {
                    duration: 1000,
                    easing: 'inAndOut',
                }
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
