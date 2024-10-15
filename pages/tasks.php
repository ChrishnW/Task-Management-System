<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <?php if ($access === '1' || $access === '3') : ?>
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="form-group col-md-2">
            <label>From</label>
            <input type="date" name="date_from" id="date_from" class="form-control" onchange="filterTable(this)">
          </div>
          <div class="form-group col-md-2">
            <label>To</label>
            <input type="date" name="date_to" id="date_to" class="form-control" onchange="filterTable(this)">
          </div>
          <div class="form-group col-md-2">
            <label>Progress</label>
            <select id="progress" name="progress" class="form-control selectpicker" data-style="bg-primary text-white text-capitalize" data-size="5" onchange="filterTable(this)">
              <option value="" data-subtext="Default" selected>All</option>
              <option value="NOT YET STARTED">Not Yet Started</option>
              <option value="IN PROGRESS">In-Progress</option>
              <option value="REVIEW">Review</option>
              <option value="FINISHED">Finished</option>
              <option value="RESCHEDULE">Reschedule</option>
            </select>
          </div>
          <div class="form-group col-sm-auto">
            <label>Status</label>
            <select id="taskStatus" name="taskStatus" class="form-control selectpicker" data-style="bg-primary text-white text-capitalize" onchange="filterTable(this)">
              <option value="1" data-subtext="Default" selected>Active</option>
              <option value="0">In-Active</option>
            </select>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover" id="taskList" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Code</th>
                <th>Title</th>
                <th>Classification</th>
                <th>Due Date</th>
                <th>Assignee</th>
                <th>Progress</th>
                <th></th>
              </tr>
            </thead>
            <tbody id='dataTableBody'>
              <?php $getTask = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tl.class=tc.id JOIN tasks t ON t.task_id=tl.id JOIN tasks_details td ON td.task_id=t.id WHERE td.status=1");
              while ($getTaskRow = $getTask->fetch_assoc()): ?>
                <tr>
                  <td><?php echo $getTaskRow['task_code']; ?></td>
                  <td>
                    <div class="d-flex justify-content-between">
                      <div><?php echo $getTaskRow['task_name']; ?></div>
                      <div>
                        <?php if (intval($getTaskRow['attachment']) === 1): ?>
                          <i class="fas fa-photo-video text-warning" data-toggle="tooltip" data-placement="top" title="Attachment Required"></i>
                        <?php endif; ?>
                        <i class="fas fa-info-circle text-info" data-toggle="tooltip" data-placement="right" title="<?php echo $getTaskRow['task_details']; ?>"></i>
                      </div>
                    </div>
                  </td>
                  <td><?php echo $getTaskRow['task_class']; ?></td>
                  <td><?php echo date("F d, Y", strtotime($getTaskRow['due'])); ?></td>
                  <td><?php echo getUser($getTaskRow['in_charge']); ?></td>
                  <td><?php echo getProgressBadge($getTaskRow['progress']); ?></td>
                  <td>
                    <button class="btn btn-sm btn-block btn-success" value="<?php echo $getTaskRow['id']; ?>" onclick="editTask(this)"><i class="fas fa-pen-square fa-fw"></i> Edit</button>
                    <button class="btn btn-sm btn-block btn-info" value="<?php echo $getTaskRow['id']; ?>" onclick="viewTask(this)"><i class="fas fa-book-open fa-fw"></i> View</button>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php elseif ($access === '2') : ?>
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="form-group col-md-2">
            <label>From</label>
            <input type="date" name="date_from" id="date_from" class="form-control" onchange="filterDate()">
          </div>
          <div class="form-group col-md-2">
            <label>To</label>
            <input type="date" name="date_to" id="date_to" class="form-control" onchange="filterDate()" disabled>
          </div>
        </div>
        <ul class="nav nav-pills nav-justified mb-3" id="myTabs">
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#todo"><i class="fas fa-business-time fa-fw"></i> To Do</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#review"><i class="fas fa-tasks fa-fw"></i> For Review</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#finished"><i class="fas fa-archive fa-fw"></i> Finished</a>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade" id="todo">
            <div class="card">
              <div class="card-header d-none" id="actionButton">
                <button id="multiStart" class="btn btn-success pull-right"><i class="fas fa-play"></i> Start All</button>
              </div>
              <div class="card-body table-responsive">
                <table id="myTasksTableTodo" class="table table-hover">
                  <thead>
                    <tr>
                      <th><input type='checkbox' id='selectAll' class='tasksCheckboxes' style="transform: scale(1.5);"></th>
                      <th>Code</th>
                      <th>Title</th>
                      <th>Classification</th>
                      <th>Due Date</th>
                      <th>Status</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody id="myTasksTodo">
                    <?php $getTodo = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tl.class=tc.id JOIN tasks t ON t.task_id=tl.id JOIN tasks_details td ON td.task_id=t.id JOIN task_status ts ON ts.stage=td.progress WHERE in_charge='$username'");
                    while ($getTodoRow = $getTodo->fetch_assoc()) {
                      $selectBox = '<input type="checkbox" name="selected_ids[]" class="form-control" value="' . (date_create(date('Y-m-d', strtotime($getTodoRow['due']))) > date_create(date('Y-m-d')) ? '' : $getTodoRow['id']) . '" ' . (date_create(date('Y-m-d', strtotime($getTodoRow['due']))) > date_create(date('Y-m-d')) ? 'disabled' : '') . '>';
                    ?>
                      <tr>
                        <td><?php echo $selectBox; ?></td>
                        <td><?php echo $getTodoRow['task_code']; ?></td>
                        <td>
                          <div class="d-flex justify-content-between">
                            <div><?php echo $getTodoRow['task_name']; ?></div>
                            <div>
                              <?php if (intval($getTodoRow['attachment']) === 1): ?>
                                <i class="fas fa-photo-video text-warning" data-toggle="tooltip" data-placement="top" title="Attachment Required"></i>
                              <?php endif; ?>
                              <i class="fas fa-info-circle text-info" data-toggle="tooltip" data-placement="right" title="<?php echo $getTodoRow['task_details']; ?>"></i>
                            </div>
                          </div>
                        </td>
                        <td><?php echo $getTodoRow['task_class']; ?></td>
                        <td><?php echo date("F d", strtotime($getTodoRow['due'])) ?></td>
                        <td><?php echo getProgressBadge($getTodoRow['progress']); ?></td>
                        <td>
                          <?php if ($getTodoRow['progress'] === 'To-Do'): ?>
                            <button type="button" class="btn btn-sm btn-block btn-success" value="<?php echo $getTodoRow['id']; ?>" onclick="startTask(this)"
                              <?php echo (date_create(date('Y-m-d', strtotime($getTodoRow['due']))) > date_create(date('Y-m-d'))) ? 'disabled' : ''; ?>>
                              <i class="fas fa-stopwatch fa-fw"></i> Start
                            </button>
                          <?php elseif ($getTodoRow['progress'] === 'On-Hold'): ?>
                            <button type="button" class="btn btn-sm btn-block btn-secondary"
                              value="<?php echo $getTodoRow['id']; ?>"
                              onclick="onHoldTask(this)"
                              disabled>
                              <i class="fas fa-pause fa-fw"></i> On Hold
                            </button>
                          <?php endif; ?>

                          <?php if ($getTodoRow['progress'] === 'Pending'): ?>
                            <button type="button" class="btn btn-sm btn-block btn-danger"
                              value="<?php echo $getTodoRow['id']; ?>"
                              onclick="endModal(this)">
                              <i class="far fa-stop-circle fa-fw"></i> Finish
                            </button>
                          <?php endif; ?>
                          <?php if ($getTodoRow['resched'] === NULL): ?>
                            <button type="button" class="btn btn-sm btn-block btn-warning" value="<?php echo $getTodoRow['id']; ?>" onclick="reschedTask(this)"><i class="fas fa-calendar-alt fa-fw"></i> Reschedule</button>
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="review">
            <div class="card">
              <div class="card-body table-responsive">
                <table id="myTasksTableReview" class="table table-hover">
                  <thead>
                    <tr>
                      <th>Code</th>
                      <th>Title</th>
                      <th>Classification</th>
                      <th>Due Date</th>
                      <th>Finished Date</th>
                      <th>Assignee</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="myTasksReview">
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="finished">
            <div class="card">
              <div class="card-body table-responsive">
                <table id="myTasksTableFinished" class="table table-hover">
                  <thead>
                    <tr>
                      <th>Code</th>
                      <th>Task Name</th>
                      <th>Classification</th>
                      <th>Due Date</th>
                      <th>Finished Date</th>
                      <th>Rating</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="myTasksFinished">
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<div class="modal fade" id="start" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content border-success">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Start Task</h5>
      </div>
      <div class="modal-body text-center">
        <input type="hidden" id="taskID">
        <i class="fas fa-question fa-5x text-success"></i>
        <br><br>
        Do you want to start this task?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal" id="confirmButton">Confirm</button>
        <button class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="resched" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content border-warning">
      <div class="modal-header bg-warning text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Reschedule Task</h5>
      </div>
      <div class="modal-body">
        <input type="hidden" id="reschedID">
        <label for="">Request Due Date:</label>
        <div class="input-group mb-2">
          <div class="input-group-prepend">
            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
          </div>
          <input type="date" id="resched_date" name="resched_date" class="form-control" min=<?php echo $minDay; ?>>
        </div>
        <label for="">Reason:</label>
        <div class="input-group mb-2">
          <div class="input-group-prepend">
            <div class="input-group-text"><i class="fas fa-comment"></i></div>
          </div>
          <textarea name="resched_reason" id="resched_reason" class="form-control" placeholder="Please write your reason for rescheduling here."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" id="requestButton">Request</button>
        <button class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="finish" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content border-danger">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="fas fa-pen-square fa-fw"></i> Finish Task</h5>
      </div>
      <div class="modal-body text-center" id="finishDetails">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="submitTask">Submit</button>
        <button class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include('../include/footer.php'); ?>

<script>
  $('#myTasksTableTodo').DataTable({
    "columnDefs": [{
      "orderable": false,
      "searchable": false,
      "targets": [0, 6]
    }],
    "order": [
      [4, "asc"],
      [2, "desc"]
    ],
    "drawCallback": function(settings) {
      $('[data-toggle="tooltip"]').tooltip();
    }
  });

  function startTask(element) {
    var id = element.value;
    $('#taskID').val(id);
    $('#start').modal('show');

    $('#confirmButton').off('click').on('click', function() {
      var taskId = $('#taskID').val();
      $.ajax({
        url: '../ajax/tasks.php',
        method: 'POST',
        data: {
          "startTask": true,
          "id": id
        },
        success: function(response) {
          if (response === "Success") {
            document.getElementById('success_log').innerHTML = 'Your task has been started.';
            $('#success').modal('show');
          } else {
            if (response !== '' && !response.includes('Warning')) {
              document.getElementById('error_found').innerHTML = response;
            } else {
              document.getElementById('error_found').innerHTML = 'There was an error processing your request.';
            }
            $('#error').modal('show');
            element.disabled = false;
          }
        },
      });
    });
  }

  function endModal(element) {
    element.disabled = true;
    const id = element.value;
    $.ajax({
      method: "POST",
      url: "../ajax/tasks.php",
      data: {
        "endModal": true,
        "taskID": id,
      },
      success: function(response) {
        $('#finishDetails').html(response);
        $('#finish').modal('show');
        element.disabled = false;
      }
    });

    $('#submitTask').off('click').on('click', function() {
      const $button = $(this);
      $button.prop('disabled', true);
      const formData = new FormData(document.getElementById('taskEndDetails'));
      formData.append('submitTask', true);
      $.ajax({
        method: "POST",
        url: "../ajax/tasks.php",
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
          if (response === 'Success') {
            document.getElementById('success_log').innerHTML = 'Operation completed successfully.';
            $('#finish').modal('hide');
            $('#success').modal('show');
          } else {
            document.getElementById('error_found').innerHTML = response;
            $('#error').modal('show');
            $button.prop('disabled', false);
          }
        }
      });
    })
  }

  function reschedTask(element) {
    let id = element.value;
    $('#resched').modal('show');
    $('#requestButton').off('click').on('click', function() {
      let $button = $(this);
      $button.prop('disabled', true);
      let reschedDate = document.getElementById('resched_date').value;
      let reschedReason = document.getElementById('resched_reason').value;
      $.ajax({
        url: '../ajax/tasks.php',
        method: 'POST',
        data: {
          "rescheduleTask": true,
          "id": id,
          "reschedDate": reschedDate,
          "reschedReason": reschedReason
        },
        success: function(response) {
          if (response === 'Success') {
            document.getElementById('success_log').innerHTML = 'Operation completed successfully.';
            $('#resched').modal('hide');
            $('#success').modal('show');
          } else {
            document.getElementById('error_found').innerHTML = response;
            $('#error').modal('show');
            $button.prop('disabled', false);
          }
        },
      });
    });
  }

  function updateCounter() {
    const textarea = document.getElementById('taskRemarks');
    const counter = document.getElementById('charCount');
    const maxLength = 500;
    const currentLength = textarea.value.length;

    counter.textContent = `${currentLength}/${maxLength}`;
  }

  $(document).ready(function() {
    // Initialize variables for event handlers (file input and file list click handlers)
    let fileInputChangeHandler, fileListClickHandler;

    // When modal is shown, attach the event listeners
    $('#fileUploadModal').on('shown.bs.modal', function() {
      const fileInput = document.getElementById('fileInput');
      const fileList = document.getElementById('fileList');
      const errorMessage = document.getElementById('error-message');

      // Handle file input change and display selected files
      fileInputChangeHandler = function() {
        errorMessage.textContent = ''; // Clear previous error messages
        const filesArray = Array.from(fileInput.files);

        // Check the number of selected files
        if (filesArray.length > 5) {
          errorMessage.textContent = 'You can only upload a maximum of 5 files.';
          return; // Exit if the limit is exceeded
        }

        fileList.innerHTML = ''; // Clear previous file list

        filesArray.forEach((file, index) => {
          const fileItem = document.createElement('div');
          fileItem.classList.add('file-item');

          fileItem.innerHTML = `
                            <span>${file.name} (${(file.size / 1024).toFixed(2)} KB)</span>
                            <span class="remove-btn" data-index="${index}">
                                <i class="fas fa-trash-alt fa-fw"></i>
                            </span>
                        `;

          fileList.appendChild(fileItem);
        });
      };

      // Handle remove file click
      fileListClickHandler = function(e) {
        // Prevent event propagation to avoid closing the modal
        e.stopPropagation();

        if (e.target.closest('.remove-btn')) {
          const index = e.target.closest('.remove-btn').getAttribute('data-index');
          const filesArray = Array.from(fileInput.files);

          // Remove file from the input's file list
          filesArray.splice(index, 1);

          // Create a new FileList object
          const newFileList = new DataTransfer();
          filesArray.forEach(file => newFileList.items.add(file));
          fileInput.files = newFileList.files;

          // Re-render the file list
          fileInput.dispatchEvent(new Event('change'));
        }
      };

      // Attach the event handlers when the modal is shown
      fileInput.addEventListener('change', fileInputChangeHandler);
      fileList.addEventListener('click', fileListClickHandler);
    });

    // When modal is hidden, remove event listeners and reset file input
    $('#fileUploadModal').on('hidden.bs.modal', function() {
      const fileInput = document.getElementById('fileInput');
      const fileList = document.getElementById('fileList');
      const errorMessage = document.getElementById('error-message');

      // Remove the event listeners to prevent errors when modal is closed
      fileInput.removeEventListener('change', fileInputChangeHandler);
      fileList.removeEventListener('click', fileListClickHandler);

      // Clear the file list display and reset file input value
      fileList.innerHTML = '';
      fileInput.value = ''; // Reset file input value
      errorMessage.textContent = ''; // Clear any error messages
    });
  });

  document.addEventListener('DOMContentLoaded', function() {
    var activeTab = localStorage.getItem('activeTab');
    if (activeTab && document.querySelector(`a[href="${activeTab}"]`)) {
      document.querySelector(`a[href="${activeTab}"]`).classList.add('active');
      document.querySelector(activeTab).classList.add('show', 'active');
    } else {
      document.querySelector('.nav-link').classList.add('active');
      document.querySelector('.tab-pane').classList.add('show', 'active');
    }

    $('#myTabs a').on('shown.bs.tab', function(e) {
      var href = $(e.target).attr('href');
      localStorage.setItem('activeTab', href);
    });
  });
</script>