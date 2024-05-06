<?php
    include("../database/connection2.php");

    // $hospital_name = $_POST['hospital_name'];
    $hospital_code = $_POST['hospital_code'];

    // $region = $_POST['region'];
    // $province = $_POST['province'];
    // $municipality = $_POST['municipality'];
    // $barangay = $_POST['barangay'];
    // $zip_code = $_POST['zip_code'];

    // $email = $_POST['email'];
    // $landline_no = $_POST['landline_no'];
    // $hospital_mobile_no = $_POST['hospital_mobile_no'];

    // $hospital_director = $_POST['hospital_director'];
    // $hospital_director_mobile_no = $_POST['hospital_director_mobile_no'];
 
    // $point_person = $_POST['point_person'];
    // $point_person_mobile_no = $_POST['point_person_mobile_no'];
    // $OTP = $_POST['OTP'];


    $sql = "DELETE FROM sdn_hospital WHERE hospital_code=:hospital_code";
            
    
    // $sql = "INSERT INTO sdn_hospital (hospital_code, hospital_region_code) VALUES (?,?)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':hospital_code', $hospital_code, PDO::PARAM_INT);

    // $stmt->bindParam(1, $hospital_code, PDO::PARAM_INT);
    // $stmt->bindParam(2, $region, PDO::PARAM_STR);
    if ($stmt->execute()) { 
        echo 'deleted';
    }
?>