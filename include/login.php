<?php
session_start();
date_default_timezone_set('Asia/Manila');
include('connect.php');

$error = '';
if (isset($_POST['submit'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];
	$hash = password_hash($password, PASSWORD_DEFAULT);
	$weak_password = '12345';
	$datetime_current = date('Y-m-d H:i:s');
	$con->next_result();
	$result = mysqli_query($con, "SELECT * FROM accounts WHERE username='$username' AND status=1");
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		$access = $row['access'];
		$emp_id = $row['id'];
		$username = $row['username'];
		$hash_password = $row['password'];

		if (!password_verify($password, $hash_password)) {
			$error = "Invalid Password!";
		} else {
			$query_insert = mysqli_query($con, "INSERT INTO system_log (action, date_created, user) VALUES ('Account Login.', '$datetime_current', '$username')");
			session_regenerate_id();
			$_SESSION['SESS_MEMBER_ID'] = $emp_id;
			$_SESSION['SESS_MEMBER_USERNAME'] = $username;
			$_SESSION['SESS_MEMBER_ACCESS'] = $access;
			$_SESSION['SESS_MEMBER_PASS'] = $hash_password;
			session_write_close();
			if (password_verify($weak_password, $hash_password)) {
				$con->next_result();
				$check_request	= mysqli_query($con, "SELECT * FROM notification WHERE user='$username' AND icon='fas fa-key' AND status=1");
				$check_get_rows	=	mysqli_fetch_assoc($check_request);
				$check_rows			=	mysqli_num_rows($check_request);
				$notif_id				=	$check_get_rows['id'];
				if ($check_rows > 0) {
					$query_update = mysqli_query($con, "UPDATE `notification` SET `date_created`='$datetime_current' WHERE id='$notif_id'");
				} else {
					$query_insert = mysqli_query($con, "INSERT INTO `notification` (`user`, `icon`, `type`, `body`, `date_created`, `status`) VALUES ('$username', 'fas fa-key', 'danger', 'Your account still uses the default password. Please change it for security.', '$datetime_current', '1')");
				}
				header("location: include/home.php");
			} else {
				header("location: include/home.php");
			}
			exit();
		}
	} else {
		$error = "There's an error accessing your account. Contact system admin now!";
	}
}
