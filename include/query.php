<?php
date_default_timezone_set('Asia/Manila');

// Use fo System Logs
mysqli_query($con, "SET @current_user = '$username'");

// Functions
function getTaskClass($taskClassNumber)
{
  $taskClasses = [1 => ['DAILY ROUTINE', 'light'], 2 => ['WEEKLY ROUTINE', 'light'], 3 => ['MONTHLY ROUTINE', 'danger'], 4 => ['ADDITIONAL TASK', 'info'], 5 => ['PROJECT', 'light'], 6 => ['MONTHLY REPORT', 'danger']];
  return '<span class="badge badge-pill badge-' . ($taskClasses[$taskClassNumber][1] ?? 'secondary') . '">' . ($taskClasses[$taskClassNumber][0] ?? 'Unknown') . '</span>';
}

function getUser($username)
{
  global $con;
  $user = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM accounts WHERE username='$username'"));
  $name = empty($user['fname']) && empty($user['lname']) ? $user['username'] : ucwords(strtolower($user['fname'] . ' ' . $user['lname']));
  $image = empty($user["file_name"]) ? '../assets/img/user-profiles/nologo.png' : '../assets/img/user-profiles/' . $user["file_name"];
  $userDetails = "<img src='$image' class='img-table' data-toggle='tooltip' data-placement='top' title='{$user['username']}'>$name";

  return $userDetails;
}

function getName($username)
{
  global $con;
  $user = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM accounts WHERE username='$username'"));
  $name = ucwords(strtolower($user['fname'] . ' ' . $user['lname']));

  return $name;
}

function getPercentage($average)
{
  if ($average == 5.0) {
    return 105;
  } elseif ($average >= 4.0 && $average <= 4.99) {
    return 100 + (($average - 4.0) / (4.99 - 4.0)) * (104 - 100);
  } elseif ($average >= 3.0 && $average <= 3.99) {
    return 90 + (($average - 3.0) / (3.99 - 3.0)) * (99 - 90);
  } elseif ($average >= 2.0 && $average <= 2.99) {
    return 80 + (($average - 2.0) / (2.99 - 2.0)) * (89 - 80);
  } elseif ($average >= 0.0 && $average <= 1.99) {
    return 70 + (($average - 0.0) / (1.99 - 0.0)) * (79 - 70);
  } else {
    return 0;
  }
}

function getProgressBadge($status)
{
  $badgeClasses = [
    'NOT YET STARTED' => 'badge-success',
    'IN PROGRESS' => 'badge-danger',
    'REVIEW' => 'badge-warning',
    'FINISHED' => 'badge-primary',
    'RESCHEDULE' => 'badge-secondary'
  ];

  $badgeClass = isset($badgeClasses[$status]) ? $badgeClasses[$status] : 'badge-secondary';

  return "<span class='badge badge-pill {$badgeClass}'>{$status}</span>";
}
// End of Functions

// Server Database Statistics
$con->next_result();
$db_size_query = mysqli_query($con, "SELECT SUM(data_length + index_length) AS size FROM information_schema.TABLES WHERE table_schema='$db_database'");
$row    = $db_size_query->fetch_assoc();
$size   = $row['size'] ? $row['size'] : 0;
$units  = ['B', 'KB', 'MB', 'GB', 'TB'];
$db_size = formatDBSize($size, $units);

// Project Size Statistics
$rootDir = __DIR__;
while (!file_exists($rootDir . '/composer.json') && dirname($rootDir) != $rootDir) {
  $rootDir = dirname($rootDir);
}
$projectSize  = formatSize(getDirectorySize($rootDir));


// Account Profile Picture
$con->next_result();
$query_result = mysqli_query($con, "SELECT *, department.dept_name FROM accounts JOIN section ON section.sec_id=accounts.sec_id JOIN department ON department.dept_id=section.dept_id WHERE accounts.username='$username'");
if (mysqli_num_rows($query_result) > 0) {
  while ($row = $query_result->fetch_assoc()) {
    $fname_temp = strtolower($row['fname']);
    $lname_temp = strtolower($row['lname']);
    $fname      = ucwords($fname_temp);
    $lname      = ucwords($lname_temp);
    $card       = $row['card'];
    $email      = $row['email'];
    $sec        = $row['sec_name'];
    $sec_id     = $row['sec_id'];
    $dept_id    = $row['dept_id'];
    $dept_name  = ucwords(strtolower($row['dept_name']));
    $sec_name   = ucwords(strtolower($sec));
    $fileSRC    = $row['file_name'];
    if (empty($row["file_name"])) {
      $profileURL = '../assets/img/user-profiles/nologo.png';
    } else {
      $profileURL = '../assets/img/user-profiles/' . $row["file_name"];
    }
  }
}

// Account Details
$con->next_result();
$query_result = mysqli_query($con, "SELECT * FROM accounts JOIN section ON section.sec_id=accounts.sec_id WHERE accounts.username='$username'");
while ($row = mysqli_fetch_assoc($query_result)) {
  $full_name_temp = strtolower($row['fname'] . ' ' . $row['lname']);
  $full_name      = ucwords($full_name_temp);
  $section        = ucwords(strtolower($row['sec_name']));
}

// Task Counter
$con->next_result();
$today = date('Y-m-d 16:00:00');
$currentMonth = date('m');
$currentYear = date('Y');
$query_result = mysqli_query($con, "SELECT COUNT(*) AS total_tasks, SUM(CASE WHEN td.status = 'FINISHED' THEN 1 ELSE 0 END) AS completed_tasks, SUM(CASE WHEN td.status != 'FINISHED' THEN 1 ELSE 0 END) AS incomplete_tasks, SUM(CASE WHEN td.status = 'REVIEW' THEN 1 ELSE 0 END) AS review_tasks, SUM(CASE WHEN td.status NOT IN ('REVIEW', 'FINISHED') THEN 1 ELSE 0 END) AS today_tasks FROM tasks t  JOIN tasks_details td ON t.id = td.task_id WHERE td.task_status = 1  AND t.in_charge = '$username'");
$row = mysqli_fetch_assoc($query_result);
$total_tasks      = $row['total_tasks'];
$completed_tasks  = $row['completed_tasks'];
$incomplete_tasks = $row['incomplete_tasks'];
$review_tasks     = $row['review_tasks'];
$today_tasks      = $row['today_tasks'];

if ($access == 3) {
  $con->next_result();
  $today = date('Y-m-d 16:00:00');
  $query_result = mysqli_query($con, "SELECT COUNT(*) AS total_tasks, SUM(CASE WHEN td.status = 'REVIEW' THEN 1 ELSE 0 END) AS for_review_tasks, SUM(CASE WHEN td.status = 'RESCHEDULE' THEN 1 ELSE 0 END) AS for_resched_tasks, SUM(CASE WHEN td.status IN ('FINISHED', 'REVIEW') AND DATE(td.due_date)=CURRENT_DATE() THEN 1 ELSE 0 END) AS ftasks, SUM(CASE WHEN td.status NOT IN ('FINISHED', 'REVIEW') AND DATE(td.due_date)=CURRENT_DATE() THEN 1 ELSE 0 END) AS utasks, COUNT(DISTINCT t.in_charge) AS members FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN section s ON tl.task_for=s.sec_id JOIN tasks t ON tl.id = t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.task_status = 1 AND s.dept_id='$dept_id'");
  $row = mysqli_fetch_assoc($query_result);
  $total_tasks       = $row['total_tasks'];
  $project_tasks     = 0;
  $for_review_tasks  = $row['for_review_tasks'];
  $for_resched_tasks = $row['for_resched_tasks'];
  $ftasks            = $row['ftasks'];
  $utasks            = $row['utasks'];
  $members           = $row['members'];
}

// Server Statistics
$con->next_result();
$db_size_query = mysqli_query($con, "SELECT SUM(data_length + index_length) AS size FROM information_schema.TABLES WHERE table_schema='gtms'");
$row    = $db_size_query->fetch_assoc();
$size   = $row['size'] ? $row['size'] : 0;
$units  = ['B', 'KB', 'MB', 'GB', 'TB'];
function formatDBSize($size, $units)
{
  for ($i = 0; $size >= 1024 && $i < count($units) - 1; $i++) {
    $size /= 1024;
  }
  return round($size, 2) . ' ' . $units[$i];
}

$con->next_result();
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

$projectDir   = 'C:\xampp\htdocs\GTMS';
$projectSize  = formatSize(getDirectorySize($projectDir));

// Notification Counter
$con->next_result();
$query_result = mysqli_query($con, "SELECT COUNT(id) AS total_notification FROM notification WHERE user='$username' AND status=1");
$row = mysqli_fetch_assoc($query_result);
$total_notification = $row['total_notification'];

// Count User Files
$con->next_result();
$query_result = mysqli_query($con, "SELECT COUNT(id) AS file_counter FROM task_files WHERE file_owner='$username'");
$row = mysqli_fetch_assoc($query_result);
$file_counter = $row['file_counter'];

// Activity Logs
function log_action($action)
{
  global $con;
  $datetime = date("Y-m-d H:i:s");
  $username = $_SESSION['SESS_MEMBER_USERNAME'];
  $query_insert = mysqli_query($con, "INSERT INTO system_log (action, date_created, user) VALUES ('$action', '$datetime', '$username')");
}

// Used for Input Type Date Min
$minDay = date('Y-m-d', strtotime('+1 day'));
