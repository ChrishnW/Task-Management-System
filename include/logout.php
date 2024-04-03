<?php
	include('auth.php');
	if(session_destroy()){
		include('connect.php');
		date_default_timezone_set('Asia/Manila');
		$systemtime = date('Y-m-d H:i:s');
		$systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Account logout.', '$systemtime', '$username')";
		$result = mysqli_query($con, $systemlog);
		header("location: ../index.php");
	}
?>