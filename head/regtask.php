<?php 
include('../include/header_head.php');
include('../include/bubbles.php');
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
    <title>Register Task</title>
</head>

<body>
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Register Task</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Register Task Form
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">

                                    <form data-toggle="validator" class="className" name="form" id="form"
                                        action="regtask_submit.php" method="POST">
                                            <div class="form-group required">
                                                <label>Task Name:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="ENTER TASK NAME" class="form-control"
                                                    name="task_name" id="task_name"
                                                    pattern="[a-zA-Z0-9-/ ]+" data-error="Invalid input!" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Task Details:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="ENTER TASK DETAILS" class="form-control"
                                                    name="task_details" id="task_details"
                                                    pattern="[a-zA-Z0-9-/ ]+" data-error="Invalid input!">
                                            </div>

                                            <div class="form-group required">
                                                <label>Task Classification:</label>
                                                <select name="task_class" id="task_class" required
                                                    class="form-control selectpicker show-menu-arrow"
                                                    data-live-search="true" placeholder="Select Task Classification">
                                                    <option disabled selected value="">--SELECT TASK CLASSIFICATION--</option>
                                                    <?php
                                                        $sql = mysqli_query($con,"SELECT * FROM task_class"); 
                                                        $con->next_result();
                                                        if(mysqli_num_rows($sql)>0){
                                                            while($row=mysqli_fetch_assoc($sql)){
                                                                $task_class_id = $row['id'];
                                                                $task_class = $row['task_class'];
                                                                echo "<option value='".$task_class_id."'>".strtoupper($task_class)."</option>";
                                                            }
                                                        } ?>
                                                </select>
                                            </div>

                                            <div class="form-group required">
                                                <label>Task For:</label>
                                                <select name="task_for" id="task_for" required
                                                    class="form-control selectpicker show-menu-arrow"
                                                    data-live-search="true" placeholder="Select Section Assigned">
                                                    <option disabled selected value="">--SELECT SECTION ASSIGNED--</option>
                                                    <?php
                                                        $sql = mysqli_query($con,"SELECT * FROM section"); 
                                                        $con->next_result();
                                                        if(mysqli_num_rows($sql)>0){
                                                            while($row=mysqli_fetch_assoc($sql)){
                                                                $sec_id1 = $row['sec_id'];
                                                                $sec_name1 = $row['sec_name'];
                                                                echo "<option value='".$sec_id1."'>".strtoupper($sec_name1)."</option>";
                                                            }
                                                        } ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-12 pull-right">
                                                    <button id="submit" type="submit" class="btn btn-success pull-right">
                                                        <span class="fa  fa-check"></span> Submit</button>
                                                    <a href="index.php">
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