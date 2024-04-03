<?php
include('../include/link.php');
include('../include/connect.php');
$ID = $_POST['id'];
$con->next_result(); 
$sql = "UPDATE tasks_details SET status='IN PROGRESS' WHERE task_code = '$ID'";
$result = mysqli_query($con, $sql) or die('Error querying database.');
?>