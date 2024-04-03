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
			.avatar{
			width:200px;
			height:200px;
			margin: auto;
			object-fit: cover;
			}
		</style>
	</head>
	<nav class="navbar navbar-default navbar-static-top" role="navigation" >
		<div class="navbar-header">
			<a class="navbar-brand" href="">
				<p class="text-primary"><img src="../assets/img/gloryicon.png"> GLORY (PHILIPPINES), INC. | <font color="red">GLORY TASK MANAGEMENT SYSTEM</font></p>
			</a>
		</div>
	</nav>
	<?php
		$con->next_result();
		$query = mysqli_query($con, "SELECT * FROM accounts WHERE username='$username'");
		if (mysqli_num_rows($query)>0) { 
		    while ($row = $query->fetch_assoc()) {
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
	<div class="container bootstrap snippets bootdey" style="height: 100vh;">
		<h1 class="text-primary">Edit Profile</h1>
		<hr>
		<div class="row">
			<!-- left column -->
			<div class="col-md-3">
				<div class="text-center">
					<form action="upload_profile.php" method="post" enctype="multipart/form-data">
						<img src="<?php echo $imageURL; ?>" class="avatar img-circle img-thumbnail" alt="avatar" style="">
						<a href="#" onclick="removeimage()">
							<h6>Remove</h6>
						</a>
						<input type="file" class="form-control pull-left" name="file" style="margin-bottom: 5px;" accept="image/png, image/jpeg, image/jpg" required>
						<input type="submit" class="form-control" value="Upload" name="submit" style="margin: auto;">
					</form>
				</div>
			</div>
			<!-- edit form column -->
			<div class="col-md-9 personal-info">
				<form class="form-horizontal" role="form">
					<?php
						$con->next_result();
						$result = mysqli_query($con,"SELECT accounts.username, accounts.fname, accounts.lname, section.sec_name,section.sec_id, accounts.email, access.access, accounts.access, accounts.card AS access_code FROM accounts INNER JOIN section on section.sec_id=accounts.sec_id INNER JOIN access ON access.id=accounts.access WHERE username = '$username'");
						while($row = mysqli_fetch_array($result)){ 
						    $sec_id = $row['sec_id'];
						    $access_code = $row['access_code']; 
						?>
					<div class="form-group">
						<label class="col-lg-3 control-label">Username:</label>
						<div class="col-lg-8">
							<input class="form-control" type="text" value="<?php echo $row['username'];?>" disabled>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">First name:</label>
						<div class="col-lg-8">
							<input class="form-control" type="text" value="<?php echo $row['fname']; ?>" disabled>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Last name:</label>
						<div class="col-lg-8">
							<input class="form-control" type="text" value="<?php echo $row['lname']; ?>" disabled>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Section:</label>
						<div class="col-lg-8">
							<input class="form-control" type="text" value="<?php echo $row['sec_name']; ?>" disabled>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Email:</label>
						<div class="col-lg-8">
							<input class="form-control" type="text" value="<?php echo $row['email']; ?>" disabled>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">ID Number:</label>
						<div class="col-lg-8">
							<input class="form-control" type="text" value="<?php echo $row['access_code']; ?>" disabled>
						</div>
					</div>
					<?php }  $con-> close(); ?>
				</form>
				<a href="home.php"><button type="button" class="btn btn-danger pull-right" style="margin-right: 75px"><i class="fa fa-arrow-left"></i> Back</button></a>
				<button type="button" id="submit-btn" class="btn btn-primary pull-right" style="margin-right: 5px" data-toggle="modal" data-target="#success">Change Password</button>
			</div>
		</div>
		<hr>
	</div>
	<div class="modal fade" id="success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content panel-success">
				<div class="modal-header panel-heading">
					<a href="user_profile.php"><button type="button" class="close"
						aria-hidden="true">&times;</button></a>
					<h4 class="modal-title" id="myModalLabel">Change Password</h4>
				</div>
				<div class="modal-body panel-body">
					<center>
						<form data-toggle="validator" action="change_password_submit.php" enctype="multipart/form-data" method="post">
							<div class="col-lg-12">
								<div class="form-group">
									<label>Old Password:</label><span class="pull-right help-block with-errors" id="divCheckOldPassword" style="margin: 0px; font-size: 11px;"></span>
									<input type="password" data-toggle="password" data-placement="before" placeholder="Enter Old Password" class="form-control" name="old_pass" id="old_pass"  required>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label>New Password:</label><span class="pull-right help-block with-errors" style="margin: 0px; font-size: 11px;"></span>
									<input type="password" data-toggle="password" data-placement="before" placeholder="Enter New Password" class="form-control"  name="new_pass"  id="new_pass" pattern="[a-zA-Z0-9-/]+" data-error="Special character not allowed." maxlength="20" required>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label>Re-type Password:</label><span class="pull-right help-block with-errors" id="divCheckPasswordMatch" style="margin: 0px; font-size: 11px;"></span>
									<input type="password"  data-toggle="password" data-placement="before" placeholder="Re-type New Password" class="form-control" name="retype_pass" id="retype_pass"  pattern="[a-zA-Z0-9-/]+" data-error="Special character not allowed."  maxlength="20" onChange="checkPasswordMatch();" required>
								</div>
							</div>
							<div class="modal fade" id="submitModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog modal-sm">
									<div class="modal-content panel-info">
										<div class="modal-header panel-heading" style="background-color: rgb(27, 29, 30); border-color: transparent">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title" id="myModalLabel">Confirmation</h4>
										</div>
										<div class="modal-body panel-body">
											<center>
												<i style="color:#3581C1; font-size:80px;" class="fa fa-question-circle  "></i>
												<br><br>
												Are you sure you want to change password?
											</center>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="fa fa-times"> </span> No</button>
											<button type="submit" name="submit" class="btn btn-success pull-right"><span class="fa fa-check"> </span> Yes</button>
										</div>
									</div>
									<!-- /.modal-content -->
								</div>
								<!-- /.modal-dialog -->
							</div>
						</form>
					</center>
				</div>
				<div class="modal-footer">
        <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#submitModal"><span class="fa fa-check"></span> Submit</button>
					<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"style="margin-right: 5px"><span class="fa fa-times"> </span> Cancel</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<script>
		function removeimage() {
		  // send a GET request to the PHP file
		  fetch("removeimage.php")
		      .then(response => response.text()) // convert the response to text
		      .then(data => {
		      // do something with the data
		      console.log(data);
		      // refresh the page
		      window.location.reload(); // add this line
		      })
		      .catch(error => {
		      // handle any errors
		      console.error(error);
		      });
		}
	</script>
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