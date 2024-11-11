<?php
// Database connection
$host = "localhost";
$username = "root";  
$password = ""; 
$dbname = "evalsystem"; 

$con = new mysqli($host, $username, $password, $dbname);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Fetch faculty members for the dropdown
$faculties = [];
$sqlFaculty = "SELECT faculty_id, CONCAT(first_name, ' ', last_name) AS full_name FROM faculty";
$resultFaculty = $con->query($sqlFaculty);
if ($resultFaculty->num_rows > 0) {
    while ($faculty = $resultFaculty->fetch_assoc()) {
        $faculties[] = $faculty;
    }
}

// Fetch surveys for the dropdown
$surveys = [];
$sqlSurveys = "SELECT survey_id, survey_name FROM surveys"; // Update with your actual table and field names
$resultSurveys = $con->query($sqlSurveys);
if ($resultSurveys->num_rows > 0) {
    while ($survey = $resultSurveys->fetch_assoc()) {
        $surveys[] = $survey;
    }
}

// Check if the evaluation period is active
$currentDate = date('Y-m-d');
$sqlPeriod = "SELECT start_date, end_date, status FROM evaluation_periods WHERE status = 'active' LIMIT 1"; // Only get active periods
$resultPeriod = $con->query($sqlPeriod);
$isActive = false;

if ($resultPeriod->num_rows > 0) {
    $period = $resultPeriod->fetch_assoc();
    $startDate = $period['start_date'];
    $endDate = $period['end_date'];
    $status = $period['status'];

    // Compare current date with the evaluation period
    if ($currentDate >= $startDate && $currentDate <= $endDate && $status == 'active') {
        $isActive = true;  // Evaluation period is active
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Faculty and Survey</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        label {
            font-size: 1.1rem;
            color: #333;
        }

        select, input[type="submit"] {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            outline: none;
            width: 100%;
        }

        select:focus, input[type="submit"]:focus {
            border-color: #0066cc;
        }

        input[type="submit"] {
            background-color: #0066cc;
            color: #fff;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #005bb5;
        }

        option {
            padding: 10px;
        }

        .closed {
            background-color: #f44336;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Select Faculty and Survey</h1>
        
        <?php if (!$isActive): ?>
            <p style="color: red; text-align: center;">The evaluation period is no longer active.</p>
        <?php else: ?>
            <form action="evaluation_form.php" method="post">
                <label for="faculty">Faculty:</label>
                <select name="faculty_id" id="faculty" required>
                    <option value="">Select a faculty member</option>
                    <?php foreach ($faculties as $faculty): ?>
                        <option value="<?= $faculty['faculty_id']; ?>"><?= $faculty['full_name']; ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="survey">Survey:</label>
                <select name="survey_id" id="survey" required>
                    <option value="">Select a survey</option>
                    <?php foreach ($surveys as $survey): ?>
                        <option value="<?= $survey['survey_id']; ?>"><?= $survey['survey_name']; ?></option>
                    <?php endforeach; ?>
                </select>

                <input type="submit" value="Proceed to Evaluation Form">
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
