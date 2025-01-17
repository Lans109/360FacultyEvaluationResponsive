<?php
define('ROOT_PATH', __DIR__);
define('FRONTEND_PATH', ROOT_PATH . '/frontend');
define('BACKEND_PATH', ROOT_PATH . '/backend');
define('MODULES_PATH', ROOT_PATH . '/modules');

define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'evalsystem');

define('SITE_NAME', 'Faculty Evaluation System');
define('SITE_URL', 'http://localhost/360FacultyEvaluationSystem');

date_default_timezone_set('Asia/Manila');

ini_set('display_errors', 1);
error_reporting(E_ALL);

?>