<?php
	include('../include/header.php');
	date_default_timezone_set("Asia/Manila");
	$dates = date('Y-m-d');
	$month = date('F');
	$md = date('F d');
	$day = date('D');
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
	</head>
	<div id="content" class="p-4 p-md-5 pt-5">
		<div id="wrapper">
			<div id="page-wrapper">
				<div class="row">
					<div class="col-lg-12">
						<!-- Dashboard <br>  -->
						<h2 class="page-header pull-left">Dashboard</h2>
						<h2 class="page-header pull-right"><?php echo $day?>, <font color="#4287f5"><?php echo $md;?></font></h2>
					</div>
				</div>
				<div class="zoom">
					<div class="col-lg-3 col-md-6">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<a href="account_list.php">
									<div class="row">
										<div class="col-xs-3">
											<i class="fas fa-users fa-5x"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="huge">
												<?php
													$result = mysqli_query($con,"SELECT COUNT('#') as total_user FROM accounts WHERE status='1'");
													$row = $result->fetch_assoc();
													echo $row['total_user']; ?>
											</div>
											<div>Active Users</div>
								</a>
								</div>
								</div>
							</div>
							<div class="panel-footer">
								<a href="account_list.php"><span class="pull-left">View Details</span></a>
								<span class="pull-right"><a href="account_list.php"><i class="fa fa-arrow-circle-right"></a></i></span>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div>
                <div class="zoom">
					<div class="col-lg-3 col-md-6">
						<div class="panel panel-red">
							<div class="panel-heading">
								<a href="tasks.php?status=NOT YET STARTED">
									<div class="row">
										<div class="col-xs-3">
											<i class="fa fa-sticky-note fa-5x"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="huge">
												<?php
													$result = mysqli_query($con,"SELECT COUNT('#') as not_yet_started_task FROM tasks_details WHERE status='NOT YET STARTED' AND task_status != 0 AND reschedule >= 0 AND approval_status >= 0");
													$row = $result->fetch_assoc();
													echo $row['not_yet_started_task']; ?>
											</div>
											<div>Not Yet Started Tasks</div>
								</a>
								</div>
								</div>
							</div>
							<div class="panel-footer">
								<a href="tasks.php?status=NOT YET STARTED"><span class="pull-left">View Details</span></a>
								<span class="pull-right"><a href="tasks.php?status=NOT YET STARTED"><i class="fa fa-arrow-circle-right"></a></i></span>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="zoom">
					<div class="col-lg-3 col-md-6">
						<div class="panel panel-yellow">
							<div class="panel-heading">
								<a href="tasks.php?status=IN PROGRESS">
									<div class="row">
										<div class="col-xs-3">
											<i class="fa fa-hourglass-start fa-5x"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="huge">
												<?php
													$result = mysqli_query($con,"SELECT COUNT('#') as ongoing_task FROM tasks_details WHERE status='IN PROGRESS' AND task_status != 0 AND reschedule >= 0 AND approval_status >= 0");
													$row = $result->fetch_assoc();
													echo $row['ongoing_task']; ?>
											</div>
											<div>Ongoing Tasks</div>
								</a>
								</div>
								</div>
							</div>
							<div class="panel-footer">
								<a href="tasks.php?status=IN PROGRESS"><span class="pull-left">View Details</span></a>
								<span class="pull-right"><a href="tasks.php?status=ONGOING"><i class="fa fa-arrow-circle-right"></a></i></span>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="zoom">
					<div class="col-lg-3 col-md-6">
						<div class="panel panel-green">
							<div class="panel-heading">
								<a href="tasks.php?status=FINISHED">
									<div class="row">
										<div class="col-xs-3">
											<i class="fas fa-tasks fa-5x"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="huge">
												<?php
													$result = mysqli_query($con,"SELECT COUNT('#') as finished_task FROM tasks_details WHERE status='FINISHED' AND task_status != 0 AND reschedule >= 0 AND approval_status >= 0");
													$row = $result->fetch_assoc();
													echo $row['finished_task']; ?>
											</div>
											<div>Finished Tasks</div>
								</a>
								</div>
								</div>
							</div>
							<div class="panel-footer">
								<a href="tasks.php?status=FINISHED"><span class="pull-left">View Details</span></a>
								<span class="pull-right"><a href="tasks.php?status=FINISHED"><i class="fa fa-arrow-circle-right"></a></i></span>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</html>