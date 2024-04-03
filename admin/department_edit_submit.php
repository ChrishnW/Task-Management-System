<div class="modal fade" id="exists" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content panel-danger">
            <div class="modal-header panel-heading">
                <a href="department_list.php"><button type="button" class="close" aria-hidden="true">&times;</button></a>
                <h4 class="modal-title" id="myModalLabel">Warning!</h4>
            </div>
            <div class="modal-body panel-body">
                <center>
                    <i style="color:#e13232; font-size:80px;" class="fa fa-times-circle "></i>
                    <br><br>
                    Department Name already exists!
                </center>
            </div>
            <div class="modal-footer">
                <a href="department_list.php"><button type="button" name="submit"
                        class="btn btn-danger pull-right">Return</button></a>
            </div>
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>

<?php
include('../include/link.php');
include('../include/connect.php');

$dept_id = $_POST['dept_id'];
$dept_name = $_POST['dept_name'];
$status = $_POST['status'];
$id= $_POST["id"];

$con->next_result();
$check1=mysqli_query($con,"SELECT * FROM department WHERE dept_name='$dept_name' AND id!=$id");
$checkrows1=mysqli_num_rows($check1);

if($checkrows1>0) {
    echo "<script type='text/javascript'>   $(document).ready(function(){ $('#exists').modal('show');   });</script>";         
    exit;
} 
else {
    $con->next_result();   
    $query = "UPDATE department SET dept_id = '$dept_id', dept_name = '$dept_name', status = '$status'  WHERE id = '$id'";
    $result = mysqli_query($con, $query) or die('Error querying database.');

    $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('$dept_name department updated.', '$systemtime', 'ADMIN')";
    $result = mysqli_query($con, $systemlog);
    header('location: department_list.php'); 
}
?>