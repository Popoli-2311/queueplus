<?php require 'config.php'; 
if (isset($_SESSION['loggedin'])) header('Location: dashboard.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center min-h-screen">
    <div class="bg-gray-800 p-8 rounded-xl shadow-xl w-96">
        <h2 class="text-3xl font-bold text-center mb-6">Admin Login</h2>
        <form method="POST" action="login_process.php">
            <input type="text" name="username" placeholder="Username" required 
                   class="w-full p-4 rounded-lg bg-gray-700 mb-4 text-lg">
            <input type="password" name="password" placeholder="Password" required 
                   class="w-full p-4 rounded-lg bg-gray-700 mb-6 text-lg">
            <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 py-4 rounded-lg text-xl font-semibold">
                Login
            </button>
        </form>
    </div>
</body>
</html>