<?php
    include('../include/auth.php');
    include('../include/link.php');
    include('../include/connect.php');

    $emp_name = $_POST['emp_name'];
    $recurrance = $_POST['submission'];
    $task_name_array = $_POST['tasks'];
    $task_for = $_POST['emp_section'];
    $task_class = $_POST['task_class'];


    $count = 0;

    foreach ($task_name_array as $task_name) {
        $con->next_result();
        $check=mysqli_query($con,"SELECT * FROM tasks WHERE task_name='$task_name' AND in_charge='$emp_name'");
        $checkrows=mysqli_num_rows($check);
        
        $con->next_result();
        $get_descp=mysqli_query($con,"SELECT * FROM task_list WHERE task_name='$task_name' AND task_for='$task_for'");
        $row=mysqli_fetch_assoc($get_descp);

        if($checkrows>0) {
            $count += 1;
            echo "<script type='text/javascript'> $(document).ready(function(){ $('#exists').modal('show'); });</script>";
        }
        else {
            // Assign the New Tasks to the Employee
            $con->next_result();
            $import_checker = mysqli_query($con, "SELECT * FROM tasks WHERE task_name='$task_name' AND in_charge='$emp_name' AND task_class='$task_class' AND task_for='$task_for' AND submission='$recurrance'");
            $import_checker_result = mysqli_num_rows($import_checker);
            if ($import_checker_result == 0) {
                $task_details = $row['task_details'];
                $assign_task = "INSERT INTO tasks (`task_name`, `task_class`, `task_details`, `task_for`, `in_charge`, `submission`) VALUES ('$task_name', '$task_class', '$task_details', '$task_for', '$emp_name', '$recurrance')";
                $assign_task_result = mysqli_query($con, $assign_task);
                $con->next_result();
                $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Assigned a task/s.', '$systemtime', 'ADMIN')";
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