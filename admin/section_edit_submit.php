<?php
    include('../include/link.php');
    include('../include/connect.php');

    $id= $_POST["id"];
    $sec_name = $_POST['sec_name'];
    $sec_id = $_POST['sec_id'];
    $old_sec_id = $_POST['old_sec_id'];
    $dept = $_POST['dept'];
    $status = $_POST['status'];
    
    $sections = mysqli_query($con, "SELECT * FROM section WHERE status = 1 AND sec_id = '$sec_id' AND sec_name = '$sec_name'");
    $checkrow = mysqli_num_rows($sections);
    if ($checkrow > 0){
        echo "<script type='text/javascript'>   $(document).ready(function(){ $('#exists').modal('show');   });</script>";
    }
    else {
        $con->next_result();   
        $query = "UPDATE section SET sec_name = '$sec_name', sec_id = '$sec_id', dept_id='$dept', status = '$status' WHERE id = '$id'";
        $result = mysqli_query($con, $query) or die('Error querying database.');

        $con->next_result();   
        $query = "UPDATE accounts SET sec_id = '$sec_id' WHERE sec_id = '$old_sec_id'";
        $result = mysqli_query($con, $query) or die('Error querying database.');

        $con->next_result();   
        $query = "UPDATE tasks SET task_for = '$sec_id' WHERE task_for = '$old_sec_id'";
        $result = mysqli_query($con, $query) or die('Error querying database.');

        $con->next_result();   
        $query = "UPDATE task_list SET task_for = '$sec_id' WHERE task_for = '$old_sec_id'";
        $result = mysqli_query($con, $query) or die('Error querying database.');

        echo "<script type='text/javascript'>   $(document).ready(function(){ $('#success').modal('show');   });</script>";
    }
?>

<div class="modal fade" id="exists" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <a href="section_list.php"><button type="button" class="close" aria-hidden="true">&times;</button></a>
                <h4 class="modal-title" id="myModalLabel">Warning!</h4>
            </div>
            <div class="modal-body panel-body">
                <center>
                    <i style="color:#e13232; font-size:80px;" class="fa fa-times-circle "></i>
                    <br><br>
                    Section Name or ID is already exists in the system!
                </center>
            </div>
            <div class="modal-footer">
                <a href="#" onclick="history.back()"><button type="button" name="submit" class="btn btn-danger pull-right">Return</button></a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <a href="section_list.php"><button type="button" class="close" aria-hidden="true">&times;</button></a>
                <h4 class="modal-title" id="myModalLabel">Updated!</h4>
            </div>
            <div class="modal-body panel-body">
                <center>
                    <i style="color:green; font-size:80px;" class="fa fa-check"></i>
                    <br><br>
                    <?php echo $sec_name?> is updated successfully!
                </center>
            </div>
            <div class="modal-footer">
                <a href="#" onclick="history.back()"><button type="button" name="submit" class="btn btn-danger pull-right">Return</button></a>
            </div>
        </div>
    </div>
</div>