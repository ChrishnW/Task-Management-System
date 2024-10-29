<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <div class="card">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold">Task List</h6>
      <div>
        <button class="btn btn-secondary" data-toggle="modal" data-target="#importModal"><i class="fas fa-file-import fa-fw"></i> Import</button>
        <button class="btn btn-secondary" onclick="exportThis();"><i class="fas fa-file-export fa-fw"></i> Export</button>
      </div>
      <div>
        <button class="btn btn-secondary" onclick="showCreate(this)"><i class="fas fa-plus fa-fw"></i> Add</button>
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
                <td><button class="btn btn-circle btn-sm btn-primary" value="<?php echo $row['sec_id']; ?>" onclick="toggleDetails(this)">+</button> <?php echo $row['sec_name']; ?></td>
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
</div>

<div class="modal fade" id="importModal" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="importModalLabel">Import Task</h5>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <i id="loadingIcon" class="fas fa-spinner fa-7x fa-spin text-primary d-none"></i>
          <i id="validateIcon" class="fas fa-check-circle fa-7x text-success d-none"></i>
          <i id="errorIcon" class="fas fa-times-circle fa-7x text-danger d-none"></i>
        </div>
        <div id="dropZone" class="drop-zone">
          Drag and drop an Excel file here, or <br>
          <button type="button" class="btn btn-link" onclick="document.getElementById('fileInput').click()">click to select a file</button>
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
        <button onclick="updateTask(this)" class="btn btn-success" id="updateButton">Update</button>
        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include('../include/footer.php'); ?>

<script src="../assets/js/import.js"></script>

<script>
  $('#regTaskTable').DataTable({
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

    if (row.child.isShown()) {
      row.child.hide();
      $(button).text('+');
    } else {
      $.ajax({
        url: '../config/registered_tasks.php', // Replace with your PHP file
        method: 'POST',
        data: {
          "toggleDetails": true,
          "id": id
        }, // Pass any necessary data
        success: function(data) {
          row.child(format(data)).show();
          $(button).text('-');
          $('[data-toggle="tooltip"]').tooltip();
        },
        error: function() {
          document.getElementById('error_found').innerHTML = 'Failed to fetch data.';
          $('#error').modal('show');
        }
      });
    }
  }

  function format(data) {
    // `data` is the response from the AJAX call
    var details = JSON.parse(data);
    var html = '<table class="table table-striped table-hover">';
    details.forEach(function(detail) {
      html += '<tr>' +
        '<td>' + detail.status + '</td>' +
        '<td>' + detail.task_name + '</td>' +
        '<td>' + detail.task_details + '</td>' +
        '<td>' + detail.task_class + '</td>' +
        '<td>' + detail.submission + '</td>' +
        '<td><div class="d-flex justify-content-center">' + detail.in_charge_list + '</td></td>' +
        '<td><button class="btn btn-secondary btn-sm" value="' + detail.id + '" onclick="editTask(this)"> Edit </button></td>' +
        '</tr>';
    });
    html += '</table>';
    return html;
  }

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
        $('#edit').modal('show');
        $('select').selectpicker();
        document.getElementById('updateButton').onclick = function() {
          const taskName = document.getElementById('editTaskName').value;
          const taskDetails = document.getElementById('editTaskDetails').value;
          const submission = document.getElementById('editSubmission').value;
          const dueDate = document.getElementById('editAttachment').value;
          const assignList = [];
          for (let option of document.getElementById('editEmplist').options) {
            if (option.selected) {
              assignList.push(option.value);
            }
          }
          $.ajax({
            url: '../config/registered_tasks.php',
            method: 'POST',
            data: {
              "updateTask": true,
              "id": id,
              "taskName": taskName,
              "taskDetails": taskDetails,
              "submission": submission,
              "editAttachment": dueDate,
              "assignList": assignList,
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
    const formData = new FormData();
    formData.append('file', selectedFile);
    formData.append('taskImport', true);

    $.ajax({
      type: 'POST',
      url: "../config/import.php",
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
        if (response === 'Success') {
          alert("File imported successfully!");
        } else {
          alert("Error during import: " + response);
        }
      },
      error: function() {
        alert("An error occurred during the import process.");
      }
    });
  }
</script>