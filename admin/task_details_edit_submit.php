<?php
include('../include/link.php');
include('../include/connect.php');
$today = date('Y-m-d');
$due_date = $_POST['due_date'];
$task_status = $_POST['task_status'];
$id= $_POST["id"];
$section= $_POST["section"];

$con->next_result(); 
$query = "UPDATE tasks_details SET due_date = '$due_date', task_status = '$task_status' WHERE id = '$id'";
$result = mysqli_query($con, $query) or die('Error querying database.'); 
header('location: task_details.php?section='.$section.'');

?>