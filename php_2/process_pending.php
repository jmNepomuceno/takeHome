<?php
    session_start();
    include("../database/connection2.php");
    date_default_timezone_set('Asia/Manila');

    $hpercode = $_POST['hpercode'];
    $sql = "SELECT * FROM incoming_referrals WHERE hpercode='". $hpercode ."' ORDER BY date_time DESC LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);  
    $jsonString = $data;



    $sql = "SELECT * FROM hperson WHERE hpercode='". $hpercode ."' ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);  
    // echo '<pre>'; print_r($data); echo '</pre>';
    $jsonString_2 = $data;

    $mergedObj = array_merge($jsonString, $jsonString_2);

    // FOR ADDRESS CODE CONVERTION
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    // $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($data); echo '</pre>';

    // if the query is slow, remove the region/province/city/brgy code and directly save the name of the regions/province/city/brgy.
    // FROM REGION CODE TO REGION DESCRIPTION QUERY
    // permanent address
    $sql_province = 'SELECT province_description FROM provinces WHERE province_code="'. $mergedObj[1]["pat_province"] .'" ';
    $stmt_province = $pdo->prepare($sql_province);
    $stmt_province->execute();
    $data_province = $stmt_province->fetchAll(PDO::FETCH_ASSOC);
    
    $sql_city = 'SELECT municipality_description FROM city WHERE municipality_code="'. $mergedObj[1]["pat_municipality"] .'" ';
    $stmt_city = $pdo->prepare($sql_city);
    $stmt_city->execute();
    $data_city = $stmt_city->fetchAll(PDO::FETCH_ASSOC);

    $sql_brgy = 'SELECT barangay_description FROM barangay WHERE barangay_code="'. $mergedObj[1]["pat_barangay"] .'" ';
    $stmt_brgy = $pdo->prepare($sql_brgy);
    $stmt_brgy->execute();
    $data_brgy = $stmt_brgy->fetchAll(PDO::FETCH_ASSOC);

    $mergedObj[1]["pat_province"] = $data_province[0]['province_description'];
    $mergedObj[1]["pat_municipality"] = $data_city[0]['municipality_description'];
    $mergedObj[1]["pat_barangay"] = $data_brgy[0]['barangay_description'];

    // current address
    $sql_province_ca = 'SELECT province_description FROM provinces WHERE province_code="'. $mergedObj[1]["pat_curr_province"] .'" ';
    $stmt_province_ca = $pdo->prepare($sql_province_ca);
    $stmt_province_ca->execute();
    $data_province_ca = $stmt_province_ca->fetchAll(PDO::FETCH_ASSOC);
    
    $sql_city_ca = 'SELECT municipality_description FROM city WHERE municipality_code="'. $mergedObj[1]["pat_curr_municipality"] .'" ';
    $stmt_city_ca = $pdo->prepare($sql_city_ca);
    $stmt_city_ca->execute();
    $data_city_ca = $stmt_city_ca->fetchAll(PDO::FETCH_ASSOC);

    $sql_brgy_ca = 'SELECT barangay_description FROM barangay WHERE barangay_code="'. $mergedObj[1]["pat_curr_barangay"] .'" ';
    $stmt_brgy_ca = $pdo->prepare($sql_brgy_ca);
    $stmt_brgy_ca->execute();
    $data_brgy_ca = $stmt_brgy_ca->fetchAll(PDO::FETCH_ASSOC);

    $mergedObj[1]["pat_curr_province"] = $data_province_ca[0]['province_description'];
    $mergedObj[1]["pat_curr_municipality"] = $data_city_ca[0]['municipality_description'];
    $mergedObj[1]["pat_curr_barangay"] = $data_brgy_ca[0]['barangay_description'];

    // current workplace address
    if($mergedObj[1]["pat_work_province"] != "N/A"){
        $sql_province_cwa = 'SELECT province_description FROM provinces WHERE province_code="'. $mergedObj[1]["pat_work_province"] .'" ';
        $stmt_province_cwa = $pdo->prepare($sql_province_cwa);
        $stmt_province_cwa->execute();
        $data_province_cwa = $stmt_province_cwa->fetchAll(PDO::FETCH_ASSOC);
        
        $sql_city_cwa = 'SELECT municipality_description FROM city WHERE municipality_code="'. $mergedObj[1]["pat_work_municipality"] .'" ';
        $stmt_city_cwa = $pdo->prepare($sql_city_cwa);
        $stmt_city_cwa->execute();
        $data_city_cwa = $stmt_city_cwa->fetchAll(PDO::FETCH_ASSOC);

        $sql_brgy_cwa = 'SELECT barangay_description FROM barangay WHERE barangay_code="'. $mergedObj[1]["pat_work_barangay"] .'" ';
        $stmt_brgy_cwa = $pdo->prepare($sql_brgy_cwa);
        $stmt_brgy_cwa->execute();
        $data_brgy_cwa = $stmt_brgy_cwa->fetchAll(PDO::FETCH_ASSOC);

        $mergedObj[1]["pat_work_province"] = $data_province_cwa[0]['province_description'];
        $mergedObj[1]["pat_work_municipality"] = $data_city_cwa[0]['municipality_description'];
        $mergedObj[1]["pat_work_barangay"] = $data_brgy_cwa[0]['barangay_description'];
    }
    
    $response = $mergedObj;


    // $response = json_encode($mergedObj);
    // echo $response;
    
    // print mo lang lahat ng need i print sa incoming_form.js bukas. gege
    // gl hf tomorrow! :)))))) <333333

    $sql = "UPDATE hperson SET status='On-Process' WHERE hpercode=:hpercode";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':hpercode', $hpercode, PDO::PARAM_STR);
    $stmt->execute();


    echo '<ul class="referred-details-ul">';
        echo '<li><label>Referring Agency:</label><span id="refer-agency"> '. $response[0]['referred_by'].'</span></li>';
        if($_POST['from'] === 'outgoing'){
            echo '<li><label>Referring To:</label><span id="refer-agency"> '. $response[0]['refer_to'].'</span></li>';
        }else{
            
        }
        echo '<li><label>Reason for Referral:</label><span id="refer-reason"> '. $response[0]['reason'].'</span></li><br>';

        echo '<li><label>Name:</label><span id="pending-name" > '. $response[0]['patlast'] . ", " . $response[0]['patfirst'] . " " . $response[0]['patmiddle'].' </span></li>';
        echo '<li><label>Birthday:</label><span id="pending-bday"> '. $response[1]['patbdate'].' </span></li>';
        echo '<li><label>Age:</label><span id="pending-age"> '. $response[1]['pat_age'].' years old </span></li>';
        echo '<li><label>Sex:</label><span id="pending-sex"> '. $response[1]['patsex'].' </span></li>';
        echo '<li><label>Civil Status:</label><span id="pending-civil"> '. $response[1]['patcstat'].' </span></li>';
        echo '<li><label>Religion:</label><span id="pending-religion"> '. $response[1]['relcode'].' </span></li>';
        echo '<li><label>Address:</label><span id="pending-address">
         '. $response[1]['pat_bldg'] . " " . $response[1]['pat_street_block'] . " " . $response[1]['pat_barangay'] . " " . $response[1]['pat_municipality'] . " " . $response[1]['pat_province'] . " " . $response[1]['pat_region'].' 
        </span></li><br>';

        echo '<li><label>Parent/Guardian:</label><span id="pending-parent"> '. $response[0]['parent_guardian'].' </span></li>';
        echo '<li><label>PHIC Member:</label><span id="pending-phic"> '. $response[0]['phic_member'].' </span></li>';
        echo '<li><label>Mode of Transport:</label><span id="pending-transport"> '. $response[0]['transport'].' </span></li>';
        echo '<li><label>Date/Time Admitted:</label><span id="pending-admitted"> '. $response[1]['created_at'].' </span></li>';
        echo '<li><label>Referring Doctor:</label><span id="pending-referring-doc"> '. $response[0]['referring_doctor'].' </span></li>';
        echo '<li><label>Contact #:</label><span id="pending-contact-no"> '. $response[1]['pat_mobile_no'].' </span></li><br>';

        if($response[0]['type'] == 'OB'){
            echo '<li><label>OB-Gyne</label><span id="pending-ob"> '. $response[1]['created_at'].' </span></li>';
            echo '<li><label>Last Menstrual Period:</label><span id="pending-last-mens"> '. $response[0]['referring_doctor'].' </span></li>';
            echo '<li><label>Age of Gestation</label><span id="pending-gestation"> '. $response[0]['referred_by'].' </span></li><br>';
        }
        
        echo '<li><label>Chief Complaint and History:</label><span id="pending-complaint-history"> '. $response[0]['chief_complaint_history'].' </span></li><br>';

        echo '<li><label>Physical Examination</label><span id="pending-pe"> '. $response[0]['chief_complaint_history'].' </span></li>';
        echo '<li><label>Blood Pressure:</label><span id="pending-bp"> '. $response[0]['bp'].' </span></li>';
        echo '<li><label>Heart Rate:</label><span id="pending-hr"> '. $response[0]['hr'].' </span></li>';
        echo '<li><label>Respiratory Rate:</label><span id="pending-rr"> '. $response[0]['rr'].' </span></li>';
        echo '<li><label>Temperature:</label><span id="pending-temp"> '. $response[0]['temp'].' </span></li>';
        echo '<li><label>Weight:</label><span id="pending-weight"> '. $response[0]['weight'].' </span></li><br>';

        if($response[0]['type'] == 'OB'){
            echo '<li><label><label>Fetal Heart Tone:</label><span id="pending-heart-tone"> '. $response[0]['referred_by'].' </span></li>';
            echo '<li><label><label>Fundal Height:</label><span id="pending-fundal-height"> '. $response[0]['referred_by'].' </span></li><br>';

            echo '<li><label><label>Internal Examination</label><span id="pending-ie"> '. $response[0]['referred_by'].' </span></li>';
            echo '<li><label><label>Cervical Dilatation:</label><span id="pending-cd"> '. $response[0]['referred_by'].' </span></li>';
            echo '<li><label><label>Bag of Water:</label><span id="pending-bag-water"> '. $response[0]['referred_by'].' </span></li>';
            echo '<li><label><label>Presentation:</label><span id="pending-presentation"> '. $response[0]['referred_by'].' </span></li>';
            echo '<li><label><label>Others:</label><span id="pending-others"> '. $response[0]['referred_by'].' </span></li><br>';
        }

        echo '<li><label>Pertinent PE Findings:</label><span id="pending-p-pe-find"> '. $response[0]['pertinent_findings'].' </span></li><br>';

        echo '<li><label>Impression / Diagnosis:</label><span id="pending-diagnosis"> '. $response[0]['diagnosis'].' </span></li>';
    echo '</ul>';


    // update the date of the reception time or, when did the user click the pencil or open the referral form
    $reception_time = date('Y-m-d H:i:s');
    $sql = "UPDATE incoming_referrals SET reception_time=:reception_time WHERE hpercode=:hpercode ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':reception_time', $reception_time, PDO::PARAM_STR);
    $stmt->bindParam(':hpercode', $hpercode, PDO::PARAM_STR);
    $stmt->execute();
    
?>