<?php 
    include("../database/connection2.php");

    include("../Dummy/PHPMailer-6.8.1/src/PHPMailer.php");
    include("../Dummy/PHPMailer-6.8.1/PHPMailer-6.8.1/src/SMTP.php"); // Optional for SMTP support
    include("../Dummy/PHPMailer-6.8.1/PHPMailer-6.8.1/src/Exception.php"); // Optional for error handling

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    $mail = new PHPMailer(true);

    $hospital_code = $_POST['hospital_code'];
    $OTP = $_POST['OTP'];

    $sql = "UPDATE sdn_hospital SET hospital_OTP = :OTP WHERE hospital_code=:hospital_code";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':hospital_code', $hospital_code, PDO::PARAM_INT);
    $stmt->bindParam(':OTP', $OTP, PDO::PARAM_INT);

    if ($stmt->execute()) {
        //SENDING EMAIL
        try {                
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF; // Set the debugging level (options: DEBUG_OFF, DEBUG_CLIENT, DEBUG_SERVER)
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->AuthType = 'PLAIN'; 
            $mail->Host = 'smtp.gmail.com'; // Specify your SMTP server
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'nepojohn031@gmail.com'; // SMTP username 
            $mail->Password = 'pzvidyfmvhdfgdwc'; // SMTP password
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, 'ssl' also accepted
            $mail->Port = 587; // TCP port to connect to

            //Recipients
            
            $mail->addAddress($_POST['email']);
            //Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Job Order';
            $mail->Body = $OTP; // OTP value from sdn_reg
            $mail->AltBody = 'This is the plain text message body';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
?>