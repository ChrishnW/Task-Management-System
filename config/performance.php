<?php
include('../include/auth.php');
if (isset($_POST['calculate'])) {
  $section    = $_POST['section'];
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
      $rows = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS overall_tasks_count, SUM(CASE WHEN tl.task_class!=6 THEN 1 ELSE 0 END) AS routineTotal, SUM(CASE WHEN tl.task_class != 6 THEN td.achievement ELSE 0 END) AS routineSUM, SUM(CASE WHEN tl.task_class=6 THEN 1 ELSE 0 END) AS reportTotal, SUM(CASE WHEN tl.task_class = 6 THEN td.achievement ELSE 0 END) AS reportSUM FROM task_list tl JOIN tasks t ON tl.id = t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.task_status = 1 AND DATE(td.due_date) >= '$date_from' AND DATE(td.due_date) <= '$date_to' AND t.in_charge='$assignee'"));
    } else {
      $rows = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS overall_tasks_count, SUM(CASE WHEN tl.task_class!=6 THEN 1 ELSE 0 END) AS routineTotal, SUM(CASE WHEN tl.task_class != 6 THEN td.achievement ELSE 0 END) AS routineSUM, SUM(CASE WHEN tl.task_class=6 THEN 1 ELSE 0 END) AS reportTotal, SUM(CASE WHEN tl.task_class = 6 THEN td.achievement ELSE 0 END) AS reportSUM FROM task_list tl JOIN tasks t ON tl.id = t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.task_status = 1 AND MONTH(td.due_date) = MONTH(CURRENT_DATE) AND YEAR(td.due_date) = YEAR(CURRENT_DATE) AND t.in_charge='$assignee'"));
    }
    $task_total         = $rows['routineTotal'] + $rows['reportTotal'];
    $routine_average    = $rows['routineTotal'] > 0 ? number_format(($rows['routineSUM'] / $rows['routineTotal']), 2) : 0;
    $routine_percentage = $rows['routineTotal'] > 0 ? number_format(getPercentage($routine_average), 2) : 0;
    $report_average     = $rows['reportTotal'] > 0 ? number_format(($rows['reportSUM'] / $rows['reportTotal']), 2) : 0;
    $report_percentage  = $rows['reportTotal'] > 0 ? number_format(getPercentage($report_average), 2) : 0; ?>
    <tr>
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
    <tbody id='dataTableBody'>
      <?php
      $query = "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE t.in_charge='$id'";
      if (isset($_POST['date_to']) && isset($_POST['date_from'])) {
        $date_to   = $_POST['date_to'];
        $date_from = $_POST['date_from'];
        $query .= " AND DATE(due_date) >= '$date_from' AND DATE(due_date) <= '$date_to'";
      }
      $result = mysqli_query($con, $query);
      if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
          $due_date           = date_format(date_create($row['due_date']), "Y-m-d h:i a");
          $date_accomplished  = !empty($row['date_accomplished']) ? date_format(date_create($row['date_accomplished']), "Y-m-d h:i a") : ""; ?>
          <tr>
            <td><?php echo $row['task_code'] ?></td>
            <td><?php echo $row['task_name']; ?> <i class=" fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
            <td><?php echo getTaskClass($row['task_class']); ?></td>
            <td><?php echo getProgressBadge($row['status']); ?></td>
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
  $id = $_POST['id']; ?>
  <table class="table table-hover" id="ViewFinishedTaskTable" width="100%" cellspacing="0">
    <thead>
      <tr>
        <th class="col-1">Code</th>
        <th class="col-5">Title</th>
        <th class="col-1">Classification</th>
        <th class="col-1">Status</th>
        <th class="col-2">Due Date</th>
        <th class="col-1">Achievement</th>
      </tr>
    </thead>
    <tbody id='dataTableBody'>
      <?php
      $query = "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE t.in_charge='$id' AND td.status='FINISHED'";
      if (isset($_POST['date_to']) && isset($_POST['date_from'])) {
        $date_to   = $_POST['date_to'];
        $date_from = $_POST['date_from'];
        $query .= " AND DATE(due_date) >= '$date_to' AND DATE(due_date) <= '$date_from'";
      }
      $result = mysqli_query($con, $query);
      if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
          $due_date = date_format(date_create($row['due_date']), "F d, Y"); ?>
          <tr>
            <td>
              <center /><?php echo $row['task_code'] ?>
            </td>
            <td><?php echo $row['task_name']; ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
            <td><?php echo getTaskClass($row['task_class']); ?></td>
            <td><?php echo getProgressBadge($row['status']); ?></td>
            <td><?php echo $due_date ?></td>
            <td class="text-center">
              <span class="h5 text-success font-weight-bold"><?php echo $row['achievement'] ?></span>
            </td>
          </tr>
      <?php }
      } ?>
    </tbody>
  </table>
<?php
}
?>