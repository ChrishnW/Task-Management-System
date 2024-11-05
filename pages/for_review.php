<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <?php if ($access == 1) { ?>
  <?php } elseif ($access == 2) { ?>
  <?php } elseif ($access == 3) { ?>
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
          <option value="4">Additional Task</option>
        </select>
      </div>
    </div>
    <div class="card">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold">For Review Tasks</h6>
        <div class="dropdown no-arrow">

        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th class="col-1 text-center"><input type="checkbox" id='selectAll' class="form-control"></th>
                <th>Code</th>
                <th>Title</th>
                <th>Classification</th>
                <th>Due Date</th>
                <th>Accomplished</th>
                <th>Asignee</th>
                <th class="text-truncate">
                  <button type="button" class="btn btn-success" id="approveButton" onclick="approveIDs(this)" style="display: none;">
                    <i class="fas fa-check-double fa-fw"></i> Approve
                  </button>
                </th>
              </tr>
            </thead>
            <tbody id='dataTableBody'>
              <?php $con->next_result();
              $result = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN section s ON tl.task_for=s.sec_id JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.task_status=1 AND td.status='REVIEW' AND s.dept_id = '$dept_id'");
              if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                  $due_date           = date_format(date_create($row['due_date']), "F d, Y h:i a");
                  $date_accomplished  = date_format(date_create($row['date_accomplished']), "F d, Y h:i a");
              ?>
                  <tr>
                    <td><input type="checkbox" name="selected_ids[]" class="form-control" value="<?php echo $row['id']; ?>"></td>
                    <td><?php echo $row['task_code'] ?></td>
                    <td>
                      <?php echo $row['task_name']; ?>
                      <i class='fas fa-info-circle' data-toggle='tooltip' data-placement='right' title='<?php echo $row['task_details']; ?>'></i>
                      <?php if ((new DateTime($row['date_accomplished']))->setTime(0, 0, 0) > (new DateTime($row['due_date']))->setTime(0, 0, 0)): ?>
                        <i class='fas fa-hourglass-end text-danger' data-toggle='tooltip' data-placement='right' title='Late Submission'></i>
                      <?php endif; ?>
                      <?php if ($row['requirement_status'] == 1): ?>
                        <i class="fas fa-photo-video text-warning" data-toggle="tooltip" data-placement="right" title="File Attachment Required"></i> <?php endif; ?>
                      <?php if ($row['old_date'] !== NULL): ?>
                        <i class='fas fa-sync text-warning' data-toggle='tooltip' data-placement='right' title='Rescheduled'></i>
                      <?php endif; ?>
                    </td>
                    <td><?php echo getTaskClass($row['task_class']); ?></td>
                    <td class="text-truncate"><?php echo $due_date ?></td>
                    <td class="text-truncate"><?php echo $date_accomplished ?></td>
                    <td class="text-truncate"><?php echo getUser($row['in_charge']); ?></td>
                    <td class="text-truncate"><button type="button" onclick="checkTask(this)" class="btn btn-warning btn-block" value="<?php echo $row['id'] ?>" data-name="<?php echo $row['task_name'] ?>"><i class="fas fa-star fa-fw"></i> Review</button></td>
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="review" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content border-warning">
      <div class="modal-header">
        <h5 class="modal-title">Review Task</h5>
      </div>
      <div class="modal-body" id="taskDetails">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal" id="approveTask">Approve</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
      "targets": [0, 7]
    }],
    "order": [
      [5, "desc"],
      [3, "asc"]
    ],
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
            [6, "desc"],
            [3, "asc"]
          ],
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
        $('[data-toggle="popover"]').popover();
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
            document.getElementById('success_log').innerHTML = 'Task ' + document.getElementById('approveCode').value + ' reviewed and approved successfully.';
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