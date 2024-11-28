
<?php 
//Summarizing Faculty Results

if (!isset($facultyData[$facultyId])) {
    die("Faculty data not found.");
}

$sqlPeriod = "
    SELECT * FROM evaluation_periods WHERE period_id = '$period';
";

$resultPeriod = $con->query($sqlPeriod);

if ($resultPeriod->num_rows > 0) {
    $academicYear = '';
    $semester = '';
    while ($row = $resultPeriod->fetch_assoc()) {
        $academicYear = $row['academic_year'];
        $semester = $row['semester'];
        $student_scoring = $row['student_scoring'];
        $chair_scoring = $row['chair_scoring'];
        $peer_scoring = $row['peer_scoring'];
        $self_scoring = $row['self_scoring'];
    }
}

$facultyName = $facultyData[$facultyId]['full_name'];
$facultyProgram = $facultyData[$facultyId]['department'];
$facultyNo = $facultyData[$facultyId]['faculty_id'];

function generateFacultyCourseEvaluation($facultyData, $facultyNo, $role, $period) {
    $facultyCourses = '';
    $avgResults = [];
    $surveyQuestions = [];

    foreach ($facultyData as $faculty) {
        if ($faculty['faculty_id'] == $facultyNo) {
            foreach ($faculty['courses'] as $course) {
                if ($course['period_id'] == $period) {
                    $facultyCourses .= "<tr>
                        <td>" . htmlspecialchars($course['course_code']) . "</td>
                        <td>" . htmlspecialchars($course['section']) . "</td>";
                    
                    $totalAvg = 0;
                    $questionCount = 0;

                    foreach ($course['surveys'] as $survey) {
                        if ($survey['target_role'] == $role) {
                            $facultyCourses .= "<td style='border-left: 2px solid #D3D3D3;'>" . htmlspecialchars($survey['total_evaluated_survey']) . "</td>";
                            $surveyQuestions[$survey['survey_id']] = [];

                            foreach ($survey['questions'] as $question) {
                                if (!empty($question['response'])) {
                                    $totalRating = array_sum($question['response']);
                                    $count = count($question['response']);
                                    $averageRating = $totalRating / $count;
                                    $facultyCourses .= "<td>" . round($averageRating, 2) . "</td>";
                                    $totalAvg += $averageRating;
                                    $questionCount++;
                                    
                                } else {
                                    $facultyCourses .= "<td>-</td>";
                                }

                                $surveyQuestions[$survey['survey_id']][] = [
                                    'question_code' => $question['question_code'], 
                                    'question_text' => $question['question_text'],
                                    'question_criteria' => $question['question_criteria']
                                ];
                            }
                        }
                    }

                    $overallAverage = ($questionCount > 0) ? ROUND(($totalAvg / $questionCount), 3) : '-';
                    $facultyCourses .= "<td class='avg' style='border-left: 2px solid #D3D3D3;'>" . $overallAverage . "</td></tr>";

                    $avgResults[] = [
                        'course_code' => $course['course_code'],
                        'section' => $course['section'],
                        'average' => $overallAverage
                    ];
                }
            }
        }
    }

    $questions = '';
    $legend = '';
    
    foreach ($surveyQuestions as $surveyId => $codes) {
        $questions .= "<td class='metadata' style='border-left: 2px solid white;'>N</td>";
        foreach ($codes as $code) {
            $questions .= "<td class='metadata'>" . htmlspecialchars($code['question_code']) . "</td>";
        }
        $questions .= "<td class='metadata' style='border-left: 2px solid white;'>AVG</td>";
        if (empty($codes)) {
            $questions .= "<td class='metadata' style='border-left: 2px solid white;'>AVG</td>";
        }
    }

    $legend = '';
    $criteriaGroups = [];

    foreach ($surveyQuestions as $surveyId => $codes) {
        foreach ($codes as $code) {
            $criteria = $code['question_criteria'];
            if (!isset($criteriaGroups[$criteria])) {
                $criteriaGroups[$criteria] = [];
            }
            $criteriaGroups[$criteria][] = $code;
        }
    }

    foreach ($criteriaGroups as $criteria => $codes) {
        $legend .= "<td style='vertical-align: top;'>"; 
        $legend .= "<table style='border-collapse: collapse; width: 100%;'>";
        $legend .= "<tr><td><h6 style='margin: 0; margin-bottom: 10px;'>" . htmlspecialchars($criteria) . "</h6></td></tr>";
        $legend .= "</div></table>";
        $legend .= "</td>";
    }

    $results = '';
    $studentFinal = 0;
    $avgCount = 0;

    foreach ($avgResults as $result) {
        $average = $result['average'];
        
        // Display the result
        $results .= "<tr><td>" . htmlspecialchars($result['course_code']) . "</td><td align='center'>" . 
                    (is_numeric($average) ? $average : '-') . 
                    "</td></tr>";
        
        // Only include numeric averages in the final calculation
        if (is_numeric($average)) {
            $studentFinal += $average;
            $avgCount++;
        }
    }

    $finalResults = ($avgCount > 0) ? round($studentFinal / $avgCount, 2) : '-';

    return [
        'facultyCourses' => $facultyCourses,
        'questions' => $questions,
        'results' => $results,
        'finalResults' => $finalResults,
        'avgResults' => $avgResults,
        'legend' => $legend
    ];
}

// Generating evaluation data for Student role
$evaluationDataStudent = generateFacultyCourseEvaluation($facultyData, $facultyNo, 'Student', $period);
$facultyCoursesStudent = $evaluationDataStudent['facultyCourses'];
$questionsStudent = $evaluationDataStudent['questions'];
$resultsStudent = $evaluationDataStudent['results'];
$finalResultsStudent = $evaluationDataStudent['finalResults'];
$avgResultsStudent = $evaluationDataStudent['avgResults'];
$legendStudent = $evaluationDataStudent['legend'];

// Generating evaluation data for Faculty role
$evaluationDataFaculty = generateFacultyCourseEvaluation($facultyData, $facultyNo, 'Faculty', $period);
$facultyCoursesFaculty = $evaluationDataFaculty['facultyCourses'];
$questionsFaculty = $evaluationDataFaculty['questions'];
$resultsFaculty = $evaluationDataFaculty['results'];
$finalResultsFaculty = $evaluationDataFaculty['finalResults'];
$avgResultsFaculty = $evaluationDataFaculty['avgResults'];
$legendFaculty = $evaluationDataFaculty['legend'];

// Generating evaluation data for Program Chair role
$evaluationDataChair = generateFacultyCourseEvaluation($facultyData, $facultyNo, 'Program_chair', $period);
$facultyCoursesChair = $evaluationDataChair['facultyCourses'];
$questionsChair = $evaluationDataChair['questions'];
$resultsChair = $evaluationDataChair['results'];
$finalResultsChair = $evaluationDataChair['finalResults'];
$avgResultsChair = $evaluationDataChair['avgResults'];
$legendChair = $evaluationDataChair['legend'];

// Generating evaluation data for Self role
$evaluationDataSelf = generateFacultyCourseEvaluation($facultyData, $facultyNo, 'Self', $period);
$facultyCoursesSelf = $evaluationDataSelf['facultyCourses'];
$questionsSelf = $evaluationDataSelf['questions'];
$resultsSelf = $evaluationDataSelf['results'];
$finalResultsSelf = $evaluationDataSelf['finalResults'];
$avgResultsSelf = $evaluationDataSelf['avgResults'];
$legendSelf = $evaluationDataSelf['legend'];

$array_graph_overall = [
    ['Student', $finalResultsStudent === '-' ? 0 : $finalResultsStudent],
    ['Faculty', $finalResultsFaculty === '-' ? 0 : $finalResultsFaculty],
    ['Self', $finalResultsSelf === '-' ? 0 : $finalResultsSelf],
    ['Chair/Dean', $finalResultsChair === '-' ? 0 : $finalResultsChair],
];

function array_results($result) {
    return [$result['course_code'] . ' - ' . $result['section'], (float)$result['average']];
}
$array_graph_student = array_map("array_results", $avgResultsStudent);
$array_graph_peer = array_map("array_results", $avgResultsFaculty);
$array_graph_self = array_map("array_results", $avgResultsSelf);
$array_graph_chair = array_map("array_results", $avgResultsChair);
$array_graph_combined = [];

if (empty($avgResultsStudent) || empty($avgResultsFaculty) || empty($avgResultsSelf) || empty($avgResultsChair)) {
    // Fallback for empty results
    $array_graph_combined[] = ['No results', 0, 0, 0, 0];
} else {
    foreach ($avgResultsStudent as $index => $studentResult) {
        $facultyResult = $avgResultsFaculty[$index];
        $selfResult = $avgResultsSelf[$index];
        $chairResult = $avgResultsChair[$index];

        $array_graph_combined[] = [
            $studentResult['course_code'] . ' - ' . $studentResult['section'],
            round((float)$studentResult['average'], 2),
            round((float)$facultyResult['average'], 2),
            round((float)$chairResult['average'], 2),
            round((float)$selfResult['average'], 2)
        ];
    }
}
$header = ['Evaluator','Rating'];
$header_combined = ['Evaluator', 'Student', 'Peer', 'Chair/Dean', 'Self'];
array_unshift($array_graph_student, $header);
array_unshift($array_graph_peer, $header);
array_unshift($array_graph_self, $header);
array_unshift($array_graph_chair, $header);
array_unshift($array_graph_overall, $header);
array_unshift($array_graph_combined, $header_combined);



$overallResult = [
    'Student' => $finalResultsStudent,
    'Faculty' => $finalResultsFaculty,
    'Self' => $finalResultsSelf,
    'Chair/Dean' => $finalResultsChair,
];

$finalOverall = '';
$overallTotal = 0;

$weights = [
    'Student' => ($student_scoring/100),
    'Faculty' => ($peer_scoring/100),
    'Self' => ($self_scoring/100),
    'Chair/Dean' => ($chair_scoring/100),
];

foreach ($overallResult as $evaluator => $average) {
    // Determine the weight and percentage
    $percentage = isset($weights[$evaluator]) ? $weights[$evaluator] * 100 : 0;

    // Handle empty averages by skipping calculation and displaying '-'
    if (is_numeric($average)) {
        $weightedTotal = $average * (isset($weights[$evaluator]) ? $weights[$evaluator] : 0);
        $overallTotal += $weightedTotal;
    } else {
        $weightedTotal = '-'; // Display '-' if the average is not numeric
    }

    // Append to finalOverall
    $finalOverall .= "<tr>
                        <td>" . htmlspecialchars($evaluator) . "</td>
                        <td>" . (is_numeric($average) ? htmlspecialchars(round($average, 2)) : '-') . "</td>
                        <td>" . htmlspecialchars($percentage) . "%</td>
                        <td>" . (is_numeric($weightedTotal) ? htmlspecialchars(round($weightedTotal, 2)) : '-') . "</td>
                    </tr>";
}

$sql_comment = "SELECT 
            se.comments
        FROM 
            students_evaluations se
        JOIN
            evaluations e ON e.evaluation_id = se.evaluation_id
        JOIN
            course_sections cs ON cs.course_section_id = e.course_section_id
        JOIN
            faculty_courses fc ON fc.course_section_id = cs.course_section_id
        JOIN
            faculty f ON f.faculty_id = fc.faculty_id
        WHERE 
            f.faculty_id = ($facultyId+1)
            AND comments IS NOT NULL 
            AND TRIM(comments) != ''
            AND e.period_id = $period
        ORDER BY 
            se.date_evaluated ASC";

$result_comment = mysqli_query($con, $sql_comment);


$comments = [];
if (mysqli_num_rows($result_comment) > 0) {
    while ($row = mysqli_fetch_assoc($result_comment)) {
        $comments[] = $row["comments"];
    }
}

// Generate HTML for displaying comments with pagination after every 30 rows
$page = 1;
$maxPage = ceil((count($comments)/30));
$showComments = '<div class="page-break"></div>
                <span class="evalbody">
                    <h5 style="margin-bottom: 10px;">**Student Comments/Suggestions | Page ' . $page . '/' . $maxPage . ' |**</h5>
                        <div>
                            <table class="comments">';

for ($i = 0; $i < count($comments); $i++) {
    // Add page break every 30 rows
    if ($i > 0 && $i % 30 === 0) {
        $page++;
        $showComments .= '
                            </table>
                        </div>
                    </span>
                    
                    <div class="page-break"></div>

                <span class="evalbody">
                    <h5 style="margin-bottom: 10px;">**Student Comments/Suggestions | Page ' . $page . '/' . $maxPage . ' |**</h5>
                        <div>
                            <table class="comments">';
    }

    // Output each comment in a single row
    $showComments .= '<tr><td style="vertical-align: top;">';
    $showComments .= htmlspecialchars($comments[$i] ?? '');
    $showComments .= '</td></tr>';
}

$showComments .= '</table>';

?>
