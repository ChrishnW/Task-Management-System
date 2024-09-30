<?php
include('../include/auth.php');
date_default_timezone_set('Asia/Manila');
if (isset($_POST['sectionSelect'])) {
  $id = $_POST['departmentSelect'];
  $query_result = mysqli_query($con, "SELECT * FROM section WHERE status=1 AND dept_id='$id'");
  echo "<option value='' data-subtext='Default' selected>All</option>";
  while ($row = mysqli_fetch_assoc($query_result)) {
    $sec_id   = $row['sec_id'];
    $sec_name = $row['sec_name'];
    echo "<option value='$sec_id' data-subtext='$sec_id' class='text-capitalize'>" . strtolower($sec_name) . "</option>";
  }
}
if (isset($_POST['filterTable'])) {
  $date_from  = $_POST['date_from'];
  $date_to    = $_POST['date_to'];
  $department = isset($_POST['department']) ? $_POST['department'] : '';
  $section    = $_POST['section'];
  $progress   = $_POST['progress'];
  $class      = isset($_POST['class']) ? $_POST['class'] : '';
  $status     = isset($_POST['status']) ? $_POST['status'] : 1;
  $query = "SELECT DISTINCT tasks_details.*, accounts.file_name, tasks.task_details, section.dept_id, CONCAT(accounts.fname,' ',accounts.lname) AS Mname FROM tasks_details JOIN accounts ON tasks_details.in_charge = accounts.username JOIN tasks ON tasks_details.task_name = tasks.task_name JOIN section ON tasks_details.task_for = section.sec_id WHERE task_status = '$status'";
  if ($date_from != NULL && $date_to != NULL) {
    $query .= " AND DATE(due_date) >= '$date_from' AND DATE(due_date) <= '$date_to'";
  } else {
    $query .= " AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE)";
  }
  if ($department != NULL && $section != NULL) {
    $query .= " AND section.dept_id = '$department' AND tasks_details.task_for = '$section'";
  }
  if ($department != NULL) {
    $query .= " AND section.dept_id = '$department'";
  }
  if ($section != NULL) {
    $query .= " AND tasks_details.task_for = '$section'";
  }
  if ($progress != NULL) {
    $query .= " AND tasks_details.status = '$progress'";
  }
  if ($class != NULL) {
    $query .= " AND tasks_details.task_class = '$class'";
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
      $assignee   = '<img src=' . $assigneeURL . ' class="img-table-solo"> ' . ucwords(strtolower($row['Mname'])) . '';
      $status_badges = [
        'NOT YET STARTED' => 'primary',
        'IN PROGRESS' => 'warning',
        'REVIEW' => 'danger',
        'FINISHED' => 'success',
        'RESCHEDULE' => 'secondary'
      ];
      $progress = '<span class="badge badge-' . $status_badges[$row['status']] . '">' . $row['status'] . '</span>'; ?>
      <tr>
        <td>
          <?php if ($access == 1) { ?>
            <button type="button" class="btn btn-info btn-block" onclick="editTask(this)" value="<?php echo $row['id'] ?>"><i class="fas fa-pen fa-fw"></i> Edit</button>
          <?php } ?>
          <button type="button" onclick="viewTask(this)" class="btn btn-primary btn-block" value="<?php echo $row['id'] ?>" data-name="<?php echo $row['task_name'] ?>"><i class="fas fa-eye fa-fw"></i> View</button>
        </td>
        <td>
          <center /><?php echo $row['task_code'] ?>
        </td>
        <td><?php echo $row['task_name'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
        <td><?php echo $task_class ?></td>
        <td><?php echo $due_date ?></td>
        <td><?php echo $assignee ?></td>
        <td><?php echo $progress ?></td>
      </tr>
    <?php }
  }
}
if (isset($_POST['filterTableTask'])) {
  $from   = $_POST['dateFrom'];
  $to     = $_POST['dateTo'];
  $status = ['TODO' => 'NOT YET STARTED', 'INPROGRESS' => 'IN PROGRESS', 'REVIEW' => 'REVIEW', 'FINISHED' => 'FINISHED', 'RESCHEDULE' => 'RESCHEDULE'][$_POST['status']];
  function getTaskClass($taskClassNumber)
  {
    $taskClasses = [1 => ['DAILY ROUTINE', 'info'], 2 => ['WEEKLY ROUTINE', 'info'], 3 => ['MONTHLY ROUTINE', 'info'], 4 => ['ADDITIONAL TASK', 'info'], 5 => ['PROJECT', 'info'], 6 => ['MONTHLY REPORT', 'danger']];
    return '<span class="badge badge-' . ($taskClasses[$taskClassNumber][1] ?? 'secondary') . '">' . ($taskClasses[$taskClassNumber][0] ?? 'Unknown') . '</span>';
  }
  if ($from !== '' && $to !== '') {
    if ($status == 'NOT YET STARTED' || $status == 'IN PROGRESS' || $status == 'RESCHEDULE') {
      $query_result = mysqli_query($con, "SELECT DISTINCT tasks_details.*, accounts.file_name, tasks.task_details, section.dept_id, CONCAT(accounts.fname,' ',accounts.lname) AS Mname FROM tasks_details JOIN accounts ON tasks_details.in_charge = accounts.username JOIN tasks ON tasks_details.task_name = tasks.task_name JOIN section ON tasks_details.task_for = section.sec_id WHERE tasks_details.task_status = 1 AND tasks_details.status='$status' AND tasks_details.in_charge='$username' AND DATE(due_date) >= '$from' AND DATE(due_date) <= '$to'");
    } else {
      $query_result = mysqli_query($con, "SELECT DISTINCT tasks_details.*, accounts.file_name, tasks.task_details, section.dept_id, CONCAT(accounts.fname,' ',accounts.lname) AS Mname FROM tasks_details JOIN accounts ON tasks_details.in_charge = accounts.username JOIN tasks ON tasks_details.task_name = tasks.task_name JOIN section ON tasks_details.task_for = section.sec_id WHERE tasks_details.task_status = 1 AND tasks_details.status='$status' AND tasks_details.in_charge='$username' AND DATE(date_accomplished) >= '$from' AND DATE(date_accomplished) <= '$to'");
    }
  } else {
    $query_result = mysqli_query($con, "SELECT DISTINCT tasks_details.*, accounts.file_name, tasks.task_details, section.dept_id, CONCAT(accounts.fname,' ',accounts.lname) AS Mname FROM tasks_details JOIN accounts ON tasks_details.in_charge = accounts.username JOIN tasks ON tasks_details.task_name = tasks.task_name JOIN section ON tasks_details.task_for = section.sec_id WHERE tasks_details.task_status = 1 AND tasks_details.status='$status' AND tasks_details.in_charge='$username'");
  }
  if ($status == 'NOT YET STARTED') {
    while ($row = $query_result->fetch_assoc()) {
      $current_date = date('Y-m-d');
      $action       = (date_create(date('Y-m-d', strtotime($row['due_date']))) > date_create($current_date)) ? '<button type="button" class="btn btn-block btn-secondary fa-fw" disabled><i class="fas fa-ban"></i> Pending</button>' : '<button type="button" class="btn btn-block btn-success" value="' . $row['id'] . '" onclick="startTask(this)"><i class="fas fa-play fa-fw"></i> Start</button>';
      $checkbox = '<input type="checkbox" name="selected_ids[]" class="form-control" value="' . (date_create(date('Y-m-d', strtotime($row['due_date']))) > date_create($current_date) ? '' : $row['id']) . '" ' . (date_create(date('Y-m-d', strtotime($row['due_date']))) > date_create($current_date) ? 'disabled' : '') . '>';
      $due_date     = date_format(date_create($row['due_date']), "Y-m-d h:i a");
      $assignee     = '<img src=' . (empty($row['file_name']) ? '../assets/img/user-profiles/nologo.png' : '../assets/img/user-profiles/' . $row['file_name']) . ' class="img-table-solo"> ' . ucwords(strtolower($row['Mname'])) . ''; ?>
      <tr>
        <td><?php echo $checkbox ?></td>
        <td><?php echo $row['task_code'] ?></td>
        <td><?php echo $row['task_name'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
        <td><?php echo getTaskClass($row['task_class']); ?></td>
        <td><?php echo $due_date ?></td>
        <td><?php echo $assignee ?></td>
        <td><?php echo $action ?><button type="button" class="btn btn-block btn-secondary" value="<?php echo $row['id']; ?>" onclick="rescheduleTask(this)"><i class="fas fa-calendar-alt fa-fw"></i> Reschedule</button></td>
      </tr>
    <?php }
  } elseif ($status == 'IN PROGRESS') {
    while ($row = $query_result->fetch_assoc()) {
      $due_date = date_format(date_create($row['due_date']), "Y-m-d h:i a");
      $assignee = '<img src=' . (empty($row['file_name']) ? '../assets/img/user-profiles/nologo.png' : '../assets/img/user-profiles/' . $row['file_name']) . ' class="img-table-solo"> ' . ucwords(strtolower($row['Mname'])) . ''; ?>
      <tr>
        <td><?php echo $row['task_code'] ?></td>
        <td><?php echo $row['task_name'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
        <td><?php echo getTaskClass($row['task_class']); ?></td>
        <td><?php echo $due_date ?></td>
        <td><?php echo $assignee ?></td>
        <td><button type="button" class="btn btn-block btn-danger" value='<?php echo $row['id']; ?>' onclick="endTask(this)"><i class="fas fa-stop fa-fw"></i> Finish</button></td>
      </tr>
    <?php }
  } elseif ($status == 'REVIEW') {
    while ($row = $query_result->fetch_assoc()) {
      $due_date           = date_format(date_create($row['due_date']), "Y-m-d h:i a");
      $date_accomplished  = date_format(date_create($row['date_accomplished']), "Y-m-d h:i a");
      $assignee           = '<img src=' . (empty($row['file_name']) ? '../assets/img/user-profiles/nologo.png' : '../assets/img/user-profiles/' . $row['file_name']) . ' class="img-table-solo"> ' . ucwords(strtolower($row['Mname'])) . ''; ?>
      <tr>
        <td><?php echo $row['task_code'] ?></td>
        <td><?php echo $row['task_name'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
        <td><?php echo getTaskClass($row['task_class']); ?></td>
        <td><?php echo $due_date ?></td>
        <td><?php echo $date_accomplished ?></td>
        <td><?php echo $assignee ?></td>
        <td><button type="button" class="btn btn-block btn-warning" value='<?php echo $row['id']; ?>' onclick="reviewTask(this)"><i class="fas fa-eye fa-fw"></i> View</button></td>
      </tr>
    <?php }
  } elseif ($status == 'FINISHED') {
    while ($row = $query_result->fetch_assoc()) {
      $due_date           = date_format(date_create($row['due_date']), "Y-m-d h:i a");
      $date_accomplished  = date_format(date_create($row['date_accomplished']), "Y-m-d h:i a");
      $assignee           = '<img src=' . (empty($row['file_name']) ? '../assets/img/user-profiles/nologo.png' : '../assets/img/user-profiles/' . $row['file_name']) . ' class="img-table-solo"> ' . ucwords(strtolower($row['Mname'])) . ''; ?>
      <tr>
        <td><?php echo $row['task_code'] ?></td>
        <td><?php echo $row['task_name'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
        <td><?php echo getTaskClass($row['task_class']); ?></td>
        <td><?php echo $due_date ?></td>
        <td><?php echo $date_accomplished ?></td>
        <td><span class="h5 text-success font-weight-bold"><?php echo $row['achievement'] ?></span></td>
        <td><?php echo $assignee ?></td>
        <td><button type="button" class="btn btn-block btn-primary" value='<?php echo $row['id']; ?>' onclick="checkTask(this)"><i class="fas fa-history fa-fw"></i> Details</button></td>
      </tr>
    <?php }
  } elseif ($status == 'RESCHEDULE') {
    while ($row = $query_result->fetch_assoc()) {
      $due_date = date_format(date_create($row['due_date']), "Y-m-d");
      $old_date = date_format(date_create($row['old_date']), "Y-m-d");
      $assignee = '<img src=' . (empty($row['file_name']) ? '../assets/img/user-profiles/nologo.png' : '../assets/img/user-profiles/' . $row['file_name']) . ' class="img-table-solo"> ' . ucwords(strtolower($row['Mname'])) . ''; ?>
      <tr>
        <td><?php echo $row['task_code'] ?></td>
        <td><?php echo $row['task_name'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
        <td><?php echo getTaskClass($row['task_class']); ?></td>
        <td><?php echo $due_date ?></td>
        <td><?php echo $old_date ?></td>
        <td><?php echo $assignee ?></td>
      </tr>
    <?php }
  }
}
if (isset($_POST['editTask'])) {
  $id = $_POST['taskID'];
  $query_result = mysqli_query($con, "SELECT * FROM tasks_details td JOIN tasks t ON t.t_ID=td.task_id JOIN task_list tl ON tl.tl_ID=t.task_id WHERE td_ID='$id'");
  while ($row = mysqli_fetch_assoc($query_result)) {
    $task_classes       = [1 => "DAILY ROUTINE", 2 => "WEEKLY ROUTINE", 3 => "MONTHLY ROUTINE", 4 => "ADDITIONAL TASK", 5 => "PROJECT", 6 => "MONTHLY REPORT"];
    $task_class         = $task_classes[$row['task_class']] ?? "UNKNOWN";
    $due_date           = date_format(date_create($row['due_date']), "F d, Y h:i a");
    $date_accomplished  = date_format(date_create($row['date_accomplished']), "F d, Y h:i a"); ?>
    <div class="row">
      <div class="form-group col-md-4">
        <label>Assignee:</label>
        <input type="hidden" value="<?php echo $row['id'] ?>" name="taskDetailsID" id="taskDetailsID" readonly>
        <input type="text" value="<?php echo $row['in_charge'] ?>" class="form-control" disabled>
      </div>
      <div class="form-group col-md-5">
        <label>Code:</label>
        <input type="text" value="<?php echo $row['task_code'] ?>" class="form-control" disabled>
      </div>
      <div class="form-group col-md-3">
        <label>Achievement:</label>
        <input type="number" value="<?php echo $row['achievement'] ?? "0" ?>" class="form-control" name="update_score" id="update_score">
      </div>
      <div class="form-group col-md-12">
        <label>Title:</label>
        <input type="text" value="<?php echo $row['task_name'] ?>" class="form-control" disabled>
      </div>
      <div class="form-group col-md-5">
        <label>Classification:</label>
        <input type="text" value="<?php echo $task_class ?>" class="form-control" disabled>
      </div>
      <div class="form-group col-md-4">
        <label>Current Progress:</label>
        <select name="update_progress" id="update_progress" class="form-control" <?php if ($row['status'] == 'FINISHED' || $row['status'] == 'REVIEW') {
                                                                                    echo "disabled";
                                                                                  } ?>>
          <?php if ($row['status'] == 'NOT YET STARTED') { ?>
            <option value="NOT YET STARTED" selected>Not Yet Started</option>
            <option value="IN PROGRESS">In-Progress</option>
          <?php } elseif ($row['status'] == 'IN PROGRESS') { ?>
            <option value="NOT YET STARTED">Not Yet Started</option>
            <option value="IN PROGRESS" selected>In-Progress</option>
          <?php } else { ?>
            <option value="<?php echo $row['status'] ?>" selected><?php echo ucwords(strtolower($row['status'])) ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group col-md-3">
        <label>Status:</label>
        <select name="update_status" id="update_status" class="form-control">
          <?php if ($row['task_status'] == 1) { ?>
            <option value="1" selected>Active</option>
            <option value="0">In-Active</option>
          <?php } else { ?>
            <option value="1">Active</option>
            <option value="0" selected>In-Active</option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group col-md-6">
        <label>Set Due Date:</label>
        <input name="update_datetime" id="update_datetime" type="datetime-local" value="<?php echo $row['due_date'] ?>" class="form-control">
      </div>
      <div class="form-group col-md-6">
        <label>Accomplished Date:</label>
        <input type="datetime-local" value="<?php echo $row['date_accomplished'] ?>" class="form-control" disabled>
      </div>
    </div>
  <?php }
}
if (isset($_POST['updateTask'])) {
  $error    = false;
  $id       = $_POST['taskID'];
  $progress = $_POST['progress'];
  $status   = $_POST['status'];

  if ($_POST['datetime'] === '') {
    $error = true;
    echo "Date and Time cannot be empty. Please fill in all required fields.";
  }
  if (!$error) {
    $datetime = str_replace("T", " ", $_POST['datetime']) . ":00";
    $query_result = mysqli_query($con, "UPDATE tasks_details SET status='$progress', due_date='$datetime', task_status='$status' WHERE id='$id'");
    if ($query_result) {
      echo "Success";
    } else {
      echo "Unable to complete the operation. Please try again later.";
    }
  }
}
if (isset($_POST['startTask'])) {
  $id = $_POST['id'];
  $query_result = mysqli_query($con, "UPDATE tasks_details SET progress='IN PROGRESS' WHERE td_ID='$id'");
  if ($query_result) {
    $query_code = mysqli_query($con, "SELECT task_code FROM tasks_details WHERE td_ID='$id'");
    $row = mysqli_fetch_assoc($query_code);
    log_action("Task {$row['task_code']} started.");
    echo "Success";
  } else {
    echo "Unable to complete the operation. Please try again later.";
  }
}
if (isset($_POST['startTaskMultiple'])) {
  $count = 0;
  $taskIDmultiple = $_POST['checkedIds'];
  foreach ($taskIDmultiple as $taskID) {
    $query_result = mysqli_query($con, "UPDATE tasks_details SET progress='IN PROGRESS' WHERE td_ID='$taskID'");
    if ($query_result) {
      $query_code = mysqli_query($con, "SELECT task_code FROM tasks_details WHERE td_ID='$taskID'");
      $row = mysqli_fetch_assoc($query_code);
      log_action("Task {$row['task_code']} started.");
      $count++;
    }
  }
  echo $count > 0 ? "Success" : "Unable to complete the operation. Please try again later.";
}
if (isset($_POST['endTaskDeatails'])) {
  $id = $_POST['taskID'];
  $query_result = mysqli_query($con, "SELECT * FROM tasks_details td JOIN tasks t ON t.t_ID=td.task_id WHERE task_status=1 AND td_ID='$id'");
  while ($row = mysqli_fetch_assoc($query_result)) {
    $require = $row['attachment'];
  }
  if ($require == 1) { ?>
    <input type="hidden" id="finish_taskID" name="finish_taskID" value="<?php echo $id ?>">
    <textarea class="form-control border-dark" rows="5" cols="50" name="taskRemarks" id="taskRemarks" placeholder="Write your remarks for this task..."></textarea>
    <br>
    <center><input type="file" name="file-1[]" id="file-1" class="form-control-file col-sm-5 mb-2" multiple /></center>
    <button type="button" class="btn btn-sm btn-secondary" onclick="resetFileInput()">Remove Selected Files</button>
  <?php } else { ?>
    <input type="hidden" id="finish_taskID" name="finish_taskID" value="<?php echo $id ?>">
    <textarea class="form-control border-dark" rows="5" cols="50" name="taskRemarks" id="taskRemarks" placeholder="Write your remarks for this task..."></textarea>
  <?php }
}
if (isset($_POST['endTask'])) {
  $currentDateTime  = date('Y-m-d H:i:s');
  $id               = $_POST['finish_taskID'];
  $remarks          = str_replace("'", "&apos;", preg_replace('/^\s+|\s+$|\s+(?=\s)/m', '', $_POST['taskRemarks']));
  $query_result = mysqli_query($con, "SELECT * FROM tasks_details td JOIN tasks t ON t.t_ID=td.task_id WHERE td_ID='$id'");
  while ($row = $query_result->fetch_assoc()) {
    $assignee     = $row['in_charge'];
    $task_code    = $row['task_code'];
    $require      = $row['attachment'];
    $due_date     = date_create($row['due_date']);
    $finish_date  = date_create($row['date_accomplished']);
    $days         = date_diff($due_date, $finish_date);
    $interval     = $days->format("%R%a");
    $achievement = 4;
  }
  if ($require == 1) {
    if (empty($_FILES['file-1']['name'][0]) && empty($remarks)) {
      echo "An empty field has been detected!<br>Please ensure to include your remarks and attach a file to submit.";
    } elseif (empty($remarks)) {
      echo "An empty field has been detected!<br>Please ensure to include your remarks for this task.";
    } elseif (empty($_FILES['file-1']['name'][0])) {
      echo "An empty field has been detected!<br>File attachments are required for this task.";
    } elseif (strlen(trim($remarks)) <= 30) {
      echo "The remarks contains fewer than 30 characters (excluding excess whitespace).";
    } else {
      $files      = $_FILES['file-1'];
      $upload_dir = '../files/' . $assignee;
      $targetDir  = "../files/$assignee/";
      if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
      }
      for ($i = 0; $i < count($files['name']); $i++) {
        $original_filename  = $files['name'][$i];
        $filetype           = $files['type'][$i];
        $filesize           = $files['size'][$i];
        $tmpname            = $files['tmp_name'][$i];
        $query = mysqli_query($con, "SELECT * FROM task_files WHERE task_code='$task_code' AND file_name='$original_filename' AND file_owner='$assignee'");
        $check = mysqli_num_rows($query);
        if ($check > 0) {
          die("File upload error, Duplicate file detected!<br>Please upload a different file or filename.");
          break;
        } else {
          $file_extension     = pathinfo($original_filename, PATHINFO_EXTENSION);
          $new_filename       = '[' . $task_code . '] ' . $original_filename;
          $destination        = $upload_dir . '/' . $new_filename;
          if (move_uploaded_file($tmpname, $destination)) {
            $query_update = mysqli_query($con, "UPDATE tasks_details SET progress='REVIEW', date_accomplished='$currentDateTime', achievement='$achievement', remarks='$remarks' WHERE td_ID='$id'");
            $query_insert = mysqli_query($con, "INSERT INTO `task_files`(`task_code`, `file_name`, `file_size`, `file_type`, `file_dated`, `file_owner`, `file_target`) VALUES ('$task_code', '$original_filename', '$filesize', '$file_extension', '$currentDateTime', '$assignee', '$new_filename')");
            if ($query_update && $query_insert) {
              $query_code = mysqli_query($con, "SELECT task_code FROM tasks_details WHERE td_ID='$id'");
            } else {
              die('Unable to complete the operation. Please try again later.');
            }
          } else {
            die('Unable to complete the operation. File storage location is unknown.');
          }
        }
      }
      if ($query_code) {
        $row = mysqli_fetch_assoc($query_code);
        log_action("Task {$row['task_code']} completed and sent for review.");
        die("Success");
      }
    }
  } else {
    if (empty($remarks)) {
      echo "An empty field has been detected!<br>Please ensure to include your remarks for this task.";
    } elseif (strlen(trim($remarks)) <= 30) {
      echo "The remarks contains fewer than 30 characters (excluding excess whitespace).";
    } else {
      $query_update = mysqli_query($con, "UPDATE tasks_details SET progress='REVIEW', date_accomplished='$currentDateTime', achievement='$achievement', remarks='$remarks' WHERE td_ID='$id'");
      if ($query_update) {
        $query_code = mysqli_query($con, "SELECT task_code FROM tasks_details WHERE td_ID='$id'");
        $row = mysqli_fetch_assoc($query_code);
        log_action("Task {$row['task_code']} completed and sent for review.");
        echo "Success";
      }
    }
  }
}
if (isset($_POST['checkTask'])) {
  $id = $_POST['taskID'];
  $query_result = mysqli_query($con, "SELECT * FROM tasks_details td JOIN tasks t ON t.t_ID=td.task_id JOIN task_list tl ON tl.tl_ID=t.task_id WHERE td_ID='$id'");
  while ($row = mysqli_fetch_assoc($query_result)) {
    $task_classes       = [1 => "DAILY ROUTINE", 2 => "WEEKLY ROUTINE", 3 => "MONTHLY ROUTINE", 4 => "ADDITIONAL TASK", 5 => "PROJECT", 6 => "MONTHLY REPORT"];
    $task_class         = $task_classes[$row['task_class']] ?? "UNKNOWN";
    $due_date           = date_format(date_create($row['due_date']), "F d, Y h:i a");
    $date_accomplished  = date_format(date_create($row['date_accomplished']), "F d, Y h:i a"); ?>
    <form id="editDetails" enctype="multipart/form-data">
      <div class="row">
        <input type="hidden" name="taskReview_id" id="taskReview_id" value="<?php echo $row['td_ID'] ?>">
        <input type="hidden" name="taskReview_owner" id="taskReview_owner" value="<?php echo $row['in_charge'] ?>">
        <div class="col-md-3">
          <div class="form-group">
            <label>Code:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-qrcode"></i></div>
              </div>
              <input type="text" class="form-control" name="taskReview_code" id="taskReview_code" value="<?php echo $row['task_code'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-7">
          <div class="form-group">
            <label>Title:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-tag"></i></div>
              </div>
              <input type="text" class="form-control" name="taskReview_title" id="taskReview_title" value="<?php echo $row['task_name'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <label>Achievement:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-trophy"></i></div>
              </div>
              <input type="text" class="form-control" name="taskReview_title" id="taskReview_title" value="<?php echo $row['achievement'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>Due Date:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-calendar-day"></i></div>
              </div>
              <input type="datetime-local" class="form-control" name="taskReview_title" id="taskReview_title" value="<?php echo $row['due_date'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>Date Accomplished:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-calendar-check"></i></div>
              </div>
              <input type="datetime-local" class="form-control" name="taskReview_title" id="taskReview_title" value="<?php echo $row['date_accomplished'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label>Remarks:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-sticky-note"></i></div>
              </div>
              <textarea class="form-control" rows="4" cols="50" name="taskReview_remarks" id="taskReview_remarks" readonly><?php echo $row['remarks'] ?></textarea>
            </div>
          </div>
        </div>
        <div class="col-md-auto">
          <div class="form-group">
            <label>Approved By:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-user"></i></div>
              </div>
              <input type="text" class="form-control" name="taskReview_title" id="taskReview_title" value="<?php echo $row['head_name'] ?>" readonly>
            </div>
          </div>
        </div>
        <?php if ($row['head_note'] != '' || $row['head_note'] != NULL) { ?>
          <div class="col-md-12">
            <div class="form-group">
              <label>Comment:</label>
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fas fa-comments"></i></div>
                </div>
                <textarea class="form-control" rows="3" cols="50" name="taskReview_remarks" id="taskReview_remarks" readonly><?php echo $row['head_note'] ?></textarea>
              </div>
            </div>
          </div>
        <?php }
        if ($row['attachment'] == 1) { ?>
          <div class="col-md-12">
            <div class="form-group">
              <label>Attachments:</label>
              <div class="table-responsive">
                <table id="taskView_table" class="table table-sm table-hover table-borderless">
                  <thead>
                    <tr>
                      <th>File</th>
                      <th>Size</th>
                      <th>Uploaded Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="taskReview_files">
                    <?php
                    $task_code  = $row['task_code'];
                    $query_result = mysqli_query($con, "SELECT * FROM task_files WHERE task_code='$task_code'");
                    while ($row = mysqli_fetch_assoc($query_result)) {
                      $size   = formatSize($row['file_size']);
                      $action = '<button type="button" class="btn btn-circle btn-success" value="' . $row['id'] . '" onclick="downloadFile(this)"><i class="fas fa-file-download"></i></button>';
                      $date = date_format(date_create($row['file_dated']), "Y-m-d h:i a");
                    ?>
                      <tr>
                        <td><?php echo $row['file_name'] ?></td>
                        <td><?php echo $size ?></td>
                        <td><?php echo $date ?></td>
                        <td><?php echo $action ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </form>
  <?php }
}
if (isset($_POST['reviewTask'])) {
  $id = $_POST['taskID'];
  $query_result = mysqli_query($con, "SELECT * FROM tasks_details td JOIN tasks t ON t.t_ID=td.task_id JOIN task_list tl ON tl.tl_ID=t.task_id WHERE td_ID='$id'");
  while ($row = mysqli_fetch_assoc($query_result)) {
    $task_classes       = [1 => "DAILY ROUTINE", 2 => "WEEKLY ROUTINE", 3 => "MONTHLY ROUTINE", 4 => "ADDITIONAL TASK", 5 => "PROJECT", 6 => "MONTHLY REPORT"];
    $task_class         = $task_classes[$row['task_class']] ?? "UNKNOWN";
    $due_date           = date_format(date_create($row['due_date']), "F d, Y h:i a");
    $date_accomplished  = date_format(date_create($row['date_accomplished']), "F d, Y h:i a"); ?>
    <form id="editDetails" enctype="multipart/form-data">
      <div class="row">
        <input type="hidden" name="taskReview_id" id="taskReview_id" value="<?php echo $row['td_ID'] ?>">
        <input type="hidden" name="taskReview_owner" id="taskReview_owner" value="<?php echo $row['in_charge'] ?>">
        <div class="col-md-3">
          <div class="form-group">
            <label>Code:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-qrcode"></i></div>
              </div>
              <input type="text" class="form-control" name="taskReview_code" id="taskReview_code" value="<?php echo $row['task_code'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-7">
          <div class="form-group">
            <label>Title:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-tag"></i></div>
              </div>
              <input type="text" class="form-control" name="taskReview_title" id="taskReview_title" value="<?php echo $row['task_name'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <label>Achievement:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-trophy"></i></div>
              </div>
              <input type="text" class="form-control" name="taskReview_title" id="taskReview_title" value="<?php echo $row['achievement'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="form-group col-md-4">
          <label>Classification:</label>
          <input type="text" value="<?php echo $task_class ?>" class="form-control" disabled>
        </div>
        <div class="form-group col-md-4">
          <label>Due Date:</label>
          <input type="text" value="<?php echo $due_date ?>" class="form-control" disabled>
        </div>
        <div class="form-group col-md-4">
          <label>Date Accomplished:</label>
          <input type="text" value="<?php echo $date_accomplished ?>" class="form-control" disabled>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label>Remarks:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-sticky-note"></i></div>
              </div>
              <textarea class="form-control" rows="4" cols="50" name="taskReview_remarks" id="taskReview_remarks"><?php echo $row['remarks'] ?></textarea>
            </div>
          </div>
        </div>
        <?php if ($row['attachment'] == 1) { ?>
          <div class="col-md-12">
            <div class="form-group">
              <label>Add Attachment:</label>
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fas fa-file-upload"></i></div>
                </div>
                <input class="form-control" type="file" name="taskReview_upload[]" id="taskReview_upload" multiple>
                <button type="button" class="btn btn-secondary" onclick="resetFileInput()">Remove Selected Files</button>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label>Current Attachments:</label>
              <div class="table-responsive">
                <table id="taskReview_table" class="table table-sm table-hover table-borderless">
                  <thead>
                    <tr>
                      <th>File</th>
                      <th>Size</th>
                      <th>Uploaded Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="taskReview_files">
                    <?php
                    $task_code  = $row['task_code'];
                    $query_result = mysqli_query($con, "SELECT * FROM task_files WHERE task_code='$task_code'");
                    while ($row = mysqli_fetch_assoc($query_result)) {
                      $size   = formatSize($row['file_size']);
                      $action = '<button type="button" class="btn btn-circle btn-success" value="' . $row['id'] . '" onclick="downloadFile(this)"><i class="fas fa-file-download"></i></button> <button type="button" class="btn btn-circle btn-danger" value="' . $row['id'] . '" onclick="deleteFile(this)"><i class="fas fa-trash"></i></button>';
                      $date   = date_format(date_create($row['file_dated']), "Y-m-d h:i a");
                    ?>
                      <tr>
                        <td><?php echo $row['file_name'] ?></td>
                        <td><?php echo $size ?></td>
                        <td><?php echo $date ?></td>
                        <td><?php echo $action ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </form>
  <?php }
}
if (isset($_POST['updateDetails'])) {
  $success          = true;
  $currentDateTime  = date('Y-m-d H:i:s');
  $id               = $_POST['taskReview_id'];
  $task_code        = $_POST['taskReview_code'];
  $assignee         = $_POST['taskReview_owner'];
  $remarks          = str_replace("'", "&apos;", $_POST['taskReview_remarks']);
  $query = mysqli_query($con, "SELECT * FROM tasks_details td JOIN tasks t ON t.t_ID=td.task_id WHERE td_ID='$id'");
  while ($row = mysqli_fetch_assoc($query)) {
    $require = $row['attachment'];
  }
  if ($require == 1) {
    $query_update = mysqli_query($con, "UPDATE tasks_details SET remarks='$remarks' WHERE td_ID='$id'");
    $files      = $_FILES['taskReview_upload'];
    $upload_dir = '../files/' . $assignee;
    $targetDir  = "../files/$assignee/";
    if (!file_exists($upload_dir)) {
      mkdir($upload_dir, 0777, true);
    }
    for ($i = 0; $i < count($files['name']); $i++) {
      $original_filename  = $files['name'][$i];
      $filetype           = $files['type'][$i];
      $filesize           = $files['size'][$i];
      $tmpname            = $files['tmp_name'][$i];

      $query = mysqli_query($con, "SELECT * FROM task_files WHERE task_code='$task_code' AND file_name='$original_filename' AND file_owner='$assignee'");
      $check = mysqli_num_rows($query);
      if ($check > 0) {
        echo "File upload error, Duplicate file detected!<br>Please upload a different file or filename.";
        $success = false;
        break;
      } else {
        $file_extension     = pathinfo($original_filename, PATHINFO_EXTENSION);

        $new_filename       = '[' . $task_code . '] ' . $original_filename;
        $destination        = $upload_dir . '/' . $new_filename;

        if (move_uploaded_file($tmpname, $destination)) {
          $query_insert = mysqli_query($con, "INSERT INTO `task_files`(`task_code`, `file_name`, `file_size`, `file_type`, `file_dated`, `file_owner`, `file_target`) VALUES ('$task_code', '$original_filename', '$filesize', '$file_extension', '$currentDateTime', '$assignee', '$new_filename')");
        }
      }
    }
    if ($success) {
      $query_code = mysqli_query($con, "SELECT task_code FROM tasks_details WHERE td_ID='$id'");
      $row = mysqli_fetch_assoc($query_code);
      log_action("Task {$row['task_code']} remarks/files have been edited.");
      echo "Success";
    }
  } else {
    $query_update = mysqli_query($con, "UPDATE tasks_details SET remarks='$remarks' WHERE td_ID='$id'");
    if ($query_update) {
      $query_code = mysqli_query($con, "SELECT task_code FROM tasks_details WHERE td_ID='$id'");
      $row = mysqli_fetch_assoc($query_code);
      log_action("Task {$row['task_code']} remarks have been edited.");
      echo "Success";
    }
  }
}
if (isset($_POST['deleteFile'])) {
  $deleteFileID = $_POST['id'];
  $query_result = mysqli_query($con, "SELECT * FROM task_files WHERE id ='$deleteFileID'");
  while ($row = mysqli_fetch_assoc($query_result)) {
    $fileName = $row['file_target'];
    $assignee = $row['file_owner'];
    $targetDir = "../files/$assignee/";
    $query_remove = mysqli_query($con, "DELETE FROM task_files WHERE id ='$deleteFileID'");
    if ($query_remove) {
      if ($fileName != "" && file_exists($targetDir . $fileName)) {
        unlink($targetDir . $fileName);
      }
      log_action("File {$row['file_name']} deleted from task {$row['task_code']}.");
      echo "Success";
    } else {
      echo "Unable to complete the operation. Please try again later.";
    }
  }
}
if (isset($_GET['downloadFile'])) {
  $id = $_GET['id'];
  $query_result = mysqli_query($con, "SELECT * FROM task_files WHERE id='$id'");
  while ($row = mysqli_fetch_assoc($query_result)) {
    $file     = $row['file_target'];
    $assignee = $row['file_owner'];
    $filePath =  "../files/$assignee/$file";

    if (file_exists($filePath)) {
      log_action("File {$row['file_name']} downloaded from task {$row['task_code']}.");
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename=' . basename($filePath));
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize($filePath));
      flush();
      readfile($filePath);
      exit;
    } else {
      echo 'File does not exist.';
    }
  }
}
if (isset($_POST['viewTask'])) {
  $id = $_POST['taskID'];
  $query_result = mysqli_query($con, "SELECT DISTINCT tasks_details.*, accounts.file_name, tasks.task_details, section.dept_id, CONCAT(accounts.fname,' ',accounts.lname) AS Mname FROM tasks_details JOIN accounts ON tasks_details.in_charge = accounts.username JOIN tasks ON tasks_details.task_name = tasks.task_name JOIN section ON tasks_details.task_for = section.sec_id WHERE tasks_details.id='$id'");
  while ($row = mysqli_fetch_assoc($query_result)) {
    $task_classes       = [1 => "DAILY ROUTINE", 2 => "WEEKLY ROUTINE", 3 => "MONTHLY ROUTINE", 4 => "ADDITIONAL TASK", 5 => "PROJECT", 6 => "MONTHLY REPORT"];
    $task_class         = $task_classes[$row['task_class']] ?? "UNKNOWN";
    $due_date           = date_format(date_create($row['due_date']), "F d, Y h:i a");
    $date_accomplished  = date_format(date_create($row['date_accomplished']), "F d, Y h:i a"); ?>
    <form id="editDetails" enctype="multipart/form-data">
      <div class="row">
        <div class="col-md-5">
          <div class="form-group">
            <label>Assignee:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-user"></i></div>
              </div>
              <input type="text" class="form-control" value="<?php echo ucwords(strtolower($row['Mname'])) ?>" readonly>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <input type="hidden" name="taskReview_id" id="taskReview_id" value="<?php echo $row['id'] ?>">
        <input type="hidden" name="taskReview_owner" id="taskReview_owner" value="<?php echo $row['in_charge'] ?>">
        <div class="col-md-3">
          <div class="form-group">
            <label>Code:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-qrcode"></i></div>
              </div>
              <input type="text" class="form-control" name="taskReview_code" id="taskReview_code" value="<?php echo $row['task_code'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-7">
          <div class="form-group">
            <label>Title:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-tag"></i></div>
              </div>
              <input type="text" class="form-control" name="taskReview_title" id="taskReview_title" value="<?php echo $row['task_name'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <label>Achievement:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-trophy"></i></div>
              </div>
              <input type="text" class="form-control" name="taskReview_title" id="taskReview_title" value="<?php echo $row['achievement'] ?? 'TO BE DETERMINED' ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>Due Date:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-calendar-day"></i></div>
              </div>
              <input type="datetime-local" class="form-control" name="taskReview_title" id="taskReview_title" value="<?php echo $row['due_date'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>Date Accomplished:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-calendar-check"></i></div>
              </div>
              <input type="datetime-local" class="form-control" name="taskReview_title" id="taskReview_title" value="<?php echo $row['date_accomplished'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label>Remarks:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-sticky-note"></i></div>
              </div>
              <textarea class="form-control" rows="4" cols="50" name="taskReview_remarks" id="taskReview_remarks" readonly><?php echo $row['remarks'] ?? 'TO BE DETERMINED' ?></textarea>
            </div>
          </div>
        </div>
        <div class="col-md-auto">
          <div class="form-group">
            <label>Approved By:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-user"></i></div>
              </div>
              <input type="text" class="form-control" name="taskReview_title" id="taskReview_title" value="<?php echo $row['head_name'] ?? 'TO BE DETERMINED' ?>" readonly>
            </div>
          </div>
        </div>
        <?php if ($row['head_note'] != '' || $row['head_note'] != NULL) { ?>
          <div class="col-md-12">
            <div class="form-group">
              <label>Comment:</label>
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fas fa-comments"></i></div>
                </div>
                <textarea class="form-control" rows="3" cols="50" name="taskReview_remarks" id="taskReview_remarks" readonly><?php echo $row['head_note'] ?></textarea>
              </div>
            </div>
          </div>
        <?php }
        if ($row['attachment'] == 1) { ?>
          <div class="col-md-12">
            <div class="form-group">
              <label>Attachments:</label>
              <div class="table-responsive">
                <table id="taskView_table" class="table table-sm table-hover table-borderless">
                  <thead>
                    <tr>
                      <th>File</th>
                      <th>Size</th>
                      <th>Uploaded Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="taskReview_files">
                    <?php
                    $task_code  = $row['task_code'];
                    $query_result = mysqli_query($con, "SELECT * FROM task_files WHERE task_code='$task_code'");
                    while ($row = mysqli_fetch_assoc($query_result)) {
                      $size   = formatSize($row['file_size']);
                      $action = '<button type="button" class="btn btn-sm btn-success" value="' . $row['id'] . '" onclick="downloadFile(this)"><i class="fas fa-file-download fa-fw"></i> Download</button>';
                      $date = date_format(date_create($row['file_dated']), "F d, Y h:i a");
                    ?>
                      <tr>
                        <td><?php echo $row['file_name'] ?></td>
                        <td><?php echo $size ?></td>
                        <td><?php echo $date ?></td>
                        <td><?php echo $action ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </form>
<?php
  }
}
if (isset($_POST['rescheduleTask'])) {
  $id     = $_POST['id'];
  if ($_POST['reschedDate'] == '' || $_POST['reschedReason'] == '') {
    die('Please fill in the required fields.');
  } else {
    $date   = $_POST['reschedDate'] . ' 16:00:00';
    $reason = str_replace("'", "&apos;", $_POST['reschedReason']);
    $query_update = mysqli_query($con, "UPDATE `tasks_details` SET `progress`='RESCHEDULE', `old_date`='$date', `reason`='$reason' WHERE `td_ID`='$id'");
    if ($query_update) {
      echo "Success";
    }
  }
}
if (isset($_POST['addTask'])) {
  $task       = $_POST['taskName'];
  $check = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tasks WHERE task_name='$task' AND task_class=4"));
  if ($check > 0) {
    echo "Task already exists.";
  } else {
    $details    = ucwords(strtolower($_POST['addDetails']));
    $in_charge  = $_POST['taskAssignee'];
    $due_date   = $_POST['dueDate'] . ' 16:00:00';
    $date_today = date('Y-m-d');
    $require    = $_POST['require'];
    $task_for   = $_POST['assignTask_section'];

    $latestcode = mysqli_fetch_assoc(mysqli_query($con, "SELECT MAX(task_code) AS latest_task_code FROM tasks_details WHERE task_class='4' AND task_for='$task_for'"))['latest_task_code'];
    $numeric_portion = intval(substr($latestcode, -6)) + 1;
    $taskCode = $task_for . '-TA-' . str_pad($numeric_portion, 6, '0', STR_PAD_LEFT);

    $insert_task = mysqli_multi_query($con, "INSERT INTO `tasks` (`task_name`,`task_class`,`task_details`,`task_for`,`attachment`,`in_charge`,`submission`) VALUES ('$task', '4', '$details', '$task_for', '$require', '$in_charge', '$due_date'); INSERT INTO tasks_details (`task_code`, `task_name`, `task_class`, `task_for`, `in_charge`, `status`, `date_created`, `due_date`, `attachment`, `task_status`) VALUES ('$taskCode', '$task', '4', '$task_for', '$in_charge', 'NOT YET STARTED', '$date_today', '$due_date', '$require', 1)");
    if ($insert_task) {
      die('Success');
    } else {
      die('Error');
    }
  }
}
?>