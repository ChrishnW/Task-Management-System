<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <?php if ($access == 1) { ?>
    <div class="card">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold">Registered Accounts</h6>
        <div class="dropdown no-arrow">
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover" id="accountTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th><input type="checkbox" class="form-control" disabled></th>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Section & Department</th>
                <th>Access</th>
                <th>Status</th>
                <th class="text-truncate">
                  <button type="button" onclick="showCreate(this)" class="btn btn-primary btn-block">
                    <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> Create
                  </button>
                </th>
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
                    <td><input type="checkbox" class="form-control" disabled></td>
                    <td class="text-truncate"><?php echo empty($row['card']) ? 'No Data' : $row['card'] ?></td>
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
                      <div class="dropdown mb-1">
                        <button class="btn btn-secondary btn-block btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                          Settings
                        </button>
                        <div class="dropdown-menu">
                          <button class="dropdown-item" value="<?php echo $row['id']; ?>" onclick="accountEdit(this)">Edit</button>
                          <button class="dropdown-item" value="<?php echo $row['id']; ?>" onclick="accountDelete(this)">Delete</button>
                        </div>
                      </div>
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
  <?php } elseif ($access == 3) { ?>
    <div class="card">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold">Deparment Members</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="memberList" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Section</th>
                <th>Role</th>
                <th class="col-1"></th>
              </tr>
            </thead>
            <tbody>
              <?php $con->next_result();
              $result = mysqli_query($con, "SELECT * FROM accounts a JOIN section s ON a.sec_id=s.sec_id WHERE s.dept_id='$dept_id' AND a.status=1 AND a.access NOT IN (1, 3)");
              if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                  $member = $row['username'];
                  $query = mysqli_query($con, "SELECT COUNT(*) AS total_task FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id WHERE t.in_charge='$member' AND tl.task_class!=4");
                  $query_result = mysqli_fetch_assoc($query); ?>
                  <tr>
                    <td><?php echo $row['card']; ?></td>
                    <td><?php echo getUser($row['username']); ?></td>
                    <td><?php echo $row['sec_name']; ?></td>
                    <td> </td>
                    <td class="text-truncate"><button class="btn btn-success btn-block">Assign</button></td>
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
          <button id="resetUserPass" class="btn btn-danger" onclick='resetPassword()'>Reset Password</button>
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
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create Account</h5>
      </div>
      <div class="modal-body">
        <form id="createAccountDetails" enctype="multipart/form-data">
          <div class="form-row">
            <div class="form-group col-6">
              <label for="newUsername">Username</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-user"></i></span>
                </div>
                <input type="text" class="form-control" id="newUsername" name="newUsername"
                  placeholder="Enter username">
              </div>
            </div>
            <div class="form-group col-6">
              <label for="newSystemAccess">System Access</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-key"></i></span>
                </div>
                <select class="form-control selectpicker show-tick" data-style="border-secondary" title="Select Access" id="newSystemAccess" name="newSystemAccess" onchange="accessLevel(this)">
                  <?php $getAccess = mysqli_query($con, "SELECT * FROM access WHERE id != 1");
                  while ($getAccessRow = mysqli_fetch_assoc($getAccess)) : ?>
                    <option value=" <?php echo $getAccessRow['id']; ?>"><?php echo ucwords($getAccessRow['access']); ?></option>
                  <?php endwhile; ?>
                </select>
              </div>
            </div>
            <div class="form-group col-12">
              <label for="newDepartment">Department</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-building"></i></span>
                </div>
                <select name="newDepartment" id="newDepartment" class="form-control selectpicker show-tick" data-style="border-secondary" data-size="5" data-live-search="true" data-dropup-auto="false" title="Select Department" onchange="selectDepartment(this)">
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
            <div class="form-group col-12 d-none" id="accessHide">
              <label for="newSection">Section</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-sitemap"></i></span>
                </div>
                <select name="newSection" id="newSection" class="form-control selectpicker show-tick" data-style="border-secondary" data-size="5" title="Select Section" data-live-search="true" data-dropup-auto="false">
                </select>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button onclick="accountCreate(this)" class="btn btn-success" name="create_update">Save</button>
        <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
      "targets": [0, 6],
    }],
    "order": [
      [2, "asc"]
    ]
  }, $('[data-toggle="tooltip"]').tooltip());

  $('#memberList').DataTable({
    searching: false, // Disable the search bar
    lengthChange: false,
    "columnDefs": [{
      "autoWidth": false,
      "orderable": false,
      "searchable": false,
      "targets": 4,
    }],
    "order": [
      [1, "asc"]
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

  function resetPassword() {
    const resetID = document.getElementById('account_id').value;
    // alert(resetID);
    $.ajax({
      method: "POST",
      url: "../config/accounts.php",
      data: {
        'resetPassword': true,
        'id': resetID
      },
      success: function(response) {
        if (response === 'Success') {
          document.getElementById('success_log').innerHTML = 'Account password reset successfully.';
          $('#success').modal('show');
        } else {
          document.getElementById('error_log').innerHTML = response;
          $('#error').modal('show');
        }
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

  function showCreate(element) {
    $('#createAccount').modal('show');
  }

  function accountCreate(element) {
    const formData = new FormData(document.getElementById('createAccountDetails'));
    formData.append('accountCreate', true);
    $.ajax({
      method: "POST",
      url: "../config/accounts.php",
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        if (response === "Success") {
          document.getElementById('success_log').innerHTML = 'Account has been created successfully.';
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
    var accessSelect = document.getElementById('newSystemAccess').value;
    $.ajax({
      method: "POST",
      url: "../config/accounts.php",
      data: {
        "selectDepartment": true,
        "id": departmentSelect,
      },
      success: function(response) {
        var $sectionSelect = $("select[name='newSection']");
        $sectionSelect.html(response).selectpicker('refresh');

        if (accessSelect == 3 || accessLevel == 1) {
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
    if (access == 2) {
      document.getElementById('accessHide').classList.remove('d-none');
    } else {
      document.getElementById('accessHide').classList.add('d-none');
    }
    $("select[name='newDepartment']").val('').selectpicker('refresh');
    $("select[name='newSection']").val('').selectpicker('refresh');
  }

  function accountDelete(element) {
    var accountID = element.value;
    $('#delete').modal('show');
    document.getElementById('confirmBtn').onclick = function() {
      $('#delete').modal('hide');
      $.ajax({
        method: "POST",
        url: "../config/accounts.php",
        data: {
          'accountDelete': true,
          'id': accountID,
        },
        success: function(response) {
          if (response == 'Success') {
            window.location.reload();
          } else {
            document.getElementById('error_log').innerHTML = response;
            $('#error').modal('show');
          }
        }
      });
    };
  }
</script>