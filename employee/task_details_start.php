<?php
  include('../include/auth.php');
  include('../include/connect.php');
  $ID = $_POST['id'];
  $con->next_result(); 
  $sql = "UPDATE tasks_details SET status='IN PROGRESS' WHERE task_code = '$ID'";
  $result = mysqli_query($con, $sql) or die('Error querying database.');

  if ($result){
    $con->next_result(); 
    $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Started task [$ID].', '$systemtime', '$username')";
    $result = mysqli_query($con, $systemlog);
  }
?>