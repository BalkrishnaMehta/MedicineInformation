<?php
	static $error="";
	session_start();
	if(!empty($_POST)){

		$Email = $_POST['email'];
		$connect = new mysqli('localhost','root','','pps_impex');
		if($connect->connect_error){
			die('Connection Failed '.$connect->connect_error);
		}
		else{
			$stmt = $connect->prepare("select * from user where email = ?");
			$stmt->bind_param("s",$Email);
			$stmt->execute();
			$stmt_result = $stmt->get_result();
			if($stmt_result->num_rows == 0){
				$error = "Invalid email<br><span class='detail'> We could not find that email";
			}
			else{
				$_SESSION['email']=$Email;
				header('Location: sendmail.php');
			}
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
					<label for="mail"><h6>Email</h6></label>
					<input type="text" id="mail" name="email"><br><br>
					<input type="submit" value="continue" id="btn"><br>
				</form>
			</div>
		</div>
	</body>
</HTML>
