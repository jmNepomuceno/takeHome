<?php 
    session_start();
    include('../database/connection2.php');

    $type = $_GET['type'];
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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="w-[95%] h-[90%] flex flex-col border-2 border-[#bfbfbf] px-12 py-12 rounded-lg">
        
        <input id="type-input" type="hidden" name="type-input" value=<?php echo $type; ?>>
        <input id="code-input" type="hidden" name="code-input" value=<?php echo $code; ?>>
        <input id="prompt" type="hidden" name="prompt" value=''>
        <input id="hospital_code" type="hidden" name="hospital_code" value=<?php echo $_SESSION['hospital_code']; ?>>

        <div class="w-full">
            <label class="font-semibold text-2xl ml-[1%] ">
                <?php echo $type; ?> Referral Form
            </label>
        </div>

        <div class="w-full h-full flex flex-col justify-center items-center">
            <div class="w-full h-[15%] flex flex-row items-center justify-start">

                <div class="w-[70%] h-full flex flex-row justify-start items-center">
                    <div class="w-[50%] h-full flex flex-col justify-center items-left ml-5">
                        <label class="font-bold -ml-[1.7%]">Refer to <span class="text-red-600 font-bold text-xl">*</span></label>    
                        <select id="refer-to-select" class="rounded-md w-full border-2 p-1 border-[#bfbfbf] -ml-[1.7%]">
                            <!-- <option value="Disabled Selected">Select</option> -->
                            <option value="Bataan General Hospital and Medical Center">Bataan General Hospital and Medical Center</option> -->
                            <?php for($i = 0; $i < count($hospital_names); $i++) { ?>
                                <?php echo "<option value='" . $hospital_names[$i]['hospital_name'] . "'" . ">" . $hospital_names[$i]['hospital_name'] . "</option>" ?>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="w-[30%] h-full ml-[4%] flex flex-col justify-center items-left">
                        <div class="ml-1 flex flex-row justify-start items-center font-bold mt-3">
                            <h1>Sensitive Case <span class="text-red-600 font-bold text-xl">*</span> </h1>
                            <button class="ml-1 w-4 h-4 rounded-full bg-blue-500 hover:bg-blue-700 focus:outline-none cursor-pointer text-black flex flex-row justify-center items-center">
                                <h4 class="text-xs text-white">i</h4>
                            </button>
                        </div>
                        <div class="w-full h-[40px]">
                            <input type="radio" name="sensitive" class="ml-[5%]" value="true"> 
                            <label class="mb-[0.3%] ml-[0.2%]">Yes</label>
                            <input type="radio" name= "sensitive" class="ml-[2%]" value="false">
                            <label class="mb-[0.3%] ml-[0.2%]">No</label>
                        </div>
                    </div>
                </div>

                
                <a href="#" class="text-blue-500 font-bold ml-[15%]">
                    Check Bed Availability
                </a>
            </div>   

            <div class="w-full h-[11%] flex flex-row justify-around items-start ">
                <div class="w-[30%] flex flex-col">
                    <label class="-ml-[2%] font-bold">Parent/Guardian(If minor)</label>
                    <input id="parent-guard-input" type="textbox" class="rounded-md  w-[98%] border-2  border-[#bfbfbf] -ml-[2%] outline-none">
                </div>
                
                <div class="ml-[2%] w-[15%]">
                    <label class="ml-[0.5%] font-bold">PHIC Member? <span class="text-red-600 font-bold text-xl">*</span></label>
                    <select id="phic-member-select" class="rounded-md ml-[0.5%] w-[100%] border-2  border-[#bfbfbf] outline-none">
                        <option value="">Select</option>
                        <option value="true"> Yes</option>
                        <option value="false"> No </option>
                    </select>
                </div>

                <div class="ml-[2%] w-[15%]">
                    <label class="ml-[0.5%] font-bold">Mode of Transport <span class="text-red-600 font-bold text-xl">*</span></label>
                    <select id="transport-select" class="rounded-md ml-[0.5%] w-[100%] border-2  border-[#bfbfbf] outline-none">
                        <option value="">Select</option>
                        <option value="Ambulance"> Ambulance </option>
                        <option value="Private Car"> Private Car </option>
                        <option value="Commute"> Commute </option>
                    </select>
                </div>

                <div class="ml-[2%] w-[23%]">
                    <label class="ml-[0.5%] font-bold">Date/Time Admitted <span class="text-red-600 font-bold text-xl">*</span></label>
                    <input type="datetime" class="rounded-md ml-[%] w-[100%] border-2  border-[#bfbfbf] outline-none">
                </div>   

                
            </div>

            <div class="w-full h-[74%] flex flex-row justify-center items-center">

                <div class="w-[50%] h-full flex flex-col justify-center items-center">
                    
                    <div class="w-[97%] h-[12%] flex flex-col">
                        <label class="font-bold">Referring Doctor <span class="text-red-600 font-bold text-xl">*</span></label>
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

                        <input id="referring-doc-input" type="textbox" class="rounded-md  w-[98%] border-2  border-[#bfbfbf] outline-none">
                    </div>

                    <div class="w-[97%] h-[87%] flex flex-col justify-start items-left">
                        <label class="w-full font-bold ">Chief Complaint and History <span class="text-red-600 font-bold text-xl">*</span></label>
                        <textarea id="complaint-history-input" class="border-2  border-[#bfbfbf] w-full h-[33.3%] resize-none outline-none"></textarea>

                        <label class="w-full font-bold  ">Reason for Referral <span class="text-red-600 font-bold text-xl">*</span></label>
                        <textarea id="reason-referral-input" class="border-2  border-[#bfbfbf] w-full h-[33.3%] resize-none outline-none"></textarea>

                        <label class="w-full font-bold  ">Impression / Diagnosis <span class="text-red-600 font-bold text-xl">*</span></label>
                        <textarea id="diagnosis" class="border-2  border-[#bfbfbf] w-full h-[33.3%] resize-none outline-none"></textarea>
                    </div>
                </div>

                <div class="w-[50%] h-full flex flex-col justify-between items-left">
                    <label class="w-full h-[20px] ml-3 font-bold">Physical Examination</label>
                    <div class="w-[97%] h-[95%] border-2 border-[#bfbfbf] rounded-lg ml-[1.5%] flex flex-col justify-center items-center">
                        <div class="flex flex-row w-[98%] h-[14%]  ml-[6px] mt-2 justify-center items-center">
                            <div class="w-[20%]">
                                <div class="ml-1 flex flex-row justify-start items-center font-bold mt-3">
                                    <h1>BP <span class="text-red-600 font-bold text-xl">*</span></h1>
                                    <button class="ml-1 w-4 h-4 rounded-full bg-blue-500 hover:bg-blue-700 focus:outline-none cursor-pointer text-black flex flex-row justify-center items-center">
                                        <h4 class="text-xs text-white">i</h4>
                                    </button>
                                </div>
                                <input id='bp-input' type="textbox" class="border-2  border-[#bfbfbf] w-[98%] outline-none">                      
                            </div>

                            <div class="w-[20%]  ml-[3%]">
                                <div class="ml-1 flex flex-row justify-start items-center font-bold mt-3">
                                    <h1>HR <span class="text-red-600 font-bold text-xl">*</span></h1>
                                    <button class="ml-1 w-4 h-4 rounded-full bg-blue-500 hover:bg-blue-700 focus:outline-none cursor-pointer text-black flex flex-row justify-center items-center">
                                        <h4 class="text-xs text-white">i</h4>
                                    </button>
                                </div>
                                <input id='hr-input' type="textbox" class="border-2  border-[#bfbfbf] w-[98%] outline-none">     
                            </div>

                            <div class="w-[20%]  ml-[3%]">
                                <div class="ml-1 flex flex-row justify-start items-center font-bold mt-3">
                                    <h1>RR <span class="text-red-600 font-bold text-xl">*</span></h1>
                                    <button class="ml-1 w-4 h-4 rounded-full bg-blue-500 hover:bg-blue-700 focus:outline-none cursor-pointer text-black flex flex-row justify-center items-center">
                                        <h4 class="text-xs text-white">i</h4>
                                    </button>
                                </div>
                                <input id='rr-input' type="textbox" class="border-2  border-[#bfbfbf] w-[98%] outline-none">     
                            </div>

                               

                            <div class="w-[20%]  ml-[3%]">
                                <div class="ml-1 flex flex-row justify-start items-center font-bold mt-3">
                                    <h1>Temp (°C) <span class="text-red-600 font-bold text-xl">*</span></h1>
                                    <button class="ml-1 w-4 h-4 rounded-full bg-blue-500 hover:bg-blue-700 focus:outline-none cursor-pointer text-black flex flex-row justify-center items-center">
                                        <h4 class="text-xs text-white">i</h4>
                                    </button>
                                </div>
                                <input id='temp-input' type="textbox" class="border-2  border-[#bfbfbf] w-[98%] outline-none"> 
                            </div>
                        </div>
                        <div class="flex flex-col w-[20%] h-[12%] mt-[10px] justify-center items-left ">
                            <div class="ml-1 flex flex-row justify-start items-center font-bold mt-3">
                                <h1>WT.(kg) <span class="text-red-600 font-bold text-xl">*</span></h1>
                                <button class="ml-1 w-4 h-4 rounded-full bg-blue-500 hover:bg-blue-700 focus:outline-none cursor-pointer text-black flex flex-row justify-center items-center">
                                    <h4 class="text-xs text-white">i</h4>
                                </button>
                            </div>
                            <input id='weight-input' type="textbox" class="border-2  border-[#bfbfbf] w-[98%] outline-none"> 
                        </div> 

                        <div class="w-[90%] h-[70%]">
                            <label class="ml-2 font-bold">Pertinent PE Findings <span class="text-red-600 font-bold text-xl">*</span>       </label>
                            <textarea id="pe-findings-input" class="border-2 border-[#bfbfbf] w-[98%] outline-none h-[88%] ml-[1%] rounded-lg"></textarea>
                        </div>
                    </div>
                </div>

                
            </div>

            <div class="w-[95%] flex flex-row justify-start items-center mr-2 mt-2">
                <button id="submit-referral-btn-id" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded mr-2">Submit</button>
                <button id="cancel-referral-btn-id" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded h-[40px]">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header flex flex-row justify-between items-center">
                <div class="flex flex-row justify-between items-center">
                    <h5 id="modal-title" class="modal-title" id="exampleModalLabel">Warning</h5>
                    <i id="modal-icon" class="fa-solid fa-triangle-exclamation ml-2"></i>
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
                <button id="ok-modal-btn" type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-dismiss="modal">OK</button>
                <button id="yes-modal-btn" type="button" class="hidden bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-dismiss="modal">Yes</button>
            </div>
            </div>
        </div>
    </div>

    <script src="./test.js?v=<?php echo time(); ?>"></script>

</body>
</html>