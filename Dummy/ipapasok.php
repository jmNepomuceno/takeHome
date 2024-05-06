<?php

  include('../database/connection2.php');

    if(isset($_POST['ipasok'])){


        try {
        
            // connect to mysql
    
            $pdoConnect = new PDO("mysql:host=localhost;dbname=bghmc","root","S3rv3r");
        } catch (PDOException $exc) {
            echo $exc->getMessage();
            exit();
        }
    


        $region_code = $_POST['region_code'];
        $region_description = $_POST['region_description'];
        // $name3 = $_POST['name3'];
    
        $pdoQuery = "INSERT INTO region(region_code,region_description)
             VALUES (:region_code, :region_description)";

           


            
$pdoResult = $pdoConnect->prepare($pdoQuery);

$pdoResult->bindParam(":region_code", $region_code);
$pdoResult->bindParam(":region_description", $region_description);

$pdoExec = $pdoResult->execute();
    // ":middle_name" => $middlename,
    // ":selected_date" => $birthday,
    // ":mobileno" => $mobileno,
    // ":user_name" => $username,
    // ":password" => $passwords,


    // check if mysql insert query successful
if($pdoExec)
{
    echo 'Data Inserted';
}



    }




?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link rel="stylesheet" href="style.css">
    <title>Document</title>




</head>
<body>


    <form action="ipapasok.php" method="post">

    Region Code:
    <input type="text" name="region_code"><br>
    Region Description:
    <input type="text" name="region_description">  <br>
    <!-- <input type="text" name="name3"><br> -->


    <input type="submit" name="ipasok"  value="ipasok"> <br>







    </form>


    
</body>
</html>