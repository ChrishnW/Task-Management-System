<!DOCTYPE html>
<html lang="en">
<?php
	include('../include/connect.php');
	include('../include/auth.php');
	date_default_timezone_set('Asia/Manila');
  $systemtime = date('Y-m-d H:i:s');
	
	if($access!='1'){
		$con->next_result();
		$systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Tries to access admin account.', '$systemtime', '$username')";
		$result = mysqli_query($con, $systemlog);
		echo '<script>alert("Error 404")</script>'; 
		echo '<script>history.back();</script>';
		exit();
	}
?>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>G-TMS</title>
		<link rel="shortcut icon" href="../assets/img/gloryicon.png">
		<!-- Bootstrap Core CSS -->
		<link href="../vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
		<!-- MetisMenu CSS -->
		<link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
		<!-- Custom CSS -->
		<link href="../assets/css/sb-admin-2.css" rel="stylesheet">
		<!-- Morris Charts CSS -->
		<link href="../vendor/morrisjs/morris.css" rel="stylesheet">
		<!-- Custom Fonts -->
		<link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link href="../assets/css/select2.min.css" rel="stylesheet">
		<link href="../assets/css/jquery-ui.min.css" rel="stylesheet">
		<link href="../assets/css/dataTables.bootstrap.css" rel="stylesheet">
		<link rel="stylesheet" href="../assets/css/bootstrap-select.css">
		<style>
			#loader {
			position: fixed;
			left: 0px;
			top: 0px;
			width: 100%;
			height: 100%;
			z-index: 9999;
			background: url('../assets/img/loader.gif') 50% 50% no-repeat rgb(0, 0, 0);
			}
		</style>
		<?php
			$con->next_result();
			$query = mysqli_query($con, "SELECT * FROM accounts INNER JOIN  section ON section.sec_id=accounts.sec_id WHERE username='$username' ");
			if (mysqli_num_rows($query)>0) { 
			    while ($row = $query->fetch_assoc()) {
				$fname = $row['fname'];
				$card = $row['card'];
				$email = $row['email'];
				$sec = $row['sec_name'];
					// Check if file_name is empty
					if (empty($row["file_name"])) {
						// Use a default image URL
						$imageURL = '../assets/img/user-profiles/nologo.png';
					} else {
						// Use the image URL from the database
						$imageURL = '../assets/img/user-profiles/'.$row["file_name"];
					}
			    }
			}
		?>
	</head>
	<script src="../vendor/jquery/jquery-1.9.1.min.js"></script>
	<script>
		$(window).on('load', function() {
		$('#loader').fadeOut('slow');
		});
	</script>
	<div id="loader"></div>
	<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0; margin-top: -5px;">
		<div class="navbar-header">
			<button type="button" id="sidebarCollapse" class="pull-left" style="border: none">
			<i class="fa fa-bars" style="font-size: 30px;"></i>
			<span class="sr-only">Toggle Menu</span>
			</button>
			<a class="navbar-brand" href="index.php">
				<p class="text-primary" title="Task Management System">GLORY (PHILIPPINES), INC. | <font color="red"> TASK MANAGEMENT SYSTEM</font></p>
			</a>
		</div>
	</nav>
	<div class="wrapper d-flex align-items-stretch">
	<nav id="sidebar">
		<div class="p-4 pt-5">
			<div class="prof" style="font-family: 'Trebuchet MS', sans-serif;margin-top: 50px;text-align: center;">
				<div class="logo">
					<img src="<?php echo $imageURL; ?>" class="avatar img-circle img-thumbnail" alt="">
				</div>
				<h5 style="text-align: center; cursor: default"><?php echo $fname?></h5>
				<h6 style="text-align: center; cursor: default"><?php echo $sec?></h6>
			</div>
			<ul class="nav" id="side-menu">
				<li>
					<a href="../include/home.php"><i class="fa fa-home fa-fw"></i> Home</a>
				</li>
				<li>
					<a href="index.php"><i class="glyphicon glyphicon-th-large fa-fw"></i> Dashboard</a>
				</li>
				<li>
					<a href="system_logs.php"><i class="fa fa-newspaper-o fa-fw"></i> System Logs</a>
				</li>
				<li>
					<a href="#"><i class="fa fa-user fa-fw"></i> User Management<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
						<li>
							<a href="account_list.php"><i class="fa fa-users fa-fw"></i> Accounts</a>
						</li>
						<li>
							<a href="department_list.php"><i class="fa fa-sitemap fa-fw"></i> Department</a>
						</li>
						<li>
							<a href="section_list.php"><i class="fa fa-sitemap fa-fw"></i> Sections</a>
						</li>
						<li>
							<a href="attendancelog.php"><i class="fa fa-check-circle fa-fw"></i> Attendance</a>
						</li>
						<li>
							<a href="dayoff.php" ><i class="fa fa-calendar fa-fw"></i> Day off</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="#"><i class="fa fa-tasks fa-fw"></i> Task Management<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
						<li>
							<a href="task_import.php"><i class="fa fa-upload fa-fw"></i> Import Tasks</a>
						</li>
						<li>
							<a href="section_tasks_list.php"><i class="fa fa-table fa-fw"></i> Task List</a>
						</li>
						<li>
							<a href="manage_task_list.php"><i class="fa fa-calendar-plus-o fa-fw"></i> Task Assignment</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="task_details_list.php"><i class="fa fa-archive fa-fw"></i> Task Details</a>
				</li>
				<li>
					<a href="../include/user_profile.php?modal=hide" style="margin-top: 0"><i class="fa fa-cog fa-fw"></i> Configuration</a>
				</li>
				<li>
					<a href="../include/logout.php"><i class="glyphicon glyphicon-log-out fa-fw"></i> Logout</a>
				</li>
				<!-- <li>
					<a href="#"><i class="fa fa-user fa-fw"></i> System Automation<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
						<li>
							<a href="../include/expired_tasks.php"><i class="fa fa-cog fa-fw"></i> Overdue Tasks</a>
						</li>
						<li>
							<a href="../include/tasks_daily.php"><i class="fa fa-cog fa-fw"></i> Daily Tasks</a>
						</li>
						<li>
							<a href="../include/tasks_weekly.php"><i class="fa fa-cog fa-fw"></i> Weekly Tasks</a>
						</li>
						<li>
							<a href="../include/tasks_monthly.php"><i class="fa fa-cog fa-fw"></i> Monthly Tasks</a>
						</li>
					</ul>
				</li> -->
			</ul>
			<div class="footer">
				<p>
					System Kaizen &copy;<script>document.write(new Date().getFullYear());</script> <br></a>
				</p>
			</div>
		</div>
	</nav>

	<script src="../assets/js/jquery-3.3.1.min.js"></script>
	<script src="../assets/js/popper.min.js"></script>
	<script src="../assets/js/bootstrap.min.js"></script>
	<script src="../assets/js/main.js"></script>
	<script src="../vendor/jquery/jquery.min.js"></script>
	<!-- Autocomplete Jquery-->
	<script src="../vendor/jquery/jquery-ui.min.js"></script>
	<!-- Flot Charts JavaScript -->
	<script src="../vendor/flot/excanvas.min.js"></script>
	<script src="../vendor/flot/jquery.flot.js"></script>
	<script src="../vendor/flot/jquery.flot.pie.js"></script>
	<script src="../vendor/flot/jquery.flot.resize.js"></script>
	<script src="../vendor/flot/jquery.flot.time.js"></script>
	<script src="../vendor/flot-tooltip/jquery.flot.tooltip.min.js"></script>
	<script src="../data/flot-data.js"></script>
	<!-- Metis Menu Plugin Javascript -->
	<script src="../vendor/metisMenu/metisMenu.min.js"></script>
	<script src="../assets/js/validator.js"></script>
	<!-- DataTables Javascript -->
	<script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
	<script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
	<!-- Morris Charts Javascript -->
	<script src="../vendor/raphael/raphael.min.js"></script>
	<script src="../vendor/morrisjs/morris.min.js"></script>
	<!-- Custom Theme Javascript -->
	<script src="../assets/js/sb-admin-2.js"></script>
	<!-- For select multiple tag-->
	<script src="../assets/js/bootstrap-select.js"></script> 
	<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="../assets/js/select2.min.js"></script>