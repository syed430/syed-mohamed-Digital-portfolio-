<?php
session_start();
include "../db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT id, username, password FROM managers WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // **No password hashing, directly checking password as plain text**
        if ($password === $row['password']) {
            $_SESSION['manager_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: task_list.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-500 via-purple-600 to-pink-500 bg-cover bg-fixed flex items-center justify-center min-h-screen">

    <!-- Login Card -->
    <div class="bg-white bg-opacity-30 backdrop-blur-lg p-8 rounded-xl shadow-xl w-full max-w-md text-center animate-fadeIn">
        <h2 class="text-2xl font-bold text-gray-800">Manager Login</h2> 
        <p class="text-black-700 mb-6">Sign in to manage tasks efficiently</p>

        <?php if (!empty($error)): ?>
            <p class="text-red-500 mb-4"><?= $error; ?></p>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 font-medium text-left">Username</label>
                <input type="text" name="username" required 
                    class="w-full px-4 py-2 mt-1 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
            </div>

            <div>
                <label class="block text-gray-700 font-medium text-left">Password</label>
                <input type="password" name="password" required 
                    class="w-full px-4 py-2 mt-1 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
            </div>

            <div class="btn-main">
                <button id="bt-two" type="submit"
                    class=" bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition shadow-md">
                    Login
                </button>
                <button id="bt" onclick="window.location.href='logout.php'" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-700 transition">Logout</button>
            </div>
        </form>
    </div>

    <!-- Animations -->
    <style>
        #bt {
            margin-left: 10vh;
            width: 30%;
        }
        #bt-two {
            width: 30%;
        }
        .btn-main {
            display: inline-block;
            justify-content: space-evenly;
            width: 100%;
        }
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
