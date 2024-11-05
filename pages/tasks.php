<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <?php if ($access == 1 || $access == 3) : ?>
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center mb-3">
          <!-- From Date -->
          <div class="form-group col-2 mr-2">
            <label for="fromDate">From</label>
            <input type="date" id="fromDate" class="form-control">
          </div>
          <!-- To Date -->
          <div class="form-group col-2 mr-3">
            <label for="toDate">To</label>
            <input type="date" id="toDate" class="form-control" onchange="filterTable()" disabled>
          </div>
          <!-- Sorting & Filtering Dropdown Button -->
          <div class="dropdown mr-3">
            <button class="btn btn-primary dropdown-toggle" type="button" id="sortFilterDropdown" data-toggle="dropdown">
              <i class="fas fa-filter fa-fw"></i> Filter
            </button>
            <div class="dropdown-menu p-3" id="filterOptions" aria-labelledby="sortFilterDropdown" style="min-width: 250px;">
              <form id="filterTable">
                <h6>Filter by Department</h6>
                <div class="form-group">
                  <select name="filterByDepartment" id="filterByDepartment" class="form-control filterByDepartment" onchange="filterTable()">
                    <option value="All">All</option>
                    <?php
                    $getDepList = "SELECT * FROM department";
                    if ($access == 3) {
                      $getDepList .= " WHERE dept_id='$dept_id'";
                    }
                    $getDepList .= " ORDER BY dept_name ASC";
                    $getDepResult = mysqli_query($con, $getDepList);
                    while ($row = mysqli_fetch_assoc($getDepResult)): ?>
                      <option value="<?php echo $row['dept_id']; ?>"><?php echo ucwords(strtolower($row['dept_name'])); ?></option>
                    <?php endwhile; ?>
                  </select>
                </div>
                <div class="form-group form-hide d-none">
                  <h6>Filter by Section</h6>
                  <select name="filterBySection" id="filterBySection" class="form-control filterBySection" onchange="filterTable()"></select>
                </div>
                <h6 class="mt-2">Filter by Task Progress</h6>
                <div class="form-group">
                  <select class="form-control" id="priorityFilter" onchange="filterTable()">
                    <option value="All" selected>All</option>
                    <option value="NOT YET STARTED">Not Yet Started</option>
                    <option value="IN PROGRESS">In Progress</option>
                    <option value="REVIEW">In Review</option>
                    <option value="FINISHED">Completed</option>
                    <option value="RESCHEDULE">Reschedule</option>
                  </select>
                </div>
                <h6 class="mt-2">Filter by Task Class</h6>
                <div class="form-group">
                  <select class="form-control" id="classFilter" onchange="filterTable()">
                    <option value="All" selected>All</option>
                    <option value="DAILY ROUTINE">Daily Routine</option>
                    <option value="WEEKLY ROUTINE">Weekly Routine</option>
                    <option value="MONTHLY ROUTINE">Monthly Routine</option>
                    <option value="MONTHLY REPORT">Monthly Report</option>
                    <option value="ADDITIONAL TASK">Additional Routine</option>
                  </select>
                </div>
                <h6 class="mt-2">Filter by Status</h6>
                <div>
                  <input type="radio" name="statusFilter" id="allStatus" value="All" onchange="filterTable()">
                  <label for="allStatus">All</label>
                </div>
                <div>
                  <input type="radio" name="statusFilter" id="activeStatus" value="1" onchange="filterTable()" checked>
                  <label for="activeStatus">Active</label>
                </div>
                <div>
                  <input type="radio" name="statusFilter" id="inactiveStatus" value="0" onchange="filterTable()">
                  <label for="inactiveStatus">Inactive</label>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-hover" id="taskDeployedTable">
            <thead>
              <tr>
                <th>Code</th>
                <th>Task</th>
                <th>Classification</th>
                <th>Due Date</th>
                <th>Assignee</th>
                <th>Progress</th>
                <th></th>
              </tr>
            </thead>
            <tbody id='taskDeployedBody'>
              <?php $con->next_result();
              $result = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.task_status=1");
              if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                  $due_date = date_format(date_create($row['due_date']), "F d, Y h:i a"); ?>
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
                    <td class="text-truncate"><?php echo $due_date ?></td>
                    <td class="text-truncate"><?php echo getUser($row['in_charge']); ?></td>
                    <td><?php echo getProgressBadge($row['status']); ?></td>
                    <td class="text-truncate">
                      <button type="button" class="btn btn-secondary btn-block" onclick="editTask(this)" value="<?php echo $row['id'] ?>"><i class="fas fa-edit fa-fw"></i> Modify</button>
                      <?php if (in_array($row['status'], ['REVIEW', 'FINISHED'])): ?>
                        <button type="button" onclick="viewTask(this)" class="btn btn-primary btn-block" value="<?php echo $row['id'] ?>" data-name="<?php echo $row['task_name'] ?>"><i class="fas fa-info fa-fw"></i> Details</button>
                      <?php endif; ?>
                    </td>
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
          <div class="form-group col-2 mr-2">
            <label for="fromDate">From</label>
            <input type="date" id="fromDate" class="form-control">
          </div>
          <!-- To Date -->
          <div class="form-group col-2 mr-3">
            <label for="toDate">To</label>
            <input type="date" id="toDate" class="form-control" onchange="checkDateInputs()" disabled>
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
                      <th>Code</th>
                      <th>Task</th>
                      <th>Classification</th>
                      <th>Due Date</th>
                      <th>Status</th>
                      <th><button class="btn btn-success btn-block d-none" id="startSelect"></button></th>
                    </tr>
                  </thead>
                  <tbody id="myTasksTodo">
                    <?php
                    $query_result = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.task_status=1 AND t.in_charge='$username' AND td.status NOT IN ('REVIEW', 'FINISHED')");
                    while ($row = $query_result->fetch_assoc()) {
                      $current_date = date('Y-m-d');
                      $checkbox = '<input type="checkbox" name="selected_ids[]" class="form-control" value="' . (date_create(date('Y-m-d', strtotime($row['due_date']))) > date_create($current_date) ? '' : $row['id']) . '" ' . (date_create(date('Y-m-d', strtotime($row['due_date']))) > date_create($current_date) ? 'disabled' : '') . '>';
                      $due_date = date_format(date_create($row['due_date']), "F d, Y h:i a"); ?>
                      <tr class="<?php if ((new DateTime($today))->setTime(0, 0, 0) > (new DateTime($row['due_date']))->setTime(0, 0, 0) && $row['status'] === 'NOT YET STARTED') echo "tick-pulse"; ?>">
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
                        <td class="text-truncate"><?php echo $due_date ?></td>
                        <td><?php echo getProgressBadge($row['status']); ?></td>
                        <td class="text-truncate">
                          <?php if ($row['status'] === 'NOT YET STARTED') {
                            if (date_create(date('Y-m-d', strtotime($row['due_date']))) > date_create($current_date)) {
                              echo '<button class="btn btn-secondary btn-block" disabled><i class="far fa-clock fa-fw"></i> On Hold</button>';
                            } else {
                              echo '<button class="singleStart btn btn-success btn-block" value="' . $row['id'] . '" onclick="startTask(this)"><i class="far fa-play-circle fa-fw"></i> Start</button>';
                            }
                          } elseif ($row['status'] === 'IN PROGRESS') {
                            echo '<button class="btn btn-danger btn-block" value="' . $row['id'] . '" onclick="endTask(this)" data-task="' . $row['task_name'] . '"><i class="far fa-stop-circle fa-fw"></i> Finish</button>';
                          } else {
                            echo '<button class="btn btn-dark btn-block" disabled><i class="far fa-clock fa-fw"></i> On Hold</button>';
                          }
                          if ($row['old_date'] === NULL) {
                            echo '<button class="btn btn-secondary btn-block" value="' . $row['id'] . '" onclick="rescheduleTask(this)"><i class="fas fa-redo fa-fw"></i> Reschedule</button>';
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
                      <th>Code</th>
                      <th>Title</th>
                      <th>Classification</th>
                      <th>Started Date</th>
                      <th>Finished Date</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody id="myTasksReview">
                    <?php
                    $query_result = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.task_status=1 AND t.in_charge='$username' AND td.status='REVIEW'");
                    while ($row = $query_result->fetch_assoc()) {
                      $start_date = is_null($row['date_start']) ? "N/A" : date_format(date_create($row['date_start']), "F d, Y h:i a");
                      $date_accomplished = date_format(date_create($row['date_accomplished']), "F d, Y h:i a"); ?>
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
                        <td class="text-truncate"><?php echo $start_date ?></td>
                        <td class="text-truncate"><?php echo $date_accomplished ?></td>
                        <td class="text-truncate"><button type="button" class="btn btn-block btn-warning" value='<?php echo $row['id']; ?>' onclick="reviewTask(this)"><i class="far fa-eye fa-fw"></i> View</button></td>
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
                      <th>Code</th>
                      <th>Title</th>
                      <th>Classification</th>
                      <th>Due Date</th>
                      <th>Rating</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody id="myTasksFinished">
                    <?php
                    $query_result = mysqli_query($con, "SELECT * FROM task_class tc JOIN task_list tl ON tc.id=tl.task_class JOIN tasks t ON tl.id=t.task_id JOIN tasks_details td ON t.id=td.task_id WHERE td.task_status=1 AND t.in_charge='$username' AND td.status='FINISHED'");
                    while ($row = $query_result->fetch_assoc()) {
                      $due_date = date_format(date_create($row['due_date']), "F d, Y h:i a"); ?>
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
                        <td class="text-truncate"><?php echo $due_date ?></td>
                        <td class="text-center">
                          <span class="h5 text-success font-weight-bold"><?php echo $row['achievement'] ?></span>
                        </td>
                        <td class="text-truncate"><button type="button" class="btn btn-block btn-primary" value='<?php echo $row['id']; ?>' onclick="viewTask(this)"><i class="fas fa-tasks fa-fw"></i> Details</button></td>
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
  <?php elseif ($access == 0) : ?>
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
        <h5 class="modal-title text-truncate" id="taskTitle"></h5>
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
      <div class="modal-header">
        <h5 class="modal-title">Task Details</h5>
      </div>
      <div class="modal-body" id="taskDetails">
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" id="editbtn">Edit</button>
        <button class="btn btn-success d-none" id="savebtn">Save</button>
        <button class="btn btn-secondary" data-dismiss="modal" id="closebtn">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="edit" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modify Task Details</h5>
      </div>
      <div class="modal-body" id="modifyDetails">
      </div>
      <div class="modal-footer justify-content-between">
        <div>
          <button class="btn btn-danger" id="deleteTask">Delete</button>
        </div>
        <div>
          <button class="btn btn-success" id="saveEdit">Save</button>
          <button class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="re-view" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content border-warning">
      <div class="modal-header">
        <h5 class="modal-title">Task Details</h5>
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
<script src="../assets/js/department-load-section.js" w></script>
<script src="../assets/js/datepicker-min.js"></script>

<script>
  $.fn.dataTable.ext.type.order['date-custom-pre'] = function(d) {
    var months = {
      "January": 1,
      "February": 2,
      "March": 3,
      "April": 4,
      "May": 5,
      "June": 6,
      "July": 7,
      "August": 8,
      "September": 9,
      "October": 10,
      "November": 11,
      "December": 12
    };
    var dateParts = d.split(' ');
    return new Date(dateParts[2], months[dateParts[0]] - 1, dateParts[1].replace(',', ''));
  };
</script>

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
    const taskID = element.value;
    const access = <?php echo $access ?>;
    $.ajax({
      method: "POST",
      url: "../config/tasks.php",
      data: {
        "viewTask": true,
        "taskID": taskID,
      },
      success: function(response) {
        $('#taskDetails').html(response);
        openSpecificModal('view', 'modal-lg');
        if ($('#editProgress').val() === 'REVIEW' || access === 2) {
          $('#editbtn').addClass('d-none');
        } else if (access === 1) {
          $('#editbtn').addClass('d-none');
        }
        document.getElementById('editbtn').onclick = function() {
          $('#editbtn').addClass('d-none');
          $('#savebtn').removeClass('d-none');
          if (access === 1) {
            $('#headComment').removeClass('d-none');
            $('#editComment').removeAttr('readonly');
            $('#editScore').removeAttr('readonly');
          }
        }
        document.getElementById('closebtn').onclick = function() {
          $('#savebtn').addClass('d-none');
          $('#editbtn').removeClass('d-none');
          $('#editScore, #editComment').attr('readonly', 'readonly');
          $('#headComment').addClass('d-none');
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
    const id = element.value;
    const name = element.getAttribute('data-task');
    $.ajax({
      method: "POST",
      url: "../config/tasks.php",
      data: {
        "endTaskDeatails": true,
        "taskID": id,
      },
      success: function(response) {
        $('#taskTitle').html('<i class="fas fa-pen-square fa-fw"></i> ' + name);
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
        openSpecificModal('view', 'modal-lg');
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
        openSpecificModal('re-view', 'modal-lg');
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

  function checkDateInputs() {
    var dateFrom = document.getElementById('fromDate').value;
    var dateTo = document.getElementById('toDate').value;
    var status = localStorage.getItem('activeTab').replace('#', '').toUpperCase();
    var setTab = status.charAt(0).toUpperCase() + status.slice(1).toLowerCase();
    $('#myTasksTable' + setTab).DataTable().destroy();
    $('#myTasks' + setTab).empty();
    $.ajax({
      method: "POST",
      url: "../config/tasks.php",
      data: {
        "filterTableTask": true,
        "dateFrom": dateFrom,
        "dateTo": dateTo,
        "status": status
      },
      success: function(response) {
        console.log(response);
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

<script>
  $('#filterOptions').on('click', function(event) {
    event.stopPropagation();
  });
</script>

<!-- Admin -->
<script>
  $('#taskDeployedTable').DataTable({
    "columnDefs": [{
      "orderable": false,
      "searchable": false,
      "targets": [0, 6]
    }, {
      "type": "date-custom",
      "targets": 3
    }],
    "order": [
      [3, "desc"],
      [1, "asc"]
    ]
  }, $('[data-toggle="tooltip"]').tooltip());

  function editTask(element) {
    const taskID = element.value;
    const access = <?php echo $access ?>;
    $.ajax({
      method: "POST",
      url: "../config/tasks.php",
      data: {
        "editTask": true,
        "taskID": taskID,
      },
      success: function(response) {
        $('#modifyDetails').html(response);
        openSpecificModal('edit', 'modal-md');
        if (access === 3) {
          $('#deleteTask').addClass('d-none');
          $('#adminShow1, #adminShow2').addClass('d-none');
        }
        document.getElementById('saveEdit').onclick = function() {
          const formData = new FormData(document.getElementById('modifyForm'));
          formData.append('taskID', taskID);
          formData.append('modifyTask', true);
          $.ajax({
            method: "POST",
            url: "../config/tasks.php",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
              if (response === 'Success') {
                document.getElementById('success_log').innerHTML = 'Task modified successfully.';
                $('#success').modal('show');
              } else {
                document.getElementById('error_found').innerHTML = response;
                $('#error').modal('show');
              }
            }
          })
        }
        document.getElementById('deleteTask').onclick = function() {
          openSpecificModal('delete', 'modal-sm');
          document.getElementById('confirmBtn').onclick = function() {
            $.ajax({
              method: "POST",
              url: "../config/tasks.php",
              data: {
                "deleteTask": true,
                "taskID": taskID
              },
              success: function(response) {
                if (response === 'Success') {
                  document.getElementById('success_log').innerHTML = 'Task deleted successfully.';
                  $('#delete').modal('hide');
                  $('#success').modal('show');
                } else {
                  document.getElementById('error_found').innerHTML = response;
                  $('#delete').modal('hide');
                  $('#error').modal('show');
                }
              }
            });
          }
        }
      }
    });
  }

  function filterTable() {
    $('#taskDeployedTable').DataTable().destroy();
    $('#taskDeployedBody').empty();
    const department = document.getElementById('filterByDepartment').value;
    const section = document.getElementById('filterBySection').value;
    const progress = document.getElementById('priorityFilter').value;
    const tclass = document.getElementById('classFilter').value;
    const status = document.querySelector('input[name="statusFilter"]:checked')?.value;
    const filteredStatus = (status === "ACTIVE" && (document.getElementById('statusActive').checked || document.getElementById('statusInactive').checked)) ? null : status;
    const fromDate = document.getElementById('fromDate').value;
    const toDate = document.getElementById('toDate').value;

    let data = {
      "filterTable": true
    };

    if (department !== "All") data.department = department;
    if (section !== "" && section !== "All") data.section = section;
    data.progress = progress;
    if (tclass !== "" && tclass !== "All") data.tclass = tclass;
    if (status !== "All") data.status = filteredStatus;

    if (fromDate !== "") data.fromDate = fromDate;
    if (toDate !== "") data.toDate = toDate;

    console.log(data);

    $.ajax({
      method: "POST",
      url: "../config/tasks.php",
      data: data,
      success: function(response) {
        console.log(response);
        $('#taskDeployedBody').append(response);
        $('#taskDeployedTable').DataTable({
          "columnDefs": [{
            "orderable": false,
            "searchable": false,
            "targets": [0, 6]
          }, {
            "type": "date-custom",
            "targets": 3
          }],
          "order": [
            [3, "desc"],
            [1, "asc"]
          ]
        }, $('[data-toggle="tooltip"]').tooltip());
      }
    })
  }
</script>

<!-- Member -->
<script>
  <?php if ($access === '2'): ?>
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
  <?php endif; ?>
</script>