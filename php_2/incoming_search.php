<?php 
    session_start();
    include("../database/connection2.php");

    $from_where = $_POST['where'];

    // get the classification names
    $sql = "SELECT classifications FROM classifications";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data_classifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $color = ["#d77707" , "#22c45e" , "#0368a1" , "#cf3136" , "#919122" , "#999966" , "#6666ff"];
    $dynamic_classification = [];
    for($i = 0; $i < count($data_classifications); $i++){
        $dynamic_classification[$data_classifications[$i]['classifications']] = $color[$i];
    }

    if($from_where === "search"){
        $ref_no = $_POST['ref_no'];
        $last_name = $_POST['last_name'];
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'];
        $case_type = $_POST['case_type'];
        $agency = $_POST['agency'];
        $status = $_POST['status'];
        // $status = 'Pending';
        if(isset($_POST['hpercode_arr'])){
            $_SESSION['fifo_hpercode'] = $_POST['hpercode_arr'];   
        }

        $sql = "SELECT * FROM incoming_referrals WHERE ";

        $conditions = array();
        $others = false;

        if (!empty($ref_no)) {
            $conditions[] = "reference_num LIKE '%". $ref_no ."%'";
            $others = true;
        }

        if (!empty($last_name)) {
            $conditions[] = "patlast LIKE '%". $last_name ."%' ";
            $others = true;
        }

        if (!empty($first_name)) {
            $conditions[] = "patfirst LIKE '%". $first_name ."%' ";
            $others = true;
        }

        if (!empty($middle_name)) {
            $conditions[] = "patmiddle LIKE '%". $middle_name ."%' ";
            $others = true;
        }

        if (!empty($case_type)) {
            $conditions[] = "type = '" . $case_type . "'"; 
            $others = true;
        }

        if (!empty($agency)) {
            $conditions[] = "referred_by = '" . $agency . "'";
            $others = true;
        } 

        if(!empty($status) && $status!="All"){
            $conditions[] = "status = '" . $status . "'";
            $others = false;
        }

        

        if (count($conditions) > 0) {
            $sql .= implode(" AND ", $conditions);
        } else {
            $sql .= "1";  // Always true condition if no input values provided.
        }
        
        $sql .= " AND refer_to = '" . $_SESSION["hospital_name"] . "' ORDER BY date_time DESC";
        // echo $sql;

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // $jsonString = json_encode($data);
        // echo $jsonString;

        $index = 0;
        $previous = 0;
        $loop = 0;
        $accord_index = 0;
        // Loop through the data and generate table rows`

        // maybe work for interdepartamental
        // if(isset($_POST['hpercode_arr'])){
        //     for($i = 0; $i < count($_POST['hpercode_arr']); $i++){
        //         foreach ($data as $row) {
        //             // echo $_POST['hpercode_arr'][$i] . ',' . $row['hpercode'] . "---";
        //             if($_POST['hpercode_arr'][$i] != $row['hpercode']){
        //                 // echo $_POST['hpercode_arr'][$i];
        //                 $type_color = $dynamic_classification[$row['type']];
            
        //                 $style_tr = 'opacity:1; pointer-events:auto;';
            
        //                 // $waiting_time = "--:--:--";
        //                 $date1 = new DateTime($row['date_time']);
        //                 $waiting_time_bd = "";
        //                 if($row['reception_time'] != null){
        //                     $date2 = new DateTime($row['reception_time']);
        //                     $waiting_time = $date1->diff($date2);
        //                     $waiting_time_bd .= sprintf('%02d:%02d:%02d', $waiting_time->h, $waiting_time->i, $waiting_time->s);
            
        //                 }else{
        //                     $waiting_time_bd = "00:00:00";
        //                 }
            
        //                 if($row['reception_time'] == ""){
        //                     $row['reception_time'] = "00:00:00";
        //                 }
            
        //                 $stopwatch = "00:00:00";
        //                 if($row['sent_interdept_time'] == ""){
        //                     if($_SESSION['running_timer'] != "" && $row['status'] == 'On-Process'){
        //                         $stopwatch  = $_SESSION['running_timer'];
        //                     }
        //                 }else{
        //                     $stopwatch  = $row['sent_interdept_time'];
        //                 }
                        
        //                 if($row['status_interdept'] != "" && $row['status_interdept'] != null){
        //                     $sql = "SELECT department FROM incoming_interdept WHERE hpercode='". $row['hpercode'] ."'";
        //                     $stmt = $pdo->prepare($sql);
        //                     $stmt->execute();
        //                     $temp_data = $stmt->fetch(PDO::FETCH_ASSOC);
        //                     // echo '<pre>'; print_r($temp_data); echo '</pre>';
        //                     $row['status'] = $row['status_interdept'] . " - " . strtoupper($temp_data['department']);
        //                 }
                    
        //                 if($row['approved_time'] == ""){
        //                     $row['approved_time'] = "0000-00-00 00:00:00";
        //                 }

        //                 $sql = "SELECT final_progress_time FROM incoming_interdept WHERE hpercode='".$row['hpercode']."'";
        //                 $stmt = $pdo->prepare($sql);
        //                 $stmt->execute();
        //                 $interdept_time = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //                 $total_time = "00:00:00";
        //                     if($interdept_time){
        //                         if($interdept_time[0]['final_progress_time'] != "" && $row['sent_interdept_time'] != ""){
        //                             list($hours1, $minutes1, $seconds1) = array_map('intval', explode(':', $interdept_time[0]['final_progress_time']));
        //                             list($hours2, $minutes2, $seconds2) = array_map('intval', explode(':', $row['sent_interdept_time']));

        //                             // Create DateTime objects in UTC with the provided hours, minutes, and seconds
        //                             $date1 = new DateTime('1970-01-01 ' . $hours1 . ':' . $minutes1 . ':' . $seconds1, new DateTimeZone('UTC'));
        //                             $date2 = new DateTime('1970-01-01 ' . $hours2 . ':' . $minutes2 . ':' . $seconds2, new DateTimeZone('UTC'));

        //                             // Calculate the total milliseconds
        //                             $totalMilliseconds = $date1->getTimestamp() * 1000 + $date2->getTimestamp() * 1000;

        //                             // Create a new DateTime object in UTC with the total milliseconds
        //                             $newDate = new DateTime('@' . ($totalMilliseconds / 1000), new DateTimeZone('UTC'));

        //                             // Format the result in UTC time "HH:mm:ss"
        //                             $total_time = $newDate->format('H:i:s');
        //                         }
        //                     }else{
        //                         $interdept_time[0]['final_progress_time'] = "00:00:00";
        //                         $row['sent_interdept_time'] = "00:00:00";
        //                     }

        //                 if($interdept_time[0]['final_progress_time'] == ""){
        //                     $interdept_time[0]['final_progress_time'] = "00:00:00";
        //                 }

        //                 // immediate approval
                        
        //                 // echo $row['hpercode'] . '---';
        //                 // echo '<tr class="tr-incoming" style="'. $style_tr .'">
        //                 //         <td id="dt-refer-no"> ' . $row['reference_num'] . ' - '.$index.' </td>
        //                 //         <td id="dt-patname">' . $row['patlast'] , ", " , $row['patfirst'] , " " , $row['patmiddle']  . '</td>
        //                 //         <td id="dt-type" style="background:' . $type_color . ' ">' . $row['type'] . '</td>
        //                 //         <td id="dt-phone-no">
        //                 //             <label> Referred: ' . $row['referred_by'] . '  </label>
        //                 //             <label> Landline: ' . $row['landline_no'] . ' </label>
        //                 //             <label> Mobile: ' . $row['mobile_no'] . ' </label>
        //                 //         </td>
        //                 //         <td id="dt-turnaround"> 
        //                 //             <i class="accordion-btn fa-solid fa-plus"></i>
                        
        //                 //             <label class="referred-time-lbl"> Referred: ' . $row['date_time'] . ' </label>
        //                 //             <label class="reception-time-lbl"> Reception: '. $row['reception_time'] .'</label>
        //                 //             <label class="sdn-proc-time-lbl"> SDN Processed: '. $row['sent_interdept_time'] .'</label>
                                    
        //                 //             <div class="breakdown-div">
        //                 //                 <label class="interdept-proc-time-lbl"> Interdept Processed: '. $interdept_time[0]['final_progress_time'].'</label>
        //                 //                 <label class="processed-time-lbl"> Total Processed: '.$total_time.'  </label>  
        //                 //                 <label> Approval: '.$row['approved_time'] .'  </label>  
        //                 //                 <label> Deferral: 0000-00-00 00:00:00  </label>  
        //                 //                 <label> Cancelled: 0000-00-00 00:00:00  </label>  
        //                 //                 <label> Arrived: 0000-00-00 00:00:00  </label>  
        //                 //                 <label> Checked: 0000-00-00 00:00:00  </label>  
        //                 //                 <label> Admitted: 0000-00-00 00:00:00  </label>  
        //                 //                 <label> Discharged: 0000-00-00 00:00:00  </label>  
        //                 //                 <label> Follow up: 0000-00-00 00:00:00  </label>  
        //                 //                 <label> Ref. Back: 0000-00-00 00:00:00  </label>  
        //                 //             </div>
        //                 //         </td>
        //                 //         <td id="dt-stopwatch">
        //                 //             <div id="stopwatch-sub-div">
        //                 //                 Processing: <span class="stopwatch">'.$stopwatch.'</span>
        //                 //             </div>
        //                 //         </td>
                                
        //                 //         <td id="dt-status">
        //                 //             <div> 
        //                 //                 <label class="pat-status-incoming">' . $row['status'] . '</label>
        //                 //                 <i class="pencil-btn fa-solid fa-pencil"></i>
        //                 //                 <input class="hpercode" type="hidden" name="hpercode" value= ' . $row['hpercode'] . '>
                        
        //                 //             </div>
        //                 //         </td>
        //                 //     </tr>';

        //             }            
        //         }
        //     }
        // }

        $sql = "SELECT * FROM incoming_referrals WHERE status='On-Process' AND refer_to = '" . $_SESSION["hospital_name"] . "' ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $on_process = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // for immediate approval
        foreach ($on_process as $row) {
            $type_color = $dynamic_classification[$row['type']];

            if($previous == 0){
                $index += 1;
            }else{
                if($row['reference_num'] == $previous){
                    $index += 1;
                }else{
                    $index = 1;
                }  
            }
            
            // $style_tr = 'background:#33444d; color:white;';
            $style_tr = '';
            if($loop != 0 &&  $row['status'] === 'Pending'){
                $style_tr = 'opacity:0.5; pointer-events:none;';
            }

            // $waiting_time = "--:--:--";
            $date1 = new DateTime($row['date_time']);
            $waiting_time_bd = "";
            if($row['reception_time'] != null){
                $date2 = new DateTime($row['reception_time']);
                $waiting_time = $date1->diff($date2);

                // if ($waiting_time->days > 0) {
                //     $differenceString .= $waiting_time->days . ' days ';
                // }

                $waiting_time_bd .= sprintf('%02d:%02d:%02d', $waiting_time->h, $waiting_time->i, $waiting_time->s);

            }else{
                $waiting_time_bd = "00:00:00";
            }

            if($row['reception_time'] == ""){
                $row['reception_time'] = "00:00:00";
            }

            // if($row['status_interdept'] != "" && $row['status_interdept'] != null){
            //     $sql = "SELECT department FROM incoming_interdept WHERE hpercode='". $row['hpercode'] ."'";
            //     $stmt = $pdo->prepare($sql);
            //     $stmt->execute();
            //     $data = $stmt->fetch(PDO::FETCH_ASSOC);

            //     $row['status'] = $row['status_interdept'] . " - " . strtoupper($data['department']);
            // }
            
            // processed time = progress time ng admin + progress time ng dept
            // maiiwan yung timer na naka print, once na send na sa interdept
            
            $sql = "SELECT final_progress_time FROM incoming_interdept WHERE hpercode=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$row['hpercode']]);
            $interdept_time = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $total_time = "00:00:00";
            if($interdept_time){
                if($interdept_time[0]['final_progress_time'] != "" && $row['sent_interdept_time'] != ""){
                    list($hours1, $minutes1, $seconds1) = array_map('intval', explode(':', $interdept_time[0]['final_progress_time']));
                    list($hours2, $minutes2, $seconds2) = array_map('intval', explode(':', $row['sent_interdept_time']));

                    // Create DateTime objects in UTC with the provided hours, minutes, and seconds
                    $date1 = new DateTime('1970-01-01 ' . $hours1 . ':' . $minutes1 . ':' . $seconds1, new DateTimeZone('UTC'));
                    $date2 = new DateTime('1970-01-01 ' . $hours2 . ':' . $minutes2 . ':' . $seconds2, new DateTimeZone('UTC'));

                    // Calculate the total milliseconds
                    $totalMilliseconds = $date1->getTimestamp() * 1000 + $date2->getTimestamp() * 1000;

                    // Create a new DateTime object in UTC with the total milliseconds
                    $newDate = new DateTime('@' . ($totalMilliseconds / 1000), new DateTimeZone('UTC'));

                    // Format the result in UTC time "HH:mm:ss"
                    $total_time = $newDate->format('H:i:s');
                }
            }else{
                $interdept_time[0]['final_progress_time'] = "00:00:00";
                $row['sent_interdept_time'] = "00:00:00";

                // calculate for immediate approval
                if($row['final_progressed_timer'] != NULL){
                    $total_time = $row['final_progressed_timer'];
                }
            }


            if($row['approved_time'] == ""){
                $row['approved_time'] = "0000-00-00 00:00:00";
            }

            if($interdept_time[0]['final_progress_time'] == ""){
                $interdept_time[0]['final_progress_time'] = "00:00:00";
            }

            
            // if($row['sent_interdept_time'] == NULL){
            //     $row['sent_interdept_time'] = "00:00:00";
            //     // $sdn_processed_val = $row['final_progressed_timer'];
            // }

            $stopwatch = "00:00:00";
            if($row['sent_interdept_time'] == "00:00:00"){
                if($_SESSION['running_timer'] != "" && $row['status'] == 'On-Process'){
                    $stopwatch  = $_SESSION['running_timer'];
                }
            }else{
                $stopwatch  = $row['sent_interdept_time'];
            }

            if($row['sent_interdept_time'] == "00:00:00"){
                $sdn_processed_val = $row['final_progressed_timer'];
            }

                echo '<tr class="tr-incoming" style="'. $style_tr .'">
                        <td id="dt-refer-no"> ' . $row['reference_num'] . ' - '.$index.' </td>
                        <td id="dt-patname">' . $row['patlast'] , ", " , $row['patfirst'] , " " , $row['patmiddle']  . '</td>
                        <td id="dt-type" style="background:' . $type_color . ' ">' . $row['type'] . '</td>
                        <td id="dt-phone-no">
                            <label> Referred: ' . $row['referred_by'] . '  </label>
                            <label> Landline: ' . $row['landline_no'] . ' </label>
                            <label> Mobile: ' . $row['mobile_no'] . ' </label>
                        </td>
                        <td id="dt-turnaround"> 
                            <i id="accordion-id- '.$accord_index.'" class="accordion-btn fa-solid fa-plus"></i>

                            <label class="referred-time-lbl"> Referred: ' . $row['date_time'] . ' </label>
                            <label class="reception-time-lbl"> Reception: '. $row['reception_time'] .'</label>
                            <label class="sdn-proc-time-lbl"> SDN Processed: '. $sdn_processed_val .'</label>
                            
                            <div class="breakdown-div">
                                <label class="interdept-proc-time-lbl"> Interdept Processed: '. $interdept_time[0]['final_progress_time'].'</label>
                                <label class="processed-time-lbl"> Total Processed: '.$total_time.'  </label>  
                                <label> Approval: '.$row['approved_time'] .'  </label>  
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
                                Processing: <span class="stopwatch">'.$stopwatch.'</span>
                            </div>
                        </td>
                        
                        <td id="dt-status">
                            <div> 
                                
                                <label class="pat-status-incoming">' . $row['status'] . '</label>
                                <i class="pencil-btn fa-solid fa-pencil"></i>
                                <input class="hpercode" type="hidden" name="hpercode" value= ' . $row['hpercode'] . '>

                            </div>
                        </td>
                    </tr>';

            
            $previous = $row['reference_num'];
            $loop += 1;
            $accord_index += 1;
        }
        
        foreach ($data as $row) {
            if(isset($_POST['hpercode_arr'])){
                if(in_array($row['hpercode'], $_SESSION['fifo_hpercode']) && $row['status'] != 'Approved'){
                    continue;
                }
            }

            $type_color = $dynamic_classification[$row['type']];

            if($previous == 0){
                $index += 1;
            }else{
                if($row['reference_num'] == $previous){
                    $index += 1;
                }else{
                    $index = 1;
                }  
            }
            
            // $style_tr = 'background:#33444d; color:white;';
            $style_tr = '';
            if($loop != 0 &&  $row['status'] === 'Pending'){
                $style_tr = 'opacity:0.5; pointer-events:none;';
            }

            // $waiting_time = "--:--:--";
            $date1 = new DateTime($row['date_time']);
            $waiting_time_bd = "";
            if($row['reception_time'] != null){
                $date2 = new DateTime($row['reception_time']);
                $waiting_time = $date1->diff($date2);

                // if ($waiting_time->days > 0) {
                //     $differenceString .= $waiting_time->days . ' days ';
                // }

                $waiting_time_bd .= sprintf('%02d:%02d:%02d', $waiting_time->h, $waiting_time->i, $waiting_time->s);

            }else{
                $waiting_time_bd = "00:00:00";
            }

            if($row['reception_time'] == ""){
                $row['reception_time'] = "00:00:00";
            }

            // if($row['status_interdept'] != "" && $row['status_interdept'] != null){
            //     $sql = "SELECT department FROM incoming_interdept WHERE hpercode='". $row['hpercode'] ."'";
            //     $stmt = $pdo->prepare($sql);
            //     $stmt->execute();
            //     $data = $stmt->fetch(PDO::FETCH_ASSOC);

            //     $row['status'] = $row['status_interdept'] . " - " . strtoupper($data['department']);
            // }
            
            // processed time = progress time ng admin + progress time ng dept
            // maiiwan yung timer na naka print, once na send na sa interdept
            
            $sql = "SELECT final_progress_time FROM incoming_interdept WHERE hpercode=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$row['hpercode']]);
            $interdept_time = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $total_time = "00:00:00";
            if($interdept_time){
                if($interdept_time[0]['final_progress_time'] != "" && $row['sent_interdept_time'] != ""){
                    list($hours1, $minutes1, $seconds1) = array_map('intval', explode(':', $interdept_time[0]['final_progress_time']));
                    list($hours2, $minutes2, $seconds2) = array_map('intval', explode(':', $row['sent_interdept_time']));

                    // Create DateTime objects in UTC with the provided hours, minutes, and seconds
                    $date1 = new DateTime('1970-01-01 ' . $hours1 . ':' . $minutes1 . ':' . $seconds1, new DateTimeZone('UTC'));
                    $date2 = new DateTime('1970-01-01 ' . $hours2 . ':' . $minutes2 . ':' . $seconds2, new DateTimeZone('UTC'));

                    // Calculate the total milliseconds
                    $totalMilliseconds = $date1->getTimestamp() * 1000 + $date2->getTimestamp() * 1000;

                    // Create a new DateTime object in UTC with the total milliseconds
                    $newDate = new DateTime('@' . ($totalMilliseconds / 1000), new DateTimeZone('UTC'));

                    // Format the result in UTC time "HH:mm:ss"
                    $total_time = $newDate->format('H:i:s');
                }
            }else{
                $interdept_time[0]['final_progress_time'] = "00:00:00";
                $row['sent_interdept_time'] = "00:00:00";

                // calculate for immediate approval
                if($row['final_progressed_timer'] != NULL){
                    $total_time = $row['final_progressed_timer'];
                }
            }


            if($row['approved_time'] == ""){
                $row['approved_time'] = "0000-00-00 00:00:00";
            }

            if($interdept_time[0]['final_progress_time'] == ""){
                $interdept_time[0]['final_progress_time'] = "00:00:00";
            }

            
            // if($row['sent_interdept_time'] == NULL){
            //     $row['sent_interdept_time'] = "00:00:00";
            //     // $sdn_processed_val = $row['final_progressed_timer'];
            // }

            $stopwatch = "00:00:00";
            if($row['sent_interdept_time'] == "00:00:00"){
                if($_SESSION['running_timer'] != "" && $row['status'] == 'On-Process'){
                    $stopwatch  = $_SESSION['running_timer'];
                }
            }else{
                $stopwatch  = $row['sent_interdept_time'];
            }

            if($row['sent_interdept_time'] == "00:00:00"){
                $sdn_processed_val = $row['final_progressed_timer'];
            }

                echo '<tr class="tr-incoming" style="'. $style_tr .'">
                        <td id="dt-refer-no"> ' . $row['reference_num'] . ' - '.$index.' </td>
                        <td id="dt-patname">' . $row['patlast'] , ", " , $row['patfirst'] , " " , $row['patmiddle']  . '</td>
                        <td id="dt-type" style="background:' . $type_color . ' ">' . $row['type'] . '</td>
                        <td id="dt-phone-no">
                            <label> Referred: ' . $row['referred_by'] . '  </label>
                            <label> Landline: ' . $row['landline_no'] . ' </label>
                            <label> Mobile: ' . $row['mobile_no'] . ' </label>
                        </td>
                        <td id="dt-turnaround"> 
                            <i id="accordion-id- '.$accord_index.'" class="accordion-btn fa-solid fa-plus"></i>

                            <label class="referred-time-lbl"> Referred: ' . $row['date_time'] . ' </label>
                            <label class="reception-time-lbl"> Reception: '. $row['reception_time'] .'</label>
                            <label class="sdn-proc-time-lbl"> SDN Processed: '. $sdn_processed_val .'</label>
                            
                            <div class="breakdown-div">
                                <label class="interdept-proc-time-lbl"> Interdept Processed: '. $interdept_time[0]['final_progress_time'].'</label>
                                <label class="processed-time-lbl"> Total Processed: '.$total_time.'  </label>  
                                <label> Approval: '.$row['approved_time'] .'  </label>  
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
                                Processing: <span class="stopwatch">'.$stopwatch.'</span>
                            </div>
                        </td>
                        
                        <td id="dt-status">
                            <div> 
                                
                                <label class="pat-status-incoming">' . $row['status'] . '</label>
                                <i class="pencil-btn fa-solid fa-pencil"></i>
                                <input class="hpercode" type="hidden" name="hpercode" value= ' . $row['hpercode'] . '>

                            </div>
                        </td>
                    </tr>';

            
            $previous = $row['reference_num'];
            $loop += 1;
            $accord_index += 1;
        }

    }
    else{
        try{
            $sql = "SELECT * FROM incoming_referrals WHERE (status='Pending' OR status='On-Process') AND refer_to='". $_SESSION["hospital_name"] ."' ORDER BY date_time ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // echo count($data);
            $jsonData = json_encode($data);

            $index = 0;
            $previous = 0;
            $loop = 0;
            $index_color = 0;
            // Loop through the data and generate table rows
            foreach ($data as $row) {
                
                $type_color = $dynamic_classification[$row['type']];

                if($previous == 0){
                    $index += 1;
                }else{
                    if($row['reference_num'] == $previous){
                        $index += 1;
                    }else{
                        $index = 1;
                    }  
                }
                
                // $style_tr = 'background:#33444d; color:white;';
                $style_tr = '';
                if($loop != 0 &&  $row['status'] === 'Pending'){
                    $style_tr = 'opacity:0.5; pointer-events:none;';
                }

                // $waiting_time = "--:--:--";
                $date1 = new DateTime($row['date_time']);
                $waiting_time_bd = "";
                if($row['reception_time'] != null){
                    $date2 = new DateTime($row['reception_time']);
                    $waiting_time = $date1->diff($date2);

                    // if ($waiting_time->days > 0) {
                    //     $differenceString .= $waiting_time->days . ' days ';
                    // }

                    $waiting_time_bd .= sprintf('%02d:%02d:%02d', $waiting_time->h, $waiting_time->i, $waiting_time->s);

                }else{
                    $waiting_time_bd = "00:00:00";
                }

                if($row['reception_time'] == ""){
                    $row['reception_time'] = "00:00:00";
                }

                if($row['status_interdept'] != "" && $row['status_interdept'] != null){
                    $sql = "SELECT department FROM incoming_interdept WHERE hpercode='". $row['hpercode'] ."'";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);

                    $row['status'] = $row['status_interdept'] . " - " . strtoupper($data['department']);
                }
                // processed time = progress time ng admin + progress time ng dept
                // maiiwan yung timer na naka print, once na send na sa interdept
                
                $sql = "SELECT final_progress_time FROM incoming_interdept WHERE hpercode=:hpercode";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':hpercode', $row['hpercode'], PDO::PARAM_STR);
                $stmt->execute();
                $interdept_time = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $total_time = "00:00:00";
                if($interdept_time){
                    if($interdept_time[0]['final_progress_time'] != "" && $row['sent_interdept_time'] != ""){
                        list($hours1, $minutes1, $seconds1) = array_map('intval', explode(':', $interdept_time[0]['final_progress_time']));
                        list($hours2, $minutes2, $seconds2) = array_map('intval', explode(':', $row['sent_interdept_time']));

                        // Create DateTime objects in UTC with the provided hours, minutes, and seconds
                        $date1 = new DateTime('1970-01-01 ' . $hours1 . ':' . $minutes1 . ':' . $seconds1, new DateTimeZone('UTC'));
                        $date2 = new DateTime('1970-01-01 ' . $hours2 . ':' . $minutes2 . ':' . $seconds2, new DateTimeZone('UTC'));

                        // Calculate the total milliseconds
                        $totalMilliseconds = $date1->getTimestamp() * 1000 + $date2->getTimestamp() * 1000;

                        // Create a new DateTime object in UTC with the total milliseconds
                        $newDate = new DateTime('@' . ($totalMilliseconds / 1000), new DateTimeZone('UTC'));

                        // Format the result in UTC time "HH:mm:ss"
                        $total_time = $newDate->format('H:i:s');
                    }
                }else{
                    $interdept_time[0]['final_progress_time'] = "00:00:00";
                    $row['sent_interdept_time'] = "00:00:00";
                }


                if($row['approved_time'] == ""){
                    $row['approved_time'] = "0000-00-00 00:00:00";
                }

                if($interdept_time[0]['final_progress_time'] == ""){
                    $interdept_time[0]['final_progress_time'] = "00:00:00";
                }

                if($row['sent_interdept_time'] == ""){
                    $row['sent_interdept_time'] = "00:00:00";
                }

                $stopwatch = "00:00:00";
                if($row['sent_interdept_time'] == "00:00:00"){
                    if($_SESSION['running_timer'] != "" && $row['status'] == 'On-Process'){
                        $stopwatch  = $_SESSION['running_timer'];
                    }
                }else{
                    $stopwatch  = $row['sent_interdept_time'];
                }

                // for sensitive case
                $pat_full_name = "";
                if($row['sensitive_case'] === 'true'){
                    $pat_full_name = "<button id='sensitive-case-btn'> Sensitive Case </button>";
                }else{
                    $pat_full_name = $row['patlast'] . ", " . $row['patfirst'] . " " . $row['patmiddle'];
                }

                echo '
                    <tr class="tr-incoming" style="'. $style_tr .'">
                        <td id="dt-refer-no"> ' . $row['reference_num'] . ' - '.$index.' </td>
                        <td id="dt-patname">' . $pat_full_name . '</td>
                        <td id="dt-type" style="background:' . $type_color . ' ">' . $row['type'] . '</td>
                        <td id="dt-phone-no">
                            <label> Referred: ' . $row['referred_by'] . '  </label>
                            <label> Landline: ' . $row['landline_no'] . ' </label>
                            <label> Mobile: ' . $row['mobile_no'] . ' </label>
                        </td>
                        <td id="dt-turnaround"> 
                            <i class="accordion-btn fa-solid fa-plus"></i>

                            <label class="referred-time-lbl"> Referred: ' . $row['date_time'] . ' </label>
                            <label class="reception-time-lbl"> Reception: '. $row['reception_time'] .'</label>
                            <label class="sdn-proc-time-lbl"> SDN Processed: '. $row['sent_interdept_time'] .'</label>
                            
                            <div class="breakdown-div">
                                <label class="interdept-proc-time-lbl"> Interdept Processed: '. $interdept_time[0]['final_progress_time'].'</label>
                                <label class="processed-time-lbl"> Total Processed: '.$total_time.'  </label>  
                                <label> Approval: '.$row['approved_time'] .'  </label>  
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
                                Processing: <span class="stopwatch">'.$stopwatch.'</span>
                            </div>
                        </td>
                        
                        <td id="dt-status">
                            <div> 
                                <label class="pat-status-incoming">' . $row['status'] . '</label>
                                <i class="pencil-btn fa-solid fa-pencil"></i>
                                <input class="hpercode" type="hidden" name="hpercode" value= ' . $row['hpercode'] . '>

                            </div>
                        </td>
                    </tr>';

                $previous = $row['reference_num'];
                $loop += 1;
                $index_color += 1;
            }

            // Close the database connection
            $pdo = null;
        }
        catch(PDOException $e){
            echo "asdf";
        }
    }


?>