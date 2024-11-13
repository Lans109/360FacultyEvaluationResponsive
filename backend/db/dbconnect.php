<?php 
$servername = "localhost";
$username = "root";
$password = "";
$database = "evalsystem";

$con = mysqli_connect($servername, $username, $password, $database);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>