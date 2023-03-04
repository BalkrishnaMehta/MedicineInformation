<?php
	$Code = mt_rand(111111,999999);
	$error ="";
	if (!empty($_POST)){
		$Name = $_POST["name"];
		$MobileNo = $_POST["number"];
		$Email = $_POST["email"];
		$Password = $_POST["pass"];
		if(empty($Name)){
			$error = 'Empty field<br><span class="detail">Enter your name</span>';
		}
		elseif(empty($MobileNo)){
			$error = 'Empty field<br><span class="detail">Enter your mobile number</span>';
		}
		elseif(!is_numeric($MobileNo) || strlen($MobileNo) != 10){
			$error = 'There was a problem<br><span class="detail">Your mobile number is not of reqired format</span>';
			$_POST["number"]="";	
		}
		elseif(!preg_match("/^[6789]+\d{9}$/", $MobileNo)){
			$error = 'There was a problem<br><span class="detail">Invalid Mobile Number</span>';
			$_POST["number"]="";	
		}
		elseif(empty($Email)){
			$error = 'Empty field<br><span class="detail">Enter your email</span>';
		}
		elseif(!preg_match("/^([a-zA-Z0-9\.]+@+[a-zA-Z]+(\.)+[a-zA-Z]{2,3})$/", $Email)){
			$error = 'There was a problem<br><span class="detail">Your email is not of reqired format</span>';
			$_POST["email"]="";
		}
		elseif(empty($Password)){
			$error = 'Empty field<br><span class="detail">Enter your password</span>';
		}
		elseif(strlen($Password) < 8) {
			$error = "Password too short!";
			$_POST["pass"]="";
		}
		elseif( strlen($Password) > 20 ) {
			$error = "Password too long!";
			$_POST["pass"]="";
		}
		elseif( !preg_match("#[0-9]+#", $Password ) ) {
			$error = "Password must include at least one number!";
			$_POST["pass"]="";
		}
		elseif( !preg_match("#[a-z]+#", $Password) ) {
			$error = "Password must include at least one letter!";
			$_POST["pass"]="";
		}
		elseif( !preg_match("#[A-Z]+#", $Password ) ) {
			$error = "Password must include at least one CAPS!";
			$_POST["pass"]="";
		}
		elseif( !preg_match("#[@$!%*?&]#", $Password ) ) {
			$error = "Password must include at least one symbol!";
		}
		elseif(!array_key_exists("vcode",$_REQUEST)){
			$error = "Email Not Verified";
		}
		elseif(array_key_exists("vcode",$_REQUEST) && $_REQUEST['vcode'] != $_REQUEST['Vcode']){
			$error = "Email Not Verified";
		}
		else{
			$connect = new mysqli('localhost','root','','pps_impex');
			if($connect->connect_error){
				die('Connection Failed: '.$connect->connect_error);
			}
			else{
				$stmt = $connect->prepare("select * from user where email = ?");
				$stmt->bind_param("s",$Email);
				$stmt->execute();
				$stmt_result = $stmt->get_result();
				if($stmt_result->num_rows == 0){
					$stmt2 = $connect->prepare("insert into user(name, mobileNo, email, password)
						values(?,?,?,?)");
					$stmt2->bind_param("siss",$Name,$MobileNo,$Email,$Password);
					$stmt2->execute();
					$stmt2->close();
					$connect->close();
					header('Location: zzzz.php');
				}
				else{
					$error ="email already exists.";
				}
			}
		} 
	}
?>
<!DOCTYPE HTML>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="index.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script
			src="https://code.jquery.com/jquery-3.6.1.min.js"
			integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ="
			crossorigin="anonymous">
		</script>
		<title>PPS IMPEX Sign-up</title>
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
					<h4>Create Account</h4><hr>
					<input type="hidden" name="Vcode" value="<?php echo $Code;?>">
					<label for="name"><h6>Your name</h6></label>
					<input type="text" placeholder="First and last name" id="name" name="name" value="<?php  if(isset($_POST['name'])){ echo $_POST['name'];}?>"><br><br>
					<label for="num"><h6>Mobile number</h6></label>
					<input type="tel" placeholder="Mobileno" id="num" name="number" value="<?php  if(isset($_POST['number'])){echo $_POST['number'];}?>"><br><br>
					<label for="mail"><h6>Email</h6></label>
					<input type="text" id="mail" name="email" placeholder="Email ID" value="<?php  if(isset($_POST['email'])){echo $_POST['email'];}?>">
					<input type="submit" id="send" style="float:right; margin:0.7em;" onclick="return false;" value="Send Code">
					<div id="verify"></div><br><br>
					<label for="pass"><h6>Password</h6></label>
					<input type="password" placeholder="atleast 8 characters"  id="pass" name="pass" name="pass" value = "<?php  if(isset($_POST['pass'])){echo $_POST['pass'];}?>"><br><br>
					<input type="submit" value="submit" id="btn"><br>
					<span style="font-size:0.75em;">Already a member?</span><a id="link" href="zzzz.php">Sign-In</a><br>
				</form>
			</div>
		</div>
	</body>
	<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script>
		$("#send").click(function(event){
			var a = document.getElementById('mail').value;
			if(!a.includes("@") || !a.includes(".") || a.includes(" ")) {
			 	return;
			}
    		event.preventDefault();
			add = '<br><br><label for="vcode"><h6>Verification Code</h6></label><input id="vcode" name="vcode" type="tel" maxlength="6" size="6"> <input type="button" id="verification" name="verification" style="float:right; margin:0.7em; " onclick="vrun()" value="Verify">';
			if ($('#vcode').length === 0) {
				$('#verify').append(add);
			}
			$.ajax({
				dataType: 'JSON',
				url: 'sendmail1.php',
				type: 'POST',
				//data: $('#contact').serialize(),
				data: 'userName='+$("#name").val()+'&Email='+ $("#mail").val()+'&mobileNo='+ $("#num").val()+'&Code='+ <?php echo $Code;?>,
				beforeSend: function(xhr){
					$('#send').val('SENDING...');
					},
					success: function(response){
					},
					error: function(){
        			},
					complete: function(){
						$('#send').val('SENT');
					}
			});
		});
		function vrun(){
			if($("#vcode").val() == <?php echo $Code;?>){
				$('#verification').val('Verified');
			}
			else{
				$('#verification').css("border","1.4px solid red");
				$('#verification').effect("shake",{times:2 , distance:5 , direction:"left"}, 700);
			}
		}
	</script>
</HTML>
