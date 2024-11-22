<?php
include('../include/auth.php');

function assignee($user, $id)
{
  global $con;

  $userlist = strpos($user, ',') !== false ? explode(',', $user) : [$user];

  $images = [];

  foreach ($userlist as $username) {
    $username = trim($username);
    $task = mysqli_fetch_assoc(mysqli_query($con, "SELECT t.*, tl.id AS mid, tl.task_name FROM tasks t JOIN task_list tl ON t.task_id=tl.id WHERE t.task_id='$id' AND t.in_charge='$username'"));
    $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT *, CONCAT(accounts.fname,' ',accounts.lname) AS fullName FROM accounts WHERE username='$username'"));
    $image = empty($row["file_name"]) ? '../assets/img/user-profiles/nologo.png' : '../assets/img/user-profiles/' . $row["file_name"];
    $mname = empty(trim($row['fullName'])) ? $row['username'] : $row['fullName'];

    $images[] = "<img src='$image' alt='{$mname}' class='user-table' data-toggle='tooltip' data-placement='top' title='{$mname}' data-id='{$task['id']}' data-task='{$task['task_name']}' onclick='assignDetails(this)'>";
  }
  $images[] = "<img src='../assets/img/icons/plus.png' class='user-table' data-toggle='tooltip' data-placement='top' title='Add New Assignee' data-for='{$task['mid']}' onclick='assigneeAdd(this)'>";
  return $images;
}

if (isset($_POST['toggleDetails'])) {
  $query_result = mysqli_query($con, "SELECT tl.*, IFNULL(GROUP_CONCAT(CASE WHEN t.status = 1 THEN t.in_charge END SEPARATOR ', '), NULL) AS in_charge_list, t.id AS tasks_id, t.submission, t.requirement_status FROM tasks t RIGHT JOIN task_list tl ON t.task_id=tl.id WHERE tl.task_for='{$_POST['id']}' AND tl.status = 1 GROUP BY tl.task_name");
  $data = array();
  while ($row = mysqli_fetch_assoc($query_result)) {
    if ($row['status'] === '1') {
      $row['status'] = '<i class="fas fa-circle text-success" data-toggle="tooltip" data-placement="right" title="Active"></i>';
    } else {
      $row['status'] = '<i class="fas fa-dot-circle text-danger" data-toggle="tooltip" data-placement="right" title="Inactive"></i>';
    }
    $row['in_charge_list'] = empty($row['in_charge_list']) ? '<img src="../assets/img/icons/plus.png" class="user-table" data-toggle="tooltip" data-placement="top" title="Add New Assignee" data-for="' . $row['id'] . '" onclick="assigneeAdd(this)">' : assignee($row['in_charge_list'], $row['id']);
    $row['task_class'] = getTaskClass($row['task_class']);
    $row['task_name'] = ($row['requirement_status'] == 1) ? $row['task_name'] . ' <i class="fas fa-photo-video text-warning" data-toggle="tooltip" data-placement="right" title="File Attachment Required"></i>' : $row['task_name'];
    $data[] = $row;
  }
  echo json_encode($data);
}

if (isset($_POST['editTask'])) {
  $getDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT t.task_id, tl.task_name, tl.task_details, tl.task_for, t.submission, t.requirement_status, tl.task_class, GROUP_CONCAT(CASE WHEN t.status = 1 THEN t.in_charge END SEPARATOR ', ') AS in_charge_list FROM tasks t RIGHT JOIN task_list tl ON t.task_id=tl.id JOIN task_class tc ON tl.task_class=tc.id WHERE tl.id='{$_POST['id']}' GROUP BY t.task_id"));
  $inchargeList = array_map('trim', explode(',', $getDetails['in_charge_list'])); ?>
  <div class="row">
    <!-- Task Name -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="editTaskName" class="font-weight-bold">Task Name</label>
        <input type="text" class="form-control" id="editTaskName" value="<?php echo $getDetails['task_name']; ?>" placeholder="Enter task name">
      </div>
    </div>

    <!-- Task Class -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="editClass" class="font-weight-bold">Task Class</label>
        <select name="editClass" id="editClass" class="form-control show-tick" data-style="border-secondary">
          <?php
          $getClass = mysqli_query($con, "SELECT * FROM task_class");
          while ($row = mysqli_fetch_assoc($getClass)) : ?>
            <option value="<?php echo $row['id'] ?>" <?php echo ($getDetails['task_class'] == $row['id']) ? 'selected' : ''; ?>>
              <?php echo ucwords(strtolower($row['task_class'])); ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Task Details -->
    <div class="col-12">
      <div class="form-group">
        <label for="editTaskDetails" class="font-weight-bold">Task Details</label>
        <textarea class="form-control" id="editTaskDetails" rows="4" placeholder="Enter task details"><?php echo $getDetails['task_details']; ?></textarea>
      </div>
    </div>
  </div>
<?php }

if (isset($_POST['updateTask'])) {
  $taskID       = $_POST['id'];
  $taskName     = $_POST['taskName'];
  $taskDetails  = $_POST['taskDetails'];
  $taskClass    = $_POST['taskClass'];
  if ($taskName !== '' && $taskDetails !== '' && $taskClass !== '') {
    $detailsUpdate = mysqli_query($con, "UPDATE `task_list` SET `task_name`='$taskName', `task_details`='$taskDetails', `task_class`='$taskClass' WHERE `id`='$taskID'");
    if ($detailsUpdate) {
      die('Success');
    } else {
      die('Error:' . mysqli_error($con));
    }
  } else {
    die('Please fill in all the fields.');
  }
}

if (isset($_POST['createTask'])) {
  if (empty($_POST['taskName']) || empty($_POST['taskDetails']) || empty($_POST['taskClass']) || empty($_POST['taskFor'])) :
    die('Missing Data!');
  else:
    foreach ($_POST['taskFor'] as $taskFor) :
      $insertQuery = mysqli_query($con, "INSERT INTO task_list (`task_name`, `task_details`, `task_class`, `task_for`) VALUES ('{$_POST['taskName']}', '{$_POST['taskDetails']}', '{$_POST['taskClass']}', '$taskFor')");
      if (!$insertQuery) {
        die('Error creating task.');
        break;
      }
    endforeach;
    die('Success');
  endif;
}

if (isset($_POST['assignDetails'])) {
  $id = $_POST['taskID'];
  $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT t.*, tl.task_details, tl.task_class FROM tasks t JOIN task_list tl ON t.task_id=tl.id WHERE t.id='$id'")); ?>
  <div class="row">
    <input type="hidden" id="editTaskID" value="<?php echo $row['id']; ?>">
    <!-- Task Details -->
    <div class="col-12">
      <div class="form-group">
        <label for="editTaskDetails" class="font-weight-bold">Task Details</label>
        <textarea class="form-control" id="editTaskDetails" rows="4" placeholder="Enter task details" readonly><?php echo $row['task_details']; ?></textarea>
      </div>
    </div>
    <!-- Task Class -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="editClass" class="font-weight-bold">Task Class</label>
        <input type="text" id="editClass" class="form-control" value="<?php echo ($row['task_class'] == 1 ? 'Daily Routine' : ($row['task_class'] == 2 ? 'Weekly Routine' : ($row['task_class'] == 3 ? 'Monthly Routine' : ($row['task_class'] == 4 ? 'Additional Routine' : ($row['task_class'] == 5 ? 'Project' : ($row['task_class'] == 6 ? 'Monthly Report' : 'UNKNOWN TASK CLASS')))))); ?>" readonly>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="ediIncharge" class="font-weight-bold">In Charge</label>
        <input type="text" class="form-control" id="ediIncharge" readonly>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="editSubmission" class="font-weight-bold">Submission</label>
        <input type="text" class="form-control" id="editSubmission" value="<?php echo $row['submission']; ?>" placeholder="Select due date">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="editAttachment" class="font-weight-bold">Require Attachment</label>
        <select class="form-control show-tick" id="editAttachment" data-style="border-secondary">
          <option value="1" <?php echo ($row['requirement_status'] == 1) ? 'selected' : ''; ?>>Required</option>
          <option value="0" <?php echo ($row['requirement_status'] == 0) ? 'selected' : ''; ?>>Not Required</option>
        </select>
      </div>
    </div>
  </div>
<?php }

if (isset($_POST['updateAssignee'])) {
  if (empty($_POST['submission'])) {
    die('Missing Submission Data!');
  } else {
    $submission = trim($_POST['submission']);
    $attachment = $_POST['attachment'];
    $updateQuery = mysqli_query($con, "UPDATE tasks SET submission='$submission', requirement_status='$attachment' WHERE id='{$_POST['id']}'");
    if ($updateQuery) {
      die('Success');
    } else {
      die('Error:' . mysqli_error($con));
    }
  }
}

if (isset($_POST['removeIncharge'])) {
  $deleteQuery = mysqli_query($con, "DELETE FROM tasks WHERE id='{$_POST['id']}'");
  if ($deleteQuery) {
    die('Success');
  } else {
    die('Error:' . mysqli_error($con));
  }
}

if (isset($_POST['assigneeAdd'])) {
  $id = $_POST['taskFor'];
  $task = mysqli_fetch_assoc(mysqli_query($con, "SELECT tl.*, IFNULL(GROUP_CONCAT(CASE WHEN t.status = 1 THEN CONCAT('\"', t.in_charge, '\"') END SEPARATOR ', '), NULL) AS in_charge_list FROM tasks t RIGHT JOIN task_list tl ON t.task_id=tl.id WHERE tl.id='$id' GROUP BY tl.task_name"));
  $incharge_list = $task['in_charge_list'];
  $sec_id = $task['task_for'];
  $task_id = $task['id'];

  $condition = !empty($incharge_list) ? "AND username NOT IN ($incharge_list)" : "";
  $accList = mysqli_query($con, "SELECT *, IF(fname IS NOT NULL AND fname != '' OR lname IS NOT NULL AND lname != '', CONCAT_WS(' ', fname, lname), username) AS mname FROM accounts WHERE status=1 AND access=2 AND sec_id='$sec_id' $condition ORDER BY username ASC"); ?>
  <div class="row">
    <input type="hidden" id="addTaskID" value="<?php echo $task_id; ?>">
    <div class="col-md-12">
      <div class="form-group">
        <label for="addIncharge" class="font-weight-bold">In Charge</label>
        <select id="addIncharge" class="form-control show-tick" data-live-search="true" data-style="border-secondary" multiple>
          <?php while ($row = mysqli_fetch_assoc($accList)) { ?>
            <option value="<?php echo $row['username']; ?>"><?php echo $row['mname']; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="addSubmission" class="font-weight-bold">Submission</label>
        <input type="text" class="form-control" id="addSubmission" placeholder="<?php echo ($task['task_class'] == 2) ? 'Example: Monday, Friday' : (($task['task_class'] == 3 || $task['task_class'] == 6) ? 'Example: 15, 30' : 'Select due date'); ?>" <?php echo ($task['task_class'] == 1) ? 'value="Daily" disabled' : ''; ?>>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="addAttachment" class="font-weight-bold">Require Attachment</label>
        <select class="form-control show-tick" id="addAttachment" data-style="border-secondary">
          <option value="" selected disabled>Nothing selected</option>
          <option value="1">Required</option>
          <option value="0">Not Required</option>
        </select>
      </div>
    </div>
  </div>
<?php }

if (isset($_POST['saveAssigneeAdd'])) {
  if (!empty($_POST['assigneeList']) && !empty($_POST['submission']) && !empty($_POST['requirement'])) {
    $task_id = $_POST['taskid'];
    $submission = $_POST['submission'];
    $requirement = $_POST['requirement'];
    foreach ($_POST['assigneeList'] as $in_charge) {
      $insertTask = mysqli_query($con, "INSERT INTO tasks (`task_id`, `requirement_status`, `in_charge`, `submission`) VALUES ('$task_id', '$requirement', '$in_charge', '$submission')");
      if (!$insertTask) {
        die('Error:' . mysqli_error($con));
        break;
      }
    }
    die('Success');
  } else {
    die('Missing Data field!');
  }
}

if (isset($_POST['deleteTask'])) {
  $id = $_POST['id'];
  $updateQuery = mysqli_query($con, "UPDATE task_list SET status=0 WHERE id='{$_POST['id']}'");
  if ($updateQuery) {
    echo "Success";
  } else {
    echo "Error:" . mysqli_error($con);
  }
}
