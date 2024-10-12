<?php
include('../include/auth.php');
if (isset($_POST['sectionUpdate'])) {
  $error          = false;
  $sec_id         = $_POST['sec_id'];
  $sec_code       = strtoupper($_POST['sec_code']);
  $sec_name       = strtoupper($_POST['sec_name']);
  $sec_department = $_POST['sec_dept'];
  $sec_status     = $_POST['sec_status'];
  $codeFlag       = false;
  $statusFlag     = false;
  if ($sec_code === '' || $sec_name === '' || $sec_department === '' || $sec_status === '') {
    $error = true;
    echo "Empty field has been detected! Please try again.";
  } elseif (strpos($sec_code, ' ') !== false) {
    $error = true;
    echo "Section ID should not contain spaces between characters.";
  }
  if (!$error) {
    $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM section WHERE id='$sec_id'"));
    if ($row['sec_id'] !== $sec_code) {
      $codeFlag = true;
    }
    if ($row['status'] !== $sec_status) {
      $statusFlag = true;
    }
    $query_update = mysqli_query($con, "UPDATE section SET sec_id='$sec_code', sec_name='$sec_name', dept_id='$sec_department', status='$sec_status' WHERE id='$sec_id'");
    if ($codeFlag) {
      $updateSecID  = "UPDATE accounts SET sec_id='$sec_code' WHERE sec_id='{$row['sec_id']}'; UPDATE tasks SET task_for='$sec_code' WHERE task_for='{$row['sec_id']}'; UPDATE task_list SET task_for='$sec_code' WHERE task_for='{$row['sec_id']}'";
      $updateNow    = mysqli_multi_query($con, $updateSecID);
    }
    if ($statusFlag) {
      $updateStatus = mysqli_query($con, "UPDATE accounts SET status='$sec_status' WHERE sec_id='{$sec_code}' AND username!='ADMIN'");
    }
    if ($query_update) {
      echo "Success";
    }
  }
}
if (isset($_POST['sectionCreate'])) {
  $error        =  false;
  $section_name = strtoupper($_POST['regsec_name']);
  $section_code = strtoupper($_POST['regsec_code']);
  $section_dep  = $_POST['regsec_dept'];
  if ($section_name === '' || $section_code === '' || $section_dep === '') {
    $error = true;
    echo "Empty field has been detected! Please try again.";
  } elseif (strpos($section_code, ' ') !== false) {
    $error = true;
    echo "Section ID should not contain spaces between characters.";
  }
  if (!$error) {
    $query_result = mysqli_query($con, "INSERT INTO section (`sec_id`, `sec_name`, `dept_id`, `status`) VALUES ('$section_code', '$section_name', '$section_dep', '1')");
    if ($query_result) {
      echo "Success";
    } else {
      echo "There's a problem that occurred during the process request; please try again.";
    }
  }
}
