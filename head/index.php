<?php
	include('../include/header_head.php');
	date_default_timezone_set("Asia/Manila");
	$dates = date('Y-m-d');
	$year = date('Y');
	$month = date('m');
	$day = date('l, F d');
	$name = mysqli_query($con,"SELECT * FROM accounts WHERE username='$username'");
	$rows = $name->fetch_assoc();
	$fname = $rows['fname'];
	?>
<html>
	<head>
		<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
		<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
		<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
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
	<!-- Script for Charts -->
	<script src="../assets/js/Chart.js"></script>
	<div id="content" class="p-4 p-md-5 pt-5">
		<div id="wrapper">
			<div id="page-wrapper">
				<div class="row">
					<div class="col-lg-12">
						<!-- Dashboard <br>  -->
						<h2 class="page-header pull-left">Dashboard</h2>
						<h2 class="page-header pull-right"><font><?php echo $day;?></font></h2>
					</div>
				</div>

				<div class="zoom">
					<div class="col-lg-3 col-md-6">
						<div class="panel panel-lightblue">
							<div class="panel-heading">
								<a href="task_approval.php">
									<div class="row">
										<div class="col-xs-3">
											<i class="fas fa-bell faa-ring animated fa-5x"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="huge">
												<?php
													$result = mysqli_query($con,"SELECT COUNT(tasks_details.id) as for_approval_task FROM tasks_details JOIN section ON section.sec_id=tasks_details.task_for WHERE tasks_details.status='NOT YET STARTED' AND tasks_details.task_status!=0 AND reschedule>0 AND section.dept_id='$dept_id'");
													$row = $result->fetch_assoc();
													echo $row['for_approval_task']; ?>
											</div>
											<div>Reschedule Tasks</div>
								</a>
								</div>
								</div>
							</div>
							<div class="panel-footer">
								<a href="task_approval.php?status=RESCHEDULE"><span class="pull-left">View Details</span></a>
								<span class="pull-right"><a  href="task_approval.php?status=RESCHEDULE"><i class="fa fa-arrow-circle-right"></a></i></span>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div>

				<div class="zoom">
					<div class="col-lg-3 col-md-6">
						<div class="panel panel-red">
							<div class="panel-heading">
								<a href="pending_for_approval.php">
									<div class="row">
										<div class="col-xs-3">
											<i class="fa fa-exclamation-triangle fa-5x"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="huge">
												<?php
													$result = mysqli_query($con,"SELECT COUNT(tasks_details.id) as for_approval_task FROM tasks_details JOIN section ON section.sec_id=tasks_details.task_for WHERE tasks_details.status='FINISHED' AND tasks_details.task_status=1 AND tasks_details.approval_status = 1 AND section.dept_id='$dept_id'");
													$row = $result->fetch_assoc();
													echo $row['for_approval_task']; ?>
											</div>
											<div>Tasks Verification</div>
								</a>
								</div>
								</div>
							</div>
							<div class="panel-footer">
								<a href="pending_for_approval.php"><span class="pull-left">View Details</span></a>
								<span class="pull-right"><a  href="pending_for_approval.php"><i class="fa fa-arrow-circle-right"></a></i></span>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div>

				<div class="zoom">
					<div class="col-lg-3 col-md-6">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<a href="tasks.php?status=NOT YET STARTED">
									<div class="row">
										<div class="col-xs-3">
											<i class="fa fa-clock-o fa-5x"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="huge">
												<?php
													$result = mysqli_query($con,"SELECT COUNT(tasks_details.id) as not_yet_started_task FROM tasks_details JOIN section ON section.sec_id=tasks_details.task_for WHERE tasks_details.status='NOT YET STARTED' AND tasks_details.task_status=1 AND tasks_details.reschedule=0 AND section.dept_id='$dept_id'");
													$row = $result->fetch_assoc();
													echo $row['not_yet_started_task']; ?>
											</div>
											<div>To-do Tasks</div>
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
											<i class="fas fa-hourglass-start fa-5x"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="huge">
												<?php
													$result = mysqli_query($con,"SELECT COUNT(tasks_details.id) as ongoing_task FROM tasks_details JOIN section ON section.sec_id=tasks_details.task_for WHERE tasks_details.status='IN PROGRESS' AND tasks_details.task_status=1 AND section.dept_id='$dept_id'");
													$row = $result->fetch_assoc();
													echo $row['ongoing_task']; ?>
											</div>
											<div>In-Progress Tasks</div>
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
											<i class="fa fa-check-square fa-5x"></i>
										</div>
										<div class="col-xs-9 text-right">
											<div class="huge">
												<?php
													$result = mysqli_query($con,"SELECT COUNT(tasks_details.id) as finished_task FROM tasks_details JOIN section ON section.sec_id=tasks_details.task_for WHERE tasks_details.status='FINISHED' AND tasks_details.task_status=1 AND tasks_details.approval_status=0 AND section.dept_id='$dept_id'");
													$row = $result->fetch_assoc();
													echo $row['finished_task']; ?>
											</div>
											<div>Complete Tasks</div>
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
				<!-- <canvas id="myChart" style="border:solid 5px #fff;border-radius:10px;background-color:#fff"></canvas> -->
			</div>
		</div>
	</div>
	<?php
		$result = mysqli_query($con,"SELECT(SELECT COUNT(id) AS total_task FROM tasks_details WHERE tasks_details.status != '' AND MONTH(tasks_details.due_date)='$month' AND YEAR(tasks_details.due_date)='$year' AND tasks_details.reschedule != 1) AS total_task, (SELECT COUNT(id) as finished_task FROM tasks_details WHERE status='FINISHED' AND task_status=1 AND approval_status=0 AND achievement >= 1 AND MONTH(tasks_details.due_date)='$month' AND YEAR(tasks_details.due_date)='$year') AS finished_task, (SELECT COUNT(id) AS failed_task FROM tasks_details WHERE tasks_details.status = 'FINISHED' AND tasks_details.reschedule != 1 AND tasks_details.achievement=0 AND MONTH(tasks_details.due_date)='$month' AND YEAR(tasks_details.due_date)='$year') AS failed_task");
		$row = $result->fetch_assoc();
		$value1 = $row['total_task'];
		$value2 = $row['finished_task'];
		$value3 = $row['failed_task'];
	?>
	<script>
		// Define the data for the chart
		const xValues = ["April", "May", "June", "July"]; // Months
		const yValues1 = [<?php echo $value1?>, 0, 0, 0]; // Total tasks
		const yValues2 = [<?php echo $value2?>, 0, 0, 0]; // Finished tasks
		const yValues3 = [<?php echo $value3?>, 0, 0, 0]; // Failed tasks
		const barColors1 = "blue"; // Color for finished tasks
		const barColors2 = "green"; // Color for failed tasks
		const barColors3 = "red"; // Color for failed tasks
		
		// Create the chart using Chart.js
		new Chart("myChart", {
		  type: "bar",
		  data: {
		    labels: xValues,
		    datasets: [
		      {
		        label: "Total Tasks",
		        backgroundColor: barColors1,
		        data: yValues1
		      },
		      {
		        label: "Finished Tasks",
		        backgroundColor: barColors2,
		        data: yValues2
		      },
		      {
		        label: "Failed Tasks",
		        backgroundColor: barColors3,
		        data: yValues3
		      }
		      
		    ]
		  },
		  options: {
		    legend: {display: true},
		    title: {
		      display: true,
		      text: "Employee's Task Report by Month of <?php echo $year?>"
		    }
		  }
		});
		
		
	</script>
</html>