<?php
include('../include/link.php');
include('../include/auth.php');
include('../include/connect.php');

  $id = $_POST['id'];
  $date = $_POST['date'];
  $caseid = $_POST['case'];
  
  if ($caseid == 1){
    $sql = "UPDATE tasks_details SET reschedule=0, due_date='$date' WHERE task_code='$id'";
    $result = mysqli_query($con, $sql); 
  }
  elseif ($caseid == 2){
    $sql = "UPDATE tasks_details SET reschedule=0, due_date='$date', old_due=NULL WHERE task_code='$id'";
    $result = mysqli_query($con, $sql); 
  }
?>