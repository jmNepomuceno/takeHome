<?php
    session_start();
    include("../database/connection2.php");
    date_default_timezone_set('Asia/Manila');

    $timer = $_POST['timer'];
    $currentDateTime = date('Y-m-d H:i:s');
    // global_single_hpercode: BGHMC-0050 <br>timer: 00:00:04 <br>approve_details:  <br>case_category:  <br>action: Approve <br>
    // echo json_encode($_SESSION['approval_details_arr']);
    // echo '<pre>'; print_r($_SESSION['approval_details_arr']); echo '</pre>';

    

    if($_POST['type_approval'] === 'true'){
        $pat_class = $_POST['case_category'];
        $global_single_hpercode = filter_input(INPUT_POST, 'global_single_hpercode');
        $approve_details = filter_input(INPUT_POST, 'approve_details');

    }else{
        foreach ($_SESSION['approval_details_arr'] as $index => $element) {
            if ($element['hpercode'] == $_POST['global_single_hpercode']) {
                // Found the matching element
                $index;
                break; // Stop looping once found
            }
        }
        // C:\Users\ACER\Documents\dumps
    
        $_SESSION['approval_details_arr'][] = array(
            'hpercode' => $_POST['global_single_hpercode'],
            'category' => $_POST['case_category'] , 
            'approve_details' => $_POST['approve_details']
        );
    }

    if($_POST['action'] === "Approve"){  
        $sql = "UPDATE incoming_referrals SET status='Approved', pat_class=:pat_class WHERE hpercode=:hpercode AND refer_to = '" . $_SESSION["hospital_name"] . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':hpercode', $global_single_hpercode, PDO::PARAM_STR);
        $stmt->bindParam(':pat_class', $pat_class, PDO::PARAM_STR);
        $stmt->execute();
    }else{
        $sql = "UPDATE incoming_referrals SET status='Deferred', pat_class=:pat_class WHERE hpercode=:hpercode AND refer_to = '" . $_SESSION["hospital_name"] . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':hpercode', $global_single_hpercode, PDO::PARAM_STR);
        $stmt->bindParam(':pat_class', $pat_class, PDO::PARAM_STR);
        $stmt->execute();
    }

    $timer = filter_input(INPUT_POST, 'timer');
    $sql_b = "UPDATE incoming_referrals SET final_progressed_timer=:timer WHERE hpercode=:hpercode AND refer_to = '" . $_SESSION["hospital_name"] . "'";
    $stmt_b = $pdo->prepare($sql_b);
    $stmt_b->bindParam(':timer', $timer, PDO::PARAM_STR);
    $stmt_b->bindParam(':hpercode', $global_single_hpercode, PDO::PARAM_STR);
    $stmt_b->execute();

    // update the approved_details and set the time of approval on the database
    if($_POST['action'] === "Approve"){
        $sql = "UPDATE incoming_referrals SET approval_details=:approve_details, approved_time=:approved_time, progress_timer=NULL, refer_to_code=NULL WHERE hpercode=:hpercode AND refer_to = '" . $_SESSION["hospital_name"] . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':approve_details', $approve_details, PDO::PARAM_STR); // currentDateTime
        $stmt->bindParam(':approved_time', $currentDateTime, PDO::PARAM_STR);
        $stmt->bindParam(':hpercode', $global_single_hpercode, PDO::PARAM_STR);
        $stmt->execute();
    }else{
        $sql = "UPDATE incoming_referrals SET deferred_details=:approve_details, deferred_time=:approved_time WHERE hpercode=:hpercode AND refer_to = '" . $_SESSION["hospital_name"] . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':approve_details', $approve_details, PDO::PARAM_STR); // currentDateTime
        $stmt->bindParam(':approved_time', $currentDateTime, PDO::PARAM_STR);
        $stmt->bindParam(':hpercode', $global_single_hpercode, PDO::PARAM_STR);
        $stmt->execute();
    }

    // echo $global_single_hpercode . "---" . $_POST['action'] . "---";

    // update also the status of the patient on the hperson table
    $sql = "SELECT type FROM incoming_referrals WHERE hpercode=:hpercode AND refer_to = '" . $_SESSION["hospital_name"] . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':hpercode', $global_single_hpercode, PDO::PARAM_STR);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if($_POST['action'] === "Approve"){
        $sql = "UPDATE hperson SET status='Approved', type='". $data['type'] ."' WHERE hpercode=:hpercode ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':hpercode', $global_single_hpercode, PDO::PARAM_STR);
        $stmt->execute();
    }
    else{
        $sql = "UPDATE hperson SET status='Deferred', type='". $data['type'] ."' WHERE hpercode=:hpercode ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':hpercode', $global_single_hpercode, PDO::PARAM_STR);
        $stmt->execute();
    }

    $sql = "SELECT patlast, patfirst, patmiddle FROM incoming_referrals WHERE hpercode=:hpercode AND refer_to = '" . $_SESSION["hospital_name"] . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':hpercode', $global_single_hpercode, PDO::PARAM_STR);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // updating for history log
    $act_type = 'pat_refer';
    $history_stats = "";
    if($_POST['action'] === "Approve"){
        $history_stats = "Approved";
    }else{
        $history_stats = "Deferred";
    }
    $action = 'Status Patient: ' . $history_stats;
    $pat_name = $data[0]['patlast'] . ' ' . $data[0]['patfirst'] . ' ' . $data[0]['patmiddle'];
    $sql = "INSERT INTO history_log (hpercode, hospital_code, date, activity_type, action, pat_name, username) VALUES (?,?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(1, $global_single_hpercode, PDO::PARAM_STR);
    $stmt->bindParam(2, $_SESSION['hospital_code'], PDO::PARAM_INT);
    $stmt->bindParam(3, $currentDateTime, PDO::PARAM_STR);
    $stmt->bindParam(4, $act_type, PDO::PARAM_STR);
    $stmt->bindParam(5, $action, PDO::PARAM_STR);
    $stmt->bindParam(6, $pat_name, PDO::PARAM_STR);
    $stmt->bindParam(7, $_SESSION['user_name'], PDO::PARAM_STR);

    $stmt->execute();

    //get all the pending or on-process status on the database to populate the data table after the approval
    $sql = "SELECT * FROM incoming_referrals WHERE (status='Pending' OR status='On-Process') AND refer_to = '" . $_SESSION["hospital_name"] . "' ORDER BY date_time ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);  
    // echo '<pre>'; print_r($data); echo '</pre>';

    $index = 0;
    $previous = 0;
    $loop = 0;
    $i = 0;
    $prev_status_interdept = "";

    // Loop through the data and generate table rows
    foreach ($data as $row) {
        $type_color;
        if($row['type'] == 'OPD'){
            $type_color = '#d77707';
        }else if($row['type'] == 'OB'){
            $type_color = '#22c45e';
        }else if($row['type'] == 'ER'){
            $type_color = '#0368a1';
        }else if($row['type'] == 'PCR'){
            $type_color = '#cf3136';
        }else if($row['type'] == 'Toxicology'){
            $type_color = '#919122';
        }

        if($previous == 0){
            $index += 1;
        }else{
            if($row['reference_num'] == $previous){
                $index += 1;
            }else{
                $index = 1;
            }  
        }
        
        $style_tr = '';
        if($loop != 0 &&  $row['status'] === 'Pending'){
            $style_tr = 'opacity:0.5; pointer-events:none;';
        }

        $sql = "SELECT department FROM incoming_interdept WHERE hpercode=:hpercode";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":hpercode", $row['hpercode'], PDO::PARAM_STR);
        $stmt->execute();
        $dept = $stmt->fetch(PDO::FETCH_ASSOC);  

        if($row['status_interdept'] == 'Approved'){
            $row['status'] = "Approved - " . $dept['department'];
        }

        if($i > 0){
            if($prev_status_interdept == 'Approved'){
                $style_tr = 'opacity:1; pointer-events:auto;';
            }
        }
        

        // $waiting_time = "--:--:--";
        $date1 = new DateTime($row['date_time']);
        $waiting_time_bd = "";
        if($row['reception_time'] != null){
            $date2 = new DateTime($row['reception_time']);
            $waiting_time = $date1->diff($date2);
            $waiting_time_bd .= sprintf('%02d:%02d:%02d', $waiting_time->h, $waiting_time->i, $waiting_time->s);

        }else{
            $waiting_time_bd = "00:00:00";
        }

        if($row['reception_time'] == ""){
            $row['reception_time'] = "00:00:00";
        }

        echo '<tr class="tr-incoming" style="'. $style_tr .' border-bottom:1px solid #bfbfbf;">
                <td id="dt-refer-no"> ' . $row['reference_num'] . ' - '.$index.' </td>
                <td id="dt-patname">' . $row['patlast'] , ", " , $row['patfirst'] , " " , $row['patmiddle']  . '</td>
                <td id="dt-type" style="background:' . $type_color . ' ">' . $row['type'] . '</td>
                <td id="dt-phone-no">
                    <label> Referred: ' . $row['referred_by'] . '  </label>
                    <label> Landline: ' . $row['landline_no'] . ' </label>
                    <label> Mobile: ' . $row['mobile_no'] . ' </label>
                </td>
                <td id="dt-turnaround"> 
                    <i class="accordion-btn fa-solid fa-plus"></i>

                    <label class="referred-time-lbl"> Referred: ' . $row['date_time'] . ' </label>
                    <label class="queue-time-lbl"> Queue Time: ' . $waiting_time_bd . ' </label>
                    <label class="reception-time-lbl"> Reception: '. $row['reception_time'] .'</label>
                    
                    <div class="breakdown-div">
                        <label class="processed-time-lbl"> Processed: 00:00:00  </label>  
                        <label> Approval: 0000-00-00 00:00:00  </label>  
                        <label> Deferral: 0000-00-00 00:00:00  </label>  
                        <label> Cancelled: 0000-00-00 00:00:00  </label>  
                        <label> Arrived: 0000-00-00 00:00:00  </label>  
                        <label> Checked: 0000-00-00 00:00:00  </label>  
                        <label> Admitted: 0000-00-00 00:00:00  </label>  
                        <label> Discharged: 0000-00-00 00:00:00  </label>  
                        <label> Follow up: 0000-00-00 00:00:00  </label>  
                        <label> Ref. Back: 0000-00-00 00:00:00  </label>  
                    </div>
                </td>
                <td id="dt-stopwatch">
                    <div id="stopwatch-sub-div">
                        Processing: <span class="stopwatch">00:00:00</span>
                    </div>
                </td>
                
                <td id="dt-status">
                    <div> 
                        
                        <label class="pat-status-incoming">'. $row['status'] .'</label>
                        <i class="pencil-btn fa-solid fa-pencil"></i>
                        <input class="hpercode" type="hidden" name="hpercode" value= ' . $row['hpercode'] . '>

                    </div>
                </td>
            </tr>';


        
        $prev_status_interdept = $row['status_interdept'];
        $previous = $row['reference_num'];
        $loop += 1;
        $i += 1;
    }

    if(count($data) === 0){
        $_SESSION["running_timer"] = "";
    }

    if($_POST['type_approval'] === 'false'){
        foreach ($_SESSION['approval_details_arr'] as $index => $element) {
            if ($element['hpercode'] == $_POST['global_single_hpercode']) {
                // Found the matching element, delete it
                unset($_SESSION['approval_details_arr'][$index]);
                break; // Stop looping once found
            }
        }
    
        $_SESSION['approval_details_arr'] = array_values($_SESSION['approval_details_arr']);
    }
    
?>