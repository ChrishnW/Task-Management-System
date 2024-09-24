<?php
  date_default_timezone_set('Asia/Manila');
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
  $query_result = mysqli_query($con, "SELECT COUNT(id) as total_tasks,(SELECT COUNT(id) FROM tasks_details WHERE in_charge='$username' AND task_status=1 AND status='FINISHED' AND MONTH(due_date)='$currentMonth' AND YEAR(due_date)='$currentYear') as completed_tasks, (SELECT COUNT(id) FROM tasks_details WHERE in_charge='$username' AND task_status=1 AND status!='FINISHED') as incomplete_tasks, (SELECT COUNT(id) FROM tasks_details WHERE in_charge='$username' AND status='REVIEW' AND task_status=1) as review_tasks, (SELECT COUNT(id) FROM tasks_details WHERE in_charge='$username' AND status='NOT YET STARTED' AND task_status=1 AND due_date='$today') as today_tasks, (SELECT COUNT(id) FROM tasks WHERE in_charge='$username' AND task_class!=4) as all_tasks, (SELECT COUNT(id) FROM tasks_details WHERE status='PROJECT' AND task_status=1 AND in_charge='$username') as project_tasks FROM tasks_details WHERE in_charge='$username' AND task_status=1 AND MONTH(due_date)='$currentMonth' AND YEAR(due_date)='$currentYear'");
  $row = mysqli_fetch_assoc($query_result);
  $total_tasks      = $row['total_tasks'];
  $completed_tasks  = $row['completed_tasks'];
  $incomplete_tasks = $row['incomplete_tasks'];
  $review_tasks     = $row['review_tasks'];
  $today_tasks      = $row['today_tasks'];
  $all_tasks        = $row['all_tasks'];
  $project_tasks    = $row['project_tasks'];

  if ($access == 3) {
    $con->next_result();
    $today = date('Y-m-d 16:00:00');
    $query_result = mysqli_query($con, "SELECT COUNT(tasks_details.id) as total_tasks, (SELECT COUNT(tasks_details.id) FROM tasks_details JOIN section ON section.sec_id=tasks_details.task_for WHERE tasks_details.task_status=1 AND tasks_details.status='REVIEW' AND section.dept_id='$dept_id') as for_review_tasks, (SELECT COUNT(tasks_details.id) FROM tasks_details JOIN section ON section.sec_id=tasks_details.task_for WHERE tasks_details.task_status=1 AND tasks_details.status='RESCHEDULE' AND section.dept_id='$dept_id') as for_resched_tasks, (SELECT COUNT(tasks_details.id) FROM tasks_details JOIN section ON section.sec_id=tasks_details.task_for WHERE tasks_details.task_status=1 AND section.dept_id='$dept_id' AND tasks_details.status='PROJECT') as project_tasks, (SELECT COUNT('accounts.id') FROM accounts JOIN section ON section.sec_id=accounts.sec_id WHERE dept_id='$dept_id' AND access=2) as members, (SELECT COUNT(td.id) FROM tasks_details td JOIN section s ON s.sec_id=td.task_for WHERE td.task_status=1 AND DATE(td.due_date)=CURRENT_DATE() AND td.status IN ('FINISHED', 'REVIEW') AND s.dept_id='$dept_id') as ftasks, (SELECT COUNT(td.id) FROM tasks_details td JOIN section s ON s.sec_id=td.task_for WHERE td.task_status=1 AND DATE(td.due_date)=CURRENT_DATE() AND td.status NOT IN ('FINISHED', 'REVIEW') AND s.dept_id='$dept_id') as utasks FROM tasks_details JOIN section ON section.sec_id=tasks_details.task_for WHERE tasks_details.task_status=1 AND section.dept_id='$dept_id'");
    $row = mysqli_fetch_assoc($query_result);
    $total_tasks       = $row['total_tasks'];
    $project_tasks     = $row['project_tasks'];
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
  $db_size = formatDBSize($size, $units);

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
function log_action($action) {
  global $con;
  $datetime = date("Y-m-d H:i:s");
  $username = $_SESSION['SESS_MEMBER_USERNAME'];
  $query_insert = mysqli_query($con, "INSERT INTO system_log (action, date_created, user) VALUES ('$action', '$datetime', '$username')");
}

// Used for Input Type Date Min
$minDay = date('Y-m-d', strtotime('+1 day'));