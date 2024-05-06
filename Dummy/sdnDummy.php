<?php
    

    
    include("../database/connection2.php");







    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $hospital_code = $_POST['hospital_code'];
        $hospital_name = $_POST['hospital_name'];
        $hospital_region_code = $_POST['hospital_region_code'];
        $hospital_province_code = $_POST['hospital_province_code'];
        $hospital_municipality_code = $_POST['hospital_municipality_code'];
        $hospital_barangay_code = ['hospital_barangay_code'];
        $hospital_zip_code = ['hospital_zip_code'];
        $hospital_email = $_POST['hospital_email'];
        $hospital_landline = $_POST['hospital_landline'];
        $hospital_mobile = $_POST['hospital_mobile'];
        $hospital_director = $_POST['hospital_director'];
        $hospital_director_mobile = $_POST['hospital_director_mobile'];
        $hospital_isVerified =$_POST['hospital_isVerified'];
        $hospital_OTP =$_POST['hospital_OTP'];
        $hospital_autKey =$_POST['hospital_autKey'];
        $hospital_isAuthorized = $_POST['hospital_isAuthorized'];

        $hash = password_hash($password, PASSWORD_DEFAULT);


       
        if ($password === $confirmPassword) {


            if(empty($hospital_code) || empty($hospital_name) || empty($hospital_region_code) || empty($hospital_province_code) || empty($hospital_municipality_code) || empty($hospital_barangay_code)|| empty($hospital_zip_code)|| empty($hospital_email)|| empty($hospital_landline)|| empty($hospital_mobile)|| empty($hospital_director)|| empty($hospital_director_mobile)|| empty($hospital_isVerified)|| empty($hospital_OTP)|| empty($hospital_autKey)|| empty($hospital_isAuthorized)) {
            // $status = "All fields are compulsory.";
        
            } else {
        
                $sql = "INSERT INTO sdn (hospital_code, hospital_name, hospital_region_code, hospital_province_code,
                                hospital_municipality_code, hospital_barangay_code, hospital_zip_code, hospital_email, hospital_landline, hospital_mobile, hospital_director,hospital_director_mobile,hospital_isVerified ,hospital_OTP ,hospital_autKey ,hospital_isAuthorized )
                        
                            VALUES (:hospital_code, :hospital_name, :hospital_region_code, :hospital_province_code, :hospital_municipality_code,
                                :hospital_barangay_code, :hospital_zip_code, :hospital_email, :hospital_landline, :hospital_mobile, :hospital_director, :hospital_director_mobile , :hospital_isVerified , :hospital_OTP,hospital_autKey ,hospital_isAuthorized)";    
        
                $stmt = $pdo->prepare($sql);
                
                $stmt->execute([ 'hospital_code' => $hospital_code,
                                'hospital_name' => $hospital_name,
                                'hospital_region_code' => $hospital_region_code,
                                'hospital_province_code' => $hospital_province_code,
                                'hospital_municipality_code' => $hospital_municipality_code,
                                'hospital_barangay_code' => $hospital_barangay_code,
                                'hospital_zip_code' => $hospital_zip_code,
                                'hospital_email' => $hospital_email,
                                'hospital_landline' => $hospital_landline, // Fix this line
                                'hospital_mobile' => $hospital_mobile,
                                'hospital_director' => $hospital_director,
                                'hospital_director_mobile' => $hospital_director_mobile,
                                'hospital_isVerified' => $hospital_isVerified,
                                'hospital_OTP' => $hospital_OTP,
                                'hospital_autKey' => $hospital_autKey,
                                'hospital_isAuthorized' => $hospital_isAuthorized,
                               
                ]);
                    
                $hospital_name = "";
                $point_person = ""; 
                $address_province = "";
                $address_municipality = "";
                $address_barangay = "";
                $email_address = "";
                $landline_no = "";
                $mobile_no = "";
                $alternate_no = "";
                $username = "";
                $password = "";

                // echo "Registered Successfully";
                echo isset($stmt);
            }
            }
        }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <form action="sdnDummy.php" method="post">
      <input type="text" name="hospital_name" placeholder="Hospital Name"><br>
      <input type="text" name="point_person" placeholder="Point Person"><br>
      <input type="text" name="address_province" placeholder="Address:Province"><br>
      <input type="text" name="address_municipality" placeholder="Address:Municipality"><br>
      <input type="text" name="address_barangay" placeholder="Address:Barangay"><br>
      <input type="email" name="email_address" placeholder="Email Address"><br>
      <input type="number" name="landline_no" placeholder="Landline No"><br>
      <input type="number" name="mobile_no" placeholder="Mobile No"><br>
      <input type="number" name="alternate_no" placeholder="Alternate Mobile No"><br>
      <input type="text" name="username" placeholder="Username"><br>
      <input type="password" name="password" placeholder="Password"><br>

      <input type="submit" name="submit" value="Register">
    </form>

</body>
</html>