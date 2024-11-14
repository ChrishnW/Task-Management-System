<?php
include('../include/auth.php');
date_default_timezone_set('Asia/Manila');

if (isset($_POST['changeStatus'])) {
  $query_result = mysqli_query($con, "UPDATE accounts SET status='{$_POST['status']}' WHERE id='{$_POST['id']}'");
  if ($query_result) {
    die('Success');
  } else {
    die('Error');
  }
}

if (isset($_POST['accountEdit'])) {
  $getDetail = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM department d JOIN section s ON d.dept_id=s.dept_id JOIN accounts a ON s.sec_id=a.sec_id WHERE a.id='{$_POST['id']}'")); ?>
  <form id="accountDetails" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $getDetail['id'] ?>">
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="username">Username</label>
        <input type="text" class="form-control uppercase" id="username" name="username" value="<?php echo $getDetail['username']; ?>">
      </div>
      <div class="form-group col-md-6">
        <label for="employeeId">Employee ID</label>
        <input type="text" class="form-control uppercase" id="employeeId" name="employeeId" value="<?php echo $getDetail['card']; ?>">
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="firstName">First Name</label>
        <input type="text" class="form-control capitalize" id="firstName" name="firstName" value="<?php echo $getDetail['fname']; ?>">
      </div>
      <div class="form-group col-md-6">
        <label for="lastName">Last Name</label>
        <input type="text" class="form-control capitalize" id="lastName" name="lastName" value="<?php echo $getDetail['lname']; ?>">
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="systemAccess">System Access</label>
        <select class="form-control" id="systemAccess" name="systemAccess">
          <?php $getAccess = mysqli_query($con, "SELECT * FROM access");
          while ($getAccessRow = mysqli_fetch_assoc($getAccess)) :
            $selected = ($getDetail['access'] === $getAccessRow['id']) ? 'selected' : ''; ?>
            <option value="<?php echo $getAccessRow['id']; ?>" <?php echo $selected ?>><?php echo ucwords($getAccessRow['access']); ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="form-group col-md-6">
        <label for="department">Department</label>
        <input type="text" class="form-control" id="department" name="department" value="<?php echo ucwords(strtolower($getDetail['dept_name'])); ?>" readonly>
      </div>
    </div>
    <div class="form-row <?php if ($getDetail['access'] === '3') echo 'd-none'; ?>">
      <div class="form-group col-md-6">
        <label for="section">Section</label>
        <select class="form-control" name="section" id="section">
          <?php $getSection = mysqli_query($con, "SELECT * FROM section WHERE status=1");
          while ($getSectionRow = mysqli_fetch_assoc($getSection)) :
            $selected = ($getDetail['sec_id'] === $getSectionRow['sec_id']) ? 'selected' : ''; ?>
            <option value="<?php echo $getSectionRow['sec_id']; ?>" <?php echo $selected ?>><?php echo ucwords(strtolower($getSectionRow['sec_name'])); ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="form-group col-md-6">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo $getDetail['email']; ?>">
      </div>
    </div>
  </form>
<?php }

if (isset($_POST['passUpdate'])) {
  $userAcc = $_POST['userAcc'];
  $setPass = password_hash($_POST['setPass'], PASSWORD_DEFAULT);
  if ($_POST['setPass'] != $_POST['conPass']) {
    echo "Passwords do not match.";
  } else {
    $update_query = mysqli_query($con, "UPDATE accounts SET password='$setPass' WHERE username='$userAcc'");
    if ($update_query) {
      log_action("Password changed successfully.");
      echo "Success";
    }
  }
}

if (isset($_POST['checkPassword'])) {
  $check    = $_POST['check'];
  $current  = $_POST['current'];
  if (!password_verify($check, $current)) {
    echo "Failed";
  } else {
    echo "Success";
  }
}

if (isset($_POST['accountReset'])) {
  $id             = $_POST['resetID'];
  $password_temp  = '12345';
  $password       = password_hash($password_temp, PASSWORD_DEFAULT);
  $update_query = mysqli_query($con, "UPDATE accounts SET password='$password' WHERE id='$id'");
  $query_user   = mysqli_query($con, "SELECT * FROM accounts WHERE id='$id'");
  $row = mysqli_fetch_assoc($query_user);
  log_action("Password for user {$row['username']} has been reset to default .");
}

if (isset($_POST['statusUpdate'])) {
  $id     = $_POST['statusID'];
  $status = $_POST['status'];
  if ($status == 1) {
    $status = 0;
  } else {
    $status = 1;
  }
  $update_query = mysqli_query($con, "UPDATE accounts SET status='$status' WHERE id='$id'");
  $query_user   = mysqli_query($con, "SELECT * FROM accounts WHERE id='$id'");
  $row = mysqli_fetch_assoc($query_user);
  log_action("Status of user {$row['username']} changed to {$status}.");
}

if (isset($_POST['accountUpdate'])) {
  $query_update = mysqli_query($con, "UPDATE accounts SET username='{$_POST['username']}', fname='{$_POST['firstName']}', lname='{$_POST['lastName']}', email='{$_POST['email']}', access='{$_POST['systemAccess']}', sec_id='{$_POST['section']}', card='{$_POST['employeeId']}' WHERE id='{$_POST['id']}'");
  if ($query_update) :
    die('Success');
  else :
    die('Unable to complete the operation. Please try again later.');
  endif;
}

if (isset($_POST['accountDelete'])) {
  $query_result = mysqli_query($con, "DELETE FROM `accounts` WHERE id='{$_POST['id']}'");
  if ($query_result) {
    die('Success');
  } else {
    die('Unable to complete the operation. Please try again later.');
  }
}

if (isset($_POST['deleteImage'])) {
  $oldFileName  = $_POST['fileName'];
  $username     = $_POST['userName'];
  $targetDir = "../assets/img/user-profiles/";

  $check_photo = mysqli_query($con, "SELECT file_name FROM accounts WHERE username = '$username'");
  $rowPhoto    = $check_photo->fetch_assoc();
  if ($rowPhoto['file_name'] != '') {
    $remove_result = mysqli_query($con, "UPDATE accounts SET file_name = '' WHERE username = '$username'");
    if ($remove_result) {
      unlink($targetDir . $oldFileName);
      log_action("Image set by user {$username} has been deleted.");
      echo "Success";
    }
  } else {
    echo "No current photo has been set for this account.";
  }
}

if (isset($_POST['uploadImage'])) {
  $fileUser       = $_POST['fileUser'];
  $number         = rand(1000, 9999);
  $targetDir      = "../assets/img/user-profiles/";
  $fileName       = basename($_FILES["image"]["name"]);
  $extension      = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
  $fileName       = $fileUser . "_" . $number . "." . $extension;
  $targetFilePath = $targetDir . $fileName;
  $fileType       = pathinfo($targetFilePath, PATHINFO_EXTENSION);
  $allowTypes     = array('jpg', 'png', 'jpeg');

  if (in_array($fileType, $allowTypes)) {
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
      if ($_FILES["image"]["size"] <= 5e+6) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
          $select = "SELECT file_name FROM accounts WHERE username = '$fileUser'";
          $select_result = mysqli_query($con, $select);
          $row = mysqli_fetch_assoc($select_result);
          $oldFileName = $row['file_name'];
          $insert_result = mysqli_query($con, "UPDATE accounts SET file_name = '$fileName' WHERE username = '$fileUser'");
          if ($insert_result) {
            if ($oldFileName != "" && file_exists($targetDir . $oldFileName)) {
              unlink($targetDir . $oldFileName);
              echo "Success";
            } else {
              echo "Success";
            }
          } else {
            echo "The file failed to upload to the database. Contact the system administrator now.";
          }
        } else {
          echo "The file path directory is not found. Contact the system administrator now.";
        }
      } else {
        echo "The file size is larger than 5 megabyte.";
      }
    } else {
      echo "The file is not genuine.";
    }
  } else {
    echo "File extensions are not supported. Try 'jpg/png/jpeg' types only.";
  }
}

if (isset($_POST['accountCreate'])) {
  if (empty($_POST['newUsername']) || empty($_POST['newSystemAccess']) || empty($_POST['newSection'])) {
    die('Missing Data!');
  } else {
    $newUsername  = strtoupper($_POST['newUsername']);
    $newAccess    = intval($_POST['newSystemAccess']);
    $newSection   = strtoupper($_POST['newSection']);
    $queryCreate = mysqli_query($con, "INSERT INTO accounts (`username`, `access`, `sec_id`) VALUES ('$newUsername', '$newAccess', '$newSection')");
    if ($queryCreate) {
      echo "Success";
    } else {
      echo "The account failed to create. Contact the system administrator now.";
    }
  }
}

if (isset($_POST['viewTaskEmp'])) {
  $userID = $_POST['assignee_id'];
  $query_result = mysqli_query($con, "SELECT * FROM tasks WHERE in_charge='$userID'");
  $dataList = [];
  $counter  = 0;
  while ($row = mysqli_fetch_assoc($query_result)) {
    $counter += 1;
    $task_classes = [
      1 => ['name' => 'DAILY ROUTINE', 'badge' => 'info'],
      2 => ['name' => 'WEEKLY ROUTINE', 'badge' => 'info'],
      3 => ['name' => 'MONTHLY ROUTINE', 'badge' => 'info'],
      4 => ['name' => 'ADDITIONAL TASK', 'badge' => 'info'],
      5 => ['name' => 'PROJECT', 'badge' => 'info'],
      6 => ['name' => 'MONTHLY REPORT', 'badge' => 'danger'],
    ];
    if (isset($task_classes[$row['task_class']])) {
      $class = $task_classes[$row['task_class']]['name'];
      $badge = $task_classes[$row['task_class']]['badge'];
    } else {
      $class = 'Unknown';
      $badge = 'secondary';
    }
    $task_class = '<span class="badge badge-' . $badge . '">' . $class . '</span>';
    if ($row['requirement_status'] == 1) {
      $requirement  = '<span class="badge badge-primary">File Attachment</span>';
    } else {
      $requirement  = '<span class="badge badge-secondary">None</span>';
    }
    $id = $row['id'];
    $action = '
        <button type="button" class="btn btn-danger btn-block" onclick="RemoveTaskView(this)" value="' . $id . '"><i class="fas fa-trash fa-fw"></i> Remove</button>
        <button type="button" class="btn btn-info btn-block" onclick="EditTaskView(this)" value="' . $id . '" data-name="' . $row['task_name'] . '" data-date="' . $row['submission'] . '" data-condition="' . $row['requirement_status'] . '" data-for="' . $row['in_charge'] . '" data-class="' . $class . '"><i class="fas fa-pencil-alt fa-fw"></i> Edit</button>
      ';
    $dataList[] = [
      'counter' => $counter,
      'id' => $action,
      'task_name' => $row['task_name'],
      'task_class' => $task_class,
      'task_details' => $row['task_details'],
      'requirement_status' => $requirement,
      'due_date' => $row['submission'],
    ];
  }
  mysqli_close($con);
  echo json_encode($dataList);
}

if (isset($_POST['editTask'])) {
  $editTask_id          = $_POST['edit_task'];
  $editTaskName         = $_POST['edit_taskName'];
  $editTask_requirement = $_POST['edit_requirement'];
  if (!isset($_POST['edit_duedate'])) {
    die('Empty field has been detected! Please try again.');
  } else {
    $editTask_duedate = $_POST['edit_duedate'];
    if (is_array($editTask_duedate)) {
      $editTask_duedate = implode(', ', $editTask_duedate);
    }
  }
  $query_result = mysqli_query($con, "UPDATE tasks SET requirement_status='$editTask_requirement', submission='$editTask_duedate' WHERE id='$editTask_id'");
  if ($query_result) {
    log_action("Details of registered task {$editTaskName} have been edited.");
    echo "Success";
  }
}

if (isset($_POST['taskDelete'])) {
  $taskID = $_POST['deleteID'];
  $query_get = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id WHERE t.id='$taskID'");
  $row = mysqli_fetch_assoc($query_get);
  $username         = $row['in_charge'];
  $task_name        = $row['task_name'];
  $datetime_current = date('Y-m-d H:i:s');
  $query_result = mysqli_query($con, "DELETE FROM `tasks` WHERE id='$taskID'");
  if ($query_result) {
    log_action("Registered task {$task_name} for user {$row['in_charge']} has been removed.");
    echo "Success";
  } else {
    echo "Unable to complete the operation. Please try again later.";
  }
}

if (isset($_POST['taskList'])) {
  $task_class = $_POST['task_class'];
  $task_for   = $_POST['task_for'];
  $query_result = mysqli_query($con, "SELECT * FROM task_list WHERE status=1 AND task_for='$task_for' AND task_class='$task_class'");
  while ($row = mysqli_fetch_assoc($query_result)) {
    $id           = $row['id'];
    $task_name    = $row['task_name'];
    $task_details = $row['task_details'];
    echo "<option value='$id'>$task_name</option>";
  }
}

if (isset($_GET['taskDownload'])) {
  $assignee = $_GET['username'];
  header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
  header("Content-Disposition: attachment; filename=" . $assignee . "_TASKS.xls");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: private", false); ?>

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  </head>
  <html>

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  </head>

  <body>
    <center>
      <b>TASK MANAGEMENT SYSTEM</b>
      <br>
      <h3><b>ASSIGNED TASKS</b></h3>
      <br>
    </center>

    <div id="table-scroll">
      <table width="100%" border="1" align="left">
        <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Classification</th>
            <th>Details</th>
            <th>Condition</th>
            <th>Due Date</th>
          </tr>
        </thead>

        <tbody>
          <?php
          $result = mysqli_query($con, "SELECT * FROM task_list tl JOIN task_class tc ON tl.task_class=tc.id JOIN tasks t ON tl.id=t.task_id WHERE t.in_charge='$assignee' AND tl.task_class!=4");
          if (mysqli_num_rows($result) > 0) {
            $count = 0;
            while ($row = $result->fetch_assoc()) {
              $count += 1;
              $task_class = '<span class="badge badge-info">' . $row['task_class'] . '</span>';
              if ($row['requirement_status'] == 1) {
                $requirement = '<span class="badge badge-primary">File Attachment</span>';
              } else {
                $requirement = '<span class="badge badge-primary">None</span>';
              } ?>
              <tr>
                <td>
                  <center /><?php echo $count ?>
                </td>
                <td><?php echo $row['task_name'] ?></td>
                <td>
                  <center /><?php echo $task_class ?>
                </td>
                <td style="white-space: nowrap;"><?php echo $row['task_details']; ?></td>
                <td>
                  <center /><?php echo $requirement ?>
                </td>
                <td>
                  <center /><?php echo $row['submission'] ?>
                </td>
              </tr>
          <?php }
          } ?>
        </tbody>
      </table>
    </div>
  </body>

  </html>
<?php
}

if (isset($_POST['selectDepartment'])) {
  $query_result = mysqli_query($con, "SELECT * FROM section WHERE status=1 AND dept_id='{$_POST['id']}'");
  if (mysqli_num_rows($query_result) > 0) {
    while ($row = mysqli_fetch_assoc($query_result)) {
      $sec_id   = $row['sec_id'];
      $sec_name = $row['sec_name'];
      echo "<option value='$sec_id' data-subtext='$sec_id'>" . ucwords(strtolower($sec_name)) . "</option>";
    }
  } else {
    echo "<option value='' selected>No Registered Section</option>";
  }
}

if (isset($_POST['assignTask'])) {
  $in_charge  = $_POST['assigneeID'];
  $sec_id     = $_POST['assigneeSEC'];
  $task_class = $_POST['assignClass'];
  $taskArray  = $_POST['assignList'];
  $require    = $_POST['assignFile'];
  $due_date   = $_POST['assignDue'];
  $result     = 0;
  $count      = 0;
  if (is_array($due_date)) {
    $due_date = implode(', ', $due_date);
  }
  foreach ($taskArray as $task_id) {
    $query_check = mysqli_query($con, "SELECT * FROM tasks WHERE task_id='$task_id' AND in_charge='$in_charge'");
    if (mysqli_num_rows($query_check) > 0) {
      $result = 1;
    } else {
      $query_get = mysqli_query($con, "SELECT * FROM task_list WHERE id='$task_id'");
      $row = mysqli_fetch_assoc($query_get);
      $task_name = $row['task_name'];
      $query_insert = mysqli_query($con, "INSERT INTO tasks (`task_id`, `requirement_status`, `in_charge`, `submission`) VALUES ('$task_id', '$require', '$in_charge', '$due_date')");
      if ($query_insert) {
        $result = 2;
        $count += 1;
      } else {
        $result = 3;
      }
    }
    if ($result == 1) {
      $result = '<span class="badge badge-warning">Exists</span> ' . $task_name . '<br>';
    } elseif ($result == 2) {
      $result = '<span class="badge badge-success">Success</span> ' . $task_name . '<br>';
    } elseif ($result === 3) {
      $result = $task_name . ' <span class="badge badge-danger">Failed</span> ' . $task_name . '<br>';
    }
    echo $result;
  }
}

if (isset($_POST['getBody'])) { ?>
  <div class="col-md-12">
    <div class="form-group">
      <label>Current Password</label><small class="text-danger d-none" id="incorrect"> Current password is incorrect!</small>
      <input type="password" class="form-control" id="curPass" placeholder="Current Password" onchange="checkPassword(this)" autocomplete="new-password">
    </div>
    <div class="form-group">
      <label>New Password</label><small class="text-danger d-none" id="used"> You already used this password.</small>
      <input type="password" class="form-control" id="newPass" placeholder="New Password" onchange="newPassword(this)" readonly>
    </div>
    <div class="form-group">
      <label>Confirm Password</label><small class="text-danger d-none" id="notmatch"> Passwords do not match!</small>
      <input type="password" class="form-control" id="conPass" placeholder="Confirm Password" onchange="conPassword(this)" readonly>
    </div>
    <p class="text-danger font-weight-bold font-italic display-9">Please review your changes. After confirmation, you will be logged out to apply the updates.</p>
  </div>
<?php
}

if (isset($_POST['editAccount'])) {
  $queryUpdate = mysqli_query($con, "UPDATE accounts SET `fname`='{$_POST['inputFirstName']}', `lname`='{$_POST['inputLastName']}', `email`='{$_POST['inputEmailAddress']}', `card`='{$_POST['inputCard']}' WHERE username='$username'");
}
?>