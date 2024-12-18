<?php include('auth.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Cache control meta tags -->
  <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="pragma" content="no-cache">
  <meta http-equiv="expires" content="0">

  <title>Task Management System</title>

  <link rel="icon" type="image/png" sizes="32x32" href="../assets/img/Logo.png">

  <!-- Custom fonts for this template -->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../assets/fonts/Nunito.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../vendor/snapappointments/bootstrap-select/dist/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="../vendor/select2-4.1.0-rc.0/dist/css/select2.min.css">
  <link rel="stylesheet" href="../assets/css/toggle-switchy.css">
  <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body id="page-top">

  <div id="preloader"><img src="../assets/img/illustrations/loading.gif" alt="Loading..."></div>

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon">
          <!-- <i class="fas fa-business-time"></i> -->
          <img src="../assets/img/Logo.png" class="system-logo">
        </div>
        <div class="sidebar-brand-text">Task Management</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <li class="nav-item">
        <a class="nav-link" href="index.php">
          <i class="fas fa-tachometer-alt fa-fw"></i>
          <span>Dashboard</span></a>
      </li>

      <hr class="sidebar-divider">

      <?php if ($access == 1) { ?>
        <div class="sidebar-heading"> Account Management </div>

        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            <i class="fas fa-users-cog"></i>
            <span>Account Management</span>
          </a>
          <div id="collapseOne" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item" href="departments.php">Department</a>
              <a class="collapse-item" href="sections.php">Section</a>
              <a class="collapse-item" href="accounts.php">User Account</a>
            </div>
          </div>
        </li>

        <hr class="sidebar-divider">

        <div class="sidebar-heading"> Task Management </div>

        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-tasks"></i>
            <span>Task Management</span>
          </a>
          <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item" href="registered_tasks.php">Task List</a>
              <a class="collapse-item" href="tasks.php">Deployed Tasks</a>
            </div>
          </div>
        </li>

        <hr class="sidebar-divider">

        <div class="sidebar-heading"> Project Management </div>

        <li class="nav-item">
          <a class="nav-link" href="http://192.168.5.246/projectmgt" target="_blank">
            <i class="fas fa-chart-bar"></i>
            <span>Project Management</span></a>
        </li>

        <hr class="sidebar-divider">

        <div class="sidebar-heading"> System Management </div>

        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
            <i class="fas fa-wrench"></i>
            <span>System Management</span>
          </a>
          <div id="collapseFour" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item" href="system_logs.php">System Logs</a>
              <a class="collapse-item" href="calendar.php">Dayoff Calendar</a>
            </div>
          </div>
        </li>
      <?php } elseif ($access == 2) { ?>
        <div class="sidebar-heading"> Components </div>

        <li class="nav-item">
          <a class="nav-link" href="tasks.php"">
            <i class=" fas fa-fw fa-tasks"></i>
            <span>My Tasks</span></a>
        </li>

        <?php if ($dept_id == 115) : ?>
          <li class="nav-item">
            <a class="nav-link" href="http://192.168.5.246/projectmgt" target="_blank">
              <i class="fas fa-fw fa-folder-open"></i>
              <span>Projects</span></a>
          </li>
        <?php else : ?>
          <li class="nav-item">
            <a class="nav-link disabled" href="project.php">
              <i class="fas fa-fw fa-folder-open"></i>
              <span>Projects</span></a>
          </li>
        <?php endif; ?>

        <li class="nav-item">
          <a class="nav-link disabled" href="accounts.php">
            <i class="fas fa-fw fa-users"></i>
            <span>Team</span></a>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading"> Ratings </div>
        <li class="nav-item">
          <a class="nav-link" href="performance.php">
            <i class="fas fa-trophy"></i>
            <span>Performance</span></a>
        </li>
      <?php } elseif ($access == 3) { ?>
        <div class="sidebar-heading"> Components </div>

        <li class="nav-item">
          <a class="nav-link" href="registered_tasks.php">
            <i class="fas fa-fw fa-list"></i>
            <span>Task List</span></a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="tasks.php">
            <i class="fas fa-fw fa-tasks"></i>
            <span>Deployed Task</span></a>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading"> Approval </div>

        <li class="nav-item">
          <a class="nav-link" href="for_review.php">
            <span class="badge badge-danger badge-counter mx-4" style="font-size: 15px;"><?php echo ($for_review_tasks >= 100) ? '99+' : $for_review_tasks; ?></span>
            <i class="fas fa-fw fa-bell"></i>
            <span>Review Task</span></a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="for_reschedule.php">
            <span class="badge badge-danger badge-counter mx-4" style="font-size: 15px;"><?php echo ($for_resched_tasks >= 100) ? '99+' : $for_resched_tasks; ?></span>
            <i class="fas fa-calendar-day"></i>
            <span>Reschedule Task</span></a>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading"> Ratings </div>

        <li class="nav-item">
          <a class="nav-link" href="../pages/performance.php">
            <i class="fas fa-award"></i>
            <span>Member Perfromance</span></a>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading"> Others </div>

        <?php if ($dept_id == 115) : ?>
          <li class="nav-item">
            <a class="nav-link" href="http://192.168.5.246/projectmgt" target="_blank">
              <i class="fas fa-fw fa-folder-open"></i>
              <span>Projects</span></a>
          </li>
        <?php else : ?>
          <li class="nav-item">
            <a class="nav-link disabled" href="project.php">
              <i class="fas fa-fw fa-folder-open"></i>
              <span>Projects</span></a>
          </li>
        <?php endif; ?>

      <?php } ?>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

      <!-- Help Section -->
      <div class="text-center d-none d-md-inline mt-auto mb-5">
        <button class="btn btn-circle text-light" data-toggle="modal" data-target="#helpModal"><i class="far fa-question-circle fa-2x"></i></button>
      </div>
    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>

            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-2x fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter" style="font-size: large;"><?php echo $total_notification ?></span>
              </a>
              <!-- Dropdown - Alerts -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">Notification Center</h6>
                <?php
                $con->next_result();
                $query_check = mysqli_query($con, "SELECT * FROM notification WHERE user='$username' ORDER BY status DESC, id DESC LIMIT 3");
                if (mysqli_num_rows($query_check) > 0) {
                  while ($row = mysqli_fetch_assoc($query_check)) {
                    $date_created = date_format(date_create($row['date_created']), "F d, Y @ h:i A"); ?>
                    <button class="dropdown-item d-flex align-items-center <?php if ($row['status'] == 1) echo 'bg-gray-200'; ?>" value="<?php echo $row['id']; ?>" onclick="<?php echo $row['action']; ?> readNotification(this);">
                      <input type="hidden" name="notificationID[]" id="notificationID" value="<?php echo $row['id']; ?>">
                      <div class="mr-3">
                        <div class="icon-circle bg-<?php echo $row['type']; ?>">
                          <i class="<?php echo $row['icon']; ?> text-white"></i>
                        </div>
                      </div>
                      <div>
                        <div class="small text-gray-500"><?php echo $date_created ?></div>
                        <?php echo $row['body']; ?>
                        <?php if ($row['status'] == 1) echo "<br><span class='badge badge-pill text-white bg-warning'>New</span>";
                        else echo "<br><span class='badge badge-pill text-white bg-secondary'>Read</span>"; ?>
                      </div>
                    </button>
                  <?php }
                } else { ?>
                  <a class="dropdown-item" href="#">
                    <div class="small text-gray-500 text-center">No Notification</div>
                  </a>
                <?php } ?>
                <button class="dropdown-item text-center small text-gray-500" onclick="readAllNotification(this)" data-toggle="modal" data-target="#notificationLogs">View All Notification</button>
              </div>
            </li>

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $fname ?></span>
                <img class="img-profile rounded-circle" src="<?php echo $profileURL ?>">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#profileModal">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <button class="dropdown-item" data-toggle="modal" data-target="#activityLogs">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </button>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->