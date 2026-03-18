<?php
require 'config.php';
if (!isset($_SESSION['loggedin'])) die('Access denied');

$action = $_POST['action'] ?? '';

if ($action === 'call_next') {
    $conn->query("UPDATE queue SET status='completed' WHERE status='serving'");
    
    $res = $conn->query("SELECT id, customer_phone FROM queue WHERE status='waiting' ORDER BY created_at ASC LIMIT 1");
    if ($row = $res->fetch_assoc()) {
        $id = $row['id'];
        $phone = $row['customer_phone'];
        
        $stmt = $conn->prepare("UPDATE queue SET status='serving' WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        // SEND SMS
        if ($phone) {
            sendSMS($phone, "Hi! Your queue number is now being served.\nPlease proceed to the counter. Thank you!");
        }
    }
    header('Location: dashboard.php?msg=Next customer called + SMS sent!');
}

elseif ($action === 'finish_current') {
    $conn->query("UPDATE queue SET status='completed' WHERE status='serving'");
    header('Location: dashboard.php?msg=Current customer finished!');
}

elseif ($action === 'reset') {
    $conn->query("TRUNCATE TABLE queue");
    header('Location: dashboard.php?msg=Queue reset!');
}
?>