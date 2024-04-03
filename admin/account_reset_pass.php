<?php 
  include ("../include/connect.php");
  include('../include/link.php');
  include('../include/auth.php');

  extract($_POST);
  $new_pass = 12345;
  $hash_new_pass = password_hash($new_pass, PASSWORD_DEFAULT);
  $iid=$con->real_escape_string($id);
  $sql=$con->query("UPDATE accounts SET password='$hash_new_pass' WHERE username='$id'");
  
  $con->next_result();
  $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('$id password resets.', '$systemtime', 'ADMIN')";
  $result = mysqli_query($con, $systemlog);
  echo 1;
?>