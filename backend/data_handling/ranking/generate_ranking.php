<?php 
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';
require_once ROOT_PATH . '/vendor/autoload.php';

$ranking = $_POST['ranking'];

$con->close();

$html = file_get_contents(ROOT_PATH . "/frontend/templates/facultyRanking.html");
$html = str_replace("{{ ranking }}", $ranking, $html);

include ROOT_PATH . '/modules/generate_faculty_list/generate_PDF.php';

?>