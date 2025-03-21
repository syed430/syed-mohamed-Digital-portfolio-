<?php
// Include the database connection
include __DIR__ . "/db.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    // Get the form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if fields are empty
    if (empty($username) || empty($password)) {
        echo "<script>alert('Please fill in all fields.');</script>";
    } else {
        // Insert the data into the managers table
        $query = "INSERT INTO managers (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $password);

        // Execute the query and check for success
        if ($stmt->execute()) {
            echo "<script>alert('Manager registered successfully!'); window.location.href='access.php';</script>";
        } else {
            echo "<script>alert('Error: Could not register manager. Please try again.');</script>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 via-purple-600 to-pink-500 bg-cover bg-fixed flex items-center justify-center min-h-screen">

    <!-- Registration Form -->
    <div id="card" class="bg-white bg-opacity-30 flex flex-col items-center justify-center text-center backdrop-blur-lg p-8 rounded-xl shadow-xl w-full max-w-md text-start animate-fadeIn">
       
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Manager Registration</h1>

        <form method="POST" class="space-y-6 w-full" onsubmit="return validateForm()">
            <div>
                <label for="username" class="block text-black font-medium text-left">Username</label>
                <input type="text" name="username" id="username" required
                    class="w-full px-4 py-2 border rounded-lg mb-4 focus:outline-none focus:ring focus:border-blue-500">
            </div>

            <div>
                <label for="password" class="block text-black font-medium text-left">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 border rounded-lg mb-4 focus:outline-none focus:ring focus:border-blue-500">
            </div>

            <div class="btn-main flex justify-between">
                <!-- Submit Button -->
                <button type="submit" name="submit" id="submit-btn" disabled
                    class="px-6 py-3 bg-gray-400 text-white rounded-lg transition shadow-md cursor-not-allowed">Submit</button>

                <!-- Back Button -->
                <a href="access.php">
                    <button type="button" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition shadow-md">Back</button>
                </a>
            </div>
        </form>
    </div>

    <script>
        // Enable submit button only if fields are filled
        function validateForm() {
            const username = document.getElementById("username").value.trim();
            const password = document.getElementById("password").value.trim();
            const submitBtn = document.getElementById("submit-btn");

            if (username !== "" && password !== "") {
                submitBtn.disabled = false;
                submitBtn.classList.remove("bg-gray-400", "cursor-not-allowed");
                submitBtn.classList.add("bg-blue-600", "hover:bg-blue-700");
            } else {
                submitBtn.disabled = true;
                submitBtn.classList.remove("bg-blue-600", "hover:bg-blue-700");
                submitBtn.classList.add("bg-gray-400", "cursor-not-allowed");
            }
        }

        document.getElementById("username").addEventListener("input", validateForm);
        document.getElementById("password").addEventListener("input", validateForm);
    </script>

    <style>
        .animate-fadeIn {
            animation: fadeIn 0.8s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

</body>
</html>
