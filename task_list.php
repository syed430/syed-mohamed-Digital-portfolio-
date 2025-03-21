<?php
session_start();
include "../db.php";

// Check if manager is logged in
if (!isset($_SESSION['manager_id'])) {
    header("Location: manager_login.php");
    exit;
}

// Handle search query
$search_employee_id = isset($_GET['employee_id']) ? $_GET['employee_id'] : '';

// Fetch tasks with optional employee ID filtering
$sql = "SELECT tasks.id, employees.employee_id, employees.employee_name, tasks.task_name, tasks.task_description, tasks.due_date, tasks.status
        FROM tasks 
        JOIN employees ON tasks.employee_id = employees.employee_id";

if (!empty($search_employee_id)) {
    $sql .= " WHERE employees.employee_id = ?";
}

$stmt = $conn->prepare($sql);

if (!empty($search_employee_id)) {
    $stmt->bind_param("i", $search_employee_id);
}

$stmt->execute();
$result = $stmt->get_result();

$tasks = [];
while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center p-6">

    <!-- Top Bar -->
    <div class="w-full max-w-5xl bg-white p-4 rounded-xl shadow-md flex justify-between items-center mb-6 animate-fadeIn">
        <h2 class="text-xl font-bold text-gray-800">Welcome, <?php echo htmlspecialchars($username); ?> Manager</h2>
        <button onclick="window.location.href='logout.php'" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-700 transition">Logout</button>
    </div>

    <!-- Main Content -->
    <div class="w-full max-w-5xl bg-white p-6 rounded-xl shadow-md animate-fadeIn">
        <h1 class="text-2xl font-bold text-center mb-6 text-gray-800">Task List</h1>

        <!-- Search Box -->
        <div class="flex gap-4 mb-6">
            <input type="text" id="searchEmployeeId" placeholder="Enter Employee ID"
                class="border rounded-lg px-4 py-2 flex-1 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                value="<?php echo htmlspecialchars($search_employee_id); ?>">
            <button onclick="filterTasks()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700 transition">Search</button>
            <button onclick="showAllTasks()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-700 transition">Show All</button>
        </div>

        <!-- Button to create a new task -->
        <button onclick="window.location.href='create_task.php'"
            class="w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-700 transition shadow-md mb-6">
            Create Task
        </button>

        <?php if (!empty($tasks)): ?>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300 rounded-xl shadow-md">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="p-3 text-left">Employee Name</th>
                            <th class="p-3 text-left">Task Name</th>
                            <th class="p-3 text-left">Description</th>
                            <th class="p-3 text-left">Due Date</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $task): ?>
                        <tr class="hover:bg-gray-100 transition">
                            <td class="p-3"><?php echo htmlspecialchars($task['employee_name']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($task['task_name']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($task['task_description']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($task['due_date']); ?></td>
                            <td class="p-3">
                                <span class="px-3 py-1 rounded-lg text-white text-sm font-semibold
                                    <?php 
                                        if ($task['status'] == '') echo 'bg-green-500';
                                        $status = trim($task['status']); // Remove any extra whitespace
                                        if ($status == 'Completed') {
                                            echo 'bg-green-500';
                                        } elseif ($status == 'pending') {
                                            echo 'bg-gray-500';
                                        } elseif ($status == 'Canceled') {
                                            echo 'bg-red-500';
                                        } elseif ($status == 'In Progress') {
                                            echo 'bg-yellow-500';
                                        } else {
                                            echo 'bg-orange-500'; // Add a default class for unexpected status
                                        }
                                    ?>">
                                    <?php echo htmlspecialchars($task['status']); ?>
                                </span>
                            </td>
                            <td class="p-3">
                                <a class="img-main" href="edit_task.php?id=<?php echo $task['id']; ?>">
                                    <img src="../image/4226577.png" alt="Edit" class="w-6 h-6 hover:opacity-80 transition">
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-red-500 text-center mt-4">No tasks found for the entered Employee ID.</p>
        <?php endif; ?>
    </div>

    <script>
        function filterTasks() {
            var employeeId = document.getElementById("searchEmployeeId").value;
            window.location.href = "task_list.php?employee_id=" + employeeId;
        }

        function showAllTasks() {
            window.location.href = "task_list.php";
        }
    </script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.8s ease-in-out;
        }
        .img-main img
        {
            margin-left: 20px;
        }
    </style>

</body>
</html>
