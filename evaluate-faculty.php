<?php
// evaluate-faculty.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Simulated faculty data
$faculty_list = [
    ['name' => 'MR. CONO, SEAN CHARLSTON', 'id' => '1'],
    ['name' => 'DR. PEÑEN, JEIRAN', 'id' => '2'],
    ['name' => 'MR. HADLOCON, ARCEL', 'id' => '3'],
    ['name' => 'ENGR. GUEVARRA, GLEN', 'id' => '4']
];
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="animations.css">
    <link rel="stylesheet" href="styles.css">
    <title>Evaluate Faculty - Faculty Evaluation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .logo {
            width: 100px;
            display: block;
            margin: 0 auto 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #800000;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <img src="LPU-LOGO.png" alt="LPU Logo" class="logo">
            <h2>Faculty Evaluation</h2>

            <form action="evaluate-form.php" method="POST">
                <div class="form-group">
                    <label>Select Your Instructor:</label>
                    <select name="faculty_id" required>
                        <option value="">-- Select Instructor --</option>
                        <?php foreach ($faculty_list as $faculty): ?>
                            <option value="<?php echo $faculty['id']; ?>">
                                <?php echo htmlspecialchars($faculty['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn">Begin Evaluation</button>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation classes to elements
            document.body.classList.add('fade-in');

            // Add loading animation to buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    this.classList.add('btn-loading');
                });
            });

            // Add animation to form submissions
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    this.classList.add('form-submitting');
                });
            });
        });
    </script>
</body>
</html>