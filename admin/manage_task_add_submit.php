<?php
    include('../include/auth.php');
    include('../include/link.php');
    include('../include/connect.php');

    if(isset($_POST['submission'])){
        $recurrance = $_POST['submission'];
    }
    if(isset($_POST['due_date'])){
        $due_date = $_POST['due_date'];
    }
    $emp_name = $_POST['emp_name'];
    $task_name_array = $_POST['tasks'];
    $task_for = $_POST['emp_section'];
    $task_class = $_POST['task_class'];
    $requirement_status = $_POST['requirement_status'];
    $task_details = 'N/A';
    $status = "NOT YET STARTED";
    $today = date('Y-m-d');

    if($task_class <= 3){
        $count = 0;
        foreach ($task_name_array as $task_name) {
            $con->next_result();
            $check=mysqli_query($con,"SELECT * FROM tasks WHERE task_name='$task_name' AND in_charge='$emp_name'");
            $checkrows=mysqli_num_rows($check);

            if($checkrows>0) {
                $count += 1;
                echo "<script type='text/javascript'> $(document).ready(function(){ $('#exists').modal('show'); });</script>";
            }
            else {
                // Assign the New Tasks to the Employee
                $con->next_result();
                $assign_task = "INSERT INTO tasks (`task_name`, `task_class`, `task_details`, `task_for`, `requirement_status`, `in_charge`, `submission`) VALUES ('$task_name', '$task_class', '$task_details', '$task_for', '$requirement_status', '$emp_name', '$recurrance')";
                $assign_task_result = mysqli_query($con, $assign_task);
                // Record Logs
                $con->next_result();
                $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Assigned $emp_name a task/s.', '$systemtime', 'ADMIN')";
                $result = mysqli_query($con, $systemlog);
                echo "<script type='text/javascript'> $(document).ready(function(){ $('#success').modal('show'); });</script>";
            }
        }
    }
    else {
        $count = 0;
        foreach ($task_name_array as $task_name) {
            $con->next_result();
            $check=mysqli_query($con,"SELECT * FROM tasks_details WHERE task_name='$task_name' AND in_charge='$emp_name' AND due_date='$due_date'");
            $checkrows=mysqli_num_rows($check);

            if($checkrows>0) {
                $count += 1;
                echo "<script type='text/javascript'> $(document).ready(function(){ $('#exists').modal('show'); });</script>";
            }
            else {
                // Assign the New Tasks to the Employee
                $con->next_result();
                $import_checker = mysqli_query($con, "SELECT * FROM tasks_details WHERE task_name='$task_name' AND in_charge='$emp_name' AND due_date='$due_date' AND date_accomplished IS NULL");
                $import_checker_result = mysqli_num_rows($import_checker);
                if ($import_checker_result <= 0){
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

                    $deploytask = "INSERT INTO tasks_details (`task_code`, `task_name`, `task_class`, `task_for`, `in_charge`, `status`, `date_created`, `due_date`, `requirement_status`, `task_status`) VALUES ('$task_code', '$task_name', '$task_class', '$task_for', '$emp_name', '$status', '$today', '$due_date', '$requirement_status', 1)";
                    $deploytask_result = mysqli_query($con, $deploytask);
                }
                // Record Logs
                $con->next_result();
                $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Assigned $emp_name a task/s.', '$systemtime', 'ADMIN')";
                $result = mysqli_query($con, $systemlog);
                echo "<script type='text/javascript'> $(document).ready(function(){ $('#success').modal('show'); });</script>";
            }
        }
    }
?>
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
                    The task(s) you selected are already assigned to this employee!
                </center>
            </div>
            <div class="modal-footer">
                <a href="manage_task_add.php"><button type="button" name="submit"
                        class="btn btn-danger pull-right">Return</button></a>
            </div>
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
	aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content panel-success">
			<div class="modal-header panel-heading">
				<a href="#" onclick="history.back()"><button type="button" class="close"
					aria-hidden="true">&times;</button></a>
				<h4 class="modal-title" id="myModalLabel">Success!</h4>
			</div>
			<div class="modal-body panel-body">
				<center>
					<i style="color:#23db16; font-size:80px;" class="fa fa-check-circle "></i>
					<br><br>
					The task has been successfully assigned to the employee!
                    <br>
                    <p style="font-size: 10px; color: yellow;">There are <?php echo $count ?> tasks that have already been assigned to this employee and skipped automatically.</p>
                </center>
			</div>
			<div class="modal-footer">
				<a href="#" onclick="history.go(-2)"><button type="button" name="submit"
					class="btn btn-success pull-right">Return</button></a>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>