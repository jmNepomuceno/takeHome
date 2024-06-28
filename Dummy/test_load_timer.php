<?php
session_start();
$elapsedTime = isset($_SESSION['elapsedTime']) ? floatval($_SESSION['elapsedTime']) : 0;
$running = isset($_SESSION['running']) ? boolval($_SESSION['running']) : false;
$startTime = isset($_SESSION['startTime']) ? floatval($_SESSION['startTime']) : 0;

echo json_encode([
    'status' => 'success',
    'elapsedTime' => $elapsedTime,
    'running' => $running,
    'startTime' => $startTime
]);
?>
