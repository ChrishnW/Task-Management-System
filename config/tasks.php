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
  $loadTable = "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.task_code != ''";

  if (isset($_POST['fromDate']) && isset($_POST['toDate'])):
    $loadTable .= " AND DATE(td.due_date) >= '{$_POST['fromDate']}' AND DATE(td.due_date) <= '{$_POST['toDate']}'";
  endif;

  if (isset($_POST['progress']) && $_POST['progress'] !== 'All'):
    $loadTable .= " AND td.status = '{$_POST['progress']}'";
  endif;
  if (isset($_POST['status'])):
    $loadTable .= " AND td.task_status = '{$_POST['status']}'";
  endif;
  if (isset($_POST['department']) && !isset($_POST['section'])):
    $depRow = mysqli_fetch_assoc(mysqli_query($con, "SELECT GROUP_CONCAT(CASE WHEN s.status = 1 THEN CONCAT('\"', s.sec_id, '\"') END SEPARATOR ', ') AS sectionList FROM section s WHERE s.dept_id='{$_POST['department']}'"));
    echo $depRow['sectionList'];
    if ($depRow['sectionList'] !== NULL):
      $loadTable .= " AND tl.task_for IN ({$depRow['sectionList']})";
    else:
      $loadTable .= " AND tl.task_for = ''";
    endif;
  elseif (isset($_POST['section'])):
    $loadTable .= " AND tl.task_for = '{$_POST['section']}'";
  endif;

  $getTable = mysqli_query($con, $loadTable);
  while ($row = mysqli_fetch_assoc($getTable)):
    $due_date = date_format(date_create($row['due_date']), "F d, Y h:i a"); ?>
    <tr <?php if ($row['task_status'] === '0') echo "class='table-danger'"; ?>>
      <td><?php echo $row['task_code'] ?></td>
      <td><?php echo $row['task_name'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
      <td><?php echo getTaskClass($row['task_class']); ?></td>
      <td class="text-truncate"><?php echo $due_date ?></td>
      <td class="text-truncate"><?php echo getUser($row['in_charge']); ?></td>
      <td><?php echo getProgressBadge($row['status']); ?></td>
      <td class="text-truncate">
        <button type="button" class="btn btn-secondary btn-block" onclick="editTask(this)" value="<?php echo $row['id'] ?>"><i class="fas fa-edit fa-fw"></i> Modify</button>
        <?php if (in_array($row['status'], ['REVIEW', 'FINISHED'])): ?>
          <button type="button" onclick="viewTask(this)" class="btn btn-primary btn-block" value="<?php echo $row['id'] ?>" data-name="<?php echo $row['task_name'] ?>"><i class="fas fa-info fa-fw"></i> Details</button>
        <?php endif; ?>
      </td>
    </tr>
    <?php endwhile;
}
if (isset($_POST['filterTableTask'])) {
  $loadTable = "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE t.in_charge='{$username}'";
  if ($_POST['status'] === 'TODO') :
    $loadTable .= " AND td.status NOT IN ('REVIEW', 'FINISHED')";
  elseif ($_POST['status'] === 'REVIEW') :
    $loadTable .= " AND td.status = 'REVIEW'";
  elseif ($_POST['status'] === 'FINISHED') :
    $loadTable .= " AND td.status = 'FINISHED'";
  endif;
  if (isset($_POST['dateFrom']) && isset($_POST['dateTo'])) :
    $loadTable .= " AND td.due_date >= '{$_POST['dateFrom']}' AND td.due_date <= '{$_POST['dateTo']}'";
  endif;
  $getTable = mysqli_query($con, $loadTable);
  while ($row = mysqli_fetch_assoc($getTable)):
    if ($_POST['status'] === 'TODO') :
      $current_date = date('Y-m-d');
      $checkbox = '<input type="checkbox" name="selected_ids[]" class="form-control" value="' . (date_create(date('Y-m-d', strtotime($row['due_date']))) > date_create($current_date) ? '' : $row['id']) . '" ' . (date_create(date('Y-m-d', strtotime($row['due_date']))) > date_create($current_date) ? 'disabled' : '') . '>';
      $due_date = date_format(date_create($row['due_date']), "F d, Y h:i a"); ?>
      <tr class="<?php if ((new DateTime($today))->setTime(0, 0, 0) > (new DateTime($row['due_date']))->setTime(0, 0, 0) && $row['status'] === 'NOT YET STARTED') echo "tick-pulse"; ?>">
        <td>
          <?php if ($row['status'] === 'NOT YET STARTED') {
            echo '<input type="checkbox" name="selected_ids[]" class="form-control bodyCheckbox" value="' . (date_create(date('Y-m-d', strtotime($row['due_date']))) > date_create($current_date) ? '' : $row['id']) . '" ' . (date_create(date('Y-m-d', strtotime($row['due_date']))) > date_create($current_date) ? 'disabled' : '') . '>';
          } else {
            echo '<input type="checkbox" name="selected_ids[]" class="form-control bodyCheckbox" disabled>';
          } ?>
        </td>
        <td><?php echo $row['task_code'] ?></td>
        <td>
          <?php echo $row['task_name'] ?>
          <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i>
          <?php if ($row['requirement_status'] === '1') : ?>
            <i class="fas fa-photo-video text-warning" data-toggle="tooltip" data-placement="right" title="File Attachment Required"></i>
          <?php endif; ?>
        </td>
        <td><?php echo getTaskClass($row['task_class']); ?></td>
        <td class="text-truncate"><?php echo $due_date ?></td>
        <td><?php echo getProgressBadge($row['status']); ?></td>
        <td class="text-truncate">
          <?php if ($row['status'] === 'NOT YET STARTED') {
            if (date_create(date('Y-m-d', strtotime($row['due_date']))) > date_create($current_date)) {
              echo '<button class="btn btn-secondary btn-block" disabled><i class="far fa-clock fa-fw"></i> On Hold</button>';
            } else {
              echo '<button class="singleStart btn btn-success btn-block" value="' . $row['id'] . '" onclick="startTask(this)"><i class="far fa-play-circle fa-fw"></i> Start</button>';
            }
          } elseif ($row['status'] === 'IN PROGRESS') {
            echo '<button class="btn btn-danger btn-block" value="' . $row['id'] . '" onclick="endTask(this)" data-task="' . $row['task_name'] . '"><i class="far fa-stop-circle fa-fw"></i> Finish</button>';
          } else {
            echo '<button class="btn btn-dark btn-block" disabled><i class="far fa-clock fa-fw"></i> On Hold</button>';
          }
          if ($row['old_date'] === NULL) {
            echo '<button class="btn btn-secondary btn-block" value="' . $row['id'] . '" onclick="rescheduleTask(this)"><i class="fas fa-redo fa-fw"></i> Reschedule</button>';
          } ?>
        </td>
      </tr>
    <?php elseif ($_POST['status'] === 'REVIEW') :
      $start_date = is_null($row['date_start']) ? "N/A" : date_format(date_create($row['date_start']), "F d, Y h:i a");
      $date_accomplished = date_format(date_create($row['date_accomplished']), "F d, Y h:i a"); ?>
      <tr>
        <td><?php echo $row['task_code'] ?></td>
        <td>
          <?php echo $row['task_name'] ?>
          <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i>
          <?php if ($row['requirement_status'] === '1') : ?>
            <i class="fas fa-photo-video text-warning" data-toggle="tooltip" data-placement="right" title="File Attachment Required"></i>
          <?php endif; ?>
        </td>
        <td><?php echo getTaskClass($row['task_class']); ?></td>
        <td class="text-truncate"><?php echo $start_date ?></td>
        <td class="text-truncate"><?php echo $date_accomplished ?></td>
        <td class="text-truncate"><button type="button" class="btn btn-block btn-warning" value='<?php echo $row['id']; ?>' onclick="reviewTask(this)"><i class="far fa-eye fa-fw"></i> View</button></td>
      </tr>
    <?php elseif ($_POST['status'] === 'FINISHED') :
      $due_date = date_format(date_create($row['due_date']), "F d, Y h:i a"); ?>
      <tr>
        <td><?php echo $row['task_code'] ?></td>
        <td>
          <?php echo $row['task_name'] ?>
          <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i>
          <?php if ($row['requirement_status'] === '1') : ?>
            <i class="fas fa-photo-video text-warning" data-toggle="tooltip" data-placement="right" title="File Attachment Required"></i>
          <?php endif; ?>
        </td>
        <td><?php echo getTaskClass($row['task_class']); ?></td>
        <td class="text-truncate"><?php echo $due_date ?></td>
        <td class="text-center">
          <span class="h5 text-success font-weight-bold"><?php echo $row['achievement'] ?></span>
        </td>
        <td class="text-truncate"><button type="button" class="btn btn-block btn-primary" value='<?php echo $row['id']; ?>' onclick="viewTask(this)"><i class="fas fa-tasks fa-fw"></i> Details</button></td>
      </tr>
    <?php endif;
  endwhile;
}
if (isset($_POST['modifyTask'])) {
  $id       = $_POST['taskID'];
  $progress = $_POST['update_progress'];
  $status   = $_POST['update_status'];

  if ($_POST['update_datetime'] === '') {
    die("Date and Time cannot be empty. Please fill in all required fields.");
    exit;
  }
  $datetime = str_replace("T", " ", $_POST['update_datetime']) . ":00";
  $query_result = mysqli_query($con, "UPDATE tasks_details SET status='$progress', due_date='$datetime', task_status='$status' WHERE id='$id'");
  if ($query_result) {
    echo "Success";
  } else {
    echo "Unable to complete the operation. Please try again later.";
  }
}
if (isset($_POST['startTask'])) {
  $id = $_POST['id'];
  $query_result = mysqli_query($con, "UPDATE tasks_details SET status='IN PROGRESS' WHERE id='$id'");
  if ($query_result) {
    $query_code = mysqli_query($con, "SELECT task_code FROM tasks_details WHERE id='$id'");
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
    $query_result = mysqli_query($con, "UPDATE tasks_details SET status='IN PROGRESS' WHERE id='$taskID'");
    if ($query_result) {
      $query_code = mysqli_query($con, "SELECT task_code FROM tasks_details WHERE id='$taskID'");
      $row = mysqli_fetch_assoc($query_code);
      log_action("Task {$row['task_code']} started.");
      $count++;
    }
  }
  echo $count > 0 ? "Success" : "Unable to complete the operation. Please try again later.";
}
if (isset($_POST['endTaskDeatails'])) {
  $id = $_POST['taskID'];
  $query_result = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.id='$id'");
  while ($row = mysqli_fetch_assoc($query_result)) {
    $require = $row['requirement_status'];
  }
  if ($require == 1) { ?>
    <form id="submitDetails" enctype="multipart/form-data">
      <input type="hidden" id="finish_taskID" name="finish_taskID" value="<?php echo $id ?>">
      <div class="form-group">
        <textarea class="form-control" name="taskRemarks" id="taskRemarks" rows="5" placeholder="Enter your remarks here..."></textarea>
      </div>
      <div class="form-group">
        <div class="file-drop-area" id="fileDropArea">
          Drag & Drop files here or click to upload
          <input type="file" name="file-1[]" id="file-1" multiple>
        </div>
        <div id="fileList" class="mt-2"></div>
      </div>
    </form>
  <?php } else { ?>
    <form id="submitDetails" enctype="multipart/form-data">
      <input type="hidden" id="finish_taskID" name="finish_taskID" value="<?php echo $id ?>">
      <textarea class="form-control border-dark" rows="5" cols="50" name="taskRemarks" id="taskRemarks" placeholder="Write your remarks for this task..."></textarea>
    </form>
  <?php }
}
if (isset($_POST['endTask'])) {
  $currentDateTime  = date('Y-m-d H:i:s');
  $id               = $_POST['finish_taskID'];
  $remarks          = str_replace("'", "&apos;", preg_replace('/^\s+|\s+$|\s+(?=\s)/m', '', $_POST['taskRemarks']));
  $query_result = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.id='$id'");
  while ($row = $query_result->fetch_assoc()) {
    $task_name    = $row['task_name'];
    $assignee     = $row['in_charge'];
    $task_code    = $row['task_code'];
    $require      = $row['requirement_status'];
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
            $query_update = mysqli_query($con, "UPDATE tasks_details SET status='REVIEW', date_accomplished='$currentDateTime', achievement='$achievement', remarks='$remarks' WHERE id='$id'");
            $query_insert = mysqli_query($con, "INSERT INTO `task_files`(`task_code`, `file_name`, `file_size`, `file_type`, `file_dated`, `file_owner`, `file_target`) VALUES ('$task_code', '$original_filename', '$filesize', '$file_extension', '$currentDateTime', '$assignee', '$new_filename')");
            if ($query_update && $query_insert) {
              $query_code = mysqli_query($con, "SELECT task_code FROM tasks_details WHERE id='$id'");
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
      $query_update = mysqli_query($con, "UPDATE tasks_details SET status='REVIEW', date_accomplished='$currentDateTime', achievement='$achievement', remarks='$remarks' WHERE id='$id'");
      if ($query_update) {
        $query_code = mysqli_query($con, "SELECT task_code FROM tasks_details WHERE id='$id'");
        $row = mysqli_fetch_assoc($query_code);
        log_action("Task {$row['task_code']} completed and sent for review.");
        echo "Success";
      }
    }
  }
}
if (isset($_POST['checkTask'])) {
  $id = $_POST['taskID'];
  $query_result = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.id='$id'");
  while ($row = mysqli_fetch_assoc($query_result)) {
    $task_classes       = [1 => "DAILY ROUTINE", 2 => "WEEKLY ROUTINE", 3 => "MONTHLY ROUTINE", 4 => "ADDITIONAL TASK", 5 => "PROJECT", 6 => "MONTHLY REPORT"];
    $task_class         = $task_classes[$row['task_class']] ?? "UNKNOWN";
    $due_date           = date_format(date_create($row['due_date']), "F d, Y h:i a");
    $date_accomplished  = date_format(date_create($row['date_accomplished']), "F d, Y h:i a"); ?>
    <form id="editDetails" enctype="multipart/form-data">
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
        if ($row['requirement_status'] == 1) { ?>
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
  $query_result = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.id='$id'");
  while ($row = mysqli_fetch_assoc($query_result)) {
    $task_classes       = [1 => "DAILY ROUTINE", 2 => "WEEKLY ROUTINE", 3 => "MONTHLY ROUTINE", 4 => "ADDITIONAL TASK", 5 => "PROJECT", 6 => "MONTHLY REPORT"];
    $task_class         = $task_classes[$row['task_class']] ?? "UNKNOWN";
    $due_date           = date_format(date_create($row['due_date']), "F d, Y h:i a");
    $date_accomplished  = date_format(date_create($row['date_accomplished']), "F d, Y h:i a"); ?>
    <form id="editDetails" enctype="multipart/form-data">
      <div class="row">
        <input type="hidden" name="taskReview_id" id="taskReview_id" value="<?php echo $row['id'] ?>">
        <input type="hidden" name="taskReview_owner" id="taskReview_owner" value="<?php echo $row['in_charge'] ?>">
        <div class="col-md-3">
          <div class="form-group">
            <label>Code</label>
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
            <label>Title</label>
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
            <label>Achievement</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-trophy"></i></div>
              </div>
              <input type="text" class="form-control" name="taskReview_title" id="taskReview_title" value="<?php echo $row['achievement'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="form-group col-md-4">
          <label>Classification</label>
          <input type="text" value="<?php echo $task_class ?>" class="form-control" disabled>
        </div>
        <div class="form-group col-md-4">
          <label>Due Date</label>
          <input type="text" value="<?php echo $due_date ?>" class="form-control" disabled>
        </div>
        <div class="form-group col-md-4">
          <label>Date Accomplished</label>
          <input type="text" value="<?php echo $date_accomplished ?>" class="form-control" disabled>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label>Remarks</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-sticky-note"></i></div>
              </div>
              <textarea class="form-control" rows="4" cols="50" name="taskReview_remarks" id="taskReview_remarks"><?php echo $row['remarks'] ?></textarea>
            </div>
          </div>
        </div>
        <?php if ($row['requirement_status'] == 1) { ?>
          <div class="col-md-12">
            <div class="form-group">
              <label for="fileInput">Attachment</label>
              <div class="custom-file">
                <input type="file" class="custom-file-input" name="taskReview_upload[]" id="taskReview_upload" multiple>
                <label class="custom-file-label" for="fileInput">Choose file</label>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <div class="table-responsive">
                <table class="table table-hover table-borderless">
                  <tbody>
                    <?php
                    $task_code  = $row['task_code'];
                    $query_result = mysqli_query($con, "SELECT * FROM task_files WHERE task_code='$task_code'");
                    while ($row = mysqli_fetch_assoc($query_result)) {
                      $size   = formatSize($row['file_size']);
                      $action = '<button type="button" class="btn btn-circle btn-success" value="' . $row['id'] . '" onclick="downloadFile(this)"><i class="fas fa-file-download"></i></button> <button type="button" class="btn btn-circle btn-danger" value="' . $row['id'] . '" onclick="deleteFile(this)"><i class="fas fa-trash"></i></button>';
                      $date   = date_format(date_create($row['file_dated']), "Y-m-d h:i a");
                      $filescount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM task_files WHERE task_code='{$row['task_code']}'"));
                    ?>
                      <tr>
                        <td><?php echo $row['file_name'] ?></td>
                        <td><?php echo $size ?></td>
                        <td>
                          <?php if (in_array(strtolower($row['file_type']), ['pdf', 'jpg', 'png', 'jpeg', 'xlsx'])): ?>
                            <button type="button" class="btn btn-info btn-sm" value="<?php echo $row['id']; ?>" onclick="viewFile(this)"><i class="fas fa-eye fa-fw"></i> View</button>
                          <?php endif; ?>
                          <button type="button" class="btn btn-success btn-sm" value="<?php echo $row['id']; ?>" onclick="downloadFile(this)">Download</button>
                          <?php if ($filescount > 1) : ?>
                            <button type="button" class="btn btn-danger btn-sm" value="<?php echo $row['id']; ?>" onclick="deleteFile(this)">Delete</button>
                          <?php else: ?>
                            <button type="button" class="btn btn-danger btn-sm" value="<?php echo $row['id']; ?>" onclick="deleteFile(this)" disabled>Delete</button>
                          <?php endif; ?>
                        </td>
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
  $query = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id AND td.id='$id'");
  while ($row = mysqli_fetch_assoc($query)) {
    $require = $row['requirement_status'];
  }
  if ($require == 1) {
    $query_update = mysqli_query($con, "UPDATE tasks_details SET remarks='$remarks' WHERE id='$id'");
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
      $query_code = mysqli_query($con, "SELECT task_code FROM tasks_details WHERE id='$id'");
      $row = mysqli_fetch_assoc($query_code);
      log_action("Task {$row['task_code']} remarks/files have been edited.");
      echo "Success";
    }
  } else {
    $query_update = mysqli_query($con, "UPDATE tasks_details SET remarks='$remarks' WHERE id='$id'");
    if ($query_update) {
      $query_code = mysqli_query($con, "SELECT task_code FROM tasks_details WHERE id='$id'");
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
  $query_result = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.id='$id'");
  while ($row = mysqli_fetch_assoc($query_result)) {
    $due_date           = date_format(date_create($row['due_date']), "F d, Y h:i a");
    $date_accomplished  = date_format(date_create($row['date_accomplished']), "F d, Y h:i a"); ?>
    <form id="editDetails" enctype="multipart/form-data">
      <div class="row">
        <input type="hidden" id="editProgress" value="<?php echo $row['status']; ?>">
        <div class="col-md-5">
          <div class="form-group">
            <label>Code</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-qrcode"></i></div>
              </div>
              <input type="text" class="form-control" name="" id="" value="<?php echo $row['task_code'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-7">
          <div class="form-group">
            <label>Assignee</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-user"></i></div>
              </div>
              <input type="text" class="form-control" value="<?php echo getName($row['in_charge']); ?>" readonly>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <input type="hidden" name="" id="" value="<?php echo $row['id'] ?>">
        <input type="hidden" name="" id="" value="<?php echo $row['in_charge'] ?>">
        <div class="col-md-7">
          <div class="form-group">
            <label>Task</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-tasks"></i></div>
              </div>
              <input type="text" class="form-control" name="" id="editTaskCode" value="<?php echo $row['task_name'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-5">
          <div class="form-group">
            <label>Due Date</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-calendar-day"></i></div>
              </div>
              <input type="datetime-local" class="form-control" name="" id="" value="<?php echo $row['due_date'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-5">
          <div class="form-group">
            <label>Date Started</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-hourglass-start"></i></div>
              </div>
              <input type="datetime-local" class="form-control" name="" id="" value="<?php echo $row['date_start'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-5">
          <div class="form-group">
            <label>Date Completed</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-hourglass-end"></i></div>
              </div>
              <input type="datetime-local" class="form-control" name="" id="" value="<?php echo $row['date_accomplished'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <label>Achievement</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-trophy"></i></div>
              </div>
              <input type="number" class="form-control" name="editScore" id="editScore" value="<?php echo $row['achievement'] ?>" min="1" max="5" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label>Remarks</label>
            <textarea class="form-control" rows="4" cols="50" name="editRemarks" id="editRemarks" readonly><?php echo $row['remarks'] ?? 'TO BE DETERMINED' ?></textarea>
          </div>
        </div>
        <?php if ($row['status'] !== 'REVIEW') : ?>
          <div class="col-md-auto">
            <div class="form-group">
              <label>Reviewed By</label>
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fas fa-user"></i></div>
                </div>
                <input type="text" class="form-control" name="" id="" value="<?php echo $row['head_name'] ?? 'TO BE DETERMINED' ?>" readonly>
              </div>
            </div>
          </div>
          <div class="col-md-5">
            <div class="form-group">
              <label>Date Approved</label>
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="far fa-calendar-check"></i></div>
                </div>
                <input type="datetime-local" class="form-control" name="" id="" value="<?php echo $row['date_accomplished'] ?>" readonly>
              </div>
            </div>
          </div>
          <div class="col-md-12 <?php if ($row['head_note'] == '' || $row['head_note'] == NULL) echo "d-none"; ?>" id="headComment">
            <div class="form-group">
              <label>Comment</label>
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fas fa-comments"></i></div>
                </div>
                <textarea class="form-control" rows="3" cols="50" name="editComment" id="editComment" readonly><?php echo $row['head_note'] ?></textarea>
              </div>
            </div>
          </div>
        <?php endif; ?>
        <?php if ($row['status'] === 'REVIEW' && $access === 2): ?>
          <div class="col-md-12">
            <div class="form-group">
              <label for="fileInput">Add Attachment</label>
              <div class="custom-file">
                <input type="file" class="custom-file-input" name="taskReview_upload[]" id="taskReview_upload" multiple>
                <label class="custom-file-label" for="fileInput">Choose file</label>
              </div>
            </div>
          </div>
        <?php endif; ?>
        <?php if ($row['requirement_status'] == 1) { ?>
          <div class="col-md-12">
            <div class="form-group">
              <label>Attachments</label>
              <div class="table-responsive">
                <table id="taskView_table" class="table table-sm table-hover table-borderless">
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
                        <td>
                          <?php if (in_array(strtolower($row['file_type']), ['pdf', 'jpg', 'png', 'jpeg', 'xlsx'])): ?>
                            <button type="button" class="btn btn-info btn-sm" value="<?php echo $row['id']; ?>" onclick="viewFile(this)"><i class="fas fa-eye fa-fw"></i> View</button>
                          <?php endif; ?>
                          <button type="button" class="btn btn-success btn-sm" value="<?php echo $row['id']; ?>" onclick="downloadFile(this)"><i class="fas fa-file-download fa-fw"></i> Download</button>
                        </td>
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
if (isset($_POST['editTask'])) :
  $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM task_list tl JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.id='{$_POST['taskID']}'")); ?>
  <form id="modifyForm">
    <div class="row">
      <div class="form-group col-md-7">
        <label>Code</label>
        <input type="text" value="<?php echo $row['task_code'] ?>" class="form-control" disabled>
      </div>
      <div class="form-group col-md-5">
        <label>Status</label>
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
      <div class="form-group col-md-12">
        <label>Task</label>
        <input type="text" value="<?php echo $row['task_name'] ?>" class="form-control" disabled>
      </div>
      <div class="form-group col-md-6" id="adminShow1">
        <label>Due Date</label>
        <input name="update_datetime" id="update_datetime" type="datetime-local" value="<?php echo $row['due_date'] ?>" class="form-control">
      </div>
      <div class="form-group col-md-6" id="adminShow2">
        <label>Current Progress</label>
        <select name="update_progress" id="update_progress" class="form-control">
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
    </div>
  </form>
<?php endif;
if (isset($_POST['rescheduleTask'])) {
  $id     = $_POST['id'];
  if ($_POST['reschedDate'] == '' || $_POST['reschedReason'] == '') {
    die('Please fill in the required fields.');
  } else {
    $date   = $_POST['reschedDate'] . ' 16:00:00';
    $reason = str_replace("'", "&apos;", $_POST['reschedReason']);
    $query_update = mysqli_query($con, "UPDATE `tasks_details` SET `status`='RESCHEDULE', `old_date`='$date', `reason`='$reason' WHERE `id`='$id'");
    if ($query_update) {
      echo "Success";
    }
  }
}
if (isset($_POST['addTask'])) {
  $taskName     = $_POST['taskName'];
  $taskDetails  = $_POST['addDetails'];
  $attachment   = $_POST['require'];
  $dueDate      = $_POST['dueDate'];
  $taskFor      = $_POST['assignTask_section'];
  $listAssignee = $_POST['taskAssignee'];

  $checkNewTask = mysqli_query($con, "SELECT * FROM task_list WHERE task_name='$taskName' AND task_for='$taskFor'");
  if (mysqli_num_rows($checkNewTask) === 0) {
    $registerNewTask = mysqli_query($con, "INSERT INTO task_list (`task_name`, `task_details`, `task_class`, `task_for`) VALUES ('$taskName', '$taskDetails', '4', '$taskFor')");
    if ($registerNewTask) {
      $tl_id = mysqli_insert_id($con);
    } else {
      die("Error: Could not insert new task. " . mysqli_error($con));
    }
  } else {
    $getTaskID = mysqli_fetch_assoc($checkNewTask);
    $tl_id = $getTaskID['id'];
  }

  $summary = [];

  foreach ($listAssignee as $assignee) {
    $insertedCount = 0;
    $skippedCount = 0;

    $checkAssignTask = mysqli_query($con, "SELECT * FROM tasks WHERE task_id='$tl_id' AND in_charge='$assignee' AND submission='$dueDate'");
    if (mysqli_num_rows($checkAssignTask) === 0) {
      $assignTask = mysqli_query($con, "INSERT INTO tasks (`task_id`, `requirement_status`, `in_charge`, `submission`) VALUES ('$tl_id', '$attachment', '$assignee', '$dueDate')");
      if ($assignTask) {
        $t_id = mysqli_insert_id($con);
        $deployedTask = mysqli_query($con, "INSERT INTO tasks_details (`task_id`, `due_date`) VALUES ('$t_id', '$dueDate 16:00:00')");
        if ($deployedTask) {
          $insertedCount++;
        } else {
          die("Error: Could not deploy new task. " . mysqli_error($con));
        }
      }
    } else {
      $skippedCount++;
    }
    $summary[] = [
      'assignee' => $assignee,
      'inserted' => $insertedCount,
      'skipped' => $skippedCount
    ];
  }
  foreach ($summary as $item) {
    echo '<i class="fas fa-user fa-fw text-primary"></i>Assignee: <span class="badge badge-primary">' . $item['assignee'] . '</span><br>';
    echo '<i class="fas fa-check-circle fa-fw text-success"></i>Inserted Tasks: <span class="badge badge-success">' . $item['inserted'] . '</span><br>';
    echo '<i class="fas fa-times-circle fa-fw text-danger"></i>Skipped Tasks: <span class="badge badge-danger">' . $item['skipped'] . '</span><br>';
    echo '<hr class="sidebar-divider d-none d-md-block">';
  }
}
if (isset($_POST['deleteTask'])) {
  $queryDelete = mysqli_query($con, "DELETE FROM tasks_details WHERE id='{$_POST['taskID']}'");
  if ($queryDelete) {
    die('Success');
  } else {
    die("Error: Could not delete task. " . mysqli_error($con));
  }
}
?>