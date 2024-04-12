<?php	
	include('../include/link.php');
	include('../include/connect.php');
	include('../include/auth.php');
	
	require ('../vendor/autoload.php');
	
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

	date_default_timezone_set('Asia/Manila');
	
	if(isset($_POST['save_excel_data'])) {
		$fileName = $_FILES['import_file']['name'];
		$file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
		$allowed_ext = ['xls','csv','xlsx'];
	
		if(in_array($file_ext, $allowed_ext)) {
			$inputFileNamePath = $_FILES['import_file']['tmp_name'];
			$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
			$data = $spreadsheet->getActiveSheet()->toArray();
			$count_data = count($data)-1;

			$count = "0";
			foreach($data as $row) {
				if ($count > 0) {
					$task_name = $row['0'];
					$task_details = $row['1'];
					$task_class = $row['2'];
					$task_for = $row['3'];
					$in_charge = $row['4'];
					$submission = $row['5'];
					$attachment = $row['6'];
					$today = date('Y-m-d');
				
					$con->next_result();
					$import_checker = mysqli_query($con, "SELECT * FROM tasks WHERE task_name = '$task_name' AND task_class='$task_class' AND in_charge = '$in_charge' AND submission = '$submission'");
					$import_checker_result = mysqli_num_rows($import_checker);

					if ($import_checker_result > 0){
						$task_duplicated = "INSERT INTO task_temp (`task_name`, `task_details`, `task_class`, `task_for`, `in_charge`, `submission`, `attachment`, `status`) values ('$task_name', '$task_details', '$task_class', '$task_for', '$in_charge', '$submission', '$attachment', 'DUPLICATED')";
						$task_duplicated_result = mysqli_query($con, $task_duplicated);
					}
					else {
						$task_ready = "INSERT INTO task_temp (`task_name`, `task_details`, `task_class`, `task_for`, `in_charge`, `submission`, `attachment`, `status`) values ('$task_name', '$task_details', '$task_class', '$task_for', '$in_charge', '$submission', '$attachment', 'CLEAR')";
						$task_ready_result = mysqli_query($con, $task_ready);
					}
				}
				else {
					$count = "1";
				}
			}
			$con->next_result();
			$import_checker=mysqli_query($con,"SELECT * FROM task_temp WHERE status = 'DUPLICATED'");
			$import_checker_result=mysqli_num_rows($import_checker);
			if ($import_checker_result > 0) {
				echo "<script type='text/javascript'>   $(document).ready(function(){ $('#exists').modal('show');   });</script>";
			}
			else {
				$con->next_result();
				$sql = mysqli_query($con,"SELECT * FROM task_temp WHERE status='CLEAR'"); 
				$con->next_result();
				if(mysqli_num_rows($sql)>0) {
					while($row=mysqli_fetch_assoc($sql)) {
						$task_name = $row['task_name'];
						$task_class = $row['task_class'];
						$task_details = $row['task_details'];
						$task_for = $row['task_for'];
						$submission = $row['submission'];
						$in_charge = $row['in_charge'];
						$attachment = $row['attachment'];
						$status = 'NOT YET STARTED';
						$today = date('Y-m-d');
						
						// Register the New Tasks in the Materlist
						$con->next_result();
						$import_checker = mysqli_query($con, "SELECT * FROM task_list WHERE task_name='$task_name' AND task_for='$task_for'");
						$import_checker_result = mysqli_num_rows($import_checker);
						if ($import_checker_result == 0){
							$register_task = "INSERT INTO task_list (`task_name`, `task_details`, `task_class`, `task_for`, `date_created`, `status`) VALUES ('$task_name', '$task_details', '$task_class', '$task_for', '$today', 1)";
							$register_task_result = mysqli_query($con, $register_task);
						}
						// Assign the New Tasks to the Employee
						$con->next_result();
						$import_checker = mysqli_query($con, "SELECT * FROM tasks WHERE task_name='$task_name' AND in_charge='$in_charge'");
						$import_checker_result = mysqli_num_rows($import_checker);
						if ($import_checker_result == 0){
							$assign_task = "INSERT INTO tasks (`task_name`, `task_class`, `task_details`, `task_for`, `requirement_status`, `in_charge`, `submission`) VALUES ('$task_name', '$task_class', '$task_details', '$task_for', '$attachment', '$in_charge', '$submission')";
							$assign_task_result = mysqli_query($con, $assign_task);
						}
					}
					if ($assign_task_result) {
						$con->next_result();
						$systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Import tasks module runs successfully.', '$systemtime', '$username')";
						$result = mysqli_query($con, $systemlog);
						echo "<script type='text/javascript'>   $(document).ready(function(){ $('#success').modal('show');   });</script>";
					}
				}
				else {
					echo "<script type='text/javascript'>   $(document).ready(function(){ $('#warning').modal('show');   });</script>";
				}
			}
		}
		else {
			echo "<script type='text/javascript'>   $(document).ready(function(){ $('#error').modal('show');   });</script>";
		}
	}
?>
<div class="modal fade" id="exists" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
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
					There's a problem deploying tasks!
					<br>
					Download the error report <a href="download_unregistered.php"><font color="red">here</font></a>.
				</center>
			</div>
			<div class="modal-footer">
				<a href="task_import.php"><button type="button" name="submit"
					class="btn btn-danger pull-right">Return</button></a>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
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
					Tasks was uploaded successfully!
				</center>
			</div>
			<div class="modal-footer">
				<a href="task_import.php"><button type="button" name="submit"
					class="btn btn-success pull-right">Return</button></a>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content panel-success">
			<div class="modal-header panel-heading">
				<a href="task_import.php"><button type="button" class="close"
					aria-hidden="true">&times;</button></a>
				<h4 class="modal-title" id="myModalLabel">Warning!</h4>
			</div>
			<div class="modal-body panel-body">
				<center>
					<i style="color:#e13232; font-size:80px;" class="fa fa-times-circle "></i>
					<br><br>
					Invalid File!
					<br>
					Please upload XLS, XLSX & CSV file only.
				</center>
			</div>
			<div class="modal-footer">
				<a href="task_import.php"><button type="button" name="submit"
					class="btn btn-danger pull-right">Return</button></a>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="warning" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content panel-success">
			<div class="modal-header panel-heading">
				<a href="task_import.php"><button type="button" class="close"
					aria-hidden="true">&times;</button></a>
				<h4 class="modal-title" id="myModalLabel">System Error!</h4>
			</div>
			<div class="modal-body panel-body">
				<center>
					<i style="color:#e13232; font-size:80px;" class="fa fa-exclamation-triangle"></i>
					<br><br>
					The system encountered an unexpected error;
					<br>
					Please contact the system administrator now!
				</center>
			</div>
			<div class="modal-footer">
				<a href="task_import.php"><button type="button" name="submit"
					class="btn btn-danger pull-right">Return</button></a>
			</div>
		</div>
	</div>
</div>