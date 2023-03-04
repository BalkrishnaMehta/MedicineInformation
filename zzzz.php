<?php
$error="";
if (!empty($_POST)){
	$Email=$_POST['email'];
	$Password=$_POST['password'];

	setcookie('email',$Email,time()+60*60*24*30);
	setcookie('password',$Password,time()+60*60*24*30);
	$connect = new mysqli('localhost','root','','pps_impex');
	if(empty($Email)){
		$error = 'Empty field<br><span class="detail">Enter your email</span>';
	}
	else{
		if($connect->connect_error){
			die('Connection Failed: '.$connect->connect_error);
		}
		else{
			$stmt = $connect->prepare("select * from user where email = ?");
			$stmt->bind_param("s",$Email);
			$stmt->execute();
			$stmt_result = $stmt->get_result();
			if($stmt_result->num_rows > 0){
				$data = $stmt_result->fetch_assoc();
				if($data['password'] === $Password){
					if($data['role'] == 'owner'){
						setcookie('role','owner',time()+60*60*24*30);
						header('Location: owner_home.php');
					}
					else if($data['role'] == 'admin'){
						header('Location: admin_home.php');
						setcookie('role','admin',time()+60*60*24*30);
					}
					else{
						header('Location: index.php');
						setcookie('role','user',time()+60*60*24*30);
					}
				}
				else{
					$error = 'There was a problem<br><span class="detail">Your password is incorrect</span>';
				}
			}
			else{
				$error = 'Incorrect Email ID<br><span class="detail"> We can not find an account with that email id</span>';
				$_POST['email']="";
			}	
		}
	}
}
?>

<!DOCTYPE HTML>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="index.css">
		<title>PPS IMPEX Sign-In</title>
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
					<h4>Sign-In</h4><hr>
					<label for="mail"><h6>Email</h6></label>
					<input type="text" id="mail" name="email" value="<?php if(isset($_POST['email'])){echo $_POST['email'];}?>"><br><br>
					<label for="pass"><h6>Password</h6></label>
					<input type="password" id="pass" name="password"><br>
					<a href="forgot_password_email.php" id="link">Forgot Password?</a><br><br>
					<input type="submit" value="submit" id="btn"><br>
					<span style="font-size:0.75em;">Not a member yet?</span><a href="registration.php" id="link">Sign Up Now</a><br>
				</form>
			</div>
		</div>
	</body>
</HTML>
