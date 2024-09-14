<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <div class="row">
    <div class="form-group col-md-2">
      <label>To</label>
      <input type="date" name="date_to" id="date_to" class="form-control" onchange="filterTable(this)">
    </div>
    <div class="form-group col-md-2">
      <label>From</label>
      <input type="date" name="date_from" id="date_from" class="form-control" onchange="filterTable(this)">
    </div>
    <div class="form-group col-md-2">
      <label>Classification</label>
      <select name="reviewClass" id="reviewClass" class="form-control selectpicker show-tick" data-style="border border-secondary" onchange="filterTable(this)">
        <option value="">All</option>
        <option value="1">Daily Routine</option>
        <option value="2">Weekly Routine</option>
        <option value="3">Monthly Routine</option>
        <option value="6">Monthly Report</option>
      </select>
    </div>
  </div>
  <div class="card">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
      <h6 class="m-0 font-weight-bold text-white">Rescheduling Tasks</h6>
      <div class="dropdown no-arrow">
        <button type="button" class="btn btn-success btn-sm" id="approveButton" onclick="approveIDs(this)" style="display: none;">
          <i class="fas fa-check-double fa-fw"></i> Approve
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
          <thead class='table table-primary'>
            <tr>
              <th class="col-auto">Action</th>
              <th class="col-auto">Code</th>
              <th class="col-auto">Title</th>
              <th class="col-auto">Classification</th>
              <th class="col-auto">Original Due Date</th>
              <th class="col-auto">Requested Due Date</th>
              <th class="col-auto">Asignee</th>
            </tr>
          </thead>
          <tbody id='dataTableBody'>
            <?php
            function getTaskClass($taskClassNumber)
            {
              $taskClasses = [1 => ['DAILY ROUTINE', 'info'], 2 => ['WEEKLY ROUTINE', 'info'], 3 => ['MONTHLY ROUTINE', 'info'], 4 => ['ADDITIONAL TASK', 'info'], 5 => ['PROJECT', 'info'], 6 => ['MONTHLY REPORT', 'danger']];
              return '<span class="badge badge-' . ($taskClasses[$taskClassNumber][1] ?? 'secondary') . '">' . ($taskClasses[$taskClassNumber][0] ?? 'Unknown') . '</span>';
            }
            $result = mysqli_query($con, "SELECT DISTINCT tasks_details.*, accounts.file_name, tasks.task_details, section.dept_id, CONCAT(accounts.fname,' ',accounts.lname) AS Mname FROM tasks_details JOIN accounts ON tasks_details.in_charge = accounts.username JOIN tasks ON tasks_details.task_name = tasks.task_name JOIN section ON tasks_details.task_for = section.sec_id WHERE tasks_details.task_status=1 AND tasks_details.status='RESCHEDULE' AND section.dept_id = '$dept_id'");
            if (mysqli_num_rows($result) > 0) {
              while ($row = $result->fetch_assoc()) {
                $due_date = date_format(date_create($row['due_date']), "Y-m-d");
                $old_date = date_format(date_create($row['old_date']), "Y-m-d");
                $assignee = '<img src=' . (empty($row['file_name']) ? '../assets/img/user-profiles/nologo.png' : '../assets/img/user-profiles/' . $row['file_name']) . ' class="img-table-solo"> ' . ucwords(strtolower($row['Mname'])) . ''; ?>
                <tr>
                  <td><button type="button" onclick="checkTask(this)" class="btn btn-primary btn-sm btn-block" value="<?php echo $row['id'] ?>" data-name="<?php echo $row['task_name'] ?>"><i class="fas fa-eye fa-fw"></i> View</button></td>
                  <td><?php echo $row['task_code'] ?></td>
                  <td><?php echo $row['task_name'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
                  <td><?php echo getTaskClass($row['task_class']); ?></td>
                  <td><?php echo $due_date ?></td>
                  <td><?php echo $old_date ?></td>
                  <td><?php echo $assignee ?></td>
                </tr>
            <?php }
            } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="review" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-primary">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Reschedule Task</h5>
      </div>
      <div class="modal-body" id="taskDetails">
      </div>
      <div class="modal-footer d-flex justify-content-between w-100">
        <div>
          <button type="button" class="btn btn-success" data-dismiss="modal" id="approveTask">Approve</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal" id="rejectTask">Reject</button>
        </div>
        <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal">Close</button>
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
        <i class="fas fa-exclamation-triangle fa-5x text-danger mb-3"></i>
        <br>
        <small id='textValid' class="d-none text-danger font-italic font-weight-bold">This field cannot be empty to proceed.</small>
        <textarea name="rejectReason" id="rejectReason" class="form-control" placeholder="You’re about to reject this request. Please indicate the reason."></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="rejectConfirm">Proceed</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
  $('#dataTable').DataTable({
    "order": [
      [5, "desc"],
      [2, "asc"]
    ],
    "pageLength": 10,
    "lengthMenu": [10, 25, 50, 100],
    "drawCallback": function(settings) {
      $('[data-toggle="tooltip"]').tooltip();
    }
  });

  function filterTable() {
    var taskClass = document.getElementById('reviewClass').value;
    var date_from = document.getElementById('date_from').value;
    var date_to = document.getElementById('date_to').value;
    if (taskClass.value === '') {
      taskClass.value = null;
    }
    if (date_to.value === '') {
      date_to.value = null;
    }
    if (date_from.value === '') {
      date_from.value = null;
    }
    $('#dataTable').DataTable().destroy();
    $('#dataTableBody').empty();
    $.ajax({
      method: "POST",
      url: "../config/for_reschedule.php",
      data: {
        "filterTable": true,
        "taskClass": taskClass,
        "date_to": date_to,
        "date_from": date_from,
      },
      success: function(response) {
        $('#dataTableBody').append(response);
        $('#dataTable').DataTable({
          "order": [
            [5, "desc"],
            [2, "asc"]
          ],
          "pageLength": 5,
          "lengthMenu": [5, 10, 25, 50, 100],
          "drawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
          }
        });
      }
    })
  }

  function checkTask(element) {
    var taskID = element.value;
    $.ajax({
      method: "POST",
      url: "../config/for_reschedule.php",
      data: {
        "viewTask": true,
        "taskID": taskID,
      },
      success: function(response) {
        $('#taskDetails').html(response);
        $('#review').modal('show');
        $('#taskView_table').DataTable({
          order: [
            [0, 'asc']
          ],
          pageLength: 3,
          lengthMenu: [3, 10, 25, 50, 100],
          "drawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
          }
        });
      }
    });

    $('#approveTask').off('click').on('click', function(event) {
      var $button = $(this);
      $button.prop('disabled', true);
      var formData = new FormData(document.getElementById('approveRequest'));
      formData.append('approveTask', true);
      $.ajax({
        method: "POST",
        url: "../config/for_reschedule.php",
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
          if (response === 'Success') {
            document.getElementById('success_log').innerHTML = 'You have successfully approved the rescheduling of this task';
            $('#review').modal('hide');
            $('#success').modal('show');
          } else {
            document.getElementById('error_found').innerHTML = response;
            $('#error').modal('show');
            $button.prop('disabled', false);
          }
        }
      })
    });

    $('#rejectTask').off('click').on('click', function() {
      var $reject = $(this);
      $reject.prop('disabled', true);
      $('#danger').modal('show');
      $('#rejectConfirm').off('click').on('click', function() {
        if (document.getElementById('rejectReason').value === '') {
          document.getElementById('rejectReason').classList.add('border-danger');
          document.getElementById('textValid').classList.remove('d-none');
        } else {
          var taskID    = document.getElementById('reschedID').value;
          var taskUser  = document.getElementById('reschedUser').value;
          var taskName  = document.getElementById('resched_taskName').value;
          var reason    = document.getElementById('rejectReason').value;
          $.ajax({
            method: "POST",
            url: "../config/for_reschedule.php",
            data: {
              "rejectTask": true,
              "taskID": taskID,
              "taskUser": taskUser,
              "taskName": taskName,
              "reason": reason
            },
            success: function(response) {
              if (response === 'Success') {
                document.getElementById('success_log').innerHTML = 'You have successfully rejected the rescheduling of this task';
                $('#review').modal('hide');
                $('#danger').modal('hide');
                $('#success').modal('show');
              } else {
                $('#danger').modal('hide');
                document.getElementById('error_found').innerHTML = response;
                $('#error').modal('show');
                $reject.prop('disabled', false);
              }
            }
          });
        }
      });
    });
  }
</script>