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
                <th>Username</th>
                <th>Name</th>
                <th>Section & Department</th>
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
                    <td><?php echo $row['username']; ?></td>
                    <td id="td-table"><img src="<?php echo $imageURL; ?>" class="img-table"><?php echo $row['fname'] . ' ' . $row['lname']; ?></td>
                    <td>
                      <?php echo $row['access'] != 'head' ? $row['sec_name'] : ''; ?><p class="form-text text-primary"><?php echo $row['dept_name']; ?></p>
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
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
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
  $('#dataTable').DataTable({
    "order": [
      [4, "asc"],
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
    element.disabled = true;
    var edit_task = document.getElementById('emptask_id').value;
    var edit_taskName = document.getElementById('emptask_name').value;
    var edit_requirement = document.getElementById('emptask_file').checked ? 1 : 0;
    var edit_duedate_element = document.getElementById('emptask_duedate');
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
          document.getElementById('success_log').innerHTML = edit_taskName + ' information task of ' + document.getElementById('emptask_for').value + ' has been updated successfully.';
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
</script>