<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <?php if ($access == 1) { ?>
  <?php } elseif ($access == 2) { ?>
    <div class="row">
      <div class="form-group col-md-2">
        <label>To</label>
        <input type="date" name="date_to" id="date_to" class="form-control" onchange="filterTable()">
      </div>
      <div class="form-group col-md-2">
        <label>From</label>
        <input type="date" name="date_from" id="date_from" class="form-control" onchange="filterTable()">
      </div>
      <div class="form-group col-md-2">
        <label>Section</label>
        <select id="section" name="section[]" class="form-control selectpicker" data-style="bg-primary text-white text-capitalize" data-size="5" data-live-search="true" onchange="filterTable()">
          <option value='' data-subtext='Default' selected>All</option>
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
      <div class="form-group col-md-2">
        <label>Progress</label>
        <select id="progress" name="progress" class="form-control selectpicker" data-style="bg-primary text-white text-capitalize" data-size="5" onchange="filterTable()">
          <option value="" data-subtext="Default" selected>All</option>
          <option value="NOT YET STARTED">Not Yet Started</option>
          <option value="IN PROGRESS">In-Progress</option>
          <option value="REVIEW">Review</option>
          <option value="FINISHED">Finished</option>
        </select>
      </div>
    </div>
    <div class="card">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
        <h6 class="m-0 font-weight-bold text-white">Project Tasks</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
            <thead class='table table-success'>
              <tr>
                <th class="col col-md-1">Action</th>
                <th>Project</th>
                <th>Task</th>
                <th class="col col-md-2">Project Started</th>
                <th class="col col-md-2">Project Due Date</th>
                <th>Project Status</th>
                <th>Task Status</th>
              </tr>
            </thead>
            <tfoot class='table table-success'>
              <tr>
                <th class="col col-md-1">Action</th>
                <th>Project</th>
                <th>Task</th>
                <th class="col col-md-2">Project Started</th>
                <th class="col col-md-2">Project Due Date</th>
                <th>Project Status</th>
                <th>Task Status</th>
              </tr>
            </tfoot>
            <tbody id='dataTableBody'>
              <?php $query_result = mysqli_query($con, "SELECT project_task.*, project_list.title, project_list.leader, project_list.member, project_list.start, project_list.end, project_list.status AS pstatus FROM project_task JOIN project_list ON project_list.id=project_task.project_id WHERE $emp_id IN (project_list.member)");
              if (mysqli_num_rows($query_result) > 0) {
                while ($row = $query_result->fetch_assoc()) {
                  $dateStart = date_format(date_create($row['start']), "F d, Y");
                  $dateEnd   = date_format(date_create($row['end']), "F d, Y");
              ?>
                  <tr>
                    <td>
                      <div class="btn-group dropright">
                        <button type="button" class="btn btn-block btn-sm btn-outline-primary dropdown-toggle" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i> Action</button>
                        <div class="dropdown-menu">
                          <button type="button" class="dropdown-item" onclick="actionView(this)" value="<?php echo $row['project_id'] ?>"><i class="fas fa-eye fa-fw"></i> View</button>
                          <div class="dropdown-divider"></div>
                          <button type="button" class="dropdown-item" onclick="addActivity(this)" value="<?php echo $row['project_id'] ?>"><i class="fas fa-reply fa-fw"></i> Add Activity</button>
                        </div>
                      </div>
                    </td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['task']; ?></td>
                    <td><?php echo $dateStart; ?></td>
                    <td><?php echo $dateEnd; ?></td>
                    <td><span class="badge"><?php echo $row['pstatus']; ?></span></td>
                    <td><span class="badge"><?php echo $row['status']; ?></span></td>
                  </tr>
              <?php }
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php } elseif ($access == 3) { ?>
    <div class="row">
      <div class="form-group col-md-2">
        <label>To</label>
        <input type="date" name="date_to" id="date_to" class="form-control" onchange="filterTable()">
      </div>
      <div class="form-group col-md-2">
        <label>From</label>
        <input type="date" name="date_from" id="date_from" class="form-control" onchange="filterTable()">
      </div>
      <div class="form-group col-md-2">
        <label>Section</label>
        <select id="section" name="section[]" class="form-control selectpicker" data-style="bg-primary text-white text-capitalize" data-size="5" data-live-search="true" onchange="filterTable()">
          <option value='' data-subtext='Default' selected>All</option>
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
      <div class="form-group col-md-2">
        <label>Progress</label>
        <select id="progress" name="progress" class="form-control selectpicker" data-style="bg-primary text-white text-capitalize" data-size="5" onchange="filterTable()">
          <option value="" data-subtext="Default" selected>All</option>
          <option value="NOT YET STARTED">Not Yet Started</option>
          <option value="IN PROGRESS">In-Progress</option>
          <option value="REVIEW">Review</option>
          <option value="FINISHED">Finished</option>
        </select>
      </div>
    </div>
    <div class="card">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
        <h6 class="m-0 font-weight-bold text-white">Project List</h6>
        <button type="button" onclick="showCreate(this)" class="btn btn-primary btn-sm">
          <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> Create New Project
        </button>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
            <thead class='table table-success'>
              <tr>
                <th class="col col-md-1">Action</th>
                <th>Title</th>
                <th>Date Started</th>
                <th class="col col-md-1">Due Date</th>
                <th>Progress</th>
                <th>Project Leader</th>
              </tr>
            </thead>
            <tfoot class='table table-success'>
              <tr>
                <th class="col col-md-1">Action</th>
                <th>Title</th>
                <th>Date Started</th>
                <th class="col col-md-1">Due Date</th>
                <th>Progress</th>
                <th>Project Leader</th>
              </tr>
            </tfoot>
            <tbody id='dataTableBody'>
              <?php $con->next_result();
              $result = mysqli_query($con, "SELECT project_list.*, accounts.file_name, accounts.username FROM project_list JOIN department ON department.dept_id=project_list.dept_id JOIN accounts ON accounts.id=project_list.leader WHERE project_list.dept_id='$dept_id'");
              if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                  if (empty($row['file_name'])) {
                    $imageURL = '../assets/img/user-profiles/nologo.png';
                  } else {
                    $imageURL = '../assets/img/user-profiles/' . $row['file_name'];
                  }
              ?>
                  <tr>
                    <td>
                      <div class="btn-group dropright">
                        <button type="button" class="btn btn-block btn-sm btn-outline-primary dropdown-toggle" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i> Action</button>
                        <div class="dropdown-menu">
                          <button type="button" class="dropdown-item" onclick="actionView(this)" value="<?php echo $row['id'] ?>"><i class="fas fa-eye fa-fw"></i> View</button>
                          <div class="dropdown-divider"></div>
                          <button type="button" class="dropdown-item" onclick="actionEdit(this)" value="<?php echo $row['id'] ?>"><i class="fas fa-pencil-alt fa-fw"></i> Edit</button>
                          <div class="dropdown-divider"></div>
                          <button type="button" class="dropdown-item" onclick="actionDelete(this)" value="<?php echo $row['id'] ?>"><i class="fas fa-trash fa-fw"></i> Delete</button>
                        </div>
                      </div>
                    </td>
                    <td><?php echo $row['title'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['details'] ?>"></i></td>
                    <td><?php echo $row['start'] ?></td>
                    <td><?php echo $row['end'] ?></td>
                    <td><span class="badge badge-info"><?php echo $row['status'] ?></span></td>
                    <td data-toggle="tooltip" data-placement="top" title="<?php echo $row['username'] ?>">
                      <center /><img src='<?php echo $imageURL ?>' class="border border-primary img-table-solo">
                    </td>
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
 
<div class="modal fade" id="view" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content border-primary">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">View Project</h5>
      </div>
      <div class="modal-body" id="projectDetails">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="create" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content border-primary">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Create New Task</h5>
      </div>
      <form id="taskAddDetails" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Task:</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-font"></i></div>
                  </div>
                  <input type="text" class="form-control" name="task_name" id="task_name">
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label>Description:</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-info"></i></div>
                  </div>
                  <textarea name="task_details" id="task_details" class="form-control"></textarea>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label>Status:</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-flag"></i></div>
                  </div>
                  <select name="task_status" id="task_status" class="form-control">
                    <option value="PENDING">Pending</option>
                    <option value="IN PROGRESS">In Progress</option>
                    <option value="Completed">Completed</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="saveTaskButton">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="submit" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content border-primary">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-plus fa-fw"></i> Project Activity for: <span id="taskname"></span></h5>
      </div>
      <form id="projectActivity" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label>Subject:</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-font"></i></div>
                  </div>
                  <input type="text" class="form-control" name="subject" id="subject">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Date:</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-calendar-day"></i></div>
                  </div>
                  <input type="date" class="form-control" name="date" id="date">
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Start Time:</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-hourglass-start"></i></div>
                  </div>
                  <input type="time" class="form-control" name="start" id="start">
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>End Time:</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-hourglass-end"></i></div>
                  </div>
                  <input type="time" class="form-control" name="end" id="end">
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label>Comment or Progress Description:</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-info"></i></div>
                  </div>
                  <textarea name="task_details" id="task_details" class="form-control"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="saveTaskButton">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="error" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Error</h5>
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

<?php include('../include/footer.php'); ?>

<script>
  $('#dataTable').DataTable({
    "order": [
      [4, "desc"],
      [0, "asc"]
    ],
    "pageLength": 5,
    "lengthMenu": [5, 10, 25, 50, 100],
    "drawCallback": function(settings) {
      $('[data-toggle="tooltip"]').tooltip();
    }
  });

  function actionView(element) {
    var prj_id = element.value;
    $.ajax({
      method: "POST",
      url: "../config/project.php",
      data: {
        "actionView": true,
        "prj_id": prj_id,
      },
      success: function(response) {
        $('#projectDetails').html(response);
        $('#view').modal('show');
        $('#taskList').DataTable({
          "order": [
            [0, "asc"]
          ],
        })
      }
    });
  }

  function createTask(element) {
    $('#create').modal('show');

    $('#saveTaskButton').on('click', function() {
      document.getElementById('saveTaskButton').disabled = true;
      if (document.getElementById('task_name').value === '' || document.getElementById('task_details').value === '') {
        document.getElementById('saveTaskButton').disabled = false;
        document.getElementById('error_found').innerHTML = 'Empty field has been detected!';
        $('#error').modal('show');
      } else {
        var project_id = document.getElementById('project_id').value;
        var formDetails = new FormData(document.getElementById('taskAddDetails'));
        formDetails.append('project_id', project_id);
        formDetails.append('createTask', true);
        console.log(formDetails);
        $.ajax({
          method: "POST",
          url: "../config/project.php",
          data: formDetails,
          contentType: false,
          processData: false,
          success: function(response) {
            if (response === 'Success') {
              $('#create').modal('hide');
              document.getElementById('saveTaskButton').disabled = false;
            } else {
              document.getElementById('error_found').innerHTML = response;
              $('#error').modal('show');
              document.getElementById('saveTaskButton').disabled = false;
            }
          }
        });
      }
    });
  }

  function addActivity(element) {
    var id = element.value;
    console.log(id);
    $('#submit').modal('show');
  }
</script>