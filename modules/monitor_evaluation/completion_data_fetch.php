<?php 
$query_student = "SELECT COUNT(student_id) as 'total_students' FROM students";
$results_student = mysqli_query($con, $query_student) or die(mysqli_error($con));

$total_students = 0;

if(mysqli_num_rows($results_student)) {
    while ($row = mysqli_fetch_assoc($results_student)) {
        $total_students = $row['total_students'];
    }
}

$query_completion = "SELECT COUNT(student_id) as students_evaluated, date_evaluated 
                     FROM students_evaluations 
                     WHERE date_evaluated IS NOT NULL  -- Only consider students who have evaluated
                     GROUP BY date_evaluated";
$results_completion = mysqli_query($con, $query_completion) or die(mysqli_error($con));

$evaluation_completed = [];

if (mysqli_num_rows($results_completion)) {
    while ($row = mysqli_fetch_assoc($results_completion)) {
        // Check if date_evaluated is not null before formatting
        if ($row['date_evaluated'] !== null) {
            $formattedDate = date("m/d/y", strtotime($row['date_evaluated']));
            $evaluation_completed[] = [$formattedDate, (int)$row['students_evaluated']];
        }
    }
} else {
    // If no results in the students_evaluations table, set counts to 0 and display 'No results'
    $evaluation_completed[] = ['No results', 0];
}

// Query to count students completed and not completed evaluations
$query_status = "SELECT 
                    COUNT(CASE WHEN is_completed = 1 THEN 1 END) as students_completed, 
                    COUNT(CASE WHEN is_completed = 0 THEN 1 END) as students_not_completed
                FROM students_evaluations";

$results_status = mysqli_query($con, $query_status) or die(mysqli_error($con));

// Fetch the result and store counts in variables
if (mysqli_num_rows($results_status) > 0) {
    $row = mysqli_fetch_assoc($results_status);
    $students_completed = (int)$row['students_completed'];
    $students_not_completed = (int)$row['students_not_completed'];
} else {
    // If no results are returned, set counts to 0
    $students_completed = 0;
    $students_not_completed = 0;
}

// Query to count daily completion by time
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
        // Check if time_evaluated is not null before passing to strtotime
        if ($row['time_evaluated'] !== null) {
            $formattedDate = date("H:00", strtotime($row['time_evaluated']));
            $daily_completed[] = [$formattedDate, (int)$row['students_evaluated']];
            $total_daily_evaluated += $row['students_evaluated'];
        } else {
            // Handle the case where time_evaluated is null
            $daily_completed[] = ['No valid time', 0];
        }
    }
} else {
    // Fallback for no results
    $daily_completed[] = ['No results', 0];
    $total_daily_evaluated = 0;
}
?>
