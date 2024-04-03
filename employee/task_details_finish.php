<?php
include('../include/link.php');
include('../include/connect.php');
$ID = $_POST['id'];
$ACTION = $_POST['action'];
$today = date("Y-m-d"); 
$con->next_result(); 
$sql = "UPDATE tasks_details SET status='FINISHED', remarks='$ACTION', date_accomplished='$today' WHERE id = '$ID'";
$result = mysqli_query($con, $sql) or die('Error querying database.');
?>