<?php
//Faculty Data

$sqlFaculty = "
    SELECT 
        f.faculty_id, 
        f.first_name, 
        f.last_name, 
        f.department_id,
        d.department_name
    FROM 
        faculty f
    JOIN 
        departments d ON f.department_id = d.department_id
";

$resultFaculty = $con->query($sqlFaculty);

if ($resultFaculty->num_rows > 0) {
    $facultyData = [];
    $legend = [];

    while ($row = $resultFaculty->fetch_assoc()) {
        $fullName = $row['first_name'] . ' ' . $row['last_name'];
        $courseEvaluations = [];

        $sqlCourses = "
            SELECT 
                c.course_code,
                cs.section,
                cs.course_section_id,
                ep.period_id,
                ep.semester,
                ep.academic_year
            FROM 
                faculty_courses fc
            JOIN 
                course_sections cs ON fc.course_section_id = cs.course_section_id
            JOIN
                courses c ON cs.course_id = c.course_id
            JOIN 
                evaluation_periods ep ON ep.period_id = cs.period_id
            WHERE 
                fc.faculty_id = ?
            GROUP BY 
                cs.course_section_id, c.course_code, cs.section, ep.period_id, ep.semester, ep.academic_year;
        ";

        $stmtCourses = $con->prepare($sqlCourses);
        $stmtCourses->bind_param("i", $row['faculty_id']);
        $stmtCourses->execute();
        $resultCourses = $stmtCourses->get_result();

        if ($resultCourses->num_rows > 0) {
            while ($courseRow = $resultCourses->fetch_assoc()) {
                $surveys = [];

                $sqlSurvey = "
                    SELECT 
                        s.survey_id, 
                        s.survey_name, 
                        s.target_role,
                        COUNT(e.evaluation_id) AS total_evaluated_survey
                    FROM 
                        surveys s
                    LEFT JOIN 
                        evaluations e ON s.survey_id = e.survey_id AND e.course_section_id = ? AND e.period_id = ?
                    GROUP BY 
                        s.survey_id, s.survey_name, s.target_role;
                ";

                $stmtSurvey = $con->prepare($sqlSurvey);
                $stmtSurvey->bind_param("ii", $courseRow['course_section_id'], $courseRow['period_id']);
                $stmtSurvey->execute();
                $resultSurvey = $stmtSurvey->get_result();

                if ($resultSurvey->num_rows > 0) {
                    while ($surveyRow = $resultSurvey->fetch_assoc()) {
                        $evaluationQuestions = [];

                        $sqlQuestions = "
                           SELECT 
                                q.question_code, 
                                q.question_id,
                                q.criteria_id,
                                qc.description AS criteria_description,
                                q.question_text
                            FROM 
                                questions q
                            JOIN
                                questions_criteria qc ON qc.criteria_id = q.criteria_id
                            WHERE 
                                q.survey_id = ?
                            ORDER BY
                                q.criteria_id;
                        ";

                        $stmtQuestions = $con->prepare($sqlQuestions);
                        $stmtQuestions->bind_param("i", $surveyRow['survey_id']);
                        $stmtQuestions->execute();
                        $resultQuestions = $stmtQuestions->get_result();

                        if ($resultQuestions->num_rows > 0) {
                            while ($questionRow = $resultQuestions->fetch_assoc()) {
                                $allQuestions[] = $questionRow['question_code'];
                                
                                $questionResponse = [];

                                $sqlResponse = "
                                    SELECT 
                                        r.rating 
                                    FROM 
                                        responses r
                                    JOIN 
                                        evaluations e ON r.evaluation_id = e.evaluation_id
                                    WHERE 
                                        r.question_id = ? AND e.course_section_id = ? AND e.period_id = ?;
                                ";

                                $stmtResponse = $con->prepare($sqlResponse);
                                $stmtResponse->bind_param("iii", $questionRow['question_id'], $courseRow['course_section_id'], $courseRow['period_id']);
                                $stmtResponse->execute();
                                $resultResponse = $stmtResponse->get_result();

                                if ($resultResponse->num_rows > 0) {
                                    while ($ratingRow = $resultResponse->fetch_assoc()) {
                                        $questionResponse[] = $ratingRow['rating'];
                                    }
                                } else {
                                    $questionResponse = [];
                                }

                                $evaluationQuestions[] = [
                                    'question_code' => $questionRow['question_code'],
                                    'question_id' => $questionRow['question_id'],
                                    'question_criteria' => $questionRow['criteria_description'],
                                    'question_text' => $questionRow['question_text'],
                                    'response' => $questionResponse
                                ];
                            }
                        } else {
                            $evaluationQuestions = [];
                        }
                        

                        $surveys[] = [
                            'survey_id' => $surveyRow['survey_id'],
                            'target_role' => $surveyRow['target_role'],
                            'survey_name' => $surveyRow['survey_name'],
                            'total_evaluated_survey' => $surveyRow['total_evaluated_survey'],
                            'questions' => $evaluationQuestions
                        ];
                    }
                } else {
                    $surveys = [];
                }

                $courseEvaluations[] = [
                    'course_code' => $courseRow['course_code'],
                    'section' => $courseRow['section'],
                    'section_id' => $courseRow['course_section_id'],
                    'period_id' => $courseRow['period_id'], 
                    'semester' => $courseRow['semester'],   
                    'academic_year' => $courseRow['academic_year'], 
                    'surveys' => $surveys
                ];
            }
        }

        $facultyData[] = [
            'faculty_id' => $row['faculty_id'],
            'full_name' => $fullName,
            'department' => $row['department_name'],
            'courses' => $courseEvaluations
        ];
    }
} else {
    echo "No faculty members found.";
}

$sqlComments = "
    SELECT 
        comments
    FROM 
        students_evaluations
";

$resultComments = $con->query($sqlComments);
$comments = [];
if ($resultComments->num_rows > 0) {
    
    while ($commentsRow = $resultComments->fetch_assoc()) {
        $comments[] = $commentsRow['comments'];
    }
}

