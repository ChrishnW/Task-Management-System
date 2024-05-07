<?php
  include('../include/auth.php');
  include('../include/connect.php');

  date_default_timezone_set('Asia/Manila');
  $ID = $_POST['id'];
  $today = date("Y-m-d:H:i:s");
  $con->next_result();
  $targetDir = "../documents/Task-Attachments/";

  $fileName = basename($_FILES["file"]["name"]);
  $extension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
  $fileName = "[" . $username . "_" . $ID . "] " . $fileName;
  $targetFilePath = $targetDir . $fileName;
  $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

  $allowTypes = array('pdf', 'xls', 'xlsx', 'docx', 'pptx', 'txt');
  if (in_array($fileType, $allowTypes)) {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
      $select = "SELECT attachment FROM tasks_details WHERE task_code = '$ID'";
      $select_result = mysqli_query($con, $select);
      $row = mysqli_fetch_assoc($select_result);
      $oldFileName = $row['attachment'];

      $insert = "UPDATE tasks_details SET attachment = '$fileName' WHERE task_code = '$ID'";
      $insert_result = mysqli_query($con, $insert);
      if($insert) {
        if($oldFileName != "" && file_exists($targetDir . $oldFileName)){
          unlink($targetDir . $oldFileName);
        }
        $con->next_result();
        $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Replace attachment for task [$ID].', '$systemtime', '$username')";
        $result = mysqli_query($con, $systemlog);
        echo "Success";
      }
    } 
    else {
      echo "Unexpected error";
    }
  } 
  else {
    echo "File not supported";
  }
?>
