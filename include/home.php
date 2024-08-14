<?php
  include('auth.php');
  
  date_default_timezone_set('Asia/Manila');
  $datetime = date('Y-m-d H:i:s');

  $con->next_result();
  $query = mysqli_query($con,"SELECT * FROM accounts INNER JOIN access ON access.id=accounts.access WHERE username = '$username'");
  $result = mysqli_fetch_assoc($query);
  $emp_card = $result['card'];

  if($result){
    $systemlog = mysqli_query($con, "INSERT INTO attendance VALUES ('', '$emp_card', '$datetime')");
    header("location: ".$result['link']."");
  }
?>