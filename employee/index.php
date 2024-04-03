<?php
	include('../include/header_employee.php');
	include('../include/bubbles.php');
	date_default_timezone_set("Asia/Manila");
	$dates = date('Y-m-d');
	$month = date('F');
	$md = date('F d');
	$name = mysqli_query($con,"SELECT * FROM accounts WHERE username='$username'");
	$rows = $name->fetch_assoc();
	$fname = $rows['fname'];
	$lname = $rows['lname'];
	?>
<html>
	<head>
		<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
		<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
		<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
		<link href="../assets/css/darkmode.css" rel="stylesheet">
	</head>
	<style>
		.panel>.panel-gray> {
		background-color: #ababab;
		border-color: #ababab;
		color: #fff;
		}
		.panel-heading {
		background-color: #ababab;
		border-color: #ababab;
		color: #fff;
		}
		.panel-gray {
		border-color: #ababab;
		}
		#page-wrapper a,
		#page-wrapper a:hover,
		#page-wrapper a:focus,
		#page-wrapper a:active {
		text-decoration: none;
		color: inherit;
		}
		.zoom:hover {
		transform: scale(1.05);
		transition: transform .5s;
		}
	</style>
	<body>
		<div id="wrapper">
			<div id="page-wrapper">
				<div class="row">
					<div class="col-lg-12">
						<!-- Dashboard <br>  -->
						<h2 class="page-header pull-left" style="font-family: monospace;">Welcome Back, <font color="#4287f5"><?php echo $fname ?></font>!
						</h2>
					</div>
				</div>
				<div class="clearfix visible-xs"></div>
				<div class="zoom">
					<div class="col-lg-4 col-md-6">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<a href="task_details.php?status=NOT YET STARTED">
									<div class="row">
										<div class="col-xs-3">
											<i class="fa fa-clock-o fa-spin fa-5x"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="huge">
												<?php
													$result = mysqli_query($con,"SELECT COUNT(id) as total_nys FROM tasks_details WHERE status='NOT YET STARTED' AND in_charge='$username' AND task_status IS TRUE AND approval_status IS TRUE  AND (reschedule = '0' OR reschedule = '2' AND approval_status=1)");
													$row = $result->fetch_assoc();
													echo $row['total_nys']; ?>
											</div>
											<div>Not Yet Started Tasks</div>
								</a>
								</div>
								</div>
							</div>
							<div class="panel-footer">
								<a href="task_details.php?status=NOT YET STARTED"><span class="pull-left">View Details</span></a>
								<span class="pull-right"><a href="task_details.php?status=NOT YET STARTED"><i class="fa fa-arrow-circle-right"></a></i></span>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="zoom">
					<div class="col-lg-4 col-md-6">
						<div class="panel panel-yellow">
							<div class="panel-heading">
								<a href="task_details.php?status=IN PROGRESS">
									<div class="row">
										<div class="col-xs-3">
											<i class="fas fa-spinner fa-spin fa-5x"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="huge">
												<?php
													$result = mysqli_query($con,"SELECT COUNT(id) as total_ongoing FROM tasks_details WHERE status='IN PROGRESS' AND in_charge='$username' AND task_status IS TRUE");
													$row = $result->fetch_assoc();
													echo $row['total_ongoing']; ?>
											</div>
											<div>Ongoing Tasks</div>
								</a>
								</div>
								</div>
							</div>
							<div class="panel-footer">
								<a href="task_details.php?status=IN PROGRESS"><span class="pull-left">View Details</span></a>
								<span class="pull-right"><a href="task_details.php?status=IN PROGRESS"><i class="fa fa-arrow-circle-right"></a></i></span>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="zoom">
					<div class="col-lg-4 col-md-6">
						<div class="panel panel-green">
							<div class="panel-heading">
								<a href="task_details.php?status=FINISHED">
									<div class="row">
										<div class="col-xs-3">
											<i class="fa fa-check-square fa-5x"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="huge">
												<?php
													$result = mysqli_query($con,"SELECT COUNT(id) as total_finished FROM tasks_details WHERE status='FINISHED' AND in_charge='$username' AND task_status IS TRUE");
													$row = $result->fetch_assoc();
													echo $row['total_finished']; ?>
											</div>
											<div>Finished Tasks</div>
								</a>
								</div>
								</div>
							</div>
							<div class="panel-footer">
								<a href="task_details.php?status=FINISHED"><span class="pull-left">View Details</span></a>
								<span class="pull-right"><a href="task_details.php?status=FINISHED"><i class="fa fa-arrow-circle-right"></a></i></span>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						My Tasks for the Month of <?php echo $month?>
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table id="dt-vertical-scroll" class="table" cellspacing="0" width="50%">
								<thead class="thead-light">
									<tr>
										<th scope="col">
											Task Code
										</th>
										<th scope="col">
											Task Classification
										</th>
										<th scope="col">
											Due Date
										</th>
										<th scope="col">
											In-charge
										</th>
										<th scope="col">
											<center>Status</center>
										</th>
									</tr>
								</thead>
								<tbody id="show_task">
									<?php
										$con->next_result();
											$result = mysqli_query($con,"SELECT tasks_details.task_code, tasks_details.in_charge, task_class.task_class, tasks_details.due_date, tasks_details.in_charge, tasks_details.status FROM tasks_details LEFT JOIN task_list ON task_list.task_code = tasks_details.task_code LEFT JOIN task_class ON task_class.id = task_list.task_class WHERE tasks_details.in_charge = '$username' AND tasks_details.status != 'FINISHED' GROUP BY tasks_details.id ORDER BY tasks_details.due_date ASC");
											if (mysqli_num_rows($result)>0) { 
												while ($row = $result->fetch_assoc()) {
													echo "<tr>                                                 
														<td> " . $row["task_code"] . " </td>
														<td> " . $row["task_class"] . " </td>
														<td> " . $row["due_date"] . " </td>
														<td> " . $row["in_charge"] . " </td>
														<td> " . $row["status"] . " </td>
														</tr>";
												}
											} 
										?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			</div>
		</div>
	</body>
<script>
$(document).ready(function () {
  $('#dt-vertical-scroll').dataTable({
	"order": [[ 2, "asc" ]],
    "paging": false,
    "fnInitComplete": function () {
      var myCustomScrollbar = document.querySelector('#dt-vertical-scroll_wrapper .dataTables_scrollBody');
      var ps = new PerfectScrollbar(myCustomScrollbar);
    },
    "scrollY": 450,
  });
});
</script>
</html>