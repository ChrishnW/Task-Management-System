<?php 
include('../include/header.php');
?>

<html>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
<link href="../assets/css/darkmode.css" rel="stylesheet">
<style>
.form-group.required label {
    font-weight: bold;
}

.form-group.required label:after {
    color: #e32;
    content: ' *';
    display: inline;
}
</style>

<head>
    <title>Register Account</title>
</head>

<body>
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Register Account</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Register Account Form
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">

                                    <form data-toggle="validator" class="className" name="form" id="form"
                                        action="account_add_submit.php" method="POST">
                                        <div class="form-group">

                                            <div class="form-group required">
                                                <label>User Name:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="Enter User Name" class="form-control"
                                                    name="username" id="username" style="text-transform:uppercase"
                                                    pattern="[a-zA-Z0-9-/ ]+" data-error="Invalid input!" required>
                                            </div>

                                            <div class="form-group required">
                                                <label>First Name:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="Enter First Name" class="form-control"
                                                    name="fname" id="fname" style="text-transform:uppercase"
                                                    pattern="[a-zA-Z0-9-/ ]+" data-error="Invalid input!" required>
                                            </div>

                                            <div class="form-group required">
                                                <label>Last Name:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="Enter Last Name" class="form-control"
                                                    name="lname" id="lname" style="text-transform:uppercase"
                                                    pattern="[a-zA-Z0-9-/ ]+" data-error="Invalid input!" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Email:</label><span class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="ENTER EMAIL" class="form-control"
                                                    name="email" id="email">
                                            </div>

                                            <!-- Added Employee Card Number -->
                                            <div class="form-group" required>
                                                <label>Card Number:</label><span class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="ENTER CARD NUMBER" class="form-control"
                                                    name="card" id="card" required>
                                            </div>

                                            <div class="form-group required">
                                                <label>Access:</label>
                                                <select name="access" id="access" required
                                                    class="form-control selectpicker show-menu-arrow"
                                                    data-live-search="true" placeholder="Select Access">
                                                    <option disabled selected value="">--SELECT ACCESS--</option>
                                                    <?php
                                                        $sql = mysqli_query($con,"SELECT * FROM access WHERE id!='1'"); 
                                                        $con->next_result();
                                                        if(mysqli_num_rows($sql)>0){
                                                            while($row=mysqli_fetch_assoc($sql)){
                                                                $access_id = $row['id'];
                                                                $access = $row['access'];
                                                                echo "<option value='".$access_id."'>".strtoupper($access)."</option>";
                                                            }
                                                        } ?>
                                                </select>
                                            </div>

                                            <div class="form-group required">
                                                <label>Section:</label>
                                                <select name="section" id="section" required
                                                    class="form-control selectpicker show-menu-arrow"
                                                    data-live-search="true" placeholder="Select Section">
                                                    <option disabled selected value="">--SELECT SECTION--</option>
                                                    <?php
                                                        $sql = mysqli_query($con,"SELECT * FROM section WHERE status='1' "); 
                                                        $con->next_result();
                                                        if(mysqli_num_rows($sql)>0){
                                                            while($row=mysqli_fetch_assoc($sql)){
                                                                $sec_id = $row['sec_id'];
                                                                $sec_name = $row['sec_name'];
                                                                echo "<option value='".$sec_id."'>".$sec_name."</option>";
                                                            }
                                                        } ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-12 pull-right">
                                                    <button id="submit" type="submit" class="btn btn-success pull-right">
                                                        <span class="fa  fa-check"></span> Submit</button>
                                                    <a href="account_list.php">
                                                        <button type="button" class="btn btn btn-danger"> <span class="fa fa-times">
                                                        </span> Cancel</button></a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>