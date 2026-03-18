<?php
require 'config.php';
header('Content-Type: application/json');

$queue = $_GET['queue'] ?? '';
if (!$queue) exit(json_encode(['error' => 'no queue']));

$my = $conn->query("SELECT status, created_at FROM queue WHERE queue_number = '$queue'")->fetch_assoc();
if (!$my) exit(json_encode(['error' => 'not found']));

$currentRes = $conn->query("SELECT queue_number FROM queue WHERE status='serving' LIMIT 1");
$current = $currentRes->fetch_assoc()['queue_number'] ?? null;

// Position
$posRes = $conn->query("SELECT COUNT(*) as pos FROM queue WHERE status='waiting' AND created_at < '{$my['created_at']}'");
$position = ($posRes->fetch_assoc()['pos'] ?? 0) + 1;

// Waiting list (max 6)
$waiting = [];
$res = $conn->query("SELECT queue_number FROM queue WHERE status='waiting' ORDER BY created_at ASC LIMIT 6");
while ($r = $res->fetch_assoc()) $waiting[] = $r['queue_number'];

echo json_encode([
    'current'    => $current,
    'position'   => $position,
    'waiting'    => $waiting,
    'myStatus'   => $my['status']
]);
?>