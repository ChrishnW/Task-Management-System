<!DOCTYPE html>
<?php
include('../include/connect.php');
include('../include/auth.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>G-TMS</title>
    <link rel="shortcut icon" href="../assets/img/gloryicon.png">

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../assets/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link href="../assets/css/select2.min.css" rel="stylesheet">

    <link href="../assets/css/jquery-ui.min.css" rel="stylesheet">
    <link href="../assets/css/dataTables.bootstrap.css" rel="stylesheet">
    
    <link rel="stylesheet" href="../assets/css/bootstrap-select.css">
    <style>
        #loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url('../assets/img/loader.gif') 50% 50% no-repeat rgb(0, 0, 0);
        }
    </style>
    <script src="../vendor/jquery/jquery-1.9.1.min.js"></script>
    <script>
        $(window).on('load', function() {
        $('#loader').fadeOut('slow');
        });
    </script>
</head>
<body>
<div id="loader"></div>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php"><p class="text-primary"><img src="../assets/img/gloryicon.png"> GLORY (PHILIPPINES), INC. | <font color="red"> TASK MANAGEMENT SYSTEM</font></p></a>
            </div>
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <br>
                        <li>
                            <a href="../include/home.php"><i class="fa fa-home fa-fw"></i> Home</a>
                        </li>
                        <li>
                            <a href="index.php"><i class="fas fa-th fa-fw"></i> Dashboard</a>
                        </li>

                        <li>
                            <a href="#"><i class="fa fa-archive fa-fw"></i> Task Details<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                <a href="task_details.php?section=MIS"><i class="fa fa-folder fa-fw"></i> Management Information System</a>
                                </li>
                                <li>
                                <a href="task_details.php?section=SK"><i class="fa fa-folder fa-fw"></i> System Kaizen</a>
                                </li>
                                <li>
                                <a href="task_details.php?section=FEM"><i class="fa fa-folder fa-fw"></i> Facility and Equipment Maintenace</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#"><i class="fas fa-user-cog fa-fw"></i> Task Management<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                <a href="regtask.php"><i class="fas fa-pen fa-fw"></i> Create New Task</a>
                                </li>
                                <li>
                                <a href="assigntask.php"><i class="fa fa-calendar-plus-o fa-fw"></i> Assign Tasks</a>
                                </li>
                                <li>
                                <a href="deploytask.php"><i class="fa fa-rocket fa-fw"></i> Deploy Task</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#"><i class="fas fa-trophy fa-fw"></i> Staff Performance<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                <a href="performance.php?section=MIS"><i class="fa fa-group fa-fw"></i> Management Information System</a>
                                </li>
                                <li>
                                <a href="performance.php?section=SK"><i class="fa fa-group fa-fw"></i> System Kaizen</a>
                                </li>
                                <li>
                                <a href="performance.php?section=FEM"><i class="fa fa-group fa-fw"></i> Facility and Equipment Maintenace</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-user-circle fa-fw"></i> <?php echo $username?><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="../include/user_profile.php?modal=hide" style="margin-top: 0"><i class="fa fa-cog fa-spin fa-fw"></i> Configuration</a>
                                </li>
                                <li>
                                    <a href="../include/logout.php"><i class="glyphicon glyphicon-log-out fa-fw"></i> Logout</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
<script src="../vendor/jquery/jquery.min.js"></script>
    <!-- Autocomplete Jquery-->
<script src="../vendor/jquery/jquery-ui.min.js"></script>
<!-- Flot Charts JavaScript -->
<script src="../vendor/flot/excanvas.min.js"></script>
<script src="../vendor/flot/jquery.flot.js"></script>
<script src="../vendor/flot/jquery.flot.pie.js"></script>
<script src="../vendor/flot/jquery.flot.resize.js"></script>
<script src="../vendor/flot/jquery.flot.time.js"></script>
<script src="../vendor/flot-tooltip/jquery.flot.tooltip.min.js"></script>
<script src="../data/flot-data.js"></script>

<!-- Metis Menu Plugin Javascript -->
<script src="../vendor/metisMenu/metisMenu.min.js"></script>
<script src="../assets/js/validator.js"></script>

<!-- DataTables Javascript -->
<script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>

<!-- Morris Charts Javascript -->
<script src="../vendor/raphael/raphael.min.js"></script>
<script src="../vendor/morrisjs/morris.min.js"></script>

<!-- Custom Theme Javascript -->
<script src="../assets/js/sb-admin-2.js"></script>

<!-- For select multiple tag-->
<script src="../assets/js/bootstrap-select.js"></script> 
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="../assets/js/select2.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
$('#admin-table').DataTable({
        "order":[[0,"desc"]],
        responsive: true,
        lengthMenu: [[10,15,20,50],[10,15,20,50]],
        pageLength: 10
    });
});
</script>