<?php
include('connect.php');
include('auth.php');
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
    <link href="../vendor/font-awesome/css/fontawesome.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/font-awesome/css/brands.css" rel="stylesheet" type="text/css">
    <link href="../vendor/font-awesome/css/solid.css" rel="stylesheet" type="text/css">

    <link href="../assets/css/select2.min.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-select.min.css" rel="stylesheet">

    <link href="../assets/css/jquery-ui.min.css" rel="stylesheet">
    <link href="../assets/css/dataTables.bootstrap.css" rel="stylesheet">    
    <link href="../assets/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/bootstrap-select.css">

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
                    <i class="fas fa-users fa-fw"></i> <?php ?> <i class="fas fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li>
                        <a href="user_profile.php?username=<?php echo $username; ?>"><i class="fa fa-user fa-fw"></i> User Profile</a>
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
                <h1 class="page-header">User Profile</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7">
                <div class="well">
                    <div class="row">
                        <form data-toggle="validator" action="user_profile_update.php" enctype="multipart/form-data" method="post">

                        <?php
                        $con->next_result();
                        $result = mysqli_query($con,"SELECT accounts.username, accounts.fname, accounts.lname, section.sec_name,section.sec_id, accounts.email, access.access, accounts.access AS access_code FROM accounts INNER JOIN section on section.sec_id=accounts.sec_id INNER JOIN access ON access.id=accounts.access WHERE username = '$username'");
                        while($row = mysqli_fetch_array($result)){ 
                            $sec_id = $row['sec_id'];
                            $access_code = $row['access_code']; ?>
                        
                            <div class="col-lg-12">
                                <div class="form-group">
                                <label>User Name:</label><span class="pull-right help-block with-errors" style="margin: 0px; font-size: 11px;"></span>
                                <input class="form-control" placeholder="Enter Username" name="username"   value="<?php echo $row['username']; ?>"  onkeyup="keypress();" required pattern="[a-zA-Z0-9]+" data-error="Invalid input!">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                <label>First Name:</label><span class="pull-right help-block with-errors" style="margin: 0px; font-size: 11px;"></span>
                                <input class="form-control" placeholder="Enter First Name" name="fname"  value="<?php echo $row['fname']; ?>"  onkeyup="keypress();" pattern="[a-zA-Z\s]+" data-error="Invalid input!">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                <label>Last Name:</label><span class="pull-right help-block with-errors" style="margin: 0px; font-size: 11px;"></span>
                                <input class="form-control" placeholder="Enter Last Name" name="lname" value="<?php echo $row['lname']; ?>"  onkeyup="keypress();" pattern="[a-zA-Z\s]+" data-error="Invalid input!">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                <label>Email Account:</label><span class="pull-right help-block with-errors" style="margin: 0px; font-size: 11px;"></span>
                                <input class="form-control" placeholder="Enter E-mail" name="email" value="<?php echo $row['email']; ?>"  onkeyup="keypress();" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" data-error="Invalid input!">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                <label>Section:</label><span class="pull-right help-block with-errors" style="margin: 0px; font-size: 11px;"></span>
                                <select name="section" id="section" class="form-control selectpicker show-menu-arrow" data-live-search="true">
                                <option selected value="<?php echo $row['sec_id']; ?>"><?php echo $row['sec_name']; ?></option>
                                <?php
                                    $sql = mysqli_query($con,"SELECT * FROM section WHERE status='1' AND sec_id != '$sec_id'"); 
                                    $con->next_result();
                                    if(mysqli_num_rows($sql)>0){
                                        while($row1=mysqli_fetch_assoc($sql)){
                                            $sec_id1 = $row1['sec_id'];
                                            $sec_name1 = $row1['sec_name'];
                                            echo "<option value='".$sec_id1."'>".$sec_name1."</option>";
                                        }
                                } ?>
                                </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                <label>Access:</label><span class="pull-right help-block with-errors" style="margin: 0px; font-size: 11px;"></span>
                                <select name="access" id="access" class="form-control selectpicker show-menu-arrow" data-live-search="true">
                                <option selected value="<?php echo $row['access_code']; ?>"><?php echo strtoupper($row['access']); ?></option>
                                <?php
                                    $sql = mysqli_query($con,"SELECT * FROM access WHERE id !='$access_code'"); 
                                    $con->next_result();
                                    if(mysqli_num_rows($sql)>0){
                                        while($row2=mysqli_fetch_assoc($sql)){
                                            $access_code1 = $row2['id'];
                                            $access_name1 = $row2['access'];
                                            echo "<option value='".$access_code1."'>".strtoupper($access_name1)."</option>";
                                        }
                                } ?>
                                </select>
                                </div>
                            </div>

                            <div class="col-lg-12 ">
                                <div class="form-group">
                                    <button type="button" id="submit-btn" name="submit" value="submit" class="btn btn-success pull-right" data-toggle="modal" data-target="#submitModal" disabled>
                                    Update Account</button>
                                    <a href="change_password.php?username=<?php echo $username; ?>"><button type="button" id="submit-btn" class="btn btn-primary pull-left">Change Password</button></a>
                                </div>
                            </div>
                            <?php }  $con-> close(); ?>

                            <div class="modal fade" id="submitModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content panel-info">
                                        <div class="modal-header panel-heading">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                                        </div>
                                        <div class="modal-body panel-body">
                                        <center>
                                        <i style="color:#3581C1; font-size:80px;" class="fas fa-question-circle  "></i>
                                        <br><br>
                                        Are you sure you want to Update account?
                                        </center>
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal"> </span>  No</button>
                                        <button type="submit" name="submit" class="btn btn-primary pull-right"> </span> Yes</button>
                                        </div>
                                    </div>
                                                    <!-- /.modal-content -->
                                </div>
                                                <!-- /.modal-dialog -->
                            </div>                        
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <a href="home.php"> <button class='btn btn-danger pull-left'><i class="fa fa-arrow-left"></i> Return to Homepage</button></a>
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
<script src="../assets/js/slideshow.js"></script>

<script src="../assets/js/bootstrap-toggle.min.js"></script>
<!-- For select multiple tag-->
<script src="../assets/js/bootstrap-select.js"></script> 
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="../assets/js/select2.min.js"></script>