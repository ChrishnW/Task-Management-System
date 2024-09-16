<?php
include('../connect.php');
date_default_timezone_set('Asia/Manila');

$date_today   = date('Y-m-d');
$currentYear  = date("Y");
$currentMonth = date("m");
$taskCount  = 0;

$query_tasks  = mysqli_query($con, "SELECT * FROM tasks WHERE task_class IN (3, 6) ORDER BY task_name ASC, task_class ASC");
if (mysqli_num_rows($query_tasks) > 0) {
  while ($row = $query_tasks->fetch_assoc()) {
    $taskName   = $row['task_name'];
    $taskClass  = $row['task_class'];
    $taskFor    = $row['task_for'];
    $inCharge   = $row['in_charge'];
    $getFile    = $row['requirement_status'];
    $day        = $row['submission'];
    $dueDate = date("Y-m-d 16:00:00", strtotime("$currentYear-$currentMonth-$day"));

    if (date('w', strtotime($dueDate)) == 0) {
      $taskStatus = "RESCHEDULE";
    } else {
      $query_dayoff = mysqli_query($con, "SELECT * FROM day_off WHERE status=1 AND date_off='$dueDate'");
      $dayoff = $query_dayoff->num_rows;
      if ($dayoff > 0) {
        $taskStatus = "RESCHEDULE";
      } else {
        $taskStatus = "NOT YET STARTED";
      }
    }
    $latestcode = mysqli_fetch_assoc(mysqli_query($con, "SELECT MAX(task_code) AS latest_task_code FROM tasks_details WHERE task_class='$taskClass' AND task_for='$taskFor'"))['latest_task_code'];
    $numeric_portion = intval(substr($latestcode, -6)) + 1;
    $prefix = ($taskClass == 3) ? 'TM' : 'TR';
    $taskCode = $taskFor . '-' . $prefix . '-' . str_pad($numeric_portion, 6, '0', STR_PAD_LEFT);
    $insert_task = "INSERT INTO tasks_details (`task_code`, `task_name`, `task_class`, `task_for`, `in_charge`, `status`, `date_created`, `due_date`, `requirement_status`, `task_status`) VALUES ('$taskCode', '$taskName', '$taskClass', '$taskFor', '$inCharge', '$taskStatus', '$date_today', '$dueDate', '$getFile', 1)";
    $result_task = mysqli_query($con, $insert_task);
    $taskCount += 1;
  }
  if ($result_task) {
    $systemAction = "$taskCount monthly routine & report tasks have been successfully generated.";
  } else {
    $systemAction = "Failed to generate tasks.";
  }
} else {
  $systemAction = 'No monthly routine & report tasks assigned to members found in the system. No tasks have been distributed.';
}

if ($systemAction != '') {
  $systemTime  = date('Y-m-d H:i:s');
  $query_log  = mysqli_query($con, "INSERT INTO system_log (action, date_created, user) VALUES ('$systemAction', '$systemTime', 'SYSTEM MODULE')");
}
