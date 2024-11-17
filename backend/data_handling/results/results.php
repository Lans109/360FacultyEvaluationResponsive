<?php
// Include database connection
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Fetch departments for filtering
$departmentQuery = "SELECT department_id, department_name FROM departments";
$departmentResult = mysqli_query($con, $departmentQuery);

// Set filters
$selectedDepartment = isset($_GET['department']) ? $_GET['department'] : '';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch filtered faculty members with department names
$facultyQuery = "
    SELECT f.faculty_id, CONCAT(f.first_name, ' ', f.last_name) AS faculty_name, d.department_name 
    FROM faculty f
    LEFT JOIN departments d ON f.department_id = d.department_id
    WHERE 1=1";

// Apply department filter
if (!empty($selectedDepartment)) {
    $facultyQuery .= " AND f.department_id = '" . mysqli_real_escape_string($con, $selectedDepartment) . "'";
}

// Apply search filter (search by name or ID)
if (!empty($searchTerm)) {
    // Split search term into parts (words)
    $searchParts = explode(' ', $searchTerm);

    // Build the SQL condition for each word
    $searchConditions = [];
    foreach ($searchParts as $part) {
        $part = mysqli_real_escape_string($con, $part);
        $searchConditions[] = "(f.first_name LIKE '%$part%' OR f.last_name LIKE '%$part%' OR f.faculty_id LIKE '%$part%')";
    }

    // Combine conditions with AND
    $facultyQuery .= " AND (" . implode(' AND ', $searchConditions) . ")";
}

$facultyResult = mysqli_query($con, $facultyQuery);

if (!$facultyResult) {
    die("Query failed: " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../../frontend/layout/navbar.php'; ?>
    <link rel='stylesheet' href='../../../frontend/templates/admin-style.css'>
    <title>Faculty Results</title>
</head>

<body>
    <?php include ROOT_PATH . '/frontend/layout/sidebar.php'; ?>
    <main>
        <div class="upperMain">
            <h1>Faculty Results</h1>
        </div>
        <div class="content">
            <div class="container mt-4">
                <div class="table">
                    <!-- Filter and Search Form -->
                    <form method="get" class="filter-form">
                        <label for="department">Filter by Department:</label>
                        <select name="department" id="department">
                            <option value="">All Departments</option>
                            <?php while ($row = mysqli_fetch_assoc($departmentResult)): ?>
                                <option value="<?php echo htmlspecialchars($row['department_id']); ?>"
                                    <?php echo ($row['department_id'] == $selectedDepartment) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($row['department_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>

                        <label for="search">Search Faculty:</label>
                        <input type="text" name="search" id="search" placeholder="Enter name or ID" 
                            value="<?php echo htmlspecialchars($searchTerm); ?>">

                        <button type="submit" class="enroll-btn">Apply Filter</button>
                    </form>

                    <!-- Faculty Table -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Faculty ID</th>
                                <th>Faculty Name</th>
                                <th>Department</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($facultyResult) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($facultyResult)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['faculty_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['faculty_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['department_name']); ?></td>
                                        <td>
                                            <!--test-->
                                            <a href="faculty_summary.php?facultyId=<?php echo $row['faculty_id']-1; ?>&period=1" class="view-btn">

                                                View Results
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">No faculty members found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
<!-- for sidebar button -->
<script type="text/javascript" src="../../../frontend/layout/app.js" defer></script>

</html>
