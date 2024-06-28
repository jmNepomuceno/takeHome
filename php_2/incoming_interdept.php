<?php
    session_start();
    include("../database/connection2.php");
    date_default_timezone_set('Asia/Manila');

    // check first if the hpercode is already on the session array
    
    $_SESSION['approval_details_arr'][] = array(
        'hpercode' => $_POST['hpercode'],
        'category' => $_POST['case_category'] , 
        'approve_details' => $_POST['approve_details']
    );
    // insert the data into incoming_interdept
    $dept = $_POST['dept'];
    $currentDateTime = date('Y-m-d H:i:s');
    $hpercode = $_POST['hpercode'];
    $pause_time = $_POST['pause_time'];
    $pat_class = $_POST['case_category'];

    $sql = "INSERT INTO incoming_interdept (department, hpercode, recept_time, unRead, interdept_status) VALUES (?,?,?,1,'Pending')";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(1, $dept, PDO::PARAM_STR);
    $stmt->bindParam(2, $hpercode, PDO::PARAM_STR);
    $stmt->bindParam(3, $currentDateTime, PDO::PARAM_STR);
    $stmt->execute();

    //update the status of the patient in the table of incoming_referrals
    $sql = "UPDATE incoming_referrals SET status_interdept='Pending' , sent_interdept_time=:pause_time, pat_class=:pat_class WHERE hpercode=:hpercode";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':hpercode', $hpercode, PDO::PARAM_STR);
    $stmt->bindParam(':pause_time', $pause_time, PDO::PARAM_STR);
    $stmt->bindParam(':pat_class', $pat_class, PDO::PARAM_STR);
    $stmt->execute();

    $sql = "SELECT * FROM incoming_referrals WHERE (status='Pending' OR status='On-Process') AND refer_to='". $_SESSION["hospital_name"] ."' ORDER BY date_time ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response = json_encode($data);
    echo $response;
?>