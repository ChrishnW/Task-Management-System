<?php
include('../include/auth.php');

function assignee($user, $id)
{
  global $con;

  $userlist = strpos($user, ',') !== false ? explode(',', $user) : [$user];

  $images = [];

  foreach ($userlist as $username) {
    $username = trim($username);
    $task = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tasks WHERE task_id='$id' AND in_charge='$username'"));
    $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT *, CONCAT(accounts.fname,' ',accounts.lname) AS fullName FROM accounts WHERE username='$username'"));
    $image = empty($row["file_name"]) ? '../assets/img/user-profiles/nologo.png' : '../assets/img/user-profiles/' . $row["file_name"];
    $mname = empty(trim($row['fullName'])) ? $row['username'] : $row['fullName'];

    $images[] = "<img src='$image' alt='$username' class='user-table' data-toggle='tooltip' data-placement='top' title='{$mname}' data-id='{$task['id']}' onclick='assignDetails(this)'>";
  }
  $images[] = "<img src='../assets/img/icons/plus.png' class='user-table'>";
  return $images;
}

if (isset($_POST['toggleDetails'])) {
  $query_result = mysqli_query($con, "SELECT tl.*, IFNULL(GROUP_CONCAT(CASE WHEN t.status = 1 THEN t.in_charge END SEPARATOR ', '), NULL) AS in_charge_list, t.id AS tasks_id, t.submission, t.requirement_status FROM tasks t RIGHT JOIN task_list tl ON t.task_id=tl.id WHERE tl.task_for='{$_POST['id']}' GROUP BY tl.task_name");
  $data = array();
  while ($row = mysqli_fetch_assoc($query_result)) {
    if ($row['status'] === '1') {
      $row['status'] = '<i class="fas fa-circle text-success" data-toggle="tooltip" data-placement="right" title="Active"></i>';
    } else {
      $row['status'] = '<i class="fas fa-dot-circle text-danger" data-toggle="tooltip" data-placement="right" title="Inactive"></i>';
    }
    $row['in_charge_list'] = empty($row['in_charge_list']) ? '<img src="../assets/img/icons/plus.png" class="user-table">' : assignee($row['in_charge_list'], $row['id']);
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
  $in_charge = $_POST['username'];
  $id = $_POST['taskID'];
  $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tasks WHERE id='$id'")); ?>
  <div class="row">
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
