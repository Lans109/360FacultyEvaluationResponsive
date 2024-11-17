<?php 
$query_student = "SELECT COUNT(student_id) as 'total_students' FROM students";
$results_student = mysqli_query($con, $query_student) or die(mysqli_error($con));

$total_students = 0;

if(mysqli_num_rows($results_student)) {
    while ($row = mysqli_fetch_assoc($results_student)) {
    $total_students = $row['total_students'];
    }
}


$query_completion = "SELECT COUNT(student_id) as students_evaluated, date_evaluated FROM students_evaluations GROUP BY date_evaluated";
$results_completion = mysqli_query($con, $query_completion) or die(mysqli_error($con));

$evaluation_completed = [];
$total_evaluated = 0;

if (mysqli_num_rows($results_completion)) {
    while ($row = mysqli_fetch_assoc($results_completion)) {
        $formattedDate = date("m/d/y", strtotime($row['date_evaluated']));
        $evaluation_completed[] = [$formattedDate, (int)$row['students_evaluated']];
        $total_evaluated += $row['students_evaluated'];
    }
} else {
    $total_evaluated = 0;
    $evaluation_completed[] = ['No results', 0];
}

$total_not_evaluated = $total_students - $total_evaluated;

$query_daily_completion = "
    SELECT COUNT(student_id) AS students_evaluated, time_evaluated
    FROM students_evaluations 
    WHERE DATE(date_evaluated) = CURDATE()  -- Filter by current date
    GROUP BY time_evaluated
";
$results_daily_completion = mysqli_query($con, $query_daily_completion) or die(mysqli_error($con));

$daily_completed = [];
$total_daily_evaluated = 0;
$date = date("m/d/Y");

if (mysqli_num_rows($results_daily_completion)) {
    while ($row = mysqli_fetch_assoc($results_daily_completion)) {
        $formattedDate = date("H:00", strtotime($row['time_evaluated']));
        $daily_completed[] = [$formattedDate, (int)$row['students_evaluated']];
        $total_daily_evaluated += $row['students_evaluated'];
    }
} else {
    // Fallback for no results
    $daily_completed[] = ['No results', 0];
    $total_daily_evaluated = 0;
}
?>
