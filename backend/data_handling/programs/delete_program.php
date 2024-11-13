<?php
include '../../db/dbconnect.php';

if (isset($_GET['program_id'])) {
    $program_id = intval($_GET['program_id']);
    
    $delete_department = "DELETE FROM programs WHERE program_id = $program_id";
    if (mysqli_query($con, $delete_department)) {
        echo "<script>
                alert('Program deleted successfully!');
                window.location.href = 'programs.php';
              </script>";
    } else {
        echo "<script>
                alert('Error deleting program.');
                window.location.href = 'programs.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid program ID.');
            window.location.href = 'programs.php';
          </script>";
}
?>
