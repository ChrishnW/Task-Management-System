<div class="modal fade" id="exists" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content panel-danger">
            <div class="modal-header panel-heading" style="background-color: rgb(45, 45, 45);
    border-color: transparent;">
                <a href="task_add.php"><button type="button" class="close"
                        aria-hidden="true">&times;</button></a>
                <h4 class="modal-title" id="myModalLabel">Warning!</h4>
            </div>
            <div class="modal-body panel-body" style="background-color: rgb(45, 45, 45);
    border-color: transparent;">
                <center style="color: white">
                    <i style="color:#e13232; font-size:80px;" class="fa fa-times-circle "></i>
                    <br><br>
                    Task already deployed under this employee!
                </center>
            </div>
            <div class="modal-footer" style="background-color: rgb(45, 45, 45);
    border-color: transparent;">
                <a href="deploytask.php"><button type="button" name="submit"
                        class="btn btn-danger pull-right">Return</button></a>
            </div>
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content panel-danger">
            <div class="modal-header panel-heading" style="background-color: rgb(45, 45, 45);
    border-color: transparent;">
                <a href="task_add.php"><button type="button" class="close"
                        aria-hidden="true">&times;</button></a>
                <h4 class="modal-title" id="myModalLabel" style="color: #008000">Notification</h4>
            </div>
            <div class="modal-body panel-body" style="background-color: rgb(45, 45, 45);
    border-color: transparent;">
                <center style="color: white">
                    <i style="color:#008000; font-size:80px;" class="fa fa-check-circle"></i>
                    <br><br>
                    Task has been deployed successfully!
                </center>
            </div>
            <div class="modal-footer" style="background-color: rgb(45, 45, 45);
    border-color: transparent;">
                <a href="deploytask.php"><button type="button" name="submit"
                        class="btn btn-danger pull-right">Return</button></a>
            </div>
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>

<?php
include('../include/link.php');
include('../include/connect.php');
$section = $_POST['section'];
$in_charge = $_POST['emp_name'];
$task_code = $_POST['tasks'];
$date_created = $_POST['date_created'];
$due_date = $_POST['due_date'];
$status = "NOT YET STARTED";
$task_status = "1";

$con->next_result();
$check=mysqli_query($con,"SELECT * FROM tasks_details WHERE task_code='$task_code' AND in_charge='$in_charge' AND due_date='$due_date'");
$checkrows=mysqli_num_rows($check);

if($checkrows>0) {
    echo "<script type='text/javascript'>   $(document).ready(function(){ $('#exists').modal('show');   });</script>";         
    exit;
}
else {
    $pdo = new PDO( "mysql:host=localhost;dbname=gtms", "gtms", "p@55w0rd$$$" );
    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); 

    $sql = "INSERT INTO tasks_details (task_code, date_created, due_date, in_charge, status, task_status, approval_status, reschedule) VALUES (:task_code, :date_created, :due_date, :in_charge, :status, :task_status, 1, 0)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':task_code', $task_code);
    $stmt->bindParam(':date_created', $date_created);
    $stmt->bindParam(':due_date', $due_date);
    $stmt->bindParam(':in_charge', $in_charge);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':task_status', $task_status);
    $stmt->execute();
    echo "<script type='text/javascript'>   $(document).ready(function(){ $('#success').modal('show');   });</script>";
}
?>