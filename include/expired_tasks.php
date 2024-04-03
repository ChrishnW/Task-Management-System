<?php
include('connect.php');
$today = date('Y-m-d');
$sql = mysqli_query($con,"SELECT * FROM tasks_details WHERE status = 'NOT YET STARTED' AND DATEDIFF(CURDATE(), due_date) >= 2");

while ($row = $sql->fetch_assoc()) 
{
  $due_date=$row["due_date"];
  $in_charge=$row["in_charge"];
  $id=$row["id"];
  $check_attendance = mysqli_query($con,"SELECT * FROM attendance LEFT JOIN accounts ON accounts.card = attendance.card WHERE attendance.date = '$due_date' AND accounts.username= '$in_charge'");
  $checkrows=mysqli_num_rows($check_attendance);
  
  if($checkrows <= 0) {
    $update_expired = "UPDATE tasks_details SET status='FINISHED', achievement='0', date_accomplished='$today', remarks='Failed to perform task' WHERE due_date = '$due_date' AND in_charge = '$in_charge' AND id = '$id' ";
    $result = mysqli_query($con, $update_expired);
  }
}

?>