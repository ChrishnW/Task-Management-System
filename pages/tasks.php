<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <?php if ($access == 1) { ?>
    <div class="row">
      <div class="form-group col-md-2">
        <label>To</label>
        <input type="date" name="date_to" id="date_to" class="form-control">
      </div>
      <div class="form-group col-md-2">
        <label>From</label>
        <input type="date" name="date_from" id="date_from" class="form-control">
      </div>
      <div class="form-group col-md-2">
        <label>Department</label>
        <select id="department" name="department" class="form-control selectpicker" data-style="bg-primary text-white text-capitalize" data-size="5" data-live-search="true" onchange="selectSection(this);">
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
        <select id="section" name="section[]" class="form-control selectpicker" data-style="bg-primary text-white text-capitalize" data-size="5">
          <option value="" data-subtext="Default" selected>All</option>
        </select>
      </div>
      <div class="form-group col-md-2">
        <label>Progress</label>
        <select id="progress" name="progress" class="form-control selectpicker" data-style="bg-primary text-white text-capitalize" data-size="5">
          <option value="" data-subtext="Default" selected>All</option>
          <option value="NOT YET STARTED">Not Yet Started</option>
          <option value="IN PROGRESS">In-Progress</option>
          <option value="REVIEW">Review</option>
          <option value="FINISHED">Finished</option>
        </select>
      </div>
      <div class="form-group col">
        <br>
        <button type="button" class="btn btn-success" onclick="filterTable(this)"><i class="fas fa-filter fa-fw"></i> Filter Table</button>
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
                <th class="col col-md-1">Action</th>
                <th>Code</th>
                <th>Title</th>
                <th>Classification</th>
                <th class="col col-md-1">Due Date</th>
                <th>Asignee</th>
                <th>Progress</th>
              </tr>
            </thead>
            <tfoot class='table table-success'>
              <tr>
                <th class="col col-md-1">Action</th>
                <th>Code</th>
                <th>Title</th>
                <th>Classification</th>
                <th class="col col-md-1">Due Date</th>
                <th>Asignee</th>
                <th>Progress</th>
              </tr>
            </tfoot>
            <tbody id='dataTableBody'>
              <?php $con->next_result();
              $result = mysqli_query($con, "SELECT DISTINCT tasks_details.*, accounts.file_name, tasks.task_details FROM tasks_details JOIN accounts ON tasks_details.in_charge=accounts.username JOIN tasks ON tasks_details.task_name=tasks.task_name WHERE task_status=1 AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE)");
              if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                  if (empty($row['file_name'])) {
                    $imageURL = '../assets/img/user-profiles/nologo.png';
                  } else {
                    $imageURL = '../assets/img/user-profiles/' . $row['file_name'];
                  }
                  $task_classes = [1 => ['name' => 'DAILY ROUTINE', 'badge' => 'info'], 2 => ['name' => 'WEEKLY ROUTINE', 'badge' => 'info'], 3 => ['name' => 'MONTHLY ROUTINE', 'badge' => 'info'], 4 => ['name' => 'ADDITIONAL TASK', 'badge' => 'info'], 5 => ['name' => 'PROJECT', 'badge' => 'info'], 6 => ['name' => 'MONTHLY REPORT', 'badge' => 'danger']];
                  if (isset($task_classes[$row['task_class']])) {
                    $class = $task_classes[$row['task_class']]['name'];
                    $badge = $task_classes[$row['task_class']]['badge'];
                  } else {
                    $class = 'Unknown';
                    $badge = 'secondary';
                  }
                  $task_class = '<span class="badge badge-' . $badge . '">' . $class . '</span>';
                  $due_date  = date_format(date_create($row['due_date']), "Y-m-d h:i a");
                  $assignee  = '<img src=' . $imageURL . ' class="border border-primary img-table-solo">';
                  $status_badges = [
                    'NOT YET STARTED' => 'primary',
                    'IN PROGRESS' => 'warning',
                    'REVIEW' => 'danger',
                    'FINISHED' => 'success',
                    'RESCHEDULE' => 'secondary'
                  ];
                  $progress = '<span class="badge badge-' . $status_badges[$row['status']] . '">' . $row['status'] . '</span>';
              ?>
                  <tr>
                    <td><button type="button" class="btn btn-info btn-circle" onclick="editTask(this)" value="<?php echo $row['id'] ?>"><i class="fas fa-pen"></i></button> <button type="button" onclick="viewTask(this)" class="btn btn-warning btn-circle" value="<?php echo $row['id'] ?>" data-name="<?php echo $row['task_name'] ?>"><i class="fas fa-eye"></i></button></td>
                    <td>
                      <center /><?php echo $row['task_code'] ?>
                    </td>
                    <td><?php echo $row['task_name'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
                    <td><?php echo $task_class ?></td>
                    <td><?php echo $due_date ?></td>
                    <td data-toggle="tooltip" data-placement="top" title="<?php echo $row['in_charge'] ?>">
                      <center /><?php echo $assignee ?>
                    </td>
                    <td><?php echo $progress ?></td>
                  </tr>
              <?php }
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php } elseif ($access == 2) { ?>
    <div class="row">
      <div class="form-group col-md-2">
        <label>To</label>
        <input type="date" name="date_to" id="date_to" class="form-control">
      </div>
      <div class="form-group col-md-2">
        <label>From</label>
        <input type="date" name="date_from" id="date_from" class="form-control">
      </div>
      <div class="form-group col">
        <br>
        <button type="button" class="btn btn-success" onclick="filterTableTask(this)"><i class="fas fa-filter fa-fw"></i> Filter Table</button>
        <button type="button" class="btn btn-danger" onclick="location.reload();"><i class="fas fa-eraser fa-fw"></i> Remove Filter</button>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">My Task</h5>
        <?php
        $query_result = mysqli_query($con, "SELECT COUNT('id') AS not_yet_started, (SELECT COUNT('id') FROM tasks_details WHERE task_status=1 AND status='IN PROGRESS' AND in_charge='$username') AS in_progress, (SELECT COUNT('id') FROM tasks_details WHERE task_status=1 AND status='REVIEW' AND in_charge='$username') AS review, (SELECT COUNT('id') FROM tasks_details WHERE task_status=1 AND status='FINISHED' AND in_charge='$username') AS finished, (SELECT COUNT('id') FROM tasks_details WHERE task_status=1 AND status='RESCHEDULE' AND in_charge='$username') AS rescheduled FROM tasks_details WHERE task_status=1 AND status='NOT YET STARTED' AND in_charge='$username'");
        $row = mysqli_fetch_assoc($query_result);
        ?>
        <ul class="nav nav-tabs" id="myTabs">
          <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#todo" data-status="NOT YET STARTED"><i class="fas fa-list-ul"></i> To-do <span class="badge badge-success"><?php echo $row['not_yet_started'] ?></span></a>
          </li>
          <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#inprogress" data-status="IN PROGRESS"><i class="fas fa-hourglass-start"></i> In-Progress <span class="badge badge-danger"><?php echo $row['in_progress'] ?></span></a>
          </li>
          <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#review" data-status="REVIEW"><i class="fas fa-hand-paper"></i> For Review <span class="badge badge-warning"><?php echo $row['review'] ?></span></a>
          </li>
          <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#finished" data-status="FINISHED"><i class="fas fa-tasks"></i> Finished <span class="badge badge-primary"><?php echo $row['finished'] ?></span></a>
          </li>
          <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#reschedule" data-status="RESCHEDULE"><i class="fas fa-clock"></i> Rescheduling <span class="badge badge-secondary"><?php echo $row['rescheduled'] ?></span></a>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade" id="todo">
            <div class="card">
              <div class="card-header" id="actionButton" style="display: none;">
                <button id="submitButton" onclick="getCheckedValue(this)" class="btn btn-success pull-right"><i class="fas fa-play"></i> Start All Selected</button>
              </div>
              <div class="card-body table-responsive">
                <table id="myTasksTableTodo" class="table table-striped">
                  <thead class="table table-success">
                    <tr>
                      <th class="col col-md-1"><input type='checkbox' id='selectAll' class='form-control tasksCheckboxes'></th>
                      <th>Code</th>
                      <th>Title</th>
                      <th>Classification</th>
                      <th class="col col-md-1">Due Date</th>
                      <th>Asignee</th>
                      <th>Progress</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="myTasksTodo">
                    <?php
                    $query_result = mysqli_query($con, "SELECT DISTINCT tasks_details.*, accounts.file_name, tasks.task_details, section.dept_id FROM tasks_details JOIN accounts ON tasks_details.in_charge = accounts.username JOIN tasks ON tasks_details.task_name = tasks.task_name JOIN section ON tasks_details.task_for = section.sec_id WHERE tasks_details.task_status = 1 AND tasks_details.status='NOT YET STARTED' AND tasks_details.in_charge='$username'");
                    while ($row = $query_result->fetch_assoc()) {
                      $current_date = date('Y-m-d');
                      $task_classes = [
                        1 => ['name' => 'DAILY ROUTINE', 'badge' => 'info'],
                        2 => ['name' => 'WEEKLY ROUTINE', 'badge' => 'info'],
                        3 => ['name' => 'MONTHLY ROUTINE', 'badge' => 'info'],
                        4 => ['name' => 'ADDITIONAL TASK', 'badge' => 'info'],
                        5 => ['name' => 'PROJECT', 'badge' => 'info'],
                        6 => ['name' => 'MONTHLY REPORT', 'badge' => 'danger']
                      ];

                      $class = $task_classes[$row['task_class']]['name'] ?? 'Unknown';
                      $badge = $task_classes[$row['task_class']]['badge'] ?? 'secondary';

                      $action = (date_create($row['due_date']) <= date_create($current_date))
                        ? '<button type="button" class="btn btn-circle btn-secondary" disabled><i class="fas fa-ban"></i></button>'
                        : '<button type="button" class="btn btn-circle btn-success" value=' . $row['id'] . ' onclick="startTask(this)"><i class="fas fa-play"></i></button>';

                      $checkbox = (date_create($row['due_date']) <= date_create($current_date))
                        ? '<input type="checkbox" name="selected_ids[]" class="form-control" value="" disabled>'
                        : '<input type="checkbox" name="selected_ids[]" class="form-control" value=' . $row['id'] . '>';

                      $status_badges = [
                        'NOT YET STARTED' => 'primary',
                        'IN PROGRESS' => 'warning',
                        'REVIEW' => 'danger',
                        'FINISHED' => 'success',
                        'RESCHEDULE' => 'secondary'
                      ];
                      $progress = '<span class="badge badge-' . $status_badges[$row['status']] . '">' . $row['status'] . '</span>';

                      $task_class = '<span class="badge badge-' . $badge . '">' . $class . '</span>';
                      $due_date = date_format(date_create($row['due_date']), "Y-m-d h:i a");
                      $assignee = '<img src=' . $imageURL . ' class="border border-primary img-table-solo">';
                    ?>
                      <tr>
                        <td><?php echo $checkbox ?></td>
                        <td><?php echo $row['task_code'] ?></td>
                        <td><?php echo $row['task_name'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
                        <td><?php echo $task_class ?></td>
                        <td><?php echo $due_date ?></td>
                        <td data-toggle="tooltip" data-placement="left" title="<?php echo $row['in_charge'] ?>">
                          <center /><?php echo $assignee ?>
                        </td>
                        <td><?php echo $progress ?></td>
                        <td><?php echo $action ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="inprogress">
            <div class="card">
              <div class="card-body table-responsive">
                <table id="myTasksTableInprogress" class="table table-striped">
                  <thead class="table table-danger">
                    <tr>
                      <th>Code</th>
                      <th>Title</th>
                      <th>Classification</th>
                      <th>Due Date</th>
                      <th>Asignee</th>
                      <th>Progress</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="myTasksInprogress">
                    <?php
                    $query_result = mysqli_query($con, "SELECT DISTINCT tasks_details.*, accounts.file_name, tasks.task_details, section.dept_id FROM tasks_details JOIN accounts ON tasks_details.in_charge = accounts.username JOIN tasks ON tasks_details.task_name = tasks.task_name JOIN section ON tasks_details.task_for = section.sec_id WHERE tasks_details.task_status = 1 AND tasks_details.status='IN PROGRESS' AND tasks_details.in_charge='$username'");
                    while ($row = $query_result->fetch_assoc()) {
                      $current_date = date('Y-m-d');
                      $task_classes = [
                        1 => ['name' => 'DAILY ROUTINE', 'badge' => 'info'],
                        2 => ['name' => 'WEEKLY ROUTINE', 'badge' => 'info'],
                        3 => ['name' => 'MONTHLY ROUTINE', 'badge' => 'info'],
                        4 => ['name' => 'ADDITIONAL TASK', 'badge' => 'info'],
                        5 => ['name' => 'PROJECT', 'badge' => 'info'],
                        6 => ['name' => 'MONTHLY REPORT', 'badge' => 'danger']
                      ];

                      $class = $task_classes[$row['task_class']]['name'] ?? 'Unknown';
                      $badge = $task_classes[$row['task_class']]['badge'] ?? 'secondary';

                      $action = '<button type="button" class="btn btn-circle btn-danger" value=' . $row['id'] . ' onclick="endTask(this)"><i class="fas fa-stop"></i></button>';

                      $status_badges = [
                        'NOT YET STARTED' => 'primary',
                        'IN PROGRESS' => 'warning',
                        'REVIEW' => 'danger',
                        'FINISHED' => 'success',
                        'RESCHEDULE' => 'secondary'
                      ];
                      $progress = '<span class="badge badge-' . $status_badges[$row['status']] . '">' . $row['status'] . '</span>';

                      $task_class = '<span class="badge badge-' . $badge . '">' . $class . '</span>';
                      $due_date = date_format(date_create($row['due_date']), "Y-m-d h:i a");
                      $assignee = '<img src=' . $imageURL . ' class="border border-primary img-table-solo">';
                    ?>
                      <tr>
                        <td><?php echo $row['task_code'] ?></td>
                        <td><?php echo $row['task_name'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
                        <td><?php echo $task_class ?></td>
                        <td><?php echo $due_date ?></td>
                        <td data-toggle="tooltip" data-placement="top" title="<?php echo $row['in_charge'] ?>">
                          <center /><?php echo $assignee ?>
                        </td>
                        <td><?php echo $progress ?></td>
                        <td><?php echo $action ?></td>
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
                <table id="myTasksTableReview" class="table table-striped">
                  <thead class="table table-warning">
                    <tr>
                      <th>Code</th>
                      <th>Title</th>
                      <th>Classification</th>
                      <th>Finished Date</th>
                      <th>Asignee</th>
                      <th>Progress</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="myTasksReview">
                    <?php
                    $query_result = mysqli_query($con, "SELECT DISTINCT tasks_details.*, accounts.file_name, tasks.task_details, section.dept_id FROM tasks_details JOIN accounts ON tasks_details.in_charge = accounts.username JOIN tasks ON tasks_details.task_name = tasks.task_name JOIN section ON tasks_details.task_for = section.sec_id WHERE tasks_details.task_status = 1 AND tasks_details.status='REVIEW' AND tasks_details.in_charge='$username'");
                    while ($row = $query_result->fetch_assoc()) {
                      $current_date = date('Y-m-d');
                      $task_classes = [
                        1 => ['name' => 'DAILY ROUTINE', 'badge' => 'info'],
                        2 => ['name' => 'WEEKLY ROUTINE', 'badge' => 'info'],
                        3 => ['name' => 'MONTHLY ROUTINE', 'badge' => 'info'],
                        4 => ['name' => 'ADDITIONAL TASK', 'badge' => 'info'],
                        5 => ['name' => 'PROJECT', 'badge' => 'info'],
                        6 => ['name' => 'MONTHLY REPORT', 'badge' => 'danger']
                      ];

                      $class = $task_classes[$row['task_class']]['name'] ?? 'Unknown';
                      $badge = $task_classes[$row['task_class']]['badge'] ?? 'secondary';

                      $action = '<button type="button" class="btn btn-circle btn-warning" value=' . $row['id'] . ' onclick="reviewTask(this)"><i class="fas fa-eye"></i></button>';

                      $status_badges = [
                        'NOT YET STARTED' => 'primary',
                        'IN PROGRESS' => 'warning',
                        'REVIEW' => 'danger',
                        'FINISHED' => 'success',
                        'RESCHEDULE' => 'secondary'
                      ];
                      $progress = '<span class="badge badge-' . $status_badges[$row['status']] . '">' . $row['status'] . '</span>';

                      $task_class = '<span class="badge badge-' . $badge . '">' . $class . '</span>';
                      $date_accomplished = date_format(date_create($row['date_accomplished']), "Y-m-d h:i a");
                      $assignee = '<img src=' . $imageURL . ' class="border border-primary img-table-solo">';
                    ?>
                      <tr>
                        <td><?php echo $row['task_code'] ?></td>
                        <td><?php echo $row['task_name'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
                        <td><?php echo $task_class ?></td>
                        <td><?php echo $date_accomplished ?></td>
                        <td data-toggle="tooltip" data-placement="top" title="<?php echo $row['in_charge'] ?>">
                          <center /><?php echo $assignee ?>
                        </td>
                        <td><?php echo $progress ?></td>
                        <td><?php echo $action ?></td>
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
                <table id="myTasksTableFinished" class="table table-striped">
                  <thead class="table table-primary">
                    <tr>
                      <th>Code</th>
                      <th>Title</th>
                      <th>Classification</th>
                      <th>Finished Date</th>
                      <th>Asignee</th>
                      <th>Progress</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="myTasksFinished">
                    <?php
                    $query_result = mysqli_query($con, "SELECT DISTINCT tasks_details.*, accounts.file_name, tasks.task_details, section.dept_id FROM tasks_details JOIN accounts ON tasks_details.in_charge = accounts.username JOIN tasks ON tasks_details.task_name = tasks.task_name JOIN section ON tasks_details.task_for = section.sec_id WHERE tasks_details.task_status = 1 AND tasks_details.status='FINISHED' AND tasks_details.in_charge='$username'");
                    while ($row = $query_result->fetch_assoc()) {
                      $current_date = date('Y-m-d');
                      $task_classes = [
                        1 => ['name' => 'DAILY ROUTINE', 'badge' => 'info'],
                        2 => ['name' => 'WEEKLY ROUTINE', 'badge' => 'info'],
                        3 => ['name' => 'MONTHLY ROUTINE', 'badge' => 'info'],
                        4 => ['name' => 'ADDITIONAL TASK', 'badge' => 'info'],
                        5 => ['name' => 'PROJECT', 'badge' => 'info'],
                        6 => ['name' => 'MONTHLY REPORT', 'badge' => 'danger']
                      ];

                      $class = $task_classes[$row['task_class']]['name'] ?? 'Unknown';
                      $badge = $task_classes[$row['task_class']]['badge'] ?? 'secondary';

                      $action = '<button type="button" class="btn btn-circle btn-primary" value=' . $row['id'] . ' onclick="checkTask(this)"><i class="fas fa-history"></i></button>';

                      $status_badges = [
                        'NOT YET STARTED' => 'primary',
                        'IN PROGRESS' => 'warning',
                        'REVIEW' => 'danger',
                        'FINISHED' => 'success',
                        'RESCHEDULE' => 'secondary'
                      ];
                      $progress = '<span class="badge badge-' . $status_badges[$row['status']] . '">' . $row['status'] . '</span>';

                      $task_class = '<span class="badge badge-' . $badge . '">' . $class . '</span>';
                      $date_accomplished = date_format(date_create($row['date_accomplished']), "Y-m-d h:i a");
                      $assignee = '<img src=' . $imageURL . ' class="border border-primary img-table-solo">';
                    ?>
                      <tr>
                        <td><?php echo $row['task_code'] ?></td>
                        <td><?php echo $row['task_name'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
                        <td><?php echo $task_class ?></td>
                        <td><?php echo $date_accomplished ?></td>
                        <td data-toggle="tooltip" data-placement="top" title="<?php echo $row['in_charge'] ?>">
                          <center /><?php echo $assignee ?>
                        </td>
                        <td><?php echo $progress ?></td>
                        <td><?php echo $action ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="reschedule">
            <div class="card">
              <div class="card-body table-responsive">
                <table id="myTasksTableReschedule" class="table table-striped">
                  <thead class="table table-secondary">
                    <tr>
                      <th>Code</th>
                      <th>Title</th>
                      <th>Classification</th>
                      <th>Due Date</th>
                      <th>Asignee</th>
                      <th>Progress</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="myTasksReschedule">
                    <?php
                    $query_result = mysqli_query($con, "SELECT DISTINCT tasks_details.*, accounts.file_name, tasks.task_details, section.dept_id FROM tasks_details JOIN accounts ON tasks_details.in_charge = accounts.username JOIN tasks ON tasks_details.task_name = tasks.task_name JOIN section ON tasks_details.task_for = section.sec_id WHERE tasks_details.task_status = 1 AND tasks_details.status='RESCHEDULE' AND tasks_details.in_charge='$username'");
                    while ($row = $query_result->fetch_assoc()) {
                      $current_date = date('Y-m-d');
                      $task_classes = [
                        1 => ['name' => 'DAILY ROUTINE', 'badge' => 'info'],
                        2 => ['name' => 'WEEKLY ROUTINE', 'badge' => 'info'],
                        3 => ['name' => 'MONTHLY ROUTINE', 'badge' => 'info'],
                        4 => ['name' => 'ADDITIONAL TASK', 'badge' => 'info'],
                        5 => ['name' => 'PROJECT', 'badge' => 'info'],
                        6 => ['name' => 'MONTHLY REPORT', 'badge' => 'danger']
                      ];

                      $class = $task_classes[$row['task_class']]['name'] ?? 'Unknown';
                      $badge = $task_classes[$row['task_class']]['badge'] ?? 'secondary';

                      $action = '<button type="button" class="btn btn-circle btn-warning" value=' . $row['id'] . ' onclick="startTask(this)"><i class="fas fa-eye"></i></button>';

                      $status_badges = [
                        'NOT YET STARTED' => 'primary',
                        'IN PROGRESS' => 'warning',
                        'REVIEW' => 'danger',
                        'FINISHED' => 'success',
                        'RESCHEDULE' => 'secondary'
                      ];
                      $progress = '<span class="badge badge-' . $status_badges[$row['status']] . '">' . $row['status'] . '</span>';

                      $task_class = '<span class="badge badge-' . $badge . '">' . $class . '</span>';
                      $due_date = date_format(date_create($row['due_date']), "Y-m-d h:i a");
                      $assignee = '<img src=' . $imageURL . ' class="border border-primary img-table-solo">';
                    ?>
                      <tr>
                        <td><?php echo $row['task_code'] ?></td>
                        <td><?php echo $row['task_name'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
                        <td><?php echo $task_class ?></td>
                        <td><?php echo $due_date ?></td>
                        <td data-toggle="tooltip" data-placement="top" title="<?php echo $row['in_charge'] ?>">
                          <center /><?php echo $assignee ?>
                        </td>
                        <td><?php echo $progress ?></td>
                        <td><?php echo $action ?></td>
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
      <!-- <div class="form-group col-md-2">
        <label>Department</label>
        <select id="department" name="department" class="form-control selectpicker" data-style="bg-primary text-white text-capitalize" data-size="5">
          <?php
          $con->next_result();
          $sql = mysqli_query($con, "SELECT * FROM department WHERE status='1' AND dept_id='$dept_id'");
          if (mysqli_num_rows($sql) > 0) {
            while ($row = mysqli_fetch_assoc($sql)) { ?>
              <option value='<?php echo $row['dept_id'] ?>' data-subtext='<?php echo $row['dept_id'] ?>' class="text-capitalize"><?php echo strtolower($row['dept_name']) ?></option>
          <?php }
          } ?>
        </select>
      </div> -->
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
        <h6 class="m-0 font-weight-bold text-white">Deployed Tasks</h6>
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
                <th>Asignee</th>
                <th>Progress</th>
              </tr>
            </thead>
            <tfoot class='table table-success'>
              <tr>
                <th class="col col-md-1">Action</th>
                <th>Code</th>
                <th>Title</th>
                <th>Classification</th>
                <th class="col col-md-1">Due Date</th>
                <th>Asignee</th>
                <th>Progress</th>
              </tr>
            </tfoot>
            <tbody id='dataTableBody'>
              <?php $con->next_result();
              $result = mysqli_query($con, "SELECT DISTINCT tasks_details.*, accounts.file_name, tasks.task_details, section.dept_id FROM tasks_details JOIN accounts ON tasks_details.in_charge = accounts.username JOIN tasks ON tasks_details.task_name = tasks.task_name JOIN section ON tasks_details.task_for = section.sec_id WHERE task_status=1 AND MONTH(due_date) = MONTH(CURRENT_DATE) AND YEAR(due_date) = YEAR(CURRENT_DATE) AND section.dept_id='$dept_id'");
              if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                  if (empty($row['file_name'])) {
                    $imageURL = '../assets/img/user-profiles/nologo.png';
                  } else {
                    $imageURL = '../assets/img/user-profiles/' . $row['file_name'];
                  }
                  $task_classes = [1 => ['name' => 'DAILY ROUTINE', 'badge' => 'info'], 2 => ['name' => 'WEEKLY ROUTINE', 'badge' => 'info'], 3 => ['name' => 'MONTHLY ROUTINE', 'badge' => 'info'], 4 => ['name' => 'ADDITIONAL TASK', 'badge' => 'info'], 5 => ['name' => 'PROJECT', 'badge' => 'info'], 6 => ['name' => 'MONTHLY REPORT', 'badge' => 'danger']];
                  if (isset($task_classes[$row['task_class']])) {
                    $class = $task_classes[$row['task_class']]['name'];
                    $badge = $task_classes[$row['task_class']]['badge'];
                  } else {
                    $class = 'Unknown';
                    $badge = 'secondary';
                  }
                  $task_class = '<span class="badge badge-' . $badge . '">' . $class . '</span>';
                  $due_date  = date_format(date_create($row['due_date']), "Y-m-d h:i a");
                  $assignee  = '<img src=' . $imageURL . ' class="border border-primary img-table-solo">';
                  $status_badges = [
                    'NOT YET STARTED' => 'primary',
                    'IN PROGRESS' => 'warning',
                    'REVIEW' => 'danger',
                    'FINISHED' => 'success',
                    'RESCHEDULE' => 'secondary'
                  ];
                  $progress = '<span class="badge badge-' . $status_badges[$row['status']] . '">' . $row['status'] . '</span>';
              ?>
                  <tr>
                    <td><button type="button" onclick="viewTask(this)" class="btn btn-warning btn-circle" value="<?php echo $row['id'] ?>" data-name="<?php echo $row['task_name'] ?>"><i class="fas fa-eye"></i></button></td>
                    <td>
                      <center /><?php echo $row['task_code'] ?>
                    </td>
                    <td><?php echo $row['task_name'] ?> <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="<?php echo $row['task_details'] ?>"></i></td>
                    <td><?php echo $task_class ?></td>
                    <td><?php echo $due_date ?></td>
                    <td data-toggle="tooltip" data-placement="top" title="<?php echo $row['in_charge'] ?>">
                      <center /><?php echo $assignee ?>
                    </td>
                    <td><?php echo $progress ?></td>
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

<div class="modal fade" id="start" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-success">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Start Task</h5>
      </div>
      <div class="modal-body text-center">
        <input type="hidden" id="taskID">
        <i class="fas fa-question fa-5x text-success"></i>
        <br><br>
        Do you want to start this task?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" data-dismiss="modal" id="confirmButton">Confirm</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="finish" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content border-danger">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Finish Task</h5>
      </div>
      <form id="submitDetails" enctype="multipart/form-data">
        <div class="modal-body text-center" id="finishDetails">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success" data-dismiss="modal" id="submitTask">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="view" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content border-primary">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">View Task</h5>
      </div>
      <div class="modal-body" id="taskDetails">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="re-view" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content border-warning">
      <div class="modal-header bg-warning text-white">
        <h5 class="modal-title">Review Task</h5>
      </div>
      <div class="modal-body" id="reviewDetails">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="button" onclick="updateDetails(this)" class="btn btn-success" id="updateButton">Update</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="edit" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content border-info">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">Edit Task</h5>
      </div>
      <div class="modal-body" id="editDetails">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="button" onclick="updateTask(this)" class="btn btn-success" id="updateButton">Update</button>
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
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="delete_id">Proceed</button>
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
      [4, "desc"],
      [2, "asc"]
    ],
    "pageLength": 5,
    "lengthMenu": [5, 10, 25, 50, 100],
    "drawCallback": function(settings) {
      $('[data-toggle="tooltip"]').tooltip();
    }
  });

  function filterTable() {
    var date_to = document.getElementById('date_to').value;
    var date_from = document.getElementById('date_from').value;
    var department = <?php echo json_encode($dept_id) ?>;
    var section = document.getElementById('section').value;
    var progress = document.getElementById('progress').value;
    if (section.value === '') {
      section.value = null;
    }
    if (progress.value === '') {
      progress.value = null;
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
      url: "../config/tasks.php",
      data: {
        "filterTable": true,
        "date_to": date_to,
        "date_from": date_from,
        "department": department,
        "section": section,
        "progress": progress,
      },
      success: function(response) {
        $('#dataTableBody').append(response);
        $('#dataTable').DataTable({
          "order": [
            [4, "desc"],
            [2, "asc"]
          ],
          "pageLength": 5,
          "lengthMenu": [5, 10, 25, 50, 100],
          "drawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
          }
        });
      }
    });
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
        $('#taskDetails').html(response);
        $('#view').modal('show');
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

  function editTask(element) {
    var taskID = element.value;
    $.ajax({
      method: "POST",
      url: "../config/tasks.php",
      data: {
        "editTask": true,
        "taskID": taskID,
      },
      success: function(response) {
        document.getElementById('updateButton').value = taskID;
        $('#editDetails').html(response);
        $('#edit').modal('show');
      }
    });
  }

  function updateTask(element) {
    element.disabled = true;
    var taskID    = document.getElementById('taskDetailsID').value;
    var progress  = document.getElementById('update_progress').value;
    var datetime  = document.getElementById('update_datetime').value;
    var status    = document.getElementById('update_status').value;
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
          $('#edit').modal('hide');
          $('#success').modal('show');
        } else {
          document.getElementById('error_found').innerHTML = response;
          $('#error').modal('show');
          element.disabled = false;
        }
      }
    });
  }

  function startTask(element) {
    var id = element.value;
    $('#taskID').val(id);
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
      console.log(formData);
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
        $('#view').modal('show');
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
        document.getElementById("updateButton").disabled = true;
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

        function showUpdateButton() {
          document.getElementById("updateButton").disabled = false;
        }
        document.getElementById('taskReview_remarks').addEventListener('input', showUpdateButton);
        document.getElementById('taskReview_upload').addEventListener('change', showUpdateButton);
      }
    });
  }

  function updateDetails(element) {
    element.disabled = true;
    var formDetails = new FormData(document.getElementById('editDetails'));
    formDetails.append('updateDetails', true);
    console.log(formDetails);
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

  function getCheckedValue(element) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"][name="selected_ids[]"]');
    var checkedIds = [];
    checkboxes.forEach(function(checkbox) {
      if (checkbox.checked) {
        checkedIds.push(checkbox.value);
      }
    });
    $('#start').modal('show');
    $('#confirmButton').off('click').on('click', function() {
      $.ajax({
        url: '../config/tasks.php',
        method: 'POST',
        data: {
          "startTaskMultiple": true,
          "checkedIds": checkedIds
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

  function filterTableTask(element) {
    var date_to = $('#date_to').val();
    var date_from = $('#date_from').val();
    var progress = localStorage.getItem('activeTab').replace('#', '').toUpperCase();
    var currentTab = progress.charAt(0).toUpperCase() + progress.slice(1).toLowerCase();
    console.log(date_to, date_from, progress, currentTab);
    $('#myTasksTable' + currentTab).DataTable().destroy();
    $('#myTasks' + currentTab).empty();
    $.ajax({
      method: "POST",
      url: "../config/tasks.php",
      data: {
        "filterTableTask": true,
        "date_to": date_to,
        "date_from": date_from,
        "progress": progress,
      },
      success: function(response) {
        $('#myTasks' + currentTab).append(response);
        if (currentTab === 'Todo') {
          $('#myTasksTable' + currentTab).DataTable({
            "order": [
              [4, "asc"],
              [2, "asc"]
            ],
            "pageLength": 5,
            "lengthMenu": [5, 10, 25, 50, 100],
            "drawCallback": function(settings) {
              $('[data-toggle="tooltip"]').tooltip();
            }
          });
        } else {
          $('#myTasksTable' + currentTab).DataTable({
            "order": [
              [3, "desc"],
              [1, "asc"]
            ],
            "pageLength": 5,
            "lengthMenu": [5, 10, 25, 50, 100],
            "drawCallback": function(settings) {
              $('[data-toggle="tooltip"]').tooltip();
            }
          });
        }
      }
    });
  }

  function resetFileInput() {
    const fileInput = document.getElementById('file-1');
    if (typeof fileInput !== 'undefined' && fileInput !== null) {
      fileInput.value = '';
    }

    const fileInput2 = document.getElementById('taskReview_upload');
    if (typeof fileInput2 !== 'undefined' && fileInput2 !== null) {
      fileInput2.value = '';
    }
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

    function initializeDataTable(tableId) {
      if (tableId === 'myTasksTableTodo') {
        $('#' + tableId).DataTable({
          "order": [
            [4, "asc"],
            [2, "asc"]
          ],
          pageLength: 5,
          lengthMenu: [5, 10, 25, 50, 100],
          "drawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
          }
        });
      } else {
        $('#' + tableId).DataTable({
          "order": [
            [3, "desc"],
            [1, "asc"]
          ],
          pageLength: 5,
          lengthMenu: [5, 10, 25, 50, 100],
          "drawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
          }
        });
      }
    }

    $('#myTabs a').on('shown.bs.tab', function(e) {
      var href = $(e.target).attr('href');
      localStorage.setItem('activeTab', href);

      var tableId = $(href).find('table').attr('id');
      if (tableId && !$.fn.DataTable.isDataTable('#' + tableId)) {
        initializeDataTable(tableId);
      }
    });

    var initialTableId = $('.tab-pane.show.active').find('table').attr('id');
    if (initialTableId && !$.fn.DataTable.isDataTable('#' + initialTableId)) {
      initializeDataTable(initialTableId);
    }

    document.getElementById('selectAll').addEventListener('click', function() {
      var checkboxes = document.querySelectorAll('input[type="checkbox"][name="selected_ids[]"]');
      var actionButton = document.getElementById('actionButton');
      checkboxes.forEach(function(checkbox) {
        if (checkbox.value !== "" && !checkbox.disabled) {
          checkbox.checked = document.getElementById('selectAll').checked;
        }
      });
      actionButton.style.display = checkboxesChecked() ? 'block' : 'none';
    });

    function checkboxesChecked() {
      var checkboxes = document.querySelectorAll('input[type="checkbox"][name="selected_ids[]"]');
      for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
          return true;
        }
      }
      return false;
    }

  });
</script>