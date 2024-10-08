<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <?php if ($access == 1) { ?>
    <div class="card">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold">User Management</h6>
        <div class="dropdown no-arrow">
          <button type="button" onclick="showCreate(this)" class="btn btn-primary">
            <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> New Account
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
            <thead class='table'>
              <tr>
                <td></td>
                <th>Username</th>
                <th>Name</th>
                <th>Department & Section</th>
                <th>Access</th>
                <th>Status</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php $con->next_result();
              $result = mysqli_query($con, "SELECT * FROM department d JOIN section s ON s.dept_id=d.dept_id JOIN accounts ac ON ac.sec_id=s.sec_id JOIN access a ON ac.access=a.id WHERE a.access!='admin'");
              if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                  $imageURL = empty($row["img"]) ? '../assets/img/user-profiles/nologo.png' : '../assets/img/user-profiles/' . $row["img"];
                  $status = $row['status'] == 1 ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-danger'>Inactive</span>";
              ?>
                  <tr>
                    <td><input type="checkbox" name="usernames[]" id="usernames" class="form-control" value="<?php echo $row['username']; ?>" onchange="transferAccount(this)"></td>
                    <td><?php echo $row['username']; ?></td>
                    <td id="td-table"><img src="<?php echo $imageURL; ?>" class="img-table"><?php echo $row['fname'] . ' ' . $row['lname']; ?></td>
                    <td>
                      <p class="form-text text-primary"><?php echo $row['dept_name']; ?></p><?php echo $row['access'] != 'head' ? $row['sec_name'] : ''; ?>
                    </td>
                    <td>
                      <select class="form-control selectpicker show-tick" data-user="<?php echo $row['username']; ?>" onchange="changeAccess(this)">
                        <?php $level = mysqli_query($con, "SELECT * FROM access WHERE id!=1 ORDER BY access ASC");
                        while ($level_row = $level->fetch_assoc()) {
                          $selected = ($level_row['access'] == $row['access']) ? 'selected' : ''; ?>
                          <option value="<?php echo $level_row['id']; ?>" <?php echo $selected; ?>><?php echo ucwords(strtolower($level_row['access'])); ?></option>
                        <?php } ?>
                      </select>
                    </td>
                    <td><?php echo $status ?></td>
                    <td>
                      <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-block dropdown-toggle" data-toggle="dropdown"><i class="fas fa-cog fa-fw"></i> Settings</button>
                        <div class="dropdown-menu">
                          <button type="button" class="dropdown-item" value="<?php echo $row['username']; ?>" onclick="accountEdit(this)"><i class="fas fa-cog fa-fw"></i> Edit information</button>
                          <div class="dropdown-divider"></div>
                          <?php echo $row['status'] == 0 ? '<button type="button" class="dropdown-item" value="1" data-user=' . $row['username'] . ' onclick="changeStatus(this)"><i class="fas fa-toggle-on fa-fw"></i> Activate user</button>' : '<button type="button" class="dropdown-item" value="0" data-user=' . $row['username'] . ' onclick="changeStatus(this)"><i class="fas fa-toggle-off fa-fw"></i> Deactivate user</button>'; ?>
                          <button type="button" class="dropdown-item" disabled><i class="fas fa-trash fa-fw"></i> Delete user</button>
                        </div>
                      </div>
                    </td>
                  </tr>
              <?php }
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php } elseif ($access == 2) { ?>
  <?php } elseif ($access == 3) { ?>
    <div class="card">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
        <h6 class="m-0 font-weight-bold text-white">Deparment Members</h6>
        <div class="dropdown no-arrow">
          <button type="button" onclick="showCreate(this)" class="btn btn-primary">
            <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> Register New Member
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
            <thead class='table table-success'>
              <tr>
                <th>Action</th>
                <th>Username</th>
                <th>Name</th>
                <th>Section & Department</th>
                <th>Total Tasks</th>
                <th>Status</th>
              </tr>
            </thead>
            <tfoot class='table table-success'>
              <tr>
                <th>Action</th>
                <th>Username</th>
                <th>Name</th>
                <th>Section & Department</th>
                <th>Total Tasks</th>
                <th>Status</th>
              </tr>
            </tfoot>
            <tbody>
              <?php $con->next_result();
              $result = mysqli_query($con, "SELECT accounts.fname, accounts.lname, accounts.file_name , accounts.username, accounts.email, section.sec_name, access.access, accounts.status, accounts.id, department.dept_name, section.sec_id FROM accounts LEFT JOIN section ON accounts.sec_id=section.sec_id LEFT JOIN access on accounts.access=access.id LEFT JOIN department ON department.dept_id=section.dept_id WHERE department.dept_id='$dept_id' AND accounts.access=2");
              if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                  $member = $row['username'];
                  $query = mysqli_query($con, "SELECT COUNT(id) AS total_task FROM tasks WHERE tasks.in_charge='$member' AND tasks.task_class!=4");
                  $query_result = mysqli_fetch_assoc($query);
                  if (empty($row['file_name'])) {
                    $imageURL = '../assets/img/user-profiles/nologo.png';
                  } else {
                    $imageURL = '../assets/img/user-profiles/' . $row['file_name'];
                  }
                  if ($row['status'] == 1) {
                    $status = 'Active';
                    $btn = 'success';
                  } else {
                    $status = 'Inactive';
                    $btn = 'danger';
                  } ?>
                  <tr>
                    <td>
                      <center /><button type="button" class="btn btn-info btn-block" value="<?php echo $row['username']; ?>" onclick="checkTasks(this)"><i class="fas fa-eye fa-fw"></i> View</button>
                      <button type="button" class="btn btn-success btn-block" value="<?php echo $row['username']; ?>" data-for="<?php echo $row['sec_id']; ?>" onclick="addTasks(this)"><i class="fas fa-plus fa-fw"></i> Add Task</button>
                    </td>
                    <td><?php echo $row['username']; ?></td>
                    <td id="td-table"><img src="<?php echo $imageURL; ?>" class="img-table"><?php echo $row['fname'] . ' ' . $row['lname']; ?></td>
                    <td><?php echo $row['sec_name']; ?> <p class="form-text text-danger"><?php echo $row['dept_name']; ?></p>
                    </td>
                    <td>
                      <p class="badge badge-primary"><?php echo $query_result['total_task'] ?> Assigned Tasks</p>
                    </td>
                    <td><span class="badge badge-<?php echo $btn ?>"><?php echo $status ?></span></td>
                  </tr>
              <?php }
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php } ?>
</div>

<div class="modal fade" id="viewTable" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
    <div class="modal-content border-info">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="assignee_name">View Assigned Task</h5>
        <div class="dropdown no-arrow">
          <button type="button" onclick="taskDownload(this)" class="btn btn-sm btn-success"><i class="fas fa-file-excel fa-fw"></i> Download</button>
        </div>
      </div>
      <div class="modal-body" id="viewAssignedTaskTable">
      </div>
      <div class="modal-footer">
        <button type="button" id="destroyTable" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="edit" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content border-info">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">Task Properties</h5>
      </div>
      <div class="modal-body" id="taskPropertiesDetails">

      </div>
      <div class="modal-footer">
        <button type="button" onclick="editTask(this)" class="btn btn-success" id="emptask_id">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="addtask" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content border-primary">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Assign Task</h5>
      </div>
      <div class="modal-body" id="taskDetails">
        <form id="taskAddDetails" enctype="multipart/form-data">
          <div class="form-group">
            <label>Assignee:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-user"></i></div>
              </div>
              <input type="text" id="assigneeID" name="assigneeID" class="form-control" readonly>
              <input type="hidden" id="assigneeSEC" name="assigneeSEC" class="form-control" readonly>
            </div>
          </div>
          <div class="form-group">
            <label>Task Class:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-flag"></i></div>
              </div>
              <select name="assignClass" id="assignClass" class="form-control selectpicker show-tick" data-style="border-secondary" data-size="4" onchange="taskList(this);">
                <option value="" selected disabled>--Select Task Class--</option>
                <?php $query = mysqli_query($con, "SELECT * FROM task_class WHERE id!=4 AND id!=5");
                while ($row = mysqli_fetch_assoc($query)) { ?>
                  <option value="<?php echo $row['id'] ?>" data-subtext="Class <?php echo $row['id'] ?>"><?php echo ucwords(strtolower($row['task_class'])) ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Task Name:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-tasks"></i></div>
              </div>
              <select name="assignList[]" id="assignList" class="form-control selectpicker show-tick" data-style="border-secondary" data-size="5" data-live-search="true" data-width="fit" data-actions-box="true" data-selected-text-format="count > 3" data-header="Select a task/s" multiple>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>File Requirement:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-paperclip"></i></div>
              </div>
              <select name="assignFile" id="assignFile" class="form-control selectpicker show-tick" data-style="border-secondary">
                <option value="" selected disabled>Nothing selected</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Due Date:</label>
            <div class="input-group mb-2" id="dueDateContainer">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="assignTask(this)" class="btn btn-success" id="emptask_id">Assign</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="createAccount" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-secondary">
      <div class="modal-header">
        <h5 class="modal-title">Account Details</h5>
      </div>
      <div class="modal-body">
        <form id="accountRegister" enctype="multipart/form-data">
          <div class="form-row">
            <div class="form-group col-md-5">
              <label for="reg_user">Username</label>
              <input type="text" class="form-control text-uppercase" name="reg_user" id="reg_user" readonly>
            </div>
            <div class="form-group col-md-7">
              <label for="reg_id">Employee ID <small class="text-danger">*</small></label>
              <input type="text" class="form-control text-uppercase" name="reg_id" id="reg_id">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="reg_fname">First Name <small class="text-danger">*</small></label>
              <input type="text" class="form-control text-uppercase" name="reg_fname" id="reg_fname" onchange="genUsername();">
            </div>
            <div class="form-group col-md-6">
              <label for="reg_lname">Last Name <small class="text-danger">*</small></label>
              <input type="text" class="form-control text-uppercase" name="reg_lname" id="reg_lname" onchange="genUsername();">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-8">
              <label for="reg_email">Email <small class="text-danger">*</small></label>
              <input type="email" class="form-control" name="reg_email" id="reg_email">
            </div>
            <div class="form-group col-md-4">
              <label for="reg_access">Access <small class="text-danger">*</small></label>
              <select name="reg_access" id="reg_access" class="form-control selectpicker show-tick" data-style="border-secondary" title="Nothing" onchange="accountAccess(this);">
                <option data-divider="true"></option>
                <?php
                $access_list = mysqli_query($con, "SELECT * FROM access WHERE id!=1 ORDER BY access ASC");
                while ($access_row = mysqli_fetch_assoc($access_list)) { ?>
                  <option value="<?php echo $access_row['id'] ?>"> <?php echo ucwords($access_row['access']); ?></option>
                  <option data-divider="true"></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="reg_dept">Department <small class="text-danger">*</small></label>
            <select name="reg_dept" id="reg_dept" class="form-control selectpicker show-tick" data-live-search="true" data-size="5" data-style="border-secondary" title="Select department here..." onchange="deptList(this);">
              <option data-divider="true"></option>
              <option value="EMPTY" data-icon="fas fa-sync">Refresh Section</option>
              <option data-divider="true"></option>
              <?php
              $dept_list = mysqli_query($con, "SELECT d.* FROM department d JOIN section s ON s.dept_id=d.dept_id WHERE d.status=1 GROUP BY d.dept_id ORDER BY d.dept_name ASC");
              while ($dept_row = mysqli_fetch_assoc($dept_list)) { ?>
                <option value="<?php echo $dept_row['dept_id'] ?>"> <?php echo ucwords(strtolower($dept_row['dept_name'])); ?></option>
                <option data-divider="true"></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group" id="hideThis">
            <label for="reg_sect">Section <small class="text-danger">*</small></label>
            <select name="reg_sect" id="reg_sect" class="form-control selectpicker show-tick" data-live-search="true" data-size="5" data-style="border-secondary" title="Select section here...">
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info">Create Account</button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="accountPassword" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="accountUsername"></h5>
      </div>
      <div class="modal-body">
        <form method="POST">
          <div class="col-md-12">
            <label>Enter a new password for this account:</label>
            <input type="text" class="form-control" id="account_password" name="account_password" placeholder="New Password">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="updatePassword(this);" class="btn btn-success" name="account_update">Change Password</button>
        <button type="button" onclick="location.reload();" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="result" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Result</h5>
      </div>
      <div class="modal-body text-left" id="resultBody">
      </div>
      <div class="modal-footer">
        <button type="button" onclick="location.reload();" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include('../include/footer.php'); ?>

<script>
  function showCreate() {
    $('#createAccount').modal('show');
  }

  $('#dataTable').DataTable({
    "order": [
      [4, "asc"],
      [2, "asc"]
    ]
  });

  // Admin Functions
  function transferAccount(element) {
    const listUsernames = document.querySelectorAll('input[name="usernames[]"]:checked');
    const getUsernames = [];

    listUsernames.forEach(function(checkbox) {
      getUsernames.push(checkbox.value);
    });

    console.log(getUsernames);
    return getUsernames;
  }
  // End Admin Functions
</script>