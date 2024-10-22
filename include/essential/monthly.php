<?php
include('../connect.php');
date_default_timezone_set('Asia/Manila');

$date_today   = date('Y-m-d');
$currentYear  = date("Y");
$currentMonth = date("m");
$taskCount  = 0;

$query_tasks  = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id WHERE t.status=1 AND tl.task_class IN (3, 6) ORDER BY tl.task_name ASC");
if (mysqli_num_rows($query_tasks) > 0) {
  while ($row = $query_tasks->fetch_assoc()) {
    $task_id    = $row['id'];
    $submission = $row['submission'];
    $dayNumbers = explode(', ', $submission);

    foreach ($dayNumbers as $day) {
      $fetchDate = sprintf("%04d-%02d-%02d", $currentYear, $currentMonth, $day);
      $dueDate   = $fetchDate . ' 16:00:00';

      $deployTask = mysqli_query($con, "INSERT INTO tasks_details (`task_id`, `due_date`) VALUES ('$task_id', '$dueDate')");
      if ($deployTask) {
        $taskCount += 1;
      }
    }
  }
  if ($taskCount > 0) {
    $systemAction = "$taskCount monthly routine & report tasks have been successfully generated.";
  } else {
    $systemAction = "Failed to generate tasks.";
  }
} else {
  $systemAction = 'No monthly routine & report tasks assigned to members found in the system. No tasks have been distributed.';
}

if ($systemAction != '') {
  $systemTime  = date('Y-m-d H:i:s');
  $query_log  = mysqli_query($con, "INSERT INTO system_log (action, date_created, user) VALUES ('$systemAction', '$systemTime', 'ADMIN')");
}
