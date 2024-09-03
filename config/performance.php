<?php
include('../include/auth.php');
if (isset($_POST['calculate'])) {
  $section    = $_POST['section'];
  $date_to    = $_POST['date_to'];
  $date_from  = $_POST['date_from'];
  function getPercentage($average)
  {
    if ($average == 5.0) {
      return 120;
    } elseif ($average >= 4.0 && $average <= 4.99) {
      return 105 + (($average - 4.0) / (4.99 - 4.0)) * (119 - 105);
    } elseif ($average >= 3.0 && $average <= 3.99) {
      return 95 + (($average - 3.0) / (3.99 - 3.0)) * (104 - 95);
    } elseif ($average >= 2.0 && $average <= 2.99) {
      return 80 + (($average - 2.0) / (2.99 - 2.0)) * (94 - 80);
    } elseif ($average >= 0.0 && $average <= 1.99) {
      return 70 + (($average - 0.0) / (1.99 - 0.0)) * (79 - 70);
    } else {
      return 0;
    }
  }
  $result_temp  = "SELECT accounts.*, section.dept_id, section.sec_name FROM accounts JOIN section ON section.sec_id=accounts.sec_id WHERE section.dept_id='$dept_id' AND accounts.access=2";
  if ($section != NULL) {
    $result_temp .= " AND accounts.sec_id='$section'";
  }
  $result       = mysqli_query($con, $result_temp);
  while ($row = $result->fetch_assoc()) {
    if (empty($row['file_name'])) {
      $imageURL = '../assets/img/user-profiles/nologo.png';
    } else {
      $imageURL = '../assets/img/user-profiles/' . $row['file_name'];
    }
    $assignee  = $row['username'];

    if ($date_to != NULL && $date_from != NULL) {
      $query     = "SELECT DISTINCT * FROM tasks_details WHERE tasks_details.task_status=1 AND tasks_details.status='FINISHED' AND tasks_details.in_charge='$assignee' AND DATE(due_date) >= '$date_to' AND DATE(due_date) <= '$date_from'";
      $count_task = mysqli_query($con, $query);

      $task_total       = "SELECT DISTINCT COUNT(id) AS task_total FROM tasks_details WHERE in_charge='$assignee' AND task_status=1 AND tasks_details.task_class != 5 AND tasks_details.task_class != 6 AND DATE(due_date) >= '$date_to' AND DATE(due_date) <= '$date_from'";
      $task_total_query = mysqli_query($con, $task_total);
      $task_total_row   = mysqli_fetch_assoc($task_total_query);

      $report_total       = "SELECT DISTINCT COUNT(id) AS report_total FROM tasks_details WHERE in_charge='$assignee' AND task_status=1 AND tasks_details.task_class = 6 AND DATE(due_date) >= '$date_to' AND DATE(due_date) <= '$date_from'";
      $report_total_query = mysqli_query($con, $report_total);
      $report_total_row   = mysqli_fetch_assoc($report_total_query);
    } else {
      $query     = "SELECT DISTINCT * FROM tasks_details WHERE tasks_details.task_status=1 AND tasks_details.status='FINISHED' AND tasks_details.in_charge='$assignee' AND MONTH(tasks_details.due_date) = MONTH(CURRENT_DATE) AND YEAR(tasks_details.due_date) = YEAR(CURRENT_DATE)";
      $count_task = mysqli_query($con, $query);

      $task_total       = "SELECT DISTINCT COUNT(id) AS task_total FROM tasks_details WHERE in_charge='$assignee' AND task_status=1 AND tasks_details.task_class != 5 AND tasks_details.task_class != 6 AND MONTH(tasks_details.due_date) = MONTH(CURRENT_DATE) AND YEAR(tasks_details.due_date) = YEAR(CURRENT_DATE)";
      $task_total_query = mysqli_query($con, $task_total);
      $task_total_row   = mysqli_fetch_assoc($task_total_query);

      $report_total       = "SELECT DISTINCT COUNT(id) AS report_total FROM tasks_details WHERE in_charge='$assignee' AND task_status=1 AND tasks_details.task_class = 6 AND MONTH(tasks_details.due_date) = MONTH(CURRENT_DATE) AND YEAR(tasks_details.due_date) = YEAR(CURRENT_DATE)";
      $report_total_query = mysqli_query($con, $report_total);
      $report_total_row   = mysqli_fetch_assoc($report_total_query);
    }

    $routine_total    = 0;
    $routine_sum      = 0;
    $report_sum       = 0;
    $routine_average  = 0;
    $report_average   = 0;
    while ($count_row = $count_task->fetch_assoc()) {
      if ($count_row['task_class'] != 5 && $count_row['task_class'] != 6) {
        $routine_sum  += $count_row['achievement'];
      }
      if ($count_row['task_class'] == 6) {
        $report_sum += $count_row['achievement'];
      }
      $routine_total    = $task_total_row['task_total'];
      $report_total     = $report_total_row['report_total'];

      if ($routine_total != 0) {
        $routine_average  = number_format(($routine_sum / $routine_total), 2);
        $routine_percentage = number_format(getPercentage($routine_average), 2);
        
      }
      if ($report_total != 0) {
        $report_average   = number_format(($report_sum / $report_total), 2);
        $report_percentage  = number_format(getPercentage($report_average), 2);
      }
    } ?>
    <tr>
      <td></td>
      <td id="td-table"><img src="<?php echo $imageURL; ?>" class="img-table"><?php echo $row['fname'] . ' ' . $row['lname']; ?></td>
      <td><?php echo $row['sec_name']; ?></td>
      <td><center/><span class="badge badge-info"><?php echo $routine_total ?> Total</span></td>
      <td><?php echo $routine_average ?? '0' ?> (Routine) <p class="text-danger"><?php echo $report_average ?? '0' ?> (Report)</p></td>
      <td><?php echo $routine_percentage ?? '0' ?> (Routine) <p class="text-danger"><?php echo $report_percentage ?? '0' ?> (Report)</p></td>
      <td><button class="btn btn-block btn-primary btn-sm"><i class="fas fa-eye fa-fw"></i> View</button></td>
    </tr>
<?php }
}
?>