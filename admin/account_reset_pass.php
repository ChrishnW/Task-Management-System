<?php 
include ("../include/connect.php");
extract($_POST);
$iid=$con->real_escape_string($id);
$sql=$con->query("UPDATE accounts SET password='12345' WHERE id='$id'");
echo 1;
?>