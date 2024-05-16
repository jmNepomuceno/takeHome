<?php 
    session_start();
    include("../database/connection2.php");

    $start_date = $_POST['from_date'];
    $end_date = $_POST['to_date'];
    $end_date_adjusted = date('Y-m-d', strtotime($end_date . ' +1 day'));

    
    $averageDuration_reception = "00:00:00";
    $averageDuration_approval  = "00:00:00";
    $averageDuration_total  = "00:00:00";
    $fastest_response_final  = "00:00:00";
    $slowest_response_final  = "00:00:00";
    
    $currentDateTime = date('Y-m-d');
    $formatted_average_sdn_average = "00:00:00";
    $averageTime_interdept = "00:00:00";

    // $formattedFromDate = $from_date->format('Y-m-d') . '%';
    // $formattedToDate = $to_date->format('Y-m-d') . '%';

    // $sql = "";
    // if($_POST['where'] === 'incoming'){
    //     $sql = "SELECT  reception_time, date_time, final_progressed_timer, hpercode FROM incoming_referrals WHERE (status!='Pending' OR status!='On-Process' OR status!='Deferred' OR status!='Deferred' OR status!='Cancelled' OR status!='Discharged' OR status!='Referred Back') AND approved_time BETWEEN :start_date AND :end_date AND refer_to = '" . $_SESSION["hospital_name"] . "'";   
    // }else{
    //     $sql = "SELECT  reception_time, date_time, final_progressed_timer, hpercode FROM incoming_referrals WHERE (status!='Pending' OR status!='On-Process' OR status!='Deferred' OR status!='Deferred' OR status!='Cancelled' OR status!='Discharged' OR status!='Referred Back') AND approved_time BETWEEN :start_date AND :end_date AND referred_by = '" . $_SESSION["hospital_name"] . "'";
    // }

    // $sql = "SELECT  reception_time, date_time, final_progressed_timer, hpercode FROM incoming_referrals WHERE (status!='Pending' OR status!='On-Process' OR status!='Deferred' OR status!='Deferred' OR status!='Cancelled' OR status!='Discharged' OR status!='Referred Back') AND approved_time BETWEEN :start_date AND :end_date AND refer_to = '" . $_SESSION["hospital_name"] . "'";

    $sql = "SELECT  hpercode, reception_time, date_time, final_progressed_timer, sent_interdept_time FROM incoming_referrals WHERE refer_to = 'Bataan General Hospital and Medical Center' AND date_time >= :start_date AND date_time <= :end_date_adjusted";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['start_date' => $start_date , 'end_date_adjusted' => $end_date_adjusted]);
    $dataRecep = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT hpercode, final_progress_time FROM incoming_interdept WHERE final_progress_date >= :start_date AND final_progress_date < :end_date_adjusted";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['start_date' => $start_date , 'end_date_adjusted' => $start_date]);
    $dataRecep_interdept = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // $finalJsonString = json_encode($dataRecep);
    // echo $finalJsonString;

    ///////////////////////////////////////////////////////////

    $recep_arr = array(); 
    for($i = 0; $i < count($dataRecep); $i++){
        // Given dates
        $date1 = new DateTime($dataRecep[$i]['reception_time']);
        $date2 = new DateTime($dataRecep[$i]['date_time']);

        // Calculate the difference
        $interval = $date1->diff($date2);

        // Format the difference as hh:mm:ss
        $formattedDifference = sprintf(
            '%02d:%02d:%02d',
            $interval->h,
            $interval->i,
            $interval->s
        );

        array_push($recep_arr, $formattedDifference);
    }

    //  // INTERDEPT REFERRAL AVERAGE
    //  $totalSeconds_interdept = 0;
    //  foreach ($dataRecep_interdept as $item) {
    //      // Extract hours, minutes, and seconds from final_progress_time
    //      list($hours, $minutes, $seconds) = explode(':', $item['final_progress_time']);
    //      // Convert hours and minutes to seconds and add to total
    //      $totalSeconds_interdept += $hours * 3600 + $minutes * 60 + $seconds;
    //  }

    //  // Calculate the average in seconds
    //  $averageSeconds_interdept = (int) ($totalSeconds_interdept / count($dataRecep_interdept));

    //  // Optionally, convert the average back to hh:mm:ss format
    //  $averageTime_interdept = gmdate("H:i:s", $averageSeconds_interdept);

    // //  echo "Average final_progress_time: $averageTime_interdept";

     // SDN REFERRAL AVERAGE
     $sum_sdn_average = 0;

     foreach ($dataRecep as $item) {
        if($item['sent_interdept_time'] === NULL || $item['sent_interdept_time'] === ""){
            $sum_sdn_average += strtotime($item['final_progressed_timer']) - strtotime('00:00:00');
        }else{
            $sum_sdn_average += strtotime($item['sent_interdept_time']) - strtotime('00:00:00');
        }
     }
     $count_sdn_average = count($dataRecep);
     $average_sdn_average = $sum_sdn_average / $count_sdn_average;
     $average_seconds_sdn_average = (int)$average_sdn_average;
     $formatted_average_sdn_average = gmdate("H:i:s", $average_seconds_sdn_average);
    //  echo "Average sent_interdept_time: $formatted_average_sdn_average";

    // print_r($recep_arr);
     // echo '<pre>'; print_r($recep_arr); echo '</pre>';

     $fastest_recep_secs = array();
     // Function to convert duration to seconds
     function durationToSeconds($duration) {
         list($hours, $minutes, $seconds) = explode(':', $duration);
         return $hours * 3600 + $minutes * 60 + $seconds;
     }

     // Function to convert seconds to duration
     function secondsToDuration($seconds) {
         $hours = floor($seconds / 3600);
         $minutes = floor(($seconds % 3600) / 60);
         $seconds = $seconds % 60;

         return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
     }

     // for average reception time
     $averageSeconds_reception = 0;
     for($i = 0; $i < count($recep_arr); $i++){
         $averageSeconds_reception += durationToSeconds($recep_arr[$i]);
     }

     // for approval time
     $averageSeconds_approval = 0;
     for($i = 0; $i < count($dataRecep); $i++){
         $averageSeconds_approval += durationToSeconds($dataRecep[$i]['final_progressed_timer']);
     }

     // for total time
     $averageSeconds_total = 0;
     for($i = 0; $i < count($dataRecep); $i++){
         $averageSeconds_total += (durationToSeconds($dataRecep[$i]['final_progressed_timer']) + durationToSeconds($recep_arr[$i]));
     }

     // echo $averageSeconds_total;

     for($i = 0; $i < count($recep_arr); $i++){
         durationToSeconds($recep_arr[$i]);
         array_push($fastest_recep_secs, durationToSeconds($dataRecep[$i]['final_progressed_timer']));
     }

    
     $averageSeconds_reception = (int) round($averageSeconds_reception / count($dataRecep));   
     $averageDuration_reception = secondsToDuration($averageSeconds_reception);  

     $averageSeconds_approval = (int) round($averageSeconds_approval / count($dataRecep));
     $averageDuration_approval = secondsToDuration($averageSeconds_approval);

     $averageSeconds_total = (int) round($averageSeconds_total / count($dataRecep));
     $averageDuration_total = secondsToDuration($averageSeconds_total);

     $fastest_response_final = secondsToDuration(min($fastest_recep_secs));
     $slowest_response_final = secondsToDuration(max($fastest_recep_secs));

     $associativeArray = array(
        'totalReferrals' => count($dataRecep),
        'averageSeconds_reception' => $averageSeconds_reception,
        'averageDuration_reception' => $averageDuration_reception,
        'averageSeconds_approval' => $averageSeconds_approval,
        'averageDuration_approval' => $averageDuration_approval,
        'averageSeconds_total' => $averageSeconds_total,
        'averageDuration_total' => $averageDuration_total,
        'fastest_response_final' => $fastest_response_final,
        'slowest_response_final' => $slowest_response_final,
        'average_sdn_average' => $formatted_average_sdn_average,
        'averageTime_interdept' => $averageTime_interdept
    );

    // print_r($associativeArray);

    $finalJsonString = json_encode($associativeArray);
    echo $finalJsonString;
?>

