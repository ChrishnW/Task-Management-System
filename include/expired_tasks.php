<?php
  include('connect.php');
  date_default_timezone_set('Asia/Manila');
  $today = date('Y-m-d H:i:s');
  $now = new DateTime();
  $yesterday = date('Y-m-d', strtotime($today . ' -' . 1 . ' days'));
  $check = 0;

  $sql = mysqli_query($con,"SELECT * FROM tasks_details WHERE status != 'FINISHED' AND DATEDIFF(CURDATE(), due_date) >= 1");
  while ($row = $sql->fetch_assoc()) {
    $due_date=$row["due_date"];
    $in_charge=$row["in_charge"];
    $id=$row["id"];

    $check_attendance = mysqli_query($con,"SELECT * FROM attendance LEFT JOIN accounts ON accounts.card = attendance.card WHERE attendance.date = '$due_date' AND accounts.username= '$in_charge'");
    $checkrows=mysqli_num_rows($check_attendance);
    if ($checkrows <= 0) {
      $con->next_result();
      $update_expired = "UPDATE tasks_details SET status='FINISHED', achievement='0', date_accomplished='$today', remarks='Failed to perform task', head_name='SYSTEM' WHERE due_date = '$due_date' AND in_charge = '$in_charge' AND id = '$id'";
      $result = mysqli_query($con, $update_expired);
      $check = 1;
    }
    else {
      $int = date_diff(date_create($due_date), $now);
      $interval = $int->format("%R%a");
      if ($interval >= 2) {
        $con->next_result();
        $update_expired = "UPDATE tasks_details SET status='FINISHED', achievement='0', date_accomplished='$today', remarks='Failed to perform task', head_name='SYSTEM' WHERE due_date = '$due_date' AND in_charge = '$in_charge' AND id = '$id'";
        $result = mysqli_query($con, $update_expired);
        $check = 1;
      }
    }
  }
  if ($check >= 1) {
    $con->next_result();
    $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Auto-purge module runs successfully.', '$systemtime', 'SYSTEM')";
    $result = mysqli_query($con, $systemlog);
  }
?>