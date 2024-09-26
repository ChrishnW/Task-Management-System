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
      $query_get = mysqli_query($con, "SELECT * FROM tasks_details WHERE id='$id'");
      $row = mysqli_fetch_assoc($query_get);
      $username         = $row['in_charge'];
      $task_code        = $row['task_code'];
      $datetime_current = date('Y-m-d H:i:s');
      $action = mysqli_real_escape_string($con, "localStorage.setItem('activeTab', '#finished');window.location.href='tasks.php';");
      $query_insert = mysqli_query($con, "INSERT INTO `notification` (`user`, `icon`, `type`, `body`, `action`, `date_created`, `status`) VALUES ('$username', 'fas fa-exclamation', 'warning', '<b>$head_name</b> wrote a comment on your task <b>$task_code</b>: <i>$comment</i>', '$action', '$datetime_current', '1')");
    }
    $query_result = mysqli_query($con, "UPDATE tasks_details SET status='FINISHED', achievement='$score', head_name='$head_name', head_note='$head_comment' WHERE id='$id'");
    if ($query_result) {
      log_action("You reviewed and approved task {$taskCode} for user {$inCharge} successfully.");
      echo "Success";
    } else {
      echo "Unable to complete the operation. Please try again later.";
    }
  }
}

if (isset($_POST['viewTask'])) {
  $id = $_POST['taskID'];
  $query_result = mysqli_query($con, "SELECT DISTINCT tasks_details.*, tasks.task_details, CONCAT(accounts.fname,' ',accounts.lname) AS Mname FROM tasks_details JOIN accounts ON tasks_details.in_charge = accounts.username JOIN tasks ON tasks_details.task_name = tasks.task_name WHERE tasks_details.id='$id'");
  while ($row = mysqli_fetch_assoc($query_result)) {
    $task_classes       = [1 => "DAILY ROUTINE", 2 => "WEEKLY ROUTINE", 3 => "MONTHLY ROUTINE", 4 => "ADDITIONAL TASK", 5 => "PROJECT", 6 => "MONTHLY REPORT"];
    $task_class         = $task_classes[$row['task_class']] ?? "UNKNOWN";
    $due_date           = date_format(date_create($row['due_date']), "F d, Y h:i a");
    $date_accomplished  = date_format(date_create($row['date_accomplished']), "F d, Y h:i a");
    $dueDated           = new DateTime($row['due_date']);
    $finDated           = new DateTime($row['date_accomplished']);
    $findings           = '';
    if ($finDated > $dueDated) {
      $findings .= '<span class="badge badge-danger">Late Submission</span>';
    }
    if ($row['old_date'] !== NULL) {
      $findings .= '<span class="badge badge-warning">Rescheduled</span>';
    } ?>
    <form id="checkDetails" enctype="multipart/form-data">
      <div class="row justify-content-between">
        <div class="col-md-5">
          <div class="form-group">
            <label>Assignee:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-user"></i></div>
              </div>
              <input type="hidden" name="approveIncharge" id="approveIncharge" value="<?php echo $row['in_charge']; ?>" readonly>
              <input type="text" class="form-control" value="<?php echo ucwords(strtolower($row['Mname'])) ?>" name="approveFname" id="approveFname" readonly>
            </div>
          </div>
        </div>
        <?php if ($findings !== '') { ?>
          <div class="col-md-2">
            <div class="form-group">
              <label>Findings:</label>
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
                      $size   = formatSize($row['file_size']);
                      $action = '<button type="button" class="btn btn-circle btn-success" value="' . $row['id'] . '" onclick="downloadFile(this)"><i class="fas fa-file-download"></i></button>';
                      $action .= (in_array($row['file_type'], ['pdf', 'jpg', 'png', 'jpeg', 'xlsx'])) ? ' <button type="button" class="btn btn-circle btn-info" value="' . $row['id'] . '" onclick="viewFile(this)"><i class="fas fa-eye"></i></button>' : '';
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
        <div class="col-md-12">
          <div class="form-group">
            <label>Comment:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-comments"></i></div>
              </div>
              <textarea class="form-control" name="approveComment" id="approveComment" placeholder="Write your comments here if needed..."></textarea>
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

  $query = "SELECT DISTINCT tasks_details.*, accounts.file_name, tasks.task_details, section.dept_id, CONCAT(accounts.fname,' ',accounts.lname) AS Mname FROM tasks_details JOIN accounts ON tasks_details.in_charge = accounts.username JOIN tasks ON tasks_details.task_name = tasks.task_name JOIN section ON tasks_details.task_for = section.sec_id WHERE tasks_details.task_status=1 AND tasks_details.status='REVIEW' AND section.dept_id = '$dept_id'";
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
      $date_accomplished  = date_format(date_create($row['date_accomplished']), "Y-m-d h:i a");
      $assignee   = '<img src=' . $assigneeURL . ' class="img-table-solo"> ' . ucwords(strtolower($row['Mname'])) . '';
      $icon = "<i class='fas fa-info-circle' data-toggle='tooltip' data-placement='right' title='{$row['task_details']}'></i>";
      if (new DateTime($row['date_accomplished']) > new DateTime($row['due_date'])) {
        $icon .= " <i class='fas fa-hourglass-end text-danger' data-toggle='tooltip' data-placement='right' title='Late Submission'></i>";
      }
      if ($row['requirement_status'] == 1) {
        $icon .= " <i class='fas fa-paperclip text-success' data-toggle='tooltip' data-placement='right' title='Attachment'></i>";
      }
      if ($row['old_date'] !== NULL) {
        $icon .= " <i class='fas fa-sync text-warning' data-toggle='tooltip' data-placement='right' title='Rescheduled'></i>";
      } ?>
      <tr>
        <td><input type="checkbox" name="selected_ids[]" class="form-control" value="<?php echo $row['id']; ?>"></td>
        <td><button type="button" onclick="checkTask(this)" class="btn btn-success btn-sm btn-block" value="<?php echo $row['id'] ?>" data-name="<?php echo $row['task_name'] ?>"><i class="fas fa-bars"></i> Review</button></td>
        <td><?php echo $row['task_code'] ?></td>
        <td><?php echo $row['task_name'] . ' ' . $icon ?></td>
        <td><?php echo $task_class ?></td>
        <td><?php echo $due_date ?></td>
        <td><?php echo $date_accomplished ?></td>
        <td><?php echo $assignee ?></td>
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