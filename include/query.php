<?php
date_default_timezone_set('Asia/Manila');
$currentDate  = date('Y-m-d');
$currentMonth = date('m');
$currentYear  = date('Y');
$minDay       = date('Y-m-d', strtotime('+1 day'));

// Functions
function getTaskClass($taskClassNumber)
{
  $taskClasses = [1 => ['DAILY ROUTINE', 'info'], 2 => ['WEEKLY ROUTINE', 'info'], 3 => ['MONTHLY ROUTINE', 'info'], 4 => ['ADDITIONAL TASK', 'info'], 5 => ['PROJECT', 'info'], 6 => ['MONTHLY REPORT', 'danger']];
  return '<span class="badge badge-' . ($taskClasses[$taskClassNumber][1] ?? 'secondary') . '">' . ($taskClasses[$taskClassNumber][0] ?? 'Unknown') . '</span>';
}
function getDirectorySize($dir)
{
  $size = 0;
  foreach (glob(rtrim($dir, '/') . '/*', GLOB_NOSORT) as $each) {
    $size += is_file($each) ? filesize($each) : getDirectorySize($each);
  }
  return $size;
}
function formatSize($bytes)
{
  $units = ['B', 'KB', 'MB', 'GB', 'TB'];
  for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
    $bytes /= 1024;
  }
  return round($bytes, 2) . ' ' . $units[$i];
}
function log_action($action)
{
  global $con;
  $datetime = date("Y-m-d H:i:s");
  $username = $_SESSION['SESS_MEMBER_USERNAME'];
  $query_insert = mysqli_query($con, "INSERT INTO system_log (action, date_created, user) VALUES ('$action', '$datetime', '$username')");
}
// End of Functions


// Query
$con->next_result();
$query_result = mysqli_query($con, "SELECT a.*, d.dept_name, d.dept_id, s.sec_name FROM accounts a JOIN sections s ON s.sec_id=a.sec_id JOIN departments d ON d.dept_id=s.dept_id WHERE a.username='$username'");
if (mysqli_num_rows($query_result) > 0) {
  while ($row = $query_result->fetch_assoc()) {
    $profileURL = empty($row["img"]) ? '../assets/img/user-profiles/nologo.png' : '../assets/img/user-profiles/' . $row["img"];
    $full_name  = ucwords(strtolower($row['fname'] . ' ' . $row['lname']));
    $fname      = ucwords(strtolower($row['fname']));
    $lname      = ucwords(strtolower($row['lname']));
    $card       = $row['empid'];
    $email      = $row['email'];
    $sec_id     = $row['sec_id'];
    $sec_name   = ucwords(strtolower($row['sec_name']));
    $dept_id    = $row['dept_id'];
    $dept_name  = ucwords(strtolower($row['dept_name']));
  }
}

if ($access == 3) {
  $con->next_result();
  $today = date('Y-m-d 16:00:00');
  $query_result = mysqli_query($con, "SELECT COUNT(tasks_details.id) as total_tasks, (SELECT COUNT(tasks_details.id) FROM tasks_details JOIN sections ON sections.sec_id=tasks_details.task_for WHERE tasks_details.task_status=1 AND tasks_details.status='REVIEW' AND sections.dept_id='$dept_id') as for_review_tasks, (SELECT COUNT(tasks_details.id) FROM tasks_details JOIN sections ON sections.sec_id=tasks_details.task_for WHERE tasks_details.task_status=1 AND tasks_details.status='RESCHEDULE' AND sections.dept_id='$dept_id') as for_resched_tasks, (SELECT COUNT(tasks_details.id) FROM tasks_details JOIN sections ON sections.sec_id=tasks_details.task_for WHERE tasks_details.task_status=1 AND sections.dept_id='$dept_id' AND tasks_details.status='PROJECT') as project_tasks, (SELECT COUNT('accounts.id') FROM accounts JOIN sections ON sections.sec_id=accounts.sec_id WHERE dept_id='$dept_id' AND access=2) as members, (SELECT COUNT(td.id) FROM tasks_details td JOIN sections s ON s.sec_id=td.task_for WHERE td.task_status=1 AND DATE(td.due_date)=CURRENT_DATE() AND td.status IN ('FINISHED', 'REVIEW') AND s.dept_id='$dept_id') as ftasks, (SELECT COUNT(td.id) FROM tasks_details td JOIN sections s ON s.sec_id=td.task_for WHERE td.task_status=1 AND DATE(td.due_date)=CURRENT_DATE() AND td.status NOT IN ('FINISHED', 'REVIEW') AND s.dept_id='$dept_id') as utasks FROM tasks_details JOIN sections ON sections.sec_id=tasks_details.task_for WHERE tasks_details.task_status=1 AND sections.dept_id='$dept_id'");
  $row = mysqli_fetch_assoc($query_result);
  $total_tasks       = $row['total_tasks'];
  $project_tasks     = $row['project_tasks'];
  $for_review_tasks  = $row['for_review_tasks'];
  $for_resched_tasks = $row['for_resched_tasks'];
  $ftasks            = $row['ftasks'];
  $utasks            = $row['utasks'];
  $members           = $row['members'];
}

$db_size_query = mysqli_query($con, "SELECT SUM(data_length + index_length) AS size FROM information_schema.TABLES WHERE table_schema='gtms'");
$row    = $db_size_query->fetch_assoc();
$size   = $row['size'] ? $row['size'] : 0;
$units  = ['B', 'KB', 'MB', 'GB', 'TB'];
$db_size = formatSize($size);

$projectDir   = 'C:\xampp\htdocs\GTMS';
$projectSize  = formatSize(getDirectorySize($projectDir));

$query_result = mysqli_query($con, "SELECT COUNT(id) AS total_notification FROM notification WHERE user='$username' AND status=1");
$row = mysqli_fetch_assoc($query_result);
$total_notification = $row['total_notification'];

$query_result = mysqli_query($con, "SELECT COUNT(id) AS file_counter FROM task_files WHERE file_owner='$username'");
$row = mysqli_fetch_assoc($query_result);
$file_counter = $row['file_counter'];

// End of Query
