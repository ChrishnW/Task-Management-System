<?php
	include('auth.php');
	if(session_destroy()){
		log_action("User manually logged out of the system.");
		header("location: ../index.php");
	}
?>