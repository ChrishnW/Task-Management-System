<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <div class="card">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold">Task List</h6>
      <div>
        <button class="btn btn-secondary"><i class="fas fa-file-import fa-fw"></i> Import</button>
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
            },
            success: function(result) {
              console.log(result);
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
</script>