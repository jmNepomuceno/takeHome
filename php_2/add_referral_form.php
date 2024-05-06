<?php 

    session_start();
    include("../database/connection2.php");
    date_default_timezone_set('Asia/Manila');

    $code = $_POST['code'];

    // get some information from the hperson table
    $sql = "SELECT patlast, patfirst, patmiddle, patsuffix FROM hperson WHERE hpercode='". $code ."'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // date_time column = date and time of referral
    $dateTime = new DateTime();
    // Format the DateTime object as needed
    $formattedDateTime = $dateTime->format("Y-m-d H:i:s");

    $year = $dateTime->format("Y");
    $month = $dateTime->format("m");
    $day = $dateTime->format("d");
    $hours = $dateTime->format("H");
    $minutes = $dateTime->format("i");
    $seconds = $dateTime->format("s");

    // FOR NAMING OF THE REFERENCE NUMBER DEPENDS ON WHAT HOSPITAL, BGH WILL REFER TO
    $referTo = filter_input(INPUT_POST, 'refer_to');
    $sql_temp = "SELECT hospital_municipality_code FROM sdn_hospital WHERE hospital_name = :refer_to";
    $stmt_temp = $pdo->prepare($sql_temp);
    $stmt_temp->bindParam(':refer_to', $referTo, PDO::PARAM_STR);
    $stmt_temp->execute();
    $data_municipality_code = $stmt_temp->fetch(PDO::FETCH_ASSOC);

    // reference now the municipality code to get the municipality name from city table
    $sql_temp = "SELECT municipality_description FROM city WHERE municipality_code=:id ";
    $stmt_temp = $pdo->prepare($sql_temp); 
    $stmt_temp->bindParam(':id', $data_municipality_code['hospital_municipality_code'], PDO::PARAM_STR);
    $stmt_temp->execute();
    $data_municipality_desc = $stmt_temp->fetch(PDO::FETCH_ASSOC);

    // constructing the reference number
    $inputString = $_POST['refer_to'];
    $words = explode(' ', $inputString);
    $firstLetters = array_map(function ($word) {
        return ucfirst(substr($word, 0, 1));
    }, $words);
    $abbreviation = implode('', $firstLetters);

    // sql variable
   // R3-BTN-LIMAY-FCSH-2023-12-06
   if($data_municipality_desc['municipality_description'] === "CITY OF BALANGA (Capital)"){
        $data_municipality_desc['municipality_description'] = "BALANGA";
        $abbreviation = "BGHMC";
   }
    $reference_num = 'R3-BTN-'. $data_municipality_desc['municipality_description'] . '-' . $abbreviation . '-' . $year . '-' . $month . '-' . $day;
    $patlast = $data['patlast'];
    $patfirst = $data['patfirst'];
    $patmiddle = $data['patmiddle'];
    $patsuffix = $data['patsuffix'];

    $type = $_POST['type'];

    $referred_by = $_SESSION['hospital_name'];
    $landline_no = $_SESSION['hospital_landline'];
    $mobile_no = $_SESSION['hospital_mobile'];

    $referred_time =  $year . '/' .  $month . '/' .  $day  . ' - ' .  $hours . ':' .  $minutes . ':' .  $seconds;
    $temp_referred_time =  $year . '-' .  $month . '-' .  $day  . ' ' .  $hours . ':' .  $minutes . ':' .  $seconds;
    $status = 'Pending';

    /////////////////////////////////////////////////

    $refer_to = $_POST['refer_to'];
    $sensitive_case = $_POST['sensitive_case'];
    $parent_guardian = $_POST['parent_guardian'];
    $phic_member = $_POST['phic_member'];
    $transport = $_POST['transport'];
    $referring_doc = $_POST['referring_doc'];

    $complaint_history_input = $_POST['complaint_history_input'];
    $reason_referral_input = $_POST['reason_referral_input'];
    $diagnosis = $_POST['diagnosis'];

    $bp_input = $_POST['bp_input'];
    $hr_input = $_POST['hr_input'];
    $rr_input = $_POST['rr_input'];
    $temp_input = $_POST['temp_input'];
    $weight_input = $_POST['weight_input'];
    $pe_findings_input = $_POST['pe_findings_input'];

    if($type === "OB"){
        $fetal_heart_inp = $_POST['fetal_heart_inp'];
        $fundal_height_inp = $_POST['hr_input'];
        $cervical_dilation_inp = $_POST['cervical_dilation_inp'];
        $bag_water_inp = $_POST['bag_water_inp'];
        $presentation_ob_inp = $_POST['presentation_ob_inp'];
        $others_ob_inp = $_POST['others_ob_inp'];
    }

    $sql = "";
   if($type === "OB"){
        $sql = "INSERT INTO incoming_referrals (hpercode, reference_num, patlast, patfirst, patmiddle, patsuffix, type, referred_by, landline_no, mobile_no, date_time, status, refer_to, sensitive_case, parent_guardian , phic_member, transport,
            referring_doctor, chief_complaint_history, reason, diagnosis, bp, hr, rr, temp, weight, pertinent_findings,
            fetal_heart_tone, fundal_height, cervical_dilation, bag_water, presentation, others_ob)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?, ?,?,?,?,?,?,?,?,?,?,?,?,?,?, ?, ?,?,?,?,?,?)";
   }else{
        $sql = "INSERT INTO incoming_referrals (hpercode, reference_num, patlast, patfirst, patmiddle, patsuffix, type, referred_by, landline_no, mobile_no, date_time, status, refer_to, sensitive_case, parent_guardian , phic_member, transport, referring_doctor, chief_complaint_history, reason, diagnosis, bp, hr, rr, temp, weight, pertinent_findings)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?, ?,?,?,?,?,?,?,?,?,?,?,?,?,?, ?)";
   }

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(1, $code, PDO::PARAM_STR);
    $stmt->bindParam(2, $reference_num, PDO::PARAM_STR);
    $stmt->bindParam(3, $patlast, PDO::PARAM_STR);
    $stmt->bindParam(4, $patfirst, PDO::PARAM_STR);
    $stmt->bindParam(5, $patmiddle, PDO::PARAM_STR);
    $stmt->bindParam(6, $patsuffix, PDO::PARAM_STR);
    $stmt->bindParam(7, $type, PDO::PARAM_STR);
    $stmt->bindParam(8, $referred_by, PDO::PARAM_STR);

    $stmt->bindParam(9, $landline_no, PDO::PARAM_STR);
    $stmt->bindParam(10, $mobile_no, PDO::PARAM_STR);

    $stmt->bindParam(11, $referred_time, PDO::PARAM_STR);
    $stmt->bindParam(12, $status, PDO::PARAM_STR);

    $stmt->bindParam(13, $refer_to, PDO::PARAM_STR);
    $stmt->bindParam(14, $sensitive_case, PDO::PARAM_STR);

    $stmt->bindParam(15, $parent_guardian, PDO::PARAM_STR);
    $stmt->bindParam(16, $phic_member, PDO::PARAM_STR);
    $stmt->bindParam(17, $transport, PDO::PARAM_STR);
    $stmt->bindParam(18, $referring_doc, PDO::PARAM_STR);

    $stmt->bindParam(19, $complaint_history_input, PDO::PARAM_STR);
    $stmt->bindParam(20, $reason_referral_input, PDO::PARAM_STR);
    $stmt->bindParam(21, $diagnosis, PDO::PARAM_STR);

    $stmt->bindParam(22, $bp_input, PDO::PARAM_INT);
    $stmt->bindParam(23, $hr_input, PDO::PARAM_STR);
    $stmt->bindParam(24, $rr_input, PDO::PARAM_STR);

    $stmt->bindParam(25, $temp_input, PDO::PARAM_STR);
    $stmt->bindParam(26, $weight_input, PDO::PARAM_INT);
    $stmt->bindParam(27, $pe_findings_input, PDO::PARAM_STR);

    if($type === "OB"){
        $stmt->bindParam(28, $fetal_heart_inp, PDO::PARAM_STR);
        $stmt->bindParam(29, $fundal_height_inp, PDO::PARAM_STR);
        $stmt->bindParam(30, $cervical_dilation_inp, PDO::PARAM_STR);
        $stmt->bindParam(31, $bag_water_inp, PDO::PARAM_STR);
        $stmt->bindParam(32, $presentation_ob_inp, PDO::PARAM_STR);
        $stmt->bindParam(33, $others_ob_inp, PDO::PARAM_STR);
    }
    
    $stmt->execute();
   
    // updating the status of the person in the hperson table
    $sql = "UPDATE hperson SET status='Pending' WHERE hpercode=:hpercode ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':hpercode', $code, PDO::PARAM_STR);
    $stmt->execute();
    
    // updating for history log
    // incoming of the own hospital
    $sql = "SELECT hospital_code FROM sdn_hospital WHERE hospital_name=:hospital_name";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':hospital_name', $refer_to, PDO::PARAM_STR);
    $stmt->execute();
    $data_hospital_code = $stmt->fetch(PDO::FETCH_ASSOC);

    // check whos account is online on that hospital_code
    $sql = "SELECT username FROM sdn_users WHERE user_isActive=1 AND hospital_code=:hospital_code";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':hospital_code', $data_hospital_code['hospital_code'], PDO::PARAM_STR);
    $stmt->execute();
    $data_username = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $act_type = 'pat_refer';
    $action = 'Incoming Patient: ';
    $pat_name = $patlast . ' ' . $patfirst . ' ' . $patmiddle;
    $sql = "INSERT INTO history_log (hpercode, hospital_code, date, activity_type, action, pat_name, username) VALUES (?,?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(1, $code, PDO::PARAM_STR);
    $stmt->bindParam(2, $data_hospital_code['hospital_code'], PDO::PARAM_INT);
    $stmt->bindParam(3, $temp_referred_time, PDO::PARAM_STR);
    $stmt->bindParam(4, $act_type, PDO::PARAM_STR);
    $stmt->bindParam(5, $action, PDO::PARAM_STR);
    $stmt->bindParam(6, $pat_name, PDO::PARAM_STR);
    $stmt->bindParam(7, $data_username[0]['username'], PDO::PARAM_STR);

    $stmt->execute();

    // updating for history log
    // outgoing of the referral hospital
    $act_type = 'pat_refer';
    $action = 'Outgoing Patient: ';
    $pat_name = $patlast . ' ' . $patfirst . ' ' . $patmiddle;
    $sql = "INSERT INTO history_log (hpercode, hospital_code, date, activity_type, action, pat_name, username) VALUES (?,?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(1, $code, PDO::PARAM_STR);
    $stmt->bindParam(2, $_SESSION['hospital_code'], PDO::PARAM_INT);
    $stmt->bindParam(3, $temp_referred_time, PDO::PARAM_STR);
    $stmt->bindParam(4, $act_type, PDO::PARAM_STR);
    $stmt->bindParam(5, $action, PDO::PARAM_STR);
    $stmt->bindParam(6, $pat_name, PDO::PARAM_STR);
    $stmt->bindParam(7, $_SESSION['user_name'], PDO::PARAM_STR);

    $stmt->execute();

    $sql = "UPDATE hperson SET status='Pending', type='". $type ."' WHERE hpercode=:hpercode ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':hpercode', $global_single_hpercode, PDO::PARAM_STR);
    $stmt->execute();
?>