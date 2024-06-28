<?php
    session_start();
    include('../database/connection2.php');

    //if cache is cleared redirect to index page
    if (!isset($_SESSION['user_name']) || empty($_SESSION['user_name'])) {
        header("Location: ../index.php");
        exit();
    } else {
        if ($_SESSION['user_name'] === 'admin'){
            $user_name = 'Bataan General Hospital and Medical Center';
            $count_pending = isset($_SESSION['count_pending']) ? $_SESSION['count_pending'] : 0;
        }else{
            $user_name = $_SESSION['hospital_name'];
        }
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
    <title>SDN</title>
    
    <?php require "../header_link.php" ?>

    <link rel="stylesheet" href="../css/main_style.css" />
    <style>
        .scrollbar-hidden {        
            scrollbar-width: none;            
            -webkit-scrollbar {
            display: none;
            }
        }
    </style>
</head>
<body>
    <input id="current-page-input" type="hidden" name="current-page-input" value="" />
    <input id="clicked-logout-input" type="hidden" name="clicked-logout-input" value="" />    

    <div id="main-div">
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

        <div class="aside-main-div"> 

            <aside id="side-bar-div">
                <div id="side-bar-title-bgh">
                    <img src="../assets/login_imgs/main_bg.png" alt="logo-img">
                    <p id="bgh-name">Bataan General Hospital and Medical Center</p>
                </div>

                <div id="main-side-bar-1">
                    <div id="main-side-bar-1-subdiv">
                        <i class="fa-solid fa-hospital-user"></i>
                        <h3>Patient Registration</h3>
                    </div>

                    <div id="sub-side-bar-1">
                        <div id="patient-reg-form-sub-side-bar">
                            <i class="fa-solid fa-hospital-user"></i>  
                            <h3>Patient Registration Form</h3>
                        </div>
                    </div>
                </div>

                <div id="main-side-bar-2">
                    <div id="main-side-bar-2-subdiv">
                        <i class="fa-solid fa-retweet"></i>
                        <h3>Online Referral </h3>
                    </div>

                    <div id="sub-side-bar-2">
                        <div id="outgoing-sub-div-id">
                            <i class="fa-solid fa-inbox"></i>
                            <h3>Outgoing</h3>
                        </div>
                        <div id="incoming-sub-div-id">
                            <!-- <h3 class="m-16 text-white">Incoming</h3> -->
                            <i class="fa-solid fa-inbox"></i>
                            <h3>Incoming</h3>
                        </div>
                        
                        <!-- bucas referral -->
                        <!-- <div id="bucasPending-sub-div-id">
                            <i class="fa-solid fa-inbox"></i>
                            <h3>BUCAS (Incoming)</h3>
                        </div> -->

                        <?php if($_SESSION['user_name'] === 'admin'){?>
                        <!-- bucas referral with badge -->
                        <div id="bucasPending-sub-div-id">
                            <i class="fa-solid fa-inbox"></i>
                            <h3>BUCAS (Incoming)</h3>
                            <span id="badge" class="position-absolute top-80 start-80 translate-middle badge rounded-pill bg-danger">
                            <span style="font-size: 10px !important;"><?php echo $count_pending; ?></span>
                                <span class="visually-hidden">unread messages</span>
                            </span>
                        </div>
                        
                        <!-- bucas referral -->
                        <div id="bucasHistory-sub-div-id">
                            <i class="fa-solid fa-inbox"></i>
                            <h3>BUCAS (History)</h3>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </aside>


            <div id="container">
            
            </div>
            <!-- ADMIN MODULE -->
        
        </div>
        
    </div>

    <!-- Include the count pending script for bucas referral-->
    <!-- <?php include('../php_2/count_pending.php'); ?> -->

    <!-- Modal -->
    <div class="modal fade" id="myModal-main" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    Are you sure you want to logout?
            </div>
            <div class="modal-footer">
                <button id="ok-modal-btn-main" type="button" data-bs-dismiss="modal">OK</button>
                <button id="yes-modal-btn-main" type="button" data-bs-dismiss="modal">Yes</button>
            </div>
            </div>
        </div>
    </div>

    <!-- bucas referral modal -->
    <div class="modal fade" id="bucasBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="bucasBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="bucasBackdropLabel">BUCAS MEDICAL RECORD SUMMARY</h1>
                </div>
                <div class="modal-body" style="max-height: 700px; font-size: 14px !important; overflow-y: auto;">

                </div>
                <div class="modal-footer">
                    <button type="button" id="submit-referral-btn" class="btn btn-danger" onclick="">SUBMIT</button>
                    <button type="button" id="searchBtn" class="btn btn-secondary searchBtn" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="overlay"></div>

    <div id="tutorial-carousel" class="carousel slide">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#tutorial-carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#tutorial-carousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#tutorial-carousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../assets/tutorial_images/login_imgs/login_tutorial_1.png" class="d-block w-100" alt="image">
            </div>
            <div class="carousel-item">
                <img src="../assets/tutorial_images/login_imgs/login_tutorial_2.png" class="d-block w-100" alt="image">
            </div>
            <div class="carousel-item">
                <img src="../assets/tutorial_images/login_imgs/login_tutorial_4.png" class="d-block w-100" alt="image">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#tutorial-carousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#tutorial-carousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>


    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <!-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>    
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>


    <script src="../js_2/main_style.js?v=<?php echo time(); ?>"></script>
    <script src="../js_2/location.js?v=<?php echo time(); ?>"></script>

    <script>
        // bucas referral badge count pending
        $(document).ready(function() {
            function updateCountPending() {
                $.ajax({
                    url: 'count_pending.php',
                    method: 'GET',
                    success: function(response) {
                        var _json = JSON.parse(response);
                        // console.log(_json);
                        $('#badge').text(_json.count_pending);
                    },
                    error: function(xhr, status, error) {
                        console.log('Error fetching count pending:', error);
                    }
                });
            }

            updateCountPending();

            setInterval(function() {
                updateCountPending();
            }, 60000);
        });
    </script>



    <!-- <script src="../js_2/patient_register_form2.js?v=<?php echo time(); ?>"></script>
    
    <!-- <script src="./js/incoming_form_2.js?v=<?php echo time(); ?>"></script> -->
    <!-- <script src="./js/fetch_interval.js?v=<?php echo time(); ?>"></script> -->
</body>
</html>