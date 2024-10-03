<?php
include('../include/auth.php');
date_default_timezone_set('Asia/Manila');

if (isset($_POST['accountEdit'])) {
  $id = $_POST['accountID'];
  $get = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM department d JOIN section s ON s.dept_id=d.dept_id JOIN accounts ac ON ac.sec_id=s.sec_id JOIN access a ON ac.access=a.id WHERE ac.username='$id'"));
  $lvl    = $get['access'];
  $user   = $get['username'];
  $fname  = $get['fname'];
  $lname  = $get['lname'];
  $dept   = $get['dept_name'];
  $sec    = $get['sec_name'];
  $email  = $get['email'];
  $empid  = $get['empid'];
  $img    = empty($get["img"]) ? '../assets/img/user-profiles/nologo.png' : '../assets/img/user-profiles/' . $get["img"]; ?>
  <div class="row">
    <div class="col-3">
      <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <a class="nav-link active" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="true">Profile</a>
        <a class="nav-link" id="v-pills-security-tab" data-toggle="pill" href="#v-pills-security" role="tab" aria-controls="v-pills-security" aria-selected="false">Security</a>
      </div>
    </div>
    <div class="col-9">
      <div class="tab-content" id="v-pills-tabContent">
        <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
          <form id="accountEditForm" enctype="multipart/form-data">
            <div class="text-center mb-3">
              <img id="profileImage" src="<?php echo $img; ?>" alt="Profile Picture" class="profile-image">
            </div>
            <div class="form-group">
              <label for="uploadPicture">Upload Picture</label>
              <div class="input-group">
                <input type="file" class="form-control-file col-10" name="uploadPicture" id="uploadPicture" accept=".png, .jpg">
                <div class="input-group-append">
                  <button class="btn btn-outline-dark btn-sm" type="button" id="removeButton">Remove</button>
                </div>
              </div>
              <small class="form-text text-muted">File type: .png, .jpg (Max file size: 5mb)</small>
            </div>
            <input type="hidden" class="form-control" name="curImg" id="curImg" value="<?php echo $get["img"]; ?>">
            <input type="hidden" class="form-control" name="userName" id="userName" value="<?php echo $user; ?>" readonly>
            <input type="hidden" class="form-control" name="userLvl" id="userLvl" value="<?php echo $lvl; ?>" readonly>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="firstName">First Name</label>
                <input type="text" class="form-control" name="firstName" id="firstName" value="<?php echo $fname; ?>">
              </div>
              <div class="form-group col-md-6">
                <label for="lastName">Last Name</label>
                <input type="text" class="form-control" name="lastName" id="lastName" value="<?php echo $lname; ?>">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-7">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email" value="<?php echo $email; ?>">
              </div>
              <div class="form-group col-md-5">
                <label for="empID">Employee ID</label>
                <input type="text" class="form-control" name="empID" id="empID" value="<?php echo $empid; ?>">
              </div>
            </div>
            <div class="form-group">
              <label for="editDept">Department</label>
              <input type="text" class="form-control" id="department" value="<?php echo $dept; ?>" readonly>
            </div>
            <div class="form-group">
              <label for="editSec">Section</label>
              <input type="text" class="form-control" id="section" value="<?php echo $sec; ?>" readonly>
            </div>
          </form>
          <div class="d-flex justify-content-center w-100">
            <div>
              <button type="button" onclick="detailsUpdate(this)" class="btn btn-success" name="account_update">Save Changes</button>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab">
          <form id="accountSecurity" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="current-password" class="form-label">Current Password</label>
              <input type="password" class="form-control" id="current-password" placeholder="Enter your current password">
            </div>
            <div class="mb-3">
              <label for="new-password" class="form-label">New Password</label>
              <input type="password" class="form-control" id="new-password" placeholder="Enter your new password">
            </div>
          </form>
          <div class="d-flex justify-content-center w-100">
            <div>
              <button type="button" onclick="passwordUpdate(this)" class="btn btn-success" name="account_update">Update Password</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php }
if (isset($_POST['detailsUpdate'])) {
}
if (isset($_POST['updatePassword'])) {
  $id             = $_POST['accountID'];
  $password_temp  = $_POST['newPassword'];
  $new_password   = password_hash($password_temp, PASSWORD_DEFAULT);
  $query_result = mysqli_query($con, "SELECT * FROM accounts WHERE id = '$id'");
  if (mysqli_num_rows($query_result) > 0) {
    while ($row = mysqli_fetch_assoc($query_result)) {
      $current_password = $row['password'];
      if ($new_password == $current_password) {
        echo "New password cannot be the same as the old password.";
      } else {
        $update_query = mysqli_query($con, "UPDATE accounts SET password='$new_password' WHERE id='$id'");
        log_action("Changed password for user {$row['username']}.");
        echo "Success";
      }
    }
  } else {
    echo "Unable to complete the operation. Please try again later.";
  }
}
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
  $id       = $_POST['updateID'];
  $username = strtoupper($_POST['updateUsername']);
  $fname    = strtoupper($_POST['updateFname']);
  $lname    = strtoupper($_POST['updateLname']);
  $card     = $_POST['updateCard'];
  $access   = $_POST['updateAccess'];
  $sec_id   = $_POST['updateSection'];
  $email    = strtolower($_POST['updateEmail']);
  $update_query = mysqli_query($con, "UPDATE accounts SET username='$username', fname='$fname', lname='$lname', card='$card', access='$access', sec_id='$sec_id', email='$email' WHERE id = '$id'");
  $query_user   = mysqli_query($con, "SELECT * FROM accounts WHERE id='$id'");
  $row = mysqli_fetch_assoc($query_user);
  log_action("Account details of user {$row['username']} have been changed.");
}
if (isset($_POST['accountDelete'])) {
  $id           = $_POST['deleteID'];
  $query_result = mysqli_query($con, "DELETE FROM `accounts` WHERE id='$id'");
  if ($query_result) {
    $query_user   = mysqli_query($con, "SELECT * FROM accounts WHERE id='$id'");
    $row = mysqli_fetch_assoc($query_user);
    log_action("Account for user {$row['username']} has been deleted.");
    echo "Success";
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
  $error    = false;
  $username       = strtoupper($_POST['createUsername']);
  $fname          = strtoupper($_POST['createFname']);
  $lname          = strtoupper($_POST['createLname']);
  $card           = $_POST['createCard'];
  $access         = $_POST['createAccess'];
  $dep_id         = $_POST['createDepartment'];
  $sec_id         = $_POST['createSection'];
  $email          = strtolower($_POST['createEmail']);
  if ($username === '' || $fname === '' || $lname === '' || $card === '' || $access === '' || $dep_id === '' || $sec_id === '' || $email === '') {
    $error = true;
    echo "Please fill in all required fields.";
  }
  if (!$error) {
    $password_temp  = '12345';
    $password       = password_hash($password_temp, PASSWORD_DEFAULT);
    $query_result   = mysqli_query($con, "INSERT INTO `accounts`(`card`, `username`, `password`, `fname`, `lname`, `email`, `access`, `sec_id`, `status`) VALUES ('$card', '$username', '$password', '$fname', '$lname', '$email', '$access', '$sec_id', '1')");
    if ($query_result) {
      log_action("Account for user {$username} has been created.");
      echo "Success";
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
  $query_get = mysqli_query($con, "SELECT * FROM tasks WHERE id='$taskID'");
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
    echo "<option value='$task_name'>$task_name</option>";
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
          $result = mysqli_query($con, "SELECT tasks.task_name, tasks.task_details, tasks.submission, task_class.task_class, tasks.requirement_status FROM tasks JOIN task_class ON task_class.id=tasks.task_class WHERE tasks.in_charge='$assignee' AND tasks.task_class!=4");
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
  $id = $_POST['departmentSelect'];
  $query_result = mysqli_query($con, "SELECT * FROM section WHERE status=1 AND dept_id='$id'");
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
if (isset($_POST['editDepartment'])) {
  $id = $_POST['departmentSelect'];
  $query_result = mysqli_query($con, "SELECT * FROM section WHERE status=1 AND dept_id='$id'");
  if (mysqli_num_rows($query_result) > 0) {
    while ($row = mysqli_fetch_assoc($query_result)) {
      $selected = ($row['sec_id'] == $get['dept_id']) ? "selected" : "";
      echo "<option value='{$row['sec_id']}' data-subtext='{$row['sec_id']}'>" . ucwords(strtolower($row['sec_name'])) . "</option>";
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
  foreach ($taskArray as $task_name) {
    $query_get = mysqli_query($con, "SELECT * FROM task_list WHERE task_name='$task_name'");
    $row = mysqli_fetch_assoc($query_get);
    $task_details = $row['task_details'];
    $query_check = mysqli_query($con, "SELECT * FROM tasks WHERE task_name='$task_name' AND in_charge='$in_charge'");
    if (mysqli_num_rows($query_check) > 0) {
      $result = 1;
    } else {
      $query_insert = mysqli_query($con, "INSERT INTO tasks (`task_name`, `task_class`, `task_details`, `task_for`, `requirement_status`, `in_charge`, `submission`) VALUES ('$task_name', '$task_class', '$task_details', '$sec_id', '$require', '$in_charge', '$due_date')");
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
<?php }
?>