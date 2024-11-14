<?php
include('../include/auth.php');

function assignee($user)
{
  global $con;

  $userlist = strpos($user, ',') !== false ? explode(',', $user) : [$user];

  $images = [];

  foreach ($userlist as $username) {
    $username = trim($username);

    $result = mysqli_query($con, "SELECT *, CONCAT(accounts.fname,' ',accounts.lname) AS fullName FROM accounts WHERE username='$username'");

    if ($row = mysqli_fetch_assoc($result)) {
      $image = empty($row["file_name"]) ? '../assets/img/user-profiles/nologo.png' : '../assets/img/user-profiles/' . $row["file_name"];
    } else {
      $image = '../assets/img/user-profiles/nologo.png';
    }

    $mname = empty($row['fullName']) ? $row['username'] : $row['fullName'];

    $images[] = "<img src='$image' alt='$username' class='user-table' data-toggle='tooltip' data-placement='top' title='{$mname}'>";
  }

  return $images;
}


if (isset($_POST['toggleDetails'])) {
  $query_result = mysqli_query($con, "SELECT tl.*, GROUP_CONCAT(CASE WHEN t.status = 1 THEN t.in_charge END SEPARATOR ', ') AS in_charge_list, t.submission FROM tasks t JOIN task_list tl ON t.task_id=tl.id WHERE tl.task_for='{$_POST['id']}' AND tl.task_class != 4 GROUP BY tl.task_name");
  $data = array();
  while ($row = mysqli_fetch_assoc($query_result)) {
    if ($row['status'] === '1') {
      $row['status'] = '<i class="fas fa-circle text-success" data-toggle="tooltip" data-placement="right" title="Active"></i>';
    } else {
      $row['status'] = '<i class="fas fa-dot-circle text-danger" data-toggle="tooltip" data-placement="right" title="Inactive"></i>';
    }
    $row['in_charge_list'] = assignee($row['in_charge_list']);
    $row['task_class'] = getTaskClass($row['task_class']);
    $data[] = $row;
  }
  echo json_encode($data);
}

if (isset($_POST['editTask'])) {
  $getDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT t.task_id, tl.task_name, tl.task_details, tl.task_for, t.submission, t.requirement_status, tl.task_class, GROUP_CONCAT(CASE WHEN t.status = 1 THEN t.in_charge END SEPARATOR ', ') AS in_charge_list FROM tasks t JOIN task_list tl ON t.task_id=tl.id JOIN task_class tc ON tl.task_class=tc.id WHERE tl.id='{$_POST['id']}' GROUP BY t.task_id"));
  $inchargeList = array_map('trim', explode(',', $getDetails['in_charge_list'])); ?>
  <div class="form-group">
    <label for="editTaskName">Task Name</label>
    <input type="text" class="form-control" id="editTaskName" value="<?php echo $getDetails['task_name']; ?>" placeholder="Enter task name">
  </div>
  <div class="form-group">
    <label for="editTaskDetails">Task Details</label>
    <textarea class="form-control" id="editTaskDetails" rows="3" placeholder="Enter task details"><?php echo $getDetails['task_details']; ?></textarea>
  </div>
  <div class="form-group">
    <label for="editSubmission">Due Date</label>
    <input type="text" class="form-control" id="editSubmission" value="<?php echo $getDetails['submission']; ?>">
  </div>
  <div class="form-group">
    <label for="editAttachment">Require Attachment</label>
    <select class="form-control show-tick" id="editAttachment">
      <option value="1" <?php echo ($getDetails['requirement_status'] == 1) ? 'selected' : ''; ?>>Required</option>
      <option value="0" <?php echo ($getDetails['requirement_status'] == 0) ? 'selected' : ''; ?>>Not Required</option>
    </select>
  </div>
  <div class="form-group">
    <label for="editEmplist">In Charge</label>
    <select class="form-control selectpicker show-tick" data-live-search="true" name="editEmplist[]" id="editEmplist" multiple>
      <?php $getEmp = mysqli_query($con, "SELECT * FROM accounts WHERE sec_id='{$getDetails['task_for']}' AND access=2 ORDER BY fname ASC");
      while ($empRow = mysqli_fetch_assoc($getEmp)) {
        $selected = in_array($empRow['username'], $inchargeList) ? 'selected' : ''; ?>
        <option value="<?php echo $empRow['username']; ?>" <?php echo $selected; ?>><?php echo ucwords(strtolower($empRow['fname'] . ' ' . $empRow['lname'])); ?></option>
      <?php } ?>
    </select>
  </div>
<?php }

if (isset($_POST['updateTask'])) {
  $taskID       = $_POST['id'];
  $taskName     = $_POST['taskName'];
  $taskDetails  = $_POST['taskDetails'];
  $submission   = $_POST['submission'];
  $requirement  = $_POST['editAttachment'];
  if ($taskName !== '' && $taskDetails !== '' && $submission !== '' && !empty($_POST['assignList'])) {
    $queryCheck = mysqli_query($con, "SELECT * FROM `tasks` WHERE `task_id`='$taskID' AND `status`=1 ORDER BY in_charge ASC");
    $assignList = [];
    while ($row = mysqli_fetch_assoc($queryCheck)) {
      $assignList[] = $row['in_charge'];
    }

    $inserted = array_diff($_POST['assignList'], $assignList);
    $removed  = array_diff($assignList, $_POST['assignList']);
    if (!empty($inserted) || !empty($removed)) {
      mysqli_begin_transaction($con);
      $success = true;

      if (!empty($inserted)) {
        foreach ($inserted as $newIncharge) {
          $insertQuery = "INSERT INTO tasks (task_id, requirement_status, in_charge, submission) VALUES ('$taskID', '$requirement', '$newIncharge', '$submission')";
          if (!mysqli_query($con, $insertQuery)) {
            $success = false;
            break;
          }
        }
      }
      if (!empty($removed)) {
        foreach ($removed as $removeIncharge) {
          $updateQuery = "DELETE FROM tasks WHERE task_id = '$taskID' AND in_charge = '$removeIncharge'";
          if (!mysqli_query($con, $updateQuery)) {
            $success = false;
            break;
          }
        }
      }

      if ($success) {
        mysqli_commit($con);
      } else {
        mysqli_rollback($con);
        die("An error occurred. Changes were not applied.");
      }
    }
    $detailsUpdate = mysqli_multi_query($con, "UPDATE `task_list` SET `task_name`='$taskName', `task_details`='$taskDetails' WHERE `id`='$taskID'; UPDATE `tasks` SET `submission`='$submission', `requirement_status`='$requirement' WHERE `task_id`='$taskID'");
    die('Success');
  } else {
    die('Please fill in all the fields.');
  }
}
