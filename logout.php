<?php
    if(isset($_COOKIE['email']) && isset($_COOKIE['password'])){
        $connect = new mysqli('localhost','root','','pps_impex') or die('Connection Failed: '.$connect->connect_error);
        $stmt = $connect->prepare("select * from user where email = ?");
        $stmt->bind_param("s",$_COOKIE['email']);
        $stmt->execute();
        $stmt_result = $stmt->get_result();
        $data1 = $stmt_result->fetch_assoc();
        $class = "bg-secondary";
        if($data1['role']=="owner"){
            $class = "bg-success";
        }
        if($data1['role']=="admin"){
            $class = "bg-primary";
        }
        if(isset($_POST['logout'])){
            setcookie('email','',time()-60*60);
            setcookie('password','',time()-60*60);
            setcookie('user','',time()-60*60);
            header('Location: index.php');
        }
    }
    else{
        header('Location: index.php');
    }
?>
<!DOCTYPE HTML>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <title>PPS IMPEX Sign-out</title>
        <link rel="icon" type="icon" href="jkjk.ico">
    </head>
    <body style="background-color: #FFFFF0; margin-top:3em">
        <div style="display:grid; place-items:center;">
                <a href="index.php" style="text-decoration:none;">
                    <img src="logo.png" style="height:3.5em; width:16em;">
                </a>
        </div>
        <div style="display:grid; place-items:center; margin:auto; width:18rem; border:2px solid; margin-top:2em !important; padding:1em; background-color:white !important;">
            <img src="avatar.gif" style="height:150px;width:150px; border: 1px solid black;border-radius:50%; background-color:#D8D8D8; margin:1em;">
            <p><?php echo $data1['name']."  ";?><span class="badge rounded-pill <?php echo $class;?>" style=""><?php echo $data1['role'];?></span></p>
            <p><?php echo $data1['email']."  ";?></p>
            <p><?php echo $data1['mobileNo']."  ";?></p>
            <form method="post" action="">
                <input type="submit" name="logout" value="Logout" style=" width:80px; padding: 0.4em; background-color:#F94C66; color:white; font-weight:bold; border-radius:0.25em; border: 0.0625em solid #937DC2;">
            </form>
        </div>
    </body>
</HTML>