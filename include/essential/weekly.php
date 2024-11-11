<?php
include('../connect.php');
date_default_timezone_set('Asia/Manila');

$date_today   = date('Y-m-d');
$weekStart    = date('Y-m-d', strtotime('monday this week'));
$taskCount    = 0;

$query_tasks  = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id WHERE t.status=1 AND tl.task_class=2 ORDER BY tl.task_name ASC");
if (mysqli_num_rows($query_tasks) > 0) {
  while ($row = $query_tasks->fetch_assoc()) {
    $task_id    = $row['id'];
    $submission = $row['submission'];

    $submission = explode(', ', $row['submission']);
    foreach ($submission as $day) {
      $day = ucfirst(strtolower($day));
      $date = date('Y-m-d', strtotime("$weekStart $day"));
      $dueDate          = $date . ' 16:00:00';

      $checkDeployed = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tasks_details WHERE task_id='$task_id' AND due_date='$dueDate'"));
      if ($checkDeployed === 0) :
        $deployTask = mysqli_query($con, "INSERT INTO tasks_details (`task_id`, `due_date`) VALUES ('$task_id', '$dueDate')");
        $taskCount += 1;
      endif;
    }
  }
  if ($taskCount > 0) {
    $systemAction = "$taskCount weekly routine tasks have been successfully generated.";
  } else {
    $systemAction = "Failed to generate tasks.";
  }
} else {
  $systemAction = 'No weekly routine tasks assigned to members found in the system. No tasks have been distributed.';
}

if ($systemAction != '') {
  $systemTime  = date('Y-m-d H:i:s');
  $query_log  = mysqli_query($con, "INSERT INTO system_log (action, date_created, user) VALUES ('$systemAction', '$systemTime', 'ADMIN')");
}
