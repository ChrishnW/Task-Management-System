<?php
include('../connect.php');
date_default_timezone_set('Asia/Manila');

$date_today = date('Y-m-d');
$taskCount  = 0;

if (date('N') >= 1 && date('N') <= 5) {
  $query_dayoff = mysqli_query($con, "SELECT * FROM day_off WHERE status=1 AND date_off='$date_today'");
  $dayoff_count = mysqli_num_rows($query_dayoff);
  if ($dayoff_count > 0) {
    $systemAction = "Today's date is set as a day off; no tasks have been generated.";
  } else {
    $loadDailyTasks = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id WHERE tl.status=1 AND tl.task_class=1 ORDER BY tl.task_name ASC");
    if (mysqli_num_rows($loadDailyTasks) > 0) {
      while ($row = $loadDailyTasks->fetch_assoc()) {
        $task_id  = $row['id'];
        $due_date = date('Y-m-d 16:00:00');

        $deployTask = mysqli_query($con, "INSERT INTO tasks_details (`task_id`, `due_date`) VALUES ('$task_id', '$due_date')");
        $taskCount += 1;
      }
      if ($loadDailyTasks) {
        $systemAction = "$taskCount daily routine tasks have been successfully generated.";
      } else {
        $systemAction = "Failed to generate tasks.";
      }
    } else {
      $systemAction = 'No daily routine tasks assigned to members found in the system. No tasks have been distributed.';
    }
  }
} elseif (date('N') == 6 || date('N') == 7) {
  $systemAction = "Today is a weekend; no tasks have been generated.";
}

if ($systemAction != '') {
  $systemTime  = date('Y-m-d H:i:s');
  $query_log  = mysqli_query($con, "INSERT INTO system_log (action, date_created, user) VALUES ('$systemAction', '$systemTime', 'ADMIN')");
}
