<?php
	//Start session
	session_start();
	//Connect to mysql server
	include('connect.php');
	date_default_timezone_set('Asia/Manila');
  $systemtime = date('Y-m-d H:i:s');

	$error='';
	if(isset($_POST['submit'])){
		$username = $_POST['username'];
		$password = $_POST['password'];
		$hash = password_hash($password, PASSWORD_DEFAULT);
		$con->next_result();
		$result = mysqli_query($con,"SELECT * FROM accounts WHERE username='$username' AND status=1");
		if(mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			$access = $row['access'];
			$emp_id = $row['id'];
			$username = $row['username'];
			$hash_password = $row['password'];

			if (!password_verify($password, $hash_password)){
				$error="Invalid Password!";
			}
			else{	
				//Login Successful
				session_regenerate_id();
				$_SESSION['SESS_MEMBER_ID'] = $emp_id;
				$_SESSION['SESS_MEMBER_USERNAME'] = $username;
				$_SESSION['SESS_MEMBER_ACCESS'] = $access;
				$_SESSION['SESS_MEMBER_PASS'] = $hash_password;
				session_write_close();
				
				$systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Account login.', '$systemtime', '$username')";
				$result = mysqli_query($con, $systemlog);
				header("location: include/home.php");
				exit();
			}
		}
		else {
			//Login failed
			$error="There's an error accessing your account. Contact system admin now!";
		}
	}
?>