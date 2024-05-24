<?php
    session_start();
    include('../database/connection2.php');

    $sql = "SELECT classifications FROM classifications";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($data); echo '</pre>';

    if ($_SESSION['user_name'] === 'admin'){
        $user_name = 'Bataan General Hospital and Medical Center';
    }else{
        $user_name = $_SESSION['hospital_name'];
    }

    $classification_arr = array();
    for($i = 0; $i < count($data); $i++){
        array_push($classification_arr, $data[$i]['classifications']);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SDN</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    
    
    <link rel="stylesheet" href="../css/patient_register_form2.css">

</head>
<body>
    <input id="tertiary-case" type="hidden" name="tertiary-case" value="">
    <input id="hpercode-input" type="hidden" name="hpercode-input" value="">
    <input id="hpatcode-input" type="hidden" name="hpatcode-input" value=<?php echo $_SESSION["hospital_code"]?>>

    <main id="patient-reg-form-div">

        <div id="upper-part-div">
            <aside class="important-btns">
                <!-- FUNCTION BUTTONS -->
                <div class="patient-form-btns">       
                    <select id="classification-dropdown">
                        <option value="">Classification</option>
                        <?php for($i = 0; $i < count($classification_arr); $i++){ ?>
                            <!-- <option class="cursor-pointer" value=<?php echo strtolower($classification_arr[$i]) ?>><?php echo $classification_arr[$i] ?></option> -->
                            <option class="cursor-pointer" value=<?php echo $classification_arr[$i] ?>><?php echo $classification_arr[$i] ?></option>
                        <?php }?>
                    </select>

                    <button id="add-patform-btn-id">Add</button>
                    <button id="clear-patform-btn-id"">Clear</button>

                </div>
            </aside>
        
            <div id="privacy-reminder-div">
                <p> <span>Notice: </span>For data integrity purposes. Changing of Name and Birthday will be restricted. Please send an email to bataan.bghmc.ihomp@gmail.com for your request of patient name change. </p>
                <button>x</button>
            </div>

            <button id="check-if-registered-btn">
                <i  class="fa-solid fa-magnifying-glass"></i>
                <h3 id="check-if-registered-h3">Check if the patient is already registered</h3>
            </button>
        </div>
        
        <div id="check-if-registered-div">
            <div id="search-upper-div">

                <div id="search-upper-sub-div">
                    <form action="">

                        <div id="lname-input-div">
                            <label for="search-lname"> Last Name</label>
                            <input id="search-lname" type="text" name="search-lname" autocomplete="off" placeholder="Last Name">
                        </div>

                        <div id="fname-input-div">
                            <label for="search-fname"> First Name</label>
                            <input id="search-fname" type="text" sname="search-fname" autocomplete="off" placeholder="First Name">
                        </div>

                        <div id="mname-input-div">
                            <label for="search-mname"> Middle Name</label>
                            <input id="search-mname" type="text" name="search-mname" autocomplete="off" placeholder="Middle Name">
                        </div>

                    </form>
                </div>

                <button id="search-patient-btn">
                    Search
                </button>

                <div id="name-bday-div">
                    <h3>Name</h3>
                    <h3>Birthday</h3>
                </div>

            </div>
            <div id="search-result-div">
                <!-- <h1 id="no-pat-found">No Patient Found</h1> -->
                <!-- <div id="search-sub-div">
                    <div id="upper-part-sub-div">
                        <h1 id="pat-id-h1">Patient ID: 292719</h1>
                        <div>
                            <h1>9/22/2023</h1>
                            <span class="fa-solid fa-user"></span>
                        </div>
                    </div>
                    <div id="lower-part-sub-div">
                        <h3 id="pat-name">Nepomuceno, John Marvin Gomez</h3>
                    </div>
                </div> -->

            </div>
        </div>

        <div id="main-div">

            <form action="">
                <div id="patient-reg-form-div-1">
                    <!-- PERSONAL INFORMATION DIVSION -->
                    <div id="patient-form-sub-div-1">
                        <div id="form-title-div-1">
                            <h3>Personal Information</h3>
                        </div>
                        <!-- <div class="w-[98%] h-full border-2 border-[#bfbfbf] rounded-lg"> -->
                        <div id="form-body-div-1">

                            <div class="form-sub-divs-col">
                                <div class="">
                                    <label for="hperson-last-name"> Last Name </label>
                                </div>
                                <input id="hperson-last-name" class="input-txt-classes" type="text" name="hperson-last-name" autocomplete="off" placeholder="Dela Cruz" required>
                            </div>

                            <div class="form-sub-divs-col">
                                <div>
                                    <label class="text-base ml-3" for="hperson-first-name"> First Name </label>
                                </div>
                                <input id="hperson-first-name" class="input-txt-classes" type="text" name="hperson-first-name" autocomplete="off" placeholder="Juan" required>
                            </div>

                            <div class="form-sub-divs-row">
                                
                                <div class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-middle-name"> Middle Name </label>
                                    </div>
                                    <input id="hperson-middle-name" class="input-txt-classes" type="text" name="hperson-middle-name" autocomplete="off" placeholder="Santos" required>
                                </div>
                                
                                <div class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-ext-name"> Name Ext. </label>
                                    </div>
                                    <input id="hperson-ext-name" class="input-txt-classes-non" type="text" name="hperson-ext-name" autocomplete="off" placeholder="Jr.">
                                </div>
                            </div>

                            <div class="form-sub-divs-row">

                                <div class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-birthday"> Birthday </label>
                                    </div>
                                    <input id="hperson-birthday" class="input-txt-classes" type="date" name="hperson-birthday" autocomplete="off" style="color:#666666" required>
                                </div>
                                
                                <div class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-age"> Age </label>
                                    </div>
                                    <input id="hperson-age" class="input-txt-classes-non" tabindex="-1" disabled="disabled" type="number" name="hperson-age" autocomplete="off">
                                </div>

                            </div>

                            <div class="form-sub-divs-row">

                                <div class="form-sub-divs-row-right">
                                    <div>
                                        <label for="hperson-gender"> Gender </label>
                                    </div>
                                    <select name="hperson-gender" class="input-txt-classes" id="hperson-gender" autocomplete="off" required>
                                        <option value="">Choose</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                
                                <div class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-civil-status"> Civil Status </label>
                                    </div>
                                    <select name="hperson-civil-status" class="input-txt-classes" id="hperson-civil-status" autocomplete="off" required>
                                        <option value="">Choose</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Divorced">Divorced</option>
                                        <option value="Widowed">Widowed</option>
                                    </select>
                                </div>

                            </div>

                            <div class="form-sub-divs-col">
                                <div>
                                    <label for="hperson-religion"> Religion </label>
                                </div>
                                <input id="hperson-religion" class="input-txt-classes" type="text" name="hperson-religion" autocomplete="off" placeholder="ex. Roman Catholic" required>
                            </div>

                            <div class="form-sub-divs-col">
                                <div>
                                    <label for="hperson-occupation"> Occupation </label>
                                </div>
                                <input id="hperson-occupation" class="input-txt-classes-non" type="text" name="hperson-occupation" autocomplete="off" placeholder="ex. Doctor" required >
                            </div>

                            <div class="form-sub-divs-row">

                                <div id="fifty" class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-nationality"> Nationality </label>
                                    </div>
                                    <input id="hperson-nationality" class="input-txt-classes" type="text" name="hperson-nationality" autocomplete="off" placeholder="ex. Filipino" required>
                                </div>
                                
                                <div id="fifty" class="form-sub-divs-row-right">
                                    <div>
                                        <label for="hperson-passport-no"> Passport No. </label>
                                    </div>
                                    <input id="hperson-passport-no" class="input-txt-classes-non" type="text" name="hperson-passport-no" autocomplete="off" required>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- OTHERS -->
                    <div id="patient-form-sub-div-2">
                        <div id="form-title-div-2">
                            <h3>Others</h3>
                        </div>
                        <div id="form-body-div-2">
                            <div class="form-sub-divs-row">
                                <div id="fifty" class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-hospital-no"> Hospital No. </label>
                                    </div>
                                    <input id="hperson-hospital-no" class="input-txt-classes" type="number" name="hperson-hospital-no"  autocomplete="off" value=<?php echo $_SESSION['hospital_code'] ?> style="pointer-events: none; background:#bfbfbf">
                                </div>
                                
                                <div id="fifty" class="form-sub-divs-row-right">
                                    <div>
                                        <label for="hperson-phic">PHIC </label>
                                    </div>
                                    <input id="hperson-phic" class="input-txt-classes" type="text" name="hperson-phic" autocomplete="off" placeholder="PhilHealth Number" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="patient-reg-form-div-2">
                    <div id="patient-form-sub-div-3">
                        <div id="form-title-div-3">
                            <h3>Permanent Address</h3>
                        </div>
                        <div id="form-body-div-3">

                            <div class="form-sub-divs-row">
                                <div id="fifty" class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-house-no-pa"> House No./Lot/Bldg </label>
                                    </div>
                                    <input id="hperson-house-no-pa" class="input-txt-classes" type="text" name="hperson-house-no-pa" autocomplete="off" placeholder="Lot 1" required>
                                </div>
                                
                                <div id="fifty" class="form-sub-divs-row-right">
                                    <div>
                                        <label for="hperson-street-block-pa"> Street/Block </label>
                                    </div>
                                    <input id="hperson-street-block-pa" class="input-txt-classes" type="text" name="hperson-street-block-pa" autocomplete="off" placeholder="Block 1" required>
                                </div>
                            </div>

                            <div class="form-sub-divs-row">
                                <div id="fifty" class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-region-select-pa"> Region </label>
                                    </div>
                                    <select id="hperson-region-select-pa" class="input-txt-classes" required onchange="getLocations('region', 'pa-region')" name="region" autocomplete="off" required>
                                        <option value="" class="">Choose a Region</option>
                                        <?php 
                                            $stmt = $pdo->query('SELECT region_code, region_description from region');
                                            while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                echo '<option value="' , $data['region_code'] , '">' , $data['region_description'] , '</option>';
                                            }                                        
                                        ?>
                                    </select>
                                </div>
                                
                                <div id="fifty" class="form-sub-divs-row-right">
                                    <div>
                                        <label for="hperson-province-select-pa"> Province </label>
                                    </div>
                                    <select id="hperson-province-select-pa"  class="input-txt-classes" required onchange="getLocations('province', 'pa-province')" name="province" required>
                                        <option value="" class="">Choose a Province</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-sub-divs-row">
                                <div id="fifty" class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-city-select-pa"> Municipality / City </label>
                                    </div>
                                    <select id="hperson-city-select-pa" class="input-txt-classes" required onchange="getLocations('city', 'pa-city')" name="city" required>
                                        <option value="" class="">Choose a Municipality</option>
                                    </select>
                                </div>
                                
                                <div id="fifty" class="form-sub-divs-row-right">
                                    <div>
                                        <label for="hperson-brgy-select-pa"> Barangay </label>
                                    </div>
                                    <select id="hperson-brgy-select-pa" class="input-txt-classes" required name="brgy" required>
                                        <option value="" class="">Choose a Barangay</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-sub-divs-row">
                                <div id="fifty" class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-home-phone-no-pa"> Home Phone No. </label>
                                    </div>
                                    <input id="hperson-home-phone-no-pa" class="input-txt-classes-non" type="text" name="hperson-home-phone-no-pa" autocomplete="off">
                                </div>
                                
                                <div id="fifty" class="form-sub-divs-row-right">
                                    <div>
                                        <label for="hperson-mobile-no-pa"> Mobile Phone No. </label>
                                    </div>
                                    <input id="hperson-mobile-no-pa" class="input-txt-classes" type="text" name="hperson-mobile-no-pa" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="form-sub-divs-row" >
                                <div id="alone" class="form-sub-divs-row-left">
                                    <div>
                                        <label class="text-base ml-3" for="hperson-email-pa"> Email Address </label>
                                    </div>
                                    <input id="hperson-email-pa" class="input-txt-classes-non" type="email" name="hperson-email-pa" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="patient-form-sub-div-4">
                        <div id="form-title-div-4">
                            <h3>Current Address</h3>
                            <h3 id="same-as-perma-btn">Same as permanent</h3>
                        </div>
                        <div id="form-body-div-4">
                            <div class="form-sub-divs-row">
                                <div id="fifty" class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-house-no-ca"> House No./Lot/Bldg </label>
                                    </div>
                                    <input id="hperson-house-no-ca" class="input-txt-classes" type="text" name="hperson-house-no-ca" autocomplete="off" placeholder="Lot 1">
                                </div>
                                
                                <div id="fifty" class="form-sub-divs-row-right">
                                    <div>
                                        <label for="hperson-street-block-ca"> Street/Block </label>
                                    </div>
                                    <input id="hperson-street-block-ca" class="input-txt-classes" type="text" name="hperson-street-block-ca" autocomplete="off" placeholder="Block 1">
                                </div>
                            </div>

                            <div class="form-sub-divs-row">
                                <div id="fifty" class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-region-select-ca"> Region CA </label>
                                    </div>
                                    <select id="hperson-region-select-ca" class="input-txt-classes" required onchange="getLocations('region' , 'ca-region')" name="region" autocomplete="off">
                                        <option value="" class="">Choose a Region</option>
                                        <?php 
                                            $stmt = $pdo->query('SELECT region_code, region_description from region');
                                            while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                echo '<option value="' , $data['region_code'] , '">' , $data['region_description'] , '</option>';
                                            }                                        
                                        ?>
                                    </select>
                                </div>
                                
                                <div id="fifty" class="form-sub-divs-row-right" >
                                    <div>
                                        <label for="hperson-province-select-ca"> Province </label>
                                    </div>
                                    <select id="hperson-province-select-ca" class="input-txt-classes" required onchange="getLocations('province' , 'ca-province')" name="province">
                                        <option value="ABUCAY" class="">Choose a Province</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-sub-divs-row">
                                <div id="fifty" class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-city-select-ca"> Municipality / City </label>
                                    </div>
                                    <select id="hperson-city-select-ca" class="input-txt-classes" required onchange="getLocations('city' , 'ca-city')" name="province">
                                        <option value="" class="">Choose a Municipality</option>
                                    </select>
                                </div>
                                
                                <div id="fifty" class="form-sub-divs-row-right">
                                    <div>
                                        <label for="hperson-brgy-select-ca"> Barangay </label>
                                    </div>
                                    <select id="hperson-brgy-select-ca" class="input-txt-classes" required name="province">
                                        <option value="" class="">Choose a Barangay</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-sub-divs-row">
                                <div id="fifty" class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-home-phone-no-ca"> Home Phone No. </label>
                                    </div>
                                    <input id="hperson-home-phone-no-ca" class="input-txt-classes-non" type="text" name="hperson-home-phone-no-ca" autocomplete="off">
                                </div>
                                
                                <div id="fifty" class="form-sub-divs-row-right">
                                    <div>
                                        <label for="hperson-mobile-no-ca"> Mobile Phone No. </label>
                                    </div>
                                    <input id="hperson-mobile-no-ca" class="input-txt-classes" type="text" name="hperson-mobile-no-ca" autocomplete="off">
                                </div>
                            </div>

                            <div class="form-sub-divs-row">
                                <div id="alone" class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-email-ca"> Email Address </label>
                                    </div>
                                    <input id="hperson-email-ca" class="input-txt-classes-non" type="email" name="hperson-email-ca" autocomplete="off">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                
                <div id="patient-reg-form-div-3">
                    <div id="patient-form-sub-div-5">
                        <div id="form-title-div-5">
                            <h3>Current Workplace Address</h3>
                        </div>
                        <div id="form-body-div-5">

                            <div class="form-sub-divs-row">
                                <div id="fifty" class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-house-no-cwa"> House No./Lot/Bldg </label>
                                    </div>
                                    <input id="hperson-house-no-cwa" class="input-txt-classes-non" type="text" name="hperson-house-no-cwa" autocomplete="off">
                                </div>
                                
                                <div id="fifty" class="form-sub-divs-row-right">
                                    <div>
                                        <label for="hperson-street-block-cwa"> Street/Block </label>
                                    </div>
                                    <input id="hperson-street-block-cwa" class="input-txt-classes-non" type="text" name="hperson-street-block-cwa" autocomplete="off">
                                </div>
                            </div>

                            <div class="form-sub-divs-row">
                                <div id="fifty" class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-region-select-cwa"> Region </label>
                                    </div>
                                    <select id="hperson-region-select-cwa" class="input-txt-classes-non" required onchange="getLocations('region' , 'cwa-region')" name="region" autocomplete="off">
                                        <option value="" class="">Choose a Region</option>
                                        <?php 
                                            $stmt = $pdo->query('SELECT region_code, region_description from region');
                                            while($data = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                echo '<option value="' , $data['region_code'] , '">' , $data['region_description'] , '</option>';
                                            }                                        
                                        ?>
                                    </select>
                                </div>
                                
                                <div id="fifty" class="form-sub-divs-row-right">
                                    <div>
                                        <label for="hperson-province-select-cwa"> Province </label>
                                    </div>
                                    <select id="hperson-province-select-cwa" class="input-txt-classes-non" required onchange="getLocations('province' , 'cwa-province')" name="province">
                                        <option value="" class="">Choose a Province</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-sub-divs-row">
                                <div id="fifty" class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-city-select-cwa"> Municipality / City </label>
                                    </div>
                                    <select id="hperson-city-select-cwa" class="input-txt-classes-non" required onchange="getLocations('city' , 'cwa-city')" name="city">
                                        <option value="" class="">Choose a Municipality</option>
                                    </select>
                                </div>
                                
                                <div id="fifty" class="form-sub-divs-row-right">
                                    <div>
                                        <label for="hperson-brgy-select-cwa"> Barangay </label>
                                    </div>
                                    <select id="hperson-brgy-select-cwa" class="input-txt-classes-non" name="brgy">
                                        <option value="" class="">Choose a Barangay</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-sub-divs-row">
                                <div id="fifty" class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-workplace-cwa"> Name of Workplace </label>
                                    </div>
                                    <input id="hperson-workplace-cwa" class="input-txt-classes-non" type="text" name="hperson-workplace-cwa" autocomplete="off">
                                </div>
                                
                                <div id="fifty" class="form-sub-divs-row-right">
                                    <div>
                                        <label for="hperson-ll-mb-no-cwa"> Landline / Mobile Phone No. </label>
                                    </div>
                                    <input id="hperson-ll-mb-no-cwa" class="input-txt-classes-non" type="text" name="hperson-ll-mb-no-cwa" autocomplete="off">
                                </div>
                            </div>

                            <div class="form-sub-divs-row">
                                <div id="alone" class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-email-cwa"> Email Address </label>
                                    </div>
                                    <input id="hperson-email-cwa" class="input-txt-classes-non" type="text" name="hperson-email-cwa" autocomplete="off">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div id="patient-form-sub-div-6">
                        <div id="form-title-div-6">
                            <h3>Address Outside the Philippines (For OFW only) </h3>
                        </div>
                        <div id="form-body-div-6">
                            <div class="form-sub-divs-row">
                                <div id="alone" class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-emp-name-ofw"> Employers Name </label>
                                    </div>
                                    <input id="hperson-emp-name-ofw" class="input-txt-classes-non" type="text" name="hperson-emp-name-ofw" autocomplete="off">
                                </div>
                            </div>

                            <div class="form-sub-divs-row">
                                <div id="fifty" class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-occupation-ofw"> Occupation </label>
                                    </div>
                                    <input id="hperson-occupation-ofw" class="input-txt-classes-non" type="text" name="hperson-occupation-ofw" autocomplete="off">
                                </div>
                                
                                <div id="fifty" class="form-sub-divs-row-right">
                                    <div>
                                        <label for="hperson-place-work-ofw"> Place of Work </label>
                                    </div>
                                    <input id="hperson-place-work-ofw" class="input-txt-classes-non" type="text" name="hperson-place-work-ofw" autocomplete="off">
                                </div>
                            </div>

                            <div class="form-sub-divs-row">
                                <div id="fifty" class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-house-no-ofw"> House No./Lot/Bldg </label>
                                    </div>
                                    <input id="hperson-house-no-ofw" class="input-txt-classes-non" type="text" name="hperson-house-no-ofw" autocomplete="off">
                                </div>
                                
                                <div id="fifty" class="form-sub-divs-row-right">
                                    <div>
                                        <label for="hperson-street-ofw"> Street/Block </label>
                                    </div>
                                    <input id="hperson-street-ofw" class="input-txt-classes-non" type="text" name="hperson-street-ofw" autocomplete="off">
                                </div>
                            </div>

                            <div class="form-sub-divs-row">
                                <div id="fifty" class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-region-select-ofw"> Region </label>
                                    </div>
                                    <input id="hperson-region-select-ofw" class="input-txt-classes-non" type="text" name="hperson-region-select-ofw" autocomplete="off">
                                </div>
                                
                                <div id="fifty" class="form-sub-divs-row-right">
                                    <div>
                                        <label for="hperson-province-select-ofw"> Province </label>
                                    </div>
                                    <input id="hperson-province-select-ofw" class="input-txt-classes-non" type="text" name="hperson-province-select-ofw" class="w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off">
                                </div>
                            </div>

                            <div class="form-sub-divs-row">
                                <div id="fifty" class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-city-select-ofw"> Municipality / City </label>
                                    </div>
                                    <input id="hperson-city-select-ofw" class="input-txt-classes-non" type="text" name="hperson-city-select-ofw" autocomplete="off">
                                </div>
                                
                                <div id="fifty" class="form-sub-divs-row-right">
                                    <div>
                                        <label for="hperson-country-select-ofw"> Country </label>
                                    </div>
                                    <input id="hperson-country-select-ofw" class="input-txt-classes-non" type="text" name="hperson-country-select-ofw" class="w-[90%] h-[40%] border-2 border-[#bfbfbf] rounded-lg outline-none p-2" autocomplete="off">
                                </div>
                            </div>

                            <div class="form-sub-divs-row">
                                <div id="fifty" class="form-sub-divs-row-left">
                                    <div>
                                        <label for="hperson-office-phone-no-ofw"> Office Phone No. </label>
                                    </div>
                                    <input id="hperson-office-phone-no-ofw" class="input-txt-classes-non" type="text" name="hperson-office-phone-no-ofw" autocomplete="off">
                                </div>
                                <div id="fifty" class="form-sub-divs-row-right">
                                    <div>
                                        <label for="hperson-mobile-no-ofw"> Mobile Phone No. </label>
                                    </div>
                                    <input id="hperson-mobile-no-ofw" class="input-txt-classes-non" type="text" name="hperson-mobile-no-ofw" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </form>
        </div>

    </main>

    <!-- Modal -->
    <div class="modal fade" id="myModal_pat_reg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <div class="modal-header-sub">
                    <h5 id="modal-title" class="modal-title" id="exampleModalLabel">Warning</h5>
                    <i id="modal-icon" class="fa-solid fa-triangle-exclamation"></i>
                    <!-- <i class="fa-solid fa-circle-check"></i> -->
                </div>
                <button type="button" class="close text-3xl" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modal-body" class="modal-body">
                Please fill out the required fields.
            </div>
            <div class="modal-footer">
                <button id="ok-modal-btn" type="button" data-bs-dismiss="modal">OK</button>
                <button id="yes-modal-btn" type="button" data-bs-dismiss="modal">Yes</button>
            </div>
            </div>
        </div>
    </div>

    <!-- patient history modal -->
    <div class="modal fade" id="patHistoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <button>Print</button> -->
                    <button id="close-pending-modal" data-bs-dismiss="modal">Close</button>
                </div>
                <div  class="modal-body-incoming">
                    <!-- patient information -->
                    <div class="pat-info-div">
                        <h4 id="pat-info-title">Patient's Information</h4>
                        <div class="pat-info-sub-div">

                            <div class="input-div">
                                <label class="input-title-lbl">Last Name</label>
                                <input type="text" class="info-inputs" id="info-input-lname" value="Nepomuceno"/>
                            </div>

                            <div class="input-div">
                                <label class="input-title-lbl">First Name</label>
                                <input type="text" class="info-inputs" id="info-input-fname" value="John Marvin"/>
                            </div>

                            <div class="input-div">
                                <label class="input-title-lbl">Middle Name</label>
                                <input type="text" class="info-inputs" id="info-input-mname" value="Gomez"/>
                            </div>

                            <div class="input-div">
                                <label class="input-title-lbl">Suffix Name</label>
                                <input type="text" class="info-inputs" id="info-input-sname" value="Gomez"/>
                            </div>

                            <div class="input-div">
                                <label class="input-title-lbl">Birthdate</label>
                                <input type="text" class="info-inputs" id="info-input-bdate" value="2001-04-28"/>
                            </div>                 

                            <div class="input-div">
                                <label class="input-title-lbl">Age</label>
                                <input type="text" class="info-inputs" id="info-input-age" value="22"/>
                            </div> 

                            <div class="input-div">
                                <label class="input-title-lbl">Sex</label>
                                <input type="text" class="info-inputs" id="info-input-sex" value="Male"/>
                            </div> 

                            <div class="input-div">
                                <label class="input-title-lbl">Barangay</label>
                                <input type="text" class="info-inputs" id="info-input-brgy" value="St/ Francis 2"/>
                            </div> 

                            <div class="input-div">
                                <label class="input-title-lbl">Municipality</label>
                                <input type="text" class="info-inputs" id="info-input-city" value="Limay"/>
                            </div> 

                            <div class="input-div">
                                <label class="input-title-lbl">Province</label>
                                <input type="text" class="info-inputs" id="info-input-prov" value="Bataan"/>
                            </div> 

                            <div class="input-div">
                                <label class="input-title-lbl">Region</label>
                                <input type="text" class="info-inputs" id="info-input-region" value="Reion 3"/>
                            </div> 

                            <div class="input-div">
                                <label class="input-title-lbl">Email Address</label>
                                <input type="text" class="info-inputs" id="info-input-email" value="jmgnngmj@gmail.com"/>
                            </div> 

                            <div class="input-div">
                                <label class="input-title-lbl">Mobile Phone No. </label>
                                <input type="text" class="info-inputs" id="info-input-mobile" value="09196044820"/>
                            </div> 

                            <div class="input-div">
                                <label class="input-title-lbl">Telephone Number</label>
                                <input type="text" class="info-inputs" id="info-input-telephone" value="333-3333"/>
                            </div> 

                            <div class="input-div">
                                <label class="input-title-lbl">Recorded at: </label>
                                <input type="text" class="info-inputs" id="info-input-rec_at" value="2023-10-16 10:10:16"/>
                            </div> 

                            <div class="input-div" style="width:500px">
                                <label class="input-title-lbl">Registered at: </label>
                                <input type="text" class="info-inputs" id="info-input-reg_at" value="Limay Medical Center"/>
                            </div>  
                        </div>   
                    </div>

                    <!-- referral information -->
                    <div class="pat-ref-div">
                        <div class="pat-status-mini-div">
                            <h4 id="pat-ref-title">Patient's Referral Information</h4>
                            <h4 id="pat-ref-status">Status: <span id="pat-ref-status-span">Pending</span></h4>
                        </div>
                        <div class="pat-ref-sub-div">
                            <div class="input-div">
                                <label class="input-title-lbl">Patient Type</label>
                                <input type="text" class="info-inputs" id="info-input-pat-type" value="ER"/>
                            </div>

                            <div class="input-div">
                                <label class="input-title-lbl">Patient Classification</label>
                                <input type="text" class="info-inputs" id="info-input-pat-class" value="Tertiary"/>
                            </div>

                            <div class="input-div">
                                <label class="input-title-lbl">Referred Date</label>
                                <input type="text" class="info-inputs" id="info-input-ref-date" value="2023-10-12 02:24:22"/>
                            </div>

                            <div class="input-div" style="width:220px">
                                <label class="input-title-lbl">Approved Time</label>
                                <input type="text" class="info-inputs" id="info-input-approve-time" value="2023-10-12 02:24:22"/>
                            </div>

                            <div class="input-div" style="width:500px">
                                <label class="input-title-lbl">Referred By</label>
                                <input type="text" class="info-inputs" id="info-input-ref-by" value="Limay Medical Center"/>
                            </div>

                            <div class="input-div" style="width:500px">
                                <label class="input-title-lbl">Referred To</label>
                                <input type="text" class="info-inputs" id="info-input-ref-to" value="Bataan General Hospital and Medical Center"/>
                            </div>

                            <div class="input-div" style="width:500px; height:200px;">
                                <label class="input-title-lbl">Reason Referral</label>
                                <input type="text" class="info-inputs" id="info-input-reason-ref" value="" style="height:150px;"/>
                            </div>

                            <div class="input-div" style="width:500px; height:200px">
                                <label class="input-title-lbl">Approval Details</label>
                                <input type="text" class="info-inputs" id="info-input-approve-details" value="" style="height:150px;"/>
                            </div>
                        </div>   
                    </div>
                </div>

                <div class="modal-footer">
                    <!-- <button id="ok-modal-btn-incoming" type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-dismiss="modal">OK</button>
                    <button id="yes-modal-btn-incoming" type="button" class="hidden bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-dismiss="modal">Yes</button>
                 -->
                </div>
            </div>
        </div>  
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script src="../js_2/patient_register_form2.js?v=<?php echo time(); ?>"></script>
    <script src="../js_2/search_name_2.js?v=<?php echo time(); ?>"></script>    
</body>
</html>

<!-- services.csc.gov.ph -->