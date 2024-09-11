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
  if (isset($_POST['deparmentUpdate'])) {
    $error        = false;
    $dept_id      = $_POST['dept_id'];
    $dept_name    = strtoupper($_POST['dept_name']);
    $dept_code    = $_POST['dept_code'];
    $dept_oldcode = $_POST['dept_oldcode'];
    $dept_status  = $_POST['dept_status'];
    $statusFlag     = false;
    if ($dept_id === '' || $dept_name === '') {
      $error = true;
      echo "Empty field has been detected! Please try again.";
    }
    if(!$error) {
      $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM department WHERE id='$dept_id'"));
      if ($row['dept_name'] !== $dept_name) {
        log_action("Updated Department Name from {$row['dept_name']} to {$dept_name}.");
      }
      if ($row['status'] !== $dept_status) {
        $statusFlag = true;
        if ($dept_status == 1) {
          log_action("Updated Department Status of {$dept_name} from Inactive to Active.");
        } else {
          log_action("Updated Department Status of {$dept_name} from Active to Inactive.");
        }
      }
    }
  }
?>