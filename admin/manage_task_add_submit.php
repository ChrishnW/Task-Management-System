<div class="modal fade" id="exists" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content panel-danger">
            <div class="modal-header panel-heading">
                <a href="account_add.php"><button type="button" class="close"
                        aria-hidden="true">&times;</button></a>
                <h4 class="modal-title" id="myModalLabel">Warning!</h4>
            </div>
            <div class="modal-body panel-body">
                <center>
                    <i style="color:#e13232; font-size:80px;" class="fa fa-times-circle "></i>
                    <br><br>
                    Task already exists under this employee!
                </center>
            </div>
            <div class="modal-footer">
                <a href="manage_task_add.php"><button type="button" name="submit"
                        class="btn btn-danger pull-right">Return</button></a>
            </div>
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>

<?php
include('../include/link.php');
include('../include/connect.php');

$emp_name = $_POST['emp_name'];
$task_code_array = $_POST['tasks'];

foreach ($task_code_array as $task_code) {
$con->next_result();
$check=mysqli_query($con,"SELECT * FROM tasks WHERE task_code='$task_code' AND in_charge='$emp_name'");
$checkrows=mysqli_num_rows($check);

    
if($checkrows>0) {
    echo "<script type='text/javascript'>   $(document).ready(function(){ $('#exists').modal('show');   });</script>";         
    exit;
}
else {
    $pdo = new PDO( "mysql:host=localhost;dbname=gtms", "gtms", "p@55w0rd$$$" );
    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); 

    $sql = "INSERT INTO tasks (task_code, in_charge) VALUES (:task_code, :emp_name)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':task_code', $task_code);
    $stmt->bindParam(':emp_name', $emp_name);
    $stmt->execute();
    header('location: manage_task_list.php'); 
}
}
?>