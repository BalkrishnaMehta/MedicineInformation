<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	
	
	require 'PHPMailer/src/Exception.php';
	require 'PHPMailer/src/PHPMailer.php';
	require 'PHPMailer/src/SMTP.php';
	$error="";
	session_start();
	

	$Email = $_POST["Email"];
	$name = $_POST["userName"];
	$mobileNo = $_POST["mobileNo"];
	$Code = $_POST["Code"];
		
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();                                            
        $mail->Host       = 'smtp.gmail.com';                    
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'ppsimpex16@gmail.com';                    
        $mail->Password   = 'uyocwfhjfqlwgcxy';                              
        $mail->SMTPSecure = 'ssl';            
        $mail->Port       = 465;                                   


        $mail->setFrom('ppsimpex16@gmail.com', 'PPS IMPEX');
        $mail->addAddress($Email,$name);     
        $mail->addReplyTo('no-reply@gmail.com', 'No-reply');



        $mail->isHTML(true);     
        $mail->Subject = 'Email Verification';
        $mail->Body    = 'Hey '.$name.'. Your need to verify your Email. Your OTP is: '.$Code;
        $mail->send();

    } 
    catch (Exception $e) {
        echo "Message could not be sent.<br><span class='detail'> Invalid mail ID or Mailer Error: " .$mail->ErrorInfo."</span>";
    }
?>
