<?php
    session_start();
    include('../database/connection2.php');

    // $sql = "SELECT * FROM incoming_referrals WHERE refer_to='Bataan General Hospital and Medical Center' AND hpercode='BGHMC-0049'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();
    // $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($data); echo '</pre>';

    // $sql = "SELECT department FROM incoming_interdept WHERE hpercode='BGHMC-0049'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();
    // $data = $stmt->fetch(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($data); echo '</pre>';

    // $sql = "SELECT * FROM incoming_referrals WHERE refer_to='Bataan General Hospital and Medical Center' AND hpercode='BGHMC-0050'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();
    // $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($data); echo '</pre>';

    // $start_date = '2024-04-20';
    // $end_date = '2024-04-26';
    // $end_date_adjusted = date('Y-m-d', strtotime($end_date . ' +1 day'));

    // $sql = "SELECT  * FROM sdn_hospital";
    // $sql = "SELECT  * FROM sdn_users";
    // // $sql = "SELECT hpercode, final_progress_time FROM incoming_interdept WHERE final_progress_date >= '$start_date' AND final_progress_date < '$end_date_adjusted'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute(); 
    // $pat_class_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($pat_class_data); echo '</pre>';

    // $sql = "DELETE FROM sdn_hospital WHERE hospital_ID=196";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute(); 

    // $sql = "DELETE FROM incoming_referrals WHERE hpercode='BGHMC-0077'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timer Example</title>

    <style>
        body{
            background: black;
            color:white;
            font-size: 1.3rem;
        }
    </style>
</head>
<body>
    
    <script>
        var dataArray = ['Limay Medical Center', 'Limay Medical Center', 'Morong Bataan RHU', 'Morong Bataan RHU'];

        // Object to store counts of each element
        var counts = {};

        // Iterate over the array to count occurrences
        dataArray.forEach(function(item) {
            // Count occurrences of each element
            counts[item] = (counts[item] || 0) + 1;
        });

        // Array to store the unique elements
        var uniqueArray = Object.keys(counts);

        // Array to store the counts, matching length of uniqueArray
        var duplicatesCount = uniqueArray.map(function(item) {
            // Return count of each element
            return counts[item];
        });

        console.log("Unique array:", uniqueArray);
        console.log("Duplicates count:", duplicatesCount);
    </script>
</body>
</html>