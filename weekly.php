<?php
include('include/connect.php');
date_default_timezone_set('Asia/Manila');

$date_today   = date('Y-m-d');
$weekStart    = date('Y-m-d', strtotime('monday this week'));
$taskCount    = 0;

$query_tasks  = mysqli_query($con, "SELECT * FROM tasks WHERE task_class=2 ORDER BY task_name ASC");
while ($row = $query_tasks->fetch_assoc()) {
  $taskName   = $row['task_name'];
  $taskClass  = $row['task_class'];
  $taskFor    = $row['task_for'];
  $inCharge   = $row['in_charge'];
  $submission = $row['submission'];
  $getFile    = $row['requirement_status'];
  $taskStatus = 'NOT YET STARTED';
  
  $submission = explode(', ', $row['submission']);
  foreach ($submission as $day) {
    $day = ucfirst(strtolower($day));

    $date = date('Y-m-d', strtotime("$weekStart $day"));

    if ($date >= $date_today) {
      $dueDate          = $date;
      $latestcode       = mysqli_fetch_assoc(mysqli_query($con, "SELECT MAX(task_code) AS latest_task_code FROM tasks_details WHERE task_class='$taskClass' AND task_for='$taskFor'"))['latest_task_code'];
      $numeric_portion  = intval(substr($latestcode, -6)) + 1;
      $taskCode         = $taskFor . '-TW-' . str_pad($numeric_portion, 6, '0', STR_PAD_LEFT);

      $insert_task = "INSERT INTO tasks_details (`task_code`, `task_name`, `task_class`, `task_for`, `in_charge`, `status`, `date_created`, `due_date`, `requirement_status`, `task_status`) VALUES ('$taskCode', '$taskName', '$taskClass', '$taskFor', '$inCharge', '$taskStatus', '$date_today', '$dueDate', '$getFile', 1)";
      $result_task = mysqli_query($con, $insert_task);
    }
  }
}
