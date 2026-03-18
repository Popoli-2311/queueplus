<?php
require 'config.php';
header('Content-Type: application/json');

$currentRes = $conn->query("SELECT queue_number FROM queue WHERE status='serving' LIMIT 1");
$current = $currentRes->fetch_assoc()['queue_number'] ?? null;

$waiting = [];
$res = $conn->query("SELECT queue_number FROM queue WHERE status='waiting' ORDER BY created_at ASC LIMIT 9");
while ($row = $res->fetch_assoc()) {
    $waiting[] = $row['queue_number'];
}

echo json_encode(['current' => $current, 'waiting' => $waiting]);
?>