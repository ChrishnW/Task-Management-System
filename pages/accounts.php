<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <?php if ($access == 1) { ?>
    <div class="card">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
        <h6 class="m-0 font-weight-bold text-white">Registered Accounts</h6>
        <div class="dropdown no-arrow">
          <button type="button" onclick="showCreate(this)" class="btn btn-primary">
            <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> Register New Account
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
                <th>Access</th>
                <th>Status</th>
              </tr>
            </thead>
            <tfoot class='table table-success'>
              <tr>
                <th>Action</th>
                <th>Username</th>
                <th>Name</th>
                <th>Section & Department</th>
                <th>Access</th>
                <th>Status</th>
              </tr>
            </tfoot>
            <tbody>
              <?php $con->next_result();
              $result = mysqli_query($con, "SELECT accounts.fname, accounts.lname, accounts.file_name , accounts.username, accounts.email, section.sec_name, access.access, accounts.status, accounts.id, department.dept_name FROM accounts LEFT JOIN section ON accounts.sec_id=section.sec_id LEFT JOIN access on accounts.access=access.id LEFT JOIN department ON department.dept_id=section.dept_id");
              if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
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
                      <center /><button type="button" class="btn btn-info" value="<?php echo $row['id']; ?>" onclick="accountEdit(this)"><i class="fas fa-pen fa-fw"></i> Edit</button>
                    </td>
                    <td><?php echo $row['username']; ?></td>
                    <td id="td-table"><img src="<?php echo $imageURL; ?>" class="img-table"><?php echo $row['fname'] . ' ' . $row['lname']; ?></td>
                    <td>
                      <?php if ($row['access'] != 'head') {
                        echo $row['sec_name'];
                      } ?>
                      <p class="form-text text-danger"><?php echo $row['dept_name']; ?></p>
                    </td>
                    <td><?php echo strtoupper($row['access']) ?></td>
                    <td><span class="badge badge-<?php echo $btn ?>"><?php echo $status ?></span></td>
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
          <input type="hidden" name="viewTableID" id="viewTableID">
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
<div class="modal fade" id="accountEdit" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content border-info">
      <div class="modal-header bg-info">
        <h5 class="modal-title text-white">Account Update</h5>
      </div>
      <form method="POST">
        <div class="modal-body">
          <div class="row">
            <div class="col">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Picture:</label>
                    <img class="img-profile mb-2" id="perview_image">
                    <input type="file" class="form-control-file mb-2" id="account_image" name="account_image">
                    <button type="button" class="btn btn-success ml-3 btn-sm" onclick="uploadImage(this)">Upload</button>
                    <button type="button" class="btn btn-secondary btn-sm" id="old_image" onclick="deleteImage(this)">Remove</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-10">
              <div class="row">
                <div class="col-md-4">
                  <input type="hidden" class="form-control" name="account_id" id="account_id">
                  <div class="form-group">
                    <label>User Name:</label>
                    <div class="input-group mb-2">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-user-circle"></i></div>
                      </div>
                      <input type="text" placeholder="Enter User Name" class="form-control" name="account_username" id="account_username">
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>First Name:</label>
                    <div class="input-group mb-2">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-font"></i></div>
                      </div>
                      <input type="text" placeholder="Enter First Name" class="form-control" name="account_fname" id="account_fname">
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Last Name:</label>
                    <div class="input-group mb-2">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-font"></i></div>
                      </div>
                      <input type="text" placeholder="Enter Last Name" class="form-control" name="account_lname" id="account_lname">
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Employee ID:</label>
                    <div class="input-group mb-2">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-id-card"></i></div>
                      </div>
                      <input type="text" placeholder="Enter Employee ID" class="form-control" name="account_number" id="account_number" disabled>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>ID Number:</label>
                    <div class="input-group mb-2">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-qrcode"></i></div>
                      </div>
                      <input type="text" placeholder="Enter ID Number" class="form-control" name="account_card" id="account_card">
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Access:</label>
                    <div class="input-group mb-2">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-key"></i></div>
                      </div>
                      <select class="form-control custom-select" name="account_access" id="account_access">
                        <?php
                        $con->next_result();
                        $sql = mysqli_query($con, "SELECT * FROM access WHERE id!=1 ORDER BY access ASC");
                        if (mysqli_num_rows($sql) > 0) {
                          while ($row = mysqli_fetch_assoc($sql)) { ?>
                            <option value='<?php echo $row['id'] ?>'><?php echo strtoupper($row['access']) ?></option>
                        <?php }
                        } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Section:</label>
                    <div class="input-group mb-2">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-users"></i></div>
                      </div>
                      <select name="account_section" id="account_section" class="form-control custom-select">
                        <?php
                        $con->next_result();
                        $sql = mysqli_query($con, "SELECT * FROM section WHERE status='1'");
                        if (mysqli_num_rows($sql) > 0) {
                          while ($row = mysqli_fetch_assoc($sql)) { ?>
                            <option value='<?php echo $row['sec_id'] ?>'><?php echo $row['sec_name'] ?></option>
                        <?php }
                        } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>E-mail:</label>
                    <div class="input-group mb-2">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-solid fa-at"></i></div>
                      </div>
                      <input type="text" placeholder="Enter E-mail" class="form-control" name="account_email" id="account_email">
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Password:</label>
                    <div>
                      <button type="button" class="btn btn-outline-danger btn-sm" onclick='resetPassword(this)'>Reset</button>
                      <button type="button" class="btn btn-outline-secondary btn-sm" onclick='changePassword(this)'>Change</button>
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Status:</label>
                    <div>
                      <button type="button" class="btn btn-sm" id='statusBtn' onclick='changeStatus(this)'>Active</button>
                      <button type="button" class="btn btn-outline-secondary btn-sm" id='account_delete' onclick='alert(this)'>Delete</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" onclick="accountUpdate(this)" class="btn btn-success" name="account_update">Update Account</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </div>
      </form>
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
<div class="modal fade" id="createAccount" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content border-primary">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Create New Account</h5>
      </div>
      <form method="POST">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>User Name:</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-user-circle"></i></div>
                  </div>
                  <input type="text" placeholder="Enter User Name" class="form-control" name="create_username" id="create_username">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>First Name:</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-font"></i></div>
                  </div>
                  <input type="text" placeholder="Enter First Name" class="form-control" name="create_fname" id="create_fname">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Last Name:</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-font"></i></div>
                  </div>
                  <input type="text" placeholder="Enter Last Name" class="form-control" name="create_lname" id="create_lname">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Employee ID:</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-id-card"></i></div>
                  </div>
                  <input type="text" placeholder="Enter Employee ID" class="form-control" name="create_number" id="create_number" disabled>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>ID Number:</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-qrcode"></i></div>
                  </div>
                  <input type="text" placeholder="Enter ID Number" class="form-control" name="create_card" id="create_card">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Access:</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-key"></i></div>
                  </div>
                  <select class="form-control selectpicker show-tick" data-style="border-secondary" name="create_access" id="create_access" onchange="accessLevel(this)">
                    <option value="" disabled selected>Select Access</option>
                    <option data-divider="true"></option>
                    <?php if ($access == 3) {
                      echo '<option value="2">Member</option>';
                      echo '<option value="4">Leader</option>';
                    } else {
                      $con->next_result();
                      $sql = mysqli_query($con, "SELECT * FROM access WHERE id!=1 ORDER BY access ASC");
                      if (mysqli_num_rows($sql) > 0) {
                        while ($row = mysqli_fetch_assoc($sql)) { ?>
                          <option value='<?php echo $row['id'] ?>'><?php echo ucwords(strtolower($row['access'])) ?></option>
                    <?php }
                      }
                    } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Department:</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-warehouse"></i></div>
                  </div>
                  <select name="create_department" id="create_department" class="form-control selectpicker show-tick" data-style="border-secondary" data-size="5" data-live-search="true" data-dropup-auto="false" title="Select Department" onchange="selectDepartment(this)">
                    <?php if ($access == 3) {
                      $con->next_result();
                      $sql = mysqli_query($con, "SELECT * FROM department WHERE status='1' AND dept_id='$dept_id' ORDER BY dept_name ASC");
                      if (mysqli_num_rows($sql) > 0) {
                        while ($row = mysqli_fetch_assoc($sql)) { ?>
                          <option value='<?php echo $row['dept_id'] ?>' data-subtext='<?php echo $row['dept_id'] ?>'><?php echo ucwords(strtolower($row['dept_name'])) ?></option>
                        <?php }
                      }
                    } else {
                      $con->next_result();
                      $sql = mysqli_query($con, "SELECT * FROM department WHERE status='1' ORDER BY dept_name ASC");
                      if (mysqli_num_rows($sql) > 0) {
                        while ($row = mysqli_fetch_assoc($sql)) { ?>
                          <option value='<?php echo $row['dept_id'] ?>' data-subtext='<?php echo $row['dept_id'] ?>'><?php echo ucwords(strtolower($row['dept_name'])) ?></option>
                    <?php }
                      }
                    } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Section:</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-users"></i></div>
                  </div>
                  <select name="create_section" id="create_section" class="form-control selectpicker show-tick" data-style="border-secondary" data-size="5" title="Select Section" data-live-search="true" data-dropup-auto="false">
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>E-mail:</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-solid fa-at"></i></div>
                  </div>
                  <input type="text" placeholder="Enter E-mail" class="form-control" name="create_email" id="create_email">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" onclick="accountCreate(this)" class="btn btn-success" name="create_update">Create Account</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </div>
      </form>
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
<div class="modal fade" id="success" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Success</h5>
      </div>
      <div class="modal-body text-center">
        <i class="far fa-check-circle fa-5x text-success"></i>
        <br><br>
        <p id="success_log"></p>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="location.reload();" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="warning" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Caution!</h5>
      </div>
      <div class="modal-body text-center">
        <input type="hidden" id="hidden_id">
        <i class="fas fa-exclamation-triangle fa-5x text-danger"></i>
        <br><br>
        You're about to delete this assignee's task, <br> do you still want to proceed?
      </div>
      <div class="modal-footer">
        <button type="button" onclick="RemoveTask(this)" class="btn btn-success">Proceed</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="danger" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Caution!</h5>
      </div>
      <div class="modal-body text-center">
        <input type="hidden" id="hidden_id">
        <i class="fas fa-exclamation-triangle fa-5x text-danger"></i>
        <br><br>
        You're about to delete this account, <br> do you still want to proceed?
      </div>
      <div class="modal-footer">
        <button type="button" onclick="accountDelete(this)" class="btn btn-success">Proceed</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="error" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Caution!</h5>
      </div>
      <div class="modal-body text-center">
        <i class="fas fa-sad-cry fa-5x text-danger"></i>
        <br><br>
        <p id="error_found"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include('../include/footer.php'); ?>

<script>
  $('#dataTable').DataTable({
    "order": [
      [2, "asc"]
    ]
  });

  function addTasks(element) {
    var assignee = element.value;
    var section = element.getAttribute('data-for');
    $(document).ready(function() {
      document.getElementById('assigneeID').value = assignee;
      document.getElementById('assigneeSEC').value = section;
      $('#addtask').modal('show');
    })
  }

  function taskList(element) {
    var task_for = document.getElementById('assigneeSEC').value;
    var task_class = element.value;
    var dueDateContainer = document.getElementById('dueDateContainer');

    if (task_class && task_for) {
      $.ajax({
        method: "POST",
        url: "../config/accounts.php",
        data: {
          "taskList": true,
          "task_class": task_class,
          "task_for": task_for,
        },
        success: function(response) {
          $("select[name='assignList[]']").html(response).selectpicker('refresh');
        }
      });
    }

    if (task_class === '1') {
      dueDateContainer.innerHTML = `
      <div class="input-group-prepend">
        <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
      </div>
      <input type="text" id="assignDue" name="assignDue" class="form-control" value="Weekdays" readonly>`;
    } else if (task_class === '2') {
      dueDateContainer.innerHTML = `
      <div class="input-group-prepend">
        <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
      </div>
      <select class="form-control selectpicker show-tick" data-style="border-secondary" data-actions-box="true" name="assignDue[]" id="assignDue" title="--Select a Day--" multiple>
        <option value="Monday">Monday</option>
        <option value="Tuesday">Tuesday</option>
        <option value="Wednesday">Wednesday</option>
        <option value="Thursday">Thursday</option>
        <option value="Friday">Friday</option>
      </select>`;
      $('.selectpicker').selectpicker('refresh');
    } else if (task_class === '3' || task_class === '6') {
      let options = '';
      for (let i = 1; i <= 31; i++) {
        options += `<option value="Day ${i} of the Month">Day ${i} of the Month</option>`;
      }
      dueDateContainer.innerHTML = `
      <div class="input-group-prepend">
        <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
      </div>
      <select class="form-control selectpicker show-tick" data-style="border-secondary" data-size="5" data-live-search="true" title="--Select a Date--" name="assignDue" id="assignDue">${options}</select>`;
      $('.selectpicker').selectpicker('refresh');
    }
  }

  function assignTask(element) {
    element.disabled = true;
    if (
      document.getElementById('assignClass').value === '' ||
      document.getElementById('assignList').value === '' ||
      document.getElementById('assignDue').value === '' ||
      document.getElementById('assignFile').value === ''
    ) {
      document.getElementById('error_found').innerHTML = 'Empty field has been detected!';
      $('#error').modal('show');
      element.disabled = false;
    } else {
      var formDetails = new FormData(document.getElementById('taskAddDetails'));
      formDetails.append('assignTask', true);
      $.ajax({
        method: "POST",
        url: "../config/accounts.php",
        data: formDetails,
        contentType: false,
        processData: false,
        success: function(response) {
          $('#resultBody').html(response);
          $('#result').modal('show');
        }
      });
    }
  }

  function checkTasks(element) {
    element.disabled = true;
    var assignee_id = element.value;
    // console.log(assignee_view);
    $.ajax({
      method: 'POST',
      url: '../config/assign_tasks.php',
      data: {
        "viewTaskEmp": true,
        "assignee_id": assignee_id,
      },
      success: function(response) {
        $('#viewAssignedTaskTable').html(response);
        $('#viewList').DataTable({
          "responsive": true
        });
        openSpecificModal('viewTable', 'modal-xl');
        element.disabled = false;
      }
    })
  }

  function taskDownload() {
    var viewTableID = document.getElementById('viewTableID').value;
    console.log(viewTableID);
    window.location.href = '../config/accounts.php?taskDownload=true&username=' + viewTableID;
  }

  function EditTaskView(element) {
    var editID = element.value;
    $.ajax({
      method: 'POST',
      url: '../config/assign_tasks.php',
      data: {
        "EditTaskView": true,
        "editID": editID
      },
      success: function(response) {
        $('#taskPropertiesDetails').html(response);
        $('#emptask_duedate').selectpicker('refresh');
        $('#edit').modal('show');
      }
    })
  }

  function editTask(element) {
    element.disabled          = true;
    var edit_task             = document.getElementById('emptask_id').value;
    var edit_taskName         = document.getElementById('emptask_name').value;
    var edit_requirement      = document.getElementById('emptask_file').checked ? 1 : 0;
    var edit_duedate_element  = document.getElementById('emptask_duedate');
    if (edit_duedate_element.multiple) {
      var edit_duedate = Array.from(edit_duedate_element.selectedOptions).map(option => option.value);
    } else {
      var edit_duedate = edit_duedate_element.value;
    }
    $.ajax({
      method: "POST",
      url: "../config/accounts.php",
      data: {
        "editTask": true,
        "edit_task": edit_task,
        "edit_taskName": edit_taskName,
        "edit_requirement": edit_requirement,
        "edit_duedate": edit_duedate,
      },
      success: function(response) {
        if (response === 'Success') {
          document.getElementById('success_log').innerHTML = edit_taskName + ' information task of '+ document.getElementById('emptask_for').value +' has been updated successfully.';
          $('#edit').modal('hide');
          $('#success').modal('show');
        } else {
          document.getElementById('error_found').innerHTML = response;
          $('#error').modal('show');
          element.disabled = false;
        }
      }
    })
  }

  function RemoveTaskView(element) {
    var tbdeleteID = element.value;
    document.getElementById('hidden_id').value = tbdeleteID;
    $('#warning').modal('show');
  }

  function RemoveTask(element) {
    element.disabled = true;
    var deleteID = document.getElementById('hidden_id').value;
    $.ajax({
      method: "POST",
      url: "../config/accounts.php",
      data: {
        'taskDelete': true,
        'deleteID': deleteID,
      },
      success: function(response) {
        if (response === 'Success') {
          document.getElementById('success_log').innerHTML = 'Operation completed successfully';
          $('#warning').modal('hide');
          $('#success').modal('show');
          $('#destroyTable').on('click', function() {
            dataTable.destroy();
          });
        } else {
          document.getElementById('error_found').innerHTML = response;
          $('#error').modal('show');
          element.disabled = false;
        }
      }
    })
  }

  function accountEdit(element) {
    var accountID = element.value;
    // console.log(accountID);
    $.ajax({
      method: "POST",
      url: "../config/accounts.php",
      data: {
        'accountEdit': true,
        'accountID': accountID,
      },
      success: function(respone) {
        // console.log(respone);
        $.each(respone, function(Key, value) {
          $('#account_id').val(value['id']);
          $('#account_username').val(value['username']);
          $('#account_fname').val(value['fname']);
          $('#account_lname').val(value['lname']);
          $('#account_number').val(value['emp_id']);
          $('#account_card').val(value['card']);
          $('#account_access').val(value['access']);
          $('#account_section').val(value['sec_id']);
          $('#account_email').val(value['email']);
          $('#account_delete').val(value['id']);
          $('#statusBtn').val(value['status']);
          $('#old_image').val(value['file_name']);
          if (value['file_name'] === null) {
            $("#perview_image").attr("src", "../assets/img/user-profiles/nologo.png");
          } else {
            $("#perview_image").attr("src", "../assets/img/user-profiles/" + value['file_name']);
          }
        });
        var checkStatus = document.getElementById('statusBtn').value;
        var btn = document.getElementById('statusBtn');
        if (checkStatus === '1') {
          btn.textContent = 'Active';
          btn.classList.add('btn-success');
        } else {
          btn.textContent = 'Inactive';
          btn.classList.add('btn-danger');
        }
        // console.log('Select Account:', checkStatus);
        $('#accountEdit').modal('show');
      }
    })
  }

  function resetPassword(element) {
    var resetID = document.getElementById('account_id').value;
    // console.log(resetID);
    $.ajax({
      method: "POST",
      url: "../config/accounts.php",
      data: {
        'accountReset': true,
        'resetID': resetID,
      },
      success: function(respone) {
        document.getElementById('success_log').innerHTML = 'Password has been reset to default 12345.';
        $('#accountEdit').modal('hide');
        $('#success').modal('show');
      }
    })
  }

  function changeStatus(element) {
    var statusID = document.getElementById('account_id').value;
    var status = element.value;
    // console.log(statusID);
    $.ajax({
      method: "POST",
      url: "../config/accounts.php",
      data: {
        'statusUpdate': true,
        'statusID': statusID,
        'status': status,
      },
      success: function(respone) {
        document.getElementById('success_log').innerHTML = 'Account status has been changed successfully.';
        $('#accountEdit').modal('hide');
        $('#success').modal('show');
      }
    })
  }

  function accountUpdate(element) {
    var updateID = document.getElementById('account_id').value;
    var updateUsername = document.getElementById('account_username').value;
    var updateFname = document.getElementById('account_fname').value;
    var updateLname = document.getElementById('account_lname').value;
    var updateNumber = document.getElementById('account_number').value;
    var updateCard = document.getElementById('account_card').value;
    var updateAccess = document.getElementById('account_access').value;
    var updateSection = document.getElementById('account_section').value;
    var updateEmail = document.getElementById('account_email').value;
    $.ajax({
      method: "POST",
      url: "../config/accounts.php",
      data: {
        'accountUpdate': true,
        'updateID': updateID,
        'updateUsername': updateUsername,
        'updateFname': updateFname,
        'updateLname': updateLname,
        'updateNumber': updateNumber,
        'updateCard': updateCard,
        'updateAccess': updateAccess,
        'updateSection': updateSection,
        'updateEmail': updateEmail,
      },
      success: function(respone) {
        document.getElementById('success_log').innerHTML = 'Account information has been changed successfully.';
        $('#accountEdit').modal('hide');
        $('#success').modal('show');
      }
    })
  }

  function changePassword(element) {
    var accountID = document.getElementById('account_username').value;
    document.getElementById('accountUsername').innerHTML = accountID;
    $('#accountEdit').modal('hide');
    $('#accountPassword').modal('show');
  }

  function updatePassword(element) {
    element.disabled = true;
    var newPassword = document.getElementById('account_password').value;
    var accountID = document.getElementById('account_id').value;
    $.ajax({
      method: "POST",
      url: "../config/accounts.php",
      data: {
        'updatePassword': true,
        'accountID': accountID,
        'newPassword': newPassword,
      },
      success: function(response) {
        if (response === 'Success') {
          document.getElementById('success_log').innerHTML = 'Operation completed successfully.';
          $('#accountPassword').modal('hide');
          $('#success').modal('show');
        } else {
          document.getElementById('error_found').innerHTML = response;
          $('#error').modal('show');
          element.disabled = false;
        }
      }
    })
  }

  function alert(element) {
    var alertID = document.getElementById('account_id').value;
    document.getElementById('hidden_id').value = alertID;
    $('#accountEdit').modal('hide');
    $('#danger').modal('show');
  }

  function accountDelete(element) {
    var deleteID = document.getElementById('hidden_id').value;
    $.ajax({
      method: "POST",
      url: "../config/accounts.php",
      data: {
        'accountDelete': true,
        'deleteID': deleteID,
      },
      success: function(respone) {
        if (respone === "Success") {
          document.getElementById('success_log').innerHTML = 'Account has been deleted successfully.';
          $('#danger').modal('hide');
          $('#success').modal('show');
        }
      }
    })
  }

  $(document).ready(function() {
    const imageInput = document.getElementById('account_image');
    const previewImage = document.getElementById('perview_image');

    imageInput.addEventListener('change', (event) => {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          previewImage.src = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    });
  });

  function uploadImage(element) {
    var formData = new FormData();
    var fileImage = document.getElementById('account_image');
    var fileUser = document.getElementById('account_username').value;
    formData.append('image', fileImage.files[0]);
    formData.append('fileUser', fileUser);
    formData.append('uploadImage', true);

    $.ajax({
      type: 'POST',
      url: "../config/accounts.php",
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
        console.log(response);
        if (response === "Success") {
          document.getElementById('success_log').innerHTML = 'Account photo uploaded and changed successfully.';
          $('#accountEdit').modal('hide');
          $('#success').modal('show');
        } else {
          document.getElementById('error_found').innerHTML = response;
          $('#accountEdit').modal('hide');
          $('#error').modal('show');
        }
      }
    });
  }

  function deleteImage(element) {
    var fileName = element.value;
    var userName = document.getElementById('account_username').value;
    console.log(userName);
    $.ajax({
      method: "POST",
      url: "../config/accounts.php",
      data: {
        "deleteImage": true,
        "fileName": fileName,
        "userName": userName,
      },
      success: function(response) {
        console.log(response);
        if (response === "Success") {
          document.getElementById('success_log').innerHTML = 'Account photo removed successfully.';
          $('#accountEdit').modal('hide');
          $('#success').modal('show');
        } else {
          document.getElementById('error_found').innerHTML = response;
          $('#accountEdit').modal('hide');
          $('#error').modal('show');
        }
      }
    })
  }

  function showCreate(element) {
    $('#createAccount').modal('show');
  }

  function accountCreate(element) {
    var createUsername = document.getElementById('create_username').value;
    var createFname = document.getElementById('create_fname').value;
    var createLname = document.getElementById('create_lname').value;
    var createNumber = document.getElementById('create_number').value;
    var createCard = document.getElementById('create_card').value;
    var createAccess = document.getElementById('create_access').value;
    var createDepartment = document.getElementById('create_department').value;
    var createSection = document.getElementById('create_section').value;
    var createEmail = document.getElementById('create_email').value;
    $.ajax({
      method: "POST",
      url: "../config/accounts.php",
      data: {
        'accountCreate': true,
        'createUsername': createUsername,
        'createFname': createFname,
        'createLname': createLname,
        'createNumber': createNumber,
        'createCard': createCard,
        'createAccess': createAccess,
        'createDepartment': createDepartment,
        'createSection': createSection,
        'createEmail': createEmail,
      },
      success: function(response) {
        console.log(response);
        if (response === "Success") {
          document.getElementById('success_log').innerHTML = 'Account has been created successfully.';
          $('#createAccount').modal('hide');
          $('#success').modal('show');
        } else {
          document.getElementById('error_found').innerHTML = response;
          $('#error').modal('show');
        }
      }
    })
  }

  function selectDepartment(element) {
    var departmentSelect = element.value;
    var accessSelect = document.getElementById('create_access').value;

    $.ajax({
      method: "POST",
      url: "../config/accounts.php",
      data: {
        "selectDepartment": true,
        "departmentSelect": departmentSelect,
      },
      success: function(response) {
        var $sectionSelect = $("select[name='create_section']");
        $sectionSelect.html(response).selectpicker('refresh');

        if (accessSelect == 3) {
          var nextOption = $sectionSelect.find("option").eq(1); // The second option (index 1)
          var notAvailableValue = nextOption.length ? nextOption.val() : "";

          var newOption = new Option("Not Available", notAvailableValue, true, true);

          $sectionSelect.prepend(newOption).selectpicker('refresh');
        }
      }
    });
  }

  function accessLevel(element) {
    var access = element.value;
    console.log(access);

    if (access != 3) {
      document.getElementById('create_section').disabled = false;
    } else {
      document.getElementById('create_section').disabled = true;
    }

    $("select[name='create_department']").val('').selectpicker('refresh');
    $("select[name='create_section']").val('').selectpicker('refresh');
  }
</script>