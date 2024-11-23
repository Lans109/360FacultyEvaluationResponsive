<?php 

// Query to get departments
$sql_department_list = "SELECT department_code, department_id FROM departments";
$results_department_list = mysqli_query($con, $sql_department_list) or die(mysqli_error($con));

$department_list = [];

if(mysqli_num_rows($results_department_list) > 0) {
    while($row_department = mysqli_fetch_assoc($results_department_list)) {
        $department_list[] = $row_department["department_code"];
    }
}

// Query to get faculty and their average ratings by department
$sql_faculty_list = 
"SELECT 
    IFNULL(
        AVG(CASE WHEN s.target_role = 'student' THEN r.rating * 0.5 ELSE NULL END), 0
    ) +
    IFNULL(
        AVG(CASE WHEN s.target_role = 'faculty' THEN r.rating * 0.05 ELSE NULL END), 0
    ) +
    IFNULL(
        AVG(CASE WHEN s.target_role = 'program_chair' THEN r.rating * 0.4 ELSE NULL END), 0
    ) +
    IFNULL(
        AVG(CASE WHEN s.target_role = 'self' THEN r.rating * 0.05 ELSE NULL END), 0
    ) AS weighted_avg,
    CONCAT(f.first_name, ' ', f.last_name) AS Name, 
    d.department_code,
    f.profile_image,
    f.faculty_id,
    f.email,
    COUNT(DISTINCT fc.course_section_id) as total_courses
FROM faculty f
LEFT JOIN faculty_courses fc ON f.faculty_id = fc.faculty_id
LEFT JOIN course_sections cs ON cs.course_section_id = fc.course_section_id
LEFT JOIN evaluations e ON e.course_section_id = cs.course_section_id
LEFT JOIN responses r ON r.evaluation_id = e.evaluation_id
LEFT JOIN surveys s ON s.survey_id = e.survey_id
LEFT JOIN departments d ON f.department_id = d.department_id
GROUP BY f.faculty_id, d.department_code
ORDER BY weighted_avg DESC;


";

$results_faculty_list = mysqli_query($con, $sql_faculty_list) or die(mysqli_error($con));

$faculty_list_by_department = [];

// Initialize an empty array for each department in faculty_list_by_department
foreach ($department_list as $department) {
    $faculty_list_by_department[$department] = [];
}

if(mysqli_num_rows($results_faculty_list) > 0) {
    while($row_faculty = mysqli_fetch_assoc($results_faculty_list)) {
        $department_code = $row_faculty['department_code'];
        
        // Append each faculty to the correct department
        $faculty_list_by_department[$department_code][] = [
            'faculty_name' => $row_faculty['Name'],
            'AVG' => ROUND($row_faculty['weighted_avg'], 2),
            'total_courses' => $row_faculty['total_courses'],
            'profile_image' => $row_faculty['profile_image'],
            'faculty_id' => $row_faculty['faculty_id'],
        ];
    }
}
?>
