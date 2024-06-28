<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $elapsedTime = isset($data['elapsedTime']) ? floatval($data['elapsedTime']) : 0;
    $running = isset($data['running']) ? boolval($data['running']) : false;
    $startTime = isset($data['startTime']) ? floatval($data['startTime']) : 0;

    $_SESSION['elapsedTime'] = $elapsedTime;
    $_SESSION['running'] = $running;
    $_SESSION['startTime'] = $startTime;

    echo json_encode(['status' => 'success']);
}
?>
