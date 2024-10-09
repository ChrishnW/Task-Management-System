<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <?php if ($access == 1) { ?>
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Assigned Task</h5>
        <ul class="nav nav-tabs" id="myTabs">
          <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#home">Home</a>
          </li>
          <?php
          $query = mysqli_query($con, "SELECT * FROM section WHERE status=1");
          while ($row = mysqli_fetch_array($query)) { ?>
            <li class="nav-item">
              <a class="nav-link text-capitalize" data-toggle="tab" href="#<?php echo $row['sec_id'] ?>"><?php echo strtolower($row['sec_name']) ?></a>
            </li>
          <?php } ?>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="home">
            <div class="card">
              <div class="card-body">
                <div class="card border-primary shadow mb-4">
                  <div class="card-header bg-primary py-3">
                    <h6 class="m-0 font-weight-bold text-white">Assgined New Task</h6>
                  </div>
                  <div class="card-body">
                    <form>
                      <div class="form-row">
                        <div class="form-group col-md-4">
                          <label>Section<code>*</code></label>
                          <select id="assignTask_section" class="form-control selectpicker show-tick" data-style="bg-primary text-white text-capitalize" data-live-search="true" data-header="Current Active Sections" title="Select Section" onchange="assignSection(this);">
                            <?php
                            $taskFor = mysqli_query($con, "SELECT section.sec_id, section.sec_name, department.dept_name FROM section JOIN department ON department.dept_id = section.dept_id");
                            while ($forRow = mysqli_fetch_array($taskFor)) { ?>
                              <option value="<?php echo $forRow['sec_id'] ?>" data-subtext="<?php echo $forRow['dept_name'] ?>" class="text-capitalize"><?php echo strtolower($forRow['sec_name']) ?></option>
                            <?php } ?>
                          </select>
                        </div>
                        <div class="form-group col-md-4">
                          <label>Assignee<code>*</code></label>
                          <select class="form-control selectpicker" multiple data-selected-text-format="count > 3" multiple data-actions-box="true" data-style="bg-primary text-white" data-live-search="true" data-size="5" name="assignTask_name[]" id="assignTask_name" multiple="multiple">
                          </select>
                        </div>
                        <div class="form-group col-md-4">
                          <label>Task Classification<code>*</code></label>
                          <select id="assignTask_class" name="assignTask_class" class="form-control selectpicker" data-live-search="true" data-style="bg-primary text-white text-capitalize" data-size="5" title="Select Class" onchange="selectClass(this);">
                            <?php
                            $taskClass = mysqli_query($con, "SELECT * FROM task_class WHERE id!=5 AND id!=4");
                            while ($classRow = mysqli_fetch_array($taskClass)) { ?>
                              <option value="<?php echo $classRow['id'] ?>" data-subtext="CLASS <?php echo $classRow['id'] ?>" class="text-capitalize"><?php echo strtolower($classRow['task_class']) ?></option>
                            <?php } ?>
                          </select>
                        </div>
                        <div class="form-group col-md-3">
                          <label>Task Attachment<code>*</code></label>
                          <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="assignTask_file" name="assignTask_file">
                            <label class="custom-control-label" for="assignTask_file" style="color: red">Toggle this switch if required</label>
                          </div>
                        </div>
                        <div class="form-group col-md-5">
                          <label>Task List<code>*</code></label>
                          <select class="form-control selectpicker show-tick" data-container="body" multiple data-actions-box="true" data-selected-text-format="count" data-style="bg-primary text-white" data-live-search="true" data-size="5" id="assignTask_list" name="assignTask_list[]" multiple>
                          </select>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="task_details">Recurrance Date<code>*</code></label>
                          <input type="text" name="assignTask_date" id="assignTask_date" class="form-control">
                        </div>
                      </div>

                      <button onclick="location.reload();" class="btn btn-danger">Clear</button>
                      <button onclick="assignTask(this);" type="button" class="btn btn-success">Assign</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php
          mysqli_data_seek($query, 0);
          while ($row = mysqli_fetch_array($query)) { ?>
            <div class="tab-pane fade" id="<?php echo $row['sec_id'] ?>">
              <div class="card">
                <div class="card-body">
                  <table id="table_<?php echo $row['sec_id'] ?>" class="table table-striped">
                    <thead class="bg-primary">
                      <tr class="text-white">
                        <th>Asignee</th>
                        <th>Username</th>
                        <th>Assigned Task(s)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $sectionID = $row['sec_id'];
                      $assignTable = mysqli_query($con, "SELECT * FROM accounts WHERE status=1 AND access=2 AND sec_id='$sectionID'");
                      while ($assignRow = mysqli_fetch_array($assignTable)) {
                        $assignee = $assignRow['username'];
                        $count_task = mysqli_query($con, "SELECT COUNT(id) as total_task FROM tasks WHERE in_charge='$assignee' AND task_class!=4");
                        $count_task_row = $count_task->fetch_assoc();
                        $total_task = $count_task_row['total_task'];
                        if (empty($assignRow['file_name'])) {
                          $imageURL = '../assets/img/user-profiles/nologo.png';
                        } else {
                          $imageURL = '../assets/img/user-profiles/' . $assignRow['file_name'];
                        } ?>
                        <tr>
                          <td id="td-table"><img src="<?php echo $imageURL; ?>" class="img-table"><?php echo $assignRow['fname'] . ' ' . $assignRow['lname'] ?></td>
                          <td><?php echo $assignRow['username'] ?></td>
                          <td><button onclick="viewTask(this)" value="<?php echo $assignee ?>" type="button" class="btn btn-success"><i class="fas fa-list"></i> <?php echo $total_task ?> Assigned Task(s)</button></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  <?php } elseif ($access == 2) { ?>
    <div class="card">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Task List</h6>
        <div class="dropdown no-arrow">
          <button type="button" onclick="taskDownload(this)" class="btn btn-sm"><i class="fas fa-file-pdf fa-fw text-success"></i> Download</button>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
            <thead class='table table-success'>
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
              $result = mysqli_query($con, "SELECT * FROM tasks t JOIN task_list tl ON tl.tl_ID=t.task_id WHERE in_charge='CLOPEZ' AND tl.task_class!=4");
              if (mysqli_num_rows($result) > 0) {
                $count = 0;
                while ($row = $result->fetch_assoc()) {
                  $count += 1;
                  $task_class = '<span class="badge badge-info">' . $row['task_class'] . '</span>';
                  if ($row['attachment'] == 1) {
                    $requirement = '<span class="badge badge-primary">File Attachment</span>';
                  } else {
                    $requirement = '<span class="badge badge-primary">None</span>';
                  }
              ?>
                  <tr>
                    <td><?php echo $count ?></td>
                    <td><?php echo $row['task_name'] ?></td>
                    <td><?php echo getTaskClass($row['task_class']); ?></td>
                    <td id="td-table-shrink"><?php echo $row['task_details']; ?></td>
                    <td><?php echo $requirement ?></td>
                    <td><?php echo $row['submission'] ?></td>
                  </tr>
              <?php }
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php } elseif ($access == 3) { ?>
  <?php } ?>
</div>

<div class="modal fade" id="viewTable" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
    <div class="modal-content border-info">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="assignee_name">View Assigned Task</h5>
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
        <h5 class="modal-title">View Task</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Assignee:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-user"></i></div>
            </div>
            <input type="text" id="emptask_for" class="form-control" readonly>
          </div>
        </div>
        <div class="form-group">
          <label>Task Name:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-tasks"></i></div>
            </div>
            <input type="text" id="emptask_name" class="form-control" readonly>
          </div>
        </div>
        <div class="form-group">
          <label>Task Class:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-flag"></i></div>
            </div>
            <input type="datetime" id="emptask_class" class="form-control" readonly>
          </div>
        </div>
        <div class="form-group">
          <label>Task Requirement:</label>
          <div class="input-group mb-2">
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" id="emptask_file" name="emptask_file">
              <label class="custom-control-label" for="emptask_file">Toggle this switch if required</label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label>Due Date:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
            </div>
            <input type="datetime" id="emptask_duedate" class="form-control">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" onclick="editTask(this)" class="btn btn-primary" id="emptask_id">Update</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="result" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Result</h5>
      </div>
      <div class="modal-body text-center" id="resultBody">
      </div>
      <div class="modal-footer">
        <button type="button" onclick="location.reload();" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="danger" tabindex="-1" role="dialog" aria-hidden="true">
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
        <button type="button" onclick="location.reload();" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" onclick="RemoveTask(this)" class="btn btn-primary">Proceed</button>
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

<?php include('../include/footer.php'); ?>

<script>
  <?php if ($access == 1) { ?>
    $(document).ready(function() {
      <?php
      mysqli_data_seek($query, 0);
      while ($row = mysqli_fetch_array($query)) { ?>
        $('#table_<?php echo $row['sec_id'] ?>').DataTable({
          "order": [
            [0, "asc"]
          ],
          "pageLength": 5,
          "lengthMenu": [5, 10, 25, 50, 100],
          "responsive": true
        });
      <?php } ?>
    });
  <?php
  } else { ?>
    $(document).ready(function() {
      $('#dataTable').DataTable({
        "order": [
          [0, "asc"]
        ],
        "pageLength": 10,
        "lengthMenu": [10, 25, 50, 100],
        "responsive": true
      });
    })
  <?php } ?>

  function selectClass(element) {
    var task_for = document.getElementById('assignTask_section').value;
    var task_class = element.value;
    if (task_class && task_for) {
      $.ajax({
        method: "POST",
        url: "../ajax/assign_tasks.php",
        data: {
          "selectClass": true,
          "task_class": task_class,
          "task_for": task_for,
        },
        success: function(response) {
          if (task_class === '1') {
            document.getElementById('assignTask_date').value = 'Weekdays';
            document.getElementById('assignTask_date').readOnly = true;
          } else if (task_class === '2') {
            document.getElementById('assignTask_date').value = null;
            document.getElementById('assignTask_date').readOnly = false;
            document.getElementById('assignTask_date').placeholder = 'Example: Tuesday';
          } else if (task_class === '3') {
            document.getElementById('assignTask_date').value = null;
            document.getElementById('assignTask_date').readOnly = false;
            document.getElementById('assignTask_date').placeholder = 'Example: 15th day of the month';
          } else if (task_class === '6') {
            document.getElementById('assignTask_date').value = null;
            document.getElementById('assignTask_date').readOnly = false;
            document.getElementById('assignTask_date').placeholder = 'Example: 30th day of the month';
          }
          $("select[name='assignTask_list[]']").html(response).selectpicker('refresh');
        }
      })
    }
  }

  function assignSection(element) {
    var sec_id = element.value;
    console.log(sec_id);
    $.ajax({
      method: "POST",
      url: "../ajax/assign_tasks.php",
      data: {
        "assignSection": true,
        "sec_id": sec_id,
      },
      success: function(response) {
        $("select[name='assignTask_name[]']").html(response).selectpicker('refresh');
      }
    })
  }

  function assignTask(element) {
    element.disabled = true;
    var assign_section = document.getElementById('assignTask_section').value;
    var assign_assignee = Array.from(document.querySelectorAll('select[name="assignTask_name[]"] option:checked')).map(option => option.value);
    var assign_tasks = Array.from(document.querySelectorAll('select[name="assignTask_list[]"] option:checked')).map(option => option.value);
    var assign_taskclass = document.getElementById('assignTask_class').value;
    var assign_duedate = document.getElementById('assignTask_date').value;
    var assign_requirement = document.getElementById('assignTask_file');

    if (assign_requirement.checked) {
      var assign_file = '1';
    } else {
      var assign_file = '0';
    }
    console.log(assign_file);
    $.ajax({
      method: "POST",
      url: "../ajax/assign_tasks.php",
      data: {
        "assignTask": true,
        "assign_section": assign_section,
        "assign_assignee": assign_assignee,
        "assign_tasks": assign_tasks,
        "assign_taskclass": assign_taskclass,
        "assign_duedate": assign_duedate,
        "assign_file": assign_file,
      },
      success: function(response) {
        $('#resultBody').html(response);
        $('#result').modal('show');
      }
    })
  }

  function viewTask(element) {
    element.disabled = true;
    var assignee_id = element.value;
    // console.log(assignee_view);
    $.ajax({
      method: 'POST',
      url: '../ajax/assign_tasks.php',
      data: {
        "viewTaskEmp": true,
        "assignee_id": assignee_id,
      },
      success: function(response) {
        $('#viewAssignedTaskTable').html(response);
        $('#viewList').DataTable({
          "pageLength": 5,
          "lengthMenu": [5, 10, 25, 50, 100],
          "responsive": true
        });
        openSpecificModal('viewTable', 'modal-xl');
        element.disabled = false;
      }
    })
  }

  function EditTaskView(element) {
    var editID = element.value;
    var editName = element.getAttribute('data-name');
    var editFile = element.getAttribute('data-condition');
    var editFor = element.getAttribute('data-for');
    var editClass = element.getAttribute('data-class');
    var editDuedate = element.getAttribute('data-date');
    $(document).ready(function() {
      document.getElementById('emptask_id').value = editID;
      document.getElementById('emptask_name').value = editName;
      document.getElementById('emptask_file').value = editFile;
      document.getElementById('emptask_for').value = editFor;
      document.getElementById('emptask_class').value = editClass;
      document.getElementById('emptask_duedate').value = editDuedate;
      var fileCheck = document.getElementById('emptask_file').value;
      if (fileCheck === '1') {
        document.getElementById('emptask_file').checked = true;
      }
      $('#viewTable').modal('hide');
      $('#edit').modal('show');
    })
  }

  function editTask(element) {
    element.disabled = true;
    var edit_task = element.value;
    var edit_requirement = document.getElementById('emptask_file');
    var edit_duedate = document.getElementById('emptask_duedate').value;
    if (edit_requirement.checked) {
      var edit_requirement_value = '1';
    } else {
      var edit_requirement_value = '0';
    }
    $.ajax({
      method: "POST",
      url: "../ajax/assign_tasks.php",
      data: {
        "editTask": true,
        "edit_task": edit_task,
        "edit_requirement_value": edit_requirement_value,
        "edit_duedate": edit_duedate,
      },
      success: function(response) {
        if (response === 'Success') {
          document.getElementById('success_log').innerHTML = 'Task #' + edit_task + ' information has been updated successfully.';
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
    $('#danger').modal('show');
  }

  function RemoveTask(element) {
    element.disabled = true;
    var deleteID = document.getElementById('hidden_id').value;
    $.ajax({
      method: "POST",
      url: "../ajax/assign_tasks.php",
      data: {
        'taskDelete': true,
        'deleteID': deleteID,
      },
      success: function(response) {
        if (response === 'Success') {
          document.getElementById('success_log').innerHTML = 'Operation completed successfully';
          $('#danger').modal('hide');
          $('#success').modal('show');
        } else {
          document.getElementById('error_found').innerHTML = response;
          $('#error').modal('show');
          element.disabled = false;
        }
      }
    })
  }

  function taskDownload(element) {
    window.location.href = '../ajax/assign_tasks.php?taskDownload=true';
  }
</script>