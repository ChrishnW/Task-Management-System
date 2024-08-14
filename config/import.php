<?php
include('../include/auth.php');
include('../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_POST['taskImport'])) {
  $fileName     = $_FILES['file']['name'];
  $file_ext     = pathinfo($fileName, PATHINFO_EXTENSION);
  $allowed_ext  = ['xls', 'csv', 'xlsx'];
  if (in_array($file_ext, $allowed_ext)) {
    $inputFileNamePath  = $_FILES['file']['tmp_name'];
    $spreadsheet        = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
    $data               = $spreadsheet->getActiveSheet()->toArray();
    $count_data         = count($data) - 1;

    $count = "0";
    foreach ($data as $row) {
      if ($count > 0) {
        $task_name    = $row['0'];
        $task_details = $row['1'];
        $task_class   = $row['2'];
        $task_for     = $row['3'];
        $in_charge    = $row['4'];
        $submission   = $row['5'];
        $attachment   = $row['6'];
        $today        = date('Y-m-d');

        $con->next_result();
        $import_checker           = mysqli_query($con, "SELECT * FROM tasks WHERE task_name = '$task_name' AND task_class='$task_class' AND in_charge = '$in_charge' AND submission = '$submission'");
        $import_checker_result    = mysqli_num_rows($import_checker);

        if ($import_checker_result > 0) {
          $task_duplicated        = "INSERT INTO task_temp (`task_name`, `task_details`, `task_class`, `task_for`, `in_charge`, `submission`, `attachment`, `status`) values ('$task_name', '$task_details', '$task_class', '$task_for', '$in_charge', '$submission', '$attachment', 'DUPLICATED')";
          $task_duplicated_result = mysqli_query($con, $task_duplicated);
        } else {
          $task_ready             = "INSERT INTO task_temp (`task_name`, `task_details`, `task_class`, `task_for`, `in_charge`, `submission`, `attachment`, `status`) values ('$task_name', '$task_details', '$task_class', '$task_for', '$in_charge', '$submission', '$attachment', 'CLEAR')";
          $task_ready_result      = mysqli_query($con, $task_ready);
        }
      } else {
        $count = "1";
      }
    }
    $con->next_result();
    $import_checker         = mysqli_query($con, "SELECT * FROM task_temp WHERE status = 'DUPLICATED'");
    $import_checker_result  = mysqli_num_rows($import_checker);
    if ($import_checker_result > 0) {
      echo "Duplicated";
    } else {
      $con->next_result();
      $sql = mysqli_query($con, "SELECT * FROM task_temp WHERE status='CLEAR'");
      $con->next_result();
      if (mysqli_num_rows($sql) > 0) {
        while ($row = mysqli_fetch_assoc($sql)) {
          $task_name    = $row['task_name'];
          $task_class   = $row['task_class'];
          $task_details = $row['task_details'];
          $task_for     = $row['task_for'];
          $submission   = $row['submission'];
          $in_charge    = $row['in_charge'];
          $attachment   = $row['attachment'];
          $status       = 'NOT YET STARTED';
          $today        = date('Y-m-d');

          // Register the New Tasks in the Materlist
          $con->next_result();
          $import_checker         = mysqli_query($con, "SELECT * FROM task_list WHERE task_name='$task_name' AND task_for='$task_for'");
          $import_checker_result  = mysqli_num_rows($import_checker);
          if ($import_checker_result == 0) {
            $register_task = "INSERT INTO task_list (`task_name`, `task_details`, `task_class`, `task_for`, `date_created`, `status`) VALUES ('$task_name', '$task_details', '$task_class', '$task_for', '$today', 1)";
            $register_task_result = mysqli_query($con, $register_task);
          }
          // Assign the New Tasks to the Employee
          $con->next_result();
          $import_checker = mysqli_query($con, "SELECT * FROM tasks WHERE task_name='$task_name' AND in_charge='$in_charge'");
          $import_checker_result = mysqli_num_rows($import_checker);
          if ($import_checker_result == 0) {
            $assign_task = "INSERT INTO tasks (`task_name`, `task_class`, `task_details`, `task_for`, `requirement_status`, `in_charge`, `submission`) VALUES ('$task_name', '$task_class', '$task_details', '$task_for', '$attachment', '$in_charge', '$submission')";
            $assign_task_result = mysqli_query($con, $assign_task);
            echo "Success";
          }
        }
      }
    }
  }
}
?>