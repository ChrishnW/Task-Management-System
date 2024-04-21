<?php 
	include('../include/header_employee.php');
	include('../include/connect.php');
	
	$date_today = date('Y-m-d');
	$month = date('m');
	$year = date('Y');
	$status=isset($_GET['status']) ? $_GET['status'] : die('ERROR: Record ID not found.');
	$status_lower =  strtolower($status);
	$status_name = ucwords($status_lower);
?>
<html>
	<head>
		<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
		<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
		<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
		<link href="../assets/css/darkmode.css" rel="stylesheet">
		<title>My Task Details</title>
	</head>
	<div id="content" class="p-4 p-md-5 pt-5">
		<div id="wrapper">
			<div id="page-wrapper">
				<h1 class="page-header"><?php echo $status_name ?> <font style="color:red;">Tasks</font></h1>
				<div class="row">
					<div class='form-group col-lg-2'>
						<label>From:</label><br>
						<input type='date' class='form-control' name='val_from' id='val_from' onchange='selectfrom(this)'>
					</div>
					<div class='form-group col-lg-2'>
						<label>To:</label><br>
							<input type='date' class='form-control' name='val_to' id='val_to' onchange='selectto(this)'>
					</div>
					<?php
						if ($status == "FINISHED"){
							echo "<a href='tasks_xls.php?status=$status&username=$username'> <button class='btn btn-success pull-right' style='margin-right: 20px;margin-top: 23px;'><span class='fa fa-download'></span> Download XLS</button></a>";
						}
					?>
					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								My Task Details
								<button class='btn btn-primary pull-right' id='hidden-btn' onclick='done(this)' style="margin-top: -7px; border-color: transparent;"><i class="fa fa-play-circle"></i> Start Marked Tasks</button>
								<br>
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table width="100%" class="table table-dark table-hover" id="table_task">
										<thead class="thead-light">
											<tr>
												<?php
													if($status=="NOT YET STARTED"){
														echo "<th scope='col'> Select All <input type='checkbox' id='selectAll' class='messageCheckbox'/> </td>";
													}
												?>
												<th scope="col"> Task Code </th>
												<th scope='col' title='Legend'> <i class='fa fa-asterisk' /> </th>
												<th scope="col"> Task Name </th>
												<th scope="col"> Task Classification </th>
												<?php
													if ($status != "FINISHED" && $status != "VERIFICATION"){
														echo "<th scope='col'> Due Date </th>";
													}
													elseif ($status=='FINISHED' || $status=="VERIFICATION"){
														echo "<th scope='col'> Date Accomplished </th>";
													}
												?>
												<th scope="col"> In-charge </th>
												<th scope="col"> Status </th>
												<?php 
													if ($status=="NOT YET STARTED"||$status=="IN PROGRESS") {
														echo "<th scope='col'>
														<center>Action</center> </th>";
													}
													elseif ($status=='FINISHED' || $status=="VERIFICATION"){
														echo "<th class='col-lg-1'> Achievement </th>
														<th style='text-align:center;' class='col-lg-1'> Details </th>";
													}
													?>
											</tr>
										</thead>
										<tbody id="show_task">
											<?php
												$con->next_result();
												if ($status == "NOT YET STARTED") {
													$result = mysqli_query($con, "SELECT *, (SELECT DISTINCT date FROM attendance WHERE card=accounts.card and date = tasks_details.due_date) AS loggedin FROM tasks_details JOIN accounts ON tasks_details.in_charge=accounts.username JOIN task_class on task_class.id=tasks_details.task_class WHERE tasks_details.in_charge='$username' AND tasks_details.task_status=1 AND tasks_details.reschedule=0 AND tasks_details.status='$status' AND MONTH(tasks_details.due_date)='$month' AND YEAR(tasks_details.due_date)='$year'");
													if (mysqli_num_rows($result) > 0) {
														while ($row = $result->fetch_assoc()) {
															$today      = date("Y-m-d");
															$due_date   = $row["due_date"];
															$due        = date('d-m-Y h:i A', strtotime($row['due_date'].'16:00:00'));
															$nextDate   = date('Y-m-d', strtotime($due_date . ' + ' . 1 . ' days'));
															$yesterday  = date('Y-m-d', strtotime($today . ' -' . 1 . ' days'));
															$twodago    = date('Y-m-d', strtotime($due_date . ' +' . 2 . ' days'));
															$task_class = $row['task_class'];
															$class      = "";
															$sign       = "";
															$emp_name   = $row['fname'] . ' ' . $row['lname'];
															
															if ($status == "NOT YET STARTED") {
																if ($due_date > $today) {
																	$class_label = "info";
																	$sign        = "PENDING";
																}
																elseif ($due_date == $today) {
																	$class_label = "primary";
																	$sign        = "NOT YET STARTED";
																}
																elseif ($yesterday <= $today && $row["loggedin"] == $due_date) {
																	$class_label = "primary";
																	$sign        = "NOT YET STARTED";
																}
																else {
																	$class_label = "danger";
																	$sign        = "EXPIRED";
																	$class       = "invalid";
																}
															}
															
															echo "<tr>";
															if ($due_date == $today){
																echo "<td class='". $class ."'> <input type='checkbox' class='messageCheckbox' name='item[]' id='flexCheckDefault' value='".$row['task_code']."'/> </td>";
															}
															else{
																echo "<td class='". $class ."'> <i class='fa fa-ban'></i> </td>";
															}
															echo "
															<td class='" . $class . "'>" . $row["task_code"] . " </td>";
															if ($row['requirement_status'] == 1) {
																echo "<td class='" . $class . "'> <span style='color: #00ff26'><i class='fa fa-paperclip' title='Attachment Required'></i></span></td>";
															}
															else {
																echo "<td class='" . $class . "'> </td>";
															}
															echo "                                                
															<td id='normalwrap' class='" . $class . "'> " . $row["task_name"] . " </td>
															<td class='" . $class . "'>" . $row["task_class"] . "</td>
															<td class='" . $class . "'>" . $due . "</td>
															<td class='" . $class . "' style='text-align: center'> <img src=" . $imageURL . " title=" . $row["username"] . " style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-left: 0'></td>  
															<td class='" . $class . "'><center/><p class='label label-" . $class_label . "' style='font-size:100%;'>" . $sign . "</p></td>";
															if ($due_date == $today) {
																echo " <td> <center/><button id='task_id' value='" . $row['task_code'] . "' class='btn btn-primary' onclick='start(this)'><i class='fa fa-play-circle fa-1x'></i> </button></td>";
															}
															elseif ($due_date > $today) {
																echo " <td> <center/><button disabled id='task_id' value='" . $row['task_code'] . "' class='btn btn-info' onclick='start(this)'><i class='fas fa-clock fa-1x'></i> </button></td>";
															}
															elseif ($yesterday <= $today && $row["loggedin"] == $due_date) {
																echo " <td> <center/><button id='task_id' value='" . $row['task_code'] . "' class='btn btn-primary' onclick='start(this)'><i class='fa fa-play-circle fa-1x'></i> </button></td>";
															}
															else {
																echo " <td class='" . $class . "'> <center/><button disabled id='task_id' value='" . $row['task_code'] . "' class='btn btn-danger' onclick='start(this)'><i class='fa fa-exclamation-circle fa-1x'></i> </button></td>";
															}
															echo "</tr>";
														}
													}
												}
												else if ($status == "IN PROGRESS") {
													$result = mysqli_query($con, "SELECT * FROM tasks_details JOIN accounts ON tasks_details.in_charge=accounts.username JOIN task_class on task_class.id=tasks_details.task_class WHERE in_charge='$username' AND tasks_details.status='$status' AND tasks_details.task_status=1");
													if (mysqli_num_rows($result) > 0) {
														while ($row = $result->fetch_assoc()) {
															$due_date   = $row["due_date"];
															$due        = date('d-m-Y h:i A', strtotime($row['due_date'].'16:00:00'));
															$verify     = $row['requirement_status'];
															$twodago    = date('Y-m-d', strtotime($due_date . ' +' . 2 . ' days'));
															$today      = date('Y-m-d');
															$class      = '';
															$task_class = $row['task_class'];
															$emp_name   = $row['fname'] . ' ' . $row['lname'];

															if ($today > $due_date) {
																$class       = "invalid";
																$sign        = "OVERDUE";
																$class_label = "danger";
															}
															else {
																$sign        = "IN PROGRESS";
																$class_label = "warning";
															}
															echo "<tr>
															<td class='" . $class . "'> " . $row["task_code"] . " </td>";
															if ($row['requirement_status'] == 1) {
																echo "<td class='" . $class . "'> <span style='color: #00ff26'><i class='fa fa-paperclip' title='Attachment Required'></i></span></td>";
															} 
															else {
																echo "<td class='" . $class . "'> </td>";
															}
															echo "                                                    
															<td id='normalwrap' class='" . $class . "'> " . $row["task_name"] . " </td>  
															<td class='" . $class . "'>" . $row["task_class"] . "</td>  
															<td class='" . $class . "'>" . $due . "</td> 
															<td class='" . $class . "' style='text-align: center'> <img src=" . $imageURL . " title=" . $row["username"] . " style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-left: 0'></td>  
															<td class='" . $class . "'><center/>
															<p class='label label-" . $class_label . "' style='font-size:100%;'>" . $sign . "</p></td>";
															if ($verify == 1) {
																echo "
																<td class='" . $class . "'> <center/><button id='task_id' value='" . $row['task_code'] . "' class='btn btn-danger' onclick='finish_with_attachment(this)'><i class='fa fa-stop fa-1x'></i></button>
																</td>
																</tr>";
															}
															else {
																echo "
																<td class='" . $class . "'> <center/><button id='task_id' value='" . $row['task_code'] . "' class='btn btn-danger' onclick='finish_without_attachment(this)'><i class='fa fa-stop fa-1x'></i></button>
																</td>
																</tr>";
															}
														}
													}
												}
												else if ($status == "FINISHED") {
													$result = mysqli_query($con, "SELECT * FROM tasks_details JOIN task_class ON tasks_details.task_class = task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE in_charge='$username' AND tasks_details.status='$status' AND tasks_details.task_status=1 AND tasks_details.approval_status=0");
													if (mysqli_num_rows($result) > 0) {
														while ($row = $result->fetch_assoc()) {
															$achievement = $row['achievement'];
															$emp_name    = $row['fname'] . ' ' . $row['lname'];
															$date        = date('d-m-Y h:i A', strtotime($row['date_accomplished']));

															if ($status == 'FINISHED' && $achievement != 0) {
																$class_label = "success";
																$sign        = "FINISHED";
															}
															if ($status == 'FINISHED' && $achievement == 0) {
																$class_label = "danger";
																$sign        = "FAILED";
															}
															echo "<tr>
															<td> " . $row["task_code"] . " </td>";
															if ($row['requirement_status'] == 1) {
																echo "<td> <span style='color: #00ff26'><i class='fa fa-paperclip' title='Attachment Required'></i></span></td>";
															} 
															else {
																echo "<td> </td>";
															}
															echo "                                                  
															<td id='normalwrap'> " . $row["task_name"] . " </td>                                                            
															<td>" . $row["task_class"] . "</td>  
															<td>" . $date . "</td>
															<td style='text-align: center'> <img src=" . $imageURL . " title=" . $row["username"] . " style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-left: 0'></td>  
															<td><center/><p class='label label-" . $class_label . "' style='font-size:100%;'>" . $sign . "</p></td>
															<td><center />" . $achievement . "</td>
															<td><center><button value='" . $row['task_code'] . "' data-name='" . $row['task_name'] . "' data-class='" . $row['task_class'] . "' data-remarks='" . $row['remarks'] . "' data-duedate='" . $row['due_date'] . "' data-datefinish='" . $row['date_accomplished'] . "' data-achievement='" . $row['achievement'] . "' data-file='" . $row['requirement_status'] . "' data-path='" . $row['attachment'] . "' data-note='" . $row['head_note'] . "' data-head='" . $row['head_name'] . "' class='btn btn-primary' onclick='view1(this)'><span class='fa fa-folder-open'></span> View </button></center></td> 
															</tr>";
														}
													}
												}
												else if ($status == "VERIFICATION") {
													$result = mysqli_query($con, "SELECT * FROM tasks_details JOIN task_class ON tasks_details.task_class = task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE in_charge='$username' AND tasks_details.status='FINISHED' AND tasks_details.achievement!=0 AND tasks_details.task_status=1 AND tasks_details.approval_status=1");
													if (mysqli_num_rows($result) > 0) {
														while ($row = $result->fetch_assoc()) {
															$achievement = $row['achievement'];
															$emp_name    = $row['fname'] . ' ' . $row['lname'];
															$date        = date('d-m-Y h:i A', strtotime($row['date_accomplished']));

															if ($status == 'VERIFICATION') {
																$class_label = "danger";
																$sign        = "TBD";
															}
															echo "<tr>
															<td> " . $row["task_code"] . " </td>";
															if ($row['requirement_status'] == 1) {
																echo "<td> <span style='color: #00ff26'><i class='fa fa-paperclip' title='Attachment Required'></i></span></td>";
															}
															else {
																echo "<td> </td>";
															}
															echo "                                                  
															<td id='normalwrap'> " . $row["task_name"] . " </td>                                                            
															<td>" . $row["task_class"] . "</td> 
															<td>" . $date . "</td>
															<td style='text-align: center'> <img src=" . $imageURL . " title=" . $row["username"] . " style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-left: 0'></td>  
															<td><center/><p class='label label-" . $class_label . "' style='font-size:100%;'>" . $sign . "</p></td>
															<td><center />" . $achievement . "</td>
															<td><center><button value='" . $row['task_code'] . "' data-name='" . $row['task_name'] . "' data-class='" . $row['task_class'] . "' data-remarks='" . $row['remarks'] . "' data-duedate='" . $row['due_date'] . "' data-datefinish='" . $row['date_accomplished'] . "' data-achievement='" . $row['achievement'] . "' data-file='" . $row['requirement_status'] . "' data-path='" . $row['attachment'] . "' class='btn btn-primary' onclick='view2(this)'><span class='fa fa-folder-open'></span> View </button></center></td> 
															</tr>";
														}
													}
												}
												else if ($status == "RESCHEDULE") {
													$result = mysqli_query($con, "SELECT * FROM tasks_details LEFT JOIN task_list ON tasks_details.task_name = task_list.task_name LEFT JOIN task_class ON task_list.task_class = task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE tasks_details.status='NOT YET STARTED' AND tasks_details.reschedule=1 AND in_charge='$username'");
													if (mysqli_num_rows($result) > 0) {
														while ($row = $result->fetch_assoc()) {
															$achievement = $row['achievement'];
															$emp_name    = $row['fname'] . ' ' . $row['lname'];
															$due         = date('d-m-Y h:i A', strtotime($row['due_date']));

															if ($status == 'RESCHEDULE') {
																$class_label = "info";
																$sign        = "RESCHEDULE PENDING";
															}
															echo "<tr>
															<td> " . $row["task_code"] . " </td>";
															if ($row['requirement_status'] == 1) {
																echo "<td> <span style='color: #00ff26'><i class='fa fa-paperclip' title='Attachment Required'></i></span></td>";
															} 
															else {
																echo "<td> </td>";
															}
															echo "                                                      
															<td id='normalwrap'> " . $row["task_name"] . " </td>                                                            
															<td>" . $row["task_class"] . "</td>  
															<td>" . $due . "</td> 
															<td style='text-align: center'> <img src=" . $imageURL . " title=" . $row["username"] . " style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-left: 0'></td>  
															<td><center/><p class='label label-" . $class_label . "' style='font-size:100%;'>" . $sign . "</p></td>
															</tr>";
														}
													}
												}
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<?php
							if ($status != "NOT YET STARTED" && $status != "IN PROGRESS" && $status != "FINISHED") {
								echo "<a href='index.php'> <button class='btn btn-danger pull-left'><i class='fa fa-arrow-left'></i> Return to Dashboard</button></a>";
							}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		$(document).ready(function() {
				$('#table_task').DataTable({
						responsive: true,
						destroy: true,
						"order": [[ 4, "asc" ]]
				});
		});

		function done(obj) {
			var taskID = obj.value;

			$(document).ready(function() { 
				$('#caution').modal('show');
			});
    }

    window.onload = function() {
			var checkboxes = document.querySelectorAll('.messageCheckbox');
			var button = document.getElementById('hidden-btn');

			function checkCheckboxes() {
				var isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
				button.style.display = isChecked ? 'block' : 'none';
			}

			checkboxes.forEach(function(checkbox) {
				checkbox.addEventListener('change', checkCheckboxes);
			});

			checkCheckboxes();
    };

		function toggleAll(source) {
			const checkboxes = document.querySelectorAll('input[name="item[]"]');

			checkboxes.forEach((checkbox) => {
				checkbox.checked = source.checked;
			});
    }

    const selectAllCheckbox = document.getElementById('selectAll');
    selectAllCheckbox.addEventListener('change', function () {
			toggleAll(this);
    });

    $(document).ready(function () {
			var btn = document.getElementById('submit-button');
        $("#submit-button").on("click", function () {
					var selectedValues = [];

				$(".messageCheckbox:checked").each(function () {
					selectedValues.push($(this).val());
				});

				console.log("Selected values:", selectedValues);

				$.ajax({
					url: "task_details_start_array.php",
					method: "POST",
					data: { selectedValues: selectedValues },
					success: function (response) {
						document.getElementById('submit-button').disabled = true;
						btn.textContent = 'Starting...';
						setTimeout(function () {
						$('#caution').modal('hide');
						}, 2000);
						setTimeout(function () {
							$('#success1').modal('show');
						}, 2000);

						console.log("Data sent successfully:", response);
						// $('#caution').modal('hide');
						// $('#success1').modal('show'); 
					},
					error: function (error) {
						console.error("Error sending data:", error);
					}
				});
			});
    });

		function view1(element) {
			var taskcode = element.value;
			var taskname = element.getAttribute("data-name");
			var taskclass = element.getAttribute("data-class");
			var remarks = element.getAttribute("data-remarks");
			var duedate = element.getAttribute("data-duedate");
			var datefinish = element.getAttribute("data-datefinish");
			var achievement = element.getAttribute("data-achievement");
			var file = element.getAttribute("data-file");
			var filepath = element.getAttribute("data-path");
			var headname = element.getAttribute("data-head");
			var headnote = element.getAttribute("data-note");
			$(document).ready(function () {
				$('#view1').modal('show');
				document.getElementById('taskcode').value = taskcode;
				document.getElementById('taskname').value = taskname;
				document.getElementById('taskclass').value = taskclass;
				document.getElementById('tracer').value = taskcode;
				document.getElementById('remarks').value = remarks;
				document.getElementById('duedate').value = duedate;
				document.getElementById('datefinish').value = datefinish;
				document.getElementById('achievement').value = achievement;
				document.getElementById('headname').value = headname;
				document.getElementById('headnote').value = headnote;
				document.getElementById('filepath').innerHTML = filepath;
				document.getElementById('file').value = file;
				document.getElementById('filepath').href = 'attachment_download.php?filetrack=' + encodeURIComponent(filepath);

				var button = $("#showdownload");
				if (file == 0) {
					button.hide();
				} else {
					button.show();
				}

				var hidenotes = $('#shownote');
				if (headnote == '') {
					hidenotes.hide();
				} else {
					hidenotes.show();
				}

			});
		}

		function view2(element) {
			var taskcode2 = element.value;
			var taskname2 = element.getAttribute("data-name");
			var taskclass2 = element.getAttribute("data-class");
			var remarks2 = element.getAttribute("data-remarks");
			var duedate2 = element.getAttribute("data-duedate");
			var datefinish2 = element.getAttribute("data-datefinish");
			var achievement2 = element.getAttribute("data-achievement");
			var filepath2 = element.getAttribute("data-path");
			var file2 = element.getAttribute("data-file");
			$(document).ready(function () {
				$('#view2').modal('show');
				document.getElementById('taskcode2').value = taskcode2;
				document.getElementById('taskname2').value = taskname2;
				document.getElementById('taskclass2').value = taskclass2;
				document.getElementById('tracer2').value = taskcode2;
				document.getElementById('remarks2').value = remarks2;
				document.getElementById('duedate2').value = duedate2;
				document.getElementById('datefinish2').value = datefinish2;
				document.getElementById('achievement2').value = achievement2;
				document.getElementById('filepath2').innerHTML = filepath2;
				document.getElementById('file2').value = file2;
				document.getElementById('filepath2').href = 'attachment_download.php?filetrack=' + encodeURIComponent(filepath2);

				var targetDiv = $('#show');
				if (file2 == '1') {
					tragetDiv.show();
				} else {
					targetDiv.hide();
				}
			});
		}

		function edit_task1(obj) {
			var inputcode = document.getElementById('taskcode2').value;
			var inputremarks = document.getElementById('remarks2').value;
			var fileinput = document.getElementById('file2').value;

			$(document).ready(function () {
				$('#edit_1').modal('show');
				document.getElementById('track').value = inputcode;
				document.getElementById('myH1').innerHTML = inputcode;
				document.getElementById('submited_remarks').value = inputremarks;
			});
		}

		function start(obj) {
			var taskID = obj.value;

			$(document).ready(function () {
				$('#start').modal('show');
				document.getElementById('modal_task_id2').
				innerHTML = taskID;
				document.getElementById('hidden_task_id2').
				value = taskID;
			});
		}

		function okButtonClick2() {
			var taskID = document.getElementById('hidden_task_id2').value;
			var btn = document.getElementById('okButton');
			var originalText = btn.textContent; // Store the original button text
			$.ajax({
				type: "POST",
				url: "task_details_start.php",
				data: {
					id: taskID
				}
			}).done(function (response) {
				document.getElementById('okButton').disabled = true;
				btn.textContent = 'Starting...'; // Change the button text to "Waiting"
				setTimeout(function () {
					$('#start').modal('hide');
				}, 2000); // Adjust the delay time (in milliseconds) as needed
				setTimeout(function () {
					$('#success1').modal('show');
				}, 2000); // Adjust the delay time (in milliseconds) as needed
				//window.location.reload();
			}).fail(function (xhr, status, error) {
				alert("An error occurred: " + status + "\nError: " + error);
			});
		}

		function showValue(button) {
			// get the span element next to the button
			var span = button.nextElementSibling;
			// show the span element
			span.style.display = "inline";
			// hide the button
			button.style.display = "none";
		}

		function finish_with_attachment(obj) {
			var taskID = obj.value;
			$(document).ready(function () {
				$('#finish_1').modal('show');
				document.getElementById('modal_task_id').
				innerHTML = taskID;
				document.getElementById('hidden_task_id').
				value = taskID;
			});
		}

		function finish_without_attachment(obj) {
			var taskID = obj.value;
			$(document).ready(function () {
				$('#finish_2').modal('show');
				document.getElementById('modal_task_id').
				innerHTML = taskID;
				document.getElementById('hidden_task_id').
				value = taskID;
			});
		}

		function okButtonClick_1() {
			var taskID = document.getElementById('hidden_task_id').value;
			var action = document.getElementById('textArea_1').value;
			var btn = document.getElementById('okButton_1');
			var originalText = btn.textContent; // Store the original button text
			var fileInput = document.getElementById('imageInput'); // assuming you have an input element with id="image_input_field"
			var file = fileInput.files[0]; // get the selected file
			var formData = new FormData(); // create a new FormData object
			formData.append('id', taskID); // append the task ID
			formData.append('action', action); // append the action
			formData.append('file', file); // append the file

			$.ajax({
				type: "POST",
				url: "task_details_finish.php",
				data: formData, // send the FormData object
				contentType: false, // tell jQuery not to set the content type
				processData: false, // tell jQuery not to process the data
			}).done(function (response) {
				document.getElementById('okButton_1').disabled = true;
				btn.textContent = 'Submitting...'; // Change the button text to "Waiting"
				if (response === 'Success'){
					setTimeout(function () {
					$('#finish_1').modal('hide');
					}, 2000); // Adjust the delay time (in milliseconds) as needed
					setTimeout(function () {
						$('#success2').modal('show');
					}, 2000); // Adjust the delay time (in milliseconds) as needed
				}
				else if (response === 'File not supported') {
					setTimeout(function () {
					$('#finish_1').modal('hide');
					}, 2000); // Adjust the delay time (in milliseconds) as needed
					setTimeout(function () {
						$('#error').modal('show');
					}, 2000); // Adjust the delay time (in milliseconds) as needed
				}
				else if (response === 'Unexpected error') {
					setTimeout(function () {
					$('#finish_1').modal('hide');
					}, 2000); // Adjust the delay time (in milliseconds) as needed
					setTimeout(function () {
						$('#error').modal('show');
					}, 2000); // Adjust the delay time (in milliseconds) as needed
				}
			}).fail(function (xhr, status, error) {
				alert("An error occurred: " + status + "\nError: " + error);
			});
		}

		function okButtonClick_2() {
			document.querySelector('button').disabled = true;
			var taskID = document.getElementById('hidden_task_id').value;
			var action = document.getElementById('textArea_2').value;
			var btn = document.getElementById('okButton_2');
			var originalText = btn.textContent; // Store the original button text

			$.ajax({
					type: "POST",
					url: "task_details_finish.php",
					data: {
						id: taskID,
						action: action
					}
				})
				.done(function (response) {
					document.getElementById('okButton_2').disabled = true;
					btn.textContent = 'Submitting...'; // Change the button text to "Waiting"
					setTimeout(function () {
						$('#finish_2').modal('hide');
					}, 2000); // Adjust the delay time (in milliseconds) as needed
					setTimeout(function () {
						$('#success2').modal('show');
					}, 2000); // Adjust the delay time (in milliseconds) as needed
				})
				.fail(function (xhr, status, error) {
					alert("An error occurred: " + status + "\nError: " + error);
				});
		}

		function okButtonClick_3() {
			var trackcode = document.getElementById('track').value;
			var actionedit = document.getElementById('submited_remarks').value;
			var btn = document.getElementById('saveedit');
			var originalText = btn.textContent; // Store the original button text
			console.log("Track Code:", trackcode);
			console.log("Action Edit:", actionedit);

			$.ajax({
					type: "POST",
					url: "task_details_finish_edit.php",
					data: {
						id: trackcode,
						action: actionedit
					}
				})
				.done(function (response) {
					document.getElementById('saveedit').disabled = true;
					btn.textContent = 'Saving...'; // Change the button text to "Waiting"
					setTimeout(function () {
						$('#edit_1').modal('hide');
						window.location.reload();
					}, 2000); // Adjust the delay time (in milliseconds) as needed
				})
				.fail(function (xhr, status, error) {
					alert("An error occurred: " + status + "\nError: " + error);
				});
		}

		function checkTextLength_with_attachment() {
			var textArea = document.getElementById('textArea_1');
			var imageInput = document.getElementById('imageInput');
			var okButton = document.getElementById('okButton_1');

			// Check if the textarea has at least 30 characters
			var isTextAreaValid = textArea.value.length >= 30;

			// Check if a file is selected in the input type file
			var isimageInputValid = imageInput.files.length > 0;

			// Enable the button only if both conditions are met
			okButton.disabled = !(isTextAreaValid && isimageInputValid);
		}

		function checkTextLength_without_attachment() {
			var textArea = document.getElementById('textArea_2');
			var okButton = document.getElementById('okButton_2');

			if (textArea.value.length >= 30) {
				okButton.disabled = false;
			} else {
				okButton.disabled = true;
			}
		}

		function checkInput() {
			var date = document.getElementById('request_date').value;
			var reason = document.getElementById('resched_reason').value;
			var okButton = document.getElementById('okButton1');

			if (date != "" && reason != "") {
				okButton.disabled = false;
			} else {
				okButton.disabled = true;
			}
		}

		function selectto(element) {
			let valto = $(element).val();
			let status = <?php echo json_encode($status) ?>;
			let valfrom = $('#val_from').val();
			let username = <?php echo json_encode($username) ?>;
			$('#table_task').DataTable().destroy();
			$('#show_task').empty();
			if (valto) {
				$.ajax({
					type: "post",
					url: "ajax_valto.php",
					data: {
						"valfrom": valfrom,
						"status": status,
						"valto": valto,
						"username": username
					},
					success: function (response) {
						$('#show_task').append(response);
						$('#table_task').DataTable();
					}
				});
			}
		}

		function selectfrom(element) {
			let valfrom = $(element).val();
			let status = <?php echo json_encode($status) ?>;
			let valto = $('#val_to').val();
			let username = <?php echo json_encode($username) ?>;
			$('#table_task').DataTable().destroy();
			$('#show_task').empty();
			if (valfrom) {
				$.ajax({
					type: "post",
					url: "ajax_valfrom.php",
					data: {
						"valfrom": valfrom,
						"status": status,
						"valto": valto,
						"username": username
					},
					success: function (response) {
						$('#show_task').append(response);
						$('#table_task').DataTable();
					}
				});
			}
		}

		function goBackAndReload() {
			window.history.back();
			location.reload();
		}
	</script>

	<div class="modal fade" id="caution" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content panel-success">
				<div class="modal-header panel-heading">
					<a href="pending_for_approval.php"> <button type="button" class="close" aria-hidden="true"> &times; </button> </a>
					<h4 class="modal-title" id="myModalLabel"> Caution </h4>
				</div>
				<div class="modal-body panel-body">
					<center>
						<i style="color: yellow; font-size:80px;" class="fa fa-exclamation-triangle"></i>
						<br><br>
						<p>
							You're about to start all of the marked tasks.
							<br>
							Do you wish to continue?
						</p>
					</center>
				</div>
				<div class="modal-footer">
					<button type="button" id='submit-button' class="btn btn-success pull-left"> <i class="fa fa-check-circle"> </i> Yes </button>
					<button type="button" name="submit" class="btn btn-danger pull-right" data-dismiss="modal"> <i class="fa fa-times-circle"> </i> No </button>
					</a>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content panel-success">
				<div class="modal-header panel-heading">
					<h4 class="modal-title" id="myModalLabel">Warning!</h4>
				</div>
				<div class="modal-body panel-body">
					<center>
						<i style="color:#e13232; font-size:80px;" class="fa fa-times-circle "></i>
						<br><br>
						File is not supported!
						<br><br>
						Please select PDF, XLS, XLSX, 
						DOCX, PPTX and TXT file only.
					</center>
				</div>
				<div class="modal-footer">
					<a href="task_details.php?status=IN PROGRESS"><button type="button" name="submit" class="btn btn-danger pull-right"><i class='fa fa-times fa-1x'></i> Cancel</button></a>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="start" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content panel-success">
				<div class="modal-header panel-heading">
					<a href="task_details.php?status=NOT YET STARTED"><button type="button" class="close" aria-hidden="true">&times;</button></a>
					<h4 class="modal-title" id="myModalLabel">Start Task</h4>
				</div>
				<div class="modal-body panel-body">
					<center>
						<i style="color:yellow; font-size:80px;" class="fa fa-question-circle"></i>
						<br><br>
						<span  id="modal_task_id2"></span> <!-- Add this span -->
						<p>Do you want to start this task?</p>
						<input type="hidden" id="hidden_task_id2" name="hidden_task_id2">
					</center>
				</div>
				<div class="modal-footer">
					<button id='okButton' class='btn btn-success pull-left' onclick='okButtonClick2()'><i class='fa fa-play fa-1x'></i> Start</button>
					<a href="task_details.php?status=NOT YET STARTED"><button type="button" name="submit" class="btn btn-danger pull-right"><i class='fa fa-times fa-1x'></i> Cancel</button></a>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="finish_1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
		<div class="modal-dialog modal-lg"
			<div class="modal-content panel-success">
				<div class="modal-header panel-heading">
					<button type="button" class="close" aria-hidden="true" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Finish Task</h4>
				</div>
				<div class="modal-body panel-body">
					<center>
						<i class="fa fa-pencil-square-o"></i> <b>&</b> <i class="fa fa-file"></i>
						<p>Write your remarks and attach a file for this task.</p>
						<span id="modal_task_id" hidden></span> <!-- Add this span -->
						<input type="hidden" id="hidden_task_id" name="hidden_task_id">
						<textarea id="textArea_1" class="form-control" onkeyup="checkTextLength_with_attachment()" placeholder="Please input atleast 30 characters."></textarea>
						<br>
						<div class="form-group">
							<input type="file" id="imageInput" accept=".pdf, .docx, .xls, .xlsx, .pptx, .txt" />
							<p style="color: #fa8a82">Please ensure to check your files before you click submit, since you cannot <b>WITHDRAW</b> nor <b>EDIT</b> this file after.</p>
						</div>
					</center>
				</div>
				<div class="modal-footer">
					<button disabled id='okButton_1' class='btn btn-success pull-left' onclick='okButtonClick_1()'><i class='fa fa-paper-plane fa-1x'></i> Submit</button>
					<button type="button" name="submit" class="btn btn-danger pull-right" data-dismiss="modal"><i class='fa fa-times fa-1x'></i> Cancel</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="finish_2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content panel-success">
				<div class="modal-header panel-heading">
					<button type="button" class="close" aria-hidden="true" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Finish Task</h4>
				</div>
				<div class="modal-body panel-body">
					<center>
						<i class="fa fa-pencil-square-o"></i>
						<p>Write your remarks for this task.</p>
						<span id="modal_task_id" hidden></span> <!-- Add this span -->					
						<input type="hidden" id="hidden_task_id" name="hidden_task_id">
						<textarea id="textArea_2" class="form-control" onkeyup="checkTextLength_without_attachment()" placeholder="Please input atleast 30 characters."></textarea>
					</center>
				</div>
				<div class="modal-footer">
					<button disabled id='okButton_2' class='btn btn-success pull-left' onclick='okButtonClick_2()'><i class='fa fa-paper-plane fa-1x'></i> Submit</button>
					<button type="button" name="submit" class="btn btn-danger pull-right" data-dismiss="modal"><i class='fa fa-times fa-1x'></i> Cancel</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="success1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content panel-success">
				<div class="modal-header panel-heading">
					<a href="task_details.php?status=NOT YET STARTED"><button type="button" class="close" aria-hidden="true">&times;</button></a>
					<h4 class="modal-title" id="myModalLabel">Notice</h4>
				</div>
				<div class="modal-body panel-body">
					<center>
						<i style="color:green; font-size:80px;" class="fa fa-check"></i>
						<br><br>
						<p>Task has been started.</p>
					</center>
				</div>
				<div class="modal-footer">
					<a href="task_details.php?status=NOT YET STARTED"><button type="button" name="submit" class="btn btn-danger pull-right"><i class='fa fa-times fa-1x'></i> Close</button></a>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="success2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content panel-success">
				<div class="modal-header panel-heading">
					<a href="task_details.php?status=IN PROGRESS"><button type="button" class="close" aria-hidden="true">&times;</button></a>
					<h4 class="modal-title" id="myModalLabel">Notice</h4>
				</div>
				<div class="modal-body panel-body">
					<center>
						<i style="color:green; font-size:80px;" class="fa fa-check"></i>
						<br><br>
						<p>Your task has been finished and sent to the head for verification and final rating.</p>
					</center>
				</div>
				<div class="modal-footer">
					<a href="task_details.php?status=IN PROGRESS"><button type="button" name="submit" class="btn btn-danger pull-right"><i class='fa fa-times fa-1x'></i> Close</button></a>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="success3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content panel-success">
				<div class="modal-header panel-heading">
					<a href="task_details.php?status=NOT YET STARTED"><button type="button" class="close" aria-hidden="true">&times;</button></a>
					<h4 class="modal-title" id="myModalLabel">Notice</h4>
				</div>
				<div class="modal-body panel-body">
					<center>
						<i style="color:#e13232; font-size:80px;" class="fa fa-check"></i>
						<br><br>
						<p>Task request submitted.</p>
					</center>
				</div>
				<div class="modal-footer">
					<a href="task_details.php?status=NOT YET STARTED"><button type="button" name="submit" class="btn btn-success pull-right">OK</button></a>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="success4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content panel-success">
				<div class="modal-header panel-heading">
					<button type="button" class="close" aria-hidden="true" onclick='goBackAndReload()'>&times;</button>
					<h4 class="modal-title" id="myModalLabel">Notice</h4>
				</div>
				<div class="modal-body panel-body">
					<center>
						<i style="color:#e13232; font-size:80px;" class="fa fa-fa-check"></i>
						<br><br>
						<p>You successfully updated the remarks for your task.</p>
					</center>
				</div>
				<div class="modal-footer">
					<button type="button" name="submit" class="btn btn-success pull-right" onclick='goBackAndReload()'>OK</button>
				</div>
			</div>
		</div>
	</div>

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
							<textarea class="form-control" name="remarks" id="remarks" readonly></textarea>
							<br>
							<div id="showdownload">
								<label>File Attachement:</label>
								<input type="text" name="file" id="file" hidden><br>
								<span style='color: #00ff26'><i class='fa fa-paperclip'></i></span>
								<a href="attachment_download.php" id="filepath"></a>
							</div>
							<br>
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

	<div class="modal fade" id="view2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content panel-success" >
				<div class="modal-header panel-heading">
					<a href="#" onclick="goBackAndReload()"><button type="button" class="close">&times;</button></a>
					<h4 class="modal-title" id="myModalLabel">Task Details</h4>
					<hr>
				</div>
				<div class="modal-body panel-body">
					<form data-toggle="validator" class="className" name="form" id="form" action="attachment_download.php" method="POST">
						<div class='form-group col-lg-3'>
							<label>Task Code:</label>
							<input type="text" class="form-control" name="taskcode2" id="taskcode2" disabled><br>
							<input type="text" name="tracer2" id="tracer2" hidden>
						</div>
						<div class='form-group col-lg-3'>
							<label>Due Date:</label>
							<input type="date" class="form-control" name="duedate2" id="duedate2" disabled><br>
						</div>
						<div class='form-group col-lg-4'>
							<label>Date Accomplished:</label>
							<input type="datetime-local" class="form-control" name="datefinish2" id="datefinish2" disabled><br>
						</div>
						<div class='form-group col-lg-2'>
							<label>Initial Score:</label>
							<input type="text" class="form-control" name="achievement2" id="achievement2" disabled>
						</div>
						<div class='form-group col-lg-12'>
							<label>Task Name:</label>
							<input type="text" class="form-control" name="taskname2" id="taskname2" disabled><br>
							<label>Task Classification:</label>
							<input type="text" class="form-control" name="taskclass2" id="taskclass2" disabled><br>
							<label>Task Remarks:</label>
							<textarea class="form-control" name="remarks2" id="remarks2" readonly></textarea>
							<br>
							<div id="show">
								<label>File Attachement:</label>
								<input type="text" name="file2" id="file2" hidden><br>
								<span style='color: #00ff26'><i class='fa fa-paperclip'></i></span>
								<a href="attachment_download.php" id="filepath2"></a>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success pull-left" data-dismiss="modal" onclick='edit_task1()'><span class="fa fa-pencil-square-o"></span> Edit Remarks</button>
					<a href="#" onclick="goBackAndReload()"><button type="button" class="btn btn-danger pull-right"><span class="fa fa-times"></span> Close</button></a>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="edit_1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content panel-success">
				<div class="modal-header panel-heading">
					<a href="#" onclick="goBackAndReload()"><button type="button" class="close">&times;</button></a>
					<h4 class="modal-title" id="myModalLabel">Finish Task</h4>
				</div>
				<div class="modal-body panel-body">
					<center>
						<input type="text" id="track" name="track" hidden>
						<p>Edit your remarks for the task</p>
						<p id="myH1"></p>
						<textarea id="submited_remarks" class="form-control" placeholder="Please input atleast 30 characters."></textarea>
						<br>
					</center>
				</div>
				<div class="modal-footer">
					<button id='saveedit' class='btn btn-success pull-left' onclick="okButtonClick_3()"><i class='fa fa-floppy-o fa-1x'></i> Save</button>
					<a href="#" onclick="goBackAndReload()"><button type="button" class="btn btn-danger pull-right"><span class="fa fa-times"></span> Close</button></a>
				</div>
			</div>
		</div>
	</div>

</html>