<?php
include('../include/auth.php');
include('../vendor/autoload.php');
date_default_timezone_set('Asia/Manila');

use PhpOffice\PhpSpreadsheet\IOFactory as ExcelIOFactory;

if (isset($_POST['approveTask'])) {
  $id           = $_POST['approveID'];
  $head_name    = $_POST['approveHead'];
  $head_comment = $_POST['approveComment'];
  $comment      = (strlen($head_comment) > 15) ? substr($head_comment, 0, 15) . '...' : $head_comment;
  $score        = $_POST['approveScore'];
  $inCharge     = $_POST['approveIncharge'];
  $taskCode     = $_POST['approveCode'];
  if ($score > 5 || $score == 0 || $score != floor($score)) {
    echo "Please provide a whole number score between 1 and 5, with 5 being the highest and 1 being the lowest.<br><b><i>Decimal scores are not allowed.</i></b>";
  } else {
    if ($head_comment == '' || $head_comment == NULL) {
      $head_comment = NULL;
    } else {
      $query_get = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.id='$id'");
      $row = mysqli_fetch_assoc($query_get);
      $username         = $row['in_charge'];
      $task_code        = $row['task_code'];
      $datetime_current = date('Y-m-d H:i:s');
      $action = mysqli_real_escape_string($con, "localStorage.setItem('activeTab', '#finished');window.location.href='tasks.php';");
      $query_insert = mysqli_query($con, "INSERT INTO `notification` (`user`, `icon`, `type`, `body`, `action`, `date_created`, `status`) VALUES ('$username', 'fas fa-exclamation', 'warning', '<b>$head_name</b> wrote a comment on your task <b>$task_code</b>: <i>$comment</i>', '$action', '$datetime_current', '1')");
    }
    $query_result = mysqli_query($con, "UPDATE tasks_details SET status='FINISHED', achievement='$score', head_name='$head_name', head_note='$head_comment' WHERE id='$id'");
    if ($query_result) {
      log_action("Task {$task_code} approved for review.");
      echo "Success";
    } else {
      echo "Unable to complete the operation. Please try again later.";
    }
  }
}

if (isset($_POST['viewTask'])) {
  $id = $_POST['taskID'];
  $query_result = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.id='$id'");
  while ($row = mysqli_fetch_assoc($query_result)) {
    $task_classes       = [1 => "DAILY ROUTINE", 2 => "WEEKLY ROUTINE", 3 => "MONTHLY ROUTINE", 4 => "ADDITIONAL TASK", 5 => "PROJECT", 6 => "MONTHLY REPORT"];
    $task_class         = $task_classes[$row['task_class']] ?? "UNKNOWN";
    $due_date           = date_format(date_create($row['due_date']), "F d, Y h:i a");
    $date_accomplished  = date_format(date_create($row['date_accomplished']), "F d, Y h:i a");
    $old_date           = date_format(date_create($row['old_date']), "F d, Y h:i a");
    $dueDated           = new DateTime($row['due_date']);
    $finDated           = new DateTime($row['date_accomplished']);
    $oldDated           = new DateTime($row['old_date']);

    $interval = $dueDated->diff($finDated);
    $total_duration = $interval->format('%h hours %i minutes');
    if ($dueDated > $oldDated) {
      $restats = 'Approved';
    } else {
      $restats = 'Rejected';
    }
    $findings           = '';
    $latesubtask        = '<h6>The task has been submitted late by approximately ' . $total_duration . '.</h6>';
    $reschedtask        = 'Original Due Date: <h6>' . $old_date . '</h6>Reason for Rescheduling: <h6>' . $row['reason'] . '</h6>Reschedule Remarks: <h6>' . $restats . '</h6>';

    if ($finDated->setTime(0, 0, 0) > $dueDated->setTime(0, 0, 0)) {
      $findings .= '<span class="badge badge-danger" data-toggle="popover" data-trigger="hover" data-html="true" title="Task Submission Delay" data-placement="left" data-content="' . $latesubtask . '">Late Submission</span>';
    }
    if ($row['old_date'] !== NULL) {
      $findings .= '<span class="badge badge-warning" data-toggle="popover" data-trigger="hover" data-html="true" title="Rescheduled Activity" data-placement="left" data-content="' . $reschedtask . '">Rescheduled</span>';
    } ?>
    <form id="checkDetails" enctype="multipart/form-data">
      <div class="row justify-content-between">
        <div class="col-md-5">
          <div class="form-group">
            <label>Assignee</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-user"></i></div>
              </div>
              <input type="hidden" name="approveIncharge" id="approveIncharge" value="<?php echo $row['in_charge']; ?>" readonly>
              <input type="text" class="form-control" value="<?php echo getName($row['in_charge']); ?>" name="approveFname" id="approveFname" readonly>
            </div>
          </div>
        </div>
        <?php if ($findings !== '') { ?>
          <div class="col-md-2">
            <div class="form-group">
              <label>Findings</label>
              <?php echo $findings; ?>
            </div>
          </div>
        <?php } ?>
      </div>
      <div class="row">
        <input type="hidden" name="approveID" id="approveID" value="<?php echo $row['id'] ?>">
        <input type="hidden" name="approveHead" id="approveHead" value="<?php echo $full_name ?>">
        <div class="col-md-3">
          <div class="form-group">
            <label>Code</label>
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
            <label>Task</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-tasks"></i></div>
              </div>
              <input type="text" class="form-control" name="approveTitle" id="approveTitle" value="<?php echo $row['task_name'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <label>Achievement <i class="fas fa-question-circle text-info" data-toggle="popover" data-trigger="hover" data-html="true" title="Rating Criteria" data-placement="left" data-content="Rating 5: 105% Achievement<br>Rating 4: 100% Achievement<br>Rating 3: 90% Achievement<br>Rating 2: 80% Achievement<br>Rating 1: 70% Achievement"></i></label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-trophy"></i></div>
              </div>
              <input type="number" class="form-control text-danger" name="approveScore" id="approveScore" value="<?php echo $row['achievement'] ?>" min="1" max="5">
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label>Requirements</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-info"></i></div>
              </div>
              <textarea class="form-control" name="approveDetails" id="approveDetails" readonly> <?php echo $row['task_details'] ?> </textarea>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Due Date</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-calendar-day"></i></div>
              </div>
              <input type="datetime-local" class="form-control" name="approveDue" id="approveDue" value="<?php echo $row['due_date'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Date Started</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-hourglass-start"></i></div>
              </div>
              <input type="datetime-local" class="form-control" name="approveFinish" id="approveFinish" value="<?php echo $row['date_start'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Date Accomplished</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-hourglass-end"></i></div>
              </div>
              <input type="datetime-local" class="form-control" name="approveFinish" id="approveFinish" value="<?php echo $row['date_accomplished'] ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label>Remarks</label>
            <textarea class="form-control" name="approveRemarks" id="approveRemarks" rows="5" readonly><?php echo $row['remarks'] ?></textarea>
          </div>
        </div>
        <?php if ($row['requirement_status'] == 1) { ?>
          <div class="col-md-12">
            <div class="form-group">
              <label>Attachments</label>
              <div class="table-responsive">
                <table class="table table-borderless">
                  <tbody id="taskReview_files">
                    <?php
                    $task_code  = $row['task_code'];
                    $query_result = mysqli_query($con, "SELECT * FROM task_files WHERE task_code='$task_code'");
                    while ($row = mysqli_fetch_assoc($query_result)) {
                      $size   = formatSize($row['file_size']);
                      $action = '<button type="button" class="btn btn-block btn-success" value="' . $row['id'] . '" onclick="downloadFile(this)"><i class="fas fa-file-download fa-fw"></i> Download</button>';
                      $action .= (in_array(strtolower($row['file_type']), ['pdf', 'jpg', 'png', 'jpeg', 'xlsx'])) ? ' <button type="button" class="btn btn-block btn-info" value="' . $row['id'] . '" onclick="viewFile(this)"><i class="fas fa-eye fa-fw"></i> View</button>' : '';
                      $date   = date_format(date_create($row['file_dated']), "Y-m-d h:i a");
                    ?>
                      <tr>
                        <td><?php echo $row['file_name'] ?></td>
                        <td><?php echo $size ?></td>
                        <td><?php echo $date ?></td>
                        <td class="col-1 text-truncate"><?php echo $action ?></td>
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
            <label>Comment</label>
            <div class="input-group mb-2">
              <textarea class="form-control" name="approveComment" id="approveComment" rows="4" placeholder="Write your comments here if needed..."></textarea>
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
    $task_code = getCode($taskID);
    $query_result = mysqli_query($con, "UPDATE tasks_details SET status='FINISHED', head_name='$head_name' WHERE id='$taskID'");
    if ($query_result) {
      log_action("Task {$task_code} approved for review using bulk approval operation.");
      $count += 1;
    }
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

  $query = "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN section s ON tl.task_for=s.sec_id JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.task_status=1 AND td.status='REVIEW' AND s.dept_id = '$dept_id'";
  if ($date_to != NULL && $date_from != NULL) {
    $query .= " AND DATE(td.date_accomplished) >= '$date_from' AND DATE(td.date_accomplished) <= '$date_to'";
  }
  if ($taskClass != '') {
    $query .= " AND tl.task_class='$taskClass'";
  }
  $result = mysqli_query($con, $query);
  if (mysqli_num_rows($result) > 0) {
    while ($row = $result->fetch_assoc()) {
      $due_date           = date_format(date_create($row['due_date']), "F d, Y h:i a");
      $date_accomplished  = date_format(date_create($row['date_accomplished']), "F d, Y h:i a");
    ?>
      <tr>
        <td><input type="checkbox" name="selected_ids[]" class="form-control" value="<?php echo $row['id']; ?>"></td>
        <td class="text-truncate"><?php echo $row['task_code'] ?></td>
        <td>
          <?php echo $row['task_name']; ?>
          <i class='fas fa-info-circle' data-toggle='tooltip' data-placement='right' title='<?php echo $row['task_details']; ?>'></i>
          <?php if ((new DateTime($row['date_accomplished']))->setTime(0, 0, 0) > (new DateTime($row['due_date']))->setTime(0, 0, 0)): ?>
            <i class='fas fa-hourglass-end text-danger' data-toggle='tooltip' data-placement='right' title='Late Submission'></i>
          <?php endif; ?>
          <?php if ($row['requirement_status'] == 1): ?>
            <i class="fas fa-photo-video text-warning" data-toggle="tooltip" data-placement="right" title="File Attachment Required"></i> <?php endif; ?>
          <?php if ($row['old_date'] !== NULL): ?>
            <i class='fas fa-sync text-warning' data-toggle='tooltip' data-placement='right' title='Rescheduled'></i>
          <?php endif; ?>
        </td>
        <td><?php echo getTaskClass($row['task_class']); ?></td>
        <td class="text-truncate"><?php echo $due_date ?></td>
        <td class="text-truncate"><?php echo $date_accomplished ?></td>
        <td class="text-truncate"><?php echo getUser($row['in_charge']); ?></td>
        <td class="text-truncate"><button type="button" onclick="checkTask(this)" class="btn btn-warning btn-block" value="<?php echo $row['id'] ?>" data-name="<?php echo $row['task_name'] ?>"><i class="fas fa-star fa-fw"></i> Review</button></td>
      </tr>
<?php }
  }
}

if (isset($_GET['getFile'])) {
  $id = $_GET['id'];
  $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT file_target, file_type, file_owner FROM task_files WHERE id='$id'"));
  $assignee = $row['file_owner'];
  $file     = $row['file_target'];

  $fileType = $row['file_type'];
  $filePath =  "../files/$assignee/$file";

  echo json_encode([
    'filePath' => $filePath,
    'fileType' => $fileType
  ]);
}

if (isset($_GET['file'])) {
  $filePath = $_GET['file'];

  // Get the file extension to determine file type
  $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

  if ($fileExtension === 'xlsx') {
    // Handle Excel file
    try {
      $spreadsheet = ExcelIOFactory::load($filePath);
      $worksheet = $spreadsheet->getActiveSheet();

      echo '<table class="table table-bordered">';
      foreach ($worksheet->getRowIterator() as $row) {
        echo '<tr>';
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false); // Include empty cells

        foreach ($cellIterator as $cell) {
          echo '<td>' . $cell->getValue() . '</td>';
        }
        echo '</tr>';
      }
      echo '</table>';
    } catch (Exception $e) {
      echo 'Error loading Excel file: ', $e->getMessage();
    }
  } else {
    echo 'Unsupported file type.';
  }
}
?>