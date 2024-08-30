<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <?php if ($access == 1) { ?>
  <?php } elseif ($access == 2) { ?>
  <?php } elseif ($access == 3) { ?>
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
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-danger">
        <h6 class="m-0 font-weight-bold text-white">For Review Tasks</h6>
        <div class="dropdown no-arrow">
          <button type="button" class="btn btn-success btn-sm" id="approveButton" onclick="approveIDs(this)" style="display: none;">
            <i class="fas fa-check-double fa-fw"></i> Approve
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
            <thead class='table table-danger'>
              <tr>
                <th class="col-auto"><input type="checkbox" id='selectAll' class="form-control"></th>
                <th class="col-auto">Action</th>
                <th class="col-auto">Code</th>
                <th class="col-auto">Title</th>
                <th class="col-auto">Classification</th>
                <th class="col-auto">Due Date</th>
                <th class="col-auto">Accomplished</th>
                <th class="col-auto">Asignee</th>
              </tr>
            </thead>
            <tbody id='dataTableBody'>
              <?php $con->next_result();
              $result = mysqli_query($con, "SELECT DISTINCT tasks_details.*, accounts.file_name, tasks.task_details, section.dept_id, CONCAT(accounts.fname,' ',accounts.lname) AS Mname FROM tasks_details JOIN accounts ON tasks_details.in_charge = accounts.username JOIN tasks ON tasks_details.task_name = tasks.task_name JOIN section ON tasks_details.task_for = section.sec_id WHERE tasks_details.task_status=1 AND tasks_details.status='REVIEW' AND section.dept_id = '$dept_id'");
              if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                  if (empty($row['file_name'])) {
                    $assigneeURL = '../assets/img/user-profiles/nologo.png';
                  } else {
                    $assigneeURL = '../assets/img/user-profiles/' . $row['file_name'];
                  } 
                  $task_classes = [1 => ['name' => 'DAILY ROUTINE', 'badge' => 'info'], 2 => ['name' => 'WEEKLY ROUTINE', 'badge' => 'info'], 3 => ['name' => 'MONTHLY ROUTINE', 'badge' => 'info'], 4 => ['name' => 'ADDITIONAL TASK', 'badge' => 'info'], 5 => ['name' => 'PROJECT', 'badge' => 'info'], 6 => ['name' => 'MONTHLY REPORT', 'badge' => 'danger']];
                  if (isset($task_classes[$row['task_class']])) {
                    $class = $task_classes[$row['task_class']]['name'];
                    $badge = $task_classes[$row['task_class']]['badge'];
                  } else {
                    $class = 'Unknown';
                    $badge = 'secondary';
                  }
                  $task_class         = '<span class="badge badge-' . $badge . '">' . $class . '</span>';
                  $due_date           = date_format(date_create($row['due_date']), "Y-m-d h:i a");
                  $date_accomplished  = date_format(date_create($row['date_accomplished']), "Y-m-d h:i a");
                  $assignee           = '<img src='.$assigneeURL.' class="img-table-solo"> '.ucwords(strtolower($row['Mname'])).'';
              ?>
                  <tr>
                    <td><input type="checkbox" name="selected_ids[]" class="form-control" value="<?php echo $row['id']; ?>"></td>
                    <td><button type="button" onclick="checkTask(this)" class="btn btn-success btn-sm btn-block" value="<?php echo $row['id'] ?>" data-name="<?php echo $row['task_name'] ?>"><i class="fas fa-bars"></i> Review</button></td>
                    <td><?php echo $row['task_code'] ?></td>
                    <td><?php echo $row['task_name'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i> <i class="fas fa-envelope text-success" data-toggle="tooltip" data-placement="right" title="<?php echo $row['remarks'] ?>"></i></td>
                    <td><?php echo $task_class ?></td>
                    <td><?php echo $due_date ?></td>
                    <td><?php echo $date_accomplished ?></td>
                    <td><?php echo $assignee ?></td>
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

<div class="modal fade" id="approve" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-success">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Approve Task</h5>
      </div>
      <div class="modal-body text-center">
        <input type="hidden" id="taskID">
        <i class="fas fa-question fa-5x text-success"></i>
        <br><br>
        Do you want to approve this selected task?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal" id="confirmButton">Confirm</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="review" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content border-success">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Review Task</h5>
      </div>
      <div class="modal-body" id="taskDetails">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal" id="approveTask">Approve</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
        <i class="fas fa-exclamation-triangle fa-5x text-danger"></i>
        <br><br>
        You're about to delete this file, <br> do you still want to proceed?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="delete_id">Proceed</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
        <button type="button" onclick="location.reload();" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include('../include/footer.php'); ?>

<script>
  $('#dataTable').DataTable({
    "order": [
      [5, "desc"],
      [3, "asc"]
    ],
    "pageLength": 5,
    "lengthMenu": [5, 10, 25, 50, 100],
    "drawCallback": function(settings) {
      $('[data-toggle="tooltip"]').tooltip();
    }
  });

  function filterTable() {
    var taskClass = document.getElementById('reviewClass').value;
    var date_from = document.getElementById('date_from').value;
    var date_to   = document.getElementById('date_to').value;
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
      url: "../config/for_review.php",
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
            [3, "asc"]
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
      url: "../config/for_review.php",
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

    $('#approveTask').off('click').on('click', function() {
      var $button = $(this);
      $button.prop('disabled', true);
      var formData = new FormData(document.getElementById('checkDetails'));
      formData.append('approveTask', true);
      console.log(formData);
      $.ajax({
        method: "POST",
        url: "../config/for_review.php",
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
          if (response === 'Success') {
            document.getElementById('success_log').innerHTML = 'Operation completed successfully.';
            $('#review').modal('hide');
            $('#success').modal('show');
          } else {
            document.getElementById('error_found').innerHTML = response;
            $('#error').modal('show');
            $button.prop('disabled', false);
          }
        }
      })
    })
  }

  function downloadFile(element) {
    var id = element.value;
    window.location.href = '../config/tasks.php?downloadFile=true&id=' + id;
  }

  function approveIDs(element) {
    var head_name = <?php echo json_encode($full_name); ?>;
    var checkboxes = document.querySelectorAll('input[type="checkbox"][name="selected_ids[]"]');
    var checkedIds = [];
    checkboxes.forEach(function(checkbox) {
      if (checkbox.checked) {
        checkedIds.push(checkbox.value);
      }
    });
    console.log(head_name);
    $('#approve').modal('show');
    $('#confirmButton').off('click').on('click', function() {
      $.ajax({
        url: '../config/for_review.php',
        method: 'POST',
        data: {
          "approveMultiple": true,
          "checkedIds": checkedIds,
          "head_name": head_name
        },
        success: function(response) {
          if (response === 'Success') {
            document.getElementById('success_log').innerHTML = 'Operation completed successfully.';
            $('#start').modal('hide');
            $('#success').modal('show');
          } else {
            document.getElementById('error_found').innerHTML = response;
            $('#error').modal('show');
          }
        },
      });
    });
  }

  $(document).ready(function() {
    $('#selectAll').click(function() {
      var isChecked = this.checked;
      $('input[name="selected_ids[]"]').prop('checked', isChecked);
      toggleApproveButton();
    });

    $('input[name="selected_ids[]"]').click(function() {
      var allChecked = $('input[name="selected_ids[]"]:checked').length == $('input[name="selected_ids[]"]').length;
      $('#selectAll').prop('checked', allChecked);
      toggleApproveButton();
    });

    function toggleApproveButton() {
      if ($('input[name="selected_ids[]"]:checked').length > 0) {
        $('#approveButton').show();
      } else {
        $('#approveButton').hide();
      }
    }
  });
</script>