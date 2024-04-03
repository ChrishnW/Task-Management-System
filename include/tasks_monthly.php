<?php
	include('link.php');
	include('connect.php');

  $con->next_result();
	$monthlytasks = mysqli_query($con, "SELECT * FROM tasks WHERE task_class = 3");
  while ($row = $monthlytasks->fetch_assoc()) {
    $task_name = $row['task_name'];
    $task_class = $row['task_class'];
    $task_for = $row['task_for'];
    $in_charge = $row['in_charge'];
    $submission = $row['submission'];
    $requirement_status = $row['requirement_status'];
    $status = "NOT YET STARTED";
    $today = date('Y-m-d');
    $currentDate = new DateTime();
    $success = FALSE;
    $fail = FALSE;

    // Extract the number and day from the input string
    if (preg_match('/(\d+)(?:st|nd|rd|th) week of (\w+)/', $submission, $matches)) {
      $weekNumber = (int)$matches[1];
      $dayOfWeekName = $matches[2];
    }
    // Calculate the next occurrence of the specified day of the week
    $nextDay = clone $currentDate;
    $nextDay->modify("next $dayOfWeekName"); // Use the variable $dayOfWeek here

    // Calculate the next due date
    $nextDueDate = clone $nextDay;
    $nextDueDate->modify("+" . ($weekNumber - 1) . " weeks");

    // Format the due date as YYYY-MM-DD
    $due_date = $nextDueDate->format('Y-m-d');

    // Use for rescheduling tasks that are deployed on days off
    $old_due = $due_date;

    $con->next_result();
    $check = mysqli_query($con, "SELECT * FROM day_off WHERE date_off='$due_date' AND status=1");
    $check_result = mysqli_num_rows($check);
    if ($check_result > 0 ){
      // Deploying New Task for Employee and set it for reschedule.
      $con->next_result();
      $import_checker = mysqli_query($con, "SELECT * FROM tasks_details WHERE task_name='$task_name' AND in_charge='$in_charge' AND due_date='$due_date' AND date_accomplished IS NULL");
      $import_checker_result = mysqli_num_rows($import_checker);
      if ($import_checker_result == 0){
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

        $newDate = date('Y-m-d', strtotime($due_date . ' +1 day'));

        // Start Deploying Tasks
        $deploytask = "INSERT INTO tasks_details (`task_code`, `task_name`, `task_class`, `task_for`, `in_charge`, `status`, `date_created`, `due_date`, `old_due`, `requirement_status`, `task_status`, `reschedule`, `resched_reason`) VALUES ('$task_code', '$task_name', 3, '$task_for', '$in_charge', '$status', '$today', '$newDate', '$old_due', '$requirement_status', 1, 2, 'This task has been assigned a due date of [$due_date], where that day is set as a day off or holiday in the system.')";
        $deploytask_result = mysqli_query($con, $deploytask);
        if ($deploytask_result == 1) {
          $success = TRUE;
        }
        else {
          $fail = TRUE;
        }
      }
    }
    else {
      // Deploying New Task for Employee
      $con->next_result();
      $import_checker = mysqli_query($con, "SELECT * FROM tasks_details WHERE task_name='$task_name' AND in_charge='$in_charge' AND due_date='$due_date' AND date_accomplished IS NULL");
      $import_checker_result = mysqli_num_rows($import_checker);
      if ($import_checker_result == 0){
        // Generating Unique Task Code of each Task of Employee
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

        // Start Deploying Tasks
        $deploytask = "INSERT INTO tasks_details (`task_code`, `task_name`, `task_class`, `task_for`, `in_charge`, `status`, `date_created`, `due_date`, `requirement_status`, `task_status`) VALUES ('$task_code', '$task_name', 3, '$task_for', '$in_charge', '$status', '$today', '$due_date', '$requirement_status', 1)";
        $deploytask_result = mysqli_query($con, $deploytask);
        if ($deploytask_result == 1) {
          $success = TRUE;
        }
        else {
          $fail = TRUE;
        }
      }
    }
  }

  if ($success == TRUE) {
    $con->next_result(); 
    $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Monthly task module runs successfully.', '$systemtime', 'SYSTEM')";
    $result = mysqli_query($con, $systemlog);
  }
  if ($fail == TRUE) {
    $con->next_result(); 
    $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Monthly task module failed to run.', '$systemtime', 'SYSTEM')";
    $result = mysqli_query($con, $systemlog);
  }
?>