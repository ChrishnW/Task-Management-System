<?php 
	include('connect.php');
	include('auth.php');
	include('../include/link.php');
	date_default_timezone_set('Asia/Manila');
	$systemtime = date('Y-m-d H:i:s');
	
	$number = rand(1000, 9999);
	$targetDir = "../assets/img/user-profiles/";
	$fileName = basename($_FILES["file"]["name"]);
	$extension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
	$fileName = $username . "_" . $number . "." . $extension;
	$targetFilePath = $targetDir . $fileName;
	$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
	
	if(isset($_POST["submit"])) {
		$allowTypes = array('jpg','png','jpeg');
		if(in_array($fileType, $allowTypes)){
			$check = getimagesize($_FILES["file"]["tmp_name"]);
			if ($check !== false) {
				if ($_FILES["file"]["size"] <= 1e+6) {
					if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){ 
						// Get the current file name of the user from the database
						$select = "SELECT file_name FROM accounts WHERE username = '$username'";
						$select_result = mysqli_query($con, $select);
						$row = mysqli_fetch_assoc($select_result);
						$oldFileName = $row['file_name'];
						// Insert new image file name into database 
						$insert = "UPDATE accounts SET file_name = '$fileName' WHERE username = '$username'";
						$insert_result = mysqli_query($con, $insert);
						if($insert) {
							// Delete old file from directory if it exists
							if($oldFileName != "" && file_exists($targetDir . $oldFileName)){
								unlink($targetDir . $oldFileName);
							}
							$con->next_result();
							$systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Profile image changed.', '$systemtime', '$username')";
							$result = mysqli_query($con, $systemlog);
							echo "<script type='text/javascript'>   $(document).ready(function(){ $('#success').modal('show');   });</script>";
						}
						else { 
							echo "<script type='text/javascript'>   $(document).ready(function(){ $('#error2').modal('show');   });</script>"; 
						}
					}
				}
				else {
					echo "<script type='text/javascript'>   $(document).ready(function(){ $('#error2').modal('show');   });</script>";
				}
			}
			else {
				echo "<script type='text/javascript'>   $(document).ready(function(){ $('#error').modal('show');   });</script>";
			}
		}
		else {
			echo "<script type='text/javascript'>   $(document).ready(function(){ $('#invalid').modal('show');   });</script>";
		}
	}
?>
<div class="modal fade" id="success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content panel-success">
			<div class="modal-header panel-heading">
				<a href="user_profile.php"><button type="button" class="close"
					aria-hidden="true">&times;</button></a>
				<h4 class="modal-title" id="myModalLabel">Success!</h4>
			</div>
			<div class="modal-body panel-body">
				<center>
					<i style="color:#23db16; font-size:80px;" class="fa fa-check-circle "></i>
					<br><br>
					The file has been uploaded successfully.
				</center>
			</div>
			<div class="modal-footer">
				<a href="user_profile.php"><button type="button" name="submit" class="btn btn-success pull-right">Return</button></a>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<div class="modal fade" id="invalid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content panel-success">
			<div class="modal-header panel-heading">
				<a href="user_profile.php"><button type="button" class="close" aria-hidden="true">&times;</button></a>
				<h4 class="modal-title" id="myModalLabel">Warning!</h4>
			</div>
			<div class="modal-body panel-body">
				<center>
					<i style="color:#e13232; font-size:80px;" class="fa fa-times-circle "></i>
					<br><br>
					Invalid File!
					<br>
					Only JPG, JPEG, PNG files are allowed to be uploaded.
				</center>
			</div>
			<div class="modal-footer">
				<a href="user_profile.php"><button type="button" name="submit" class="btn btn-danger pull-right">Return</button></a>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<div class="modal fade" id="error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content panel-success">
			<div class="modal-header panel-heading">
				<a href="user_profile.php"><button type="button" class="close"
					aria-hidden="true">&times;</button></a>
				<h4 class="modal-title" id="myModalLabel">Error!</h4>
			</div>
			<div class="modal-body panel-body">
				<center>
					<i style="color:#e13232; font-size:80px;" class="fa fa-times-circle "></i>
					<br><br>
					There was an error uploading your file.
					<br>
					Please try again.
				</center>
			</div>
			<div class="modal-footer">
				<a href="user_profile.php"><button type="button" name="submit" class="btn btn-danger pull-right">Return</button></a>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<div class="modal fade" id="error2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content panel-success">
			<div class="modal-header panel-heading">
				<a href="user_profile.php"><button type="button" class="close"
					aria-hidden="true">&times;</button></a>
				<h4 class="modal-title" id="myModalLabel">Error!</h4>
			</div>
			<div class="modal-body panel-body">
				<center>
					<i style="color:#e13232; font-size:80px;" class="fa fa-times-circle "></i>
					<br><br>
					Image size is too large.
					<br>
					Please try to upload images that are 1 MB in size or below.
				</center>
			</div>
			<div class="modal-footer">
				<a href="user_profile.php"><button type="button" name="submit" class="btn btn-danger pull-right">Return</button></a>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>