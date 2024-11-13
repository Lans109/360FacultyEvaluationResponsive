<?php
include '../../db/dbconnect.php';

if (isset($_GET['course_section_id'])) {
    $course_section_id = $_GET['course_section_id'];

    $delete_course_query = "DELETE FROM course_sections WHERE course_section_id = '$course_section_id'";

    if (mysqli_query($con, $delete_course_query)) {
        echo "<script>
                alert('Section deleted successfully!');
                window.location.href = 'sections.php';
              </script>";
    } else {
        echo "<script>
                alert('Error deleting department.');
                window.location.href = 'sections.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid department ID.');
            window.location.href = 'sections.php';
          </script>";
}
?>
