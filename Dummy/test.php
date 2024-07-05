<?php 
    session_start();
    include("../database/connection2.php");

    $sql = "SELECT * FROM incoming_referrals WHERE hpercode='PAT000023' ORDER BY date_time DESC LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);  
    $jsonString = $data;

    $incoming_referrals_data = $data;

    echo '<pre>'; print_r($incoming_referrals_data); echo '</pre>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Highlight Button</title>
    <style>

    </style>
</head>
<body>
   
        $sql = "SELECT * FROM incoming_referrals WHERE status='Approved' AND refer_to='Bataan General Hospital and Medical Center' ORDER BY date_time DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $row) {
            
            
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
                    // print_r($row['hpercode'] . "---" . $total_time);
                    // echo "---";
                }
            }else{
                $interdept_time[0]['final_progress_time'] = "00:00:00";
                // $row['sent_interdept_time'] = "00:00:00";
                $total_time = $row['final_progressed_timer'];
            }


            if($row['approved_time'] == ""){
                $row['approved_time'] = "0000-00-00 00:00:00";
            }

            if($interdept_time[0]['final_progress_time'] == ""){
                $interdept_time[0]['final_progress_time'] = "00:00:00";
            }

            $sdn_processed_value = "";
            if($row['sent_interdept_time'] == ""){
                $row['sent_interdept_time'] = "00:00:00";
                echo $row['final_progressed_timer'];
                $sdn_processed_value = $row['final_progressed_timer'];
            }else{
                $sdn_processed_value =  $row['sent_interdept_time'];
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
                $pat_full_name = "
                    <div class='pat-full-name-div'>
                        <button class='sensitive-case-btn'> <i class='sensitive-lock-icon fa-solid fa-lock'></i> Sensitive Case </button>
                        <input class='sensitive-hpercode' type='hidden' name='sensitive-hpercode' value= '" . $row['hpercode'] . "'>
                    </div>
                ";
            }else{
                $pat_full_name = $row['patlast'] . ", " . $row['patfirst'] . " " . $row['patmiddle'];
            }

            $previous = $row['reference_num'];

            echo $sdn_processed_value;
            echo "---";
        }

        ?>
</body>
</html>
