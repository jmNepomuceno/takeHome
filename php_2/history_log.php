<?php 
    session_start();
    include('../database/connection2.php');
    date_default_timezone_set('Asia/Manila');

    // $sql = "SELECT user_lastLoggedIn, user_lastname, user_middlename, user_firstname FROM sdn_users WHERE hospital_code='" . $_SESSION["hospital_code"] . "'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();
    // $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($data); echo '</pre>';

    if ($_SESSION['user_name'] === 'admin'){
        $user_name = 'Bataan General Hospital and Medical Center';
    }else{
        $user_name = $_SESSION['hospital_name'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <?php require "../header_link.php" ?>
    
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen overflow-hidden">

    
    <header class="header-div w-full h-[50px] flex flex-row justify-between items-center bg-[#1f292e]">
        <div class="w-[30%] h-full flex flex-row justify-start items-center">
            <div id="side-bar-mobile-btn" class="side-bar-mobile-btn w-[10%] h-full flex flex-row justify-center items-center cursor-pointer">
                <i class="fa-solid fa-bars text-white text-4xl"></i>
            </div>
            <h1 id="sdn-title-h1" class="text-white text-2xl ml-2 cursor-pointer"> Service Delivery Network</h1>
        </div>
        <div class="account-header-div w-[35%] h-full flex flex-row justify-end items-center mr-2">
            <div class="w-auto h-5/6 flex flex-row justify-end items-center mr-2">
                <!-- <div class="w-[33.3%] h-full   flex flex-row justify-end items-center -mr-1">
                    <h1 class="text-center w-full rounded-full p-1 bg-yellow-500 font-bold">6</h1>
                </div> -->
                
                    <div id="notif-div" class="w-[20px] h-full flex flex-col justify-center items-center cursor-pointer">
                        <h1 id="notif-circle" class="absolute top-2 text-center w-[17px] h-[17px] rounded-full bg-red-600 ml-5 text-white text-xs "><span id="notif-span"></span></h1>
                        <i class="fa-solid fa-bell text-white text-xl"></i>
                        <audio id="notif-sound" preload='auto' muted loop>
                            <source src="../assets/sound/water_droplet.mp3" type="audio/mpeg">
                        </audio>
                    </div>

                    <div class="w-[20px] h-full flex flex-col justify-center items-center">
                        <i class="fa-solid fa-caret-down text-white text-xs mt-2"></i>
                    </div>
                
            </div>

            <div id="nav-account-div" class="header-username-div w-auto h-5/6 flex flex-row justify-end items-center mr-2">
                <div class="w-[15%] h-full flex flex-row justify-end items-center mr-1">
                    <i class="fa-solid fa-user text-white text-xl"></i>
                </div>
                <div id="" class="w-auto h-full whitespace-nowrap flex flex-col justify-center items-center cursor-pointer">
                    <!-- <h1 class="text-white text-lg hidden sm:block">John Marvin Nepomuceno</h1> -->
                    <h1 class="text-white text-lg hidden sm:block"><?php echo $user_name ?> |   <?php echo $_SESSION['last_name'] ?>  <?php echo $_SESSION['first_name']  ?> <?php echo $_SESSION['middle_name']  ?>
                    </h1> 
                </div>
                <div class="w-[5%] h-full flex flex-col justify-center items-center sm:m-1">
                    <i class="fa-solid fa-caret-down text-white text-xs"></i>
                </div>
            </div>
        </div>
    </header>  

    <div id="nav-mobile-account-div" class="sm:hidden flex flex-col justify-start items-center bg-[#1f292e] text-white fixed w-64 h-full overflow-y-auto transition-transform duration-300 transform translate-x-96 z-10">
        <div id="close-nav-mobile-btn" class="w-full h-[50px] mt-2 flex flex-row justify-start items-center">
            <i class="fa-solid fa-x ml-2 text-2xl"></i>
        </div>
        <div class="w-full h-[350px] flex flex-col justify-around items-center">
            <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center">
                <h2 class="" >Dashboard (Incoming)</h2>
            </div>

            <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center">
                <h2 class="">Dashboard (Outgoing)</h2>
            </div>

            <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center">
                <h2 class="">Dashboard (ER/OPD)</h2>
            </div>

            <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center">
                <h2 class="">History Log</h2>
            </div>

            <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center">
                <h2 class="">Settings</h2>
            </div>

            <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center">
                <h2 class="">Help</h2>
            </div>

            <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center">
                <h2 class="">Logout</h2>
            </div>
        </div>
    </div>

    <div id="nav-drop-account-div" class="hidden z-10 absolute right-0 top-[45px] flex flex-col justify-start items-center bg-[#1f292e] text-white fixed w-[15%] h-[400px]">
        <div class="w-full h-[350px] flex flex-col justify-around items-center">
            <?php if($_SESSION["user_name"] == "admin") {?>
                <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                    <h2 id="admin-module-id" class="">Admin</h2>
                </div>
            <?php } ?>
            <div id="dashboard-incoming-btn" class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                <h2 class="">Dashboard (Incoming)</h2>
            </div>

            <div id="dashboard-outgoing-btn" class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                <h2 class="">Dashboard (Outgoing)</h2>
            </div>

            <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                <h2 class="">Dashboard (ER/OPD)</h2>
            </div>

            <div id="history-log-btn" class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                <h2 class="">History Log</h2>
            </div>

            <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                <h2 class="">Settings</h2>
            </div>

            <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                <h2 class="">Help</h2>
            </div>

            <div class="w-2/3 h-[50px] border-b-2 border-[#29363d] flex flex-row justify-center items-center cursor-pointer opacity-30 hover:opacity-100 duration-150">
                <h2 id='logout-btn' class="">Logout</h2>
            </div>
        </div>
    </div>

    <main class="custom-box-shadow w-full h-full flex flex-row justify-center items-center bg-[#ededf2]">
        <div class="w-[70%] h-[80%] bg-white rounded-3xl flex flex-col justify-start items-center">
            <div class="w-full h-[8%] border-b-2 border-[#bfbfbf]  flex flex-row justify-start items-center rounded-t-3xl bg-[#1f292e]">
                <h1 class="text-xl font-bold ml-4 text-white">Account History Log</h1>
            </div> 

           <div class="w-full h-[12%] border-b-2 border-[#bfbfbf] flex flex-row justify-between items-center">
                <div class="w-[30%] h-[50%] ml-4 border-2 rounded-lg border-[#bfbfbf] flex flex-row justify-between items-center">
                    <i class="fa-solid fa-magnifying-glass w-[15%] h-full text-2xl text-center "></i>
                    <input type="text" class="ml-2 w-[90%] h-full outline-none rounded-lg text-xl" placeholder="Search by User">
                </div>

                <div class="w-[30%] h-[50%] mr-4 border-2 rounded-lg border-[#bfbfbf] flex flex-row justify-between items-center">
                    <h1 class="w-[40%] h-full text-center flex flex-col justify-center items-center bg-[#1f292e] text-white">Activity Type</h1>
                    <select id="history-select" type="text" class="w-[90%] h-[90%] outline-none text-xl text-center cursor-pointer border-x-2 border-l-[#bfbfbf]">
                        <option value="">All Logs</option>
                        <option value="login">Login</option>
                        <option value="register">Register</option>
                        <option value="incoming">Incoming</option>
                        <option value="outgoing">Outgoing</option>
                        <!-- <option value="">All Logs</option> -->
                    </select>
                </div>
           </div> 

           <div class="w-full h-[6%] border-b-2 border-[#bfbfbf] flex flex-row justify-between items-center">
                <h1 class="ml-[5%]">Date <i class="fa-solid fa-arrow-down-short-wide"></i></h1>
                <h1 class="">Action <i class="fa-solid fa-arrow-down-short-wide"></i></h1>
                <h1 class="mr-[5%]">User name <i class="fa-solid fa-arrow-down-short-wide"></i></h1>
           </div> 

            <div class="history-container w-full h-full overflow-auto">
                <?php 
                    $sql = "SELECT * FROM sdn_users JOIN history_log ON sdn_users.username = history_log.username WHERE sdn_users.username='" . $_SESSION["user_name"] . "' ORDER BY history_log.date DESC";
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

                        $style_color = "#ffffff";
                        $text_color = "#1f292e";
                        if($i % 2 == 1){
                            $style_color = "#1f292e"; 
                            $text_color = "#ffffff";
                        }

                        echo '
                            <div class="history-div w-full h-[10%] border-b-2 border-[#bfbfbf] flex flex-row justify-between items-center bg-['.$style_color.'] text-['.$text_color.']">
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
            </div>
        </div>
    </main>

    <!-- Modal -->
    <div class="modal fade" id="myModal-main" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header flex flex-row justify-between items-center">
                <div class="flex flex-row justify-between items-center">
                    <h5 id="modal-title-main" class="modal-title-main" id="exampleModalLabel">Warning</h5>
                    <i id="modal-icon" class="fa-solid fa-triangle-exclamation ml-2"></i>
                    <!-- <i class="fa-solid fa-circle-check"></i> -->
                </div>
                <button type="button" class="close text-3xl" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modal-body-main" class="modal-body-main ml-7">
                Are you sure you want to logout?
            </div>
            <div class="modal-footer">
                <button id="ok-modal-btn-main" type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-dismiss="modal">OK</button>
                <button id="yes-modal-btn-main" type="button" class="hidden bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-dismiss="modal">Yes</button>
            </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="../js_2/history_log.js?v=<?php echo time(); ?>"></script>
</body>
</html>