<?php
  echo"here";
  // try {
  //     include("../database/connection2.php");

  //     $usernameToCheck = $_POST["username"];
      
  //     $stmt = $pdo->prepare("SELECT * FROM telemedicine WHERE username = :username");
  //     $stmt->bindParam(":username", $usernameToCheck, PDO::PARAM_STR);
  //     $stmt->execute();

  //     if ($stmt->rowCount() > 0) {
  //         echo "Username is taken.";
  //     } else {
  //         echo "Username is available.";
  //     }
  // } catch (PDOException $e) {
  //     die("Error: " . $e->getMessage());
  // }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Check Username Availability</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <form>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" onkeyup="checkUsernameAvailability(this.value)">
        <p id="availability"></p>
    </form>

    <script>
        function checkUsernameAvailability(username) {
            $.ajax({
                type: "POST",
                url: "keyup.php",
                data: { username: username },
                success: function (response) {
                    $("#availability").html(response);
                }
            });
        }
    </script>
</body>
</html>