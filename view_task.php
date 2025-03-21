<?php
session_start();
include "../db.php"; // Database connection file

// Check if employee is logged in
if (!isset($_SESSION['employee'])) {
    header("Location: login.php");
    exit;
}

$task_id = $_GET['id']; // Get task ID from URL

// Fetch task details from the database
$sql = "SELECT * FROM tasks WHERE id = '$task_id'";
$result = $conn->query($sql);
$task = $result->fetch_assoc();

if (!$task) {
    echo "Task not found.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Details</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h1>Task Details</h1>
    <p><strong>Title:</strong> <?php echo $task['title']; ?></p>
    <p><strong>Description:</strong> <?php echo $task['description']; ?></p>
    <p><strong>Assigned Employee:</strong> <?php echo $task['assigned_employee']; ?></p>
    <p><strong>Due Date:</strong> <?php echo $task['due_date']; ?></p>
    <p><strong>Status:</strong> <?php echo $task['status']; ?></p>

    <a href="task_list.php">Back to Task List</a>
</body>
</html>