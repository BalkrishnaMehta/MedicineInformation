<?php
    if(isset($_COOKIE['email']) && isset($_COOKIE['password'])){
		if($_COOKIE['role'] == "owner" || $_COOKIE['role'] == "admin"){
            if(isset($_POST['submit'])){
                if($_FILES['image']['error'] === 0){
                    $ext = strtolower(pathinfo( $_FILES['image']['name'] , PATHINFO_EXTENSION));
                    if(in_array($ext, array("jpg","jpeg","png") )){
                        $new_image_name = uniqid("IMG-",true).".".$ext;
                        $path = 'images/'. $new_image_name;
                        move_uploaded_file($_FILES['image']['tmp_name'] , $path);
                        //header("Location: http://localhost/".basename(getcwd())."/".$path);
                        $connect = new mysqli('localhost','root','','pps_impex') or die('Connection Failed: '.$connect->connect_error);
                            $stmt = $connect->prepare("insert into data(image_url, title, short_detail, detail, type) values(?,?,?,?,?)");
                            $stmt->bind_param("sssss",$new_image_name,$_POST['title'],$_POST['short_detail'],$_POST['detail'],$_POST['type']);
                            $stmt->execute();
                            $stmt->close();
                            $connect->close();
                            header('Location: admin_home.php?type=%27'.$_GET['type'].'%27');
                    }
                }
            }
        }
        else{
			header('Location: index.php');
		}
    }
    else{
        header('Location: index.php');
    }
?>
<!DOCTYPE HTML>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>PPS IMPEX Upload Medicine</title>
        <link rel="icon" type="icon" href="jkjk.ico">
    </head>
    <body style="background-color:#FFFFF0;">
        <form method="post" enctype="multipart/form-data" action="">
        <div class="container">
            <div style="display:grid; place-items:center; margin-top:3em">
                <h4>Add Medicine</h4>
            </div>
        <table class="table table-striped table-bordered" style="margin-top:2em !important; margin:auto; width:40em; background-color:white;">
            <thead>
                <tr>
                    <th>Column</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Title</th>
                    <td><input type="text" name="title" required maxlength="20">  <span id="current">0</span><span id="maximum">/20</span></td>
                </tr>
                <tr>
                    <th>short_detail</th>
                    <td><input type="text" name="short_detail" required></td>
                </tr>
                <tr>
                    <th>Detail</th>
                    <td><textarea name="detail" required></textarea></td>
                </tr>
                <tr>
                    <th>Image</th>
                    <td><input type="file" name="image" id="image" required style="color: rgba(0, 0, 0, 0)"><img src="" id="imgPreview" style="display:none;"></td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td>
                        <select name="type" id="type">
                            <option value="Generic">Generic</option>
                            <option value="Ayurvedic">Ayurvedic</option>
                            <option value="Homeopathic">Homeopathic</option>
                            <option value="Veterinary">Veterinary</option>
                        </select>
                </td>
                </tr>
            </tbody>
            <tfoot>
                <td></td>
                <td><input type="submit" name="submit" value="upload"></td>
            </tfoot>
        </table>
        </div>
        </form>
        <?php
            $type = "'" . $_GET['type'] . "'";
        ?>
    </body>
    <script>
        if(<?php echo $type;?> == "Generic"){
            document.getElementById("type")[0].setAttribute('selected','selected');
        }
        if(<?php echo $type;?> == "Ayurvedic"){
            document.getElementById("type")[1].setAttribute('selected','selected');
        }
        if(<?php echo $type;?> == "Homeopathic"){
            document.getElementById("type")[2].setAttribute('selected','selected');
        }
        if(<?php echo $type;?> == "Veterinary"){
            document.getElementById("type")[3].setAttribute('selected','selected');
        }
        selector = document.getElementsByName('title');
        $(selector).keyup(function() {
        var characterCount = $(this).val().length,
            current = $('#current'),
            maximum = $('#maximum');
        
        current.text(characterCount);
        });


        const inpFile = document.getElementById('image');
        const previewimage = document.getElementById('imgPreview');
        inpFile.addEventListener("change",function(){
            const file = this.files[0];
            if(file){
                const reader = new FileReader();
                reader.addEventListener("load",function(){
                    previewimage.setAttribute("style","display:inline-block;margin-left:-140px; height:150px;width:150px;border-radius:10%;");
                    previewimage.setAttribute("src",this.result);
                });
                reader.readAsDataURL(file);
            }
            else{
                previewimage.setAttribute("style","display:none");
            }
        });
    </script>
<HTML>