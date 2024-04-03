<?php
	//Start session
	session_start();

	//Set timeout period in seconds
	$inactive = 3600; // 1 hour in seconds

	if(isset($_SESSION['timeout'])){
		$session_life = time() - $_SESSION['timeout'];
		if ($session_life > $inactive) {
			session_destroy();
			echo "<script>alert('Your session has expired due to inactivity. You will be logged out.');</script>";
        	echo "<script>window.location = '../include/logout.php'</script>";
		}
	}
	//Check whether the session variable SESS_MEMBER_ID is present or not
	if(!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '')) {
		
		header('location: ../include/logout.php');
		exit();
	}
	else
	{
			
		$emp_id=$_SESSION['SESS_MEMBER_ID'];
		$username=$_SESSION['SESS_MEMBER_USERNAME'];
		$access=$_SESSION['SESS_MEMBER_ACCESS'];
		$pass=$_SESSION['SESS_MEMBER_PASS'];

	}
	$_SESSION['timeout'] = time();
?>
