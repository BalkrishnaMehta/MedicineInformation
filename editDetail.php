<?php
    session_start();
    $conn = new mysqli('localhost','root','','pps_impex') or die('Connection Failed: '.$connect->connect_error);
    if(isset($_REQUEST['btn2'])){
        unset($_REQUEST['btn2']);
        foreach($_REQUEST as $key => $value){
            if($key == "type"){
                $type =  $value;
            }
            if($key == "id"){
                $id =  $value;
            }
            else{
                $stmt = $conn->prepare("UPDATE data 
                                            SET $key = ?
                                            WHERE id = ?;");
                $stmt->bind_param("si",$value,$id);
                $stmt->execute();
                header('Location: admin_home.php');
            }
        }
    }
    if(isset($_REQUEST['btn1'])){
        unset($_REQUEST['btn1']);
        $url = $_REQUEST['image_url'];
        $stmt = $conn->prepare("DELETE FROM data WHERE id = ?;");
        $stmt->bind_param("i",$_REQUEST['id']);
        $stmt->execute();
        unlink("images/".$url);
        header('Location: admin_home.php?type=%27'.$_REQUEST['type'].'%27');
    }
    $conn->close();
?>
<!DOCTYPE HTML>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <title>PPS IMPEX Edit-Details of <?php echo $_GET['id'];?></title>
        <link rel="icon" type="icon" href="jkjk.ico">
    </head>
    <body style="background-color:#FFFFF0;">
    <form action="" method="post">
        <div class="container">
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Delete!!!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mytext" id="p1"> 
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-primary" data-dismiss="modal" value="No">
                    <input type="submit" name="btn1" class="btn btn-danger" value = "Delete it">
                </div>
                </div>
            </div>
        </div>
        <div style="display:grid; place-items:center; margin-top:3em">
                <h4>Edit Medicine Details</h4>
            </div>
        <table class="table table-striped table-bordered" style="margin-top:2em !important; margin:auto; width:40em; background-color:white;">
            <thead>
                <tr>
                    <th>Column</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(isset($_SESSION['data'])){
                    $data = $_SESSION['data'];
                    $id = $_GET['id'];
                    $arr = array('Generic','Ayurvedic','Homeopathic','Veterinary');
                    for($i=0;$i<count($data);$i++){
                        if($data[$i]['id'] == $id){
                            foreach($data[$i] as $key=>$value){
                                echo "<tr>";
                                echo "<th>".$key."</th>";
                                if($key=="detail"){
                                    echo "<td><textarea rows='4' cols='35' required name='$key'>$value</textarea></td>";
                                }
                                elseif($key=="type"){
                                    echo "<td><select name='$key'>";
                                        for($j=0;$j<count($arr);$j++){
                                            $dis="";
                                            if($arr[$j] == $data[$i]['type']){
                                                $dis="selected";
                                            }
                                            echo "<option value='$arr[$j]' $dis>$arr[$j]</option>";
                                        }
                                    echo "</select> </td>";
                                }
                                else{
                                    $maxlength = "524288";
                                    $dis="";
                                    $forcount = "";
                                    if($key=="id" || $key=="image_url"){
                                        $dis="readonly";
                                    }
                                    if($key=="title"){
                                        $maxlength="20";
                                        $forcount = '<span id="current">?</span><span id="maximum">/20</span>';
                                    }
                                    echo "<td><input type='text' size='30' name='$key' id=$key required $dis maxlength = $maxlength value= '$value'>   $forcount</td>";
                                }
                                echo "</tr>";
                            }
                        }
                    }
                }
                else{
                    header('Location: index.php');
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <!-- <td><input type="Submit" value="Delete" name="btn1"></td> -->
                    <td><input type="button" value="Delete" data-toggle="modal" data-target="#deleteModal"></td>
                    <td><input type="Submit" value="Submit" name="btn2"></td>
                </tr>
            </tfoot>
        </table>
        </div>
        </form>
    </body>
    <script>
        selector = document.getElementsByName('title');
        $(selector).keyup(function() {
        var characterCount = $(this).val().length,
            current = $('#current'),
            maximum = $('#maximum');
        
        current.text(characterCount);
        });
        document.getElementById("p1").innerHTML = "Are you sure you want to delete " + document.getElementById('title').value;
    </script>
</HTML>