<div class="modal fade" id="exists" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
	aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content panel-success">
			<div class="modal-header panel-heading">
				<a href="task_add.php"><button type="button" class="close"
					aria-hidden="true">&times;</button></a>
				<h4 class="modal-title" id="myModalLabel">Warning!</h4>
			</div>
			<div class="modal-body panel-body">
				<center>
					<i style="color:#e13232; font-size:80px;" class="fa fa-times-circle "></i>
					<br><br>
					The task you assigned to this employee already exists.
				</center>
			</div>
			<div class="modal-footer">
				<a href="#" onclick="history.go(-2)"><button type="button" name="submit" class="btn btn-danger pull-right">Return</button></a>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<div class="modal fade" id="success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
	aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content panel-success">
			<div class="modal-header panel-heading">
				<a href="task_import.php"><button type="button" class="close"
					aria-hidden="true">&times;</button></a>
				<h4 class="modal-title" id="myModalLabel">Success!</h4>
			</div>
			<div class="modal-body panel-body">
				<center>
					<i style="color:#23db16; font-size:80px;" class="fa fa-check-circle "></i>
					<br><br>
					The task was deployed successfully!
				</center>
			</div>
			<div class="modal-footer">
				<a href="#" onclick="history.go(-2)"><button type="button" name="submit" class="btn btn-danger pull-right">Return</button></a>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<div class="modal fade" id="error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
	aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content panel-success">
			<div class="modal-header panel-heading">
				<a href="task_import.php"><button type="button" class="close"
					aria-hidden="true">&times;</button></a>
				<h4 class="modal-title" id="myModalLabel">Error!</h4>
			</div>
			<div class="modal-body panel-body">
				<center>
					<i style="color:#e13232; font-size:80px;" class="fa fa-times-circle "></i>
					<br><br>
					There's a problem with creating and assigning the task.
                    <br>
                    Please try again.
				</center>
			</div>
			<div class="modal-footer">
				<a href="#" onclick="history.go(-2)"><button type="button" name="submit" class="btn btn-danger pull-right">Return</button></a>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<?php
	include('../include/link.php');
	include('../include/connect.php');

	$task_name = $_POST['task_name'];
	$task_details = $_POST['task_details'];
	$task_class = '4';
	$task_for = $_POST['task_for'];
	$in_charge = $_POST['in_charge'];
	$due_date = $_POST['due_date'];
	$today = date('Y-m-d');
	$requirement_status = $_POST['requirement_status'];
	$status = "NOT YET STARTED";
	$submission = "Once";

	$con->next_result();
	$check = mysqli_query($con, "SELECT * FROM tasks_details WHERE task_name='$task_name' AND in_charge='$in_charge' AND due_date='$due_date' AND date_accomplished IS NULL");
	$check_result = mysqli_num_rows($check);

	if($check_result>0) {
		echo "<script type='text/javascript'>   $(document).ready(function(){ $('#exists').modal('show');   });</script>";         
		exit;
	}
	else {
		// Register the New Tasks in the Materlist
		$con->next_result();
		$import_checker = mysqli_query($con, "SELECT * FROM task_list WHERE task_name='$task_name' AND task_for='$task_for'");
		$import_checker_result = mysqli_num_rows($import_checker);
		if ($import_checker_result == 0){
			$register_task = "INSERT INTO task_list (`task_name`, `task_class`, `task_for`, `date_created`, `status`) VALUES ('$task_name', '$task_class', '$task_for', '$today', 1)";
			$register_task_result = mysqli_query($con, $register_task);
		}
		// Assign the New Tasks to the Employee
		$con->next_result();
		$import_checker = mysqli_query($con, "SELECT * FROM tasks WHERE task_name='$task_name' AND in_charge='$in_charge'");
		$import_checker_result = mysqli_num_rows($import_checker);
		if ($import_checker_result == 0){
			$assign_task = "INSERT INTO tasks (`task_name`, `in_charge`, `submission`) VALUES ('$task_name', '$in_charge', '$submission')";
			$assign_task_result = mysqli_query($con, $assign_task);
		}
		// Deploying New Task for Employee
		$con->next_result();
		$import_checker = mysqli_query($con, "SELECT * FROM tasks_details WHERE task_name='$task_name' AND in_charge='$in_charge' AND due_date='$due_date' AND date_accomplished IS NULL");
		$import_checker_result = mysqli_num_rows($import_checker);
		if ($import_checker_result == 0){
			// Generating Unique Task Code of each Task of Employee
			$getlatestcode = mysqli_query($con, "SELECT MAX(task_code) AS latest_task_code FROM tasks_details LEFT JOIN task_list ON task_list.task_name = tasks_details.task_name WHERE task_class = '$task_class' AND task_for = '$task_for'");
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

			// Start Deploying Tasks
			$deploytask = "INSERT INTO tasks_details (`task_code`, `task_name`, `in_charge`, `status`, `date_created`, `due_date`, `requirement_status`, `task_status`) VALUES ('$task_code', '$task_name', '$in_charge', '$status', '$today', '$due_date', '$requirement_status', 1)";
			$deploytask_result = mysqli_query($con, $deploytask);
		}
		echo "<script type='text/javascript'>   $(document).ready(function(){ $('#success').modal('show');   });</script>";
	}
?>