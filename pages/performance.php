<?php

use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

include('../include/header.php');
?>

<div class="container-fluid">
  <?php if ($access == 1) { ?>
  <?php } elseif ($access == 2) {
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
    $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT *, (SELECT COUNT(id) FROM tasks_details WHERE in_charge = '$username' AND task_status = 1 AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE) AND task_class != 6) AS routineTotal, (SELECT COUNT(id) FROM tasks_details WHERE in_charge = '$username' AND task_status = 1 AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE) AND task_class = 6) AS reportTotal, (SELECT SUM(achievement) FROM tasks_details WHERE in_charge = '$username' AND task_status = 1 AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE) AND task_class != 6) AS routineSUM, (SELECT SUM(achievement) FROM tasks_details WHERE in_charge = '$username' AND task_status = 1 AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE) AND task_class = 6) AS reportSUM FROM tasks_details WHERE task_status = 1 AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE) AND in_charge = '$username'"));
    $routine_average    = $row['routineTotal'] > 0 ? number_format(($row['routineSUM'] / $row['routineTotal']), 2) : 0;
    $routine_percentage = $row['routineTotal'] > 0 ? number_format(getPercentage($routine_average), 2) : 0;
    $report_average     = $row['reportTotal'] > 0 ? number_format(($row['reportSUM'] / $row['reportTotal']), 2) : 0;
    $report_percentage  = $row['reportTotal'] > 0 ? number_format(getPercentage($report_average), 2) : 0; ?>
    <div class="row justify-content-center">
      <div class="col-xl-6">
        <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Performance</h6>
            <div class="dropdown no-arrow">
              <button type="button" onclick="showPerformance(this)" class="btn">
                <i class="fas fa-eye fa-fw text-primary"></i> View
              </button>
              <!-- <button type="button" id="print" class="btn">
                <i class="fas fa-print fa-fw text-success"></i> Print
              </button> -->
            </div>
          </div>
          <div class="card-body">
            <div class="text-center mb-4">
              <img class="profile-user-img img-fluid rounded-circle shadow" src="<?php echo $profileURL ?>" style="width: 120px; height: 120px;">
              <h3 class="profile-username mt-3"><?php echo $full_name ?></h3>
              <p class="text-muted"><?php echo $section ?></p>
            </div>

            <!-- Tab Navigation for Routine and Report -->
            <ul class="nav nav-pills justify-content-center mb-4" id="taskTabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="routine-tab" data-toggle="pill" href="#routine" role="tab" aria-controls="routine" aria-selected="true">
                  <i class="fas fa-tasks"></i> Routine
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="report-tab" data-toggle="pill" href="#report" role="tab" aria-controls="report" aria-selected="false">
                  <i class="fas fa-chart-line"></i> Report
                </a>
              </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="taskTabsContent">
              <!-- Routine Tab Content -->
              <div class="tab-pane fade show active" id="routine" role="tabpanel" aria-labelledby="routine-tab">
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <div class="card border-left-primary shadow-sm h-100">
                      <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-primary">Total Routine Tasks</h5>
                        <p class="card-text display-6"><?php echo $row['routineTotal']; ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 mb-3">
                    <div class="card border-left-success shadow-sm h-100">
                      <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-success">Routine Average</h5>
                        <p class="card-text display-6"><?php echo $routine_average; ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 mb-3">
                    <div class="card border-left-info shadow-sm h-100">
                      <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-info">Routine Completion %</h5>
                        <p class="card-text display-6"><?php echo $routine_percentage; ?>%</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Report Tab Content -->
              <div class="tab-pane fade" id="report" role="tabpanel" aria-labelledby="report-tab">
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <div class="card border-left-warning shadow-sm h-100">
                      <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-warning">Total Report Tasks</h5>
                        <p class="card-text display-6"><?php echo $row['reportTotal']; ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 mb-3">
                    <div class="card border-left-danger shadow-sm h-100">
                      <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-danger">Report Average</h5>
                        <p class="card-text display-6"><?php echo $report_average; ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 mb-3">
                    <div class="card border-left-secondary shadow-sm h-100">
                      <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-secondary">Report Completion %</h5>
                        <p class="card-text display-6"><?php echo $report_percentage; ?>%</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
      <div class="col-xl-6">
        <div class="card shadow mb-4">
          <div class="card-body">
            <h5 class="card-title">How the Average is Computed</h5>
            <p class="card-text">
              The average is calculated by dividing the sum of completed tasks by the total number of tasks. This gives a clear measure of how many tasks, on average, have been completed relative to the total tasks.
            </p>
            <b>Example</b>
            <ul>
              <li><strong>Sum of Completed Tasks:</strong> 150</li>
              <li><strong>Total Tasks:</strong> 30</li>
              <li><strong>Average:</strong> 150 / 30 = 5.00</li>
            </ul>
            <p class="card-text">
              This gives the average number of tasks completed per day during the period, which helps measure daily productivity.
            </p>
          </div>
        </div>
      </div>
    </div>
  <?php } elseif ($access == 3) { ?>
    <div class="row" id="print-exclude">
      <div class="form-group col-md-2">
        <label>From</label>
        <input type="date" name="date_from" id="date_from" class="form-control" onchange="calculate(this)">
      </div>
      <div class="form-group col-md-2">
        <label>To</label>
        <input type="date" name="date_to" id="date_to" class="form-control" onchange="calculate(this)" disabled>
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
        <div class="dropdown no-arrow" id="print-exclude">
          <button type="button" id="print-page" class="btn btn-sm text-white"><i class="fas fa-print fa-fw"></i> Print</button>
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
            <tbody id='dataTableBody' id="print-include">
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
                $rows = mysqli_fetch_assoc(mysqli_query($con, "SELECT *, (SELECT COUNT(id) FROM tasks_details WHERE in_charge = '$assignee' AND task_status = 1 AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE) AND task_class != 6) AS routineTotal, (SELECT COUNT(id) FROM tasks_details WHERE in_charge = '$assignee' AND task_status = 1 AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE) AND task_class = 6) AS reportTotal, (SELECT SUM(achievement) FROM tasks_details WHERE in_charge = '$assignee' AND task_status = 1 AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE) AND task_class != 6) AS routineSUM, (SELECT SUM(achievement) FROM tasks_details WHERE in_charge = '$assignee' AND task_status = 1 AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE) AND task_class = 6) AS reportSUM FROM tasks_details WHERE task_status = 1 AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE) AND in_charge = '$assignee'"));

                $task_total         = $rows['routineTotal'] + $rows['reportTotal'];
                $routine_average    = $rows['routineTotal'] > 0 ? number_format(($rows['routineSUM'] / $rows['routineTotal']), 2) : 0;
                $routine_percentage = $rows['routineTotal'] > 0 ? number_format(getPercentage($routine_average), 2) : 0;
                $report_average     = $rows['reportTotal'] > 0 ? number_format(($rows['reportSUM'] / $rows['reportTotal']), 2) : 0;
                $report_percentage  = $rows['reportTotal'] > 0 ? number_format(getPercentage($report_average), 2) : 0;
              ?>
                <tr>
                  <td></td>
                  <td id="td-table"><img src="<?php echo $imageURL; ?>" class="img-table"><?php echo $row['fname'] . ' ' . $row['lname']; ?></td>
                  <td><?php echo $row['sec_name']; ?></td>
                  <td id="print-exclude">
                    <center /><span class="badge badge-info"><?php echo $task_total ?> Total</span>
                  </td>
                  <td><?php echo $routine_average ?> (Routine) <p class="text-danger"><?php echo $report_average ?> (Report)</p>
                  </td>
                  <td><?php echo $routine_percentage ?? '0'; ?> (Routine) <p class="text-danger"><?php echo $report_percentage ?? '0' ?> (Report)</p>
                  </td>
                  <td id="print-exclude"><button class="btn btn-block btn-primary btn-sm" value="<?php echo $row['id']; ?>" onclick="viewTask(this)"><i class="fas fa-eye fa-fw"></i> View</button></td>
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
    var section = document.getElementById('section').value;
    var date_to = document.getElementById('date_to').value;
    var date_from = document.getElementById('date_from').value;
    var sortdata = {
      "calculate": true,
      "section": section,
    };
    if (date_from) {
      document.getElementById('date_to').setAttribute('min', date_from);
      document.getElementById('date_to').disabled = false;
    } else {
      document.getElementById('date_to').removeAttribute('min');
      document.getElementById('date_to').disabled = true;
    }
    if (date_to && date_from !== '') {
      sortdata.date_to = date_to;
      sortdata.date_from = date_from;
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
    var account_id = element.value;
    var date_to = document.getElementById('date_to').value;
    var date_from = document.getElementById('date_from').value;
    var data = {
      "viewTask": true,
      "account_id": account_id,
    };
    if (date_to && date_from !== '') {
      data.date_to = date_to;
      data.date_from = date_from;
    }
    $.ajax({
      method: "POST",
      url: "../config/performance.php",
      data: data,
      success: function(response) {
        $('#ajaxContents').html(response);
        $('#ViewFinishedTaskTable').DataTable({
          "order": [
            [4, "asc"],
            [6, "desc"]
          ]
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
        $('#ViewFinishedTaskTable').DataTable({
          "order": [
            [6, "desc"],
            [4, "asc"]
          ]
        });
        $('[data-toggle="tooltip"]').tooltip();
        openSpecificModal('view', 'modal-xl');
      }
    })
  }

  <?php if ($access == 3) { ?>
    const printButton = document.getElementById('print-page');

    const printPage = () => {
      window.print();

      setTimeout(() => {
        location.reload();
      }, 1000); // Adjust the delay as needed (1000ms = 1 second)
    };

    printButton.addEventListener('click', () => {
      const dataTable = $('#dataTable').DataTable();

      const originalSettings = dataTable.settings()[0];

      $('.dataTables_length, .dataTables_filter, tfoot, .dataTables_info, .dataTables_paginate').hide();

      dataTable.page.len(-1).draw();
      dataTable.search('').draw();
      dataTable.order([]).draw();

      setTimeout(() => {
        printPage();

        setTimeout(() => {
          $('.dataTables_length, .dataTables_filter, tfoot, .dataTables_info, .dataTables_paginate').show();
          dataTable.page.len(originalSettings._iDisplayLength).draw();
        }, 500);
      }, 100);
    });
  <?php } ?>
</script>