<?php
    session_start();
	$name="Login";
	$link="zzzz.php";
	if(!array_key_exists("type", $_GET)) {
		$_GET["type"] = "'Generic'";
	}
	
	if(isset($_COOKIE['email']) && isset($_COOKIE['password'])){
        switch($_COOKIE['role']) {
            case "user": {
                header("location: home.php");
                break;
            }
            case "admin": {
                header("location: admin_home.php");
                break;
            }
        }

		$connect = new mysqli('localhost','root','','pps_impex') or die('Connection Failed: '.$connect->connect_error);
		
        $stmt = $connect->prepare("select name from user where email = ?");
        $stmt->bind_param("s",$_COOKIE['email']);
        $stmt->execute();
        $stmt_result = $stmt->get_result();
        $data1 = $stmt_result->fetch_assoc();
        $name=$data1['name'];
        $link="logout.php";
	}
    else{
        header('Location: home.php');
    }
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
         $url = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];   
    else  
         $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $_SESSION['url'] = $url;

?>
<!DOCTYPE HTML>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="home.css">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top" id="nav">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-items" aria-controls="navbar-items" aria-expanded="false" aria-label="Toggle navigation">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <a  class="navbar-brand" href="owner_home.php" style="margin-left:0.4em;margin-right:0.4em">
                <img src="logo.png" style="height:2.2em; width:9em;">
            </a>
            <form class="search-field" style="margin-left:0.4em;margin-right:0.4em">
                <input type="search" placeholder="Search" name="search" autocomplete="off" style="padding-left: 8px;" value="<?php if(isset($_REQUEST['search'])){echo $_GET['search'];} ?>" style="width:15em;">
                <button class="btn" style="background-color:#FFE9A0; padding-top:5px; width:3em;">
                    <img src="search.svg" style="height:1.5em;width:1.5em;">
                </button>
            </form>
            <div class="collapse navbar-collapse" id="navbar-items" style="margin-left:0.4em;margin-right:0.4em">
                <ul class="items navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link active" href="owner_home.php?type='Generic'">Generic</a></li>
                    <li class="nav-item"><a class="nav-link" href="owner_home.php?type='Ayurvedic'">Ayurvedic</a></li>
                    <li class="nav-item"><a class="nav-link" href="owner_home.php?type='Homeopathic'">Homeopathic</a></li>
                    <li class="nav-item"><a class="nav-link" href="owner_home.php?type='Veterinary'">Veterinary</a></li>
                    <li class="nav-item"><a class="nav-link" href="editAdmin/">Edit Admin</a></li>
                    <li class="nav-item"><a class="nav-link" href='<?php echo $link; ?>'>
                    
                    Hello, <?php echo $name;?></a></li>
                </ul>
            </div>
        </nav>
        <?php
            if(!empty($_REQUEST['search'])){
                $search_value = $_REQUEST['search'];
                $value = "%".$search_value."%";
                $connect = new mysqli('localhost','root','','pps_impex') or die('Connection Failed: '.$connect->connect_error);
                
                $stmt1 = $connect->prepare("select * from data where concat(title, short_detail, detail) LIKE ?");
                $stmt1->bind_param("s",$value);
                $stmt1->execute();
                $stmt_result1 = $stmt1->get_result();
                if($stmt_result1->num_rows > 0){
                    while($row = $stmt_result1->fetch_assoc()){
                        $data[] = $row;
                    }
                }
                else{
                    echo "<br><br><br>No result for $search_value<br>";
                    echo "Try checking your spelling or use more general terms";
                }
            }
            else{
                $type = str_replace("'","", $_GET["type"]);
                $connect = new mysqli('localhost','root','','pps_impex') or die('Connectio Failed: '.$connect->connect_error);
                
                $stmt = $connect->prepare("select * from data where type = ?");
                $stmt->bind_param("s",$type);
                $stmt->execute();
                $stmt_result = $stmt->get_result();
                while($row = $stmt_result->fetch_assoc()){
                    $data[] = $row;
                }
            }
            echo "<div class='parent'><div id='jkjk'></div>";
            if(empty($_REQUEST['search'])){
                echo "<div class='add' style='text-align:center; display:flex; flex-flow: row nowrap; place-items:center;'>
                        <img src=add.png class='cimg'>
                    </div>";
            }
            if(isset($data)){
                $_SESSION['data']=$data;
                for($i=0;$i<count($data);$i++){
                    $image =  $data[$i]['image_url'];
                    $title = $data[$i]['title'];
                    $short_detail = $data[$i]['short_detail'];
                    $id = $data[$i]['id'];
                        echo "<div class='card1' onclick='openWin($id)'>
                                    <a href='editDetail.php?id=$id'>
                                        <img src='pencil1.png' class='editimg'>
                                    </a>
                                    <img src=images/$image class='cimg'>
                                    <div class='container'>
                                    <h5><b>$title</b></h5> 
                                    <p style=' overflow: hidden; white-space: nowrap; text-overflow: ellipsis;'>$short_detail</p> 
                                    </div>
                                </div>";
                }
                echo "</div>";
            }
        ?>
    </body>
    <script>
		var links = document.getElementsByTagName("a");
		for(i = 1; i < 5; i++) {
			links[i].classList.remove("active");
		}
        if(<?php echo $_GET["type"]; ?> == "Generic"){
            links[1].classList.add("active");
        }
        if(<?php echo $_GET["type"]; ?> == "Ayurvedic"){
            links[2].classList.add("active");
        }
        if(<?php echo $_GET["type"]; ?> == "Homeopathic"){
            links[3].classList.add("active");
        }
        if(<?php echo $_GET["type"]; ?> == "Veterinary"){
            links[4].classList.add("active");
        }
        if(typeof <?php if(!empty($_GET["search"])) echo $_GET["search"]; else{echo 1;}?> === 'undefined'){
            for(i = 1; i < 5; i++) {
			    links[i].classList.remove("active");
		    }
        }
		
        for (var i = 1; i < 5; i++) {
            if (links[i].classList.contains("active")) {
                var type = links[i].text;
            }
        }
        const element = document.querySelector(".add");
        element.addEventListener("click", () => {
            window.open("addContent.php?type=" +type,"_self");
        });

        function openWin(i) {
            window.open("details.php?id=" + i,"_self");
        }

        window.addEventListener('load', function(){
          document.getElementById("jkjk").style.display = "none";
        });
    </script>
<HTML>