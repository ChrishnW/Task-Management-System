<?php
  include('connect.php');
	include('auth.php');
  date_default_timezone_set('Asia/Manila');
  $systemtime = date('Y-m-d H:i:s');

  // execute the query
  $targetDir = "../assets/img/user-profiles/";
  $select = "SELECT file_name FROM accounts WHERE username = '$username'";
  $select_result = mysqli_query($con, $select);
  $row = mysqli_fetch_assoc($select_result);
  $oldFileName = $row['file_name'];

  // Remove the image file name into database
  $remove = "UPDATE accounts SET file_name = '' WHERE username = '$username'";
  $remove_result = mysqli_query($con, $remove);
  if($remove){
      // Delete old file from directory if it exists
      if($oldFileName != "" && file_exists($targetDir . $oldFileName)){
          unlink($targetDir . $oldFileName);
      }
      $con->next_result();
      $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Profile image removed.', '$systemtime', '$username')";
      $result = mysqli_query($con, $systemlog);
  }
  mysqli_close($con);
?>
<!-- $remove = "UPDATE accounts SET file_name = '' WHERE username = '$username'";
$remove_result = mysqli_query($con, $remove);

mysqli_close($con); -->
