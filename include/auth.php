<?php
include("connect.php");
session_start();

if (!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '')) {
	header('location: ../index.php');
	exit();
}
else {
	$emp_id 	= $_SESSION['SESS_MEMBER_ID'];
	$username = $_SESSION['SESS_MEMBER_USERNAME'];
	$access 	= $_SESSION['SESS_MEMBER_ACCESS'];
	$pass 		= $_SESSION['SESS_MEMBER_PASS'];

	// Query in the background of every pages
	include("query.php");
}
