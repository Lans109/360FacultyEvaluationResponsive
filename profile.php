<?php 
session_start(); 

// Redirect to login if the session is not set
if (!isset($_SESSION['user_id'])) { 
    header('Location: login.php'); 
    exit(); 
} 
?> 

<!DOCTYPE html> 
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Faculty Evaluation</title> 
    <style> 
        /* Reset Styles */
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        } 
        
        /* Body Styles */
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f7fafc; 
            color: #333;
        } 
        
        /* Container */
        .container { 
            max-width: 1000px; 
            margin: 20px auto; 
            padding: 0 20px; 
        } 
        
        /* Card */
        .card { 
            background-color: #fff; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); 
            padding: 30px; 
        } 
        
        /* Profile Section */
        .profile { 
            display: flex; 
            align-items: center; 
            margin-bottom: 30px; 
            flex-wrap: wrap;
        } 
        
        .profile-picture { 
            width: 80px; 
            height: 80px; 
            border-radius: 50%; 
            margin-right: 20px; 
            overflow: hidden; 
        } 
        
        .profile-picture img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
        } 
        
        .profile-info { 
            flex-grow: 1; 
        }

        .name { 
            font-size: 24px; 
            margin-bottom: 10px; 
            font-weight: bold;
        } 
        
        .course-info { 
            display: flex; 
            font-size: 14px; 
            color: #666; 
        } 
        
        .course-name, 
        .year { 
            margin-right: 20px; 
        } 
        
        /* Enrolled Courses Section */
        .enrolled-courses h3 { 
            font-size: 18px; 
            margin-bottom: 20px; 
        } 
        
        .course-list { 
            display: grid; 
            grid-template-columns: repeat(2, 1fr); 
            grid-gap: 20px; 
        } 
        
        .course-item { 
            border: 1px solid #ddd; 
            padding: 15px; 
            border-radius: 4px; 
            background-color: #f9f9f9;
            transition: background-color 0.3s;
        }
        
        .course-item:hover {
            background-color: #f1f1f1;
        }

        .course-code { 
            font-weight: bold; 
            margin-bottom: 5px; 
        } 
        
        .course-name, 
        .course-duration, 
        .course-lessons { 
            font-size: 14px; 
            color: #666; 
            margin-bottom: 5px; 
        } 
        
        /* Button Styles */
        .btn-back { 
            display: inline-block; 
            padding: 12px 20px; 
            background-color: #800000; 
            color: white; 
            text-decoration: none; 
            border-radius: 4px; 
            margin-top: 20px;
            text-align: center;
            font-size: 16px;
        } 
        
        .btn-back:hover { 
            background-color: #600000;
        }

        /* Responsive Design */
        @media (max-width: 768px) { 
            .container { 
                padding: 10px; 
            } 
            
            .card { 
                padding: 20px; 
            } 
            
            .profile { 
                flex-direction: column; 
                align-items: flex-start; 
            } 
            
            .profile-picture { 
                width: 60px; 
                height: 60px; 
                margin-right: 0; 
                margin-bottom: 10px;
            } 
            
            .name { 
                font-size: 20px;
            } 
            
            .course-info { 
                flex-direction: column;
            } 
            
            .course-name, 
            .year { 
                margin-right: 0; 
            } 
            
            .enrolled-courses h3 { 
                font-size: 16px; 
            } 
            
            .course-list { 
                grid-template-columns: 1fr; 
            } 
        }  
    </style> 
</head> 

<body> 
    <div class="container"> 
        <div class="card"> 
            <!-- Profile Section -->
            <div class="profile"> 
                <div class="profile-picture"> 
                    <img src="Sample.jpeg" alt="Profile Picture"> 
                </div> 
                <div class="profile-info"> 
                    <!-- Check if 'name' is set in the session before displaying it -->
                    <h2 class="name"><?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Guest'; ?></h2> 
                    <div class="course-info"> 
                        <p class="course-name">Bachelor of Computer Science</p> 
                        <p class="year">3rd Year</p> 
                    </div> 
                </div> 
            </div> 

            <!-- Enrolled Courses Section -->
            <div class="enrolled-courses"> 
                <h3>Recent Enrolled Courses</h3> 
                <div class="course-list"> 
                    <?php 
                    // Check if 'courses' is set and is an array before looping
                    if (isset($_SESSION['courses']) && is_array($_SESSION['courses'])):
                        foreach($_SESSION['courses'] as $course): 
                    ?>  
                        <div class="course-item">  
                            <p class="course-code"><?php echo htmlspecialchars($course['code']); ?></p>  
                            <p class="course-name"><?php echo htmlspecialchars($course['name']); ?></p>
                            <p class="course-duration"><?php echo htmlspecialchars($course['duration']); ?></p>
                            <p class="course-lessons"><?php echo htmlspecialchars($course['lessons']); ?> Lessons</p>
                        </div>  
                    <?php endforeach; 
                    else: ?>
                        <p>No courses found.</p>
                    <?php endif; ?>
                </div>  
            </div>

            <!-- Back to Dashboard Button -->
            <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>