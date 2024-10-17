<?php include('auth.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Cache control meta tags -->
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">

  <title>Task Management System</title>

  <link rel="icon" type="image/png" sizes="32x32" href="../assets/img/Logo.png">

  <!-- Custom fonts for this template -->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../vendor/snapappointments/bootstrap-select/dist/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="../assets/css/toggle-switchy.css">
  <link rel="stylesheet" href="../assets/css/style.css">

  <!-- Custome scripts for this page -->
  <script src="../assets/js/moment.min.js"></script>
</head>

<body id="page-top">

  <div id="preloader"><img src="../assets/img/illustrations/loading.gif" alt="Loading..."></div>

  <!-- Page Wrapper -->
  <div id="wrapper">



    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Left Navbar -->
          <a class="navbar-brand" href="index.php">
            <img src="../assets/img/Logo.png" width="40" height="40">
          </a>
          <span class="d-none d-xl-block position-absolute" style="margin-left: 45px; cursor: default;">Task Management System</span>

          <!-- Center Navbar -->
          <ul class="navbar-nav navbar-collapse justify-content-center position-static">
            <li class="nav-item m-3" data-toggle="tooltip" data-placement="bottom" title="Dashboard">
              <a class="nav-link" href="index.php"><i class="fas fa-tachometer-alt fa-2x"></i></a>
            </li>
            <li class="nav-item m-3" data-toggle="tooltip" data-placement="bottom" title="To Do">
              <a class="nav-link" href="tasks.php"><i class="fas fa-tasks fa-2x"></i></i></a>
            </li>
            <li class="nav-item m-3" data-toggle="tooltip" data-placement="bottom" title="Assigned Tasks">
              <a class="nav-link" href="assign_tasks.php"><i class="fas fa-sticky-note fa-2x"></i></a>
            </li>
            <li class="nav-item m-3" data-toggle="tooltip" data-placement="bottom" title="Performance">
              <a class="nav-link" href="performance.php"><i class="fas fa-trophy fa-2x"></i></a>
            </li>
          </ul>

          <!-- Right Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- <div class="topbar-divider d-none d-sm-block"></div> -->

            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell" style="font-size: x-large;"></i>
                <span class="badge badge-pill badge-danger badge-counter"><?php echo $total_notification ?></span>
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

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <!-- <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $fname ?></span> -->
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