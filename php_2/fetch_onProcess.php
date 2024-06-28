<?php 
    session_start();
    include('../database/connection2.php');
    date_default_timezone_set('Asia/Manila');
    

    $_SESSION['running_timer'] = $_POST['timer']; // elapsedTime
    $_SESSION['running_bool'] = $_POST['running_bool'];
    $_SESSION['running_startTime'] = $_POST['startTime'];

    $_SESSION['running_hpercode'] = $_POST['hpercode'];
    $_SESSION['running_index'] = $_POST['index'];

    echo $_SESSION['running_timer'];
?>