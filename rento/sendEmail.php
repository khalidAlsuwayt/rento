<?php


require("PHPMailer-master/src/PHPMailer.php");
require("PHPMailer-master/src/SMTP.php");
require("PHPMailer-master/src/Exception.php");


//Load Composer's autoloader
//require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer\PHPMailer\PHPMailer();

    //Server settings
    $mail->SMTPDebug = 1;                      //Enable verbose debug output
    $mail->isSMTP();
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'khalid1996ksaa@gmail.com';                     //SMTP username
    $mail->Password   = 'KHalid@18';                               //SMTP password
    $mail->SMTPSecure = 'ssl';            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    echo $mail->Username;

    //Recipients
    $mail->setFrom('khalid1996ksaa@gmail.com', 'Mailer');
    $mail->addAddress($email, $name);     //Add a recipient
    //
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Activate Your Account';
    $mail->Body    = "Hi $name,<br> Please Click on The Link Below to Activate Your Account:<br>". 
    $config['app_url']."activation.php?token=$token";
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
     } else {
        echo "Message has been sent";
     }






?>