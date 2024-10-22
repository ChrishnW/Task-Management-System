<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <?php if ($access == 1) : ?>
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
        <label>Department</label>
        <select id="department" name="department" class="form-control selectpicker" data-style="bg-primary text-white text-capitalize" data-size="5" data-live-search="true" onchange="selectSection(this); filterTable(this);">
          <option value="" data-subtext="Default" selected>All</option>
          <?php
          $con->next_result();
          $sql = mysqli_query($con, "SELECT * FROM department WHERE status='1' ORDER BY dept_name");
          if (mysqli_num_rows($sql) > 0) {
            while ($row = mysqli_fetch_assoc($sql)) { ?>
              <option value='<?php echo $row['dept_id'] ?>' data-subtext='Department ID <?php echo $row['dept_id'] ?>' class="text-capitalize"><?php echo strtolower($row['dept_name']) ?></option>
          <?php }
          } ?>
        </select>
      </div>
      <div class="form-group col-md-2">
        <label>Section</label>
        <select id="section" name="section[]" class="form-control selectpicker" data-style="bg-primary text-white text-capitalize" data-size="5" onchange="filterTable(this)">
          <option value="" data-subtext="Default" selected>All</option>
        </select>
      </div>
      <div class="form-group col-md-2">
        <label>Progress</label>
        <select id="progress" name="progress" class="form-control selectpicker" data-style="bg-primary text-white text-capitalize" data-size="5" onchange="filterTable(this)">
          <option value="" data-subtext="Default" selected>All</option>
          <option value="NOT YET STARTED">Not Yet Started</option>
          <option value="IN PROGRESS">In-Progress</option>
          <option value="REVIEW">Review</option>
          <option value="FINISHED">Finished</option>
          <option value="RESCHEDULE">Reschedule</option>
        </select>
      </div>
      <div class="form-group col-sm-auto">
        <label>Status</label>
        <select id="taskStatus" name="taskStatus" class="form-control selectpicker" data-style="bg-primary text-white text-capitalize" onchange="filterTable(this)">
          <option value="1" data-subtext="Default" selected>Active</option>
          <option value="0">In-Active</option>
        </select>
      </div>
    </div>
    <div class="card">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
        <h6 class="m-0 font-weight-bold text-white">Deployed Tasks</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
            <thead class='table table-success'>
              <tr>
                <th>Action</th>
                <th>Code</th>
                <th>Title</th>
                <th>Classification</th>
                <th>Due Date</th>
                <th>Assignee</th>
                <th>Progress</th>
              </tr>
            </thead>
            <tbody id='dataTableBody'>
              <?php $con->next_result();
              $result = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.task_status=1");
              if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                  $due_date = date_format(date_create($row['due_date']), "Y-m-d h:i a"); ?>
                  <tr>
                    <td><button type="button" class="btn btn-info btn-block" onclick="editTask(this)" value="<?php echo $row['id'] ?>"><i class="fas fa-pen fa-fw"></i> Edit</button> <button type="button" onclick="viewTask(this)" class="btn btn-primary btn-block" value="<?php echo $row['id'] ?>" data-name="<?php echo $row['task_name'] ?>"><i class="fas fa-eye fa-fw"></i> View</button></td>
                    <td>
                      <center /><?php echo $row['task_code'] ?>
                    </td>
                    <td><?php echo $row['task_name'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
                    <td><?php echo getTaskClass($row['task_class']); ?></td>
                    <td><?php echo $due_date ?></td>
                    <td><?php echo getUser($row['in_charge']); ?></td>
                    <td><?php echo getProgressBadge($row['status']); ?></td>
                  </tr>
              <?php }
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php elseif ($access == 2) : ?>
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="form-group col-md-2">
            <label>From</label>
            <input type="date" name="date_from" id="date_from" class="form-control" onchange="checkDateInputs(this)">
          </div>
          <div class="form-group col-md-2">
            <label>To</label>
            <input type="date" name="date_to" id="date_to" class="form-control" onchange="checkDateInputs(this)" disabled>
          </div>
        </div>
        <?php
        $query_result = mysqli_query($con, "SELECT COUNT(*) AS deployed, SUM(CASE WHEN td.status NOT IN ('REVIEW', 'FINISHED') THEN 1 ELSE 0 END) AS todo, SUM(CASE WHEN td.status = 'REVIEW' THEN 1 ELSE 0 END) AS review, SUM(CASE WHEN td.status = 'FINISHED' THEN 1 ELSE 0 END) AS finished FROM tasks t  JOIN tasks_details td ON t.id = td.task_id WHERE td.task_status = 1  AND t.in_charge = '$username'");
        $row = mysqli_fetch_assoc($query_result);
        ?>
        <ul class="nav nav-pills justify-content-center mb-3" id="myTabs">
          <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#todo" data-status="NOT YET STARTED"><i class="fas fa-list-ul fa-fw"></i> To Do <span class="badge badge-success"><?php echo $row['todo'] ?></span></a>
          </li>
          <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#review" data-status="REVIEW"><i class="fas fa-tasks fa-fw"></i> In Review <span class="badge badge-warning"><?php echo $row['review'] ?></span></a>
          </li>
          <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#finished" data-status="FINISHED"><i class="fas fa-star fa-fw"></i> Completed <span class="badge badge-primary"><?php echo $row['finished'] ?></span></a>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade" id="todo">
            <div class="card">
              <div class="card-header d-none" id="actionButton">
                <button id="multiStart" class="btn btn-success pull-right"><i class="fas fa-play"></i> Start All</button>
              </div>
              <div class="card-body table-responsive">
                <table id="myTasksTableTodo" class="table table-hover">
                  <thead>
                    <tr>
                      <th class="col-1 text-center">
                        <input type='checkbox' id='selectAll' class='tasksCheckboxes form-control'>
                      </th>
                      <th class="col-1">Code</th>
                      <th class="col-5">Task</th>
                      <th class="col-1">Classification</th>
                      <th class="col-2">Due Date</th>
                      <th class="col-1">Status</th>
                      <th class="col-1"><button class="btn btn-success btn-block d-none" id="startSelect"></button></th>
                    </tr>
                  </thead>
                  <tbody id="myTasksTodo">
                    <?php
                    $query_result = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.task_status=1 AND t.in_charge='$username' AND td.status NOT IN ('REVIEW', 'FINISHED')");
                    while ($row = $query_result->fetch_assoc()) {
                      $current_date = date('Y-m-d');
                      $checkbox = '<input type="checkbox" name="selected_ids[]" class="form-control" value="' . (date_create(date('Y-m-d', strtotime($row['due_date']))) > date_create($current_date) ? '' : $row['id']) . '" ' . (date_create(date('Y-m-d', strtotime($row['due_date']))) > date_create($current_date) ? 'disabled' : '') . '>';
                      $due_date = date_format(date_create($row['due_date']), "F d"); ?>
                      <tr>
                        <td>
                          <?php if ($row['status'] === 'NOT YET STARTED') {
                            echo '<input type="checkbox" name="selected_ids[]" class="form-control bodyCheckbox" value="' . (date_create(date('Y-m-d', strtotime($row['due_date']))) > date_create($current_date) ? '' : $row['id']) . '" ' . (date_create(date('Y-m-d', strtotime($row['due_date']))) > date_create($current_date) ? 'disabled' : '') . '>';
                          } else {
                            echo '<input type="checkbox" name="selected_ids[]" class="form-control bodyCheckbox" disabled>';
                          } ?>
                        </td>
                        <td><?php echo $row['task_code'] ?></td>
                        <td>
                          <?php echo $row['task_name'] ?>
                          <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i>
                          <?php if ($row['requirement_status'] === '1') : ?>
                            <i class="fas fa-photo-video text-warning" data-toggle="tooltip" data-placement="right" title="File Attachment Required"></i>
                          <?php endif; ?>
                        </td>
                        <td><?php echo getTaskClass($row['task_class']); ?></td>
                        <td><?php echo $due_date ?></td>
                        <td><?php echo getProgressBadge($row['status']); ?></td>
                        <td>
                          <?php if ($row['status'] === 'NOT YET STARTED') {
                            if (date_create(date('Y-m-d', strtotime($row['due_date']))) > date_create($current_date)) {
                              echo '<button class="btn btn-secondary btn-block" disabled>On Hold</button>';
                            } else {
                              echo '<button class="singleStart btn btn-success btn-block" value="' . $row['id'] . '" onclick="startTask(this)">Start</button>';
                            }
                          } elseif ($row['status'] === 'IN PROGRESS') {
                            echo '<button class="btn btn-danger btn-block" value="' . $row['id'] . '" onclick="endTask(this)">Finish</button>';
                          } else {
                            echo '<button class="btn btn-dark btn-block" disabled>On Hold</button>';
                          }

                          if ($row['old_date'] === NULL) {
                            echo '<button class="btn btn-secondary btn-block" value="' . $row['id'] . '" onclick="rescheduleTask(this)">Reschedule</button>';
                          } ?>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="review">
            <div class="card">
              <div class="card-body table-responsive">
                <table id="myTasksTableReview" class="table table-hover">
                  <thead>
                    <tr>
                      <th class="col-1">Code</th>
                      <th class="col-5">Title</th>
                      <th class="col-1">Classification</th>
                      <th class="col-2">Due Date</th>
                      <th class="col-2">Finished Date</th>
                      <th class="col-1"></th>
                    </tr>
                  </thead>
                  <tbody id="myTasksReview">
                    <?php
                    $query_result = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.task_status=1 AND t.in_charge='$username' AND td.status='REVIEW'");
                    while ($row = $query_result->fetch_assoc()) {
                      $due_date = date_format(date_create($row['due_date']), "F d");
                      $date_accomplished = date_format(date_create($row['date_accomplished']), "F d"); ?>
                      <tr>
                        <td><?php echo $row['task_code'] ?></td>
                        <td>
                          <?php echo $row['task_name'] ?>
                          <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i>
                          <?php if ($row['requirement_status'] === '1') : ?>
                            <i class="fas fa-photo-video text-warning" data-toggle="tooltip" data-placement="right" title="File Attachment Required"></i>
                          <?php endif; ?>
                        </td>
                        <td><?php echo getTaskClass($row['task_class']); ?></td>
                        <td><?php echo $due_date ?></td>
                        <td><?php echo $date_accomplished ?></td>
                        <td><button type="button" class="btn btn-block btn-warning" value='<?php echo $row['id']; ?>' onclick="reviewTask(this)">View</button></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="finished">
            <div class="card">
              <div class="card-body table-responsive">
                <table id="myTasksTableFinished" class="table table-hover">
                  <thead>
                    <tr>
                      <th class="col-1">Code</th>
                      <th class="col-5">Title</th>
                      <th class="col-1">Classification</th>
                      <th class="col-2">Due Date</th>
                      <th class="col-1">Rating</th>
                      <th class="col-1"></th>
                    </tr>
                  </thead>
                  <tbody id="myTasksFinished">
                    <?php
                    $query_result = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.task_status=1 AND t.in_charge='$username' AND td.status='FINISHED'");
                    while ($row = $query_result->fetch_assoc()) {
                      $due_date = date_format(date_create($row['due_date']), "F d, Y"); ?>
                      <tr>
                        <td><?php echo $row['task_code'] ?></td>
                        <td>
                          <?php echo $row['task_name'] ?>
                          <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i>
                          <?php if ($row['requirement_status'] === '1') : ?>
                            <i class="fas fa-photo-video text-warning" data-toggle="tooltip" data-placement="right" title="File Attachment Required"></i>
                          <?php endif; ?>
                        </td>
                        <td><?php echo getTaskClass($row['task_class']); ?></td>
                        <td><?php echo $due_date ?></td>
                        <td class="text-center">
                          <span class="h5 text-success font-weight-bold"><?php echo $row['achievement'] ?></span>
                        </td>
                        <td><button type="button" class="btn btn-block btn-primary" value='<?php echo $row['id']; ?>' onclick="checkTask(this)">Details</button></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php elseif ($access == 3) : ?>
    <div class="row">
      <div class="form-group col-md-2">
        <label>From</label>
        <input type="date" name="date_from" id="date_from" class="form-control" onchange="filterTable()">
      </div>
      <div class="form-group col-md-2">
        <label>To</label>
        <input type="date" name="date_to" id="date_to" class="form-control" onchange="filterTable()">
      </div>
      <div class="form-group col-md-2">
        <label>Section</label>
        <select id="section" name="section[]" class="form-control selectpicker show-tick" data-style="bg-primary text-white text-capitalize" data-size="5" data-live-search="true" onchange="filterTable()">
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
        <select id="progress" name="progress" class="form-control selectpicker show-tick" data-style="bg-primary text-white text-capitalize" data-size="5" onchange="filterTable()">
          <option value="" data-subtext="Default" selected>All</option>
          <option value="NOT YET STARTED">Not Yet Started</option>
          <option value="IN PROGRESS">In-Progress</option>
          <option value="REVIEW">Review</option>
          <option value="FINISHED">Finished</option>
          <option value="RESCHEDULE">Reschedule</option>
        </select>
      </div>
      <div class="form-group col-md-2">
        <label>Classification</label>
        <select id="taskClass" name="taskClass" class="form-control selectpicker show-tick" data-style="bg-primary text-white text-capitalize" data-size="5" onchange="filterTable()">
          <option value="" data-subtext="Default" selected>All</option>
          <?php
          $con->next_result();
          $sql = mysqli_query($con, "SELECT * FROM task_class WHERE id!=5");
          if (mysqli_num_rows($sql) > 0) {
            while ($row = mysqli_fetch_assoc($sql)) { ?>
              <option value='<?php echo $row['id'] ?>' class="text-capitalize"><?php echo strtolower($row['task_class']) ?></option>
          <?php }
          } ?>
        </select>
      </div>
    </div>
    <div class="card">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
        <h6 class="m-0 font-weight-bold text-white">Deployed Tasks</h6>
        <div class="dropdown no-arrow">
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#taskRegistrationModal">
            <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> Additional Task
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
            <thead class='table table-success'>
              <tr>
                <th class="col col-md-1">Action</th>
                <th>Code</th>
                <th>Title</th>
                <th>Classification</th>
                <th class="col col-md-1">Due Date</th>
                <th>Assignee</th>
                <th>Progress</th>
              </tr>
            </thead>
            <tbody id='dataTableBody'>
              <?php
              $result = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN section s ON tl.task_for=s.sec_id JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.task_status=1 AND td.status='REVIEW' AND s.dept_id = '$dept_id'");
              if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                  $due_date   = date_format(date_create($row['due_date']), "Y-m-d h:i a"); ?>
                  <tr>
                    <td><button type="button" onclick="viewTask(this)" class="btn btn-primary btn-block" value="<?php echo $row['id'] ?>" data-name="<?php echo $row['task_name'] ?>"><i class="fas fa-eye fa-fw"></i> View</button></td>
                    <td>
                      <center /><?php echo $row['task_code'] ?>
                    </td>
                    <td><?php echo $row['task_name'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
                    <td><?php echo getTaskClass($row['task_class']); ?></td>
                    <td><?php echo $due_date ?></td>
                    <td><?php echo getUser($row['in_charge']); ?></td>
                    <td><?php echo getProgressBadge($row['status']); ?></td>
                  </tr>
              <?php }
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<div class="modal fade" id="start" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content border-success">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Start Task</h5>
      </div>
      <div class="modal-body text-center">
        <input type="hidden" id="taskID">
        <i class="fas fa-question fa-5x text-success"></i>
        <br><br>
        <span id="startNote"></span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal" id="confirmButton">Confirm</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="resched" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content border-secondary">
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Reschedule Task</h5>
      </div>
      <div class="modal-body">
        <input type="hidden" id="reschedID">
        <label for="">Request Due Date:</label>
        <div class="input-group mb-2">
          <div class="input-group-prepend">
            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
          </div>
          <input type="date" id="resched_date" name="resched_date" class="form-control" min=<?php echo $minDay; ?>>
        </div>
        <label for="">Reason:</label>
        <div class="input-group mb-2">
          <div class="input-group-prepend">
            <div class="input-group-text"><i class="fas fa-comment"></i></div>
          </div>
          <textarea name="resched_reason" id="resched_reason" class="form-control" placeholder="Please write your reason for rescheduling here."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal" id="requestButton">Request</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="finish" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content border-danger">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-pen-square fa-fw"></i> Finish Task</h5>
      </div>
      <div class="modal-body" id="finishDetails">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal" id="submitTask">Submit</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<div class="modal fade" id="view" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content border-primary">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">View Task</h5>
      </div>
      <div class="modal-body" id="taskDetails">
      </div>
      <div class="modal-footer">
        <button type="button" onclick="updateTask(this)" class="btn btn-success" id="updateButton" style="display: none;">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="re-view" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content border-warning">
      <div class="modal-header bg-warning text-white">
        <h5 class="modal-title">Review Task</h5>
      </div>
      <div class="modal-body" id="reviewDetails">
      </div>
      <div class="modal-footer">
        <button type="button" onclick="updateDetails(this)" class="btn btn-success" id="updateButtonEmp">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="result" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Result</h5>
      </div>
      <div class="modal-body text-center" id="resultBody">
      </div>
      <div class="modal-footer">
        <button type="button" onclick="location.reload();" class="btn btn-secondary" data-dismiss="modal">Close</button>
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

<script src="../assets/js/drag-drop.js"></script>
<script src="../assets/js/member-datatable-settings.js"></script>

<script>
  function startSelectButton() {
    var selectCount = ToDoTable.$('.bodyCheckbox:checked').length;
    if (selectCount > 0) {
      $('#startSelect').removeClass('d-none').text('Start (' + selectCount + ')');
      ToDoTable.$('.singleStart').prop('disabled', true);
    } else {
      $('#startSelect').addClass('d-none');
      ToDoTable.$('.singleStart').prop('disabled', false);
    }
  }

  $(document).ready(function() {
    $('#selectAll').on('click', function() {
      var isChecked = this.checked;
      ToDoTable.rows().every(function() {
        var row = this.node();
        $(row).find('.bodyCheckbox').each(function() {
          if (!this.disabled) {
            this.checked = isChecked;
          }
        });
      });
      startSelectButton();
    });

    $('#myTasksTableTodo tbody').on('change', '.bodyCheckbox', function() {
      if (!this.checked) {
        $('#selectAll').prop('checked', false);
      } else {
        var allChecked = true;
        $('.bodyCheckbox').each(function() {
          if (!this.checked && !this.disabled) {
            allChecked = false;
          }
        });
        $('#selectAll').prop('checked', allChecked);
      }
      startSelectButton();
    });

    // Ensure all checkboxes are checked/unchecked across all pages
    $('#selectAll').on('click', function() {
      var isChecked = this.checked;
      ToDoTable.rows().every(function() {
        var row = this.node();
        $(row).find('.bodyCheckbox').each(function() {
          if (!this.disabled) {
            this.checked = isChecked;
          }
        });
      });
    });

    $('#startSelect').on('click', function() {
      var checkedValues = [];
      ToDoTable.$('.bodyCheckbox:checked').each(function() {
        checkedValues.push($(this).val());
      });
      document.getElementById("startNote").innerHTML = 'You’re about to start ' + checkedValues.length + ' tasks.<br>Do you wish to continue?';
      $('#start').modal('show');
      $('#confirmButton').off('click').on('click', function() {
        $.ajax({
          url: '../config/tasks.php',
          method: 'POST',
          data: {
            "startTaskMultiple": true,
            "checkedIds": checkedValues
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
    });
  });

  function filterTable() {
    <?php if ($access == 1) { ?>
      var date_to = document.getElementById('date_to').value;
      var date_from = document.getElementById('date_from').value;
      var department = document.getElementById('department').value;
      var section = document.getElementById('section').value;
      var progress = document.getElementById('progress').value;
      var status = document.getElementById('taskStatus').value;
      $('#dataTable').DataTable().destroy();
      $('#dataTableBody').empty();
      $.ajax({
        method: "POST",
        url: "../config/tasks.php",
        data: {
          "filterTable": true,
          "date_to": date_to,
          "date_from": date_from,
          "department": department,
          "section": section,
          "progress": progress,
          "status": status
        },
        success: function(response) {
          $('#dataTableBody').append(response);
          $('#dataTable').DataTable({
            "order": [
              [6, "desc"],
              [4, "desc"],
              [2, "asc"]
            ],
            "drawCallback": function(settings) {
              $('[data-toggle="tooltip"]').tooltip();
            }
          });
        }
      });
    <?php } else { ?>
      var date_to = document.getElementById('date_to').value;
      var date_from = document.getElementById('date_from').value;
      var section = document.getElementById('section').value;
      var progress = document.getElementById('progress').value;
      var taskClass = document.getElementById('taskClass').value;
      $('#dataTable').DataTable().destroy();
      $('#dataTableBody').empty();
      $.ajax({
        method: "POST",
        url: "../config/tasks.php",
        data: {
          "filterTable": true,
          "date_to": date_to,
          "date_from": date_from,
          "section": section,
          "progress": progress,
          "class": taskClass
        },
        success: function(response) {
          $('#dataTableBody').append(response);
          $('#dataTable').DataTable({
            "order": [
              [6, "desc"],
              [4, "desc"],
              [2, "asc"]
            ],
            "drawCallback": function(settings) {
              $('[data-toggle="tooltip"]').tooltip();
            }
          });
        }
      });
    <?php } ?>
  }

  function selectSection(element) {
    var departmentSelect = element.value;
    $.ajax({
      method: "POST",
      url: "../config/tasks.php",
      data: {
        "sectionSelect": true,
        "departmentSelect": departmentSelect,
      },
      success: function(response) {
        $("select[name='section[]']").html(response).selectpicker('refresh');
      }
    })
  }

  function viewTask(element) {
    var taskID = element.value;
    $.ajax({
      method: "POST",
      url: "../config/tasks.php",
      data: {
        "viewTask": true,
        "taskID": taskID,
      },
      success: function(response) {
        document.getElementById('updateButton').style.display = 'none';
        $('#taskDetails').html(response);
        openSpecificModal('view', 'modal-xl');
      }
    });
  }

  function editTask(element) {
    var editaskID = element.value;
    $.ajax({
      method: "POST",
      url: "../config/tasks.php",
      data: {
        "editTask": true,
        "taskID": editaskID,
      },
      success: function(response) {
        document.getElementById('updateButton').value = editaskID;
        document.getElementById('updateButton').style.display = 'block';
        $('#taskDetails').html(response);
        openSpecificModal('view', 'modal-md');
      }
    });
  }

  function updateTask(element) {
    element.disabled = true;
    var taskID = document.getElementById('taskDetailsID').value;
    var progress = document.getElementById('update_progress').value;
    var datetime = document.getElementById('update_datetime').value;
    var status = document.getElementById('update_status').value;
    $.ajax({
      method: "POST",
      url: "../config/tasks.php",
      data: {
        "updateTask": true,
        "taskID": taskID,
        "progress": progress,
        "datetime": datetime,
        "status": status,
      },
      success: function(response) {
        console.log(response);
        if (response === 'Success') {
          document.getElementById('success_log').innerHTML = 'Operation completed successfully.';
          $('#view').modal('hide');
          $('#success').modal('show');
        } else {
          document.getElementById('error_found').innerHTML = response;
          $('#error').modal('show');
          element.disabled = false;
        }
      }
    });
  }

  function rescheduleTask(element) {
    let id = element.value;
    $('#resched').modal('show');
    $('#requestButton').off('click').on('click', function() {
      let $button = $(this);
      $button.prop('disabled', true);
      let reschedDate = document.getElementById('resched_date').value;
      let reschedReason = document.getElementById('resched_reason').value;
      $.ajax({
        url: '../config/tasks.php',
        method: 'POST',
        data: {
          "rescheduleTask": true,
          "id": id,
          "reschedDate": reschedDate,
          "reschedReason": reschedReason
        },
        success: function(response) {
          if (response === 'Success') {
            document.getElementById('success_log').innerHTML = 'Operation completed successfully.';
            $('#resched').modal('hide');
            $('#success').modal('show');
          } else {
            document.getElementById('error_found').innerHTML = response;
            $('#error').modal('show');
            $button.prop('disabled', false);
          }
        },
      });
    });
  }

  function startTask(element) {
    var id = element.value;
    $('#taskID').val(id);
    document.getElementById("startNote").innerHTML = 'Do you want to start this task?';
    $('#start').modal('show');

    $('#confirmButton').off('click').on('click', function() {
      var taskId = $('#taskID').val();
      $.ajax({
        url: '../config/tasks.php',
        method: 'POST',
        data: {
          "startTask": true,
          "id": id
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

  function endTask(element) {
    element.disabled = true;
    var id = element.value;
    $.ajax({
      method: "POST",
      url: "../config/tasks.php",
      data: {
        "endTaskDeatails": true,
        "taskID": id,
      },
      success: function(response) {
        $('#finishDetails').html(response);
        $('#finish').modal('show');
        element.disabled = false;
      }
    });

    $('#submitTask').off('click').on('click', function() {
      var $button = $(this);
      $button.prop('disabled', true);
      var formData = new FormData(document.getElementById('submitDetails'));
      formData.append('endTask', true);
      $.ajax({
        method: "POST",
        url: "../config/tasks.php",
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
          if (response === 'Success') {
            document.getElementById('success_log').innerHTML = 'Operation completed successfully.';
            $('#finish').modal('hide');
            $('#success').modal('show');
          } else {
            document.getElementById('error_found').innerHTML = response;
            $('#error').modal('show');
            $button.prop('disabled', false);
          }
        }
      });
    })
  }

  function checkTask(element) {
    var taskID = element.value;
    $.ajax({
      method: "POST",
      url: "../config/tasks.php",
      data: {
        "checkTask": true,
        "taskID": taskID,
      },
      success: function(response) {
        console.log(response);
        $('#taskDetails').html(response);
        openSpecificModal('view', 'modal-xl');
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
  }

  function reviewTask(element) {
    var taskID = element.value;
    $.ajax({
      method: "POST",
      url: "../config/tasks.php",
      data: {
        "reviewTask": true,
        "taskID": taskID,
      },
      success: function(response) {
        $('#reviewDetails').html(response);
        $('#re-view').modal('show');
        $('#taskReview_table').DataTable({
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
  }

  function updateDetails(element) {
    element.disabled = true;
    var formDetails = new FormData(document.getElementById('editDetails'));
    formDetails.append('updateDetails', true);
    var checkEditRemarks = formDetails.get('taskReview_remarks').replace(/\s+/g, ' ').trim();
    if (checkEditRemarks === '' || checkEditRemarks.length <= 30) {
      element.disabled = false;
      document.getElementById('error_found').innerHTML = 'The remarks contains fewer than 30 characters (excluding excess whitespace).';
      $('#error').modal('show');
    } else {
      $.ajax({
        method: "POST",
        url: "../config/tasks.php",
        data: formDetails,
        contentType: false,
        processData: false,
        success: function(response) {
          if (response === 'Success') {
            document.getElementById('success_log').innerHTML = 'Operation completed successfully.';
            $('#re-view').modal('hide');
            $('#success').modal('show');
          } else {
            document.getElementById('error_found').innerHTML = response;
            $('#error').modal('show');
            element.disabled = false;
          }
        }
      })
    }
  }

  function deleteFile(element) {
    var id = element.value;
    $('#danger').modal('show');

    $('#delete_id').off('click').on('click', function() {
      $.ajax({
        method: "POST",
        url: "../config/tasks.php",
        data: {
          "deleteFile": true,
          "id": id,
        },
        success: function(response) {
          if (response === 'Success') {
            document.getElementById('success_log').innerHTML = 'Operation completed successfully.';
            $('#danger').modal('hide');
            $('#success').modal('show');
          } else {
            document.getElementById('error_found').innerHTML = response;
            $('#error').modal('show');
          }
        }
      });
    });
  }

  function downloadFile(element) {
    var id = element.value;
    window.location.href = '../config/tasks.php?downloadFile=true&id=' + id;
  }

  document.addEventListener('DOMContentLoaded', function() {
    var activeTab = localStorage.getItem('activeTab');
    if (activeTab && document.querySelector(`a[href="${activeTab}"]`)) {
      document.querySelector(`a[href="${activeTab}"]`).classList.add('active');
      document.querySelector(activeTab).classList.add('show', 'active');
    } else {
      document.querySelector('.nav-link').classList.add('active');
      document.querySelector('.tab-pane').classList.add('show', 'active');
    }

    $('#myTabs a').on('shown.bs.tab', function(e) {
      var href = $(e.target).attr('href');
      localStorage.setItem('activeTab', href);

      var tableId = $(href).find('table').attr('id');
    });
  });

  function checkDateInputs() {
    var dateFrom = document.getElementById('date_from').value;
    var dateTo = document.getElementById('date_to');
    var status = localStorage.getItem('activeTab').replace('#', '').toUpperCase();
    var setTab = status.charAt(0).toUpperCase() + status.slice(1).toLowerCase();
    if (dateFrom) {
      dateTo.setAttribute('min', dateFrom);
      dateTo.disabled = false;
    } else {
      dateTo.removeAttribute('min');
      dateTo.disabled = true;
    }
    $('#myTasksTable' + setTab).DataTable().destroy();
    $('#myTasks' + setTab).empty();
    $.ajax({
      method: "POST",
      url: "../config/tasks.php",
      data: {
        "filterTableTask": true,
        "dateFrom": dateFrom,
        "dateTo": dateTo.value,
        "status": status
      },
      success: function(response) {
        $('#myTasks' + setTab).append(response);
        if (setTab === 'Todo') {
          $('#myTasksTableTodo').DataTable(todoSettings);
        } else if (setTab === 'Review') {
          $('#myTasksTableReview').DataTable(reviewSettings);
        } else if (setTab === 'Finished') {
          $('#myTasksTableFinished').DataTable(finishedSettings);
        }
      }
    });
  }
</script>