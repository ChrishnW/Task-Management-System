<?php
  $db_host		  = 'localhost';
  $db_user		  = 'gtms';
  $db_database	= 'gtms';
  $db_pass		  = 'p@55w0rd$$$';

  $con = mysqli_connect($db_host,$db_user,$db_pass,$db_database) or die('Unable to establish a DB connection');
?>