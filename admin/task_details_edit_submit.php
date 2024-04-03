<?php
include('../include/auth.php');
include('../include/link.php');
include('../include/connect.php');
$due_date = $_POST['due_date'];
$task_code= $_POST["id"];
$section= $_POST["section"];
$task_status = $_POST['task_status'];
$requirement_status = $_POST["requirement_status"];

if ($task_status == 0){
    $con->next_result(); 
    $query = "UPDATE tasks_details SET task_status = '$task_status' WHERE task_code = '$task_code'";
    $result = mysqli_query($con, $query) or die('Eror querying database.'); 

    $con->next_result();
    $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Updates task $task_code', '$systemtime', '$username')";
    $result = mysqli_query($con, $systemlog);
    header('location: task_details.php?section='.$section.'');
}
else {
    $con->next_result(); 
    $query = "UPDATE tasks_details SET due_date = '$due_date', task_status = '$task_status', requirement_status = '$requirement_status' WHERE task_code = '$task_code'";
    $result = mysqli_query($con, $query) or die('Error querying database.'); 
    
    $con->next_result();
    $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Updates task $task_code', '$systemtime', 'ADMIN')";
    $result = mysqli_query($con, $systemlog);
    header('location: task_details.php?section='.$section.'');
}
?>