<?php
	session_start();
	$error="";
	$Code=0;
	if (!empty($_POST)){
		$OTP=$_POST['otp'];
		$Password1=$_POST['password'];
		$Password2=$_POST['cpassword'];
		$connect = new mysqli('localhost','root','','pps_impex');
		$stmt = $connect->prepare('select * from user where email = ?');
		$stmt->bind_param("s",$_SESSION['email']);
		$stmt->execute();
		$data = $stmt->get_result()->fetch_assoc();
		if($OTP == $data['code']){
			if (filter_var($Password1, FILTER_VALIDATE_REGEXP, array( "options"=> array( "regexp" => "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[a-zA-Z\d@$!%*?&]{8,20}$/"))) ){
				if($Password1 == $Password2){
					$stmt2 = $connect->prepare("update user set password = ? where email = ?");
					$stmt2->bind_param("ss",$Password1,$_SESSION['email']);
					$stmt2->execute();
					$stmt2 = $connect->prepare("update user set code = ? where email = ?");
					$stmt2->bind_param("is",$Code,$_SESSION['email']);
					$stmt2->execute();
					header('Location: zzzz.php');
				}
				else{
					$error = "There was a problem<br><span class='detail'> Password do not match";
				}
			}
			else{
				$error  = "There was a problem<br><span class='detail'>Password is not of required pattern";
			}
		}
		else{
			$error = "There was a problem<br><span class='detail'> Invalid otp provided";
		}
	}
?>
<!DOCTYPE HTML>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="index.css">
		<title>PPS IMPEX Forgot-Password</title>
        <link rel="icon" type="icon" href="jkjk.ico">
	</head>
	<body>
		<div class="grid-container">
			<div class="logo">
				<a href="index.php" style="text-decoration:none;">
					<img src="logo.png" style="height:3.5em; width:16em;">
				</a>
			</div>
			<div>
				<?php if(!empty($error)) { ?><div class="error"><span class="er">&#9888;</span><?php echo $error; ?></div><?php ; }?>
			</div>
			<div>
				<form action="" method="post">
					<h4>Reset password</h4><hr>
					<label for="otp"><h6>OTP</h6></label>
					<input type="text" id="otp" maxlength="6" name="otp"><br>
					<label for="pass1"><h6>Password</h6></label>
					<input type="password" placeholder="atleast 8 characters" id="pass1" name="password"><br>
					<label for="pass2"><h6>Confirm Password</h6></label>
					<input type="password" placeholder="atleast 8 characters"  id="pass2" name="cpassword"><br>
					<br><span id="demo" style="color:#3CCF4E;float:right;">Resend</span><br><br>
					<input type="submit" value="submit" id="btn">
				</form>
			</div>
		</div>
		<script>			
			var myButton = document.getElementById("demo");
			function sleep(ms) {
			      return new Promise(resolve => setTimeout(resolve, ms));
			}
			async function Tutor() {
				for(var i = 30; i>=1; i--) {
					await sleep(1000);
					myButton.innerHTML = i;

				}
				await sleep(1000);
				click();
			}
			Tutor();
			function click() {
				myButton.innerHTML = "<a href='sendmail.php' style='text-decoration:none;color:#3CCF4E;'>Resend</a><br>";
				
			}	
					
		</script>
	</body>
</html>
