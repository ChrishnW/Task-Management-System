<?php
	include('../include/header_employee.php');
	date_default_timezone_set("Asia/Manila");
	$dates = date('Y-m-d');
	$month = date('m');
	$year = date('Y');
	$md = date('F d');
	$day = date('l');
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
	<div id="content" class="p-4 p-md-5 pt-5">
		<div id="wrapper">
			<div id="page-wrapper">
				<div class="row">
					<div class="col-lg-12">
						<!-- Dashboard <br>  -->
						<h2 class="page-header pull-left">Dashboard</h2>
						<h2 class="page-header pull-right"><?php echo $day.', '.$md;?></font></h2>
					</div>
				</div>

				<div class="zoom">
					<div class="col-lg-4 col-md-6">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<a href="task_details.php?status=NOT YET STARTED">
									<div class="row">
										<div class="col-xs-3">
											<i class="fa fa-clock-o fa-5x"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="huge">
												<?php
													$result = mysqli_query($con,"SELECT COUNT(id) as not_yet_started_task FROM tasks_details WHERE status='NOT YET STARTED' AND task_status = 1 AND approval_status = 0 AND in_charge='$username' AND reschedule = 0 AND MONTH(tasks_details.due_date)='$month' AND YEAR(tasks_details.due_date)='$year'");
													$row = $result->fetch_assoc();
													echo $row['not_yet_started_task']; ?>
											</div>
											<div>To Do Tasks</div>
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
											<i class="fas fa-spinner fa-5x"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="huge">
												<?php
													$result = mysqli_query($con,"SELECT COUNT(id) as total_ongoing FROM tasks_details WHERE status='IN PROGRESS' AND task_status = 1 AND in_charge='$username'");
													$row = $result->fetch_assoc();
													echo $row['total_ongoing']; ?>
											</div>
											<div>In Progress Tasks</div>
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
													$result = mysqli_query($con,"SELECT COUNT(id) as total_finished FROM tasks_details WHERE status='FINISHED' AND task_status = 1 AND approval_status = 0 AND in_charge='$username'");
													$row = $result->fetch_assoc();
													echo $row['total_finished']; ?>
											</div>
											<div>Completed Tasks</div>
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

				<div class="zoom">
					<div class="col-lg-4 col-md-6">
						<div class="panel panel-red">
							<div class="panel-heading">
								<a href="task_details.php?status=VERIFICATION">
									<div class="row">
										<div class="col-xs-3">
											<i class="fa fa-gavel fa-5x"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="huge">
												<?php
													$result = mysqli_query($con,"SELECT COUNT(id) as under_review FROM tasks_details WHERE status='FINISHED' AND task_status != 0 AND approval_status = 1 AND in_charge='$username'");
													$row = $result->fetch_assoc();
													echo $row['under_review']; ?>
											</div>
											<div>Under Review Tasks</div>
								</a>
								</div>
								</div>
							</div>
							<div class="panel-footer">
								<a href="task_details.php?status=VERIFICATION"><span class="pull-left">View Details</span></a>
								<span class="pull-right"><a href="task_details.php?status=VERIFICATION"><i class="fa fa-arrow-circle-right"></a></i></span>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div>

				<div class="zoom">
					<div class="col-lg-4 col-md-6">
						<div class="panel panel-lightblue">
							<div class="panel-heading">
								<a href="task_details.php?status=RESCHEDULE">
									<div class="row">
										<div class="col-xs-3">
											<i class="fa fa-thumb-tack fa-5x"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="huge">
												<?php
													$result = mysqli_query($con,"SELECT COUNT(id) as for_rescheduling FROM tasks_details WHERE status='NOT YET STARTED' AND task_status != 0 AND reschedule=1 AND in_charge='$username'");
													$row = $result->fetch_assoc();
													echo $row['for_rescheduling']; ?>
											</div>
											<div>Reschedule Tasks</div>
								</a>
								</div>
								</div>
							</div>
							<div class="panel-footer">
								<a href="task_details.php?status=RESCHEDULE"><span class="pull-left">View Details</span></a>
								<span class="pull-right"><a href="task_details.php?status=RESCHEDULE"><i class="fa fa-arrow-circle-right"></a></i></span>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</html>