<?php
// Evaluation-Form.php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Evaluation Form</title>
    <style>
        /* Evaluation Form Specific Styles */
        .evaluation-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 24px;
            margin-bottom: 24px;
        }

        .faculty-header {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
        }

        .faculty-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 24px;
        }

        .faculty-details h2 {
            font-size: 20px;
            margin: 0 0 4px 0;
        }

        .faculty-details p {
            color: #666;
            margin: 0;
            font-size: 14px;
        }

        .rating-scale {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin: 24px 0;
            padding: 16px;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .rating-item {
            text-align: center;
            font-size: 14px;
        }

        .questions-table {
            width: 100%;
            border-collapse: collapse;
            margin: 24px 0;
        }

        .questions-table th,
        .questions-table td {
            padding: 16px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        .questions-table th {
            background: #6B0007;
            color: white;
        }

        .radio-group {
            display: flex;
            justify-content: space-around;
        }

        .action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 16px;
            margin-top: 24px;
        }

        .save-btn {
            background: #90BE6D;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .save-btn:hover {
            background: #7aa75b;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <h1 class="page-title">Faculty Evaluation</h1>

        <form action="save-evaluation.php" method="POST">
            <div class="evaluation-card">
                <div class="faculty-header">
                    <img src="Faculty.jpg" alt="Faculty Photo" class="faculty-photo">
                    <div class="faculty-details">
                        <h2><?php echo htmlspecialchars($_GET['faculty'] ?? ''); ?></h2>
                        <p><?php echo htmlspecialchars($_GET['course'] ?? ''); ?></p>
                        <p>COECSA</p>
                    </div>
                </div>

                <div class="rating-scale">
                    <div class="rating-item">5 - Very Satisfied</div>
                    <div class="rating-item">4 - Satisfied</div>
                    <div class="rating-item">3 - Neutral</div>
                    <div class="rating-item">2 - Dissatisfied</div>
                    <div class="rating-item">1 - Strongly Dissatisfied</div>
                </div>

                <table class="questions-table">
                    <thead>
                        <tr>
                            <th>Questions</th>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                            <th>5</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $questions = [
                            "How would you rate the overall quality of the faculty member's teaching?",
                            "How effective was the faculty member in explaining complex concepts?",
                            "How well did the faculty member encourage class participation and discussion?",
                            "How would you rate the faculty member's preparedness for each class?"
                        ];

                        foreach ($questions as $index => $question) {
                            echo '<tr>
                                <td>' . htmlspecialchars($question) . '</td>';
                            for ($i = 1; $i <= 5; $i++) {
                                echo '<td>
                                    <input type="radio" name="q' . $index . '" value="' . $i . '" required>
                                </td>';
                            }
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>

                <div class="action-buttons">
                    <button type="submit" class="save-btn">Save and Finish</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>