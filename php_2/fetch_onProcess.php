<?php 
    session_start();
    include('../database/connection2.php');
    date_default_timezone_set('Asia/Manila');

    $temp = json_encode($_SESSION["process_timer"]);
    echo $temp;

    
    $hpercode = $_POST['hpercode'];
    if($hpercode !== "none"){
        // $reception_time = date('Y-m-d H:i:s');
        // $sql = "UPDATE incoming_referrals SET reception_time=:reception_time WHERE hpercode=:hpercode ";
        // $stmt = $pdo->prepare($sql);
        // $stmt->bindParam(':reception_time', $reception_time, PDO::PARAM_STR);
        // $stmt->bindParam(':hpercode', $hpercode, PDO::PARAM_STR);
        // $stmt->execute();
    }
    

    $_SESSION['running_timer'] = $_POST['timer'];
    $_SESSION['running_hpercode'] = $_POST['hpercode'];
    $_SESSION['running_index'] = $_POST['index'];
    echo $_SESSION['running_hpercode'];

    /*
    1. Save logout timer in interdept
    2. Set and calculate the total turn around time of the process.
    3. Enable the second request referral in SDN Module when the status of the current or first referral is already sent on the interdept.
    */
?>