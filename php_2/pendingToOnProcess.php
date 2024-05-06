<?php
    session_start();
    include('../database/connection2.php');

    $hpercode = $_POST['hpercode'];
    if($_POST['from'] === 'incoming'){
        $sql = "UPDATE incoming_referrals SET status='On-Process' WHERE hpercode= '". $hpercode ."' ";
    }else{
        $sql = "UPDATE incoming_referrals SET status_interdept='On-Process' WHERE hpercode= '". $hpercode ."' ";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
?>