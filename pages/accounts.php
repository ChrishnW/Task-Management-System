<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <?php if ($access == 1) { ?>
    <div class="card">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold">Registered Accounts</h6>
        <div class="dropdown no-arrow">
          <button type="button" onclick="showCreate(this)" class="btn btn-primary">
            <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> Create
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover" id="accountTable" width="100%" cellspacing="0">
            <thead>
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
              $result = mysqli_query($con, "SELECT a.*, d.dept_name, s.sec_name, c.access FROM department d JOIN section s ON d.dept_id=s.dept_id JOIN accounts a ON s.sec_id=a.sec_id JOIN access c ON a.access=c.id WHERE a.access!=1");
              if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                  if ($row['status'] == 1) {
                    $status = 'Active';
                    $btn = 'success';
                  } else {
                    $status = 'Inactive';
                    $btn = 'danger';
                  } ?>
                  <tr>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo getUser($row['username']); ?></td>
                    <td>
                      <?php if ($row['access'] != 'head') {
                        echo $row['sec_name'];
                      } ?>
                      <p class="form-text text-danger"><?php echo $row['dept_name']; ?></p>
                    </td>
                    <td><span class="badge badge-pill badge-light"><?php echo strtoupper($row['access']) ?></span></td>
                    <td><span class="badge badge-<?php echo $btn ?>"><?php echo $status ?></span></td>
                    <td>
                      <center /><button type="button" class="btn btn-info btn-block btn-sm" value="<?php echo $row['id']; ?>" onclick="accountEdit(this)">Edit</button>
                      <?php if ($row['status'] === '1'): ?>
                        <button class="btn btn-danger btn-block btn-sm" value="<?php echo $row['id']; ?>" data-status="0" onclick="changeStatus(this)">Deactive</button>
                      <?php else: ?>
                        <button class="btn btn-success btn-block btn-sm" value="<?php echo $row['id']; ?>" data-status="1" onclick="changeStatus(this)">Activate</button>
                      <?php endif; ?>
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
          <button type="button" onclick="showCreate(this)" class="btn btn-primary" disabled>
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
                  $query = mysqli_query($con, "SELECT COUNT(*) AS total_task FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id WHERE t.in_charge='$member' AND tl.task_class!=4");
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
<div class="modal fade" id="accountEdit" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Account</h5>
      </div>

      <div class="modal-body" id="accountDetailsBody">
      </div>
      <div class="modal-footer justify-content-between">
        <div>
          <button id="resetUserPass" class="btn btn-danger" onclick='resetPassword(this)'>Reset Password</button>
        </div>
        <div>
          <button onclick="accountUpdate(this)" class="btn btn-success" name="account_update">Update</button>
          <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
      </form>
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

<?php include('../include/footer.php'); ?>

<script>
  $('#accountTable').DataTable({
    "columnDefs": [{
      "orderable": false,
      "searchable": false,
      "targets": 5,
    }],
    "order": [
      [0, "asc"]
    ]
  });

  function changeStatus(element) {
    element.disabled = true;
    const id = element.value;
    const status = element.getAttribute('data-status');
    $.ajax({
      type: "POST",
      url: "../config/accounts.php",
      data: {
        "id": id,
        "status": status,
        "changeStatus": true
      },
      success: function(response) {
        location.reload();
      }
    });
  }

  function accountEdit(element) {
    var accountID = element.value;
    $.ajax({
      method: "POST",
      url: "../config/accounts.php",
      data: {
        'accountEdit': true,
        'id': accountID,
      },
      success: function(response) {
        $('#resetUserPass').val(accountID);
        $('#accountDetailsBody').html(response);
        openSpecificModal('accountEdit', 'modal-lg');
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

  function accountUpdate(element) {
    const formData = new FormData(document.getElementById('accountDetails'));
    formData.append('accountUpdate', true);
    $.ajax({
      method: "POST",
      url: "../config/accounts.php",
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        console.log(response);
        if (response === 'Success') {
          document.getElementById('success_log').innerHTML = 'Account information has been changed successfully.';
          $('#success').modal('show');
        } else {
          document.getElementById('success_log').innerHTML = response;
          $('#error').modal('show');
        }
      }
    })
  }

  function changePassword(element) {
    var accountID = document.getElementById('account_username').value;
    document.getElementById('accountUsername').innerHTML = accountID;
    $('#accountEdit').modal('hide');
    $('#accountPassword').modal('show');
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