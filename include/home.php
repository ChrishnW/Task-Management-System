<?php
	include('connect.php');
	include('auth.php'); 
?>
<!DOCTYPE html>
<html lang="en">
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
		<link href="../vendor/font-awesome/css/fontawesome.min.css" rel="stylesheet" type="text/css">
		<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet" type="text/css">
		<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet" type="text/css">
		<link href="../assets/css/select2.min.css" rel="stylesheet">
		<link href="../assets/css/bootstrap-select.min.css" rel="stylesheet">
		<link href="../assets/css/jquery-ui.min.css" rel="stylesheet">
		<link href="../assets/css/dataTables.bootstrap.css" rel="stylesheet">
		<link href="../assets/css/bootstrap-toggle.min.css" rel="stylesheet">
		<link rel="stylesheet" href="../assets/css/bootstrap-select.css">
		<link href="../assets/css/darkmode.css" rel="stylesheet">
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
			.well {
        background-image: url('../assets/img/system.jpg');
        background-size: cover;
			}
		</style>
		<script src="../vendor/jquery/jquery-1.9.1.min.js"></script>
		<script>
			$(window).on('load', function() {
			  $('#loader').fadeOut('slow');
			});
		</script>
	</head>
	<body style="height: 100vh;">
		<div id="loader"></div>
		<div id="wrapper">
			<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="">
						<p class="text-primary">GLORY (PHILIPPINES), INC. | <font color="red">GLORY TASK MANAGEMENT SYSTEM</font></p>
					</a>
				</div>
			</nav>
			<div id="page-wrapper">
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header"> </h1>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="well">
							<div class="row">
								<div class="col-lg-7">
									<?php
										$con->next_result();
										// $result = mysqli_query($con,"Call GetEmployeeSystemAccess('$emp_id')"); 
										$result = mysqli_query($con,"SELECT * FROM accounts INNER JOIN access ON access.id=accounts.access WHERE username = '$username'");
										while($row = mysqli_fetch_array($result)){
                  ?>
									<center>
										<div>
											<a href="<?php echo $row['link'];  ?>">
												<div class="col-lg-4">
													<div class="panel panel-default">
														<div class="panel-body" style="border-radius: 3px;">
															<i class="fas fa-server	fa-5x"></i></br>
															<font color="#fffff"><b><?php echo $row['system_name']; ?></b> </font>
														</div>
													</div>
												</div>
											</a>
										</div>
									</center>
									<?php
										} 
                  ?>
								</div>
								<?php
									$con->next_result();
									$result = mysqli_query($con,"SELECT accounts.username, accounts.card, accounts.fname, accounts.lname, section.sec_name, accounts.email, access.access, department.dept_name FROM accounts JOIN section ON section.sec_id=accounts.sec_id JOIN access ON access.id=accounts.access JOIN department ON department.dept_id=section.dept_id WHERE username = '$username'");
									while($row = mysqli_fetch_array($result)){ 
                ?>
								<div class="col-lg-5 pull-right">
									<div class="col-lg-12 ">
										<div class="row">
											<div class="panel panel-primary">
												<div class="panel-heading">
													<b>Account Information</b> <a href="../include/logout.php" class="pull-right"><i class="glyphicon glyphicon-log-out"></i> Logout</a>
												</div>
												<div class="panel-body">
													<div class='form-group'>
														<div class="col-xs-12">
															<label>Name:</label>
    													<input class="form-control input-sm" type="text" value="<?php echo $row['fname']; ?> <?php echo $row['lname']; ?>"><br>
														</div>
														<div class="col-xs-6">
															<label>Username:</label>
    													<input class="form-control input-sm" type="text" value="<?php echo $row['username']; ?>"><br>
														</div>
														<div class="col-xs-6">
															<label>ID:</label>
    													<input class="form-control input-sm" type="text" value="<?php echo $row['card'] ?>"><br>
														</div>
														<div class="col-xs-12">
															<label>Email:</label>
    													<input class="form-control input-sm" type="text" value="<?php echo $row['email']; ?>"><br>
														</div>
														
														<div class="col-xs-12">
															<label>Section:</label>
    													<input class="form-control input-sm" type="text" value="<?php echo $row['sec_name']; ?>"><br>
														</div>
														<div class="col-xs-12">
															<label>Department:</label>
    													<input class="form-control input-sm" type="text" value="<?php echo strtoupper($row['dept_name']); ?>"><br>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php }  $con-> close(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="errormodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content panel-danger">
					<div class="modal-header panel-heading">
						<button type="button" class="close"  aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Warning!</h4>
					</div>
					<div class="modal-body panel-body">
						<center>
							<i style="color:#e13232; font-size:80px;" class="fa fa-exclamation-circle "></i>
							<br><br>
							You are not authorized to access this page. Please Login your account!
						</center>
					</div>
					<div class="modal-footer">
						<a href="logout.php"><button type="submit" name="submit" class="btn btn-danger pull-right">Okay</button></a>
					</div>
				</div>
			</div>
		</div>
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
		<script src="../assets/js/slideshow.js"></script>
		<script src="../assets/js/bootstrap-toggle.min.js"></script>
		<!-- For select multiple tag-->
		<script src="../assets/js/bootstrap-select.js"></script> 
		<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="../assets/js/select2.min.js"></script>