<?php
// Include the database connection
include_once "../../../config.php";
include BACKEND_PATH . '/db/dbconnect.php';

// Authentication check
include '../authentication.php';

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF Token validation (ensure the token matches the session one)
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

        // Unset the CSRF token after validation
        unset($_SESSION['csrf_token']);

        // Get form data and sanitize it to prevent SQL injection
        $period_id = mysqli_real_escape_string($con, $_POST['period_id']);  // ID of the evaluation period

        // Check if the evaluation period is already disseminated
        $query_check_disseminated = "SELECT disseminated FROM evaluation_periods WHERE period_id = '$period_id'";
        $result_check_disseminated = mysqli_query($con, $query_check_disseminated);

        if ($result_check_disseminated && mysqli_num_rows($result_check_disseminated) > 0) {
            $row = mysqli_fetch_assoc($result_check_disseminated);
            if ($row['disseminated'] == 1) {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'The survey for this evaluation period has already been disseminated.';
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();
            }
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Invalid evaluation period or database error.';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

        // Step 1: Fetch all available survey_ids from surveys table (no filter)
        $query_surveys = "SELECT survey_id, target_role FROM surveys";  // No target_role filtering
        $result_surveys = mysqli_query($con, $query_surveys);

        if (mysqli_num_rows($result_surveys) > 0) {
            // Step 2: For each survey_id, fetch faculty and course sections from faculty_courses table
            while ($survey = mysqli_fetch_assoc($result_surveys)) {
                $survey_id = $survey['survey_id'];
                $target_role = $survey['target_role'];

                // Fetch faculty and course sections for each survey_id
                $query_faculty = "SELECT fc.faculty_id, fc.course_section_id 
                                  FROM faculty_courses fc
                                  JOIN course_sections cs ON fc.course_section_id = cs.course_section_id
                                  WHERE cs.period_id = '$period_id'";  // Ensure $period_id is sanitized
                $result_faculty = mysqli_query($con, $query_faculty);

                if (mysqli_num_rows($result_faculty) > 0) {
                    // Step 3: Insert into evaluations table for each faculty and course_section
                    while ($per_faculty = mysqli_fetch_assoc($result_faculty)) {
                        $faculty_id = $per_faculty['faculty_id'];
                        $course_section_id = $per_faculty['course_section_id'];

                        // Insert a record for each faculty evaluation and course_section_id (no filter on survey_id)
                        $query_insert_eval = "INSERT INTO evaluations (course_section_id, survey_id, created_at, period_id)
                                              VALUES ('$course_section_id', '$survey_id', NOW(), '$period_id')";
                        if (mysqli_query($con, $query_insert_eval)) {
                            $evaluation_id = mysqli_insert_id($con);  // Get the last inserted evaluation_id

                            // Step 4: Insert into students_evaluations table ONLY if survey_id is 1
                            if ($target_role == 'Student') {
                                $query_students_evaluation = "SELECT s.student_id 
                                                   FROM students s
                                                   JOIN student_courses sc ON s.student_id = sc.student_id
                                                   WHERE sc.course_section_id = '$course_section_id'"; 
                                $result_students_evaluation = mysqli_query($con, $query_students_evaluation);

                                while ($student = mysqli_fetch_assoc($result_students_evaluation)) {
                                    $student_id = $student['student_id'];
                                    $query_insert_student_eval = "INSERT INTO students_evaluations 
                                                                  (evaluation_id, student_id, comments, date_evaluated, time_evaluated, is_completed)
                                                                  VALUES ('$evaluation_id', '$student_id', '', NULL, NULL, 0)";
                                    mysqli_query($con, $query_insert_student_eval);
                                }
                            }

                            if ($target_role == 'Faculty' || $target_role == 'Self') {
                                $query_faculty_evaluation = "SELECT f.faculty_id 
                                                  FROM faculty f
                                                  JOIN faculty_courses fc ON f.faculty_id = fc.faculty_id
                                                  WHERE fc.course_section_id = '$course_section_id'";
                                $result_faculty_evaluation = mysqli_query($con, $query_faculty_evaluation);

                                while ($faculty = mysqli_fetch_assoc($result_faculty_evaluation)) {
                                    $faculty_id = $faculty['faculty_id'];
                                    $query_insert_faculty_eval = "INSERT INTO faculty_evaluations 
                                                                  (evaluation_id, faculty_id, date_evaluated, time_evaluated, is_completed)
                                                                  VALUES ('$evaluation_id', '$faculty_id', NULL, NULL, 0)";
                                    mysqli_query($con, $query_insert_faculty_eval);
                                }
                            }

                            if ($target_role == 'Program_chair') {
                                $query_program_chairs_evaluation = "
                                    SELECT p.chair_id 
                                    FROM program_chairs p
                                    JOIN faculty f ON f.department_id = p.department_id
                                    JOIN departments d ON d.department_id = p.department_id 
                                    JOIN faculty_courses fc ON f.faculty_id = fc.faculty_id
                                    WHERE fc.course_section_id = '$course_section_id'";
                                $result_program_chairs_evaluation = mysqli_query($con, $query_program_chairs_evaluation);

                                while ($program_chair = mysqli_fetch_assoc($result_program_chairs_evaluation)) {
                                    $chair_id = $program_chair['chair_id'];
                                    $query_insert_program_chair_eval = "
                                        INSERT INTO program_chair_evaluations 
                                        (evaluation_id, chair_id, date_evaluated, time_evaluated, is_completed)
                                        VALUES ('$evaluation_id', '$chair_id', NULL, NULL, 0)";
                                    mysqli_query($con, $query_insert_program_chair_eval);
                                }
                            }

                        } else {
                            error_log("Database Error: " . mysqli_error($con));
                        }
                    }
                }
            }

            $update_query = "UPDATE evaluation_periods 
                             SET disseminated = 1 
                             WHERE period_id = '$period_id'";
            if (mysqli_query($con, $update_query)) {
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Survey dissemination successful!';
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();
            } else {
                error_log("Database Error: " . mysqli_error($con));
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Failed to update dissemination status.';
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();
            }

        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'No surveys available.';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Invalid CSRF token. Please try again.';
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

} else {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Invalid request method.';
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>
