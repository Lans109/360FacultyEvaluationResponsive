<?php
include '../../db/dbconnect.php';

if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];

    $delete_course_query = "DELETE FROM courses WHERE course_id = '$course_id'";

    if (mysqli_query($con, $delete_course_query)) {
        echo "<script>
                alert('Course deleted successfully!');
                window.location.href = 'courses.php';
              </script>";
    } else {
        echo "<script>
                alert('Error deleting department.');
                window.location.href = 'courses.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid department ID.');
            window.location.href = 'courses.php';
          </script>";
}
?>
