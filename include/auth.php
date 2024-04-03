<?php
	//Start session
	session_start();

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
?>
