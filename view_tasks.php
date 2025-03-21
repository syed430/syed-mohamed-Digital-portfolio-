<?php
include __DIR__ . "/db.php";

$search_id = isset($_GET['search_id']) ? $_GET['search_id'] : '';

$query = "SELECT employees.employee_name, tasks.task_name, tasks.task_description, tasks.creation_date, tasks.due_date, tasks.status
          FROM tasks 
          JOIN employees ON tasks.employee_id = employees.employee_id";

if ($search_id !== '') {
    $query .= " WHERE employees.employee_id = ?";
}
$query .= " ORDER BY tasks.due_date DESC";

$stmt = $conn->prepare($query);
if ($search_id !== '') {
    $stmt->bind_param("i", $search_id);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen p-6">

    <div class="w-full max-w-6xl bg-white p-8 rounded-xl shadow-lg animate-fadeIn">
        <h1 class="text-3xl font-bold text-gray-800 text-center mb-6">Task List</h1>

        <div class="flex flex-wrap justify-between items-center mb-6">
            <button onclick="location.href='index.php'" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-700 transition">Back to Dashboard</button>
            <button onclick="confirmDownload()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700 transition">Generate PDF</button>
        </div>

        <div class="flex flex-wrap gap-4 items-center justify-center mb-6">
            <input type="text" id="searchBox" placeholder="Enter Employee ID"
                class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-500">
            <button onclick="searchEmployee()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-700 transition">Search</button>
            <button onclick="resetSearch()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-700 transition">Show All</button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full bg-white border rounded-lg shadow-md">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left">Employee Name</th>
                        <th class="px-4 py-2 text-left">Task Name</th>
                        <th class="px-4 py-2 text-left">Description</th>
                        <th class="px-4 py-2 text-left">Creation Date</th>
                        <th class="px-4 py-2 text-left">Due Date</th>
                        <th class="px-4 py-2 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($task = $result->fetch_assoc()) { ?>
                        <tr class="border-t hover:bg-gray-100">
                            <td class="px-4 py-2"><?php echo htmlspecialchars($task['employee_name']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($task['task_name']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($task['task_description']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($task['creation_date']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($task['due_date']); ?></td>
                            <td class="px-4 py-2">
                                <?php 
                                    $status = strtolower($task['status']); // Ensure status is in lowercase
                                    $status_colors = [
                                        "completed" => "bg-green-500 text-white", // Make sure status is lowercase here
                                        "pending" => "bg-orange-500 text-white",
                                        "in progress" => "bg-yellow-500 text-white",
                                        "canceled" => "bg-red-500 text-white"
                                    ];
                                    // Set default color if status is not found in the array
                                    $status_class = $status_colors[$status] ?? 'bg-gray-500 text-white';
                                ?>
                                <span class="px-3 py-1 rounded-lg text-sm font-semibold <?php echo $status_class; ?>">
                                    <?php echo ucfirst($task['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function searchEmployee() {
            let empId = document.getElementById("searchBox").value;
            window.location.href = "view_tasks.php?search_id=" + empId;
        }

        function resetSearch() {
            window.location.href = "view_tasks.php";
        }

        function confirmDownload() {
            let searchParam = new URLSearchParams(window.location.search).get("search_id") || '';
            window.location.href = "generate_pdf.php?search_id=" + searchParam;
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
    </style>

</body>
</html>
