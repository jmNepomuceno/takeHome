<?php 
    session_start();
    include('../database/connection2.php');
    date_default_timezone_set('Asia/Manila');

    if ($_SESSION['user_name'] === 'admin'){
        $user_name = 'Bataan General Hospital and Medical Center';
    }else{
        $user_name = $_SESSION['hospital_name'];
    }

    $table_header_arr = ['Hospital Name' , 'Hospital Code', 'Verified', 'Number of Users' ,  'Contact Information', 'Hospital Director' , 'Point Person'];
    $sub_table_header_arr = ['Last Name' , 'First Name', 'Middle Name', 'Username', 'Password', 'Active', 'Action'];

    $sql = "SELECT classifications FROM classifications";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($data); echo '</pre>';

    $classification_arr = array();
    for($i = 0; $i < count($data); $i++){
        array_push($classification_arr, $data[$i]['classifications']);
    }

    // fetch sdn hospitals and sdn users
    // $sql = "SELECT * FROM sdn_hospital ORDER BY hospital_name ASC";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();
    // $data_sdn_hospitals = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($data_sdn_hospitals); echo '</pre>';
    // echo count($data_sdn_hospitals);
    
    $sql = "SELECT * FROM sdn_users";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data_sdn_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($data); echo '</pre>';
    
    // retrieve all the hospital code that has 2 users
    $sql = "SELECT hospital_code FROM sdn_users WHERE user_count=2";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data_sdn_users_count2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT hospital_code FROM sdn_users WHERE user_count=1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data_sdn_users_count1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($data_sdn_users_count2); echo '</pre>';

    $users_count_2_hcode = array();
    $users_count_1_hcode = array();

    for($i = 0; $i < count($data_sdn_users_count2); $i++){
        array_push($users_count_2_hcode, $data_sdn_users_count2[$i]['hospital_code']);
    }

    for($i = 0; $i < count($data_sdn_users_count1); $i++){
        array_push($users_count_1_hcode, $data_sdn_users_count1[$i]['hospital_code']);
    }

    $sql = "SELECT * FROM sdn_users WHERE hospital_code=9312";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $users_curr_hospitals = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // print_r($users_curr_hospitals);
    // echo count($users_curr_hospitals);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <?php require "../header_link.php" ?>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        .custom-modal-width {
            max-width: 80vw; /* Adjust the width as per your requirements */
            width: 100%;
        }
    </style>
</head>
<body class="h-screen overflow-hidden">
    <header class="header-div w-full h-[50px] flex flex-row justify-between items-center bg-[#1f292e]">
        <div class="w-[30%] h-full flex flex-row justify-start items-center">
            <div id="side-bar-mobile-btn" class="side-bar-mobile-btn w-[10%] h-full flex flex-row justify-center items-center cursor-pointer">
                <i class="fa-solid fa-bars text-white text-4xl"></i>
            </div>
            <h1 id="sdn-title-h1" class="text-white text-xl ml-2 cursor-pointer"> Service Delivery Network</h1>
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
                    <h1 class="text-white text-base hidden sm:block"><?php echo $user_name ?> |   <?php echo $_SESSION['last_name'] ?>  <?php echo $_SESSION['first_name']  ?> <?php echo $_SESSION['middle_name']  ?>
                    </h1> 
                </div>
                <div class="w-[5%] h-full flex flex-col justify-center items-center sm:m-1">
                    <i class="fa-solid fa-caret-down text-white text-xs"></i>
                </div>
            </div>
        </div>
    </header>  

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

    <div class="w-full h-full border border-black flex flex-wrap justify-start items-start">
        <div class="hospital-users-div relative w-[250px] h-[200px] mt-[5%] ml-[5%] rounded-2xl bg-[#1f292e] flex flex-col justify-between items-center">
            <i class="fa-solid fa-users text-white text-[5rem] mt-10"></i>
            <label id="hospital-users-lbl" class="tile-title-div w-[90%] h-[50px] border mb-3 rounded-xl text-white text-center flex flex-col justify-center items-center p-2 m-2 text-[0.9rem] font-bold   cursor-pointer opacity-30 hover:opacity-100 delay-150 duration-150" data-bs-toggle="modal" data-bs-target="#myModal-hospitalAndUsers">
                Hospitals and Users
            </label>
        </div>

        <div class="add-classification-div relative w-[250px] h-[200px] mt-[5%] ml-[5%] rounded-2xl bg-[#1f292e] flex flex-col justify-between items-center">
            <i class="fa-solid fa-file-import text-white text-[6rem] mr-4 mt-4"></i>
            <label id="add-classification-lbl" class="tile-title-div w-[90%] h-[50px] border mb-3 rounded-xl text-white text-center flex flex-col justify-center items-center p-2 m-2 text-[0.9rem] font-bold   cursor-pointer opacity-30 hover:opacity-100 delay-150 duration-150" data-bs-toggle="modal" data-bs-target="#myModal-add-classification">
                Add Patient's Classification
            </label>
        </div>

        
    </div>



    <!-- Modal -->
    <div class="modal fade" id="myModal-add-classification" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header flex flex-row justify-between items-center">
                <div class="flex flex-row justify-between items-center">
                    <h5 id="modal-title-main" class="modal-title-main" id="exampleModalLabel">Add New Patient's Classification</h5>
                    <i id="modal-icon" class="fa-solid fa-triangle-exclamation ml-2"></i>
                    <!-- <i class="fa-solid fa-circle-check"></i> -->
                </div>
                <button type="button" class="close text-3xl" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modal-body-main" class="modal-body-main h-auto">
                <div class="add-classification-div w-full h-full">
                    <!-- <input  type="text" id="add-classification-txt" class="border-2 border-black" placeholder="New Classification" /> -->
                    <!-- <button id="add-classification-btn" class="border-2 border-black">Add</button> -->

                    <h2 class="w-full h-[40px] flex flex-row justify-start items-center ml-4 font-bold text-lg border-none">Current Patient's Classifications</h2>
                    <div class="w-full flex flex-col justify-start items-center">
                        <div id="populate-patclass-div" class="w-full h-auto flex flex-wrap justify-center items-center">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="delete-classification-btn" type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2 opacity-50 pointer-events-none" data-bs-dismiss="modal">Delete</button>
                <button id="add-classification-btn" type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2 opacity-50 pointer-events-none" data-bs-dismiss="modal">Add</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal-hospitalAndUsers" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom-modal-width" role="document">
            <div class="modal-content">
            <div class="modal-header flex flex-row justify-between items-center">
                <div class="w-full flex flex-row justify-start items-center">
                    <button id="modal-title-main" class="modal-title-main btn btn-success" id="exampleModalLabel">Hospitals/BHS/RHU</button>
                </div>
                <button type="button" class="close text-3xl" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modal-body-main" class="modal-body-main h-[750px]">
                <div class="add-classification-div">
                    <table id="main-table">
                        <thead class="">
                            <tr>
                                <?php for($i = 0; $i < count($table_header_arr); $i++) { ?>
                                    <!-- <th class="border border-[#b3b3b3] p-3 bg-[#333333] text-white text-lg">  -->
                                    <th class="border border-[#b3b3b3] p-3 bg-[#333333] text-white text-lg"> 
                                        <div class="flex flex-row justify-center items-center w-full h-full">
                                            <?php echo $table_header_arr[$i] ?>
                                            <?php if($i < 3){?>
                                                <div class="flex flex-col justify-center items-center"> 
                                                    <i id="sort-up-btn-id-<?php echo $i; ?>" class="sort-up-btn fa-solid fa-caret-up ml-2 mt-1 cursor-pointer opacity-30 hover:opacity-100"></i>
                                                    <i id="sort-down-btn-id-<?php echo $i; ?>" class="sort-down-btn fa-solid fa-caret-down ml-2 -mt-2 cursor-pointer"></i>
                                                </div>
                                            <?php }?>
                                        </div>
                                    </th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <!-- Table body -->
                        <tbody class="table-body border border-[#b3b3b3] w-full">
                            <?php 
                                $sql = "SELECT * FROM sdn_hospital ORDER BY hospital_name ASC";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();
                                $data_sdn_hospitals = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <?php for($i = 0; $i < count($data_sdn_hospitals); $i++) { ?>
                                <?php
                                    // $hospital_isVerified    
                                    
                                    if($data_sdn_hospitals[$i]['hospital_isVerified'] === 1){
                                        $hospital_isVerified = 'Verified';
                                    }else{
                                        $hospital_isVerified = 'Not Verified';
                                    }

                                    $number_users = 0;
                                    

                                    if(in_array($data_sdn_hospitals[$i]['hospital_code'], $users_count_1_hcode)){
                                        $number_users = 1;
                                    }

                                    if(in_array($data_sdn_hospitals[$i]['hospital_code'], $users_count_2_hcode)){
                                        $number_users = 2;
                                    }

                                    $color_style = "#fffff";
                                    $sub_color_style = "#fffff";
                                    if($i % 2 == 0){
                                        $color_style = "#999999";
                                        $sub_color_style = "#cccccc";   
                                    }

                                    $hospital_mobile_number = $data_sdn_hospitals[$i]['hospital_mobile'];

                                    // echo $data_sdn_hospitals[$i]['hospital_code'];
                                    // echo '</br>';
                                    $users_curr_hospitals = "";
                                    $sql = "SELECT * FROM sdn_users WHERE hospital_code=:code";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->bindParam(':code', $data_sdn_hospitals[$i]['hospital_code'], PDO::PARAM_INT);
                                    $stmt->execute();
                                    $users_curr_hospitals = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    // print_r($users_curr_hospitals);
                                    // echo count($users_curr_hospitals);
                                    // echo $users_curr_hospitals[0]['user_firstname'];
                                ?>
                                <tr class="table-tr h-[70px] w-full border border-[#b3b3b3] text-base bg-[<?php echo $color_style ?>] font-medium">
                                    <td class="border-r border-[#b3b3b3] w-[450px] h-full"> <?php echo $data_sdn_hospitals[$i]['hospital_name'] ?></td>
                                    <td class="border-r border-[#b3b3b3] w-[200px] h-full"> <?php echo $data_sdn_hospitals[$i]['hospital_code'] ?></td>
                                    <td class="border-r border-[#b3b3b3] w-[130px] h-full"> <?php echo $hospital_isVerified ?></td>
                                    <!-- <td class="w-[300px] border-r border"> <?php echo $hospital_mobile_number ?> </td>  -->
                                    <td class="border-r border-[#b3b3b3] w-[130px] h-full text-center relative"> 
                                        <div class="number_users w-[90%] h-full flex flex-row justify-center items-center"> <?php echo $number_users ?> </div>
                                        
                                        <div class="hidden breakdown-div ml-4 w-[550px] h-[300px] bg-[<?php echo $sub_color_style ?>] rounded flex flex-row justify-center items-center overflow-hidden">
                                            <table class="w-[97%] h-[95%] text-center rounded">
                                                <thead>
                                                    <tr>
                                                    <?php for($j = 0; $j < count($sub_table_header_arr); $j++) { ?>
                                                        <th class="border border-[#b3b3b3] p-3 bg-[#333333] text-white text-xs"> <?php echo $sub_table_header_arr[$j] ?></th>
                                                    <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(count($users_curr_hospitals) === 2){?>
                                                        <?php for($x = 0; $x < 2; $x++) { ?>
                                                            <?php $user_firstName_var =$users_curr_hospitals[$x]['user_firstname']; ?>
                                                            <tr class="h-[50%] w-full border border-[#b3b3b3] text-base bg-[<?php echo $color_style ?>] font-medium">
                                                                <td class="border-r border-[#b3b3b3] w-[100px] text-sm"> 
                                                                    <input type="text" class="edit-users-info w-[90%] outline-none h-[30px] text-center text-sm bg-transparent pointer-events-none" value="<?php echo $users_curr_hospitals[$x]['user_lastname'] ?>" />
                                                                </td>
                                                                <td class="border-r border-[#b3b3b3] w-[100px] text-sm"> 
                                                                    <input type="text" class="edit-users-info w-[90%] h-full outline-none h-[30px] text-center text-sm bg-transparent pointer-events-none text-pretty" value="<?php echo $users_curr_hospitals[$x]['user_firstname']; ?>" />
                                                                </td>
                                                                <td class="border-r border-[#b3b3b3] w-[100px] text-sm"> 
                                                                    <input type="text" class="edit-users-info w-[90%] outline-none h-[30px] text-center text-sm bg-transparent pointer-events-none" value= <?php echo $users_curr_hospitals[$x]['user_middlename'] ?> />
                                                                </td>
                                                                <td class="border-r border-[#b3b3b3] w-[100px] text-sm"> 
                                                                    <input type="text" class="edit-users-info w-[90%] outline-none h-[30px] text-center text-sm bg-transparent pointer-events-none" value= <?php echo $users_curr_hospitals[$x]['username'] ?> />
                                                                </td>
                                                                <td class="border-r border-[#b3b3b3] w-[100px] text-sm"> 
                                                                    <input type="text" class="edit-users-info w-[90%] outline-none h-[30px] text-center text-sm bg-transparent pointer-events-none" value= <?php echo $users_curr_hospitals[$x]['password'] ?> />
                                                                </td>
                                                                <?php if($users_curr_hospitals[$x]['user_isActive'] === 0) {?>
                                                                    <td class="border-r border-[#b3b3b3] w-[100px] text-sm"> Inactive</td>
                                                                <?php }else{ ?>
                                                                    <td class="border-r border-[#b3b3b3] w-[100px] text-sm"> Active</td>
                                                                <?php } ?>
                                                                <td class="border-r border-[#b3b3b3] w-[100px] text-sm">
                                                                    <button type="button" class="edit-info-btn bg-[#0d6efd] w-[90%] h-[35px] text-white rounded-md p-1">Edit</button>
                                                                    <button type="button" class="hidden cancel-info-btn bg-[#6c757d] w-[90%] h-[35px] text-white rounded-md p-1 mt-2">Close</button>
                                                                </td>
                                                                <input class="hcode-edit-info" type="hidden" name="hcode-edit-info" value=<?php echo $users_curr_hospitals[$x]['hospital_code'] ?> />
                                                            </tr>
                                                        <?php }?>
                                                    <?php } else if(count($users_curr_hospitals) === 1){ ?>
                                                        <tr class="h-[50%] w-full border border-[#b3b3b3] text-base bg-[<?php echo $color_style ?>] font-medium">
                                                        <td class="border-r border-[#b3b3b3] w-[100px] text-sm"> 
                                                                    <input type="text" class="edit-users-info w-[90%] outline-none h-[30px] text-center text-sm bg-transparent pointer-events-none" value= <?php echo $users_curr_hospitals[0]['user_lastname'] ?> />
                                                                </td>
                                                                <td class="border-r border-[#b3b3b3] w-[100px] text-sm"> 
                                                                    <input type="text" class="edit-users-info w-[90%] outline-none h-[30px] text-center text-sm bg-transparent pointer-events-none text-pretty" value= <?php echo $users_curr_hospitals[0]['user_firstname'] ?> />
                                                                </td>
                                                                <td class="border-r border-[#b3b3b3] w-[100px] text-sm"> 
                                                                    <input type="text" class="edit-users-info w-[90%] outline-none h-[30px] text-center text-sm bg-transparent pointer-events-none" value= <?php echo $users_curr_hospitals[0]['user_middlename'] ?> />
                                                                </td>
                                                                <td class="border-r border-[#b3b3b3] w-[100px] text-sm"> 
                                                                    <input type="text" class="edit-users-info w-[90%] outline-none h-[30px] text-center text-sm bg-transparent pointer-events-none" value= <?php echo $users_curr_hospitals[0]['username'] ?> />
                                                                </td>
                                                                <td class="border-r border-[#b3b3b3] w-[100px] text-sm"> 
                                                                    <input type="text" class="edit-users-info w-[90%] outline-none h-[30px] text-center text-sm bg-transparent pointer-events-none" value= <?php echo $users_curr_hospitals[0]['password'] ?> />
                                                                </td>
                                                            <?php if($users_curr_hospitals[0]['user_isActive'] === 0) {?>
                                                                <td class="border-r border-[#b3b3b3] w-[100px] text-sm"> Inactive</td>
                                                            <?php }else{ ?>
                                                                <td class="border-r border-[#b3b3b3] w-[100px] text-sm"> Active</td>
                                                            <?php } ?>
                                                            <td class="border-r border-[#b3b3b3] w-[100px] text-sm">
                                                                <button type="button" class="edit-info-btn bg-[#0d6efd] w-[90%] h-[35px] text-white rounded-md p-1">Edit</button>
                                                                <button type="button" class="hidden cancel-info-btn bg-[#6c757d] w-[90%] h-[35px] text-white rounded-md p-1 mt-2">Close</button>
                                                            </td>
                                                            <input class="hcode-edit-info" type="hidden" name="hcode-edit-info" value=<?php echo $users_curr_hospitals[0]['hospital_code'] ?> />
                                                        </tr>
                                                    <?php } ?>
                                                        
                                                </tbody>
                                            </table>
                                        </div>

                                        <i class="see-more-btn absolute top-5 right-2 text-2xl fa-regular fa-square-caret-down cursor-pointer"></i>
                                    </td>
                                    <!-- <td class="w-[50px]"></td>  -->

                                    <td class="border-r border-[#b3b3b3] w-[300px] h-full">
                                        <div class="w-full h-full flex flex-col justify-center items-center">
                                            <label>Landline: <?php echo $data_sdn_hospitals[$i]['hospital_landline'] ?></label>
                                            <label>Mobile: <?php echo $data_sdn_hospitals[$i]['hospital_mobile'] ?></label>
                                        </div>
                                    </td>
                                    <td class="border-r border-[#b3b3b3] w-[200px] h-full"> <?php echo $data_sdn_hospitals[$i]['hospital_director'] ?></td>
                                    <td class="border-r border-[#b3b3b3] w-[200px] h-full"> <?php echo $data_sdn_hospitals[$i]['hospital_point_person'] ?></td>

                                    
                                    <!-- end rendering - sakto hahaha gl gl  -->
                                </tr>
                            <?php } ?>
                            <!-- Add more rows as needed  -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- <div class="modal-footer">
                <button id="add-classification-btn" type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2 opacity-50 pointer-events-none" data-bs-dismiss="modal">Add</button>
            </div> -->
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal-prompt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header flex flex-row justify-between items-center">
                <div class="flex flex-row justify-between items-center">
                    <h5 id="modal-title-incoming" class="modal-title-incoming" id="exampleModalLabel">Successed</h5>
                    <i id="modal-icon" class="fa-solid fa-circle-check ml-2"></i>
                    <!-- <i class="fa-solid fa-circle-check"></i> -->
                </div>
                <button type="button" class="close text-3xl" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modal-body-incoming" class="modal-body-incoming ml-2">
                Edit Successfully
            </div>
            <div class="modal-footer">
                <button id="ok-modal-btn-incoming" type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-toggle="modal" data-bs-target="#myModal-prompt">OK</button>
            </div>
            </div>
        </div>
    </div>

     <!-- Modal -->
     <div class="modal fade" id="myModal-success" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header flex flex-row justify-between items-center">
                <div class="flex flex-row justify-between items-center">
                    <h5 id="modal-title-incoming" class="modal-title-incoming" id="exampleModalLabel">Successed</h5>
                    <i id="modal-icon" class="fa-solid fa-circle-check ml-2"></i>
                    <!-- <i class="fa-solid fa-circle-check"></i> -->
                </div>
                <button type="button" class="close text-3xl" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modal-body-incoming-success" class="modal-body-incoming ml-2">
            </div>
            <div class="modal-footer">
                <button id="ok-modal-btn-incoming" type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-toggle="modal" data-bs-target="#myModal-success">OK</button>
            </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="../js_2/admin.js?v=<?php echo time(); ?>"></script>
</body>
</html>