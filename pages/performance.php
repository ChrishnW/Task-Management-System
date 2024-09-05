<?php

use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

include('../include/header.php');
?>

<div class="container-fluid">
  <?php if ($access == 1) { ?>
  <?php } elseif ($access == 2) { ?>
    <div class="row justify-content-center">
      <div class="col-xl-5 col-lg-5">
        <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Performance</h6>
            <div class="dropdown no-arrow">
              <button type="button" onclick="showPerformance(this)" class="btn">
                <i class="fas fa-eye fa-fw text-primary"></i> View
              </button>
              <button type="button" id="print" class="btn">
                <i class="fas fa-print fa-fw text-success"></i> Print
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center"><img class="profile-user-img img-fluid img-circle" src="<?php echo $profileURL ?>" alt="User profile picture">
                </div>
                <h3 class="profile-username text-center"><?php echo $full_name ?></h3>
                <p class="text-muted text-center"><?php echo $section ?></p>
                <ul class="list-group list-group-unbordered mb-3">
                  <?php
                  function getPercentage($average)
                  {
                    if ($average == 5.0) {
                      return 120;
                    } elseif ($average >= 4.0 && $average <= 4.99) {
                      return 105 + (($average - 4.0) / (4.99 - 4.0)) * (119 - 105);
                    } elseif ($average >= 3.0 && $average <= 3.99) {
                      return 95 + (($average - 3.0) / (3.99 - 3.0)) * (104 - 95);
                    } elseif ($average >= 2.0 && $average <= 2.99) {
                      return 80 + (($average - 2.0) / (2.99 - 2.0)) * (94 - 80);
                    } elseif ($average >= 0.0 && $average <= 1.99) {
                      return 70 + (($average - 0.0) / (1.99 - 0.0)) * (79 - 70);
                    } else {
                      return 0;
                    }
                  }
                  $count_task = mysqli_query($con, "SELECT *, (SELECT COUNT(id) FROM tasks_details WHERE in_charge='$username' AND task_status=1 AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE) AND task_class NOT IN (5, 6)) AS task_total, (SELECT COUNT(id) FROM tasks_details WHERE task_status=1 AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE) AND task_class = 6) AS report_total FROM tasks_details WHERE task_status=1 AND status='FINISHED' AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE) AND in_charge='$username'");
                  $routine_sum = 0;
                  $report_sum = 0;
                  $routine_total = 0;
                  $report_total = 0;
                  while ($count_row = $count_task->fetch_assoc()) {
                    if ($count_row['task_class'] != 5 && $count_row['task_class'] != 6) {
                      $routine_sum += $count_row['achievement'];
                    }
                    if ($count_row['task_class'] == 6) {
                      $report_sum += $count_row['achievement'];
                    }
                    $routine_total = $count_row['task_total'];
                    $report_total = $count_row['report_total'];
                  }

                  $routine_average = $routine_total > 0 ? number_format(($routine_sum / $routine_total), 2) : 0;
                  $routine_percentage = $routine_total > 0 ? number_format(getPercentage($routine_average), 2) : 0;

                  $report_average = $report_total > 0 ? number_format(($report_sum / $report_total), 2) : 0;
                  $report_percentage = $report_total > 0 ? number_format(getPercentage($report_average), 2) : 0;
                  ?>
                  <li class="list-group-item"><b>Completed Tasks</b><a class="float-right"><?php echo $completed_tasks ?> out of <?php echo $total_tasks ?></a></li>
                  <li class="list-group-item"><b>Average</b><a class="float-right"> <?php echo $routine_average ?> for Routine | <?php echo $report_average ?> for Report</a></li>
                  <li class="list-group-item"><b>Rating</b><a class="float-right"> <?php echo $routine_percentage ?>% | <?php echo $report_percentage ?>% </a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php } elseif ($access == 3) { ?>
    <div class="row">
      <div class="form-group col-md-2">
        <label>To</label>
        <input type="date" name="date_to" id="date_to" class="form-control" onchange="calculate(this)">
      </div>
      <div class="form-group col-md-2">
        <label>From</label>
        <input type="date" name="date_from" id="date_from" class="form-control" onchange="calculate(this)">
      </div>
      <div class="form-group col-md-2">
        <label>Section</label>
        <select id="section" class="form-control selectpicker" data-style="bg-primary text-white text-capitalize" data-size="5" onchange="calculate(this)">
          <option value="" data-subtext="Default" selected>All</option>
          <?php
          $con->next_result();
          $sql = mysqli_query($con, "SELECT * FROM section WHERE status='1' AND dept_id='$dept_id'");
          if (mysqli_num_rows($sql) > 0) {
            while ($row = mysqli_fetch_assoc($sql)) { ?>
              <option value='<?php echo $row['sec_id'] ?>' data-subtext='<?php echo $row['sec_id'] ?>' class="text-capitalize"><?php echo strtolower($row['sec_name']) ?></option>
          <?php }
          } ?>
        </select>
      </div>
    </div>
    <div class="card">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
        <h6 class="m-0 font-weight-bold text-white">TMS Member Performance</h6>
        <div class="dropdown no-arrow">
          <button type="button" onclick="taskDownload(this)" class="btn btn-sm btn-success"><i class="fas fa-file-excel fa-fw"></i> Download</button>
          <input type="hidden" name="viewTableID" id="viewTableID">
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
            <thead class='table table-success'>
              <tr>
                <th class="col col-md-1">Rank</th>
                <th>Member</th>
                <th>Section</th>
                <th>Task Count</th>
                <th class="col col-md-2">Average</th>
                <th class="col col-md-2">Percentage</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id='dataTableBody'>
              <?php
              $con->next_result();
              function getPercentage($average)
              {
                if ($average == 5.0) {
                  return 105;
                } elseif ($average >= 4.0 && $average <= 4.99) {
                  return 100 + (($average - 4.0) / (4.99 - 4.0)) * (104 - 100);
                } elseif ($average >= 3.0 && $average <= 3.99) {
                  return 90 + (($average - 3.0) / (3.99 - 3.0)) * (99 - 90);
                } elseif ($average >= 2.0 && $average <= 2.99) {
                  return 80 + (($average - 2.0) / (2.99 - 2.0)) * (89 - 80);
                } elseif ($average >= 0.0 && $average <= 1.99) {
                  return 70 + (($average - 0.0) / (1.99 - 0.0)) * (79 - 70);
                } else {
                  return 0;
                }
              }

              $query = "SELECT accounts.*, section.dept_id, section.sec_name FROM accounts JOIN section ON section.sec_id = accounts.sec_id WHERE section.dept_id = '$dept_id' AND accounts.access = 2";
              $result = mysqli_query($con, $query);
              while ($row = $result->fetch_assoc()) {
                $imageURL = empty($row['file_name']) ? '../assets/img/user-profiles/nologo.png' : '../assets/img/user-profiles/' . $row['file_name'];
                $assignee = $row['username'];
                $task_query = "SELECT *, (SELECT COUNT(id) FROM tasks_details WHERE in_charge = '$assignee' AND task_status = 1 AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE) AND task_class NOT IN (5, 6)) AS task_total, (SELECT COUNT(id) FROM tasks_details WHERE in_charge = '$assignee' AND task_status = 1 AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE) AND task_class = 6) AS report_total FROM tasks_details WHERE task_status = 1 AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE) AND in_charge = '$assignee'";
                $count_task = mysqli_query($con, $task_query);
                $routine_sum    = 0;
                $report_sum     = 0;
                $routine_total  = 0;
                $report_total   = 0;
                while ($count_row = $count_task->fetch_assoc()) {
                  if ($count_row['task_class'] != 5 && $count_row['task_class'] != 6) {
                    $routine_sum += $count_row['achievement'];
                  }
                  if ($count_row['task_class'] == 6) {
                    $report_sum += $count_row['achievement'];
                  }
                  $routine_total = $count_row['task_total'];
                  $report_total = $count_row['report_total'];
                }

                $routine_average = $routine_total > 0 ? number_format(($routine_sum / $routine_total), 2) : 0;
                $routine_percentage = $routine_total > 0 ? number_format(getPercentage($routine_average), 2) : 0;

                $report_average = $report_total > 0 ? number_format(($report_sum / $report_total), 2) : 0;
                $report_percentage = $report_total > 0 ? number_format(getPercentage($report_average), 2) : 0;
              ?>
                <tr>
                  <td></td>
                  <td id="td-table"><img src="<?php echo $imageURL; ?>" class="img-table"><?php echo $row['fname'] . ' ' . $row['lname']; ?></td>
                  <td><?php echo $row['sec_name']; ?></td>
                  <td>
                    <center /><span class="badge badge-info"><?php echo $routine_total ?> Total</span>
                  </td>
                  <td><?php echo $routine_average ?> (Routine) <p class="text-danger"><?php echo $report_average ?> (Report)</p>
                  </td>
                  <td><?php echo $routine_percentage ?? '0'; ?> (Routine) <p class="text-danger"><?php echo $report_percentage ?? '0' ?> (Report)</p>
                  </td>
                  <td><button class="btn btn-block btn-primary btn-sm" value="<?php echo $row['id']; ?>" onclick="viewTask(this)"><i class="fas fa-eye fa-fw"></i> View</button></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php } ?>
</div>

<div class="modal fade" id="view" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content border-primary">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">View</h5>
      </div>
      <div class="modal-body" id="ajaxContents">
      </div>
      <div class="modal-footer">
        <button type="button" onclick="updateTask(this)" class="btn btn-success" id="updateButton" style="display: none;">Update</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include('../include/footer.php'); ?>

<script>
  $('#dataTable').DataTable({
    "order": [
      [4, "desc"],
      [3, "desc"]
    ],
    "pageLength": 5,
    "lengthMenu": [5, 10, 25, 50, 100],
    "drawCallback": function(settings) {
      var api = this.api();
      var pageInfo = api.page.info();
      api.column(0, {
        page: 'current'
      }).nodes().each(function(cell, i) {
        var globalIndex = i + 1 + pageInfo.start;
        $(cell).addClass('rank-cell').removeClass('top1 top2 top3');
        if (globalIndex === 1) {
          $(cell).addClass('top1').html('<i class="fas fa-crown"></i> 1');
        } else if (globalIndex === 2) {
          $(cell).addClass('top2').html('<i class="fas fa-medal"></i> 2');
        } else if (globalIndex === 3) {
          $(cell).addClass('top3').html('<i class="fas fa-medal"></i> 3');
        } else {
          $(cell).html(globalIndex);
        }
      });
    }
  });

  function calculate(element) {
    var section   = document.getElementById('section').value;
    var date_to   = document.getElementById('date_to').value;
    var date_from = document.getElementById('date_from').value;
    var sortdata = {
      "calculate": true,
      "section": section,
    };
    if (date_to && date_from !== '') {
      sortdata.date_to    = date_to;
      sortdata.date_from  = date_from;
    }

    $('#dataTable').DataTable().destroy();
    $('#dataTableBody').empty();
    $.ajax({
      method: "POST",
      url: "../config/performance.php",
      data: sortdata,
      success: function(response) {
        $('#dataTableBody').append(response);
        $('#dataTable').DataTable({
          "order": [
            [4, "desc"],
            [3, "desc"]
          ],
          "pageLength": 5,
          "lengthMenu": [5, 10, 25, 50, 100],
          "drawCallback": function(settings) {
            var api = this.api();
            var pageInfo = api.page.info();
            api.column(0, {
              page: 'current'
            }).nodes().each(function(cell, i) {
              var globalIndex = i + 1 + pageInfo.start;
              $(cell).addClass('rank-cell').removeClass('top1 top2 top3');
              if (globalIndex === 1) {
                $(cell).addClass('top1').html('<i class="fas fa-crown"></i> 1');
              } else if (globalIndex === 2) {
                $(cell).addClass('top2').html('<i class="fas fa-medal"></i> 2');
              } else if (globalIndex === 3) {
                $(cell).addClass('top3').html('<i class="fas fa-medal"></i> 3');
              } else {
                $(cell).html(globalIndex);
              }
            });
          }
        });
      }
    })
  }

  function viewTask(element) {
    var account_id  = element.value;
    var date_to     = document.getElementById('date_to').value;
    var date_from   = document.getElementById('date_from').value;
    var data = {
      "viewTask": true,
      "account_id": account_id,
    };
    if (date_to && date_from !== '') {
      data.date_to    = date_to;
      data.date_from  = date_from;
    }
    $.ajax({
      method: "POST",
      url: "../config/performance.php",
      data: data,
      success: function(response) {
        $('#ajaxContents').html(response);
        $('#ViewFinishedTaskTable').DataTable({
          "order": [[4, "asc"],[6, "desc"]]
        });
        $('[data-toggle="tooltip"]').tooltip();
        openSpecificModal('view', 'modal-xl');
      }
    });
  }

  function showPerformance(element) {
    var account_id = <?php echo json_encode($emp_id) ?>;
    $.ajax({
      method: "POST",
      url: "../config/performance.php",
      data: {
        "showPerformance": true,
        "account_id": account_id
      },
      success: function(response) {
        $('#ajaxContents').html(response);
        openSpecificModal('view', 'modal-lg');
      }
    })
  }
</script>