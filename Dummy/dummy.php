<?php
//   session_start();
//   include '../database/connection.php';
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    

</body>
</html>



<?php
    $dateTimeString = "2023-10-8 8:48:5";
    $dateTime = new DateTime($dateTimeString);

    // Format the DateTime object as needed
    $formattedDateTime = $dateTime->format("Y-m-d H:i:s");

    $year = $dateTime->format("Y");
    $month = $dateTime->format("m");
    $day = $dateTime->format("d");
    $hours = $dateTime->format("H");
    $minutes = $dateTime->format("i");
    $seconds = $dateTime->format("s");

    echo "Year: " . $year . "<br>";
    echo "Month: " . $month . "<br>";
    echo "Day: " . $day . "<br>";
    echo "Hours: " . $hours . "<br>";
    echo "Minutes: " . $minutes . "<br>";
    echo "Seconds: " . $seconds . "<br>";
?>
