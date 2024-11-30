<?php 
// Include configuration and database connection
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Authentication check
include '../authentication.php';

$period = $_POST['period_id'];
$semester = $_POST['semester'];
$academic_year = $_POST['academic_year'];

require_once ROOT_PATH . '/vendor/autoload.php';

include ROOT_PATH . '/modules/generate_faculty_list/faculty_list_data_result.php';

$logo = "data:image/png;base64,".base64_encode(file_get_contents( ROOT_PATH . '/frontend/assets/LPU Black.png'));

$con->close();

$html = file_get_contents(ROOT_PATH . "/frontend/templates/facultyRanking.html");
$html = str_replace("{{ ranking }}", $ranking, $html);
$html = str_replace("{{ logo }}", $logo, $html);
$html = str_replace("{{ sem }}", $semester, $html);
$html = str_replace("{{ ay }}", $academic_year, $html);

include ROOT_PATH . '/modules/generate_faculty_list/generate_PDF.php';

?>