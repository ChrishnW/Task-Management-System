<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <?php if ($access == 1) {
    $con->next_result();
    $today = date('Y-m-d 16:00:00');
    $query_result = mysqli_query($con, "SELECT COUNT(id) as total_tasks, (SELECT COUNT(id) FROM accounts WHERE status=1) as all_accounts, (SELECT COUNT(id) FROM tasks_details WHERE status='PROJECT' AND task_status=1) as project_tasks FROM tasks_details WHERE task_status=1");
    $row = mysqli_fetch_assoc($query_result);
    $total_tasks      = $row['total_tasks'];
    $project_tasks    = $row['project_tasks'];
    $all_accounts     = $row['all_accounts']; ?>
    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>
    <div class="row">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">System Usage</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><i class="fas fa-database fa-fw"></i> <?php echo $db_size ?></div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><i class="fas fa-hdd fa-fw"></i> <?php echo $projectSize ?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-server fa-3x text-gray-500"></i>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col"><a href="#" data-toggle="modal" data-target="#systemInfo" class="btn btn-success btn-sm">More Info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Deployed Tasks</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_tasks ?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-calendar-day fa-3x text-gray-500"></i>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col"><a href="tasks.php" class="btn btn-primary btn-sm">More Info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Projects</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $project_tasks ?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-project-diagram fa-3x text-gray-500"></i>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col">
                <a href="404.php" class="btn btn-danger btn-sm">More Info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Registered Accounts</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $all_accounts ?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-users fa-3x text-gray-500"></i>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col"><a href="accounts.php" class="btn btn-info btn-sm">More Info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-4">
        <div class="card border-left-primary shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Calendar of <?php echo date('F Y') ?></h6>
          </div>
          <div class="card-body table-responsive">
            <table class="calendar table table-borderless">
              <thead>
                <tr>
                  <th>Sun</th>
                  <th>Mon</th>
                  <th>Tue</th>
                  <th>Wed</th>
                  <th>Thu</th>
                  <th>Fri</th>
                  <th>Sat</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $query_result = mysqli_query($con, "SELECT * FROM day_off WHERE status=1");
                $specialDates = [];
                if ($query_result->num_rows > 0) {
                  while ($row = $query_result->fetch_assoc()) {
                    $specialDates[] = $row['date_off'];
                    $remarks        = $row['remarks'];
                  }
                }

                $currentYear = date("Y");
                $currentMonth = date("m");
                $currentDay = date("d");
                $firstDayOfMonth = mktime(0, 0, 0, $currentMonth, 1, $currentYear);
                $daysInMonth = date("t", $firstDayOfMonth);
                $dateComponents = getdate($firstDayOfMonth);
                $monthName = $dateComponents['month'];
                $dayOfWeek = $dateComponents['wday'];

                $calendar = "<tr>";

                for ($i = 0; $i < $dayOfWeek; $i++) {
                  $calendar .= "<td></td>";
                }

                $currentDayCount = 1;
                while ($currentDayCount <= $daysInMonth) {
                  if ($dayOfWeek == 7) {
                    $dayOfWeek = 0;
                    $calendar .= "</tr><tr>";
                  }

                  $currentDate = "$currentYear-$currentMonth-" . str_pad($currentDayCount, 2, "0", STR_PAD_LEFT);

                  $class = ($dayOfWeek == 0) ? 'sunday' : ''; // Apply 'sunday' class if it's Sunday

                  if ($currentDayCount == $currentDay) {
                    $calendar .= "<td class='today $class' data-toggle='tooltip' data-placement='top' title='Today'>$currentDayCount</td>";
                  } elseif (in_array($currentDate, $specialDates)) {
                    $calendar .= "<td class='special $class' data-toggle='tooltip' data-placement='top' title='$remarks'>$currentDayCount</td>";
                  } else {
                    $calendar .= "<td class='$class'>$currentDayCount</td>";
                  }

                  $currentDayCount++;
                  $dayOfWeek++;
                }

                while ($dayOfWeek != 7) {
                  $calendar .= "<td></td>";
                  $dayOfWeek++;
                }

                $calendar .= "</tr>";
                echo $calendar;
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-xl-5">
        <div class="card border-left-info shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">Upcoming Reports</h6>
          </div>
          <div class="card-body scrollable-card-body">
            <?php
            $con->next_result();
            $today = date('Y-m-d 16:00:00');
            $query_result = mysqli_query($con, "SELECT * FROM tasks_details WHERE task_status=1 AND in_charge='$username' AND due_date!='$today' AND status='NOT YET STARTED' ORDER BY due_date ASC");
            if (mysqli_num_rows($query_result) > 0) {
              while ($row = $query_result->fetch_assoc()) {
                $currentDate = new DateTime();
                $dueDate     = new DateTime($row['due_date']);
                $interval    = $currentDate->diff($dueDate);
                if ($currentDate < $dueDate) {
                  if ($interval->days > 0 && $interval->days >= 1) {
                    $remainingTime = $interval->days . ' days remaining';
                  } elseif ($interval->days == 0) {
                    $remainingTime = $interval->h . ' hours and ' . $interval->i . ' minutes remaining';
                  }
                } else {
                  if ($interval->days > 0 && $interval->days >= 1) {
                    $remainingTime = $interval->days . ' days overdue';
                  } elseif ($interval->days == 0) {
                    $remainingTime = $interval->h . ' hours and ' . $interval->i . ' minutes overdue';
                  }
                }
                $border         = array('primary', 'danger', 'info', 'success');
                $randomBorder   = array_rand($border);
                $selectBorder   = $border[$randomBorder];
                $due_date_temp  = date_create($row['due_date']);
                $due_date       = date_format($due_date_temp, "jS \of F Y");
                $due_day        = date_format($due_date_temp, "l"); ?>
                <div class="card mb-4 py-3 border-bottom-<?php echo $selectBorder ?>">
                  <div class="card-body custom-card">
                    <div class="left-content text-<?php echo $selectBorder ?>">
                      <div class="display-8 font-weight-bold"><?php echo $due_date ?></div>
                      <div class="font-weight-bold"><?php echo $due_day ?></div>
                    </div>
                    <div class="middle-content text-center">
                      <div class="font-weight-bold display-7"><?php echo $row['task_name'] ?></div>
                      <div class="text-<?php echo $selectBorder ?> font-weight-bold">Monthly Report</div>
                    </div>
                    <div class="right-content text-center">
                      <i class="far fa-clock fa-spin display-5"></i>
                      <h6 class="font-weight-bold text-<?php echo $selectBorder ?>"><?php echo $remainingTime ?></h6>
                    </div>
                  </div>
                </div>
              <?php }
            } else { ?>
              <div class="card-body text-center">
                No scheduled monthly report.
              </div>
            <?php } ?>
          </div>
        </div>

        <div class="card border-left-danger shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Project Progression</h6>
          </div>
          <div class="card-body scrollable-card-body">
            <?php
            $con->next_result();
            $query_result = mysqli_query($con, "SELECT * FROM tasks_details WHERE status='PROJECT' AND task_status=1 AND in_charge='$username'");
            if (mysqli_num_rows($query_result) > 0) {
            } else { ?>
              <div class="card-body text-center">
                No current in-progress project.
              </div>
            <?php } ?>
          </div>
        </div>
      </div>

      <div class="col-xl-3">
        <div class="card border-left-secondary shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-secondary">Rating Criteria for Task and Reports</h6>
          </div>
          <div class="card-body scrollable-card-body-md">
            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Rating 5: 105% Achievement</h6>
              </div>
              <div class="card-body display-9 font-weight-bolder">
                <p><b>Excellent:</b> The task was completed to an outstanding standard. The result far exceeds expectations, showing exceptional quality, thoroughness, and skill.</p>
              </div>
            </div>
            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Rating 4: 100% Achievement</h6>
              </div>
              <div class="card-body display-9 font-weight-bolder">
                <p><b>Good:</b> The task was completed well, with only minor issues. The result meets and occasionally exceeds expectations, showing a high level of competence and quality.</p>
              </div>
            </div>
            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Rating 3: 90% Achievement</h6>
              </div>
              <div class="card-body display-9 font-weight-bolder">
                <p><b>Satisfactory:</b> The task was completed to an acceptable standard. The result meets the basic requirements and expectations, but there is room for improvement in certain areas.</p>
              </div>
            </div>
            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Rating 2: 80% Achievement</h6>
              </div>
              <div class="card-body display-9 font-weight-bolder">
                <p><b>Fair:</b> The task was completed but with noticeable issues. The result meets some but not all expectations, and there is a need for improvement in several areas.</p>
              </div>
            </div>
            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Rating 1: 70% Achievement</h6>
              </div>
              <div class="card-body display-9 font-weight-bolder">
                <p><b>Poor:</b> The task was not completed or was done incorrectly. The result is far below the expected standard, and significant improvement is needed.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php } elseif ($access == 2) {
    $con->next_result();
    $today = date('Y-m-d');
    $query_result = mysqli_query($con, "SELECT COUNT(id) as task_today, (SELECT COUNT(id) FROM tasks WHERE in_charge='$username' AND task_class!=4) as assigned_task, (SELECT COUNT(id) FROM tasks WHERE in_charge='$username' AND task_class=5) as total_project, (SELECT COUNT(id) FROM tasks_details WHERE in_charge='$username' AND task_status=1 AND status='IN PROGRESS' AND MONTH(due_date)='$currentMonth' AND YEAR(due_date)='$currentYear') as task_inprogress FROM tasks_details WHERE in_charge='$username' AND task_status=1 AND status='NOT YET STARTED' AND MONTH(due_date)='$currentMonth' AND YEAR(due_date)='$currentYear'");
    $row = mysqli_fetch_assoc($query_result);
    $task_today       = $row['task_today'] + $row['task_inprogress'];
    $assigned_task    = $row['assigned_task'];
    $total_project    = $row['total_project'] ?>

    <!-- <h1 class="h3 mb-4 text-gray-800">Dashboard</h1> -->
    <div class="row">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tasks Today</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $task_today ?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-calendar-day fa-3x text-gray-500"></i>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col"><a href="javascript:void(0);" onclick="localStorage.setItem('activeTab', '#todo');window.location.href='tasks.php';" class="btn btn-primary btn-sm">More Info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Assigned Tasks</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $assigned_task ?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-list fa-3x text-gray-500"></i>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col"><a href="assign_tasks.php" class="btn btn-info btn-sm">More Info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Projects</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_project ?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-project-diagram fa-3x text-gray-500"></i>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col">
                <a href="404.php" class="btn btn-danger btn-sm">More Info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">My Files</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $file_counter ?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-paperclip fa-3x text-gray-500"></i>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col"><a href="files.php" class="btn btn-success btn-sm">More Info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Calendar -->
      <div class="col-xl-4">
        <div class="card shadow mb-4 h-75">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Calendar of <?php echo date('F Y') ?></h6>
          </div>
          <div class="card-body table-responsive p-0">
            <table class="calendar table table-borderless">
              <thead>
                <tr>
                  <th>Sun</th>
                  <th>Mon</th>
                  <th>Tue</th>
                  <th>Wed</th>
                  <th>Thu</th>
                  <th>Fri</th>
                  <th>Sat</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $query_result = mysqli_query($con, "SELECT * FROM day_off WHERE status=1");
                $specialDates = [];
                if ($query_result->num_rows > 0) {
                  while ($row = $query_result->fetch_assoc()) {
                    $specialDates[] = $row['date_off'];
                    $remarks        = $row['remarks'];
                  }
                }

                $currentYear = date("Y");
                $currentMonth = date("m");
                $currentDay = date("d");
                $firstDayOfMonth = mktime(0, 0, 0, $currentMonth, 1, $currentYear);
                $daysInMonth = date("t", $firstDayOfMonth);
                $dateComponents = getdate($firstDayOfMonth);
                $monthName = $dateComponents['month'];
                $dayOfWeek = $dateComponents['wday'];

                $calendar = "<tr>";

                for ($i = 0; $i < $dayOfWeek; $i++) {
                  $calendar .= "<td></td>";
                }

                $currentDayCount = 1;
                while ($currentDayCount <= $daysInMonth) {
                  if ($dayOfWeek == 7) {
                    $dayOfWeek = 0;
                    $calendar .= "</tr><tr>";
                  }

                  $currentDate = "$currentYear-$currentMonth-" . str_pad($currentDayCount, 2, "0", STR_PAD_LEFT);

                  $class = ($dayOfWeek == 0) ? 'sunday' : ''; // Apply 'sunday' class if it's Sunday

                  if ($currentDayCount == $currentDay) {
                    $calendar .= "<td class='today $class' data-toggle='tooltip' data-placement='top' title='Today'>$currentDayCount</td>";
                  } elseif (in_array($currentDate, $specialDates)) {
                    $calendar .= "<td class='special $class' data-toggle='tooltip' data-placement='top' title='$remarks'>$currentDayCount</td>";
                  } else {
                    $calendar .= "<td class='$class'>$currentDayCount</td>";
                  }

                  $currentDayCount++;
                  $dayOfWeek++;
                }

                while ($dayOfWeek != 7) {
                  $calendar .= "<td></td>";
                  $dayOfWeek++;
                }

                $calendar .= "</tr>";
                echo $calendar;
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- Reports -->
      <div class="col-xl-5">
        <div class="card shadow mb-4 h-75">
          <div class="card-header m-0 font-weight-bolder text-primary">Upcoming Report</div>
          <?php
          $con->next_result();
          $today = date('Y-m-d 16:00:00');
          $query_result = mysqli_query($con, "SELECT DISTINCT td.task_name, tl.task_details, td.due_date, s.sec_name, s.dept_id FROM tasks_details td JOIN task_list tl ON tl.task_name=td.task_name JOIN section s ON s.sec_id=td.task_for WHERE td.task_class=6 AND td.in_charge='$username' AND td.date_accomplished IS NULL ORDER BY td.due_date ASC");
          if (mysqli_num_rows($query_result) > 0) { ?>
            <div class="card-body scrollable-card-body-md">
              <?php while ($row = $query_result->fetch_assoc()) {
                $currentDate = new DateTime();
                $dueDate     = new DateTime($row['due_date']);
                $interval    = $currentDate->diff($dueDate);
                if ($currentDate < $dueDate) {
                  if ($interval->days > 0 && $interval->days >= 1) {
                    $remainingTime = $interval->days . ' days remaining';
                  } elseif ($interval->days == 0) {
                    $remainingTime = $interval->h . ' hours and ' . $interval->i . ' minutes remaining';
                  }
                } else {
                  if ($interval->days > 0 && $interval->days >= 1) {
                    $remainingTime = $interval->days . ' days overdue';
                  } elseif ($interval->days == 0) {
                    $remainingTime = $interval->h . ' hours and ' . $interval->i . ' minutes overdue';
                  }
                }
                $randomColor    = $color[array_rand($color = array('primary', 'danger', 'info', 'success'))];
                $due_date_temp  = date_create($row['due_date']);
                $due_date       = date_format($due_date_temp, "jS \of F Y");
                $due_day        = date_format($due_date_temp, "l"); ?>
                <div class="card shadow mb-4">
                  <div class="card-header py-3 bg-<?php echo $randomColor ?> text-white">
                    <h6 class="m-0 font-weight-bold"><?php echo $row['task_name']; ?></h6>
                  </div>
                  <div class="card-body">
                    <p class="card-text"><strong>Description:</strong> <?php echo $row['task_details']; ?></p>
                    <p class="card-text"><strong>Due Date:</strong> <?php echo $due_date ?></p>
                    <p class="card-text" id="daysRemaining"><strong>Days Remaining:</strong> <?php echo $remainingTime; ?></p>
                  </div>
                </div>
              <?php } ?>
            </div>
          <?php } else { ?>
            <div class="card-body d-flex justify-content-center align-items-center bg-nodata-image"></div>
          <?php } ?>
        </div>
      </div>
      <!-- Criteria -->
      <div class="col-xl-3">
        <div class="card shadow mb-4 h-75">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Rating Criteria for Task and Reports</h6>
          </div>
          <div class="card-body scrollable-card-body-md">
            <div class="card shadow mb-4">
              <div class="card-header bg-success text-white py-3">
                <h6 class="m-0 font-weight-bold">Rating 5: 105% Achievement</h6>
              </div>
              <div class="card-body display-9 font-weight-bolder">
                <p><b class="text-success">Excellent:</b> The task was completed to an outstanding standard. The result far exceeds expectations, showing exceptional quality, thoroughness, and skill.</p>
              </div>
            </div>
            <div class="card shadow mb-4">
              <div class="card-header bg-info text-white py-3">
                <h6 class="m-0 font-weight-bold">Rating 4: 100% Achievement</h6>
              </div>
              <div class="card-body display-9 font-weight-bolder">
                <p><b class="text-info">Good:</b> The task was completed well, with only minor issues. The result meets and occasionally exceeds expectations, showing a high level of competence and quality.</p>
              </div>
            </div>
            <div class="card shadow mb-4">
              <div class="card-header bg-primary text-white py-3">
                <h6 class="m-0 font-weight-bold">Rating 3: 90% Achievement</h6>
              </div>
              <div class="card-body display-9 font-weight-bolder">
                <p><b class="text-primary">Satisfactory:</b> The task was completed to an acceptable standard. The result meets the basic requirements and expectations, but there is room for improvement in certain areas.</p>
              </div>
            </div>
            <div class="card shadow mb-4">
              <div class="card-header bg-warning text-white py-3">
                <h6 class="m-0 font-weight-bold">Rating 2: 80% Achievement</h6>
              </div>
              <div class="card-body display-9 font-weight-bolder">
                <p><b class="text-warning">Fair:</b> The task was completed but with noticeable issues. The result meets some but not all expectations, and there is a need for improvement in several areas.</p>
              </div>
            </div>
            <div class="card shadow mb-4">
              <div class="card-header bg-danger text-white py-3">
                <h6 class="m-0 font-weight-bold">Rating 1: 70% Achievement</h6>
              </div>
              <div class="card-body display-9 font-weight-bolder">
                <p><b class="text-danger">Poor:</b> The task was not completed or was done incorrectly. The result is far below the expected standard, and significant improvement is needed.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php } elseif ($access == 3) { ?>
    <div class="row">
      <div class="col-md-9 col-auto">
        <h2 class="mb-2 font-weight-bolder">Dashboard</h2>
        <h5 class="mb-3 font-weight-light display-8">Here’s what’s going on at your department right now</h5>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-3 p-0">
        <div class="col-lg-auto mb-4">
          <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                    Pending Review
                  </div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $for_review_tasks ?></div>
                </div>
                <div class="col-auto">
                  <a href="for_review.php"><i class="fas fa-tasks fa-2x text-gray-300"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-auto mb-4">
          <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                    Pending Reschedule
                  </div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $for_resched_tasks ?></div>
                </div>
                <div class="col-auto">
                  <a href="for_reschedule.php"><i class="fas fa-calendar-day fa-2x text-gray-300"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 p-0">
        <div class="col-lg-auto mb-4">
          <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                    Deployed Task
                  </div>
                  <div class="row no-gutters align-items-center">
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_tasks ?></div>
                  </div>
                </div>
                <div class="col-auto">
                  <a href="tasks.php"><i class="fas fa-clipboard-list fa-2x text-gray-300"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-auto mb-4">
          <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                    Members
                  </div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $members ?></div>
                </div>
                <div class="col-auto">
                  <a href="accounts.php"><i class="fas fa-users fa-2x text-gray-300"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 p-0">
        <div class="col-lg-auto mb-4">
          <div class="card shadow">
            <div class="card-header text-primary font-weight-bolder py-2">Task Completion</div>
            <div class="card-body">
              <div>
                <canvas id="myChart"></canvas>
              </div>
              <div class="mt-4 text-center small">
                <span class="mr-2">
                  <i class="fas fa-circle text-success"></i> Finished
                </span>
                <span class="mr-2">
                  <i class="fas fa-circle text-danger"></i> Not Finished
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 p-0 position-relative">
        <div class="col-lg-auto mb-4">
          <div class="card shadow">
            <div class="card-header text-primary font-weight-bolder py-2">Projects</div>
            <div class="card-body blur-body scrollable-card-body">
              <div class="blur-overlay">
                <h2 class="blur-text">Under Development</h2>
              </div>
              <h4 class="small font-weight-bold">Server Migration <span class="float-right">20%</span></h4>
              <div class="progress mb-4">
                <div class="progress-bar bg-danger" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <h4 class="small font-weight-bold">Sales Tracking <span class="float-right">40%</span></h4>
              <div class="progress mb-4">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <h4 class="small font-weight-bold">Customer Database <span class="float-right">60%</span></h4>
              <div class="progress mb-4">
                <div class="progress-bar" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <h4 class="small font-weight-bold">Payout Details <span class="float-right">80%</span></h4>
              <div class="progress mb-4">
                <div class="progress-bar bg-info" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <h4 class="small font-weight-bold">Account Setup <span class="float-right">Complete!</span></h4>
              <div class="progress">
                <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <!-- Calendar -->
      <div class="col-xl-4">
        <div class="card shadow mb-4 h-75">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Calendar of <?php echo date('F Y') ?></h6>
          </div>
          <div class="card-body table-responsive p-0">
            <table class="calendar table table-borderless">
              <thead>
                <tr>
                  <th>Sun</th>
                  <th>Mon</th>
                  <th>Tue</th>
                  <th>Wed</th>
                  <th>Thu</th>
                  <th>Fri</th>
                  <th>Sat</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $query_result = mysqli_query($con, "SELECT * FROM day_off WHERE status=1");
                $specialDates = [];
                if ($query_result->num_rows > 0) {
                  while ($row = $query_result->fetch_assoc()) {
                    $specialDates[] = $row['date_off'];
                    $remarks        = $row['remarks'];
                  }
                }

                $currentYear = date("Y");
                $currentMonth = date("m");
                $currentDay = date("d");
                $firstDayOfMonth = mktime(0, 0, 0, $currentMonth, 1, $currentYear);
                $daysInMonth = date("t", $firstDayOfMonth);
                $dateComponents = getdate($firstDayOfMonth);
                $monthName = $dateComponents['month'];
                $dayOfWeek = $dateComponents['wday'];

                $calendar = "<tr>";

                for ($i = 0; $i < $dayOfWeek; $i++) {
                  $calendar .= "<td></td>";
                }

                $currentDayCount = 1;
                while ($currentDayCount <= $daysInMonth) {
                  if ($dayOfWeek == 7) {
                    $dayOfWeek = 0;
                    $calendar .= "</tr><tr>";
                  }

                  $currentDate = "$currentYear-$currentMonth-" . str_pad($currentDayCount, 2, "0", STR_PAD_LEFT);

                  $class = ($dayOfWeek == 0) ? 'sunday' : ''; // Apply 'sunday' class if it's Sunday

                  if ($currentDayCount == $currentDay) {
                    $calendar .= "<td class='today $class' data-toggle='tooltip' data-placement='top' title='Today'>$currentDayCount</td>";
                  } elseif (in_array($currentDate, $specialDates)) {
                    $calendar .= "<td class='special $class' data-toggle='tooltip' data-placement='top' title='$remarks'>$currentDayCount</td>";
                  } else {
                    $calendar .= "<td class='$class'>$currentDayCount</td>";
                  }

                  $currentDayCount++;
                  $dayOfWeek++;
                }

                while ($dayOfWeek != 7) {
                  $calendar .= "<td></td>";
                  $dayOfWeek++;
                }

                $calendar .= "</tr>";
                echo $calendar;
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- Reports -->
      <div class="col-xl-5">
        <div class="card shadow mb-4 h-75">
          <div class="card-header m-0 font-weight-bolder text-primary">Upcoming Report</div>
          <?php
          $con->next_result();
          $today = date('Y-m-d 16:00:00');
          $query_result = mysqli_query($con, "SELECT DISTINCT td.task_name, tl.task_details, td.due_date, s.sec_name, s.dept_id FROM tasks_details td JOIN task_list tl ON tl.task_name=td.task_name JOIN section s ON s.sec_id=td.task_for WHERE td.task_class=6 AND dept_id='$dept_id' AND td.date_accomplished IS NULL ORDER BY td.due_date ASC");
          if (mysqli_num_rows($query_result) > 0) { ?>
            <div class="card-body scrollable-card-body-md">
              <?php while ($row = $query_result->fetch_assoc()) {
                $currentDate = new DateTime();
                $dueDate     = new DateTime($row['due_date']);
                $interval    = $currentDate->diff($dueDate);
                if ($currentDate < $dueDate) {
                  if ($interval->days > 0 && $interval->days >= 1) {
                    $remainingTime = $interval->days . ' days remaining';
                  } elseif ($interval->days == 0) {
                    $remainingTime = $interval->h . ' hours and ' . $interval->i . ' minutes remaining';
                  }
                } else {
                  if ($interval->days > 0 && $interval->days >= 1) {
                    $remainingTime = $interval->days . ' days overdue';
                  } elseif ($interval->days == 0) {
                    $remainingTime = $interval->h . ' hours and ' . $interval->i . ' minutes overdue';
                  }
                }
                $randomColor    = $color[array_rand($color = array('primary', 'danger', 'info', 'success'))];
                $due_date_temp  = date_create($row['due_date']);
                $due_date       = date_format($due_date_temp, "jS \of F Y");
                $due_day        = date_format($due_date_temp, "l"); ?>
                <div class="card shadow mb-4">
                  <div class="card-header py-3 bg-<?php echo $randomColor ?> text-white">
                    <h6 class="m-0 font-weight-bold"><?php echo $row['task_name']; ?></h6>
                  </div>
                  <div class="card-body">
                    <p class="card-text"><strong>Description:</strong> <?php echo $row['task_details']; ?></p>
                    <p class="card-text"><strong>Section:</strong> <?php echo ucwords(strtolower($row['sec_name'])) ?></p>
                    <p class="card-text"><strong>Due Date:</strong> <?php echo $due_date ?></p>
                    <p class="card-text" id="daysRemaining"><strong>Days Remaining:</strong> <?php echo $remainingTime; ?></p>
                  </div>
                </div>
              <?php } ?>
            </div>
          <?php } else { ?>
            <div class="card-body d-flex justify-content-center align-items-center bg-nodata-image"></div>
          <?php } ?>
        </div>
      </div>
      <!-- Criteria -->
      <div class="col-xl-3">
        <div class="card shadow mb-4 h-75">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Rating Criteria for Task and Reports</h6>
          </div>
          <div class="card-body scrollable-card-body-md">
            <div class="card shadow mb-4">
              <div class="card-header bg-success text-white py-3">
                <h6 class="m-0 font-weight-bold">Rating 5: 105% Achievement</h6>
              </div>
              <div class="card-body display-9 font-weight-bolder">
                <p><b class="text-success">Excellent:</b> The task was completed to an outstanding standard. The result far exceeds expectations, showing exceptional quality, thoroughness, and skill.</p>
              </div>
            </div>
            <div class="card shadow mb-4">
              <div class="card-header bg-info text-white py-3">
                <h6 class="m-0 font-weight-bold">Rating 4: 100% Achievement</h6>
              </div>
              <div class="card-body display-9 font-weight-bolder">
                <p><b class="text-info">Good:</b> The task was completed well, with only minor issues. The result meets and occasionally exceeds expectations, showing a high level of competence and quality.</p>
              </div>
            </div>
            <div class="card shadow mb-4">
              <div class="card-header bg-primary text-white py-3">
                <h6 class="m-0 font-weight-bold">Rating 3: 90% Achievement</h6>
              </div>
              <div class="card-body display-9 font-weight-bolder">
                <p><b class="text-primary">Satisfactory:</b> The task was completed to an acceptable standard. The result meets the basic requirements and expectations, but there is room for improvement in certain areas.</p>
              </div>
            </div>
            <div class="card shadow mb-4">
              <div class="card-header bg-warning text-white py-3">
                <h6 class="m-0 font-weight-bold">Rating 2: 80% Achievement</h6>
              </div>
              <div class="card-body display-9 font-weight-bolder">
                <p><b class="text-warning">Fair:</b> The task was completed but with noticeable issues. The result meets some but not all expectations, and there is a need for improvement in several areas.</p>
              </div>
            </div>
            <div class="card shadow mb-4">
              <div class="card-header bg-danger text-white py-3">
                <h6 class="m-0 font-weight-bold">Rating 1: 70% Achievement</h6>
              </div>
              <div class="card-body display-9 font-weight-bolder">
                <p><b class="text-danger">Poor:</b> The task was not completed or was done incorrectly. The result is far below the expected standard, and significant improvement is needed.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>
</div>

<div class="modal fade" id="systemInfo" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">System Usage</h5>
      </div>
      <div class="modal-body">
        <table class="table table-striped" id="systemTable" width="100%" cellspacing="0">
          <thead class='table table-primary'>
            <tr>
              <th>Table</th>
              <th>Size</th>
            </tr>
          </thead>
          <tbody id='dataTableBody'>
            <?php $query_info = mysqli_query($con, "SELECT table_name AS `Table`, (data_length + index_length) AS `Size (Bytes)` FROM information_schema.tables WHERE table_schema = '$db_database' ORDER BY (data_length + index_length) DESC");
            while ($row = $query_info->fetch_assoc()) { ?>
              <tr>
                <td><?php echo htmlspecialchars($row['Table']) ?></td>
                <td><?php echo htmlspecialchars(formatSize($row['Size (Bytes)'])) ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include('../include/footer.php'); ?>

<script>
  $(function() {
    $('[data-toggle="tooltip"]').tooltip();
  })

  $('#systemInfo').on('shown.bs.modal', function() {
    if (!$.fn.DataTable.isDataTable('#systemTable')) {
      $('#systemTable').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "order": [
          [1, "desc"],
          [0, "asc"]
        ],
        "pageLength": 5,
        "lengthMenu": [5, 10, 25, 50, 100]
      });
    }
  });

  <?php if ($access == 3) { ?>
    $(document).ready(function() {
      const ctx = document.getElementById('myChart');
      const myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: [
            'Not Finished',
            'Finished'
          ],
          datasets: [{
            data: [<?php echo $utasks ?>, <?php echo $ftasks; ?>],
            backgroundColor: [
              '#e74a3b',
              '#1cc88a'
            ],
            hoverOffset: 4
          }]
        },
        options: {
          maintainAspectRatio: false,
          tooltips: {
            titleFontColor: '#6e707e',
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: true,
            caretPadding: 10,
          },
          legend: {
            display: false
          },
          cutoutPercentage: 50,
        },
      });
    });
  <?php } ?>
</script>