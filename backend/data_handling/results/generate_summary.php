<?php 
// Include configuration and database connection
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Authentication check
include '../authentication.php';

require_once ROOT_PATH . '/vendor/autoload.php';

$facultyId = $_POST['facultyId'];
$period = $_POST['period_id'];

include ROOT_PATH . '/modules/generate_report/report_data_fetch.php';
include ROOT_PATH . '/modules/generate_report/report_data_results.php';

$logo = "data:image/png;base64,".base64_encode(file_get_contents( ROOT_PATH . '/frontend/assets/logo.png'));
$user = $_SESSION['admin_name'] . " (ID: " . $_SESSION['admin_id'] . ")";
$imgStudent = $_POST['studentImageData'];
$imgFaculty = $_POST['facultyImageData'];
$imgChair = $_POST['chairImageData'];
$imgSelf = $_POST['selfImageData'];
$imgOverall = $_POST['overallImageData'];

$con->close();

$html = file_get_contents(ROOT_PATH . "/frontend/templates/summaryReport.html");
$date = date("Y/m/d");
$html = str_replace("{{ user }}", $user, $html);
$html = str_replace("{{ ay }}", $academicYear, $html);
$html = str_replace("{{ sem }}", $semester, $html);
$html = str_replace("{{ date }}", $date, $html);
$html = str_replace("{{ name }}", $facultyName, $html);
$html = str_replace("{{ facultyNo }}", $facultyNo, $html);
$html = str_replace("{{ program }}", $facultyProgram, $html);
$html = str_replace("{{ logo }}", $logo, $html);

function replaceHtmlPlaceholders($html, $data) {
    foreach ($data as $key => $value) {
        $html = str_replace("{{ $key }}", $value, $html);
    }
    return $html;
}

$dataStudent = [
    'questionCode' => $questionsStudent,
    'sectionEval' => $facultyCoursesStudent,
    'summaryResultStudent' => $resultsStudent,
    'studentRating' => $finalResultsStudent,
    'graphStudent' => $imgStudent,
    'legendStudent' => $legendStudent,
];

$dataFaculty = [
    'questionCodePeer' => $questionsFaculty,
    'sectionEvalPeer' => $facultyCoursesFaculty,
    'summaryResultPeer' => $resultsFaculty,
    'peerRating' => $finalResultsFaculty,
    'graphFaculty' => $imgFaculty,
    'legendFaculty' => $legendFaculty,
];

$dataProgramChair = [
    'questionCodeChair' => $questionsChair,
    'sectionEvalChair' => $facultyCoursesChair,
    'summaryResultChair' => $resultsChair,
    'chairRating' => $finalResultsChair,
    'graphChair' => $imgChair,
    'legendChair' => $legendChair,
];

$dataSelf = [
    'questionCodeSelf' => $questionsSelf,
    'sectionEvalSelf' => $facultyCoursesSelf,
    'summaryResultSelf' => $resultsSelf,
    'selfRating' => $finalResultsSelf,
    'graphSelf' => $imgSelf,
    'legendSelf' => $legendSelf,
];
$html = str_replace("{{ overallFinal }}", $finalOverall, $html);

$overallTotal = floatval($overallTotal);
$html = str_replace("{{ overallTotal }}", round($overallTotal, 2), $html);

$html = str_replace("{{ overall }}", $imgOverall, $html);

$html = str_replace("{{ comments }}", $showComments, $html);

$html = replaceHtmlPlaceholders($html, $dataStudent);

$html = replaceHtmlPlaceholders($html, $dataFaculty);

$html = replaceHtmlPlaceholders($html, $dataProgramChair);

$html = replaceHtmlPlaceholders($html, $dataSelf);

include ROOT_PATH . '/modules/generate_report/generate_PDF.php';
?>