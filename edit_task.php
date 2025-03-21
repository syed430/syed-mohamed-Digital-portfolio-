<?php
session_start();
include "../db.php";

// Check if the manager is logged in
if (!isset($_SESSION['manager_id'])) {
    header("Location: manager_login.php");
    exit;
}

// Validate if 'id' is passed in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid task ID.");
}

$id = intval($_GET['id']); // Convert to integer to prevent SQL injection

// Fetch the task details
$task_query = "SELECT * FROM tasks WHERE id = ?";
$stmt = $conn->prepare($task_query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Task not found.");
}

$task = $result->fetch_assoc();

// Fetch employees list
$employee_query = "SELECT * FROM employees";
$employees = $conn->query($employee_query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save'])) {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $employee = !empty($_POST['employee']) ? intval($_POST['employee']) : NULL;
        $due_date = $_POST['due_date'];
        $status = $_POST['status'];

        // Update query
        $sql = "UPDATE tasks SET task_name = ?, task_description = ?, employee_id = ?, due_date = ?, status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissi", $title, $description, $employee, $due_date, $status, $id);

        if ($stmt->execute()) {
            header("Location: task_list.php");
            exit();
        } else {
            echo "Error updating task: " . $conn->error;
        }
    }

    // If cancel button is clicked
    if (isset($_POST['cancel_task'])) {
        $cancel_sql = "UPDATE tasks SET status = 'Canceled' WHERE id = ?";
        $stmt = $conn->prepare($cancel_sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: task_list.php");
            exit();
        } else {
            echo "Error updating status: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<!-- <body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen p-6"> -->
<body class="bg-gradient-to-r from-blue-500 via-purple-600 to-pink-500 bg-cover bg-fixed flex items-center justify-center min-h-screen">

    <!-- <div class="w-full max-w-lg bg-white p-8 rounded-xl shadow-lg animate-fadeIn"> -->
    <div class="bg-white bg-opacity-30 backdrop-blur-lg p-8 rounded-xl shadow-xl w-full max-w-lg text-start animate-fadeIn">

        <h1 class="text-3xl font-bold text-gray-800 text-center mb-6">Edit Task</h1>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700">Task Title</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($task['task_name']); ?>" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-500">
            </div>

            <div>
                <label class="block text-gray-700">Task Description</label>
                <textarea name="description" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-500"><?php echo htmlspecialchars($task['task_description']); ?></textarea>
            </div>

            <div>
                <label class="block text-gray-700">Assign Employee</label>
                <select name="employee"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-500">
                    <option value="">None</option>
                    <?php while ($row = $employees->fetch_assoc()) { ?>
                        <option value="<?php echo $row['employee_id']; ?>"
                            <?php echo ($row['employee_id'] == $task['employee_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['employee_name']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div>
                <label class="block text-gray-700">Due Date</label>
                <input type="date" name="due_date" value="<?php echo $task['due_date']; ?>" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-500">
            </div>

            <div>
                <label class="block text-gray-700">Status</label>
                <select name="status"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-500">
                    <option value="Pending" <?php echo ($task['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="In Progress" <?php echo ($task['status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                    <option value="Completed" <?php echo ($task['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                    <option value="Canceled" <?php echo ($task['status'] == 'Canceled') ? 'selected' : ''; ?>>Canceled</option>
                </select>
            </div>

            <div class="flex justify-between mt-6">
                <button type="submit" name="save"
                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-700 transition">Save Changes</button>
                <button type="submit" name="cancel_task"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-700 transition">Cancel Task</button>
                <button type="button" onclick="window.location.href='task_list.php'"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700 transition">Back</button>
            </div>
        </form>
    </div>

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
