<?php
include('../include/auth.php');
date_default_timezone_set('Asia/Manila');
if (isset($_POST['editTask'])) {
  $id = $_POST['taskID'];
  $query_result = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tl.class=tc.id JOIN tasks t ON t.task_id=tl.id JOIN tasks_details td ON td.task_id=t.id WHERE td.id='$id'");
  while ($row = mysqli_fetch_assoc($query_result)) { ?>
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
        <input type="number" value="<?php echo $row['score'] ?? "0" ?>" class="form-control" name="update_score" id="update_score">
      </div>
      <div class="form-group col-md-12">
        <label>Title:</label>
        <input type="text" value="<?php echo $row['task_name'] ?>" class="form-control" disabled>
      </div>
      <div class="form-group col-md-5">
        <label>Classification:</label>
        <input type="text" value="<?php echo $row['task_class']; ?>" class="form-control" disabled>
      </div>
      <div class="form-group col-md-4">
        <label>Current Progress:</label>
        <select name="update_progress" id="update_progress" class="form-control"
          <?php if ($row['progress'] == 'Completed' || $row['progress'] == 'On-Hold'): echo "disabled";
          endif; ?>>
          <?php if ($row['progress'] == 'To-Do') : ?>
            <option value="To-Do" selected>To-Do</option>
            <option value="Pending">Pending</option>
          <?php elseif ($row['progress'] == 'Pending') : ?>
            <option value="To-Do">To-Do</option>
            <option value="Pending" selected>Pending</option>
          <?php else : ?>
            <option value="<?php echo $row['progress'] ?>" selected><?php echo ucwords(strtolower($row['progress'])) ?></option>
          <?php endif; ?>
        </select>
      </div>
      <div class="form-group col-md-3">
        <label>Status:</label>
        <select name="update_status" id="update_status" class="form-control">
          <?php if ($row['status'] == 1) { ?>
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
        <input name="update_datetime" id="update_datetime" type="datetime-local" value="<?php echo $row['due']; ?>" class="form-control">
      </div>
      <div class="form-group col-md-6">
        <label>Accomplished Date:</label>
        <input type="datetime-local" value="<?php echo $row['end']; ?>" class="form-control" disabled>
      </div>
    </div>
  <?php }
}
if (isset($_POST['startTask'])) {
  $id = $_POST['id'];
  $query_result = mysqli_query($con, "UPDATE tasks_details SET progress=2 WHERE id='$id'");
  if ($query_result) {
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
      $count++;
    }
  }
  echo $count > 0 ? "Success" : "Unable to complete the operation. Please try again later.";
}
if (isset($_POST['endModal'])) {
  $id = $_POST['taskID'];
  $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tl.class=tc.id JOIN tasks t ON t.task_id=tl.id JOIN tasks_details td ON td.task_id=t.id WHERE td.id='$id'")); ?>
  <form id="submitTask" enctype="multipart/form-data">
    <div id="charCount" class="pull-right">0/500</div>
    <div class="textarea-container">
      <textarea class="form-control textarea-bottom-border textarea-height" rows="5" cols="50" name="taskRemarks" id="taskRemarks" placeholder="Remarks *" maxlength="500" oninput="updateCounter()"></textarea>
    </div>
    <div class="upload-box p-5 <?= $row['attachment'] !== '1' ? 'd-none' : '' ?>">
      <label for="fileInput">
        <i class="fas fa-cloud-upload-alt fa-3x"></i>
        <br>
        <input type="file" name="fileInput[]" id="fileInput" class="form-control-file d-none" multiple>
        <span class="mb-2">Drag and drop a file here or click</span>
        <small class="form-text text-muted mt-2">Maximum file size 50 MB.</small>
      </label>
    </div>
    <div id="fileList" class="mt-3 d-none"></div>
  </form>
<?php }
if (isset($_POST['submitTask'])) {
  $currentDateTime  = date('Y-m-d H:i:s');
  $id               = $_POST['finish_taskID'];
  $remarks          = str_replace("'", "&apos;", preg_replace('/^\s+|\s+$|\s+(?=\s)/m', '', $_POST['taskRemarks']));
  $query_result = mysqli_query($con, "SELECT * FROM tasks_details WHERE id='$id'");
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
        echo "Success";
      }
    }
  }
}
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
?>