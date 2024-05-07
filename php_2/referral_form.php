<?php 
    session_start();
    include('../database/connection2.php');

    $type = $_GET['type'];
    $type = str_replace('"', '', $type);

    $code = $_GET['code'];
    if (isset($_POST['newValue'])) {
        // Retrieve the new value from the AJAX request
        $newValue = $_POST['newValue'];
    
        // Set the new value in the session
        $_SESSION['prompt'] = $newValue;
    
        // You can send a response back to the client if needed
        echo 'Value saved successfully.';
    } else {
        // Handle errors
        // echo 'Error saving value.';
    }

    $sql = "SELECT hospital_name FROM sdn_hospital WHERE hospital_name != 'bgh' ORDER BY hospital_name ASC;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($data); echo '</pre>';

    // echo $data[0]['hospital_name']
    $hospital_names = $data;

    $date_today =  date("Y-m-d H:i:s");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/referral_form.css">
    <title>Document</title>
</head>
<body>
    <div class="container">
        
        <input id="type-input" type="hidden" name="type-input" value=<?php echo $type; ?>>
        <input id="code-input" type="hidden" name="code-input" value=<?php echo $code; ?>>
        <input id="prompt" type="hidden" name="prompt" value=''>
        <input id="hospital_code" type="hidden" name="hospital_code" value=<?php echo $_SESSION['hospital_code']; ?>>

        <div class="referral-title" style="width:100%">
            <label>
                <?php echo $type; ?> Referral Form
            </label>

            <div class="refer-form-btns-div">
                <button id="submit-referral-btn-id" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-toggle="modal" data-bs-target="#myModal-referral">Submit</button>
                <button id="cancel-referral-btn-id" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded h-[40px]" data-bs-toggle="modal" data-bs-target="#myModal-referral">Cancel</button>
            </div>
        </div>

        <!-- hd admitting surgery -->

        <div class="referral-body">
            <div class="first-part">
                <div class="first-sub-div-part">
                    <div class="refer-to-div">
                        <label>Refer to <span>*</span></label>    
                        <select id="refer-to-select">
                            <option value="Bataan General Hospital and Medical Center">Bataan General Hospital and Medical Center</option> -->
                            <?php for($i = 0; $i < count($hospital_names); $i++) { ?>
                                <?php echo "<option value='" . $hospital_names[$i]['hospital_name'] . "'" . ">" . $hospital_names[$i]['hospital_name'] . "</option>" ?>
                            <?php } ?>
                        </select>
                    </div>  
                            <!-- bg-[#1f292e] -->
                    <div class="sensitive-case-div">
                        <div class="sensitive-div">
                            <h1>Sensitive Case <span>*</span> </h1>
                            <button> i </button>
                        </div>
                        <div class="sensitive-rbs">
                            <input type="radio" name="sensitive_case" class="" value="true"> 
                            <label>Yes</label>
                            <input type="radio" name= "sensitive_case" value="false">
                            <label>No</label>
                        </div>
                    </div>
                </div>

                
                <a href="#" class="text-blue-500 font-bold ml-[15%]">
                    Check Bed Availability
                </a>
            </div>   

            <div class="second-part">
                <div class="second-part-divs">
                    <label>Parent/Guardian(If minor)</label>
                    <input id="parent-guard-input" type="text" autocomplete="off">
                </div>
                
                <div class="second-part-divs">
                    <label>PHIC Member? <span>*</span></label>
                    <select id="phic-member-select">
                        <option value="">Select</option>
                        <option value="true"> Yes</option>
                        <option value="false"> No </option>
                    </select>
                </div>

                <div class="second-part-divs">
                    <label>Mode of Transport <span>*</span></label>
                    <select id="transport-select">
                        <option value="">Select</option>
                        <option value="Ambulance"> Ambulance </option>
                        <option value="Private Car"> Private Car </option>
                        <option value="Commute"> Commute </option>
                    </select>
                </div>

                <div class="second-part-divs">
                    <label>Date/Time Admitted <span></span></label>
                    <input id="date-input" type="text" value=<?php echo $date_today ?> >
                </div>   

                
            </div>

            <div class="third-part">

                <div class="left-side">
                    
                    <div class="left-sub-div-1">
                        <label>Referring Doctor <span>*</span></label>
                        <!-- <select class="rounded-md w-[100%] border-2  border-[#bfbfbf] outline-none">
                            <option value="Disabled Selected">Select</option>
                            <option value="John Marvin Nepomuceno"> John Marvin Nepomuceno</option>
                            <option value="John Marvin Nepomuceno"> John Marvin Nepomuceno</option>
                            <option value="John Marvin Nepomuceno"> John Marvin Nepomuceno</option>
                            <option value="John Marvin Nepomuceno"> John Marvin Nepomuceno</option>
                            <option value="John Marvin Nepomuceno"> John Marvin Nepomuceno</option>
                            <option value="John Marvin Nepomuceno"> John Marvin Nepomuceno</option>
                            <option value="John Marvin Nepomuceno"> John Marvin Nepomuceno</option>
                            <option value="John Marvin Nepomuceno"> John Marvin Nepomuceno</option>
                            <option value="John Marvin Nepomuceno"> John Marvin Nepomuceno</option>
                            <option value="John Marvin Nepomuceno"> John Marvin Nepomuceno</option>
                        </select> -->

                        <input id="referring-doc-input" type="textbox">
                    </div>

                    <div class="left-sub-div-2">
                        <label>Chief Complaint and History <span>*</span></label>
                        <textarea id="complaint-history-input" autocomplete="off"></textarea>

                        <label>Reason for Referral <span>*</span></label>
                        <textarea id="reason-referral-input" autocomplete="off"></textarea>

                        <label>Impression / Diagnosis <span>*</span></label>
                        <textarea id="diagnosis" autocomplete="off"></textarea>
                    </div>


                    <!-- only for OB -->

                    <?php 
                        if($type === "OB"){
                            echo '<div class="ob-part">
                                    <div class="ob-first">
                                        <label>Fetal Heart Tone<span>*</span></label>
                                        <input type="text" id="fetal-heart-inp" autocomplete="off"/>
                                    </div>

                                    <div class="ob-second">
                                        <label>Fundal Height<span>*</span></label>
                                        <input type="text" id="fundal-height-inp" autocomplete="off"/>
                                    </div>

                                    <div class="ob-third">
                                        <label>Cervical Dilation<span>*</span></label>
                                        <input type="text" id="cervical-dilation-inp" autocomplete="off"/>
                                    </div>

                                    <div class="ob-fourth">
                                        <label>Bag of Water<span>*</span></label>
                                        <input type="text" id="bag-water-inp" autocomplete="off"/>
                                    </div>
                                </div>';
                        }
                    ?>
                    
                </div>

                <div class="right-side">
                    <label id="phy-exam-lbl">Physical Examination</label>
                    <div class="right-side-main-div">
                        <div class="right-side-main-div-1">
                            <div class="right-side-main-div-1-sub-1" style="width:20%">
                                <div>
                                    <h1>BP <span>*</span></h1>
                                    <button>i</button>
                                </div>
                                <input id='bp-input' type="text" autocomplete="off">                      
                            </div>

                            <div class="right-side-main-div-1-sub-2" style="width:20%; margin-left:1%">
                                <div>
                                    <h1>HR <span>*</span></h1>
                                    <button>i</button>
                                </div>
                                <input id='hr-input' type="text" autocomplete="off">     
                            </div>

                            <div class="right-side-main-div-1-sub-3" style="width:20%; margin-left:1%">
                                <div>
                                    <h1>RR <span>*</span></h1>
                                    <button>i</button>
                                </div>
                                <input id='rr-input' type="text" autocomplete="off">     
                            </div>

                            <div class="right-side-main-div-1-sub-4" style="width:20%; margin-left:1%">
                                <div>
                                    <h1>Temp (°C) <span>*</span></h1>
                                    <button>i</button>
                                </div>
                                <input id='temp-input' type="text" autocomplete="off"> 
                            </div>

                            <div class="right-side-main-div-1-sub-5" style="width:20%; margin-left:1%">
                                <div>
                                    <h1>WT. (kg) <span>*</span></h1>
                                    <button>i</button>
                                </div>
                                <input id='weight-input' type="text" autocomplete="off"> 
                            </div>
                        </div>

                        <!-- <div class="flex flex-col w-[20%] h-[12%] mt-[10px] justify-center items-left ">
                            <div class="ml-1 flex flex-row justify-start items-center font-bold mt-3">
                                <h1>WT.(kg) <span class="text-red-600 font-bold text-xl">*</span></h1>
                                <button class="ml-1 w-4 h-4 rounded-full bg-blue-500 hover:bg-blue-700 focus:outline-none cursor-pointer text-black flex flex-row justify-center items-center">
                                    <h4 class="text-xs text-white">i</h4>
                                </button>
                            </div>
                            <input id='weight-input' type="text" class="border-2  border-[#bfbfbf] w-[98%] outline-none" autocomplete="off"> 
                        </div>  -->

                        <div class="right-side-main-div-2">
                            <label>Pertinent PE Findings <span>*</span>       </label>
                            <textarea id="pe-findings-input" autocomplete="off"></textarea>
                        </div>

                        <!-- only for OB -->
                        <?php 
                            if($type === "OB"){
                                echo '
                                <div class="right-side-main-div-3">
                                    <div>
                                        <label>Presentation<span>*</span></label>
                                        <input type="text" id="presentation-ob-inp" autocomplete="off"/>
                                    </div>
        
                                    <div>
                                        <label>Others<span>*</span></label>
                                        <textarea id="others-ob-inp" autocomplete="off"></textarea>
                                    </div>
                                </div>
                                ';
                            }
                        ?>
                    </div>

                </div>

                
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal-referral" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-header-sub">
                        <h5 id="modal-title" class="modal-title" id="exampleModalLabel">Warning</h5>
                        <!-- <i id="modal-icon" class="fa-solid fa-triangle-exclamation"></i> -->
                        <!-- <i class="fa-solid fa-circle-check"></i> -->
                    </div>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modal-body" class="modal-body">
                    Please fill out the required fields.
                </div>
                <div class="modal-footer">
                    <button id="ok-modal-btn" type="button" data-bs-dismiss="modal">OK</button>
                    <button id="yes-modal-btn" type="button" data-bs-dismiss="modal" style="display:none;">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../js_2/referral_form.js?v=<?php echo time(); ?>"></script>

</body>
</html>