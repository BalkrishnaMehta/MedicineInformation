<?php
    unset($_REQUEST['btn1']);
    $connect = new mysqli('localhost','root','','pps_impex') or die('Connection Failed: '.$connect->connect_error); 
    $stmt = $connect->prepare("UPDATE user SET role = ? WHERE role != ?;");
    $value1 = "user";
    $value2 = "owner";
    $stmt->bind_param("ss",$value1,$value2);
	$stmt->execute();
    foreach($_REQUEST as $key => $value){
        $stmt1 = $connect->prepare("UPDATE user SET role = ? WHERE name = ?;");
        $value1 =  "admin";
        $value2 =  str_replace("_"," ",$key);
        $stmt1->bind_param("ss",$value1,$value2);
	    $stmt1->execute();
    }
    $stmt->close();
	$connect->close();
    header('Location: index.php?set=');
?>