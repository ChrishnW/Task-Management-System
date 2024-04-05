<?php 
	include('../include/header_employee.php');
	include('../include/connect.php');
	$today = date("Y-m-d");
	$month = date('m');
	$year = date('Y');
?>
<html>
	<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
	<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
	<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
	<link href="../assets/css/darkmode.css" rel="stylesheet">
	<head>
		<title>Employees Assigned Tasks</title>
	</head>
	<div id="content" class="p-4 p-md-5 pt-5">
		<div id="wrapper">
			<div id="page-wrapper">
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">Task Completed Summary
						</h1>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<?php echo $fname.' | '.$sec; ?>
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table width="100%" class="table table-hover " id="table_task">
										<thead>
											<tr>
												<th class="col-lg-2">
													<center />
													Task Code
												</th>
												<th class="col-lg-2">
													<center />
													Task Name
												</th>
												<th class="col-lg-2">
													<center />
													Task Classification
												</th>
												<th class="col-lg-2">
													<center />
													Due Date
												</th>
												<th class="col-lg-2">
													<center />
													Date Accomplished
												</th>
												<th class="col-lg-2">
													<center />
													Score
												</th>
											</tr>
										</thead>
										<tbody id="tbody">
											<?php
												$donetotal = 0;
												$tasktotal = 0;
												$totavg = 0;
												$donesum = 0;
												$remtask = 0;
												$ftask = 0;
												
												$result = mysqli_query($con,"SELECT * FROM tasks_details JOIN task_class ON tasks_details.task_class = task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE in_charge='$username' AND task_status!=0 AND MONTH(tasks_details.date_accomplished)='$month' AND YEAR(tasks_details.date_accomplished)='$year' AND tasks_details.date_accomplished IS NOT NULL AND approval_status=0");
												if (mysqli_num_rows($result)>0) { 
												while ($row = $result->fetch_assoc()) { 
												$taskcode = $row['task_code'];
												$taskname = $row['task_name'];
												$taskclass = $row['task_class'];
												$dateaccom = date('d-m-Y h:m A', strtotime($row['date_accomplished']));
												$datedue = date('d-m-Y h:m A', strtotime($row['due_date']));
												$datec = $row['date_created'];
												$achievement = $row['achievement'];
												
												if ($taskclass == "1"){
												    $taskclass = 'Daily Routine';
												}
												elseif ($taskclass == '2'){
												    $taskclass = 'Weekly Routine';
												}
												elseif ($taskclass == '3'){
												    $taskclass = 'Monthly Routine';
												}
												elseif ($taskclass == '4'){
												    $taskclass = 'Project';
												}
												
												echo "<tr>                                                       
												<td><center />" . $taskcode . "</td>
												<td id='normalwrap''>" . $taskname . "</td>
												<td><center />" . $taskclass . "</td>
												<td><center />" . $datedue . "</td>
												<td><center />" . $dateaccom . "</td>
												<td><center />" . $achievement . "</td>
												</tr>";
												}
												}
                                            ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<a href="#" onclick="history.back()"> <button class='btn btn-danger pull-left'><i class="fa fa-arrow-left"></i> Return to My Performance</button></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
		    $('#table_task').DataTable({
		        responsive: true,
		        "order": [[ 4, "desc" ]]
		    });
		});
	</script>
</html>