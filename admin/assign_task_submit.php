<div class="modal fade" id="exists" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <a href="account_add.php"><button type="button" class="close"
                        aria-hidden="true">&times;</button></a>
                <h4 class="modal-title" id="myModalLabel">Warning!</h4>
            </div>
            <div class="modal-body panel-body">
                <center>
                    <i style="color:#e13232; font-size:80px;" class="fa fa-times-circle "></i>
                    <br><br>
                    This employee already has the selected task assigned to them!
                </center>
            </div>
            <div class="modal-footer">
                <a href="#" onclick="history.back()"><button type="button" name="submit" class="btn btn-danger pull-right">Return</button></a>
            </div>
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
	aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content panel-success">
			<div class="modal-header panel-heading">
				<a href="task_import.php"><button type="button" class="close" aria-hidden="true">&times;</button></a>
				<h4 class="modal-title" id="myModalLabel">Success!</h4>
			</div>
			<div class="modal-body panel-body">
				<center>
					<i style="color:#23db16; font-size:80px;" class="fa fa-check-circle "></i>
					<br><br>
					This employee was successfully assigned a task!
				</center>
			</div>
			<div class="modal-footer">
				<a href="#" onclick="history.go(-2)"><button type="button" name="submit" class="btn btn-success pull-right">Return</button></a>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<?php
    include('../include/link.php');
    include('../include/connect.php');
    include('../include/auth.php');

    $task_for = $_POST['section'];
    $in_charge = $_POST['emp_name'];
    $task_name = $_POST['tasks'];
    $date_created = $_POST['date_created'];
    $due_date = $_POST['due_date'];
    $requirement_status = $_POST['requirement_status'];
    $status = "NOT YET STARTED";
    $task_status = "1";

    $con->next_result();
    $check = mysqli_query($con, "SELECT * FROM tasks_details WHERE task_name='$task_name' AND in_charge='$in_charge' AND due_date='$due_date' AND date_accomplished IS NULL");
    $check_result = mysqli_num_rows($check);

    if ($check_result > 0) {
      echo "<script type='text/javascript'>   $(document).ready(function(){ $('#exists').modal('show');   });</script>";
    }
    else {
      $con->next_result();
      $taskclass = mysqli_query($con, "SELECT task_class FROM task_list WHERE task_name = '$task_name' AND task_for='$task_for'");
      $task_class_query = mysqli_fetch_assoc($taskclass);
      $task_class = $task_class_query['task_class'];

      $con->next_result();
      $getlatestcode = mysqli_query($con, "SELECT MAX(task_code) AS latest_task_code FROM tasks_details WHERE task_class = '$task_class' AND task_for = '$task_for'");
        $getlatestcode_result = mysqli_fetch_assoc($getlatestcode);
        $latestcode = $getlatestcode_result['latest_task_code'];
        $prefix = '';
        if ($task_class == '1') {
          $prefix = 'TD';
        } 
        elseif ($task_class == '2') {
          $prefix = 'TW';
        } 
        elseif ($task_class == '3') {
          $prefix = 'TM';
        } 
        elseif ($task_class == '4') {
          $prefix = 'TA';
        } 
        elseif ($task_class == '5') {
          $prefix = 'TP';
        }
        $numeric_portion = intval(substr($latestcode, -6)) + 1;
        $task_code = $task_for.'-'.$prefix . '-' . str_pad($numeric_portion, 6, '0', STR_PAD_LEFT);

        // Deploying New Task for Employee
        $con->next_result();
        $deploytask = "INSERT INTO tasks_details (`task_code`, `task_name`, `task_class`, `task_for`, `in_charge`, `status`, `date_created`, `due_date`, `requirement_status`, `task_status`) VALUES ('$task_code', '$task_name', '$task_class', '$task_for', '$in_charge', '$status', '$date_created', '$due_date', '$requirement_status', 1)";
        $deploytask_result = mysqli_query($con, $deploytask);
        
        $con->next_result();
        $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Assigns a task for user $in_charge', '$systemtime', 'ADMIN')";
        $result = mysqli_query($con, $systemlog);
        echo "<script type='text/javascript'>   $(document).ready(function(){ $('#success').modal('show');   });</script>";
    }
?>