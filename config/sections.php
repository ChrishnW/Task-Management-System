<?php
include('../include/auth.php');
if (isset($_POST['sectionUpdate'])) {
  $error          = false;
  $sec_id         = $_POST['sec_id'];
  $sec_code       = strtoupper($_POST['sec_code']);
  $sec_name       = strtoupper($_POST['sec_name']);
  if ($sec_code === '' || $sec_name === '') {
    $error = true;
    echo "Empty field has been detected! Please try again.";
  } elseif (strpos($sec_code, ' ') !== false) {
    $error = true;
    echo "Section ID should not contain spaces between characters.";
  }
  if (!$error) {
    $query_update = mysqli_query($con, "UPDATE section SET sec_id='$sec_code', sec_name='$sec_name' WHERE id='$sec_id'");
    if ($query_result) {
      die('Success');
    } else {
      die('Error');
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

if (isset($_POST['changeStatus'])) {
  $query_result = mysqli_query($con, "UPDATE section SET status='{$_POST['status']}' WHERE id='{$_POST['id']}'");
  if ($query_result) {
    die('Success');
  } else {
    die('Error');
  }
}

// Use for Department Load Section JS
if (isset($_POST['loadSections'])) {
  $getSectionList = mysqli_query($con, "SELECT * FROM section WHERE dept_id='{$_POST['deptID']}' AND status=1");
  while ($row = mysqli_fetch_assoc($getSectionList)):
    echo '<option value="' . $row['sec_id'] . '">' . ucwords(strtolower($row['sec_name'])) . '</option>';
  endwhile;
}
