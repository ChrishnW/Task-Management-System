<?php
//Start session
session_start();
//Connect to mysql server
include('connect.php');

$error='';
if(isset($_POST['submit'])){

	$username = $_POST['username'];
	$password = $_POST['password'];

	$result = mysqli_query($con,"SELECT * FROM accounts WHERE username='$username' AND password='$password'");
	
	if(mysqli_num_rows($result) > 0)
	{
		while($row = mysqli_fetch_array($result)){
			$access = $row['access'];
			$emp_id = $row['id'];
			$username = $row['username'];
		}

		if($result) {
			if(mysqli_num_rows($result) > 0) {
				//Login Successful
				session_regenerate_id();
				$member = mysqli_fetch_assoc($result);
				$_SESSION['SESS_MEMBER_ID'] = $emp_id;
				$_SESSION['SESS_MEMBER_USERNAME'] = $username;
				$_SESSION['SESS_MEMBER_ACCESS'] = $access;
				$_SESSION['SESS_MEMBER_PASS'] = $password;
				session_write_close();
				
				header("location: include/home.php");
				exit();
			}else {
				//Login failed
				$error="Username and Password did not match!";
				
				exit();
			}
		}else {
			die("Query failed");
		}

		if(is_null($access))
		{
			$error="Error in accessing your account. Contact System Admin now!";
		}
	}
	else{
		$error="Username and Password is invalid!";
	}
} ?>