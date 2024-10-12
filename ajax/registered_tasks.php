<?php
include('../include/auth.php');
if (isset($_POST['editSelect'])) {
  $id           = $_POST['taskEditID'];
  $arrayresult  = [];

  $fetch_query = mysqli_query($con, "SELECT * FROM task_list WHERE id = '$id'");
  if (mysqli_num_rows($fetch_query) > 0) {
    while ($row = mysqli_fetch_array($fetch_query)) {
      array_push($arrayresult, $row);
      header('content-type: application/json');
      echo json_encode($arrayresult);
    }
  }
}
if (isset($_POST['taskUpdate'])) {
  $error              = false;
  $id                 = $_POST['taskUpdate_id'];
  $task_name_temp     = strtolower($_POST['taskUpdate_name']);
  $task_name          = ucwords($task_name_temp);
  $task_details_temp  = strtolower($_POST['taskUpdate_details']);
  $task_details       = ucwords($task_details_temp);
  $task_class         = $_POST['taskUpdate_class'];
  $task_for           = $_POST['taskUpdate_for'];
  $task_date          = date('Y-m-d');
  if($task_name === '' || $task_details === '' || $task_class === '' || $task_for === ''){
    $error = true;
    echo "Empty field has been detected! Please try again.";
  }
  if(!$error){
    $query_result = mysqli_query($con, "UPDATE task_list SET task_name='$task_name', task_details='$task_details', task_class='$task_class', task_for='$task_for', date_created='$task_date' WHERE id='$id'");
    if ($query_result) {
      echo "Success";
    } else {
      echo "Unable to complete the operation. Please try again later.";
    }
  }
}
if (isset($_POST['deleteTask'])) {
  $task_id  = $_POST['delete_id'];
  $query_result = mysqli_query($con, "DELETE FROM task_list WHERE id='$task_id'");
  if ($query_result) {
    echo "Success";
  } else {
    echo "Unable to complete the operation. Please try again later.";
  }
}
if (isset($_POST['taskRegister'])) {
  $error              = false;
  $task_name_temp     = strtolower($_POST['task_name']);
  $task_name          = ucwords($task_name_temp);
  $task_class         = $_POST['task_class'];
  $task_for           = $_POST['task_for'];
  $task_details_temp  = strtolower($_POST['task_details']);
  $task_details       = ucwords($task_details_temp);
  $date_created       = date('Y-m-d');
  if($task_name === '' || $task_class === '' || $task_for === '' || $task_details === ''){
    $error = true;
    echo "Empty field has been detected! Please try again.";
  }
  if(!$error){
    $check = mysqli_query($con, "SELECT * FROM task_list WHERE task_name='$task_name' AND task_for='$task_for'");
    $checkRows = mysqli_num_rows($check);
    if ($checkRows > 0) {
      echo "Task already exists on this section!";
    } else {
      $query_result = mysqli_query($con, "INSERT `task_list` (`task_name`, `task_class`, `task_for`, `task_details`, `date_created`, `status`) VALUES ('$task_name', '$task_class', '$task_for', '$task_details', '$date_created', '1')");
      echo "Success";
    }
  }
}
?>