
<?php

    require 'PHPMailer-6.8.1/PHPMailer-6.8.1/src/PHPmailer.php';
    require 'PHPMailer-6.8.1/PHPMailer-6.8.1/src/SMTP.php'; // Optional for SMTP support
    require 'PHPMailer-6.8.1/PHPMailer-6.8.1/src/Exception.php'; // Optional for error handling



    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    $mail = new PHPMailer(true);

?>