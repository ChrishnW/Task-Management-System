<?php
include('../include/auth.php');
if (isset($_POST['deparmentCreate'])) {
  $error      = false;
  $dept_name  = strtoupper($_POST['regdept_name']);
  $dept_code  = $_POST['regdept_code'];
  if ($dept_name === '' || $dept_code === '') {
    $error = true;
    echo "Please fill in all required fields.";
  }
  if (!$error) {
    $check  = mysqli_query($con, "SELECT * FROM department WHERE dept_id='$dept_code' OR dept_name='$dept_name'");
    $result = mysqli_num_rows($check);
    if ($result > 0) {
      echo "Existing Department ID or Name detected.";
    } else {
      $query_result = mysqli_query($con, "INSERT INTO department (`dept_id`, `dept_name`, `status`) VALUES ('$dept_code', '$dept_name', '1')");
      if ($query_result) {
        echo "Success";
      }
    }
  }
}
if (isset($_POST['deparmentUpdate'])) {
  $error        = false;
  $dept_id      = $_POST['dept_id'];
  $dept_name    = strtoupper($_POST['dept_name']);
  $dept_code    = $_POST['dept_code'];
  $dept_oldcode = $_POST['dept_oldcode'];
  $statusFlag     = false;
  if ($dept_id === '' || $dept_name === '') {
    $error = true;
    echo "Empty field has been detected! Please try again.";
  }
  if (!$error) {
    $update_query = mysqli_query($con, "UPDATE department SET `dept_name`='$dept_name' WHERE id='$dept_id'");
    if ($update_query) {
      echo "Success";
    }
  }
}

if (isset($_POST['changeStatus'])) {
  $query_result = mysqli_query($con, "UPDATE department SET status='{$_POST['status']}' WHERE id='{$_POST['id']}'");
  if ($query_result) {
    die('Success');
  } else {
    die('Error');
  }
}
