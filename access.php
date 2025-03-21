<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 via-purple-600 to-pink-500 bg-cover bg-fixed flex items-center justify-center min-h-screen">

    <!-- Access Section -->
    <div id="card" class="bg-white bg-opacity-30 flex flex-col items-center justify-center text-center backdrop-blur-lg p-8 rounded-xl  shadow-xl w-full max-w-md  text-start animate-fadeIn">
    <!-- <section class="bg-white flex flex-col items-center justify-center text-center py-20 px-6 bg-opacity-60 backdrop-blur-lg rounded-xl w-full max-w-md shadow-lg"> -->
        <h1 class="text-3xl font-bold text-white mb-8">Choose Register Type</h1>

        <!-- Manager Registration Button -->
        <a href="manager_access.php" class="w-full mb-4">
            <button class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-md">Manager Registration</button>
        </a><br>

        <!-- Employee Registration Button -->
        <a href="employee_access.php" class="w-full mb-4">
            <button class="w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-md">Employee Registration</button>
        </a><br>

        <!-- Back to Dashboard Button -->
        <a id="das" href="index.php" class="w-full">
            <button id ="das" class=" px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition shadow-md">Back to Dashboard</button>
        </a>
    </section>

</body>
</html>
<style>
    #das
    {
        margin-right: -50%;
    }
    </style>
