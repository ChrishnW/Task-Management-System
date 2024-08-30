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
              <div class="col"><a href="#" class="btn btn-success btn-sm">More Info <i class="fas fa-arrow-circle-right"></i></a>
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
            <table class="calendar table table-bordered">
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

                  if ($currentDayCount == $currentDay) {
                    $calendar .= "<td class='today' data-toggle='tooltip' data-placement='top' title='Today'>$currentDayCount</td>";
                  } elseif (in_array($currentDate, $specialDates)) {
                    $calendar .= "<td class='special' data-toggle='tooltip' data-placement='top' title='$remarks'>$currentDayCount</td>";
                  } else {
                    $calendar .= "<td>$currentDayCount</td>";
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
                $dueDateTime = new DateTime($row['due_date']);
                $interval = $currentDate->diff($dueDateTime);
                if ($interval->days > 0) {
                  $remainingTime = $interval->format('%a days');
                } elseif ($interval->h > 0) {
                  $remainingTime = $interval->format('%h hours');
                } else {
                  $remainingTime = $interval->format('%i minutes');
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
    $query_result = mysqli_query($con, "SELECT COUNT(id) as task_today, (SELECT COUNT(id) FROM tasks WHERE in_charge='$username') as assigned_task, (SELECT COUNT(id) FROM tasks WHERE in_charge='$username' AND task_class=5) as total_project, (SELECT COUNT(id) FROM tasks_details WHERE in_charge='$username' AND task_status=1 AND status='IN PROGRESS' AND MONTH(due_date)='$currentMonth' AND YEAR(due_date)='$currentYear') as task_inprogress FROM tasks_details WHERE in_charge='$username' AND task_status=1 AND status='NOT YET STARTED' AND MONTH(due_date)='$currentMonth' AND YEAR(due_date)='$currentYear'");
    $row = mysqli_fetch_assoc($query_result);
    $task_today       = $row['task_today'] + $row['task_inprogress'];
    $assigned_task    = $row['assigned_task'];
    $total_project    = $row['total_project'] ?>

    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>
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
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Report Files</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $assigned_task ?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-paperclip fa-3x text-gray-500"></i>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col"><a href="assign_tasks.php" class="btn btn-success btn-sm">More Info <i class="fas fa-arrow-circle-right"></i></a>
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
            <table class="calendar table table-bordered">
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

                  if ($currentDayCount == $currentDay) {
                    $calendar .= "<td class='today' data-toggle='tooltip' data-placement='top' title='Today'>$currentDayCount</td>";
                  } elseif (in_array($currentDate, $specialDates)) {
                    $calendar .= "<td class='special' data-toggle='tooltip' data-placement='top' title='$remarks'>$currentDayCount</td>";
                  } else {
                    $calendar .= "<td>$currentDayCount</td>";
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
            $query_result = mysqli_query($con, "SELECT * FROM tasks_details WHERE task_status=1 AND task_class=6 AND in_charge='$username' AND due_date!='$today' AND status='NOT YET STARTED' ORDER BY due_date ASC");
            if (mysqli_num_rows($query_result) > 0) {
              while ($row = $query_result->fetch_assoc()) {
                $currentDate = new DateTime();
                $dueDateTime = new DateTime($row['due_date']);
                $interval = $currentDate->diff($dueDateTime);
                if ($interval->days > 0) {
                  $remainingTime = $interval->format('%a days');
                } elseif ($interval->h > 0) {
                  $remainingTime = $interval->format('%h hours');
                } else {
                  $remainingTime = $interval->format('%i minutes');
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
                      <i class="far fa-clock display-5"></i>
                      <h6 class="font-weight-bold text-<?php echo $selectBorder ?>"><?php echo $remainingTime ?></h6>
                    </div>
                  </div>
                </div>
              <?php }
            } else { ?>
              <div class="card-body text-center">
                No upcoming monthly report.
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
  <?php } elseif ($access == 3) {
    $con->next_result();
    $today = date('Y-m-d 16:00:00');
    $query_result = mysqli_query($con, "SELECT COUNT(tasks_details.id) as total_tasks, (SELECT COUNT(tasks_details.id) FROM tasks_details JOIN section ON section.sec_id=tasks_details.task_for WHERE tasks_details.task_status=1 AND tasks_details.status='REVIEW' AND section.dept_id='$dept_id') as for_review_tasks, (SELECT COUNT(tasks_details.id) FROM tasks_details JOIN section ON section.sec_id=tasks_details.task_for WHERE tasks_details.task_status=1 AND section.dept_id='$dept_id' AND tasks_details.status='PROJECT') as project_tasks, (SELECT COUNT('accounts.id') FROM accounts JOIN section ON section.sec_id=accounts.sec_id WHERE dept_id='$dept_id' AND access=2) as members FROM tasks_details JOIN section ON section.sec_id=tasks_details.task_for WHERE tasks_details.task_status=1 AND section.dept_id='$dept_id'");
    $row = mysqli_fetch_assoc($query_result);
    $total_tasks      = $row['total_tasks'];
    $project_tasks    = $row['project_tasks'];
    $for_review_tasks = $row['for_review_tasks'];
    $members          = $row['members']; ?>
    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>
    <div class="row">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Current Members</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $members ?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-users fa-3x text-gray-500"></i>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col"><a href="accounts.php" class="btn btn-success btn-sm">More Info <i class="fas fa-arrow-circle-right"></i></a>
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
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Review Tasks</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $for_review_tasks ?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-bell fa-3x text-gray-500"></i>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col"><a href="for_review.php" class="btn btn-danger btn-sm">More Info <i class="fas fa-arrow-circle-right"></i></a>
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
                <i class="fas fa-calendar-alt fa-3x text-gray-500"></i>
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
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Projects</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $project_tasks ?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-project-diagram fa-3x text-gray-500"></i>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col">
                <a href="404.php" class="btn btn-warning btn-sm">More Info <i class="fas fa-arrow-circle-right"></i></a>
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
            <table class="calendar table table-bordered">
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

                  if ($currentDayCount == $currentDay) {
                    $calendar .= "<td class='today' data-toggle='tooltip' data-placement='top' title='Today'>$currentDayCount</td>";
                  } elseif (in_array($currentDate, $specialDates)) {
                    $calendar .= "<td class='special' data-toggle='tooltip' data-placement='top' title='$remarks'>$currentDayCount</td>";
                  } else {
                    $calendar .= "<td>$currentDayCount</td>";
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
                $dueDateTime = new DateTime($row['due_date']);
                $interval = $currentDate->diff($dueDateTime);
                if ($interval->days > 0) {
                  $remainingTime = $interval->format('%a days');
                } elseif ($interval->h > 0) {
                  $remainingTime = $interval->format('%h hours');
                } else {
                  $remainingTime = $interval->format('%i minutes');
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
            $query_result = mysqli_query($con, "SELECT project_list.*, accounts.file_name, accounts.username FROM project_list JOIN department ON department.dept_id=project_list.dept_id JOIN accounts ON accounts.id=project_list.leader WHERE project_list.dept_id='$dept_id'");
            if (mysqli_num_rows($query_result) > 0) {
              while ($row = mysqli_fetch_assoc($query_result)) { ?>
                <div class="card mb-4 py-3 border-bottom-danger">
                  <div class="card-body custom-card">
                    <div class="left-content text-<?php echo $selectBorder ?> col-5">
                      <div class="display-8 font-weight-bold"><?php echo $row['title']; ?></div>
                      <div class="font-weight-light font-italic display-8"><?php echo $row['details']; ?></div>
                    </div>
                    <div class="middle-content text-center col-7">
                      <i class="fas fa-user-circle"></i> Project Leader: <?php echo $row['username']; ?><br>
                      <i class="fas fa-calendar-day fa-fw"></i>Due Date: <?php echo date_format(date_create($row['end']), "F d, Y")?>
                      <hr class="sidebar-divider d-none d-md-block">
                      <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">
                          75%
                        </div>
                      </div>
                      <div class="font-weight-bold">
                        <?php echo $row['status']; ?>
                      </div>
                    </div>
                    <!-- <div class="right-content text-center">
                    </div> -->
                  </div>
                </div>
              <?php }
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
  <?php } ?>
</div>

<?php include('../include/footer.php'); ?>

<script>
  $(function() {
    $('[data-toggle="tooltip"]').tooltip()
  })
</script>