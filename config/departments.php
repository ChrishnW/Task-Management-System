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
    if ($result > 0){
      echo "Existing Department ID or Name detected.";
    } else {
      $query_result = mysqli_query($con, "INSERT INTO department (`dept_id`, `dept_name`, `status`) VALUES ('$dept_code', '$dept_name', '1')");
      if ($query_result) {
        echo "Success";
      }
    }
  }
}
if (isset($_POST['deleteDepartment'])) {
  $id = $_POST['delete_id'];
  $query_result = mysqli_query($con, "DELETE FROM `department` WHERE id='$id'");
  if ($query_result) {
    echo "Success";
  }
}
if (isset($_POST['deparmentUpdate'])) {
  $error        = false;
  $dept_id      = $_POST['dept_id'];
  $dept_name    = strtoupper($_POST['dept_name']);
  $dept_code    = $_POST['dept_code'];
  $dept_oldcode = $_POST['dept_oldcode'];
  $dept_status  = $_POST['dept_status'];
  if ($dept_id === '' || $dept_name === '') {
    $error = true;
    echo "Empty field has been detected! Please try again.";
  }
  if(!$error) {
    $query_result = mysqli_query($con, "UPDATE department SET dept_id='$dept_code', dept_name='$dept_name', status='$dept_status' WHERE id='$dept_id'");
    if ($query_result) {
      $con->next_result();
      $query_result = mysqli_query($con, "UPDATE section SET dept_id='$dept_code', status='$dept_status' WHERE dept_id='$dept_oldcode'");
      if ($query_result) {
        echo "Success";
      } else {
        echo "Failed to update the department section's information, please try again.";
      }
    } else {
      echo "Failed to update the department information, please try again.";
    }
  }
}
?>