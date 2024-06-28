<?php 
    include('database/connection2.php');
    // include('');
    // include('./php/csrf/session.php');

    session_start();
    // echo "Session ID: " . $sessionID . "<br>";
    // echo "CSRF Token: " . $_SESSION['_csrf_token'];

    if($_POST){
        // if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['_csrf_token']) {
        //     // CSRF token verification failed, handle accordingly (e.g., show an error)
        //     die("CSRF token verification failed");
        // }
        
        $_SESSION["process_timer"] = [] ;
        $sdn_username = $_POST['sdn_username'];
        $sdn_password = $_POST['sdn_password'];
        $account_validity = false;

        $timezone = new DateTimeZone('Asia/Manila'); // Replace 'Your/Timezone' with your actual time zone
        $currentDateTime = new DateTime("",$timezone);

        // Format date components
        $year = $currentDateTime->format('Y');
        $month = $currentDateTime->format('m');
        $day = $currentDateTime->format('d');

        $hours = $currentDateTime->format('H');
        $minutes = $currentDateTime->format('i');
        $seconds = $currentDateTime->format('s');

        $final_date = $year . "/" . $month . "/" . $day . " " . $hours . ":" . $minutes . ":" . $seconds;
        $normal_date = $year . "-" . $month . "-" . $day . " " . $hours . ":" . $minutes . ":" . $seconds;

        // login verifaction for the outside users
        if($sdn_username != "admin" && $sdn_password != "admin"){
            try{
                $stmt = $pdo->prepare('SELECT * FROM sdn_users WHERE username = ? AND password = ?');
                $stmt->execute([$sdn_username , $sdn_password]);
                $data_child = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if(count($data_child) == 1){
                    $account_validity = true;
                }
                
                if($account_validity == true){
                    $stmt = $pdo->prepare('SELECT * FROM sdn_hospital WHERE hospital_code = ?');
                    $stmt->execute([$data_child[0]['hospital_code']]);
                    $data_parent = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    

                    $_SESSION['hospital_code'] = $data_parent[0]['hospital_code'];
                    $_SESSION['hospital_name'] = $data_parent[0]['hospital_name'];
                    $_SESSION['hospital_email'] = $data_parent[0]['hospital_email'];
                    $_SESSION['hospital_landline'] = $data_parent[0]['hospital_landline'];
                    $_SESSION['hospital_mobile'] = $data_parent[0]['hospital_mobile'];
                    $_SESSION['hospital_name'] = $data_parent[0]['hospital_name'];

                    $_SESSION['user_name'] = $data_child[0]['username'];
                    $_SESSION['user_password'] = $data_child[0]['password'];
                    $_SESSION['first_name'] = $data_child[0]['user_firstname'];
                    $_SESSION['last_name'] = $data_child[0]['user_lastname'];
                    $_SESSION['middle_name'] = $data_child[0]['user_middlename'];
                    $_SESSION['user_type'] = 'outside';

                    $_SESSION['post_value_reload'] = 'false';
                    $_SESSION["sub_what"] = "";

                    $_SESSION['running_bool'] = false;
                    $_SESSION['running_startTime'] = "";
                    $_SESSION['running_timer'] = "";
                    $_SESSION['fifo_hpercode'] = "asdf";
                    $_SESSION['running_hpercode'] = "";
                    $_SESSION['login_time'] = $final_date;

                    $_SESSION['current_content'] = "";

                    $sql = "UPDATE incoming_referrals SET login_time = '". $final_date ."' , login_user='". $sdn_username ."' ";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();

                    $sql = "UPDATE sdn_users SET user_lastLoggedIn='online' , user_isActive='1' WHERE username=:username AND password=:password";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':username', $data_child[0]['username'], PDO::PARAM_STR);
                    $stmt->bindParam(':password', $data_child[0]['password'], PDO::PARAM_STR);
                    $stmt->execute();

                    // for history log
                    $act_type = 'user_login';
                    $pat_name = " ";
                    $hpercode = " ";
                    $action = 'online';
                    $user_name = $data_child[0]['username'];
                    $sql = "INSERT INTO history_log (hpercode, hospital_code, date, activity_type, action, pat_name, username) VALUES (?,?,?,?,?,?,?)";
                    $stmt = $pdo->prepare($sql);

                    $stmt->bindParam(1, $hpercode, PDO::PARAM_STR);
                    $stmt->bindParam(2, $_SESSION['hospital_code'], PDO::PARAM_INT);
                    $stmt->bindParam(3, $normal_date, PDO::PARAM_STR);
                    $stmt->bindParam(4, $act_type, PDO::PARAM_STR);
                    $stmt->bindParam(5, $action, PDO::PARAM_STR);
                    $stmt->bindParam(6, $pat_name, PDO::PARAM_STR);
                    $stmt->bindParam(7, $user_name, PDO::PARAM_STR);

                    $stmt->execute();

                    header('Location: ./php_2/main2.php');
                }else{
                    echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <script type="text/javascript">
                                var jQuery = $.noConflict(true);
                                jQuery(document).ready(function() {
                                    jQuery("#modal-title").text("Warning")
                                    jQuery("#modal-icon").addClass("fa-triangle-exclamation")
                                    jQuery("#modal-icon").removeClass("fa-circle-check")
                                    jQuery("#modal-body").text("Invalid username and password!")
                                    jQuery("#ok-modal-btn").text("Close")
                                    jQuery("#myModal").modal("show");
                                });
                            </script>';
                }
                
            }catch(PDOException $e){
                echo "Error: " . $e->getMessage();
            }

        }
        
        //verification for admin user logged in
        else if($sdn_username == "admin" && $sdn_password == "admin"){
            $_SESSION['hospital_code'] = '1437';
            $_SESSION['hospital_name'] = "Bataan General Hospital and Medical Center";
            $_SESSION['hospital_landline'] = '333-3333';
            $_SESSION['hospital_mobile'] = '3333-3333-333';
            
            $_SESSION['user_name'] = 'admin';
            $_SESSION['user_password'] = 'admin';
            $_SESSION['last_name'] = 'Administrator';
            $_SESSION['first_name'] = '';
            $_SESSION['middle_name'] = '';
            $_SESSION['user_type'] = 'admin';
            $_SESSION['post_value_reload'] = 'false';
            $_SESSION["sub_what"] = "";

            $_SESSION['mcc_passwords'] = [
                "Lacsamana" => "123",
                "Baltazar" => "1"
            ];
            
            $_SESSION['running_bool'] = false;
            $_SESSION['running_startTime'] = "";
            $_SESSION['running_timer'] = "";
            $_SESSION['running_hpercode'] = "";
            $_SESSION['running_index'] = "";
            $_SESSION['fifo_hpercode'] = "asdf";
            $_SESSION['update_current_date'] = "";
            $_SESSION['patient_status'] = "";
            $_SESSION['approval_details_arr'] = array();
            
            $_SESSION['current_content'] = "";

            $temp_date = $normal_date;
            
            $_SESSION['login_time'] = $final_date;

            $sql = "UPDATE incoming_referrals SET login_time = :final_date, login_user = :sdn_username";
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':final_date', $final_date, PDO::PARAM_STR);
            $stmt->bindParam(':sdn_username', $sdn_username, PDO::PARAM_STR);

            // Execute the statement
            $stmt->execute();

            $sql = "UPDATE sdn_users SET user_lastLoggedIn='online' , user_isActive='1' WHERE username='admin' AND password='admin'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            // for history log
            $act_type = 'user_login';
            $pat_name = " ";
            $hpercode = " ";
            $action = 'online';
            $user_name = 'admin';
            $sql = "INSERT INTO history_log (hpercode, hospital_code, date, activity_type, action, pat_name, username) VALUES (?,?,?,?,?,?,?)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(1, $hpercode, PDO::PARAM_STR);
            $stmt->bindParam(2, $_SESSION['hospital_code'], PDO::PARAM_INT);
            $stmt->bindParam(3, $temp_date, PDO::PARAM_STR);
            $stmt->bindParam(4, $act_type, PDO::PARAM_STR);
            $stmt->bindParam(5, $action, PDO::PARAM_STR);
            $stmt->bindParam(6, $pat_name, PDO::PARAM_STR);
            $stmt->bindParam(7, $user_name, PDO::PARAM_STR);

            $stmt->execute();

            header('Location: ./php_2/main2.php');
        } 
        else if($sdn_username != 'admin' || $sdn_password != 'admin'){
            echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script type="text/javascript">
                        var jQuery = $.noConflict(true);
                        jQuery(document).ready(function() {
                            jQuery("#modal-title").text("Warning")
                            jQuery("#modal-icon").addClass("fa-triangle-exclamation")
                            jQuery("#modal-icon").removeClass("fa-circle-check")
                            jQuery("#modal-body").text("Invalid username and password!")
                            jQuery("#ok-modal-btn").text("Close")
                            jQuery("#myModal").modal("show");
                        });
                    </script>';
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Delivery Network</title>

    <?php require "./header_link.php" ?>
    <link rel="stylesheet" href="index.css" />

    <style>
        .custom-box-shadow {
            box-shadow: rgb(38, 57, 77) 0px 20px 30px -10px;
            
        }

        canvas{ 
            display: block; vertical-align: bottom; 
        } /* ---- particles.js container ---- */ 

        #particles-js{ 
            position:absolute; width: 100%; height: 100%; background-color: #86A789; background-repeat: no-repeat; background-size: cover; background-position: 50% 50%; 
        } 
        /* ---- stats.js ---- */ 

        .js-count-particles{ 
            font-size: 1.1em; 
        } 

        #stats, .count-particles{ 
            -webkit-user-select: none; margin-top: 5px; margin-left: 5px; 
        } 

        #stats{ border-radius: 3px 3px 0 0; overflow: hidden; 
        } 

        .count-particles{ 
            border-radius: 0 0 3px 3px; 
        }
    </style>
</head>
<body>
        <!-- aesthetic hospital website background -->
        <div id="particles-js"></div> 
        <div class="count-particles"> <span class="js-count-particles">--</span> particles </div> <script src="http://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script> 
        
    <div class="container">
        
        <!-- <div class="coating-div">
            <img src="./assets/login_imgs/main_bg3.jpg" alt="main_bg-image" class="blurred-image">
            <div></div>
        </div> -->

        <div class="main-content">
            <h1 class="letter-border">Service Delivery Network</h1>

            <div class="glass-div">
                <h1 id="login-txt">Login</h1>
                <form action="index.php" method="POST">
                    <!-- here csrf -->

                    <div id="username-div">
                        <i class="username-icon fa-solid fa-user"></i>
                        <input type="text" name="sdn_username" id="username-inp" placeholder="Username" required autocomplete="off">
                    </div>

                    <div id="password-div">
                        <i class="username-icon fa-solid fa-user"></i>
                        <input type="password" name="sdn_password" id="password-inp" placeholder="Password" required autocomplete="off">
                    </div>

                    <button id="login-btn">Login</button>
                </form>
                
                <div class="query-signin-div">
                    <label for="" id="query-signin-txt">Don't have an account yet? Sign in</label>
                </div>
            </div>
        </div>

        <div class="sub-content">
            <!-- <i class="fa-solid fa-arrow-left"></i> -->
            <div class="sub-content-header-div">
                <div class="sub-content-header">SERVICE DELIVERY NETWORK</div>
                <i class="return fa-solid fa-arrow-left"></i>
            </div>

            <div class="sub-nav-btns">
                <button type="button" id="registration-btn" class="btn btn-primary">Registration</button>
                <button type="button" id="authorization-btn" class="btn btn-dark">Authorization</button>
            </div>

            <div class="sub-content-note">
                This is one-time registration ONLY. If you already have an account, no need to register again.
                <span style="color:red; margin-left:6%;">A one-time password and authorization key will be send to your registered mobile no.</span>
            </div>

            <form class="sub-content-registration-form">
                <div class="reg-form-divs">
                    <label for="" class="reg-labels">Hospital Name</label>
                    <input id="sdn-hospital-name" type="text" class="reg-inputs" required autocomplete="off">
                </div>

                <div class="reg-form-divs">
                    <label for="" class="reg-labels">Hospital Code</label>
                    <input id="sdn-hospital-code" type="number" class="reg-inputs" required autocomplete="off">
                </div>

                <div class="reg-form-divs">
                    <label for="" class="reg-labels">Address: Region</label>
                    <select id="sdn-region-select" class="reg-inputs" name="region" required autocomplete="off" style="cursor:pointer;" onchange="getLocations('region' , 'sdn-region')">
                        <option value="" class="">Choose a Region</option>
                        <?php 
                            $stmt = $pdo->query('SELECT region_code, region_description from region');
                            while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
                                echo '<option value="' , $data['region_code'] , '" >' , $data['region_description'] , '</option>';
                            }                                        
                        ?>
                    </select>
                </div>

                <div class="reg-form-divs">
                    <label for="" class="reg-labels">Address: Province</label>
                    <select id="sdn-province-select" class="reg-inputs" name="province" required autocomplete="off" onchange="getLocations('province' , 'sdn-province')">
                        <option value="" class="">Choose a Province</option>
                    </select>
                </div>

                <div class="reg-form-divs">
                    <label for="" class="reg-labels">Address: Municipality</label>
                    <select id="sdn-city-select" class="reg-inputs" name="city" required autocomplete="off" onchange="getLocations('city', 'sdn-city')">
                        <option value="" class="">Choose a Municipality</option>
                    </select>
                </div>

                <div class="reg-form-divs">
                    <label for="" class="reg-labels">Address: Barangay</label>
                    <select id="sdn-brgy-select" class="reg-inputs" name="brgy" required autocomplete="off">
                        <option value="" class="">Choose a Barangay</option>
                    </select>
                </div>

                <div class="reg-form-divs">
                    <label for="" class="reg-labels">Zip Code</label>
                    <input id="sdn-zip-code" type="number" class="reg-inputs" required autocomplete="off">
                </div>

                <div class="reg-form-divs">
                    <label for="" class="reg-labels">Email Address</label>
                    <input id="sdn-email-address" type="email" class="reg-inputs"  required autocomplete="off">
                </div>

                <div class="reg-form-divs">
                    <label for="" class="reg-labels">Hospital Landline No.</label>
                    <input id="sdn-landline-no" type="text" class="reg-inputs" required autocomplete="off">
                </div>

                <div class="reg-form-divs">
                    <label for="" class="reg-labels">Hospital Mobile No.</label>
                    <input id="sdn-hospital-mobile-no" type="text" class="reg-inputs" required autocomplete="off">
                </div>

                <div class="reg-form-divs">
                    <label for="" class="reg-labels">Hospital Director</label>
                    <input id="sdn-hospital-director" type="text" class="reg-inputs" required autocomplete="off" onkeydown="return /[a-zA-Z\s.,-]/i.test(event.key)">
                </div>

                <div class="reg-form-divs">
                    <label for="" class="reg-labels">Hospital Director Mobile No.</label>
                    <input id="sdn-hospital-director-mobile-no" type="text" class="reg-inputs" required autocomplete="off">
                </div>

                <div class="reg-form-divs">
                    <label for="" class="reg-labels">Point Person</label>
                    <input id="sdn-point-person" type="text" class="reg-inputs" required autocomplete="off" onkeydown="return /[a-zA-Z\s.,-]/i.test(event.key)">
                </div>

                <div class="reg-form-divs">
                    <label for="" class="reg-labels">Point Person Mobile No.</label>
                    <input id="sdn-point-person-mobile-no" type="text" class="reg-inputs" required autocomplete="off">
                </div>

                <!-- <button id="register-confirm-btn" type="button" class="btn btn-success">Success</button> -->
                <div class="register-confirm-div">
                    <button id="register-confirm-btn" type="button" class="btn btn-success">Register</button>
                </div>
            </form>

            <form class="sub-content-authorization-form">
                
                            
                <div class="autho-form-divs">
                    <label for="" class="reg-labels">Hospital Code</label>
                    <input id="sdn-autho-hospital-code-id" type="number" class="reg-inputs" autocomplete="off">
                </div>

                <div class="autho-form-divs">
                    <label for="" class="reg-labels">Cipher Key</label>
                    <input id="sdn-autho-cipher-key-id" type="text" class="reg-inputs" autocomplete="off">
                </div>

                <div class="autho-form-divs">
                    <label for="" class="reg-labels">Last Name</label>
                    <input id="sdn-autho-last-name-id" type="text" class="reg-inputs" autocomplete="off">
                </div>

                <div class="autho-form-divs">
                    <label for="" class="reg-labels">First Name</label>
                    <input id="sdn-autho-first-name-id" type="text" class="reg-inputs" autocomplete="off">
                </div>

                <div class="autho-form-divs">
                    <label for="" class="reg-labels">Middle Name</label>
                    <input id="sdn-autho-middle-name-id" type="text" class="reg-inputs" autocomplete="off">
                </div>

                <div class="autho-form-divs">
                    <label for="" class="reg-labels">Extension Name</label>
                    <input id="sdn-autho-ext-name-id" type="text" class="reg-inputs" autocomplete="off">
                </div>

                <div class="autho-form-divs">
                    <label for="" class="reg-labels">Username</label>
                    <input id="sdn-autho-username" type="text" class="reg-inputs" autocomplete="off">
                </div>

                <div class="autho-form-divs">
                    <label for="" class="reg-labels">Password</label>
                    <input id="sdn-autho-password" type="password" class="reg-inputs" autocomplete="off">
                </div>

                <div class="autho-form-divs">
                    <label for="" class="reg-labels">Confirm Password</label>
                    <input id="sdn-autho-confirm-password" type="password" class="reg-inputs" autocomplete="off">
                </div>

                <!-- <button id="register-confirm-btn" type="button" class="btn btn-success">Success</button> -->
                <div class="authorization-confirm-div">
                    <button id="authorization-confirm-btn" type="button" class="btn btn-success">Verify</button>
                </div>
            </form>
        </div>

        
        <div class="sdn-loading-div">
            <div id="sdn-loading-div-2">
                <h3></h3>
            </div>
            
            <h3>SENDING OTP TO YOUR EMAIL...</h3>
            <div class="loader"></div>
        </div>

        <div class="otp-modal-div">
            <div id="email-sent-div">
                <h3>OTP <span>Email sent</span></h3>
                <button id="sdn-otp-modal-btn-close" class="sdn-otp-modal-btn-close">X</button>
            </div>
            
            <div id="input-otp-div">
                <h3>INPUT THE OTP</h3>
            </div>

            <div id="otp-inputs-div">
                <div class="otp-inputs">
                    <input type="number" id="otp-input-1" placeholder="-">
                </div>
                <div class="otp-inputs">
                    <input type="number" id="otp-input-2" placeholder="-">
                </div>
                <div class="otp-inputs">
                    <input type="number" id="otp-input-3" placeholder="-">
                </div>
                <div class="otp-inputs">
                    <input type="number" id="otp-input-4" placeholder="-">
                </div>
                <div class="otp-inputs">
                    <input type="number" id="otp-input-5" placeholder="-">
                </div>
                <div class="otp-inputs">
                    <input type="number" id="otp-input-6" placeholder="-">
                </div>
            </div>

            <div id="resend-otp-div">
                <button id="resend-otp-btn">Resend OTP</button>
                <label id="resend-otp-timer">00:00</label>
            </div>

            <div id="otp-verify-div">
                <button id="otp-verify-btn" class="otp-verify-btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 h-full rounded">Verify</button>
            </div>
            
        </div>

        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <div id="modal-header-2">
                        <h5 id="modal-title" class="modal-title" id="exampleModalLabel">Verification</h5>
                        <i id="modal-icon" class="fa-solid fa-circle-check"></i>
                        <!-- <i class="fa-solid fa-circle-exclamation"></i> -->
                    </div>
                    <button type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modal-body" class="modal-body">
                    Verified OTP 
                </div>
                <div class="modal-footer">
                    <button id="ok-modal-btn" type="button" data-bs-dismiss="modal">OK</button>
                    <button id="yes-modal-btn" type="button" data-bs-dismiss="modal">Yes</button>
                </div>
                </div>
            </div>
        </div>
    </div>

    <div id="overlay"></div>
    <i id="tutorial-btn" class="fa-regular fa-circle-question"></i>

    <!-- <div class="modal fade" id="tutorial-modal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div id="tutorial_dialog" class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h1 id="tutorial_title" class="modal-title fs-5">Welcome to BataanGHMC Service Delivery Network Tutorial</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="tutorial_body">
                First, click sign in to register your RHU
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
        </div>
    </div> -->

    <div id="tutorial-carousel" class="carousel slide">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#tutorial-carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#tutorial-carousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#tutorial-carousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="./assets/tutorial_images/login_imgs/login_tutorial_1.png" class="d-block w-100" alt="image">
            </div>
            <div class="carousel-item">
                <img src="./assets/tutorial_images/login_imgs/login_tutorial_2.png" class="d-block w-100" alt="image">
            </div>
            <div class="carousel-item">
                <img src="./assets/tutorial_images/login_imgs/login_tutorial_4.png" class="d-block w-100" alt="image">
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

    

    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>   
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.1/js/bootstrap.bundle.min.js"></script>


    <script src="./index.js?v=<?php echo time(); ?>"></script>
    <script src="./js_2/location.js?v=<?php echo time(); ?>"></script>
    <script src="./js_2/sdn_reg.js?v=<?php echo time(); ?>"></script>
    <script src="./js_2/verify_otp.js?v=<?php echo time(); ?>"></script>
    <script src="./js_2/sdn_autho.js?v=<?php echo time(); ?>"></script>
    <script src="./js_2/resend_otp.js?v=<?php echo time(); ?>"></script>
    <script src="./js_2/closed_otp.js?v=<?php echo time(); ?>"></script>
    
    <script type="text/javascript">
        particlesJS("particles-js", {"particles":{"number":{"value":6,"density":{"enable":true,"value_area":800}},"color":{"value":"#4F6F52"},"shape":{"type":"polygon","stroke":{"width":0,"color":"#000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.3,"random":true,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":160,"random":false,"anim":{"enable":true,"speed":10,"size_min":40,"sync":false}},"line_linked":{"enable":false,"distance":200,"color":"#ffffff","opacity":1,"width":2},"move":{"enable":true,"speed":8,"direction":"none","random":false,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":1200}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":false,"mode":"grab"},"onclick":{"enable":false,"mode":"push"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":400,"size":40,"duration":2,"opacity":8,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true});
        var count_particles, stats, update; stats = new stats; 
        stats.setMode(0); stats.domElement.style.position = 'absolute'; stats.domElement.style.left = '0px'; stats.domElement.style.top = '0px'; document.body.appendChild(stats.domElement); count_particles = document.querySelector('.js-count-particles'); update = function() { stats.begin(); stats.end(); if (window.pJSDom[0].pJS.particles && window.pJSDom[0].pJS.particles.array) { count_particles.innerText = window.pJSDom[0].pJS.particles.array.length; } requestAnimationFrame(update); }; requestAnimationFrame(update);;
    </script>
</body>
</html>