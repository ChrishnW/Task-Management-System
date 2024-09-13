<?php
include('../include/auth.php');
date_default_timezone_set('Asia/Manila');

if (isset($_POST['approveTask'])) {
  $id           = $_POST['reschedID'];
  $approveDate  = $_POST['resched_date'];
  $oldDue       = $_POST['resched_dateog'];
  if ($approveDate == '') {
    die('Please fill in the required fields.');
  } else {
    $query_update = mysqli_query($con, "UPDATE `tasks_details` SET `status`='NOT YET STARTED', `due_date`='$approveDate 16:00:00', `old_date`='$oldDue 16:00:00' WHERE `id`='$id'");
    if ($query_update) {
      echo "Success";
    }
  }
}

if (isset($_POST['viewTask'])) {
  $id = $_POST['taskID'];
  $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT DISTINCT tasks_details.*, tasks.task_details, CONCAT(accounts.fname,' ',accounts.lname) AS Mname FROM tasks_details JOIN accounts ON tasks_details.in_charge = accounts.username JOIN tasks ON tasks_details.task_name = tasks.task_name WHERE tasks_details.id='$id'")); ?>
  <form id="approveRequest" enctype="multipart/form-data">
    <input type="hidden" id="reschedID" name="reschedID" value="<?php echo $row['id']; ?>">
    <label for="">Assignee:</label>
    <div class="input-group mb-2">
      <div class="input-group-prepend">
        <div class="input-group-text"><i class="fas fa-user"></i></div>
      </div>
      <input type="text" id="resched_user" name="resched_user" class="form-control" value="<?php echo ucwords(strtolower($row['Mname'])); ?>" readonly>
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
        <div class="input-group-text"><i class="fas fa-calendar"></i></div>
      </div>
      <input type="date" id="resched_dateog" name="resched_dateog" class="form-control" value="<?php echo date('Y-m-d', strtotime($row['due_date'])); ?>" readonly>
    </div>
    <label for="">Requested Due Date:</label>
    <?php if (new DateTime(date('Y-m-d H:i:s')) > new DateTime($row['old_date'])) {
      echo '<br><small class="text-danger font-italic">The requested date is already in the past. Please choose a new date.</small>';
    } ?>
    <div class="input-group mb-2">
      <div class="input-group-prepend">
        <div class="input-group-text"><i class="fas fa-calendar"></i></div>
      </div>
      <input type="date" id="resched_date" name="resched_date" class="form-control" value="<?php echo date('Y-m-d', strtotime($row['old_date'])); ?>">
    </div>
    <label for="">Reason:</label>
    <div class="input-group mb-2">
      <div class="input-group-prepend">
        <div class="input-group-text"><i class="fas fa-comment"></i></div>
      </div>
      <textarea name="resched_reason" id="resched_reason" class="form-control" placeholder="Please write your reason for rescheduling here." readonly><?php echo $row['reason']; ?></textarea>
    </div>
  </form>
  <?php
}

if (isset($_POST['approveMultiple'])) {
  $count          = 0;
  $taskIDmultiple = $_POST['checkedIds'];
  $head_name      = $_POST['head_name'];
  foreach ($taskIDmultiple as $taskID) {
    $query_result = mysqli_query($con, "UPDATE tasks_details SET status='FINISHED', head_name='$head_name' WHERE id='$taskID'");
    $count += 1;
  }
  if ($count != 0) {
    echo "Success";
  } else {
    echo "Unable to complete the operation. Please try again later.";
  }
}

if (isset($_POST['filterTable'])) {
  $taskClass  = $_POST['taskClass'];
  $date_to    = $_POST['date_to'];
  $date_from  = $_POST['date_from'];

  $query = "SELECT DISTINCT tasks_details.*, accounts.file_name, tasks.task_details, section.dept_id, CONCAT(accounts.fname,' ',accounts.lname) AS Mname FROM tasks_details JOIN accounts ON tasks_details.in_charge = accounts.username JOIN tasks ON tasks_details.task_name = tasks.task_name JOIN section ON tasks_details.task_for = section.sec_id WHERE tasks_details.task_status=1 AND tasks_details.status='REVIEW' AND section.dept_id = '$dept_id'";
  if ($date_to != NULL && $date_from != NULL) {
    $query .= " AND DATE(tasks_details.date_accomplished) >= '$date_to' AND DATE(tasks_details.date_accomplished) <= '$date_from'";
  }
  if ($taskClass != '') {
    $query .= " AND tasks_details.task_class='$taskClass'";
  }
  $result = mysqli_query($con, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = $result->fetch_assoc()) {
      if (empty($row['file_name'])) {
        $assigneeURL = '../assets/img/user-profiles/nologo.png';
      } else {
        $assigneeURL = '../assets/img/user-profiles/' . $row['file_name'];
      }
      $task_classes = [1 => ['name' => 'DAILY ROUTINE', 'badge' => 'info'], 2 => ['name' => 'WEEKLY ROUTINE', 'badge' => 'info'], 3 => ['name' => 'MONTHLY ROUTINE', 'badge' => 'info'], 4 => ['name' => 'ADDITIONAL TASK', 'badge' => 'info'], 5 => ['name' => 'PROJECT', 'badge' => 'info'], 6 => ['name' => 'MONTHLY REPORT', 'badge' => 'danger']];
      if (isset($task_classes[$row['task_class']])) {
        $class = $task_classes[$row['task_class']]['name'];
        $badge = $task_classes[$row['task_class']]['badge'];
      } else {
        $class = 'Unknown';
        $badge = 'secondary';
      }
      $task_class = '<span class="badge badge-' . $badge . '">' . $class . '</span>';
      $due_date   = date_format(date_create($row['due_date']), "Y-m-d h:i a");
      $date_accomplished  = date_format(date_create($row['date_accomplished']), "Y-m-d h:i a");
      $assignee   = '<img src=' . $assigneeURL . ' class="img-table-solo"> ' . ucwords(strtolower($row['Mname'])) . ''; ?>
      <tr>
        <td><input type="checkbox" name="selected_ids[]" class="form-control" value="<?php echo $row['id']; ?>"></td>
        <td><button type="button" onclick="checkTask(this)" class="btn btn-success btn-sm btn-block" value="<?php echo $row['id'] ?>" data-name="<?php echo $row['task_name'] ?>"><i class="fas fa-bars"></i> Review</button></td>
        <td><?php echo $row['task_code'] ?></td>
        <td><?php echo $row['task_name'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
        <td><?php echo $task_class ?></td>
        <td><?php echo $due_date ?></td>
        <td><?php echo $date_accomplished ?></td>
        <td>
          <?php echo $assignee ?>
        </td>
      </tr>
<?php }
  }
}
?>