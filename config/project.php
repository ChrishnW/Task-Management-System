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
          <input type="text" class="form-control" value="<?php echo $row['status']; ?>" readonly>
        </div>
      </div>
    </div>
  </div>
  <div class="card mb-3">
    <div class="card-header">
      Team Member/s:
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
              <img src="<?php echo $memberAvatar; ?>" alt="User Image">
              <a class="users-list-name" href="javascript:void(0)"><?php echo ucwords($row['name']) ?></a>
            </li>
        <?php
          endwhile;
        endif; ?>
      </ul>
    </div>
  </div>
  <div class="card mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0">Task List</h6>
      <div class="dropdown no-arrow">
        <button type="button" onclick="createTask(this)" class="btn btn-sm">
          <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> Create New Task
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive-sm">
        <table class="table table-striped" id="taskList" width="100%" cellspacing="0">
          <colgroup>
            <col width="5%">
            <col width="30%">
            <col width="10%">
            <col width="10%">
          </colgroup>
          <thead class='table'>
            <th>Action</th>
            <th>Task</th>
            <th>Status</th>
            <th>Created</th>
          </thead>
          <tbody>
            <?php $con->next_result();
            $query_result = mysqli_query($con, "SELECT * FROM project_task WHERE project_id='$id'");
            while ($row = $query_result->fetch_assoc()) { ?>
              <tr>
                <td>
                  <div class="btn-group dropright">
                    <button type="button" class="btn btn-block btn-sm btn-outline-primary dropdown-toggle" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i> Action</button>
                    <div class="dropdown-menu">
                      <button type="button" class="dropdown-item" onclick="actionEdit(this)" value="<?php echo $row['id'] ?>"><i class="fas fa-pencil-alt fa-fw"></i> Edit</button>
                      <div class="dropdown-divider"></div>
                      <button type="button" class="dropdown-item" onclick="actionDelete(this)" value="<?php echo $row['id'] ?>"><i class="fas fa-trash fa-fw"></i> Delete</button>
                    </div>
                  </div>
                </td>
                <td><?php echo $row['task'] ?></td>
                <td><?php echo $row['status'] ?></td>
                <td><?php echo $row['created'] ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="card mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0">Members Progress/Activity:</h6>
      <div class="dropdown no-arrow">
        <button type="button" class="btn btn-sm" onclick="addActivity(this)">
          <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> Add New Activity
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="card mb-4 border-left-info">
        <div class="card-body custom-card">
          <div class="left-content col-3">
            <div class="display-8 font-weight-bold">Juan Dela Cruz</div>
            <span>Task Name</span>
          </div>
          <div class="middle-content col-4">
            <span><i class="fas fa-calendar-day fa-fw"></i> August 01, 2024 07:00 am</span><br>
            <span><i class="fas fa-calendar-check fa-fw"></i> August 01, 2024 03:59 pm</span><br>
            <span><i class="fas fa-stopwatch fa-fw"></i> 9 hours</span>
          </div>
          <div class="right-content text-left col-5">
            <h6 class="font-weight-normal">
              Lorem ipsum odor amet, consectetuer adipiscing elit. Proin aliquam hendrerit nullam interdum venenatis. Cras netus class odio rutrum non. Pellentesque purus conubia ad vivamus magna felis. Tempus ridiculus nostra porttitor; dis hac est quis nec. Arcu porta sodales praesent ultricies integer consectetur eget.
              <br>
              <span class=" display-9"><i class="fas fa-link"></i> <a href="javascript:void(0)">Attachment Sample #1</a></span>
            </h6>
          </div>
        </div>
      </div>
      <div class="card mb-4 border-left-info">
        <div class="card-body custom-card">
          <div class="left-content col-3">
            <div class="display-8 font-weight-bold">Juan Dela Cruz</div>
            <span>Task Name</span>
          </div>
          <div class="middle-content col-4">
            <span><i class="fas fa-calendar-day fa-fw"></i> August 01, 2024 07:00 am</span><br>
            <span><i class="fas fa-calendar-check fa-fw"></i> August 01, 2024 03:59 pm</span><br>
            <span><i class="fas fa-stopwatch fa-fw"></i> 9 hours</span>
          </div>
          <div class="right-content text-left col-5">
            <h6 class="font-weight-normal">
              Lorem ipsum odor amet, consectetuer adipiscing elit. Proin aliquam hendrerit nullam interdum venenatis. Cras netus class odio rutrum non. Pellentesque purus conubia ad vivamus magna felis. Tempus ridiculus nostra porttitor; dis hac est quis nec. Arcu porta sodales praesent ultricies integer consectetur eget.
              <br>
              <span class=" display-9"><i class="fas fa-link"></i> <a href="javascript:void(0)">Attachment Sample #1</a></span>
            </h6>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php }

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
?>