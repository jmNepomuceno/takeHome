

<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%; /* Adjust the width as needed */
            border-collapse: collapse;
            margin: 20px; /* Add margin for spacing */
        }

        th, td {
            padding: 10px; /* Add padding to cells for spacing */
            text-align: left; /* Align cell content to the left */
            border: 1px solid #ccc; /* Add borders for cells */
        }

        th {
            background-color: #f2f2f2; /* Add background color to header cells */
        }
    </style>
</head>
<body>

        <form action="Records.php" method="post">


       <td><input type="submit" name= "delete" value="Delete"></td>';





        </form>



<?php




    try {
        $pdoConnect = new PDO("mysql:host=localhost;dbname=telemedecine_services", "root", "S3rv3r");
    } catch (PDOException $exc) {
        echo $exc->getMessage();
        exit();
    }
    
    // Execute a SELECT query to retrieve data
    $pdoQuery = "SELECT * FROM `telemdecine`";
    $pdoResult = $pdoConnect->query($pdoQuery);
    
    // Check if the query was successful
    if ($pdoResult) {
        echo '<table border="1">';
        echo '<tr>';
        echo '<th>First Name</th>';
        echo '<th>Last Name</th>';
        echo '<th>Middle Name</th>';
        echo '<th>Birthdate</th>';
        echo '<th>Mobile No.</th>';
        echo '<th>Username</th>';
        echo '<th>Password</th>';
        echo '<th>Password</th>';
        // Add more table headers for additional columns as needed
        echo '</tr>';
    
        // Fetch and display the data
        while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . $row['first_name'] . '</td>';
            echo '<td>' . $row['last_name'] . '</td>';
            echo '<td>' . $row['middle_name'] . '</td>';
            echo '<td>' . $row['birthday'] . '</td>';
            echo '<td>' . $row['mobileno'] . '</td>';
            echo '<td>' . $row['user_name'] . '</td>';
            echo '<td>' . $row['password'] . '</td>';
            

            // Add more cells for additional columns as needed
            echo '</tr>';
        }


        // if(isset([$_POST["delete"]]){






        // }

    
        echo '</table>';
    } else {
        echo 'Query Failed';
    }







?>