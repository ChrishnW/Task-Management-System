<?php
include('../include/connect.php');
extract($_POST);
$iid=$con->real_escape_string($id);
$sql=$con->query("DELETE FROM tasks WHERE id='$id'");
echo 1;    
?>