<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Smart Task Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-blue-600 p-4 shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-white text-2xl font-bold">Task Management</h1>
            <div class="space-x-4">
                <a href="manager/login.php" class="text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Manager</a>
                <a href="employee/login.php" class="text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Employee</a>
                <a href="view_tasks.php" class="text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">View Tasks</a>
                <!-- New Access Link -->
                <a href="access.php" class="text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Registration</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="flex flex-col items-center justify-center text-center py-20 flex-grow px-6">
        <h1 class="text-4xl font-bold text-gray-800 animate-fadeIn">Welcome to Digital Smart Task Management</h1>
        <p class="text-lg text-gray-600 mt-3 animate-fadeIn">Manage your tasks effectively, whether you're a manager or an employee.</p>
        <div class="imgalign">
        <img src="./image/images-removebg-preview.png" alt="Task Icon" class="w-32 mt-5 animate-bounce">
        </div>
    </section>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 1s ease-in-out;
        }
        .animate-bounce {
            animation: bounce 2s infinite;
        }
        .imgalign img
        {
          margin-top:80px;
          height:"20px";
          width:"10px"
        }
    </style>

</body>
</html>
