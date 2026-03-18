<?php 
require 'config.php';
if (!isset($_SESSION['loggedin'])) header('Location: login.php');

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-white">
<div class="max-w-6xl mx-auto p-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold">Queue Dashboard</h1>
        <a href="logout.php" class="bg-red-600 px-6 py-3 rounded-lg">Logout</a>
    </div>

    <?php if($msg) echo "<p class='bg-green-600 p-4 rounded mb-6'>$msg</p>"; ?>

    <!-- Current Serving -->
    <div class="bg-green-900 p-6 rounded-xl mb-8">
        <h2 class="text-2xl mb-4">Now Serving</h2>
        <?php
        $res = $conn->query("SELECT queue_number FROM queue WHERE status='serving' LIMIT 1");
        $current = $res->fetch_assoc()['queue_number'] ?? 'None';
        echo "<div class='text-6xl font-bold text-green-400'>$current</div>";
        ?>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <!-- Actions -->
        <div>
            <form method="POST" action="process.php">
                <input type="hidden" name="action" value="call_next">
                <button class="w-full bg-blue-600 hover:bg-blue-700 text-2xl py-8 rounded-2xl font-bold">
                    CALL NEXT
                </button>
            </form>
            
            <form method="POST" action="process.php" class="mt-4">
                <input type="hidden" name="action" value="finish_current">
                <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-2xl py-6 rounded-2xl font-bold">
                    FINISH CURRENT
                </button>
            </form>
            
            <form method="POST" action="process.php" class="mt-4">
                <input type="hidden" name="action" value="reset">
                <button onclick="return confirm('Reset entire queue?')" 
                        class="w-full bg-red-600 hover:bg-red-700 text-2xl py-6 rounded-2xl font-bold">
                    RESET QUEUE
                </button>
            </form>
        </div>

        <!-- Waiting List -->
        <div>
            <h2 class="text-2xl mb-4">Waiting</h2>
            <div class="bg-gray-900 p-6 rounded-xl max-h-96 overflow-auto">
                <?php
                $result = $conn->query("SELECT queue_number FROM queue WHERE status='waiting' ORDER BY created_at ASC");
                if ($result->num_rows == 0) echo "<p class='text-gray-400'>No one waiting</p>";
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='text-3xl py-3 border-b border-gray-700'>{$row['queue_number']}</div>";
                }
                ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>