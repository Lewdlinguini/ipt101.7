<?php
require 'vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendVerificationCode($email, $verification_code) {
    $mail = new PHPMailer();
    
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; 
    $mail->SMTPAuth = true;
    $mail->Username = 'markcussy032@gmail.com'; 
    $mail->Password = 'njay ifhp sbhm zoli'; 
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    
    $mail->setFrom('markcussy032@gmail.com', 'Mark Cussy');
    $mail->addAddress($email);
    $mail->Subject = 'Verification Code';
    $mail->Body = 'Your verification code is: ' . $verification_code;

    if ($mail->send()) {
        return true;
    } else {
        return false;
    }
}
?>