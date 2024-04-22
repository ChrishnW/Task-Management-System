<?php
  include('../include/auth.php');
  include('../include/connect.php');

  $ID = $_POST['id'];
  $ACTION_TEMP = $_POST['action'];
  $ACTION = str_replace("'", "&apos;", $ACTION_TEMP);
  $FILE = $_FILES['file'];

  $con->next_result();
  $update = "UPDATE tasks_details SET remarks='$ACTION' WHERE task_code='$ID'";
  $update = mysqli_query($con, $update);

  if ($update) {
    $con->next_result(); 
    $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Edited [$ID] remarks.', '$systemtime', '$username')";
    $result = mysqli_query($con, $systemlog);
  }
?>