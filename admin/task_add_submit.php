<div class="modal fade" id="exists" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <a href="task_add.php"><button type="button" class="close" aria-hidden="true">&times;</button></a>
                <h4 class="modal-title" id="myModalLabel">Warning!</h4>
            </div>
            <div class="modal-body panel-body">
                <center>
                    <i style="color:#e13232; font-size:80px;" class="fa fa-times-circle "></i>
                    <br><br>
                    The task name is already registered and used in another section!
                    <br>
                    <p style="font-size: 10px">Please rephrase the task name to make it unique.</p>
                </center>
            </div>
            <div class="modal-footer">
                <a href="#" onclick="history.back()"><button type="button" name="submit" class="btn btn-danger pull-right">Return</button></a>
            </div>
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content panel-success">
			<div class="modal-header panel-heading">
				<a href="#" onclick="history.back()"><button type="button" class="close" aria-hidden="true">&times;</button></a>
				<h4 class="modal-title" id="myModalLabel">Success!</h4>
			</div>
			<div class="modal-body panel-body">
				<center>
					<i style="color:#23db16; font-size:80px;" class="fa fa-check-circle "></i>
					<br><br>
					The task was registered successfully!
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

    $task_name = $_POST['task_name'];
    $task_details = $_POST['task_details'];
    $task_class = $_POST['task_class'];
    $task_for = $_POST['task_for'];
    $date_created = date('Y-m-d');

    $con->next_result();
    $check=mysqli_query($con,"SELECT * FROM task_list WHERE task_name='$task_name'");
    $checkrows=mysqli_num_rows($check);

    if ($checkrows>0) {
        echo "<script type='text/javascript'>   $(document).ready(function(){ $('#exists').modal('show');   });</script>";         
    }
    else {
        $con->next_result();
        $register_task = "INSERT INTO task_list (`task_name`, `task_class`, `task_for`, `date_created`, `status`) VALUES ('$task_name', '$task_class', '$task_for', '$date_created', 1)";
        $register_task_result = mysqli_query($con, $register_task);
        $con->next_result();
        $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Registered a task.', '$systemtime', 'ADMIN')";
        $result = mysqli_query($con, $systemlog);
        echo "<script type='text/javascript'>   $(document).ready(function(){ $('#success').modal('show');   });</script>";
    }
?>