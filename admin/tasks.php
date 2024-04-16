<?php 
	include('../include/header.php');
	include('../include/connect.php');
	$date_today = date("Y-m-d");
	$status=isset($_GET['status']) ? $_GET['status'] : die('ERROR: Record not found.'); 
?>
<html>
	<head>
		<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
		<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
		<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
		<title>Tasks</title>
	</head>
	<div id="content" class="p-4 p-md-5 pt-5">
		<div id="wrapper">
			<div id="page-wrapper">
				<h1 class="page-header"><?php echo $status ?> Tasks</h1>
				<form method="POST" action="../admin/sortdl.php?status=<?php echo $status ?>">
					<div class="row">
						<div class="form-group col-lg-2">
							<label>From:</label><br>
							<input type="date" class="form-control" name="val_from" id="val_from" onchange="selectfrom(this)">
						</div>
						<div class="form-group col-lg-2">
							<label>To:</label><br>
							<input type="date" class="form-control" name="val_to" id="val_to" onchange="selectto(this)">
						</div>
						<a href="tasks_xls.php?status=<?php echo $status ?>" id="btn1"> <button class="btn btn-success pull-left" style="margin-top: 25px;"><span class="fa fa-download fa-fw"></span> Download Table</button></a>
						<input type="submit" id="submit" value="Download Sorted Table" class="btn btn-success pull-left" style="margin-top: 25px; display: none;">
					</div>
				</form>
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<?php echo $status ?> Task
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table width="100%" class="table table-striped table-hover" id="table_task">
										<thead>
											<tr>
												<th class="col">
													<center>Task Code</center>
												</th>
												<th scope='col' title='Legend'>
													<i class='fa fa-asterisk' />
												</th>
												<th class="col">
													<center>Task Name</center>
												</th>
												<th class="col">
													<center>Task Classification</center>
												</th>
												<th class="col">
													<center>In-charge</center>
												</th>
												<?php
													if ($status != 'FINISHED'){
														echo'
														<th class="col">
														<center>Due Date</center>
														</th>';
													}
													?>
												<th class="col">
													<center>Status</center>
												</th>
												<?php
													if ($status == "FINISHED"){
														echo"
														<th class='col'>
														<center>Date Accomplished</center>
														</th>
														<th class='col'>
														<center>Score</center>
														</th>
														<th class='col'>
														<center>Details</center>
														</th>";
													}
													?>
											</tr>
										</thead>
										<tbody id="show_task">
											<?php
												/* and access!='1' */
												$con->next_result();
												$result = mysqli_query($con,"SELECT * FROM tasks_details JOIN task_class ON tasks_details.task_class = task_class.id JOIN accounts ON tasks_details.in_charge=accounts.username JOIN section ON section.sec_id=tasks_details.task_for WHERE tasks_details.task_status=1 AND tasks_details.approval_status='0' AND tasks_details.reschedule=0 AND tasks_details.status='$status' AND accounts.status = 1");               
												if (mysqli_num_rows($result)>0) { 
													while ($row = $result->fetch_assoc()) {
														$today = date("Y-m-d");
														$due_date = $row['due_date'];
														$due = date('m / d / Y', strtotime($row['due_date']));
														$date = date('m / d / Y H:i:s a', strtotime($row['date_accomplished']));
														$nextDate = date('Y-m-d', strtotime($due_date . ' + ' . 1 . ' days'));
														$yesterday = date('Y-m-d', strtotime($today . ' -' . 1 . ' days'));
														$twodago = date('Y-m-d', strtotime($due_date . ' +' . 2 . ' days'));
														$task_class = $row['task_class'] ;
														$class = "";
														$sign = "";
														$achievement = $row['achievement'];
														$emp_name=$row['fname'].' '.$row['lname'];
														if (empty($row["file_name"])) {
															// Use a default image URL
															$imageURL = '../assets/img/user-profiles/nologo.png';
														} else {
															// Use the image URL from the database
															$imageURL = '../assets/img/user-profiles/'.$row["file_name"];
														} 
												        if ($status == "NOT YET STARTED") {
												            // DAILY, ADDITIONAL AND PROJECT
												            if ($task_class == "DAILY ROUTINE" || $task_class == "ADDITIONAL TASK" || $task_class == "PROJECT"){
												                if ($due_date < $today){
												                    $class_label = "danger";
												                    $sign = "EXPIRED";
												                    $class = "invalid";
												                }
												                elseif ($due_date > $today){
												                    $class_label = "info";
												                    $sign = "PENDING";
												                }
												                elseif ($due_date == $today){
												                    $class_label = "primary";
												                    $sign = "NOT YET STARTED";
												                }
												                else {
												                    $class_label = "muted";
												                    $sign = "INVALID";
												                }
												            }
												            // WEEKLY
												            if ($task_class == "WEEKLY ROUTINE"){
												                if ($twodago  <= $today){
												                    $class_label = "danger";
												                    $sign = "EXPIRED";
												                    $class = "invalid";
												                }
												                elseif ($due_date <= $yesterday){
												                    $class_label = "warning";
												                    $sign = "EXPIRING";
												                }
												                elseif ($due_date == $today) {
												                    $class_label = "primary";
												                    $sign = "NOT YET STARTED";
												                }
												                elseif ($due_date > $today) {
												                    $class_label = "info";
												                    $sign = "PENDING";
												                }
												                
												            }
												            // MONTHLY
												            if ($task_class == "MONTHLY ROUTINE"){
												                if ($twodago  <= $today){
												                    $class_label = "danger";
												                    $sign = "EXPIRED";
												                    $class = "invalid";
												                }
												                elseif ($due_date <= $yesterday){
												                    $class_label = "warning";
												                    $sign = "EXPIRING";
												                }
												                elseif ($due_date == $today) {
												                    $class_label = "primary";
												                    $sign = "NOT YET STARTED";
												                }
												                elseif ($due_date > $today) {
												                    $class_label = "info";
												                    $sign = "PENDING";
												                }
												            }
												        }
												        if ($status == "IN PROGRESS"){ 
												            if (($today > $due_date && ($task_class == "DAILY ROUTINE" || $task_class == "ADDITIONAL TASK" || $task_class == "PROJECT"))
												            || ($twodago  <= $today && ($task_class == "WEEKLY ROUTINE" || $task_class == "MONTHLY ROUTINE"))){
												                $class = "invalid";
												                $sign = "OVERDUE";
												                $class_label = "danger";
												            }
												            else {
												                $sign = "IN PROGRESS";
												                $class_label = "warning";
												            }
												        }
												        if ($status == "FINISHED"){
												            $achievement = $row['achievement'];
												            if ($achievement == 0){
												                $class_label = "danger";
												                $sign = "FAILED";
												            }
												            if ($achievement > 0){
												                $class_label = "success";
												                $sign = "FINISHED";
												            }
												        }
												
														if ($status == "FINISHED"){
												        echo "<tr>
															<td class='".$class."'>". $row["task_code"] . " </td>"; ?>
															<?php
															if ($row['requirement_status'] == 1){
																echo "<td class='".$class."'> <span style='color: #00ff26'><i class='fa fa-paperclip' title='Attachment Required'></i></span></td>";
															}
															else {
																echo "<td class='".$class."'> </td>";
															}
															echo "
																<td id='normalwrap' class='".$class."'>" . $row["task_name"] . " </td>   
																<td class='".$class."'><center />" . $row["task_class"] . "</td> 
																<td class='".$class."' style='text-align: justify'> <img src=".$imageURL." class='profile' title=".$row["username"]." style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-right: 15px; margin-left: 0'>" . $emp_name . "</td>  
																<td class='".$class."'><center /><p class='label label-".$class_label."' style='font-size:100%;'>".$sign."</p></td>
																<td class='".$class."'><center />" . $date . "</td>
																<td class='".$class."'><center />" . $row['achievement'] . "</td>
																<td><center><button value='".$row['task_code']."' data-name='".$row['task_name']."' data-class='".$row['task_class']."' data-remarks='".$row['remarks']."' data-duedate='".$row['due_date']."' data-datefinish='".$row['date_accomplished']."' data-achievement='".$row['achievement']."' data-file='".$row['requirement_status']."' data-note='".$row['head_note']."' data-head='".$row['head_name']."' data-path='".$row['attachment']."' class='btn btn-primary' onclick='view1(this)'><span class='fa fa-folder-open'></span> View </button></center></td>
															</tr>";
														}
														else {
															echo "<tr>
															<td class='".$class."'>". $row["task_code"] . " </td>"; ?>
															<?php
															if ($row['requirement_status'] == 1){
																echo "<td class='".$class."'> <span style='color: #00ff26'><i class='fa fa-paperclip' title='Attachment Required'></i></span></td>";
															}
															else {
																echo "<td class='".$class."'> </td>";
															}
															echo " 
												            <td id='normalwrap' class='".$class."'>" . $row["task_name"] . " </td>   
												            <td class='".$class."'><center />" . $row["task_class"] . "</td> 
                                                    		<td class='".$class."' style='text-align: justify'> <img src=".$imageURL." class='profile' title=".$row["username"]." style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-right: 15px; margin-left: 0'>" . $emp_name . "</td>  
												            <td class='".$class."'><center />" . $due . "</td> 
												            <td class='".$class."'><center /><p class='label label-".$class_label."' style='font-size:100%;'>".$sign."</p></td>
												        </tr>";
												        }
													}
												} 
												if ($con->connect_error) {
												    die("Connection Failed".$con->connect_error); 
												}; 
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<a href="./index.php"> <button class='btn btn-danger pull-left'><i class="fa fa-arrow-left"></i> Return to Dashboard</button></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<style>
	@-webkit-keyframes invalid {
		from {
			background-color: red;
		}

		to {
			background-color: inherit;
		}
	}

	@-moz-keyframes invalid {
		from {
			background-color: red;
		}

		to {
			background-color: inherit;
		}
	}

	@-o-keyframes invalid {
		from {
			background-color: red;
		}

		to {
			background-color: inherit;
		}
	}

	@keyframes invalid {
		from {
			background-color: red;
		}

		to {
			background-color: inherit;
		}
	}

	.invalid {
		-webkit-animation: invalid 1s infinite;
		/* Safari 4+ */
		-moz-animation: invalid 1s infinite;
		/* Fx 5+ */
		-o-animation: invalid 1s infinite;
		/* Opera 12+ */
		animation: invalid 1s infinite;
		/* IE 10+ */
	}
	</style>
	<?php
		if ($status == 'NOT YET STARTED'){
			echo "<script>
						$(document).ready(function() {
						$('#table_task').DataTable({
							responsive: true,
							'order': [[ 5, 'asc' ]]
							});
						});
						</script>";
		}
		elseif ($status == 'IN PROGRESS') {
			echo "<script>
						$(document).ready(function() {
						$('#table_task').DataTable({
							responsive: true,
							'order': [[ 5, 'asc' ]]
							});
						});
						</script>";
		}
		elseif ($status == 'FINISHED'){
			echo "<script>
						$(document).ready(function() {
						$('#table_task').DataTable({
							responsive: true,
							'order': [[ 6, 'desc' ]]
							});
						});
						</script>";
		}
		elseif ($status == 'RESCHEDULE'){
			echo "<script>
						$(document).ready(function() {
						$('#table_task').DataTable({
							responsive: true,
							'order': [[ 5, 'desc' ]]
							});
						});
						</script>";
		}
	?>
	<script>
		function selectfrom(element) {
			let valfrom = $(element).val();
			let status = <?php echo json_encode($status) ?>;
			let valto = $('#val_to').val();
			$('#table_task').DataTable().destroy();
			$('#show_task').empty();
			if (valfrom) {
				$.ajax({
						type: "post",
						url: "ajax_valfrom.php",
						data: {
						"valfrom": valfrom,
						"status": status,
						"valto": valto
					},
					success: function(response) {
						$('#show_task').append(response);
						$('#table_task').DataTable();
					}
				});
			}
		}
	</script>
	<script>
		function selectto(element) {
			let valto = $(element).val();
			let status = <?php echo json_encode($status) ?>;
			let valfrom = $('#val_from').val();
			$('#table_task').DataTable().destroy();
			$('#show_task').empty();
			if (valto) {
				$.ajax({
					type: "post",
					url: "ajax_valto.php",
					data: {
						"valfrom": valfrom,
						"status": status,
						"valto": valto
					},
					success: function(response) {
						$('#show_task').append(response);
						$('#table_task').DataTable();
					}
				});
			}
		}
	</script>
	<script>
		$(document).ready(function(){
			$('input[type="date"]').change(function(){
				if($('#val_from').val() !='' && $('#val_to').val() !=''){
					$('#submit').show();
					$('#btn1').hide();
				}
				else{
					$('#submit').hide();
					$('#btn1').hide();
				}
			});
		});
	</script>
	<script>
		function view1(element) {
			var taskcode = element.value;
			var taskname = element.getAttribute("data-name");
			var taskclass = element.getAttribute("data-class");
			var remarks = element.getAttribute("data-remarks");
			var duedate = element.getAttribute("data-duedate");
			var datefinish = element.getAttribute("data-datefinish");
			var achievement = element.getAttribute("data-achievement");
			var filename = element.getAttribute("data-path");
			var file = element.getAttribute("data-file");
			var headname = element.getAttribute("data-head");
			var headnote = element.getAttribute("data-note");
			$(document).ready(function() {
				$('#view1').modal('show');
				document.getElementById('taskcode').value = taskcode;
				document.getElementById('taskname').value = taskname;
				document.getElementById('taskclass').value = taskclass;
				document.getElementById('tracer').value = taskcode;
				document.getElementById('remarks').value = remarks;
				document.getElementById('duedate').value = duedate;
				document.getElementById('datefinish').value = datefinish;
				document.getElementById('achievement').value = achievement;
				document.getElementById('file').value = file;
				document.getElementById('filename').innerHTML = filename;
				document.getElementById('filename').href = '../employee/attachment_download.php?filetrack=' + encodeURIComponent(filename);
				document.getElementById('headname').value = headname;
				document.getElementById('headnote').value = headnote;

				var button = $("#showdownload");
				if (file == 0){
					button.hide();
				}
				else {
					button.show();
				}
				
				var hidenotes = $('#shownote');
				if (headnote == ''){
					hidenotes.hide();
				}
				else {
					hidenotes.show();
				}
			});
		}
	</script>
	<div class="modal fade" id="view1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content panel-success">
				<div class="modal-header panel-heading">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Task Details</h4>
					<hr>
				</div>
				<div class="modal-body panel-body">
					<form data-toggle="validator" class="className" name="form" id="form" action="attachment_download.php" method="POST">
						<div class='form-group col-lg-3'>
							<label>Task Code:</label>
							<input type="text" class="form-control" name="taskcode" id="taskcode" disabled><br>
							<input type="text" name="tracer" id="tracer" hidden>
						</div>
						<div class='form-group col-lg-3'>
							<label>Due Date:</label>
							<input type="date" class="form-control" name="duedate" id="duedate" disabled><br>
						</div>
						<div class='form-group col-lg-4'>
							<label>Date Accomplished:</label>
							<input type="datetime-local" class="form-control" name="datefinish" id="datefinish" disabled><br>
						</div>
						<div class='form-group col-lg-2'>
							<label>Achievement:</label>
							<input type="text" class="form-control" name="achievement" id="achievement" disabled>
						</div>
						<div class='form-group col-lg-12'>
							<label>Task Name:</label>
							<input type="text" class="form-control" name="taskname" id="taskname" disabled><br>
							<label>Task Classification:</label>
							<input type="text" class="form-control" name="taskclass" id="taskclass" disabled><br>
							<label>Task Remarks:</label>
							<textarea class="form-control" name="remarks" id="remarks" readonly></textarea><br>
							<div id="showdownload">
								<label>File Attachement:</label>
								<input type="text" name="file" id="file" hidden><br>
								<span style='color: #00ff26'><i class='fa fa-paperclip'></i></span>
								<a href="attachment_download.php" id="filename"></a>
							</div><br>
							<label>Approved By:</label>
							<input type="text" class="form-control" name="headname" id="headname" disabled><br>
							<div id="shownote">
								<label>Note:</label>
								<textarea class="form-control" name="headnote" id="headnote" readonly></textarea>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
				</div>
			</div>
		</div>
	</div>
</html>