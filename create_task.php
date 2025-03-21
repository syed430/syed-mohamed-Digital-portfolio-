<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "../db.php";

// Check if the manager is logged in
if (!isset($_SESSION['manager_id'])) {
    header("Location: login.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $employee = !empty($_POST['employee']) ? $_POST['employee'] : NULL;
    $creation_date = $_POST['creation_date'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    $task_name = $title; // Using title as task name

    $stmt = $conn->prepare("INSERT INTO tasks (task_name, task_description, employee_id, creation_date, due_date, status, name) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("ssissss", $title, $description, $employee, $creation_date, $due_date, $status, $task_name);
        if ($stmt->execute()) {
            header("Location: task_list.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// Fetch employees for dropdown
$employee_query = "SELECT * FROM employees";
$employees = $conn->query($employee_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Task</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 flex flex-col items-center via-purple-600 to-pink-500 bg-cover bg-fixed flex items-center justify-center min-h-screen">


    <!-- Banner Image -->
    <div class="w-full max-w-3xl">
    </div >

    <!-- Task Creation Form -->
    <!-- <div class="w-full max-w-3xl bg-white p-8 rounded-xl shadow-lg animate-fadeIn">-->
    <div class="bg-white bg-opacity-30 backdrop-blur-lg p-8 rounded-xl shadow-xl w-full max-w-lg text-start animate-fadeIn">

        <h1 class="text-2xl font-bold text-gray-800 text-center mb-6">Create New Task</h1>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block font-semibold text-gray-700">Task Title:</label>
                <input type="text" name="title" required 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
            </div>

            <div>
                <label class="block font-semibold text-gray-700">Task Description:</label>
                <textarea name="description" required 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition"></textarea>
            </div>

            <div>
                <label class="block font-semibold text-gray-700">Assign Employee:</label>
                <select name="employee" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                    <option value="">None</option>
                    <?php while ($row = $employees->fetch_assoc()) { ?>
                        <option value="<?php echo htmlspecialchars($row['employee_id']); ?>">
                            <?php echo htmlspecialchars($row['employee_name']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-gray-700">Creation Date:</label>
                    <input type="date" name="creation_date" required 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                </div>
                <div>
                    <label class="block font-semibold text-gray-700">Due Date:</label>
                    <input type="date" name="due_date" required 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-gray-700">Status:</label>
                <select name="status" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                    <option value="Pending" class="text-orange-500">Pending</option>
                    <option value="In Progress" class="text-yellow-500">In Progress</option>
                    <option value="Completed" class="text-green-500">Completed</option>
                    <option value="Cancelled" class="text-red-500">Cancelled</option>
                </select>
            </div>

            <div class="flex justify-between">
                <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700 transition">
                    Create Task
                </button>
                <button type="button" onclick="window.location.href='task_list.php'"
                    class="px-6 py-2 bg-red-500 text-white rounded-lg">
                    Back
                </button>
            </div>
        </form>
    </div>

    <script>
        function fadeInAnimation() {
            document.querySelector('.animate-fadeIn').style.opacity = 0;
            setTimeout(() => {
                document.querySelector('.animate-fadeIn').style.opacity = 1;
                document.querySelector('.animate-fadeIn').style.transform = "translateY(0)";
            }, 200);
        }

        window.onload = fadeInAnimation;
    </script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.8s ease-in-out;
        }

    </style>

</body>
</html>
