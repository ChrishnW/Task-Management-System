<?php
include('../include/link.php');
include('../include/auth.php');
include('../include/connect.php');

    $id = $_POST['id'];
    $today = date('Y-m-d');
      $con->next_result(); 
      $sql = "UPDATE tasks_details SET reschedule=0, date_accomplished='$today', status='FINISHED', remarks='Failed to perform task' WHERE task_code ='$id'";
      $result = mysqli_query($con, $sql); 
?>