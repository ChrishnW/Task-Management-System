<?php
include('../include/auth.php');
if (isset($_POST['selectClass'])) {
  $task_for   = $_POST['task_for'];
  $task_class = $_POST['task_class'];
  $query_result = mysqli_query($con, "SELECT * FROM task_list WHERE status=1 AND task_for='$task_for' AND task_class='$task_class'");
  while ($row = mysqli_fetch_assoc($query_result)) {
    $id           = $row['id'];
    $task_name    = $row['task_name'];
    $task_details = $row['task_details'];
    echo "<option value='$task_name'>$task_name</option>";
  }
}
if (isset($_POST['assignSection'])) {
  $section = $_POST['sec_id'];
  $query_result = mysqli_query($con, "SELECT * FROM accounts WHERE status=1 AND sec_id='$section' AND access=2");
  while ($row = mysqli_fetch_assoc($query_result)) {
    $emp_name_temp  = strtolower($row['fname'] . ' ' . $row['lname']);
    $emp_name       = ucwords($emp_name_temp);
    $username       = $row['username'];
    $emp_id         = $row['emp_id'];
    echo "<option value='$username' data-subtext='$username'>$emp_name</option>";
  }
}
if (isset($_POST['assignTask'])) {
  $error          = false;
  $task_section   = $_POST['assign_section'];
  $task_class     = $_POST['assign_taskclass'];
  $task_duedate   = $_POST['assign_duedate'];
  $assign_file   = $_POST['assign_file'];
  if (!isset($_POST['assign_assignee']) || !isset($_POST['assign_tasks']) || $task_section === '' || $task_class === '' || $task_duedate === '') {
    $error = true;
    echo "Empty field has been detected! Please try again.";
  }
  if (!$error) {
    $assigneeArray = $_POST['assign_assignee'];
    $tasksArray = $_POST['assign_tasks'];

    $summary = [];

    foreach ($assigneeArray as $assignee) {
      $insertedCount = 0;
      $skippedCount = 0;

      foreach ($tasksArray as $tasks) {
        $getTaskDetails = mysqli_query($con, "SELECT * FROM task_list WHERE task_name='$tasks'");
        while ($row = mysqli_fetch_assoc($getTaskDetails)) {
          $task_details = $row['task_details'];
        }
        $checkTasks = mysqli_query($con, "SELECT * FROM tasks WHERE task_name='$tasks' AND in_charge='$assignee'");
        $checkRows = mysqli_num_rows($checkTasks);
        if ($checkRows > 0) {
          $skippedCount++;
        } else {
          $query_result = mysqli_query($con, "INSERT INTO tasks (`task_name`, `task_class`, `task_details`, `task_for`, `requirement_status`, `in_charge`, `submission`) VALUES ('$tasks', '$task_class', '$task_details', '$task_section', '$assign_file', '$assignee', '$task_duedate')");
          if ($query_result) {
            $insertedCount++;
          }
        }
      }

      $summary[] = [
        'assignee' => $assignee,
        'inserted' => $insertedCount,
        'skipped' => $skippedCount
      ];
    }

    // Display the summary
    foreach ($summary as $item) {
      echo '<i class="fas fa-user fa-fw text-primary"></i>Assignee: <span class="badge badge-primary">' . $item['assignee'] . '</span><br>';
      echo '<i class="fas fa-check-circle fa-fw text-success"></i>Inserted Tasks: <span class="badge badge-success">' . $item['inserted'] . '</span><br>';
      echo '<i class="fas fa-times-circle fa-fw text-danger"></i>Skipped Tasks: <span class="badge badge-danger">' . $item['skipped'] . '</span><br>';
      echo '<hr class="sidebar-divider d-none d-md-block">';
    }
  }
}
if (isset($_POST['viewTaskEmp'])) {
  $userID = $_POST['assignee_id'];
  $query_result = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id WHERE t.in_charge='$userID' AND tl.task_class!=4");
  $dataList = [];
  $counter  = 0; ?>
  <input type="hidden" name="viewTableID" id="viewTableID" value="<?php echo $userID ?>">
  <table id="viewList" class="table table-striped">
    <thead class="table-success">
      <tr>
        <th>#</th>
        <th>Task Name</th>
        <th>Task Class</th>
        <th>Task Details</th>
        <th>Condition</th>
        <th>Due Date</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody id="viewTasklist">
      <?php while ($row = mysqli_fetch_assoc($query_result)) {
        $counter += 1;
        if ($row['requirement_status'] == 1) {
          $requirement  = '<span class="badge badge-primary">File Attachment</span>';
        } else {
          $requirement  = '<span class="badge badge-secondary">None</span>';
        }
        $id = $row['id'];
        $action = '
        <button type="button" class="btn btn-danger btn-sm btn-block" onclick="RemoveTaskView(this)" value="' . $id . '"><i class="fas fa-trash fa-fw"></i> Remove</button>
        <button type="button" class="btn btn-info btn-sm btn-block" onclick="EditTaskView(this)" value="' . $id . '" data-name="' . $row['task_name'] . '" data-date="' . $row['submission'] . '" data-condition="' . $row['requirement_status'] . '" data-for="' . $row['in_charge'] . '" data-class="' . $row['task_class'] . '"><i class="fas fa-pencil-alt fa-fw"></i> Edit</button>
      '; ?>
        <tr>
          <td><?php echo $counter ?></td>
          <td><?php echo $row['task_name'] ?></td>
          <td><?php echo getTaskClass($row['task_class']); ?></td>
          <td id='td-table-shrink'><?php echo $row['task_details'] ?></td>
          <td><?php echo $requirement ?></td>
          <td><?php echo $row['submission'] ?></td>
          <td><?php echo $action ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
<?php
}
if (isset($_POST['editTask'])) {
  $error                = false;
  $editTask_id          = $_POST['edit_task'];
  $editTask_requirement = $_POST['edit_requirement_value'];
  $editTask_duedate     = $_POST['edit_duedate'];
  if ($editTask_requirement === '' || $editTask_duedate === '') {
    $error = true;
    echo "Empty field has been detected! Please try again.";
  }
  if (!$error) {
    $query_result = mysqli_query($con, "UPDATE tasks SET requirement_status='$editTask_requirement', submission='$editTask_duedate' WHERE id='$editTask_id'");
    if ($query_result) {
      echo "Success";
    }
  }
}
if (isset($_POST['taskDelete'])) {
  $taskID = $_POST['deleteID'];
  $query_result = mysqli_query($con, "DELETE FROM `tasks` WHERE id='$taskID'");
  if ($query_result) {
    echo "Success";
  } else {
    echo "Unable to complete the operation. Please try again later.";
  }
}
if (isset($_GET['taskDownload'])) {
  header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
  header("Content-Disposition: attachment; filename=" . $username . "_TASKS.xls");
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
          $result = mysqli_query($con, "SELECT * FROM task_list tl JOIN task_class tc ON tl.task_class=tc.id JOIN tasks t ON tl.id=t.task_id WHERE t.in_charge='$username' AND tl.task_class!=4");
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
<?php }
if (isset($_POST['EditTaskView'])) {
  $id = $_POST['editID'];
  $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id WHERE t.id='$id'"));
  if ($row['task_class'] == 2) {
    $daysArray = explode(', ', $row['submission']);
  } ?>
  <div class="form-group">
    <label>Assignee:</label>
    <div class="input-group mb-2">
      <div class="input-group-prepend">
        <div class="input-group-text"><i class="fas fa-user"></i></div>
      </div>
      <input type="hidden" id="emptask_id" value="<?php echo $row['id']; ?>">
      <input type="text" id="emptask_for" class="form-control" value="<?php echo $row['in_charge']; ?>" readonly>
    </div>
  </div>
  <div class="form-group">
    <label>Task Name:</label>
    <div class="input-group mb-2">
      <div class="input-group-prepend">
        <div class="input-group-text"><i class="fas fa-tasks"></i></div>
      </div>
      <input type="text" id="emptask_name" class="form-control" value="<?php echo $row['task_name']; ?>" readonly>
    </div>
  </div>
  <div class="form-group">
    <label>Task Class:</label>
    <div class="input-group mb-2">
      <div class="input-group-prepend">
        <div class="input-group-text"><i class="fas fa-flag"></i></div>
      </div>
      <input type="datetime" id="emptask_class" class="form-control" value="<?php echo ($row['task_class'] == 1 ? 'DAILY ROUTINE' : ($row['task_class'] == 2 ? 'WEEKLY ROUTINE' : ($row['task_class'] == 3 ? 'MONTHLY ROUTINE' : ($row['task_class'] == 6 ? 'MONTHLY REPORT' : '')))); ?>" readonly>
    </div>
  </div>
  <div class="form-group">
    <label>File Attachment:</label>
    <div class="input-group mb-2">
      <label class="toggle-switchy" for="emptask_file" data-color="green" data-size="lg" data-label="left">
        <input type="checkbox" id="emptask_file" name="emptask_file" <?php if ($row['requirement_status'] == 1) echo 'checked'; ?>>
        <span class="toggle"><span class="switch"></span></span>
        <span class="label">Required</span>
      </label>
    </div>
  </div>
  <div class="form-group">
    <label>Due Date:</label>
    <div class="input-group mb-2">
      <?php if ($row['task_class'] == 1) { ?>
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
        </div>
        <input type="text" id="emptask_duedate" name="emptask_duedate" class="form-control" value="Weekdays" readonly>
      <?php } elseif ($row['task_class'] == 2) { ?>
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
        </div>
        <select class="form-control selectpicker show-tick" data-style="border-secondary" data-actions-box="true" name="emptask_duedate[]" id="emptask_duedate" multiple>
          <option value="Monday" <?php echo in_array('Monday', $daysArray) ? 'selected' : ''; ?>>Monday</option>
          <option value="Tuesday" <?php echo in_array('Tuesday', $daysArray) ? 'selected' : ''; ?>>Tuesday</option>
          <option value="Wednesday" <?php echo in_array('Wednesday', $daysArray) ? 'selected' : ''; ?>>Wednesday</option>
          <option value="Thursday" <?php echo in_array('Thursday', $daysArray) ? 'selected' : ''; ?>>Thursday</option>
          <option value="Friday" <?php echo in_array('Friday', $daysArray) ? 'selected' : ''; ?>>Friday</option>
        </select>
      <?php } elseif ($row['task_class'] == 3 || $row['task_class'] == 6) { ?>
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
        </div>
        <select class="form-control selectpicker show-tick" data-style="border-secondary" data-size="5" data-live-search="true" name="emptask_duedate" id="emptask_duedate">
          <?php for ($i = 1; $i <= 31; $i++) {
            $selected = ($row['submission'] == $i) ? 'selected' : ''; ?>
            <option value="<?php echo $i ?>" <?php echo $selected; ?>>Day <?php echo $i ?> of the Month</option>
          <?php } ?>
        </select>
      <?php } ?>
    </div>
  </div>
<?php }
?>