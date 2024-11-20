<?php
include('../include/header.php');
$result = mysqli_query($con, "TRUNCATE task_temp");
?>

<div class="container-fluid">
  <?php if ($access == 1) : ?>
    <div class="card">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <div>
          <h4 class="m-0 font-weight-bold">Task List</h4>
        </div>
        <div>
          <button class="btn btn-orange" data-toggle="modal" data-target="#importModal"><i class="fas fa-file-import fa-fw"></i> Import</button>
          <button class="btn btn-orange" onclick="exportThis();"><i class="fas fa-file-export fa-fw"></i> Export</button>
        </div>
        <div>
          <button class="btn btn-success" onclick="createTask();"><i class="fas fa-plus fa-fw"></i> Create</button>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table" id="regTaskTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th class="col-1"></th>
                <th class="col-4">Section</th>
                <th class="col-4">Department</th>
                <th class="col-2">Tasks Count</th>
              </tr>
            </thead>
            <tbody>
              <?php $getTaskList = mysqli_query($con, "SELECT * FROM department d JOIN section s ON d.dept_id=s.dept_id WHERE s.status=1");
              while ($row = mysqli_fetch_assoc($getTaskList)) {
                $taskCount = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS count FROM task_list WHERE task_for='{$row['sec_id']}'")); ?>
                <tr>
                  <td><input type="checkbox" class="form-control export-sec-list" value="<?php echo $row['sec_id']; ?>"></td>
                  <td><button class="btn btn-circle btn-primary toggle-details" value="<?php echo $row['sec_id']; ?>" onclick="toggleDetails(this)"><i class="far fa-eye"></i></button> <?php echo $row['sec_name']; ?></td>
                  <td><?php echo $row['dept_name']; ?></td>
                  <td class="text-center">
                    <span class="badge badge-pill badge-success">
                      <?php echo $taskCount['count']; ?>
                    </span>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php elseif ($access == 3) : ?>
    <div class="card">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold">Task List</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table" id="regTaskTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th class="col-1 text-center text-truncate">
                  <button class="btn btn-orange" onclick="exportThis();"><i class="fas fa-file-export fa-fw"></i> Export</button>
                </th>
                <th class="col-4">Section</th>
                <th class="col-4">Department</th>
                <th class="col-2">Tasks Count</th>
              </tr>
            </thead>
            <tbody>
              <?php $getTaskList = mysqli_query($con, "SELECT * FROM department d JOIN section s ON d.dept_id=s.dept_id WHERE s.status=1 AND d.dept_id='$dept_id'");
              while ($row = mysqli_fetch_assoc($getTaskList)) {
                $taskCount = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS count FROM task_list WHERE task_for='{$row['sec_id']}'")); ?>
                <tr>
                  <td><input type="checkbox" class="form-control export-sec-list" value="<?php echo $row['sec_id']; ?>"></td>
                  <td><button class="btn btn-circle btn-primary toggle-details" value="<?php echo $row['sec_id']; ?>" onclick="toggleDetails(this)"><i class="far fa-eye"></i></button> <?php echo $row['sec_name']; ?></td>
                  <td><?php echo $row['dept_name']; ?></td>
                  <td class="text-center">
                    <span class="badge badge-pill badge-success">
                      <?php echo $taskCount['count']; ?>
                    </span>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<div class="modal fade" id="importModal" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="importModalLabel">
          <i class="fas fa-upload"></i> Import Task
        </h5>
        <button type="button" class="btn btn-primary" onclick="downloadTemplate()">
          <i class="fas fa-download"></i> Download Template
        </button>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <i id="loadingIcon" class="fas fa-spinner fa-7x fa-spin text-primary d-none"></i>
          <i id="validateIcon" class="fas fa-check-circle fa-7x text-success d-none"></i>
          <i id="errorIcon" class="fas fa-times-circle fa-7x text-danger d-none"></i>
        </div>
        <div id="dropZone" class="drop-zone">
          Drag and drop an Excel file here, Or <br>
          <button type="button" class="btn btn-link text-decoration-none" onclick="document.getElementById('fileInput').click()">Click here to select a file.</button>
        </div>
        <!-- Hidden file input -->
        <input type="file" id="fileInput" accept=".xlsx, .xls" style="display: none;" />
        <p id="fileName" class="mt-3 text-muted"></p>
        <!-- Progress Bar -->
        <div class="progress mt-3 d-none" id="progressContainer">
          <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="importBtn" type="button" class="btn btn-primary d-none" onclick="importFile()">Import</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="edit" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content border-primary">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Edit Task</h5>
      </div>
      <div class="modal-body" id="taskInfo">
      </div>
      <div class="modal-footer">
        <button class="btn btn-success d-none" id="updateButton">Save Changes</button>
        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="assignee" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content border-primary">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalTitle"></h5>
      </div>
      <div class="modal-body" id="assigneeInfo">
      </div>
      <div class="modal-footer justify-content-between">
        <button class="btn btn-danger" id="removeThis">Remove</button>
        <div>
          <button class="btn btn-success d-none" id="updateBtn">Save Changes</button>
          <button class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="create" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content border-success">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Create Task</h5>
      </div>
      <div class="modal-body" id="newtaskInfo">
        <div class="row">
          <!-- Task Name -->
          <div class="col-md-6">
            <div class="form-group">
              <label for="newTaskName" class="font-weight-bold">Task Name</label>
              <input type="text" class="form-control" id="newTaskName" placeholder="Enter task name">
            </div>
          </div>

          <!-- Task Class -->
          <div class="col-md-6">
            <div class="form-group">
              <label for="newClass" class="font-weight-bold">Task Class</label>
              <select name="newClass" id="newClass" class="form-control selectpicker show-tick" data-style="border-secondary">
                <?php
                $getClass = mysqli_query($con, "SELECT * FROM task_class");
                while ($row = mysqli_fetch_assoc($getClass)) : ?>
                  <option value="<?php echo $row['id'] ?>">
                    <?php echo ucwords(strtolower($row['task_class'])); ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <!-- Task Details -->
          <div class="col-12">
            <div class="form-group">
              <label for="newTaskDetails" class="font-weight-bold">Task Details</label>
              <textarea class="form-control" id="newTaskDetails" rows="4" placeholder="Enter task details"></textarea>
            </div>
          </div>
        </div>

        <div class="row">
          <!-- Task For -->
          <div class="col-12">
            <div class="form-group">
              <label for="newEmplist" class="font-weight-bold">Task For</label>
              <select class="form-control selectpicker" data-live-search="true" data-style="border-secondary" name="newtaskForList[]" id="newtaskForList" multiple>
                <?php
                $getSec = mysqli_query($con, "SELECT * FROM section WHERE status=1");
                while ($row = mysqli_fetch_assoc($getSec)) { ?>
                  <option value="<?php echo $row['sec_id']; ?>">
                    <?php echo ucwords(strtolower($row['sec_name'])); ?>
                  </option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success" id="saveTask">Create</button>
        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include('../include/footer.php'); ?>

<script src="../assets/js/import.js"></script>

<script>
  $('#regTaskTable').DataTable({
    searching: false, // Disable the search bar
    lengthChange: false,
    "columnDefs": [{
      "autoWidth": false,
      "orderable": false,
      "searchable": false,
      "targets": 0,
    }],
    "order": [
      [1, "asc"]
    ]
  });

  function createTask() {
    $('#create').modal('show');
    var saveButton = document.getElementById('saveTask');
    document.getElementById('saveTask').onclick = function() {
      saveButton.disabled = true;
      let data = {
        "createTask": true
      };
      data.taskName = document.getElementById('newTaskName').value;
      data.taskClass = document.getElementById('newClass').value;
      const taskforList = [];
      for (let option of document.getElementById('newtaskForList').options) {
        if (option.selected) {
          taskforList.push(option.value);
        }
      }
      data.taskFor = taskforList;
      data.taskDetails = document.getElementById('newTaskDetails').value;
      $.ajax({
        type: "POST",
        url: "../config/registered_tasks.php",
        data: data,
        success: function(response) {
          if (response == 'Success') {
            document.getElementById('success_log').innerHTML = 'Task created successfully.';
            $('#success').modal('show');
          } else {
            document.getElementById('error_found').innerHTML = response;
            $('#error').modal('show');
            saveButton.disabled = false;
          }
        }
      });
    }
  }

  function exportThis() {
    const selectedValues = [];
    $('.export-sec-list:checked').each(function() {
      selectedValues.push($(this).val());
    });

    const fullURL = `../config/export.php?exportTaskList=true&section=${selectedValues}`;

    // Open the new URL in a new window
    window.open(fullURL);
  }

  function toggleDetails(button) {
    var id = button.value;
    var tr = $(button).closest('tr');
    var row = $('#regTaskTable').DataTable().row(tr);
    var tableId = 'detailsTable_' + id; // Unique key for each row

    if (row.child.isShown()) {
      row.child.hide();
      $(button).html('<i class="far fa-eye"></i>');
      localStorage.removeItem(tableId); // Remove state when closed
    } else {
      $.ajax({
        url: '../config/registered_tasks.php', // Replace with your PHP file
        method: 'POST',
        data: {
          "toggleDetails": true,
          "id": id
        }, // Pass any necessary data
        success: function(data) {
          row.child(format(data, id)).show();
          $(button).html('<i class="far fa-eye-slash"></i>');
          localStorage.setItem(tableId, 'open'); // Save state when opened
          $('[data-toggle="tooltip"]').tooltip();
        },
        error: function() {
          document.getElementById('error_found').innerHTML = 'Failed to fetch data.';
          $('#error').modal('show');
        }
      });
    }
  }

  function format(data, id) {
    // `data` is the response from the AJAX call
    var details = JSON.parse(data);
    var html = '<table class="table table-striped table-hover" id="detailsTable_' + id + '">';
    // Add thead and header row
    html += '<thead><tr>' +
      '<th></th>' +
      '<th>Task Name</th>' +
      '<th>Task Details</th>' +
      '<th>Task Class</th>' +
      '<th>Assignee</th>' +
      '<th></th>' +
      '</tr></thead>';
    details.forEach(function(detail) {
      html += '<tr>' +
        '<td class="text-center">' + detail.status + '</td>' +
        '<td class="text-truncate">' + detail.task_name + '</td>' +
        '<td>' + detail.task_details + '</td>' +
        '<td>' + detail.task_class + '</td>' +
        '<td><div class="d-flex justify-content-center">' + detail.in_charge_list + '</td></td>' +
        '<td class="text-truncate"><button class="btn btn-secondary" value="' + detail.id + '" onclick="editTask(this)"><i class="fas fa-edit fa-fw"></i> Edit </button></td>' +
        '</tr>';
    });
    html += '</table>';

    // Initialize DataTable
    setTimeout(function() {
      $('#detailsTable_' + id + '').DataTable({
        "columnDefs": [{
          "autoWidth": false,
          "orderable": false,
          "searchable": false,
          "targets": [0, 5],
        }],
        "order": [
          [1, "asc"]
        ]
      });
    }, 0); // Delay to ensure the table is fully rendered

    return html;
  }

  // Function to check and restore state on page load
  function restoreTableState() {
    $('#regTaskTable tbody tr').each(function() {
      var button = $(this).find('button.toggle-details'); // Adjust selector as needed
      var id = button.val();
      var tableId = 'detailsTable_' + id;

      if (localStorage.getItem(tableId) === 'open') {
        toggleDetails(button[0]); // Open the row if it was previously opened
      }
    });
  }

  // Call restoreTableState on page load
  $(document).ready(function() {
    restoreTableState();
  });

  function editTask(element) {
    var id = element.value;
    $.ajax({
      url: '../config/registered_tasks.php',
      method: 'POST',
      data: {
        "editTask": true,
        "id": id
      },
      success: function(response) {
        $('#taskInfo').html(response);
        $('select').selectpicker();
        openSpecificModal('edit', 'modal-md');

        const saveButton = document.getElementById('updateButton');
        const inputs = document.querySelectorAll("#editTaskName, #editTaskDetails");
        const selects = document.querySelectorAll("#editClass");

        // Reset the save button to be hidden
        if (!saveButton.classList.contains("d-none")) {
          saveButton.classList.add("d-none");
        }

        // Function to remove the d-none class from the save button
        function removeHiddenClass() {
          if (saveButton.classList.contains("d-none")) {
            saveButton.classList.remove("d-none");
          }
        }

        // Add event listeners to inputs
        inputs.forEach(input => {
          input.addEventListener("input", removeHiddenClass);
        });

        // Add event listeners to selects
        selects.forEach(select => {
          select.addEventListener("change", removeHiddenClass);
        });

        document.getElementById('updateButton').onclick = function() {
          const taskName = document.getElementById('editTaskName').value;
          const taskDetails = document.getElementById('editTaskDetails').value;
          const taskClass = document.getElementById('editClass').value;
          $.ajax({
            url: '../config/registered_tasks.php',
            method: 'POST',
            data: {
              "updateTask": true,
              "id": id,
              "taskName": taskName,
              "taskDetails": taskDetails,
              "taskClass": taskClass,
            },
            success: function(result) {
              if (result == 'Success') {
                document.getElementById('success_log').innerHTML = 'Task information updated successfully.';
                $('#success').modal('show');
              } else {
                document.getElementById('error_found').innerHTML = result;
                $('#error').modal('show');
              }
            }
          })
        }
      },
    });
  }

  function assignDetails(user) {
    const taskID = user.getAttribute('data-id');
    const taskName = user.getAttribute('data-task');
    $.ajax({
      url: '../config/registered_tasks.php',
      method: 'POST',
      data: {
        "assignDetails": true,
        "taskID": taskID
      },
      success: function(result) {
        $('#assigneeInfo').html(result);
        $('select').selectpicker();
        document.getElementById('modalTitle').innerHTML = taskName;
        document.getElementById('ediIncharge').value = user.alt;
        openSpecificModal('assignee', 'modal-md');

        const saveButton = document.getElementById('updateBtn');
        const inputs = document.querySelectorAll("#editSubmission");
        const selects = document.querySelectorAll("#editAttachment");

        // Reset the save button to be hidden
        if (!saveButton.classList.contains("d-none")) {
          saveButton.classList.add("d-none");
        }

        // Function to remove the d-none class from the save button
        function removeHiddenClass() {
          if (saveButton.classList.contains("d-none")) {
            saveButton.classList.remove("d-none");
          }
        }

        // Add event listeners to inputs
        inputs.forEach(input => {
          input.addEventListener("input", removeHiddenClass);
        });

        // Add event listeners to selects
        selects.forEach(select => {
          select.addEventListener("change", removeHiddenClass);
        });

        document.getElementById('updateBtn').onclick = function() {
          const id = document.getElementById('editTaskID').value;
          const submission = document.getElementById('editSubmission').value;
          const attachment = document.getElementById('editAttachment').value;
          $.ajax({
            url: '../config/registered_tasks.php',
            method: 'POST',
            data: {
              "updateAssignee": true,
              "id": id,
              "submission": submission,
              "attachment": attachment,
            },
            success: function(response) {
              if (response == 'Success') {
                document.getElementById('success_log').innerHTML = 'Task information updated successfully.';
                $('#success').modal('show');
              } else {
                document.getElementById('error_found').innerHTML = response;
                $('#error').modal('show');
              }
            }
          })
        }

        document.getElementById('removeThis').onclick = function() {
          $('#delete').modal('show');
          document.getElementById('confirmBtn').onclick = function() {
            $('#delete').modal('hide');
            const id = document.getElementById('editTaskID').value;
            $.ajax({
              url: '../config/registered_tasks.php',
              method: 'POST',
              data: {
                "removeIncharge": true,
                "id": id,
              },
              success: function(response) {
                if (response == 'Success') {
                  document.getElementById('success_log').innerHTML = 'Task assignee has been removed successfully.';
                  $('#success').modal('show');
                } else {
                  document.getElementById('error_found').innerHTML = response;
                  $('#error').modal('show');
                }
              }
            });
          }
        }
      }
    });
  }

  function validateFile() {
    const formData = new FormData();
    formData.append('file', selectedFile);
    formData.append('validateFile', true);

    $.ajax({
      type: 'POST',
      url: "../config/import.php",
      data: formData,
      contentType: false,
      processData: false,
      xhr: function() {
        const xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener("progress", (e) => {
          if (e.lengthComputable) {
            const percentComplete = Math.round((e.loaded / e.total) * 100);
            updateProgressBar(percentComplete, "bg-info");
          }
        }, false);
        return xhr;
      },
      success: function(response) {
        if (response === 'Valid') { // Assuming 'Valid' indicates a valid file
          updateProgressBar(100, "bg-success");
          loadingIcon.classList.add('d-none'); // Hide loading icon
          validateIcon.classList.remove('d-none'); // Show success icon
          importBtn.classList.remove('d-none'); // Show Import button
        } else {
          handleError(response);
        }
      },
      error: function() {
        // Display error at the last completed percentage
        updateProgressBar(progressBar.style.width.replace('%', ''), "bg-danger");
        fileNameDisplay.textContent = "An error occurred during validation.";
      }
    });
  }

  // Update progress bar
  function updateProgressBar(percent, className) {
    progressBar.style.width = percent + '%';
    progressBar.className = `progress-bar progress-bar-striped ${className}`;
  }

  // Error handling function to manage icons and messages
  function handleError(message) {
    loadingIcon.classList.add('d-none'); // Hide loading icon
    errorIcon.classList.remove('d-none'); // Show error icon
    fileNameDisplay.textContent = "Error: " + message;
    updateProgressBar(progressBar.style.width.replace('%', ''), "bg-danger");
  }

  // Final import function to process the validated file
  function importFile() {
    document.getElementById('importBtn').disabled = true;
    const formData = new FormData();
    formData.append('file', selectedFile);
    formData.append('taskImport', true);

    togglePreloader(true);

    $.ajax({
      type: 'POST',
      url: "../config/import.php",
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
        togglePreloader(false);
        if (response == 'Success') {
          document.getElementById('success_log').innerHTML = 'Task information updated successfully.';
          $('#success').modal('show');
        } else {
          document.getElementById('importBtn').disabled = false;
          document.getElementById('error_found').innerHTML = response;
          $('#error').modal('show');
        }
      },
      error: function() {
        alert("An error occurred during the import process.");
      }
    });
  }

  function downloadTemplate() {
    window.open('../files/for_import_tasks_excel_template.xlsx', '_blank');
  }
</script>