<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <?php if ($access == 1) {
    $con->next_result();
    $today = date('Y-m-d 16:00:00');
    $query_result = mysqli_query($con, $query = "SELECT (SELECT COUNT(*) FROM tasks t JOIN tasks_details td ON t.id = td.task_id WHERE td.task_status = 1) AS total_tasks,(SELECT COUNT(*) FROM accounts ac WHERE ac.status = 1) AS all_accounts");
    $row = mysqli_fetch_assoc($query_result);
    $total_tasks      = $row['total_tasks'];
    $all_accounts     = $row['all_accounts']; ?>
    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

    <div class="row">
      <!-- Cards Section -->
      <div class="col-xl-8">
        <div class="row">
          <div class="col-xl-6 col-md-6 mb-4">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">System Usage</h6>
                <i class="fas fa-server fa-lg"></i>
              </div>
              <div class="card-body">
                <div class="row text-center">
                  <div class="col-6 border-end">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Database Size</div>
                    <div class="h4 mb-0 font-weight-bold text-gray-800">
                      <i class="fas fa-database fa-fw"></i> <?php echo $db_size ?>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Project Size</div>
                    <div class="h4 mb-0 font-weight-bold text-gray-800">
                      <i class="fas fa-hdd fa-fw"></i> <?php echo $projectSize ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer text-center">
                <a href="#" class="btn btn-sm btn-success text-white" data-toggle="modal" data-target="#systemInfo">
                  More Info <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xl-6 col-md-6 mb-4">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Tasks Overview</h6>
                <i class="fas fa-tasks fa-lg"></i>
              </div>
              <div class="card-body">
                <div class="row text-center">
                  <div class="col-12">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Tasks</div>
                    <div class="h4 mb-0 font-weight-bold text-gray-800">
                      <i class="fas fa-list fa-fw"></i> <?php echo $total_tasks ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer text-center">
                <a href="tasks.php" class="btn btn-sm btn-primary text-white">
                  View Tasks <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>
          </div>
          <div class="col-xl-6 col-md-6 mb-4">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Accounts Overview</h6>
                <i class="fas fa-users fa-lg"></i>
              </div>
              <div class="card-body">
                <div class="row text-center">
                  <div class="col-12">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Accounts</div>
                    <div class="h4 mb-0 font-weight-bold text-gray-800">
                      <i class="fas fa-user-friends fa-fw"></i> <?php echo $all_accounts ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer text-center">
                <a href="accounts.php" class="btn btn-sm btn-info text-white">
                  Manage Accounts <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Calendar Section -->
      <div class="col-xl-4">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">Calendar of <?php echo date('F Y'); ?></h6>
            <i class="fas fa-calendar-alt fa-lg"></i>
          </div>
          <div class="card-body table-responsive">
            <table class="calendar table table-bordered text-center">
              <thead class="bg-light">
                <tr>
                  <th class="text-danger">Sun</th>
                  <th>Mon</th>
                  <th>Tue</th>
                  <th>Wed</th>
                  <th>Thu</th>
                  <th>Fri</th>
                  <th class="text-primary">Sat</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $query_result = mysqli_query($con, "SELECT * FROM day_off WHERE status=1");
                $specialDates = [];
                $remarks = [];

                if ($query_result->num_rows > 0) {
                  while ($row = $query_result->fetch_assoc()) {
                    $specialDates[] = $row['date_off'];
                    $remarks[$row['date_off']] = $row['remarks'];
                  }
                }

                $currentYear = date("Y");
                $currentMonth = date("m");
                $currentDay = date("d");
                $firstDayOfMonth = mktime(0, 0, 0, $currentMonth, 1, $currentYear);
                $daysInMonth = date("t", $firstDayOfMonth);
                $dayOfWeek = date("w", $firstDayOfMonth);

                $calendar = "<tr>";
                for ($i = 0; $i < $dayOfWeek; $i++) {
                  $calendar .= "<td></td>";
                }

                for ($currentDayCount = 1; $currentDayCount <= $daysInMonth; $currentDayCount++) {
                  if ($dayOfWeek == 7) {
                    $dayOfWeek = 0;
                    $calendar .= "</tr><tr>";
                  }

                  $currentDate = "$currentYear-$currentMonth-" . str_pad($currentDayCount, 2, "0", STR_PAD_LEFT);
                  $class = "";

                  if ($currentDayCount == $currentDay) {
                    $class = "today bg-primary text-white font-weight-bold";
                    $tooltip = "Today";
                  } elseif (in_array($currentDate, $specialDates)) {
                    $class = "special bg-warning";
                    $tooltip = $remarks[$currentDate];
                  } elseif ($dayOfWeek == 0) {
                    $class = "text-danger"; // Sunday styling
                    $tooltip = "";
                  } elseif ($dayOfWeek == 6) {
                    $class = "text-primary"; // Saturday styling
                    $tooltip = "";
                  } else {
                    $tooltip = "";
                  }

                  $calendar .= "<td class='$class' data-toggle='tooltip' title='$tooltip' data-placement='top'>$currentDayCount</td>";
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
    </div>

  <?php } elseif ($access == 2) { ?>
    <div class="row">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">To Do Tasks</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $today_tasks ?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-list fa-3x text-gray-500"></i>
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
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Under Review Tasks</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $review_tasks ?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-tasks fa-3x text-gray-500"></i>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col"><a href="javascript:void(0);" onclick="localStorage.setItem('activeTab', '#review');window.location.href='tasks.php';" class="btn btn-warning btn-sm">More Info <i class="fas fa-arrow-circle-right"></i></a>
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
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completed Tasks</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $completed_tasks ?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-star fa-3x text-gray-500"></i>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col"><a href="javascript:void(0);" onclick="localStorage.setItem('activeTab', '#finished');window.location.href='tasks.php';" class="btn btn-success btn-sm">More Info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-secondary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">File Directory</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $file_counter ?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-paperclip fa-3x text-gray-500"></i>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col"><a href="files.php" class="btn btn-secondary btn-sm">More Info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Calendar -->
      <div class="col-xl-4">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">Calendar of <?php echo date('F Y'); ?></h6>
            <i class="fas fa-calendar-alt fa-lg"></i>
          </div>
          <div class="card-body table-responsive">
            <table class="calendar table table-bordered text-center">
              <thead class="bg-light">
                <tr>
                  <th class="text-danger">Sun</th>
                  <th>Mon</th>
                  <th>Tue</th>
                  <th>Wed</th>
                  <th>Thu</th>
                  <th>Fri</th>
                  <th class="text-primary">Sat</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $query_result = mysqli_query($con, "SELECT * FROM day_off WHERE status=1");
                $specialDates = [];
                $remarks = [];

                if ($query_result->num_rows > 0) {
                  while ($row = $query_result->fetch_assoc()) {
                    $specialDates[] = $row['date_off'];
                    $remarks[$row['date_off']] = $row['remarks'];
                  }
                }

                $currentYear = date("Y");
                $currentMonth = date("m");
                $currentDay = date("d");
                $firstDayOfMonth = mktime(0, 0, 0, $currentMonth, 1, $currentYear);
                $daysInMonth = date("t", $firstDayOfMonth);
                $dayOfWeek = date("w", $firstDayOfMonth);

                $calendar = "<tr>";
                for ($i = 0; $i < $dayOfWeek; $i++) {
                  $calendar .= "<td></td>";
                }

                for ($currentDayCount = 1; $currentDayCount <= $daysInMonth; $currentDayCount++) {
                  if ($dayOfWeek == 7) {
                    $dayOfWeek = 0;
                    $calendar .= "</tr><tr>";
                  }

                  $currentDate = "$currentYear-$currentMonth-" . str_pad($currentDayCount, 2, "0", STR_PAD_LEFT);
                  $class = "";

                  if ($currentDayCount == $currentDay) {
                    $class = "today bg-primary text-white font-weight-bold";
                    $tooltip = "Today";
                  } elseif (in_array($currentDate, $specialDates)) {
                    $class = "special bg-warning";
                    $tooltip = $remarks[$currentDate];
                  } elseif ($dayOfWeek == 0) {
                    $class = "text-danger"; // Sunday styling
                    $tooltip = "";
                  } elseif ($dayOfWeek == 6) {
                    $class = "text-primary"; // Saturday styling
                    $tooltip = "";
                  } else {
                    $tooltip = "";
                  }

                  $calendar .= "<td class='$class' data-toggle='tooltip' title='$tooltip' data-placement='top'>$currentDayCount</td>";
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
          <div class="card-header m-0 font-weight-bolder text-primary">Upcoming Monthly Routine & Report</div>
          <?php
          $con->next_result();
          $query_result = mysqli_query($con, "SELECT * FROM tasks_details td JOIN tasks t ON td.task_id=t.id JOIN task_list tl ON t.task_id=tl.id WHERE tl.task_class IN (3, 6) AND t.in_charge='$username' AND td.status IN ('NOT YET STARTED', 'RESCHEDULE')");
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
        <div class="card shadow-sm border-0">
          <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">Calendar of <?php echo date('F Y'); ?></h6>
            <i class="fas fa-calendar-alt fa-lg"></i>
          </div>
          <div class="card-body table-responsive">
            <table class="calendar table table-bordered text-center">
              <thead class="bg-light">
                <tr>
                  <th class="text-danger">Sun</th>
                  <th>Mon</th>
                  <th>Tue</th>
                  <th>Wed</th>
                  <th>Thu</th>
                  <th>Fri</th>
                  <th class="text-primary">Sat</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $query_result = mysqli_query($con, "SELECT * FROM day_off WHERE status=1");
                $specialDates = [];
                $remarks = [];

                if ($query_result->num_rows > 0) {
                  while ($row = $query_result->fetch_assoc()) {
                    $specialDates[] = $row['date_off'];
                    $remarks[$row['date_off']] = $row['remarks'];
                  }
                }

                $currentYear = date("Y");
                $currentMonth = date("m");
                $currentDay = date("d");
                $firstDayOfMonth = mktime(0, 0, 0, $currentMonth, 1, $currentYear);
                $daysInMonth = date("t", $firstDayOfMonth);
                $dayOfWeek = date("w", $firstDayOfMonth);

                $calendar = "<tr>";
                for ($i = 0; $i < $dayOfWeek; $i++) {
                  $calendar .= "<td></td>";
                }

                for ($currentDayCount = 1; $currentDayCount <= $daysInMonth; $currentDayCount++) {
                  if ($dayOfWeek == 7) {
                    $dayOfWeek = 0;
                    $calendar .= "</tr><tr>";
                  }

                  $currentDate = "$currentYear-$currentMonth-" . str_pad($currentDayCount, 2, "0", STR_PAD_LEFT);
                  $class = "";

                  if ($currentDayCount == $currentDay) {
                    $class = "today bg-primary text-white font-weight-bold";
                    $tooltip = "Today";
                  } elseif (in_array($currentDate, $specialDates)) {
                    $class = "special bg-warning";
                    $tooltip = $remarks[$currentDate];
                  } elseif ($dayOfWeek == 0) {
                    $class = "text-danger"; // Sunday styling
                    $tooltip = "";
                  } elseif ($dayOfWeek == 6) {
                    $class = "text-primary"; // Saturday styling
                    $tooltip = "";
                  } else {
                    $tooltip = "";
                  }

                  $calendar .= "<td class='$class' data-toggle='tooltip' title='$tooltip' data-placement='top'>$currentDayCount</td>";
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
          <div class="card-header m-0 font-weight-bolder text-primary">Upcoming Monthly Routine & Report</div>
          <?php
          $con->next_result();
          $query_result = mysqli_query($con, "SELECT * FROM tasks_details td JOIN tasks t ON td.task_id=t.id JOIN task_list tl ON t.task_id=tl.id JOIN section s ON tl.task_for=s.sec_id WHERE tl.task_class IN (3, 6) AND s.dept_id='$dept_id' AND td.status IN ('NOT YET STARTED', 'RESCHEDULE') GROUP BY tl.task_name");
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