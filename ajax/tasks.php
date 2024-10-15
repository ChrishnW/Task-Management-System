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
  <form id="taskEndDetails" enctype="multipart/form-data">
    <input type="hidden" name="taskID" value="<?php echo $row['id']; ?>">
    <div id="charCount" class="pull-right">0/500</div>
    <div class="textarea-container">
      <textarea class="form-control textarea-bottom-border textarea-height" rows="5" cols="50" name="taskRemarks" id="taskRemarks" placeholder="Remarks *" maxlength="500" oninput="updateCounter()"></textarea>
    </div>
    <div class="upload-box border p-3">
      <div class="upload-content">
        <i class="fas fa-cloud-upload-alt fa-3x"></i> <!-- FontAwesome icon -->
        <span class="mt-2">Drag & Drop to Upload File</span>
        <span> OR </span>
        <button class="btn btn-secondary upload-button"
          onclick="document.getElementById('fileInput').click();">
          Browse File
        </button>
        <input type="file" id="fileInput" class="form-control-file" multiple>
      </div>
      <small class="form-text text-muted mt-2">Maximum file size 50 MB. Up to 5 files.</small>
      <div id="error-message" class="error-message"></div> <!-- Error message placeholder -->
    </div>
    <div id="fileList"></div> <!-- Scrollable file list container -->
  </form>
<?php }
if (isset($_POST['submitTask'])) {
  $id               = $_POST['taskID'];
  $remarks          = str_replace("'", "&apos;", preg_replace('/^\s+|\s+$|\s+(?=\s)/m', '', $_POST['taskRemarks']));
  $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tasks t JOIN tasks_details td ON td.task_id=t.id WHERE td.id='$id'"));
  if (strlen(trim($remarks)) <= 30) :
    die("The remarks contains fewer than 30 characters (excluding excess whitespace).");
  endif;
  if (!empty($_FILES['fileInput']['name'][0]) && !empty($_POST['taskRemarks'])) {
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