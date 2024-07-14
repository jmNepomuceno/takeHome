<?php 
    session_start();
    include('../database/connection2.php');
    date_default_timezone_set('Asia/Manila');
    
    if ($_SESSION['user_name'] === 'admin'){
        $user_name = 'Bataan General Hospital and Medical Center';
    }else{
        $user_name = $_SESSION['hospital_name'];
    }

    $sql = "SELECT COUNT(*) FROM incoming_referrals WHERE status='Pending' AND refer_to='". $_SESSION['hospital_name'] ."'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $incoming_num = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <?php require "../header_link.php" ?>
    <link rel="stylesheet" href="../css/history_log.css">
</head>
<body class="h-screen">

    <header class="header-div">
        <div class="side-bar-title">
            <h1 id="sdn-title-h1"> Service Delivery Network</h1>
            <div class="side-bar-mobile-btn">
                <i id="navbar-icon" class="fa-solid fa-bars"></i>
            </div>
        </div>
        <div class="account-header-div">
            <div class="notif-main-div">
                <!-- <div class="w-[33.3%] h-full   flex flex-row justify-end items-center -mr-1">
                    <h1 class="text-center w-full rounded-full p-1 bg-yellow-500 font-bold">6</h1>
                </div> -->
                                    
                    <div id="notif-div">
                        <?php 
                            if($incoming_num['COUNT(*)'] > 0){
                                echo '<h1 id="notif-circle" style="display:block;"><span id="notif-span"></span></h1>';
                            }else{
                                echo '<h1 id="notif-circle" style="display:none;"><span id="notif-span"></span></h1>';
                            }
                        ?>
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

                    <!-- <div class="w-[20px] h-full flex flex-col justify-center items-center">
                        <i class="fa-solid fa-caret-down text-white text-xs mt-2"></i>
                    </div> -->
            </div>

            <div id="nav-account-div" class="header-username-div">
                <div class="user-icon-div">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="user-name-div">
                    <!-- <h1 class="text-white text-lg hidden sm:block">John Marvin Nepomuceno</h1> -->
                    <?php 
                        if($_SESSION['last_name'] === 'Administrator'){
                            echo '<h1 id="user_name-id">' . $user_name . ' | ' . $_SESSION["last_name"] . '</h1>';
                        }else{
                            echo '<h1 id="user_name-id">' . $user_name . ' | ' . $_SESSION["last_name"] . ', ' . $_SESSION['first_name'] . ' ' . $_SESSION['middle_name'] . '</h1>';;

                        }
                    ?>
                    
                </div>
                <div class="username-caret-div">
                    <i class="fa-solid fa-caret-down"></i>
                </div>
            </div>
        </div>
    </header>

    <div id="nav-drop-account-div">
        <div id="nav-drop-acc-sub-div">
            
            <?php if($_SESSION["user_name"] == "admin") {?>
                <div id="admin-module-btn" class="nav-drop-btns">
                    <h2 id="admin-module-id" class="nav-drop-btns-txt">Admin</h2>
                </div>
            <?php } ?>
            <div id="dashboard-incoming-btn" class="nav-drop-btns">
                <h2 class="nav-drop-btns-txt">Dashboard (Incoming)</h2>
            </div>

            <div id="dashboard-outgoing-btn" class="nav-drop-btns">
                <h2 class="nav-drop-btns-txt">Dashboard (Outgoing)</h2>
            </div>

            <div class="nav-drop-btns">
                <h2 class="nav-drop-btns-txt">Dashboard (ER/OPD)</h2>
            </div>

            <div id="history-log-btn" class="nav-drop-btns">
                <h2 class="nav-drop-btns-txt">History Log</h2>
            </div>

            <div class="nav-drop-btns">
                <h2 class="nav-drop-btns-txt">Settings</h2>
            </div>

            <div class="nav-drop-btns">
                <h2 class="nav-drop-btns-txt">Help</h2>
            </div>

            <div class="nav-drop-btns">
                <h2 id='logout-btn' class="nav-drop-btns-txt" data-bs-toggle="modal" data-bs-target="#myModal-main">Logout</h2>
            </div>
        </div>
    </div>

    <div class="main-div">
        <div class="main-sub-div">
            <div>
                <h1>Account History Log</h1>
            </div> 


        </div>  
    </div>

    
    <script type="text/javascript" src="../js_2/history_log.js?v=<?php echo time(); ?>"></script>
</body>
</html>