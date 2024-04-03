<?php
include('connect.php');
include('auth.php'); ?>
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
    <link href="../vendor/font-awesome/css/fontawesome.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/font-awesome/css/brands.css" rel="stylesheet" type="text/css">
    <link href="../vendor/font-awesome/css/solid.css" rel="stylesheet" type="text/css">

    <link href="../assets/css/select2.min.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-select.min.css" rel="stylesheet">

    <link href="../assets/css/jquery-ui.min.css" rel="stylesheet">
    <link href="../assets/css/dataTables.bootstrap.css" rel="stylesheet">  

    <style>
        #page-wrapper {
            position: inherit;
            margin: 0 0 0 0px;
            padding: 0 30px;
            border-left: 1px solid #e7e7e7;
        }
    </style>
</head>
<body>
<div id="wrapper">
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="home.php"><p class="text-primary"><img src="../assets/img/gloryicon.png"> GLORY (PHILIPPINES), INC. | <font color="red">GLORY TASK MANAGEMENT SYSTEM</font></p></a>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fas fa-users fa-fw"></i> <?php echo strtoupper($username)?> <i class="fas fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li>
                        <a href="user_profile.php?id=<?php echo $emp_id; ?>"><i class="fa fa-user fa-fw"></i> User Profile</a>
                    </li>
                    <li>
                        <a href="logout.php"><i class="fas fa-sign-out-alt fa-fw"></i> Logout</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Modify Password</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7">
                <div class="well">
                    <div class="row">
                        <form data-toggle="validator" action="change_password_submit.php?username=<?php echo $_GET['username']; ?>" enctype="multipart/form-data" method="post">
                            <div class="col-lg-12">
                                <div class="form-group">
                                <label>Old Password:</label><span class="pull-right help-block with-errors" id="divCheckOldPassword" style="margin: 0px; font-size: 11px;"></span>
                                <input type="password" data-toggle="password" data-placement="before" placeholder="Enter Old Password" class="form-control" name="old_pass" id="old_pass"  required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                <label>New Password:</label><span class="pull-right help-block with-errors" style="margin: 0px; font-size: 11px;"></span>
                                <input type="password" data-toggle="password" data-placement="before" placeholder="Enter New Password" class="form-control"  name="new_pass"  id="new_pass" pattern="[a-zA-Z0-9-/]+" data-error="Special character not allowed." maxlength="20" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                <label>Re-type Password:</label><span class="pull-right help-block with-errors" id="divCheckPasswordMatch" style="margin: 0px; font-size: 11px;"></span>
                                <input type="password"  data-toggle="password" data-placement="before" placeholder="Re-type New Password" class="form-control" name="retype_pass" id="retype_pass"  pattern="[a-zA-Z0-9-/]+" data-error="Special character not allowed."  maxlength="20" onChange="checkPasswordMatch();" required>
                                </div>
                            </div>
                            <div class="col-lg-12 ">
                            <div class="form-group">
                                <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#submitModal">
                                <span class="fa fa-check"> </span> Submit</button>
                                <button type="button" class="btn btn-danger pull-right" onclick="javascript:history.back()">
                                <span class="fa fa-times"> </span> Cancel</button>
                            </div>
                            </div>
                            <div class="modal fade" id="submitModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content panel-info">
                                        <div class="modal-header panel-heading">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                                        </div>
                                        <div class="modal-body panel-body">
                                        <center>
                                            <i style="color:#3581C1; font-size:80px;" class="fa fa-question-circle  "></i>
                                            <br><br>
                                            Are you sure you want to change password?
                                        </center>
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="fa fa-times"> </span> No</button>
                                        <button type="submit" name="submit" class="btn btn-success pull-right"><span class="fa fa-check"> </span> Yes</button>
                                        </div>
                                    </div>        <!-- /.modal-content -->
                                </div>          <!-- /.modal-dialog -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

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

<script src="../assets/js/bootstrap-toggle.min.js"></script>
<!-- For select multiple tag-->
<script src="../assets/js/bootstrap-select.js"></script> 
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="../assets/js/select2.min.js"></script>

<script type="text/javascript">
    function checkPasswordMatch() {
        var new_pass = $("#new_pass").val();
        var retype_pass = $("#retype_pass").val();

        if (new_pass != retype_pass)
            $("#divCheckPasswordMatch").html("Passwords do not match!").css({"color" : "#a94442"});
        else
            $("#divCheckPasswordMatch").html("Passwords match.").css({"color" : "#3c763d"});
    }

    $(document).ready(function () {
       $("#new_pass, #retype_pass").keyup(checkPasswordMatch);
    });
</script>

<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/bootstrap-show-password.js"></script>
<script>
    $(function() {
      $('#password').password()
      .password('focus')
      .on('show.bs.password', function(e) {
        $('#eventLog').text('On show event');
        $('#methods').prop('checked', true);
      }).on('hide.bs.password', function(e) {
        $('#eventLog').text('On hide event');
        $('#methods').prop('checked', false);
      });
      $('#methods').click(function() {
        $('#password').password('toggle');
      });
    });
</script>