<?php
include('../include/auth.php');
if (isset($_POST['calculate'])) {
  $section    = $_POST['section'];
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
  $result_temp = "SELECT accounts.*, section.dept_id, section.sec_name FROM accounts JOIN section ON section.sec_id = accounts.sec_id WHERE section.dept_id='$dept_id' AND accounts.access=2";
  if ($section != NULL) {
    $result_temp .= " AND accounts.sec_id='$section'";
  }
  $result = mysqli_query($con, $result_temp);
  while ($row = $result->fetch_assoc()) {
    $imageURL = empty($row['file_name']) ? '../assets/img/user-profiles/nologo.png' : '../assets/img/user-profiles/' . $row['file_name'];
    $assignee  = $row['username'];
    if (isset($_POST['date_to']) && isset($_POST['date_from'])) {
      $date_to   = $_POST['date_to'];
      $date_from = $_POST['date_from'];
      $rows = mysqli_fetch_assoc(mysqli_query($con, "SELECT *, (SELECT COUNT(id) FROM tasks_details WHERE in_charge = '$assignee' AND task_status = 1 AND DATE(due_date) >= '$date_from' AND DATE(due_date) <= '$date_to' AND task_class != 6) AS routineTotal, (SELECT COUNT(id) FROM tasks_details WHERE in_charge = '$assignee' AND task_status = 1 AND DATE(due_date) >= '$date_from' AND DATE(due_date) <= '$date_to' AND task_class = 6) AS reportTotal, (SELECT SUM(achievement) FROM tasks_details WHERE in_charge = '$assignee' AND task_status = 1 AND DATE(due_date) >= '$date_from' AND DATE(due_date) <= '$date_to' AND task_class != 6) AS routineSUM, (SELECT SUM(achievement) FROM tasks_details WHERE in_charge = '$assignee' AND task_status = 1 AND DATE(due_date) >= '$date_from' AND DATE(due_date) <= '$date_to' AND task_class = 6) AS reportSUM FROM tasks_details WHERE task_status = 1 AND DATE(due_date) >= '$date_from' AND DATE(due_date) <= '$date_to' AND in_charge = '$assignee'"));
    } else {
      $rows = mysqli_fetch_assoc(mysqli_query($con, "SELECT *, (SELECT COUNT(id) FROM tasks_details WHERE in_charge = '$assignee' AND task_status = 1 AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE) AND task_class != 6) AS routineTotal, (SELECT COUNT(id) FROM tasks_details WHERE in_charge = '$assignee' AND task_status = 1 AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE) AND task_class = 6) AS reportTotal, (SELECT SUM(achievement) FROM tasks_details WHERE in_charge = '$assignee' AND task_status = 1 AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE) AND task_class != 6) AS routineSUM, (SELECT SUM(achievement) FROM tasks_details WHERE in_charge = '$assignee' AND task_status = 1 AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE) AND task_class = 6) AS reportSUM FROM tasks_details WHERE task_status = 1 AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE) AND in_charge = '$assignee'"));
    }

    $task_total         = $rows['routineTotal'] + $rows['reportTotal'];
    $routine_average    = $rows['routineTotal'] > 0 ? number_format(($rows['routineSUM'] / $rows['routineTotal']), 2) : 0;
    $routine_percentage = $rows['routineTotal'] > 0 ? number_format(getPercentage($routine_average), 2) : 0;
    $report_average     = $rows['reportTotal'] > 0 ? number_format(($rows['reportSUM'] / $rows['reportTotal']), 2) : 0;
    $report_percentage  = $rows['reportTotal'] > 0 ? number_format(getPercentage($report_average), 2) : 0; ?>
    <tr>
      <td></td>
      <td id="td-table"><img src="<?php echo $imageURL; ?>" class="img-table"><?php echo $row['fname'] . ' ' . $row['lname']; ?></td>
      <td><?php echo $row['sec_name']; ?></td>
      <td id="print-exclude">
        <center /><span class="badge badge-info"><?php echo $task_total ?> Total</span>
      </td>
      <td><?php echo $routine_average ?> (Routine) <p class="text-danger"><?php echo $report_average ?> (Report)</p>
      </td>
      <td><?php echo $routine_percentage ?? '0'; ?> (Routine) <p class="text-danger"><?php echo $report_percentage ?? '0' ?> (Report)</p>
      </td>
      <td id="print-exclude"><button class="btn btn-block btn-primary btn-sm" value="<?php echo $row['id']; ?>" onclick="viewTask(this)"><i class="fas fa-eye fa-fw"></i> View</button></td>
    </tr>
  <?php }
}

if (isset($_POST['viewTask'])) {
  $id = $_POST['account_id']; ?>
  <table class="table table-striped" id="ViewFinishedTaskTable" width="100%" cellspacing="0">
    <thead class='table table-success'>
      <tr>
        <th>Code</th>
        <th>Title</th>
        <th>Classification</th>
        <th>Status</th>
        <th>Due Date</th>
        <th>Date Accomplished</th>
        <th>Achievement</th>
      </tr>
    </thead>
    <tfoot class='table table-success'>
      <tr>
        <th>Code</th>
        <th>Title</th>
        <th>Classification</th>
        <th>Status</th>
        <th>Due Date</th>
        <th>Date Accomplished</th>
        <th>Achievement</th>
      </tr>
    </tfoot>
    <tbody id='dataTableBody'>
      <?php
      $query = "SELECT DISTINCT td.*, t.task_details FROM tasks_details td JOIN accounts ac ON ac.username=td.in_charge JOIN tasks t ON td.task_name=t.task_name WHERE ac.id='$id'";
      if (isset($_POST['date_to']) && isset($_POST['date_from'])) {
        $date_to   = $_POST['date_to'];
        $date_from = $_POST['date_from'];
        $query .= " AND DATE(due_date) >= '$date_from' AND DATE(due_date) <= '$date_to'";
      }
      $result = mysqli_query($con, $query);
      if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
          if (empty($row['file_name'])) {
            $imageURL = '../assets/img/user-profiles/nologo.png';
          } else {
            $imageURL = '../assets/img/user-profiles/' . $row['file_name'];
          }
          $status_badges = [
            'NOT YET STARTED' => 'primary',
            'IN PROGRESS' => 'warning',
            'REVIEW' => 'danger',
            'FINISHED' => 'success',
            'RESCHEDULE' => 'secondary'
          ];
          $task_classes = [1 => ['name' => 'DAILY ROUTINE', 'badge' => 'info'], 2 => ['name' => 'WEEKLY ROUTINE', 'badge' => 'info'], 3 => ['name' => 'MONTHLY ROUTINE', 'badge' => 'info'], 4 => ['name' => 'ADDITIONAL TASK', 'badge' => 'info'], 5 => ['name' => 'PROJECT', 'badge' => 'info'], 6 => ['name' => 'MONTHLY REPORT', 'badge' => 'danger']];
          if (isset($task_classes[$row['task_class']])) {
            $class = $task_classes[$row['task_class']]['name'];
            $badge = $task_classes[$row['task_class']]['badge'];
          } else {
            $class = 'Unknown';
            $badge = 'secondary';
          }
          $task_class         = '<span class="badge badge-' . $badge . '">' . $class . '</span>';
          $due_date           = date_format(date_create($row['due_date']), "Y-m-d h:i a");
          $date_accomplished  = !empty($row['date_accomplished']) ? date_format(date_create($row['date_accomplished']), "Y-m-d h:i a") : "TO BE DETERMINED";
          $assignee           = '<img src=' . $imageURL . ' class="border border-primary img-table-solo" data-toggle="tooltip" data-placement="top" title="' . $row['in_charge'] . '">';
          $progress = '<span class="badge badge-' . $status_badges[$row['status']] . '">' . $row['status'] . '</span>'; ?>
          <tr>
            <td><?php echo $row['task_code'] ?></td>
            <td><?php echo $row['task_name']; ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
            <td><?php echo $task_class ?></td>
            <td><?php echo $progress ?></td>
            <td><?php echo $due_date ?></td>
            <td><?php echo $date_accomplished ?></td>
            <td>
              <center /><span class="d-block display-8"><?php echo $row['achievement'] ?? '0'; ?></span>
            </td>
          </tr>
      <?php }
      } ?>
    </tbody>
  </table>
<?php
}

if (isset($_POST['showPerformance'])) {
  $id = $_POST['account_id']; ?>
  <table class="table table-striped" id="ViewFinishedTaskTable" width="100%" cellspacing="0">
    <thead class='table table-success'>
      <tr>
        <th>Code</th>
        <th>Title</th>
        <th>Classification</th>
        <th>Status</th>
        <th>Due Date</th>
        <th>Date Accomplished</th>
        <th>Achievement</th>
      </tr>
    </thead>
    <tfoot class='table table-success'>
      <tr>
        <th>Code</th>
        <th>Title</th>
        <th>Classification</th>
        <th>Status</th>
        <th>Due Date</th>
        <th>Date Accomplished</th>
        <th>Achievement</th>
      </tr>
    </tfoot>
    <tbody id='dataTableBody'>
      <?php
      $query = "SELECT DISTINCT td.*, t.task_details FROM tasks_details td JOIN accounts ac ON ac.username=td.in_charge JOIN tasks t ON td.task_name=t.task_name WHERE ac.id='$id'";
      if (isset($_POST['date_to']) && isset($_POST['date_from'])) {
        $date_to   = $_POST['date_to'];
        $date_from = $_POST['date_from'];
        $query .= " AND DATE(due_date) >= '$date_to' AND DATE(due_date) <= '$date_from'";
      }
      $result = mysqli_query($con, $query);
      if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
          if (empty($row['file_name'])) {
            $imageURL = '../assets/img/user-profiles/nologo.png';
          } else {
            $imageURL = '../assets/img/user-profiles/' . $row['file_name'];
          }
          $status_badges = [
            'NOT YET STARTED' => 'primary',
            'IN PROGRESS' => 'warning',
            'REVIEW' => 'danger',
            'FINISHED' => 'success',
            'RESCHEDULE' => 'secondary'
          ];
          $task_classes = [1 => ['name' => 'DAILY ROUTINE', 'badge' => 'info'], 2 => ['name' => 'WEEKLY ROUTINE', 'badge' => 'info'], 3 => ['name' => 'MONTHLY ROUTINE', 'badge' => 'info'], 4 => ['name' => 'ADDITIONAL TASK', 'badge' => 'info'], 5 => ['name' => 'PROJECT', 'badge' => 'info'], 6 => ['name' => 'MONTHLY REPORT', 'badge' => 'danger']];
          if (isset($task_classes[$row['task_class']])) {
            $class = $task_classes[$row['task_class']]['name'];
            $badge = $task_classes[$row['task_class']]['badge'];
          } else {
            $class = 'Unknown';
            $badge = 'secondary';
          }
          $task_class         = '<span class="badge badge-' . $badge . '">' . $class . '</span>';
          $due_date           = date_format(date_create($row['due_date']), "Y-m-d h:i a");
          $date_accomplished  = !empty($row['date_accomplished']) ? date_format(date_create($row['date_accomplished']), "Y-m-d h:i a") : "TO BE DETERMINED";
          $assignee           = '<img src=' . $imageURL . ' class="border border-primary img-table-solo" data-toggle="tooltip" data-placement="top" title="' . $row['in_charge'] . '">';
          $progress = '<span class="badge badge-' . $status_badges[$row['status']] . '">' . $row['status'] . '</span>'; ?>
          <tr>
            <td>
              <center /><?php echo $row['task_code'] ?>
            </td>
            <td><?php echo $row['task_name']; ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
            <td><?php echo $task_class ?></td>
            <td><?php echo $progress ?></td>
            <td><?php echo $due_date ?></td>
            <td><?php echo $date_accomplished ?></td>
            <td>
              <center /><span class="d-block display-8"><?php echo $row['achievement'] ?? '0'; ?></span>
            </td>
          </tr>
      <?php }
      } ?>
    </tbody>
  </table>
<?php
}
?>