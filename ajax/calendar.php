<?php
include('../include/auth.php');
if (isset($_POST['getDates'])) {
  $from = $_POST['date_from'];
  $to = $_POST['date_to'];

  $startDate = new DateTime($from);
  $endDate = new DateTime($to);

  $dateList = [];
  while ($startDate <= $endDate) {
    $dateList[] = $startDate->format('Y-m-d');
    $startDate->modify('+1 day');
  }
  foreach ($dateList as $date) {
    echo "<option value=\"$date\">" . date('F d, Y l', strtotime($date)) . "</option>";
  }
}
if (isset($_POST['dayoffCreate'])) {
  $error          = false;
  $register_dates = $_POST['register_dates'] ?? '';
  $remarks        = $_POST['remarks'];
  $count = 0;
  if($register_dates === '' || $remarks === ''){
    $error  = true;
    echo "Empty field has been detected! Please try again.";
  }
  if(!$error){
    foreach ($register_dates as $date) {
      $check      = mysqli_query($con, "SELECT * FROM day_off WHERE date_off='$date'");
      $checkrows  = mysqli_num_rows($check);
      if ($checkrows > 0) {
        $count += 1;
      } else {
        $query_result = mysqli_query($con, "INSERT INTO day_off (`date_off`, `remarks`, `status`) VALUES ('$date', '$remarks', '1')");
      }
    }
    if($query_result) {
      echo "Success";
    } else {
      echo "Unable to complete the operation. Please try again later.";
    }
  }
}
if (isset($_POST['recordDelete'])) {
  $id = $_POST['id'];
  $query_result = mysqli_query($con, "DELETE FROM `day_off` WHERE id='$id'");
  if ($query_result) {
    echo "Success";
  } else {
    echo "Unable to complete the operation. Please try again later.";
  }
}
if (isset($_POST['dayoffUpdate'])) {
  $error    = false;
  $id       = $_POST['update_id'];
  $new_date = $_POST['update_date'];
  $status   = $_POST['update_status'];
  $remarks  = $_POST['update_remarks'];
  if($new_date === '' || $status === '' || $remarks === ''){
    $error  = true;
    echo "Empty field has been detected! Please try again.";
  }
  if(!$error){
    $query_result = mysqli_query($con, "UPDATE day_off SET date_off='$new_date', status='$status', remarks='$remarks' WHERE id='$id'");
    if ($query_result) {
      echo "Success";
    } else {
      echo "Unable to complete the operation. Please try again later.";
    }
  }
}
?>