<?php
include('../include/header.php');
?>

<div class="container-fluid">
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
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold">Rescheduling Tasks</h6>
      <div class="dropdown no-arrow">
        <button type="button" class="btn btn-success btn-sm" id="approveButton" onclick="approveIDs(this)" style="display: none;">
          <i class="fas fa-check-double fa-fw"></i> Approve
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Code</th>
              <th>Task</th>
              <th>Classification</th>
              <th>Original Due Date</th>
              <th>Requested Due Date</th>
              <th>Asignee</th>
              <th></th>
            </tr>
          </thead>
          <tbody id='dataTableBody'>
            <?php
            $result = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN section s ON tl.task_for=s.sec_id JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.task_status=1 AND td.status='RESCHEDULE' AND s.dept_id = '$dept_id'");
            if (mysqli_num_rows($result) > 0) {
              while ($row = $result->fetch_assoc()) {
                $due_date = date_format(date_create($row['due_date']), "F d, Y");
                $old_date = date_format(date_create($row['old_date']), "F d, Y"); ?>
                <tr>
                  <td><?php echo $row['task_code'] ?></td>
                  <td><?php echo $row['task_name'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
                  <td><?php echo getTaskClass($row['task_class']); ?></td>
                  <td class="text-truncate"><?php echo $due_date ?></td>
                  <td class="text-truncate"><?php echo $old_date ?></td>
                  <td class="text-truncate"><?php echo getUser($row['in_charge']); ?></td>
                  <td class="text-truncate"><button type="button" onclick="checkTask(this)" class="btn btn-primary btn-block" value="<?php echo $row['id'] ?>" data-name="<?php echo $row['task_name'] ?>"><i class="fas fa-envelope-open-text fa-fw"></i> Open</button></td>
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

<?php include('../include/footer.php'); ?>

<script>
  $('#dataTable').DataTable({
    "columnDefs": [{
      "orderable": false,
      "searchable": false,
      "targets": 6
    }],
    "order": [
      [4, "desc"]
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
          var taskID = document.getElementById('reschedID').value;
          var taskUser = document.getElementById('reschedUser').value;
          var taskCode = document.getElementById('reschedCode').value;
          var reason = document.getElementById('rejectReason').value;
          $.ajax({
            method: "POST",
            url: "../config/for_reschedule.php",
            data: {
              "rejectTask": true,
              "taskID": taskID,
              "taskUser": taskUser,
              "taskCode": taskCode,
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
                console.log(response);
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