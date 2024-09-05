<?php
include('../include/auth.php');
date_default_timezone_set('Asia/Manila');

if (isset($_POST['actionView'])) {
  $id = $_POST['prj_id'];
  $query_result = mysqli_query($con, "SELECT project_list.*, accounts.fname, accounts.lname, accounts.file_name FROM project_list JOIN accounts ON accounts.id=project_list.leader WHERE project_list.id='$id'");
  $row = mysqli_fetch_assoc($query_result);
  $user_ids = $row['member'];
  if (empty($row['file_name'])) {
    $imageURL = '../assets/img/user-profiles/nologo.png';
  } else {
    $imageURL = '../assets/img/user-profiles/' . $row['file_name'];
  } ?>
  <div class="row">
    <div class="col-md-7">
      <input type="hidden" class="form-control" name="account_id" id="account_id">
      <div class="form-group">
        <label>Project Name:</label>
        <div class="input-group mb-2">
          <div class="input-group-prepend">
            <div class="input-group-text"><i class="fas fa-user-circle"></i></div>
          </div>
          <input type="hidden" name="project_id" id="project_id" value="<?php echo $row['id']; ?>">
          <input type="text" class="form-control" value="<?php echo $row['title']; ?>" readonly>
        </div>
      </div>
    </div>
    <div class="col-md-5">
      <div class="form-group">
        <label>Project Leader:</label>
        <div class="input-group mb-2">
          <div class="input-group-prepend">
            <div class="input-group-text"><img src="<?php echo $imageURL; ?>" alt="" srcset=""></div>
          </div>
          <input type="text" class="form-control" value="<?php echo ucwords(strtolower($row['fname'] . ' ' . $row['lname'])); ?>" readonly>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group">
        <label>Description:</label>
        <div class="input-group mb-2">
          <div class="input-group-prepend">
            <div class="input-group-text"><i class="fas fa-font"></i></div>
          </div>
          <textarea class="form-control" readonly><?php echo $row['details']; ?></textarea>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label>Start Date:</label>
        <div class="input-group mb-2">
          <div class="input-group-prepend">
            <div class="input-group-text"><i class="fas fa-calendar-day"></i></div>
          </div>
          <input type="date" class="form-control" value="<?php echo $row['start']; ?>" readonly>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label>End Date:</label>
        <div class="input-group mb-2">
          <div class="input-group-prepend">
            <div class="input-group-text"><i class="fas fa-calendar-check"></i></div>
          </div>
          <input type="date" class="form-control" value="<?php echo $row['end']; ?>" readonly>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label>Status:</label>
        <div class="input-group mb-2">
          <div class="input-group-prepend">
            <div class="input-group-text"><i class="fas fa-flag"></i></div>
          </div>
          <input type="hidden" name="project_id" id="project_id" value="<?php echo $row['id']; ?>">
          <input type="text" class="form-control" value="<?php echo ucwords(strtolower($row['status'])) ?>" readonly>
        </div>
      </div>
    </div>
  </div>
  <div class="card mb-3">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between border-left-primary">
      <h6 class="m-0">Team Member/s:</h6>
    </div>
    <div class="card-body text-center">
      <ul class="users-list clearfix">
        <?php if (!empty($user_ids)) :
          $members = $con->query("SELECT *,concat(fname,' ',lname) AS name FROM accounts WHERE id IN ($user_ids) ORDER BY concat(fname,' ',lname) ASC");
          while ($row = $members->fetch_assoc()) :
            if (empty($row['file_name'])) {
              $memberAvatar = '../assets/img/user-profiles/nologo.png';
            } else {
              $memberAvatar = '../assets/img/user-profiles/' . $row['file_name'];
            } ?>
            <li>
              <img src="<?php echo $memberAvatar; ?>" alt="User Image" class="img-fluid">
              <span class="users-list-name"><?php echo ucwords($row['name']) ?></span>
            </li>
        <?php
          endwhile;
        endif; ?>
      </ul>
    </div>
  </div>
  <div class="card mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between border-left-primary">
      <h6 class="m-0">Task List:</h6>
      <div class="dropdown no-arrow">
        <?php if ($access != 2) { ?>
          <button type="button" onclick="createTask(this)" class="btn btn-sm">
            <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> Create New Task
          </button>
        <?php } ?>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive-sm">
        <table class="table table-striped" id="taskList" width="100%" cellspacing="0">
          <colgroup>
            <?php if ($access != 2) { ?>
              <col width="auto">
            <?php } ?>
            <col width="auto">
            <col width="auto">
            <col width="auto">
            <col width="auto">
          </colgroup>
          <thead class='table'>
            <?php if ($access != 2) { ?>
              <th>Action</th>
            <?php } ?>
            <th>#</th>
            <th>Task</th>
            <th>Status</th>
            <th>Created</th>
          </thead>
          <tbody>
            <?php $con->next_result();
            $query_result = mysqli_query($con, "SELECT * FROM project_task WHERE project_id='$id'");
            $count = 0;
            while ($row = $query_result->fetch_assoc()) { 
              $count += 1;?>
              <tr>
                <?php if ($access != 2) { ?>
                  <td>
                    <div class="btn-group dropright">
                      <button type="button" class="btn btn-block btn-sm btn-outline-primary dropdown-toggle" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i> Action</button>
                      <div class="dropdown-menu">
                        <button type="button" class="dropdown-item" onclick="taskView(this)" value="<?php echo $row['id'] ?>"><i class="fas fa-eye fa-fw"></i> View</button>
                        <div class="dropdown-divider"></div>
                        <button type="button" class="dropdown-item" onclick="taskEdit(this)" value="<?php echo $row['id'] ?>"><i class="fas fa-pencil-alt fa-fw"></i> Edit</button>
                        <div class="dropdown-divider"></div>
                        <button type="button" class="dropdown-item" onclick="taskDelete(this)" value="<?php echo $row['id'] ?>"><i class="fas fa-trash fa-fw"></i> Delete</button>
                      </div>
                    </div>
                  </td>
                <?php } ?>
                <td><?php echo $count; ?></td>
                <td><?php echo $row['task'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['details'] ?>"></i></td>
                <td><?php echo $row['status'] ?></td>
                <td><?php echo date('F d, Y', strtotime($row['created'])); ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="card mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between border-left-primary">
      <h6 class="m-0">Members Progress/Activity:</h6>
    </div>
    <div class="card-body">
      <?php $con->next_result();
      $query_result = mysqli_query($con, "SELECT project_productivity.*, project_task.task, accounts.file_name, concat(accounts.fname,' ',accounts.lname) as name FROM project_productivity JOIN accounts ON accounts.id=project_productivity.user_id JOIN project_task ON project_task.id=project_productivity.task_id WHERE project_productivity.project_id='$id' ORDER BY project_productivity.id DESC");
      if (mysqli_num_rows($query_result) > 0) {
        while ($row = $query_result->fetch_assoc()) {
          $name   = ucwords(strtolower($row['name']));
          $start  = date_format(date_create($row['date'] . ' ' . $row['start']), "F d, Y h:i a");
          $end    = date_format(date_create($row['date'] . ' ' . $row['end']), "F d, Y h:i a");
          if (empty($row['file_name'])) {
            $imageURL = '../assets/img/user-profiles/nologo.png';
          } else {
            $imageURL = '../assets/img/user-profiles/' . $row['file_name'];
          } ?>
          <div class="card mb-4 border-bottom-success">
            <div class="card-body custom-card">
              <div class="left-content col-3">
                <div class="display-8 font-weight-bold"><?php echo $row['task']; ?></div>
                <hr class="sidebar-divider my-0">
                <span id="span-img" class="mt-1"><img src="<?php echo $imageURL ?>" class="mr-1"><?php echo $name ?></span>
              </div>
              <div class="middle-content col-4">
                <span><i class="fas fa-calendar-day fa-fw"></i> <?php echo $start ?></span><br>
                <span><i class="fas fa-calendar-check fa-fw"></i> <?php echo $end ?></span><br>
                <span><i class="fas fa-stopwatch fa-fw"></i> <?php echo $row['rendered']; ?></span>
              </div>
              <div class="right-content text-left col-5">
                <div class="display-8 font-weight-bold"><?php echo $row['subject'] ?></div>
                <h6 class="font-weight-normal">
                  <i class="fas fa-quote-left fa-fw"></i>
                  <?php echo $row['comment']; ?>
                  <br>
                  <span class=" display-9"><i class="fas fa-link"></i> <a href="javascript:void(0)">Attachment Sample #1</a></span>
                </h6>
              </div>
            </div>
          </div>
      <?php }
      } ?>
    </div>
  </div>
  <?php
}
if (isset($_POST['createTask'])) {
  $project_id   = $_POST['project_id'];
  $task_name    = ucwords(strtolower($_POST['task_name']));
  $task_details = ucwords(strtolower($_POST['task_details']));
  $task_status  = $_POST['task_status'];
  $date_today   = date('Y-m-d');

  $query_insert = mysqli_query($con, "INSERT INTO `project_task` (`project_id`, `task`, `details`, `status`, `created`) VALUES ('$project_id', '$task_name', '$task_details', '$task_status', '$date_today')");
  if ($query_insert) {
    echo "Success";
  } else {
    echo "Unable to complete the operation. Please try again later.";
  }
}
if (isset($_POST['createActivity'])) {
  $task_id    = $_POST['task_id'];
  $project_id = $_POST['project_id'];
  $subject    = ucwords(strtolower($_POST['subject']));
  $date       = $_POST['date'];
  $start      = $_POST['start'];
  $end        = $_POST['end'];
  $comments   = ucwords(strtolower($_POST['comments']));

  $start_time = new DateTime($start);
  $end_time   = new DateTime($end);
  $interval   = $start_time->diff($end_time);
  $duration   = $interval->format('%h hours, %i minutes');
  $query_insert = mysqli_query($con, "INSERT INTO `project_productivity` (`project_id`, `task_id`, `comment`, `subject`, `date`, `start`, `end`, `rendered`, `user_id`) VALUES ('$project_id', '$task_id', '$comments', '$subject', '$date', '$start', '$end', '$duration', '$emp_id')");
  if ($query_insert) {
    echo "Success";
  } else {
    echo "Unable to complete the operation. Please try again later.";
  }
}
if (isset($_POST['actionEdit'])) {
  $id       = $_POST['prjID'];
  $dept_id  = $_POST['prjDept'];
  $query_result = mysqli_query($con, "SELECT project_list.*, accounts.file_name, accounts.username FROM project_list JOIN department ON department.dept_id=project_list.dept_id JOIN accounts ON accounts.id=project_list.leader WHERE project_list.id='$id' AND project_list.dept_id='$dept_id'");
  $row = mysqli_fetch_assoc($query_result);
  $start  = $row['start'];
  $end    = $row['end'];
  $status = $row['status'];
  $desc   = $row['details'];
  $leader = $row['leader'];
  $member = explode(',', $row['member']); ?>
  <form id="editProjectDetails" enctype="multipart/form-data">
    <div class="row">
      <div class="col-md-6">
        <input type="hidden" name="projectID" id="projectID" value="<?php echo $row['id']; ?>">
        <div class="form-group">
          <label>Task:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-font"></i></div>
            </div>
            <input type="text" class="form-control" name="projectTitle" id="projectTitle" value="<?php echo $row['title']; ?>">
          </div>
        </div>
        <div class="form-group">
          <label>Start Date:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-calendar-day"></i></div>
            </div>
            <input type="date" class="form-control" name="projectStart" id="projectStart" value="<?php echo $start; ?>">
          </div>
        </div>
        <div class="form-group">
          <label>Project Leader:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-user"></i></div>
            </div>
            <select name="projectLeader" id="projectLeader" class="form-control form-control-sm selectpicker show-tick" data-live-search="true" data-style="border-secondary" data-size="5" data-actions-box="true">
              <?php
              $con->next_result();
              $query_result = mysqli_query($con, "SELECT accounts.*, section.dept_id FROM accounts JOIN section ON section.sec_id=accounts.sec_id WHERE dept_id='$dept_id' AND access=2 ORDER BY accounts.fname ASC");
              while ($row = mysqli_fetch_array($query_result)) {
                $selected = ($row['id'] == $leader) ? 'selected' : ''; ?>
                <option value="<?php echo $row['id']; ?>" data-subtext="<?php echo $row['username']; ?>" <?php echo $selected; ?>>
                  <?php echo ucwords(strtolower($row['fname'] . ' ' . $row['lname'])) ?>
                </option>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label>Status:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-flag"></i></div>
            </div>
            <select name="projectStatus" id="projectStatus" class="form-control">
              <option value="PENDING" <?php echo ($status == 'PENDING') ? 'selected' : ''; ?>>Pending</option>
              <option value="ON HOLD" <?php echo ($status == 'ON HOLD') ? 'selected' : ''; ?>>On-Hold</option>
              <option value="DONE" <?php echo ($status == 'DONE') ? 'selected' : ''; ?>>Done</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label>End Date:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-calendar-check"></i></div>
            </div>
            <input type="date" class="form-control" name="projectEnd" id="projectEnd" value="<?php echo $end; ?>">
          </div>
        </div>
        <div class="form-group">
          <label>Project Members: <small class="text-danger d-none" id="label1">Cannot leave this blank.</small></label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-users"></i></div>
            </div>
            <select name="projectMembers[]" id="projectMembers" class="form-control form-control-sm selectpicker show-tick" data-live-search="true" data-style="border-secondary" data-size="5" data-actions-box="true" multiple>
              <?php
              $con->next_result();
              $query_result = mysqli_query($con, "SELECT accounts.*, section.dept_id FROM accounts JOIN section ON section.sec_id=accounts.sec_id WHERE dept_id='$dept_id' AND access=2 AND accounts.id!='$leader' ORDER BY accounts.fname ASC");
              while ($row = mysqli_fetch_array($query_result)) {
                $selected = in_array($row['id'], $member) ? 'selected' : ''; ?> ?>
                <option value="<?php echo $row['id']; ?>" data-subtext="<?php echo $row['username']; ?>" <?php echo $selected; ?>><?php echo ucwords(strtolower($row['fname'] . ' ' . $row['lname'])) ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label>Description:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-info"></i></div>
            </div>
            <textarea name="projectDesc" id="projectDesc" class="form-control"><?php echo $desc; ?></textarea>
          </div>
        </div>
      </div>
    </div>
  </form>
  <?php
}
if (isset($_POST['saveEdit'])) {
  $id       = $_POST['projectID'];
  $title    = ucwords(strtolower($_POST['projectTitle']));
  $start    = $_POST['projectStart'];
  $end      = $_POST['projectEnd'];
  $status   = $_POST['projectStatus'];
  $leader   = $_POST['projectLeader'];
  $members  = implode(',', $_POST['projectMembers']);
  $descrip  = ucwords(strtolower($_POST['projectDesc']));

  $query_update = mysqli_query($con, "UPDATE `project_list` SET `title`='$title', `details`='$descrip', `start`='$start', `end`='$end', `status`='$status', `leader`='$leader', `member`='$members' WHERE id='$id'");
  if($query_update){
    echo "Success";
  } else {
    echo "Unable to process your request. Please try again later.";
  }
}
?>