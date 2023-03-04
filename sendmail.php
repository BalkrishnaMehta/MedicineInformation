<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	
	
	require 'PHPMailer/src/Exception.php';
	require 'PHPMailer/src/PHPMailer.php';
	require 'PHPMailer/src/SMTP.php';
	$error="";
	session_start();
	

	$Email = $_SESSION['email'];
	$Code= mt_rand(111111,999999);
	$connect = new mysqli('localhost','root','','pps_impex');
	if($connect->connect_error){
		die('Connection Failed '.$connect->connect_error);
	}
	else{
		$stmt = $connect->prepare("select * from user where email = ?");
		$stmt->bind_param("s",$Email);
		$stmt->execute();
		$stmt_result = $stmt->get_result();
		$data = $stmt_result->fetch_assoc();
		$stmt2 = $connect->prepare("update user set code = ? where email = ?");
		$stmt2->bind_param("is",$Code,$Email);
		$stmt2->execute();
		
		$mail = new PHPMailer(true);

		try {
			//Server settings
			$mail->isSMTP();                                            //Send using SMTP
			$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			$mail->Username   = 'ppsimpex16@gmail.com';                     //SMTP username
			$mail->Password   = 'uyocwfhjfqlwgcxy';                               //SMTP password
			$mail->SMTPSecure = 'ssl';            //Enable implicit TLS encryption
			$mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

			//Recipients
			$mail->setFrom('ppsimpex16@gmail.com', 'PPS IMPEX');
			$mail->addAddress($data['email'],$data['name']);     //Add a recipient name not compulsory
			$mail->addReplyTo('no-reply@gmail.com', 'No-reply');


			//Content
			$mail->isHTML(true);       //Set email format to HTML
			$mail->Subject = 'Password Reset Request';
			$mail->Body    = 'Hey '.$data['name'].'. Your password can be reset by verifying given otp. Your OTP is: '.$Code;
			$_SESSION["email"] = $data['email'];
			$mail->send();
			header("Location: forgot_password.php");
		} 
		catch (Exception $e) {
		    $_SESSION['error'] = "Message could not be sent.<br><span class='detail'> Invalid mail ID or Mailer Error: " .$mail->ErrorInfo."</span>";
		    //print_r($_SESSION);
		    header('Location: forgot_password_email.php');
		}
	}
?>
