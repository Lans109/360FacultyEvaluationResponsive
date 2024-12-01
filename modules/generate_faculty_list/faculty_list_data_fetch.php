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
    f.faculty_id,
    f.profile_image,
    d.department_code,
    COUNT(DISTINCT CASE WHEN cs.period_id = $period THEN fc.course_section_id END) AS total_courses,
    CONCAT(f.first_name, ' ', f.last_name) AS faculty_name,
    IFNULL(AVG(subquery_student.avg_rating_per_question), 0) AS overall_avg_rating_student,
    IFNULL(AVG(subquery_self.avg_rating_per_question), 0) AS overall_avg_rating_self,
    IFNULL(AVG(subquery_peer.avg_rating_per_question), 0) AS overall_avg_rating_peer,
    IFNULL(AVG(subquery_program_chair.avg_rating_per_question), 0) AS overall_avg_rating_program_chair,
    -- Weighted average calculation: 
    -- 50% for student, 40% for program chair, 5% for self and peer
    ROUND(
        (
            (IFNULL(AVG(subquery_student.avg_rating_per_question), 0) * 0.5) +
            (IFNULL(AVG(subquery_program_chair.avg_rating_per_question), 0) * 0.4) +
            (IFNULL(AVG(subquery_self.avg_rating_per_question), 0) * 0.05) +
            (IFNULL(AVG(subquery_peer.avg_rating_per_question), 0) * 0.05)
        ), 2
    ) AS weighted_avg_rating
FROM faculty f
LEFT JOIN (
    -- Subquery for student evaluations
    SELECT 
        cs.course_section_id,   
        r.question_id,          
        fc.faculty_id,          
        AVG(r.rating) AS avg_rating_per_question  
    FROM responses r
    LEFT JOIN evaluations e ON r.evaluation_id = e.evaluation_id
    LEFT JOIN course_sections cs ON e.course_section_id = cs.course_section_id
    LEFT JOIN surveys s ON e.survey_id = s.survey_id
    LEFT JOIN faculty_courses fc ON fc.course_section_id = cs.course_section_id  
    WHERE s.target_role = 'student' AND cs.period_id = $period
    GROUP BY fc.faculty_id, cs.course_section_id, r.question_id  
) AS subquery_student ON f.faculty_id = subquery_student.faculty_id

LEFT JOIN (
    -- Subquery for self-evaluations
    SELECT 
        cs.course_section_id,   
        r.question_id,          
        fc.faculty_id,          
        AVG(r.rating) AS avg_rating_per_question  
    FROM responses r
    LEFT JOIN evaluations e ON r.evaluation_id = e.evaluation_id
    LEFT JOIN course_sections cs ON e.course_section_id = cs.course_section_id
    LEFT JOIN surveys s ON e.survey_id = s.survey_id
    LEFT JOIN faculty_courses fc ON fc.course_section_id = cs.course_section_id  
    WHERE s.target_role = 'self' AND cs.period_id = $period  
    GROUP BY fc.faculty_id, cs.course_section_id, r.question_id  
) AS subquery_self ON f.faculty_id = subquery_self.faculty_id

LEFT JOIN (
    -- Subquery for peer (faculty) evaluations
    SELECT 
        cs.course_section_id,   
        r.question_id,          
        fc.faculty_id,          
        AVG(r.rating) AS avg_rating_per_question  
    FROM responses r
    LEFT JOIN evaluations e ON r.evaluation_id = e.evaluation_id
    LEFT JOIN course_sections cs ON e.course_section_id = cs.course_section_id
    LEFT JOIN surveys s ON e.survey_id = s.survey_id
    LEFT JOIN faculty_courses fc ON fc.course_section_id = cs.course_section_id  
    WHERE s.target_role = 'faculty' AND cs.period_id = $period  
    GROUP BY fc.faculty_id, cs.course_section_id, r.question_id  
) AS subquery_peer ON f.faculty_id = subquery_peer.faculty_id

LEFT JOIN (
    -- Subquery for program chair evaluations
    SELECT 
        cs.course_section_id,   
        r.question_id,          
        fc.faculty_id,          
        AVG(r.rating) AS avg_rating_per_question  
    FROM responses r
    LEFT JOIN evaluations e ON r.evaluation_id = e.evaluation_id
    LEFT JOIN course_sections cs ON e.course_section_id = cs.course_section_id
    LEFT JOIN surveys s ON e.survey_id = s.survey_id
    LEFT JOIN faculty_courses fc ON fc.course_section_id = cs.course_section_id  
    WHERE s.target_role = 'program_chair' AND cs.period_id = $period  
    GROUP BY fc.faculty_id, cs.course_section_id, r.question_id  
) AS subquery_program_chair ON f.faculty_id = subquery_program_chair.faculty_id

LEFT JOIN faculty_courses fc ON f.faculty_id = fc.faculty_id

LEFT JOIN course_sections cs ON fc.course_section_id = cs.course_section_id

LEFT JOIN departments d ON d.department_id = f.department_id

GROUP BY f.faculty_id, f.first_name, f.last_name, f.profile_image
ORDER BY weighted_avg_rating DESC;
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
            'faculty_name' => $row_faculty['faculty_name'],
            'AVG' => ROUND($row_faculty['weighted_avg_rating'], 2),
            'total_courses' => $row_faculty['total_courses'],
            'profile_image' => $row_faculty['profile_image'],
            'faculty_id' => $row_faculty['faculty_id'],
        ];

        // Calculate Scaling Factor
        $scaled_avg = $row_faculty['weighted_avg_rating']*$row_faculty['total_courses'];
    }
}


?>
