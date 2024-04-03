<?php
	include("connect.php");
	// Start session
	session_start();

	// Check whether the session variable SESS_MEMBER_ID is present or not
	if (!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '')) {
		// header('location: ../include/logout.php');
		header('location: ../include/session_expired.php');
		exit();
	} 
	else {
		$emp_id = $_SESSION['SESS_MEMBER_ID'];
		$username = $_SESSION['SESS_MEMBER_USERNAME'];
		$access = $_SESSION['SESS_MEMBER_ACCESS'];
		$pass = $_SESSION['SESS_MEMBER_PASS'];
	}

	// Set the session timeout to 60 minutes (3600 seconds)
	$inactive = 3600;

	// Check if the session has been inactive for more than 60 minutes
	if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive)) {
		// Redirect to a logout page or any other desired location
		$systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('[$username] account session expired.', '$systemtime', 'SYSTEM')";
		$result = mysqli_query($con, $systemlog);
		header('location: ../include/session_expired.php');
		session_destroy();
	}
	// Update the last activity time
	$_SESSION['last_activity'] = time();
?>
