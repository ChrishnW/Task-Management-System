<?php
include('../include/auth.php');
if (isset($_POST['deleteSection'])) {
  $id       = $_POST['delete_id'];
  $section  = $_POST['delete_sec'];
  $query_result = mysqli_query($con, "DELETE FROM `section` WHERE id='$id'");
  if ($query_result) {
    $con->next_result();
    $query_result = mysqli_query($con, "UPDATE `accounts` SET status='0' WHERE sec_id='$section'");
    if ($query_result) {
      echo "Success";
    } else {
      echo "An unexpected error has occurred while removing the deleted section from each registered account.";
    }
  } else {
    echo "There's a problem that occurred during the process request; please try again.";
  }
}
if (isset($_POST['sectionUpdate'])) {
  $error          = false;
  $sec_id         = $_POST['sec_id'];
  $sec_code       = strtoupper($_POST['sec_code']);
  $sec_oldcode    = $_POST['sec_oldcode'];
  $sec_name       = strtoupper($_POST['sec_name']);
  $sec_department = $_POST['sec_dept'];
  $sec_status     = $_POST['sec_status'];
  if($sec_code === '' || $sec_name === '' || $sec_department === '' || $sec_status === ''){
    $error = true;
    echo "Empty field has been detected! Please try again.";
  } elseif (strpos($sec_code, ' ') !== false) {
    $error = true;
    echo "Section ID should not contain spaces between characters.";
  }
  if(!$error){
    $query_result = mysqli_query($con, "UPDATE section SET sec_id='$sec_code', sec_name='$sec_name', dept_id='$sec_department', status='$sec_status' WHERE id='$sec_id'");
    if ($query_result) {
      $con->next_result();
      $query_result = mysqli_query($con, "UPDATE accounts SET sec_id='$sec_code', status='$sec_status' WHERE sec_id='$sec_oldcode'");
      if ($query_result) {
        $con->next_result();
        $query_result = mysqli_query($con, "UPDATE tasks SET task_for='$sec_code' WHERE task_for='$sec_oldcode'");
        if ($query_result) {
          $con->next_result();
          $query_result = mysqli_query($con, "UPDATE task_list SET task_for='$sec_code', status='$sec_status' WHERE task_for='$sec_oldcode'");
          if ($query_result) {
            $con->next_result();
            $query_result = mysqli_query($con, "UPDATE tasks_details SET task_status='$sec_status' WHERE task_for='$sec_oldcode'");
            if ($query_result) {
              echo "Success";
            } else {
              echo "Failed to update deployed tasks section.";
            }
          } else {
            echo "Failed to update task list section.";
          }
        } else {
          echo "Failed to update assigned task section.";
        }
      } else {
        echo "Failed to update registered accounts section.";
      }
    } else {
      echo "There's a problem that occurred during the process request; please try again.";
    }
  }
}
if (isset($_POST['sectionCreate'])) {
  $error        =  false;
  $section_name = strtoupper($_POST['regsec_name']);
  $section_code = strtoupper($_POST['regsec_code']);
  $section_dep  = $_POST['regsec_dept'];
  if($section_name === '' || $section_code === '' || $section_dep === ''){
    $error = true;
    echo "Empty field has been detected! Please try again.";
  } elseif (strpos($section_code, ' ') !== false) {
    $error = true;
    echo "Section ID should not contain spaces between characters.";
  }
  if(!$error){
    $query_result = mysqli_query($con, "INSERT INTO section (`sec_id`, `sec_name`, `dept_id`, `status`) VALUES ('$section_code', '$section_name', '$section_dep', '1')");
    if ($query_result) {
      echo "Success";
    }
    else {
      echo "There's a problem that occurred during the process request; please try again.";
    }
  }
}
?>