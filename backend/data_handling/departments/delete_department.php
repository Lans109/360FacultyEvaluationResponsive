<?php
include '../../db/dbconnect.php';

if (isset($_GET['department_id'])) {
    $department_id = intval($_GET['department_id']);
    
    $delete_department = "DELETE FROM departments WHERE department_id = $department_id";
    if (mysqli_query($con, $delete_department)) {
        echo "<script>
                alert('Department deleted successfully!');
                window.location.href = 'departments.php';
              </script>";
    } else {
        echo "<script>
                alert('Error deleting department.');
                window.location.href = 'departments.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid department ID.');
            window.location.href = 'departments.php';
          </script>";
}
?>
