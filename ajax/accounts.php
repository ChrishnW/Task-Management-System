<?php
include('../include/auth.php');

if (isset($_POST['accountEdit'])) {
  $id = $_POST['accountID'];
  $get = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM departments d JOIN sections s ON s.dept_id=d.dept_id JOIN accounts ac ON ac.sec_id=s.sec_id JOIN access a ON ac.access=a.id WHERE ac.username='$id'"));
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
        <!-- <a class="nav-link" id="v-pills-permission-tab" data-toggle="pill" href="#v-pills-permission" role="tab" aria-controls="v-pills-permission" aria-selected="false">Permission</a> -->
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
              <input type="text" class="form-control" id="departments" value="<?php echo $dept; ?>" readonly>
            </div>
            <?php if (!in_array($lvl, ['admin', 'head'])) { ?>
              <div class="form-group">
                <label for="editSec">Section</label>
                <input type="text" class="form-control" id="sections" value="<?php echo $sec; ?>" readonly>
              </div>
            <?php } ?>
          </form>
          <div class="d-flex justify-content-center w-100">
            <div>
              <button type="button" onclick="detailsUpdate(this)" class="btn btn-success" name="account_update">Save Changes</button>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab">
          <form id="accountSecurity" enctype="multipart/form-data">
            <input type="hidden" id="current-user" name="current-user" value="<?php echo $user; ?>">
            <div class="mb-3">
              <label for="current-password" class="form-label">Current Password</label>
              <input type="password" class="form-control" id="current-password" name="current-password" placeholder="Enter your current password">
            </div>
            <div class="mb-3">
              <label for="new-password" class="form-label">New Password</label>
              <input type="password" class="form-control" id="new-password" name="new-password" placeholder="Enter your new password" oninput="checkPasswordStrength()">
            </div>
            <div class="progress">
              <div id="strength-bar" class="progress-bar strength-meter" role="progressbar"></div>
            </div>
            <p id="strength-text" class="mt-2"></p>
          </form>
          <div class="d-flex justify-content-between w-100">
            <div>
              <button type="button" onclick="passwordReset(this)" class="btn btn-primary" name="account_update">Reset Password</button>
            </div>
            <div>
              <button type="button" onclick="passwordUpdate(this)" class="btn btn-success" name="account_update">Update Password</button>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="v-pills-permission" role="tabpanel" aria-labelledby="v-pills-permission-tab">
          <form id="accountPermission" enctype="multipart/form-data">
            <div class="mb-3">
              <div class="form-row">
                <div class="form-group col-3">
                  <label for="user-role">Finish Task</label>
                  <br>
                  <label for="ID_HERE" class="toggle-switchy" data-size="lg" data-style="square" data-color="green">
                    <input type="checkbox" id="ID_HERE" Disabled>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </div>
              </div>
            </div>
          </form>
          <div class="d-flex justify-content-center w-100">
            <div>
              <button type="button" onclick="permissionUpdate(this)" class="btn btn-success" name="account_update">Update Permission</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php }
if (isset($_POST['detailsUpdate'])) {
  $update   = "UPDATE `accounts` SET";
  $updates  = [];
  $check = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM accounts WHERE username='{$_POST['userName']}'"));
  if (empty($_POST['firstName']) || empty($_POST['lastName']) || empty($_POST['email']) || empty($_POST['empID'])) {
    die('All fields must be filled out.');
  }
  $fields = ['fname' => strtoupper($_POST['firstName']), 'lname' => strtoupper($_POST['lastName']), 'email' => $_POST['email'], 'empid' => $_POST['empID'],];
  foreach ($fields as $key => $value) {
    if ($check[$key] != $value) {
      $updates[] .= "$key='$value'";
    }
  }
  if (isset($_POST['imgCon'])) {
    if ($_POST['imgCon'] == '1') {
      $targetDir = "../assets/img/user-profiles/";
      if (file_exists($targetDir . $_POST['curImg'])) {
        if (unlink($targetDir . $_POST['curImg'])) {
          $updates[] = " img='nologo.png'";
        } else {
          die('File does not exist.');
        }
      } else {
        $updates[] = " img='nologo.png'";
      }
    } else if ($_POST['imgCon'] == '2') {
      $targetDir  = "../assets/img/user-profiles/";
      $fileName   = $_POST['userName'] . "_" . rand(1000, 9999) . "." . pathinfo($_FILES["uploadPicture"]["name"], PATHINFO_EXTENSION);
      $FilePath   = $targetDir . $fileName;
      $allowTypes = array('jpg', 'png', 'jpeg');
      if (in_array(pathinfo($FilePath, PATHINFO_EXTENSION), $allowTypes)) {
        if (getimagesize($_FILES["uploadPicture"]["tmp_name"]) !== false) {
          if ($_FILES["uploadPicture"]["size"] <= 5e+6) {
            if (move_uploaded_file($_FILES["uploadPicture"]["tmp_name"], $FilePath)) {
              if ($_POST['curImg'] != "" && file_exists($targetDir . $_POST['curImg'])) {
                if ($_POST['curImg'] != 'nologo.png') {
                  if (unlink($targetDir . $_POST['curImg'])) {
                    $updates[] .= " img='{$fileName}'";
                  } else {
                    die('File does not exist.');
                  }
                } else {
                  $updates[] .= " img='{$fileName}'";
                }
              } else {
                die('The current image file does not exist at the path');
              }
            } else {
              die('Invalid file path.');
            }
          } else {
            die('The file is too large');
          }
        } else {
          die('The file is not an image.');
        }
      } else {
        die('Image extensions are not supported.');
      }
    }
  }
  if (!empty($updates)) {
    $update .= " " . implode(", ", $updates) . "WHERE username='{$_POST['userName']}'";
    $result = mysqli_query($con, $update);
    if ($result) {
      die('Success');
    } else {
      die('Error updating record:' . $con->error);
    }
  } else {
    die('No data to update.');
  }
}
if (isset($_POST['passwordUpdate'])) {
  $currentUser = $_POST['current-user'];
  $currentPass = $_POST['current-password'];
  $newPassword = password_hash($_POST['new-password'], PASSWORD_DEFAULT);
  $get = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM accounts WHERE username='$currentUser'"));
  if ($_POST['current-password'] != '' && $_POST['new-password'] != '') {
    if (strlen($_POST['new-password']) < 6) {
      die('Password must be at least 6 characters long.');
    } else {
      if (password_verify($currentPass, $get['password'])) {
        $updatePass = mysqli_query($con, "UPDATE accounts SET password='$newPassword' WHERE username='$currentUser'");
        if ($updatePass) {
          die('Success');
        } else {
          die('Error updating password:' . $con->error);
        }
      } else {
        die('Incorrect password.');
      }
    }
  } else {
    die('Please fill in both fields.');
  }
}
if (isset($_POST['accountPermission'])) {
  // Under development
}
if (isset($_POST['statusUpdate'])) {
  $user   = $_POST['userName'];
  $status = $_POST['status_value'];
  $changeStatus = mysqli_query($con, "UPDATE accounts SET status='$status' WHERE username='$user'");
  if ($changeStatus) {
    die('Success');
  } else {
    die('Error updating status:' . $con->error);
  }
}
if (isset($_POST['changeAccess'])) {
  $user   = $_POST['user'];
  $access = $_POST['access'];
  $changeAccess = mysqli_query($con, "UPDATE accounts SET access='$access' WHERE username='$user'");
  if ($changeAccess) {
    die('Success');
  } else {
    die('Error updating access:' . $con->error);
  }
}
if (isset($_POST['deptList'])) {
  $id = $_POST['dept_id'];
  $query_result = mysqli_query($con, "SELECT * FROM sections WHERE status=1 AND dept_id='$id'");
  if (mysqli_num_rows($query_result) > 0) {
    while ($row = mysqli_fetch_assoc($query_result)) {
      echo "<option value='{$row['sec_id']}' data-subtext='{$row['sec_id']}'>" . ucwords(strtolower($row['sec_name'])) . "</option>";
    }
  } else {
    echo "<option value='EMPTY' selected>No Registered Section</option>";
  }
}
if (isset($_POST['accountCreate'])) {
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
  $query_result = mysqli_query($con, "SELECT * FROM sections WHERE status=1 AND dept_id='$id'");
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
  $query_result = mysqli_query($con, "SELECT * FROM sections WHERE status=1 AND dept_id='$id'");
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