<?php 
    session_start();
    include('../database/connection2.php');
    date_default_timezone_set('Asia/Manila');
    

    $dateTime = new DateTime();
    // Format the DateTime object to get the year, month, and day
    $formattedDate = $dateTime->format('Y-m-d') . '%';

    $sql = "SELECT COUNT(*) FROM incoming_referrals WHERE status='Approved' AND approved_time LIKE :proc_date AND refer_to = '" . $_SESSION["hospital_name"] . "'";
    // $sql = "SELECT COUNT(*) FROM incoming_referrals WHERE status='Approved' AND approved_time LIKE '2024-02-08%' AND refer_to = '" . $_SESSION["hospital_name"] . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':proc_date', $formattedDate, PDO::PARAM_STR);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $number_of_referrals = $data['COUNT(*)'];

    if ($_SESSION['user_name'] === 'admin'){
        $user_name = 'Bataan General Hospital and Medical Center';
    }else{
        $user_name = $_SESSION['hospital_name'];
    }

    $averageDuration_reception = "00:00:00";
    $averageDuration_approval  = "00:00:00";
    $averageDuration_total  = "00:00:00";
    $fastest_response_final  = "00:00:00";
    $slowest_response_final  = "00:00:00";
    
    $currentDateTime = date('Y-m-d');
    $formatted_average_sdn_average = "00:00:00";
    $averageTime_interdept = "00:00:00";

    if($data['COUNT(*)'] > 0){
        
        // echo $currentDateTime;
        $sql = "SELECT hpercode, reception_time, date_time, final_progressed_timer, sent_interdept_time FROM incoming_referrals WHERE refer_to = :hospital_name AND reception_time LIKE :current_date";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':hospital_name', $_SESSION['hospital_name']); 
        $currentDateTime_param = "%$currentDateTime%";
        $stmt->bindParam(':current_date', $currentDateTime_param, PDO::PARAM_STR); 
        $stmt->execute();
        $dataRecep = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // INTERDEPARTAMENTAL REFERRAL 
        $sql = "SELECT hpercode, final_progress_time FROM incoming_interdept WHERE final_progress_date LIKE :current_date";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':current_date', $currentDateTime_param, PDO::PARAM_STR); 
        $stmt->execute();
        $dataRecep_interdept = $stmt->fetchAll(PDO::FETCH_ASSOC);


        // echo '<pre>'; print_r($dataRecep); echo '</pre>';
        // echo '<pre>'; print_r($dataRecep_interdept); echo '</pre>';

        $recep_arr = array();
        for($i = 0; $i < count($dataRecep); $i++){
            // Given dates
            $date1 = new DateTime($dataRecep[$i]['reception_time']);
            $date2 = new DateTime($dataRecep[$i]['date_time']);

            // Calculate the difference
            $interval = $date2->diff($date1);

            // Format the difference as hh:mm:ss
            $formattedDifference = sprintf(
                '%02d:%02d:%02d',
                $interval->h,
                $interval->i,
                $interval->s
            );

            // Access the difference components
            $hours = $interval->h; // hours
            $minutes = $interval->i; // minutes
            $seconds = $interval->s; // seconds

            // Output the time difference
            // echo "Time Difference: $hours hours, $minutes minutes, $seconds seconds";

            array_push($recep_arr, $formattedDifference);
        }
        
        // // INTERDEPT REFERRAL AVERAGE
        // $totalSeconds_interdept = 0;
        // foreach ($dataRecep_interdept as $item) {
        //     // Extract hours, minutes, and seconds from final_progress_time
        //     list($hours, $minutes, $seconds) = explode(':', $item['final_progress_time']);
        //     // Convert hours and minutes to seconds and add to total
        //     $totalSeconds_interdept += $hours * 3600 + $minutes * 60 + $seconds;
        // }

        // // Calculate the average in seconds
        // $averageSeconds_interdept = (int) ($totalSeconds_interdept / count($dataRecep_interdept));

        // // Optionally, convert the average back to hh:mm:ss format
        // $averageTime_interdept = gmdate("H:i:s", $averageSeconds_interdept);


        // SDN REFERRAL AVERAGE
        $sum_sdn_average = 0;

        foreach ($dataRecep as $item) {
            // echo '<pre>'; print_r($item); echo '</pre>';
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

        
        // echo '<pre>'; print_r($fastest_recep_secs); echo '</pre>';

        $averageSeconds_reception = (int) round($averageSeconds_reception / $data['COUNT(*)']);
        $averageDuration_reception = secondsToDuration($averageSeconds_reception);  

        $averageSeconds_approval = (int) round($averageSeconds_approval / $data['COUNT(*)']);
        $averageDuration_approval = secondsToDuration($averageSeconds_approval);

        $averageSeconds_total = (int) round($averageSeconds_total / $data['COUNT(*)']);
        $averageDuration_total = secondsToDuration($averageSeconds_total);

        $fastest_response_final = secondsToDuration(min($fastest_recep_secs));
        $slowest_response_final = secondsToDuration(max($fastest_recep_secs));
    }

    // populate table header by the patient classifications.
    $class_code = array();
    $sql = "SELECT class_code FROM classifications";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $pat_class_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // echo '<pre>'; print_r($pat_class_data); echo '</pre>';
    for($i = 0; $i < count($pat_class_data); $i++){
        $class_code[$pat_class_data[$i]['class_code'] . "_primary"] = 0;
        $class_code[$pat_class_data[$i]['class_code'] . "_secondary"] = 0;
        $class_code[$pat_class_data[$i]['class_code'] . "_tertiary"] = 0;
    }   
    // echo '<pre>'; print_r($class_code); echo '</pre>';
    $current_date_1 = date("F Y");
    $current_date_2 = date("F j, Y - h:ia");

    // get all the refer from hospitals
    $sql = "SELECT referred_by FROM incoming_referrals WHERE refer_to = :hospital_name AND reception_time LIKE :current_date";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':hospital_name', $_SESSION['hospital_name']); 
    $currentDateTime_param = "%$currentDateTime%";
    $stmt->bindParam(':current_date', $currentDateTime_param, PDO::PARAM_STR); 
    $stmt->execute();
    $dataReferFrom = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $dataReferFrom_json = json_encode($dataReferFrom);

    $sql = "SELECT pat_class FROM incoming_referrals WHERE refer_to = :hospital_name AND reception_time LIKE :current_date";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':hospital_name', $_SESSION['hospital_name']); 
    $currentDateTime_param = "%$currentDateTime%";
    $stmt->bindParam(':current_date', $currentDateTime_param, PDO::PARAM_STR); 
    $stmt->execute();
    $dataPatClass = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $dataPatClass_json = json_encode($dataPatClass);

    $sql = "SELECT type FROM incoming_referrals WHERE refer_to = :hospital_name AND reception_time LIKE :current_date";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':hospital_name', $_SESSION['hospital_name']); 
    $currentDateTime_param = "%$currentDateTime%";
    $stmt->bindParam(':current_date', $currentDateTime_param, PDO::PARAM_STR); 
    $stmt->execute();
    $dataPatType = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $dataPatType_json = json_encode($dataPatType);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- <link rel="stylesheet" href="../output.css"> -->
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <link rel="stylesheet" href="../css/dashboard_incoming.css">
</head>
<body class="h-screen">

    <input type="hidden" id="total-processed-refer-inp" value=<?php echo $data['COUNT(*)'] ?>>
    
    <header class="header-div">
        <div class="header-sub-div-1">
            <div id="side-bar-mobile-btn" class="side-bar-mobile-btn">
                <i class="fa-solid fa-bars"></i> 
            </div>
            <h1 id="sdn-title-h1"> Service Delivery Network</h1>
        </div>
        <div class="account-header-div">

            <div class="notification-sub-div">
                <!-- <div class="w-[33.3%] h-full   flex flex-row justify-end items-center -mr-1">
                    <h1 class="text-center w-full rounded-full p-1 bg-yellow-500 font-bold">6</h1>
                </div> -->
                
                    <div id="notif-div">
                        <h1 id="notif-circle"><span id="notif-span" >33</span></h1>
                        <i class="fa-solid fa-bell"></i>
                        <audio id="notif-sound" preload='auto' muted loop>
                            <source src="../assets/sound/water_droplet.mp3" type="audio/mpeg">
                        </audio>

                        <div id="notif-sub-div">
                            <!-- <div class="h-[30px] w-full border border-black flex flex-row justify-evenly items-center">
                                <h4 class="font-bold text-lg">3</h4>
                                <h4 class="font-bold text-lg">OB</h4>
                            </div> -->
                            <!-- b3b3b3 -->
                        </div>
                    </div>
            </div>

            <div id="nav-account-div" class="header-username-div">
                <div class="user-icon-div">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div id="" class="user-name-div">
                    <!-- <h1 class="text-white text-lg hidden sm:block">John Marvin Nepomuceno</h1> --> 
                    <?php 
                        if($_SESSION['last_name'] === 'Administrator'){
                            echo '<h1>' . $user_name . ' | ' . $_SESSION["last_name"] . '</h1>';
                        }else{
                            echo '<h1>' . $user_name . ' | ' . $_SESSION["last_name"] . ', ' . $_SESSION['first_name'] . ' ' . $_SESSION['middle_name'] . '</h1>';;
                        }
                    ?>
                </div>
                <div class="caret-div">
                    <i class="fa-solid fa-caret-down"></i>
                </div>
            </div>
        </div>
    </header>  

    <div id="nav-drop-account-div">
        <?php if($_SESSION["user_name"] == "admin") {?>
            <div id="admin-module-div-id">
                <h2 id="admin-module-id" class="">Admin</h2>
            </div>
        <?php } ?>
        <div id="dashboard-incoming-btn">
            <h2 class="">Dashboard (Incoming)</h2>
        </div>

        <div id="dashboard-outgoing-btn">
            <h2 class="">Dashboard (Outgoing)</h2>
        </div>

        <div>
            <h2 class="">Dashboard (ER/OPD)</h2>
        </div>

        <div id="history-log-btn">
            <h2 class="">History Log</h2>
        </div>

        <div>
            <h2 class="">Settings</h2>
        </div>

        <div>
            <h2 class="">Help</h2>
        </div>

        <div>
            <h2 id='logout-btn' class="">Logout</h2>
        </div>
    </div>

    <div class="main-div"> 
        <div class="main-title-div">
            <label>Dashboard For Incoming Referrals</label>
            <div> 
                <label id="curr-month-lbl"><?php echo $current_date_1 ?></label>
                <label id="curr-date-lbl"">as of <?php echo $current_date_2 ?></label>
            </div>
        </div>

        <div class="main-filter-div">
            <button id="filter-date-btn">Filter</button>
            <div>
                <label>from <input type="date" id='from-date-inp'> to <input type="date" id='to-date-inp'></label>
            </div>
        </div>

        <div class="main-turnaround-div">
            <div>
                <label id="total-processed-refer">18</label>
                <label>Total Processed Referrals</label>
            </div>
            <div>
                <label id="average-reception-id" class="average-reception-lbl"><?php echo $averageDuration_reception ?></label>
                <label>Average Reception Time</label>
            </div>

            <div>
                <label id="average-sdn-approve-id"><?php echo $formatted_average_sdn_average ?></label>
                <label>Average SDN Approval Time</label>
            </div>

            <div>
                <label id="average-interdept-approve-id"><?php echo $averageTime_interdept ?></label>
                <label id="average-interdept-approve-lbl">Average Interdepartamental Approval Time</label>
            </div>


            <div>
                <label id="average-approve-id"><?php echo $averageDuration_approval ?></label>
                <label>Average Approval Time</label>
            </div>

            <div>
                <label id="fastest-id"><?php echo $fastest_response_final ?></label>
                <label>Fastest Response Time</label>
            </div>

            <div>
                <label id="slowest-id"><?php echo $slowest_response_final ?></label>
                <label>Slowest Response Time</label>
            </div>
        </div>

        <div class="main-graph-div">
            <div id="main-graph-sub-div-1">
                <label class="font-semibold text-xl ">Case Category</label>
                <canvas id="myChart-1"></canvas>
            </div>

            <div id="main-graph-sub-div-2">
                <label class="font-semibold text-xl">Case Type</label>
                <canvas id="myChart-2"></canvas>
            </div>


            <div id="main-graph-sub-div-3">
                <label class="font-semibold text-xl">Referring Health Facility</label>
                <canvas id="myChart-3"></canvas>
            </div>
        </div>

        <div class="main-data-div">
            <table id="tablet">
                <thead class="w-full">
                    <tr>
                        <th rowspan="3" class="pat-class" >
                            <label>Referring Health Facility</label>
                        </th>

                        <?php 
                            // echo '<pre>'; print_r($pat_class_data); echo '</pre>';
                            foreach ($pat_class_data as $class) {
                                echo "<th class=\"pat-class\" colspan=\"3\">";
                                echo "<label>{$class['class_code']}</label>";
                                echo "</th>";
                                echo "\n"; // Add a newline for readability
                            }
                        ?>

                        <th rowspan="2" class="pat-class" >
                            <label>Total</label>
                        </th>
                    </tr>   

                    <tr>
                        
                        <?php 
                            for($i = 0; $i < count($pat_class_data); $i++){
                                echo '
                                <th>
                                    <label>Primary</label>
                                </th>
        
        
                                <th>
                                    <label>Secondary</label>
                                </th>
        
                                <th>
                                    <label>Tertiary</label>
                                </th>
    
                                ';
                            }
                        ?>
                    </tr> 
                </thead> 

                <tbody id="tbody-class" class="w-full">
                        <!-- <tr class="tr-div text-center">
                            <td class="border-2 border-slate-700 col-span-3">CENTRO MEDICO DE SANTISIMO ROSARIO</td>

                            <td class="add border-2 border-slate-700">10</td>
                            <td class="add border-2 border-slate-700">2</td>
                            <td class="add border-2 border-slate-700">2</td>

                            <td class="add border-2 border-slate-700">2</td>
                            <td class="add border-2 border-slate-700">3</td>
                            <td class="add border-2 border-slate-700">2</td>

                            <td class="add border-2 border-slate-700">45</td>
                            <td class="add border-2 border-slate-700">6</td>
                            <td class="add border-2 border-slate-700">2</td>
                            <td class="sumCell border-2 border-slate-700"></td>
                        </tr>              -->


                    <?php 
                        $dateTime = new DateTime();
                        // Format the DateTime object to get the year, month, and day
                        $formattedDate = $dateTime->format('Y-m-d') . '%';
                        // echo $formattedDate;

                        $sql = "SELECT pat_class, type, referred_by FROM incoming_referrals WHERE status='Approved' AND approved_time LIKE :proc_date AND refer_to = '" . $_SESSION["hospital_name"] . "'";
                        // $sql = "SELECT pat_class, type, referred_by FROM incoming_referrals WHERE (status='Approved' OR status='Checked' OR status='Arrived') AND refer_to = '" . $_SESSION["hospital_name"] . "'";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':proc_date', $formattedDate, PDO::PARAM_STR);
                        $stmt->execute();
                        $tr_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        // echo '<pre>'; print_r($tr_data); echo '</pre>';

                        for($i = 0; $i < count($tr_data); $i++){
                            echo '<input type="hidden" class="referred-by-class" value="' . $tr_data[$i]["referred_by"] . '">';
                        }

                        $in_table = [];
                        
                        foreach ($tr_data as $row){
                            if (!in_array($row['referred_by'], $in_table)) {
                                $in_table[] = $row['referred_by'];
                            }   
                        }

                        // echo '<pre>'; print_r($class_code); echo '</pre>';
                        $loop_index = 0;
                        for($i = 0; $i < count($in_table); $i++){
                            foreach ($tr_data as $row){
                                if($in_table[$i] === $row['referred_by']){
                                    $referred_by = $row['referred_by'];

                                    // new logic for dynamic rendering of the classication of the patients case to be put on the table
                                    $lowercase_string = strtolower($row['pat_class']);
                                    $class_code[$row['type']."_".$lowercase_string] += 1;
                                }        
                            }


                            echo '
                            <tr class="tr-div text-center"> 
                                <td class="border-2 border-slate-700 col-span-3">'.$referred_by.'</td>
                            ';
                            foreach ($class_code as $key => $value) {
                                echo '
                                    <td class="add border-2 border-slate-700">'. $value .'</td>
                                ';
                            }
                            echo '
                                <td class="sumCell border-2 border-slate-700">'. array_sum($class_code) .'</td>
                            </tr>
                            ';

                            $class_code = array_fill_keys(array_keys($class_code), 0);
                        }   
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal-dashboardIncoming" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <!-- <div class="modal-dialog" role="document"> -->
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title-div">
                        <h5 id="modal-title-main" class="modal-title-main" id="exampleModalLabel">Warning</h5>
                        <i id="modal-icon" class="fa-solid fa-triangle-exclamation"></i>
                        <!-- <i class="fa-solid fa-circle-check"></i> -->
                    </div>
                    <!-- <button id="x-btn" type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> -->
                </div>
                <!-- <div id="modal-body-main" class="modal-body-main"> -->
                <div id="modal-body" class="logout-modal">
                    No incoming referrals for today yet.
                </div>
                <div class="modal-footer">
                    <button id="ok-modal-btn-main" type="button" data-bs-dismiss="modal">OK</button>
                    <button id="yes-modal-btn-main" type="button" data-bs-dismiss="modal" style="display:none">Yes</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        var dataReferFrom = <?php echo $dataReferFrom_json; ?>;
        var dataPatClass = <?php echo $dataPatClass_json; ?>;
        var dataPatType = <?php echo $dataPatType_json; ?>;
        var number_of_referrals = <?php echo $number_of_referrals ?>
    </script>
    <script type="text/javascript" src="../js_2/dashboard_incoming.js?v=<?php echo time(); ?>"></script>
</body>
</html>