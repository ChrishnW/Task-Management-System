<?php
   include('../include/header_head.php');
   include('../include/bubbles.php');
   date_default_timezone_set("Asia/Manila");
   $dates = date('Y-m-d');
   $year = date('Y');
   $month = date('m');
   $day = date('F d');
   $name = mysqli_query($con,"SELECT * FROM accounts WHERE username='$username'");
   $rows = $name->fetch_assoc();
   $fname = $rows['fname'];
   ?>
<html>
   <head>
      <link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
      <link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
      <link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
      <link href="../assets/css/darkmode.css" rel="stylesheet">
   </head>
   <style>
      .panel>.panel-gray> {
      background-color: #ababab;
      border-color: #ababab;
      color: #fff;
      }
      .panel-heading {
      background-color: #ababab;
      border-color: #ababab;
      color: #fff;
      }
      .panel-gray {
      border-color: #ababab;
      }
      #page-wrapper a,
      #page-wrapper a:hover,
      #page-wrapper a:focus,
      #page-wrapper a:active {
      text-decoration: none;
      color: inherit;
      }
      .zoom:hover {
      transform: scale(1.05);
      transition: transform .5s;
      }

      @-webkit-keyframes ring {
        0% {
            -webkit-transform: rotate(-15deg);
            transform: rotate(-15deg);
        }

        2% {
            -webkit-transform: rotate(15deg);
            transform: rotate(15deg);
        }

        4% {
            -webkit-transform: rotate(-18deg);
            transform: rotate(-18deg);
        }

        6% {
            -webkit-transform: rotate(18deg);
            transform: rotate(18deg);
        }

        8% {
            -webkit-transform: rotate(-22deg);
            transform: rotate(-22deg);
        }

        10% {
            -webkit-transform: rotate(22deg);
            transform: rotate(22deg);
        }

        12% {
            -webkit-transform: rotate(-18deg);
            transform: rotate(-18deg);
        }

        14% {
            -webkit-transform: rotate(18deg);
            transform: rotate(18deg);
        }

        16% {
            -webkit-transform: rotate(-12deg);
            transform: rotate(-12deg);
        }

        18% {
            -webkit-transform: rotate(12deg);
            transform: rotate(12deg);
        }

        20% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        }

        @keyframes ring {
        0% {
            -webkit-transform: rotate(-15deg);
            -ms-transform: rotate(-15deg);
            transform: rotate(-15deg);
        }

        2% {
            -webkit-transform: rotate(15deg);
            -ms-transform: rotate(15deg);
            transform: rotate(15deg);
        }

        4% {
            -webkit-transform: rotate(-18deg);
            -ms-transform: rotate(-18deg);
            transform: rotate(-18deg);
        }

        6% {
            -webkit-transform: rotate(18deg);
            -ms-transform: rotate(18deg);
            transform: rotate(18deg);
        }

        8% {
            -webkit-transform: rotate(-22deg);
            -ms-transform: rotate(-22deg);
            transform: rotate(-22deg);
        }

        10% {
            -webkit-transform: rotate(22deg);
            -ms-transform: rotate(22deg);
            transform: rotate(22deg);
        }

        12% {
            -webkit-transform: rotate(-18deg);
            -ms-transform: rotate(-18deg);
            transform: rotate(-18deg);
        }

        14% {
            -webkit-transform: rotate(18deg);
            -ms-transform: rotate(18deg);
            transform: rotate(18deg);
        }

        16% {
            -webkit-transform: rotate(-12deg);
            -ms-transform: rotate(-12deg);
            transform: rotate(-12deg);
        }

        18% {
            -webkit-transform: rotate(12deg);
            -ms-transform: rotate(12deg);
            transform: rotate(12deg);
        }

        20% {
            -webkit-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        }

        .faa-ring.animated,
        .faa-ring.animated-hover:hover,
        .faa-parent.animated-hover:hover > .faa-ring {
        -webkit-animation: ring 2s ease infinite;
        animation: ring 2s ease infinite;
        transform-origin-x: 50%;
        transform-origin-y: 0px;
        transform-origin-z: initial;
        }
   </style>
   <!-- Script for Charts -->
   <script src="../assets/js/Chart.js"></script>
   <body>
      <div id="wrapper">
         <div id="page-wrapper">
            <div class="row">
               <div class="col-lg-12">
                  <!-- Dashboard <br>  -->
                  <h2 class="page-header pull-left">Dashboard</h2>
                  <h2 class="page-header pull-right">Today, <font color="#4287f5"><?php echo $day;?></font></h2>
               </div>
            </div>
            <div class="clearfix visible-xs"></div>
            <div class="zoom">
               <div class="col-lg-3 col-md-6">
                  <div class="panel panel-primary">
                     <div class="panel-heading">
                        <a href="task_approval.php?status=RESCHEDULE">
                           <div class="row">
                              <div class="col-xs-3">
                                 <i class="fas fa-bell faa-ring animated fa-5x"></i>
                              </div>
                              <div class="col-xs-9 text-right">
                                 <div class="huge">
                                    <?php
                                       $result = mysqli_query($con,"SELECT COUNT(id) as for_approval_task FROM tasks_details WHERE status='NOT YET STARTED' AND task_status IS TRUE AND approval_status = 0");
                                       $row = $result->fetch_assoc();
                                       echo $row['for_approval_task']; ?>
                                 </div>
                                 <div>Task Approval</div>
                        </a>
                        </div>
                        </div>
                     </div>
                     <div class="panel-footer">
                        <a href="task_approval.php?status=RESCHEDULE"><span class="pull-left">View Details</span></a>
                        <span class="pull-right"><a  href="task_approval.php?status=RESCHEDULE"><i class="fa fa-arrow-circle-right"></a></i></span>
                        <div class="clearfix"></div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="zoom">
               <div class="col-lg-3 col-md-6">
                  <div class="panel panel-red">
                     <div class="panel-heading">
                        <a href="tasks.php?status=NOT YET STARTED">
                           <div class="row">
                              <div class="col-xs-3">
                                 <i class="fa fa-clock-o fa-spin fa-5x"></i>
                              </div>
                              <div class="col-xs-9 text-right">
                                 <div class="huge">
                                    <?php
                                       $result = mysqli_query($con,"SELECT COUNT(id) as not_yet_started_task FROM tasks_details WHERE status='NOT YET STARTED' AND task_status IS TRUE AND approval_status IS TRUE  AND (reschedule != '1' OR reschedule = '2' AND approval_status=1)");
                                       $row = $result->fetch_assoc();
                                       echo $row['not_yet_started_task']; ?>
                                 </div>
                                 <div>Not Yet Started Tasks</div>
                        </a>
                        </div>
                        </div>
                     </div>
                     <div class="panel-footer">
                        <a href="tasks.php?status=NOT YET STARTED"><span class="pull-left">View Details</span></a>
                        <span class="pull-right"><a href="tasks.php?status=NOT YET STARTED"><i class="fa fa-arrow-circle-right"></a></i></span>
                        <div class="clearfix"></div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="zoom">
               <div class="col-lg-3 col-md-6">
                  <div class="panel panel-yellow">
                     <div class="panel-heading">
                        <a href="tasks.php?status=IN PROGRESS">
                           <div class="row">
                              <div class="col-xs-3">
                                 <i class="fas fa-spinner fa-spin fa-5x"></i>
                              </div>
                              <div class="col-xs-9 text-right">
                                 <div class="huge">
                                    <?php
                                       $result = mysqli_query($con,"SELECT COUNT(id) as ongoing_task FROM tasks_details WHERE status='IN PROGRESS' AND task_status IS TRUE");
                                       $row = $result->fetch_assoc();
                                       echo $row['ongoing_task']; ?>
                                 </div>
                                 <div>Ongoing Tasks</div>
                        </a>
                        </div>
                        </div>
                     </div>
                     <div class="panel-footer">
                        <a href="tasks.php?status=IN PROGRESS"><span class="pull-left">View Details</span></a>
                        <span class="pull-right"><a href="tasks.php?status=ONGOING"><i class="fa fa-arrow-circle-right"></a></i></span>
                        <div class="clearfix"></div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="zoom">
               <div class="col-lg-3 col-md-6">
                  <div class="panel panel-green">
                     <div class="panel-heading">
                        <a href="tasks.php?status=FINISHED">
                           <div class="row">
                              <div class="col-xs-3">
                                 <i class="fa fa-check-square fa-5x"></i>
                              </div>
                              <div class="col-xs-9 text-right">
                                 <div class="huge">
                                    <?php
                                       $result = mysqli_query($con,"SELECT COUNT(id) as finished_task FROM tasks_details WHERE status='FINISHED' AND task_status IS TRUE AND MONTH(tasks_details.due_date) = '$month'");
                                       $row = $result->fetch_assoc();
                                       echo $row['finished_task']; ?>
                                 </div>
                                 <div>Finished Tasks</div>
                        </a>
                        </div>
                        </div>
                     </div>
                     <div class="panel-footer">
                        <a href="tasks.php?status=FINISHED"><span class="pull-left">View Details</span></a>
                        <span class="pull-right"><a href="tasks.php?status=FINISHED"><i class="fa fa-arrow-circle-right"></a></i></span>
                        <div class="clearfix"></div>
                     </div>
                  </div>
               </div>
            </div>
            <canvas id="myChart" style="width:100%;max-width:inherit;border:solid 5px #fff;border-radius:10px;margin:10px;background-color:#fff"></canvas>
         </div>
      </div>
      </div>
   </body>
<?php
$result = mysqli_query($con,"SELECT (SELECT COUNT(id) AS finished_task FROM tasks_details WHERE tasks_details.status = 'FINISHED' AND tasks_details.task_status IS TRUE AND tasks_details.remarks != 'Failed to perform task' AND MONTH(tasks_details.due_date) = '$month') AS finished_task, (SELECT COUNT(id) AS failed_task FROM tasks_details WHERE tasks_details.status = 'FINISHED' AND tasks_details.task_status IS TRUE AND tasks_details.remarks = 'Failed to perform task' AND MONTH(tasks_details.due_date) = '$month') AS failed_task, (SELECT COUNT(id) AS total_task FROM tasks_details WHERE tasks_details.status = 'FINISHED' AND tasks_details.task_status IS TRUE AND MONTH(tasks_details.due_date) = '$month') AS total_task");
$row = $result->fetch_assoc();
$value1 = $row['finished_task'];
$value2 = $row['failed_task'];
$value3 = $row['total_task'];
?>
<script>
  // Define the data for the chart
  const xValues = ["January", "February", "March", "April"]; // Months
  const yValues1 = [<?php echo $value3?>, 0, 0, 0]; // Total tasks
  const yValues2 = [<?php echo $value1?>, 0, 0, 0]; // Finished tasks
  const yValues3 = [<?php echo $value2?>, 0, 0, 0]; // Failed tasks
  const barColors1 = "blue"; // Color for finished tasks
  const barColors2 = "green"; // Color for failed tasks
  const barColors3 = "red"; // Color for failed tasks

  // Create the chart using Chart.js
  new Chart("myChart", {
    type: "bar",
    data: {
      labels: xValues,
      datasets: [
        {
          label: "Total Tasks",
          backgroundColor: barColors1,
          data: yValues1
        },
        {
          label: "Finished Tasks",
          backgroundColor: barColors2,
          data: yValues2
        },
        {
          label: "Failed Tasks",
          backgroundColor: barColors3,
          data: yValues3
        }
        
      ]
    },
    options: {
      legend: {display: true},
      title: {
        display: true,
        text: "Task Comparison by Month of <?php echo $year?>"
      }
    }
  });


</script>
</html>