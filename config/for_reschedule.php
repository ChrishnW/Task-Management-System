<?php
include('../include/auth.php');
date_default_timezone_set('Asia/Manila');

if (isset($_POST['approveTask'])) {
  $id           = $_POST['reschedID'];
  $approveDate  = $_POST['resched_date'];
  $oldDue       = $_POST['resched_dateog'];
  $dateToday    = date('Y-m-d');
  if ($approveDate == '') {
    die('Please fill in the required fields.');
  }
  if ($approveDate < $dateToday) {
    die('Invalid date. Please choose a date that is not in the past.');
  }
  $query_update = mysqli_query($con, "UPDATE `tasks_details` SET `status`='NOT YET STARTED', `due_date`='$approveDate 16:00:00', `old_date`='$oldDue 16:00:00' WHERE `id`='$id'");
  if ($query_update) {
    echo "Success";
  }
}

if (isset($_POST['rejectTask'])) {
  $id       = $_POST['taskID'];
  $taskUser = $_POST['taskUser'];
  $taskCode = $_POST['taskCode'];
  $reason   = $_POST['reason'];
  $datetime_current = date('Y-m-d H:i:s');

  $query_update = mysqli_query($con, "UPDATE `tasks_details` SET `status`='NOT YET STARTED' WHERE id='$id'");
  if ($query_update) {
    $action = mysqli_real_escape_string($con, "window.location.href='tasks.php';");
    $query_insert = mysqli_query($con, "INSERT INTO `notification` (`user`, `icon`, `type`, `body`, `action`, `date_created`, `status`) VALUES ('$taskUser', 'fas fa-times', 'danger', 'Request for reschedule of task $taskCode has been rejected for the following reason:<br><b>$reason.</b>', '$action', '$datetime_current', '1')");
    if ($query_insert) {
      log_action("You have rejected the reschedule request of user {$taskUser} for task {$taskCode}.");
      echo "Success";
    } else {
      die('An unexpected error has occurred. Please try again.');
    }
  }
}

if (isset($_POST['viewTask'])) {
  $id = $_POST['taskID'];
  $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN section s ON tl.task_for=s.sec_id JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.id='$id'")); ?>
  <form id="approveRequest" enctype="multipart/form-data">
    <input type="hidden" id="reschedID" name="reschedID" value="<?php echo $row['id']; ?>">
    <input type="hidden" id="reschedCode" name="reschedCode" value="<?php echo $row['task_code']; ?>">
    <input type="hidden" id="reschedUser" name="reschedUser" value="<?php echo $row['in_charge']; ?>">
    <label for="">Assignee:</label>
    <div class="input-group mb-2">
      <div class="input-group-prepend">
        <div class="input-group-text"><i class="fas fa-user"></i></div>
      </div>
      <input type="text" id="resched_user" name="resched_user" class="form-control" value="<?php echo getName($row['in_charge']); ?>" readonly>
    </div>
    <label for="">Task Name:</label>
    <div class="input-group mb-2">
      <div class="input-group-prepend">
        <div class="input-group-text"><i class="fas fa-tasks"></i></div>
      </div>
      <input type="text" id="resched_taskName" name="resched_taskName" class="form-control" value="<?php echo $row['task_name']; ?>" readonly>
    </div>
    <label for="">Original Due Date:</label>
    <div class="input-group mb-2">
      <div class="input-group-prepend">
        <div class="input-group-text"><i class="fas fa-calendar-times"></i></div>
      </div>
      <input type="date" id="resched_dateog" name="resched_dateog" class="form-control" value="<?php echo date('Y-m-d', strtotime($row['due_date'])); ?>" readonly>
    </div>
    <label for="">Requested Due Date:</label>
    <?php if (new DateTime(date('Y-m-d H:i:s')) > new DateTime($row['old_date'])) {
      echo '<br><small class="text-danger font-italic">The requested date is already in the past. Please choose a new date.</small>';
    } ?>
    <div class="input-group mb-2">
      <div class="input-group-prepend">
        <div class="input-group-text"><i class="fas fa-calendar-day"></i></div>
      </div>
      <input type="date" id="resched_date" name="resched_date" class="form-control" value="<?php echo date('Y-m-d', strtotime($row['old_date'])); ?>">
    </div>
    <label for="">Reason:</label>
    <textarea name="resched_reason" id="resched_reason" class="form-control" placeholder="Please write your reason for rescheduling here." readonly><?php echo $row['reason']; ?></textarea>
  </form>
  <?php
}

if (isset($_POST['filterTable'])) {
  $taskClass  = $_POST['taskClass'];
  $date_to    = $_POST['date_to'];
  $date_from  = $_POST['date_from'];
  $query = "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN section s ON tl.task_for=s.sec_id JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.task_status=1 AND td.status='RESCHEDULE' AND s.dept_id = '$dept_id'";
  if ($date_from != NULL && $date_to != NULL) {
    $query .= " AND DATE(td.old_date) >= '$date_from' AND DATE(td.old_date) <= '$date_to'";
  }
  if ($taskClass != '') {
    $query .= " AND tl.task_class='$taskClass'";
  }
  $result = mysqli_query($con, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = $result->fetch_assoc()) {
      $due_date = date_format(date_create($row['due_date']), "F d, Y");
      $old_date = date_format(date_create($row['old_date']), "F d, Y"); ?>
      <tr>
        <td><?php echo $row['task_code'] ?></td>
        <td><?php echo $row['task_name'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
        <td><?php echo getTaskClass($row['task_class']); ?></td>
        <td class="text-truncate"><?php echo $due_date ?></td>
        <td class="text-truncate"><?php echo $old_date ?></td>
        <td class="text-truncate"><?php echo getUser($row['in_charge']); ?></td>
        <td class="text-truncate"><button type="button" onclick="checkTask(this)" class="btn btn-primary btn-block" value="<?php echo $row['id'] ?>" data-name="<?php echo $row['task_name'] ?>"><i class="fas fa-envelope-open-text fa-fw"></i> Open</button></td>
      </tr>
<?php }
  }
}
?>