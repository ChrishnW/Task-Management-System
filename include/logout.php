<?php
	include('auth.php');
	if(session_destroy()){
		log_action("Account Logout.");
		header("location: ../index.php");
	}
?>