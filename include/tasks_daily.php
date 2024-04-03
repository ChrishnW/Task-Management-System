<?php
	include('link.php');
	include('connect.php');

  $today = date('Y-m-d');
  $due_date = $today;

  if (date('N') >= 1 && date('N') <= 5) {
    $con->next_result();
    $dailytasks = mysqli_query($con, "SELECT * FROM tasks WHERE task_class = 1");
    while ($row = $dailytasks->fetch_assoc()) {
      $task_name = $row['task_name'];
      $task_class = $row['task_class'];
      $task_for = $row['task_for'];
      $in_charge = $row['in_charge'];
      $submission = $row['submission'];
      $requirement_status = 0;
      $status = "NOT YET STARTED";
      $fail = FALSE;
      $success = FALSE;
      $duplicate = FALSE;

      $con->next_result();
      $check = mysqli_query($con, "SELECT * FROM day_off WHERE date_off='$due_date' AND status=1");
      $check_result = mysqli_num_rows($check);
      if ($check_result <= 0){
        $con->next_result();
        $import_checker = mysqli_query($con, "SELECT * FROM tasks_details WHERE task_name='$task_name' AND in_charge='$in_charge' AND due_date='$due_date' AND date_accomplished IS NULL");
        $import_checker_result = mysqli_num_rows($import_checker);
        if ($import_checker_result <= 0){
          $getlatestcode = mysqli_query($con, "SELECT MAX(task_code) AS latest_task_code FROM tasks_details WHERE task_class = '$task_class' AND task_for = '$task_for'");
          $getlatestcode_result = mysqli_fetch_assoc($getlatestcode);
          $latestcode = $getlatestcode_result['latest_task_code'];
          $prefix = '';
          if ($task_class == '1') {
            $prefix = 'TD';
          } 
          elseif ($task_class == '2') {
            $prefix = 'TW';
          } 
          elseif ($task_class == '3') {
            $prefix = 'TM';
          } 
          elseif ($task_class == '4') {
            $prefix = 'TA';
          } 
          elseif ($task_class == '5') {
            $prefix = 'TP';
          }
          $numeric_portion = intval(substr($latestcode, -6)) + 1;
          $task_code = $task_for.'-'.$prefix . '-' . str_pad($numeric_portion, 6, '0', STR_PAD_LEFT);

          $deploytask = "INSERT INTO tasks_details (`task_code`, `task_name`, `task_class`, `task_for`, `in_charge`, `status`, `date_created`, `due_date`, `requirement_status`, `task_status`) VALUES ('$task_code', '$task_name', 1, '$task_for', '$in_charge', '$status', '$today', '$due_date', '$requirement_status', 1)";
          $deploytask_result = mysqli_query($con, $deploytask);
          $success = TRUE;
        }
      }
      else {
        $fail = TRUE;
      }
    }
  }
  else {
    $fail = TRUE;
  }

  if ($success == TRUE) {
    $con->next_result(); 
    $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Daily task module runs successfully.', '$systemtime', 'SYSTEM')";
    $result = mysqli_query($con, $systemlog);
  }
  if ($fail == TRUE) {
    $con->next_result();
    $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Daily task module day off date detected.', '$systemtime', 'SYSTEM')";
    $result = mysqli_query($con, $systemlog);
  }
?>