<?php
include('../include/auth.php');
if (isset($_POST['sectionUpdate'])) {
  $error          = false;
  $sec_code       = strtoupper($_POST['sec_code']);
  $sec_name       = strtoupper($_POST['sec_name']);
  $sec_oldcode    = $_POST['sec_oldcode'];
  $sec_department = $_POST['sec_dept'];
  $sec_status     = $_POST['sec_status'];
  if ($sec_code === '' || $sec_name === '' || $sec_department === '' || $sec_status === '') {
    $error = true;
    echo "Please fill in the required field.";
  } elseif (strpos($sec_code, ' ') !== false) {
    $error = true;
    echo "Section ID should not contain spaces between characters.";
  }
  if (!$error) {
    $query_result = mysqli_query($con, "UPDATE sections SET sec_id='$sec_code', sec_name='$sec_name', dept_id='$sec_department', status='$sec_status' WHERE sec_id='$sec_oldcode'");
    if ($query_result) {
      die('Success');
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
    $query_result = mysqli_query($con, "INSERT INTO sections (`sec_id`, `sec_name`, `dept_id`, `status`) VALUES ('$section_code', '$section_name', '$section_dep', '1')");
    if ($query_result) {
      echo "Success";
    } else {
      echo "There's a problem that occurred during the process request; please try again.";
    }
  }
}
