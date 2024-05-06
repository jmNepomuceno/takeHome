<?php
    session_start();
    include("../database/connection2.php");
    
    $hpercode = $_POST['hpercode'];
    $myArray = [];
    $seen_data;
    //update the status of the patient in the table of incoming_referrals
    $sql = "SELECT status_interdept, reception_time FROM incoming_referrals WHERE hpercode=:hpercode";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':hpercode', $hpercode, PDO::PARAM_STR);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT referring_seenTime, referring_seenBy FROM incoming_interdept WHERE hpercode=:hpercode";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':hpercode', $hpercode, PDO::PARAM_STR);
    $stmt->execute();
    $seen_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
    // referring_seenTime, referring_seenBy

    if($data['status_interdept'] != ""){
        $myArray['status_interdept'] = true;

        $sql = "SELECT referring_seenTime, referring_seenBy FROM incoming_interdept WHERE hpercode=:hpercode";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':hpercode', $hpercode, PDO::PARAM_STR);
        $stmt->execute();
        $seen_data = $stmt->fetch(PDO::FETCH_ASSOC);

        $myArray = array_merge($myArray, $seen_data);

    }else{
        $myArray['status_interdept'] = false;
    }

    if($data['reception_time'] != ""){
        $myArray['reception_time'] = true;
    }else{
        $myArray['reception_time'] = false;
        
    }

    $response = json_encode($myArray);
    echo $response;
?>