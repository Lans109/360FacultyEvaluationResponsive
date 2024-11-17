
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

                    $overallAverage = ($questionCount > 0) ? ($totalAvg / $questionCount) : 0;
                    $facultyCourses .= "<td class='avg' style='border-left: 2px solid #D3D3D3;'>" . round($overallAverage, 2) . "</td></tr>";

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
        $results .= "<tr><td>" . htmlspecialchars($result['course_code']) . "</td><td align='center'>" . round($result['average'], 2) . "</td></tr>";
        $studentFinal += $result['average'];
        $avgCount++;
    }

    $finalResults = ($avgCount > 0) ? round($studentFinal / $avgCount, 2) : 0;

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

//Arrays for graph generation ----------------------------------------------------------------------------------------------------
$array_graph_overall = [
    ['Student', $finalResultsStudent],
    ['Faculty', $finalResultsFaculty],
    ['Self', $finalResultsSelf],
    ['Chair/Dean', $finalResultsChair],
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
            (float)$studentResult['average'],
            (float)$facultyResult['average'],
            (float)$chairResult['average'],
            (float)$selfResult['average']
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


//---------------------------------------------------------------------------------------------------------------------------------


//Final Result Generation --------------------------------------------------------------------------------------------------------
$overallResult = [
    'Student' => $finalResultsStudent,
    'Faculty' => $finalResultsFaculty,
    'Self' => $finalResultsSelf,
    'Chair/Dean' => $finalResultsChair,
];

$finalOverall = '';
$overallTotal = 0;

$weights = [
    'Student' => 0.5,
    'Faculty' => 0.05,
    'Self' => 0.05,
    'Chair/Dean' => 0.4,
];

foreach ($overallResult as $evaluator => $average) {
    if (array_key_exists($evaluator, $weights)) {
        $percentage = $weights[$evaluator] * 100;
    } else {
        $percentage = 0;
    }

    $weightedTotal = $average * (isset($weights[$evaluator]) ? $weights[$evaluator] : 0); 

    $finalOverall .= "<tr>
                        <td>" . htmlspecialchars($evaluator) . "</td>
                        <td>" . htmlspecialchars($average) . "</td>
                        <td>" . htmlspecialchars($percentage) . "%</td>
                        <td>" . htmlspecialchars($weightedTotal) . "</td>
                    </tr>";
    $overallTotal += $weightedTotal;
}
//------------------------------------------------------------------------------------------------------------------------------


$columns = 2; // Number of columns per row
$showComments = '<div class="page-break"></div>
                <span class="evalbody">
                    <h5 style="margin-bottom: 10px;">**Student Comments/Suggestions**</h5>
                        <div>
                            <table class="comments">';

for ($i = 0; $i < count($comments); $i++) {
    // Add page break every 30 rows
    if ($i > 0 && $i % 30 === 0) {
        $showComments .= '
                            </table>
                        </div>
                    </span>
                    
                    <div class="page-break"></div>

                <span class="evalbody">
                    <h5 style="margin-bottom: 10px;">**Student Comments/Suggestions**</h5>
                        <div>
                            <table class="comments">';
    }
    
    // Start a new row for every set of columns
    if ($i % $columns == 0) {
        $showComments .= '<tr>';
    }

    // Output each comment in a cell
    $showComments .= '<td style="vertical-align: top; width: ' . (100 / $columns) . '%;">';
    $showComments .= htmlspecialchars($comments[$i] ?? '');
    $showComments .= '</td>';

    // Close the row after the last column
    if (($i + 1) % $columns == 0) {
        $showComments .= '</tr>';
    }
}

// Close the last row if it’s not completely filled
if (count($comments) % $columns != 0) {
    $showComments .= '</tr>';
}

$showComments .= '</table>';

?>
