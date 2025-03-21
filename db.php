<?php
$servername = "localhost";
$username = "root"; // Default username
$password = ""; // Default password
$dbname = "digital_smart_task_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>