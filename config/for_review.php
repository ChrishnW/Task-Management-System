<?php
include('../include/auth.php');
date_default_timezone_set('Asia/Manila');

if (isset($_POST['approveTask'])) {
  $id           = $_POST['approveID'];
  $head_name    = $_POST['approveHead'];
  $head_comment = $_POST['approveComment'];
  $score        = $_POST['approveScore'];
  if ($head_comment == '' || $head_comment == NULL) {
    $head_comment = NULL;
  } else {
    $query_get = mysqli_query($con, "SELECT * FROM tasks_details WHERE id='$id'");
    $row = mysqli_fetch_assoc($query_get);
    $username         = $row['in_charge'];
    $task_code        = $row['task_code'];
    $datetime_current = date('Y-m-d H:i:s');
    $query_insert = mysqli_query($con, "INSERT INTO `notification` (`user`, `icon`, `type`, `body`, `date_created`, `status`) VALUES ('$username', 'fas fa-exclamation', 'warning', 'You have received a note from the head regarding your completed $task_code task.', '$datetime_current', '1')");
  }
  $query_result = mysqli_query($con, "UPDATE tasks_details SET status='FINISHED', achievement='$score', head_name='$head_name', head_note='$head_comment' WHERE id='$id'");
  if ($query_result) {
    echo "Success";
  } else {
    echo "Unable to complete the operation. Please try again later.";
  }
}

if (isset($_POST['viewTask'])) {
  $id = $_POST['taskID'];
  $query_result = mysqli_query($con, "SELECT DISTINCT tasks_details.*, tasks.task_details, CONCAT(accounts.fname,' ',accounts.lname) AS Mname FROM tasks_details JOIN accounts ON tasks_details.in_charge = accounts.username JOIN tasks ON tasks_details.task_name = tasks.task_name WHERE tasks_details.id='$id'");
  while ($row = mysqli_fetch_assoc($query_result)) {
    $task_classes       = [1 => "DAILY ROUTINE", 2 => "WEEKLY ROUTINE", 3 => "MONTHLY ROUTINE", 4 => "ADDITIONAL TASK", 5 => "PROJECT", 6 => "MONTHLY REPORT"];
    $task_class         = $task_classes[$row['task_class']] ?? "UNKNOWN";
    $due_date           = date_format(date_create($row['due_date']), "F d, Y h:i a");
    $date_accomplished  = date_format(date_create($row['date_accomplished']), "F d, Y h:i a"); ?>
    <form id="checkDetails" enctype="multipart/form-data">
      <div class="row">
        <div class="col-md-5">
          <div class="form-group">
            <label>Assignee:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-qrcode"></i></div>
              </div>
              <input type="text" class="form-control" name="approveCode" id="approveCode" value="<?php echo $row['task_code'] ?>" readonly>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <input type="hidden" name="approveID" id="approveID" value="<?php echo $row['id'] ?>">
        <input type="hidden" name="approveHead" id="approveHead" value="<?php echo $full_name ?>">
        <div class="col-md-3">
          <div class="form-group">
            <label>Code:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-qrcode"></i></div>
              </div>
              <input type="text" class="form-control" name="approveCode" id="approveCode" value="<?php echo $row['task_code'] ?>" readonly>
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
              <input type="text" class="form-control" name="approveTitle" id="approveTitle" value="<?php echo $row['task_name'] ?>" readonly>
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
              <input type="number" class="form-control text-danger" name="approveScore" id="approveScore" value="<?php echo $row['achievement'] ?>" min="1" max="5">
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
              <input type="datetime-local" class="form-control" name="approveDue" id="approveDue" value="<?php echo $row['due_date'] ?>" readonly>
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
              <input type="datetime-local" class="form-control" name="approveFinish" id="approveFinish" value="<?php echo $row['date_accomplished'] ?>" readonly>
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
              <textarea class="form-control" name="approveRemarks" id="approveRemarks" readonly><?php echo $row['remarks'] ?></textarea>
            </div>
          </div>
        </div>
        <?php if ($row['requirement_status'] == 1) { ?>
          <div class="col-md-12">
            <div class="form-group">
              <label>Attachments:</label>
              <div class="table-responsive">
                <table id="taskView_table" class="table table-striped">
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
                      $size = number_format($row['file_size'] / (1024 * 1024), 2);
                      $action = '<button type="button" class="btn btn-circle btn-success" value="' . $row['id'] . '" onclick="downloadFile(this)"><i class="fas fa-file-download"></i></button>';
                      $date = date_format(date_create($row['file_dated']), "Y-m-d h:i a");
                    ?>
                      <tr>
                        <td><?php echo $row['file_name'] ?></td>
                        <td><?php echo $size ?> mb</td>
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
        <div class="col-md-12">
          <div class="form-group">
            <label>Comment:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-comments"></i></div>
              </div>
              <textarea class="form-control" name="approveComment" id="approveComment" placeholder="Put something here..."></textarea>
            </div>
          </div>
        </div>
      </div>
    </form>
    <?php }
}

if (isset($_POST['approveMultiple'])) {
  $count          = 0;
  $taskIDmultiple = $_POST['checkedIds'];
  $head_name      = $_POST['head_name'];
  foreach ($taskIDmultiple as $taskID) {
    $query_result = mysqli_query($con, "UPDATE tasks_details SET status='FINISHED', head_name='$head_name' WHERE id='$taskID'");
    $count += 1;
  }
  if ($count != 0) {
    echo "Success";
  } else {
    echo "Unable to complete the operation. Please try again later.";
  }
}

if (isset($_POST['filterTable'])) {
  $taskClass  = $_POST['taskClass'];
  $date_to    = $_POST['date_to'];
  $date_from  = $_POST['date_from'];

  $query = "SELECT DISTINCT tasks_details.*, accounts.file_name, tasks.task_details, section.dept_id FROM tasks_details JOIN accounts ON tasks_details.in_charge = accounts.username JOIN tasks ON tasks_details.task_name = tasks.task_name JOIN section ON tasks_details.task_for = section.sec_id WHERE tasks_details.task_status=1 AND tasks_details.status='REVIEW' AND section.dept_id = '$dept_id'";
  if ($date_to != NULL && $date_from != NULL) {
    $query .= " AND DATE(tasks_details.date_accomplished) >= '$date_to' AND DATE(tasks_details.date_accomplished) <= '$date_from'";
  }
  if ($taskClass != '') {
    $query .= " AND tasks_details.task_class='$taskClass'";
  }
  $result = mysqli_query($con, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = $result->fetch_assoc()) {
      if (empty($row['file_name'])) {
        $imageURL = '../assets/img/user-profiles/nologo.png';
      } else {
        $imageURL = '../assets/img/user-profiles/' . $row['file_name'];
      }
      $task_classes = [1 => ['name' => 'DAILY ROUTINE', 'badge' => 'info'], 2 => ['name' => 'WEEKLY ROUTINE', 'badge' => 'info'], 3 => ['name' => 'MONTHLY ROUTINE', 'badge' => 'info'], 4 => ['name' => 'ADDITIONAL TASK', 'badge' => 'info'], 5 => ['name' => 'PROJECT', 'badge' => 'info'], 6 => ['name' => 'MONTHLY REPORT', 'badge' => 'danger']];
      if (isset($task_classes[$row['task_class']])) {
        $class = $task_classes[$row['task_class']]['name'];
        $badge = $task_classes[$row['task_class']]['badge'];
      } else {
        $class = 'Unknown';
        $badge = 'secondary';
      }
      $status_badges = [
        'NOT YET STARTED' => 'primary',
        'IN PROGRESS' => 'warning',
        'REVIEW' => 'danger',
        'FINISHED' => 'success',
        'RESCHEDULE' => 'secondary'
      ];
      $progress = '<span class="badge badge-' . $status_badges[$row['status']] . '">' . $row['status'] . '</span>';
      $task_class = '<span class="badge badge-' . $badge . '">' . $class . '</span>';
      $date_accomplished  = date_format(date_create($row['date_accomplished']), "Y-m-d h:i a");
      $assignee  = '<img src=' . $imageURL . ' class="border border-primary img-table-solo">'; ?>
      <tr>
        <td><input type="checkbox" name="selected_ids[]" class="form-control" value="<?php echo $row['id']; ?>"></td>
        <td><button type="button" onclick="checkTask(this)" class="btn btn-success btn-circle" value="<?php echo $row['id'] ?>" data-name="<?php echo $row['task_name'] ?>"><i class="fas fa-check"></i></button></td>
        <td><?php echo $row['task_code'] ?></td>
        <td><?php echo $row['task_name'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
        <td><?php echo $task_class ?></td>
        <td><?php echo $date_accomplished ?></td>
        <td data-toggle="tooltip" data-placement="right" title="<?php echo $row['in_charge'] ?>">
          <center /><?php echo $assignee ?>
        </td>
        <td><?php echo $progress ?></td>
      </tr>
<?php }
  }
}
?>