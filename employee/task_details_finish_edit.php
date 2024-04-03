<?php
include('../include/link.php');
include('../include/auth.php');
include('../include/connect.php');

$ID = $_POST['id'];
$ACTION = $_POST['action'];
$FILE = $_FILES['file'];

$con->next_result();
$update = "UPDATE tasks_details SET remarks='$ACTION' WHERE task_code='$ID'";
$update = mysqli_query($con, $update);
?>