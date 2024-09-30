<?php
session_start();
date_default_timezone_set('Asia/Manila');
include('connect.php');
$today		= date('Y-m-d H:i:s');
$username = $_POST['username'];
$password	=	$_POST['password'];
$hash			= password_hash($password, PASSWORD_DEFAULT);
$wpass 		= '12345';
$spass		=	'$2y$10$znE.9leHF4uFoDshO9bvUOMX2Qk5NhcbxVpqozpFymkIIYkfREDl.';
$result		= mysqli_query($con, "SELECT * FROM accounts WHERE username='$username' AND status=1");
if (mysqli_num_rows($result) > 0) {
	$row			= mysqli_fetch_array($result, MYSQLI_ASSOC);
	$access 	= $row['access'];
	$emp_id 	= $row['id'];
	$username = $row['username'];
	$hpass		= $row['password'];

	if (password_verify($password, $spass)) {
		$query_insert = mysqli_query($con, "INSERT INTO system_log (action, date_created, user) VALUES ('$username has been logged in using the administrator password.', '$today', 'ADMIN')");
		session_regenerate_id();
		$_SESSION['SESS_MEMBER_ID']				= $emp_id;
		$_SESSION['SESS_MEMBER_USERNAME'] = $username;
		$_SESSION['SESS_MEMBER_ACCESS'] 	= $access;
		$_SESSION['SESS_MEMBER_PASS'] 		= $hpass;
		session_write_close();
		die('Success');
	} elseif (!password_verify($password, $hpass)) {
		die('Incorrect');
	} else {
		$query_insert = mysqli_query($con, "INSERT INTO system_log (action, date_created, user) VALUES ('Account Login.', '$today', '$username')");
		session_regenerate_id();
		$_SESSION['SESS_MEMBER_ID'] = $emp_id;
		$_SESSION['SESS_MEMBER_USERNAME'] = $username;
		$_SESSION['SESS_MEMBER_ACCESS'] = $access;
		$_SESSION['SESS_MEMBER_PASS'] = $hpass;
		session_write_close();
		if (password_verify($wpass, $hpass)) {
			$con->next_result();
			$check_request	= mysqli_query($con, "SELECT * FROM notification WHERE user='$username' AND icon='fas fa-user-secret'");
			$check_get_rows	=	mysqli_fetch_assoc($check_request);
			$check_rows			=	mysqli_num_rows($check_request);
			$notif_id				=	$check_get_rows['id'];
			if ($check_rows > 0) {
				$query_delete = mysqli_query($con, "DELETE FROM `notification` WHERE id='{$check_get_rows['id']}'");
				$action = mysqli_real_escape_string($con, "$('#profileModal').modal('show');");
				$query_insert = mysqli_query($con, "INSERT INTO `notification` (`user`, `icon`, `type`, `body`, `action`, `date_created`, `status`) VALUES ('$username', 'fas fa-user-secret', 'danger', 'Your password is still using the default. Please change it to enhance account security.', '$action', '$today', '1')");
			} else {
				$action = mysqli_real_escape_string($con, "$('#profileModal').modal('show');");
				$query_insert = mysqli_query($con, "INSERT INTO `notification` (`user`, `icon`, `type`, `body`, `action`, `date_created`, `status`) VALUES ('$username', 'fas fa-user-secret', 'danger', 'Your password is still using the default. Please change it to enhance account security.', '$action', '$today', '1')");
			}
			die('Success');
		} else {
			die('Success');
		}
		exit();
	}
} else {
	die("There's an error accessing your account. Contact system admin now!");
}
