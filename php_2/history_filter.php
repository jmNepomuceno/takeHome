<?php 
    session_start();
    include('../database/connection2.php');
    date_default_timezone_set('Asia/Manila');

    // $sql = "SELECT user_lastLoggedIn, user_lastname, user_middlename, user_firstname FROM sdn_users WHERE hospital_code='" . $_SESSION["hospital_code"] . "'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();
    // $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($data); echo '</pre>';

    $sql = "";
    if($_POST['option'] === 'all'){
        $sql = "SELECT * FROM sdn_users JOIN history_log ON sdn_users.username = history_log.username WHERE sdn_users.username='" . $_SESSION["user_name"] . "' ORDER BY history_log.date DESC";
    }else{
        $sql = "SELECT * FROM sdn_users JOIN history_log ON sdn_users.username = history_log.username WHERE history_log.activity_type='". $_POST['option'] ."' AND sdn_users.username='" . $_SESSION["user_name"] . "' ORDER BY history_log.date DESC";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();   
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($data); echo '</pre>';

    $temp_1 = "";
    $temp_2 = "";
    $temp_3 = "";

    for($i = 0; $i < count($data); $i++){

        if($data[$i]['activity_type'] === 'user_login'){
            $name = $data[$i]['user_lastname'] . ', ' . $data[$i]['user_firstname'] . ' ' . $data[$i]['user_middlename'] . '. ';
            $originalDate = $data[$i]['user_lastLoggedIn'];
            $currentDate = date('Y-m-d H:i:s');
            $formattedDate = "";

            $dateTime = new DateTime($data[$i]['date']);
            $formattedDate = $dateTime->format('F j, Y g:ia');

            $temp_1 = $formattedDate;
            $temp_2 = "Online Status: " . $data[$i]['action'];
            $temp_3 = $name;
        }
        else {
            $name = $data[$i]['user_lastname'] . ', ' . $data[$i]['user_firstname'] . ' ' . $data[$i]['user_middlename'] . '. ';
            $originalDate = $data[$i]['date'];
            $currentDate = date('Y-m-d H:i:s');
            $formattedDate = "";

            $dateTime = new DateTime($originalDate);    
            $formattedDate = $dateTime->format('F j, Y g:ia');

            $temp_1 = $formattedDate;
            $temp_2 = $data[$i]['action'] . ' ' . $data[$i]['pat_name'];
            $temp_3 = $name;
        }
        

        echo '
            <div class="history-div w-full h-[10%] border-b-2 border-[#bfbfbf] flex flex-row justify-between items-center">
                <div class="w-[20%] h-full flex flex-row justify-around items-center ml-4">
                    <i class="fa-regular fa-calendar-days text-2xl "></i>
                    <h3>'. $temp_1 .'</h3>
                </div>

                <div class="w-[30%] h-full flex flex-row justify-around items-center">
                    <!-- <i class="fa-regular fa-calendar-days text-2xl "></i> -->
                    <h3 class="text-base"<span id="status-login">'. $temp_2 .'</span></h3>
                </div>

                <div class="w-[20%] h-full flex flex-row justify-evenly items-center mr-4">
                    <h3> '. $temp_3 .' </h3>
                    <i class="fa-solid fa-user text-2xl "></i>

                </div>
            </div>
        ';
    }
?>