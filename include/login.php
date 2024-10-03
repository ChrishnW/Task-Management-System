<?php
session_start();
include('connect.php');

$userin = strtoupper($_POST['username']);
$passin	=	$_POST['password'];

$accountDetails = mysqli_query($con, "SELECT * FROM accounts WHERE username='$userin'");
if (mysqli_num_rows($accountDetails) > 0) {
	$row = mysqli_fetch_assoc($accountDetails);
	$access		=	$row['access'];
	$username	= $row['username'];
	$password =	$row['password'];
	if (password_verify($passin, $password)) {
		session_regenerate_id();
		$_SESSION['SESS_MEMBER_ACCESS'] 	= $access;
		$_SESSION['SESS_MEMBER_USERNAME'] = $username;
		$_SESSION['SESS_MEMBER_PASS'] 		= $password;
		session_write_close();
		die('Success');
	} else {
		die('The password you entered is incorrect. Please try again.');
	}
} else {
	echo "Wala";
}
