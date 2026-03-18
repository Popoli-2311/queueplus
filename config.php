<?php
session_start();
$host = 'sql206.infinityfree.com';
$db   = 'if0_41419877_queue_system';
$user = 'if0_41419877';
$pass = 'queueplus'; 

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// ================== SMS FUNCTION (Semaphore) ==================
function sendSMS($phone, $message) {
    $apikey = 'YOUR_SEMAPHORE_API_KEY_HERE';   // ← Get free at https://semaphore.co (sign up → API Keys)
    
    $data = [
        'apikey'  => $apikey,
        'number'  => $phone,          // 09xxxxxxxxx format
        'message' => $message
    ];

    $ch = curl_init('https://api.semaphore.co/api/v4/messages');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}

// ================== Next Queue Number ==================
function getNextQueueNumber($conn) {
    $result = $conn->query("SELECT MAX(CAST(SUBSTRING(queue_number, 2) AS UNSIGNED)) as max_num FROM queue WHERE queue_number LIKE 'A%'");
    $row = $result->fetch_assoc();
    $next = ($row['max_num'] ?? 0) + 1;
    return 'A' . str_pad($next, 3, '0', STR_PAD_LEFT);
}
?>