<?php
	include('auth.php');
	if(session_destroy()){
		header("location: ../index.php");
	}
?>