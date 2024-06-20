<?php
    session_start();
    include('../database/connection2.php');

    // $hpercode = $_POST['hpercode'];
    // if($_POST['from'] === 'incoming'){
    //     $sql = "UPDATE incoming_referrals SET status='On-Process' WHERE hpercode= '". $hpercode ."' ";
    // }else{
    //     $sql = "UPDATE incoming_referrals SET status_interdept='On-Process' WHERE hpercode= '". $hpercode ."' ";
    // }
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();


    $hpercode = $_POST['hpercode'];

    // if hpercode has duplicates, get the last referral by date_time
    $sql = "SELECT date_time FROM incoming_referrals WHERE hpercode='". $hpercode ."' ORDER BY date_time DESC LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $latest_referral = $stmt->fetch(PDO::FETCH_ASSOC);

    if($_POST['from'] === 'incoming'){
        $sql = "UPDATE incoming_referrals SET status='On-Process' WHERE hpercode= '". $hpercode ."' AND date_time='". $latest_referral['date_time'] ."'";
    }else{
        $sql = "UPDATE incoming_referrals SET status_interdept='On-Process' WHERE hpercode= '". $hpercode ."' ";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
?>