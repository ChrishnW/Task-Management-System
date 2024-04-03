<?php 
include('../include/header_head.php');
$in_charge = $_GET['in_charge'];
?>

<html>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
<link href="../assets/css/darkmode.css" rel="stylesheet">

<head>
    <title>Register Task</title>
</head>

<div id="content" class="p-4 p-md-5 pt-5">
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Create & Assign New Task to Employee</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Task Form
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form data-toggle="validator" class="className" name="form" id="form" action="regtask_submit.php" method="POST">
                                            <div class="form-group required">
                                                <label>Task Name:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="Enter Task Name" class="form-control" name="task_name" id="task_name" pattern="[a-zA-Z0-9-/ ]+" data-error="Invalid input!" autocomplete="off" autofocus required>
                                            </div>

                                            <div class="form-group">
                                                <label>Task Details:</label><span class="pull-right help-block with-errors" style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="Enter Task Details" class="form-control" name="task_details" id="task_details" pattern="[a-zA-Z0-9-/ ]+" data-error="Invalid input!" autocomplete="off">
                                            </div>

                                            <!-- <div class="form-group required">
                                                <label>Task Classification:</label>
                                                <select name="task_class" id="task_class" required
                                                    class="form-control selectpicker show-menu-arrow"
                                                    data-live-search="true" placeholder="Select Task Classification">
                                                    <option disabled selected value="">--Select Task Classification--</option>
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
                                            </div> -->

                                            <div class="form-group required">
                                                <label>Employee Name:</label>
                                                <input type="text" id="in_charge" name="in_charge" value="<?php echo $in_charge ?>" hidden>
                                                <select name="emp_name" id="emp_name" required class="form-control selectpicker show-menu-arrow" data-live-search="true" placeholder="Select Employee" onchange="selectname(this)" disabled>
                                                    <?php
                                                        $sql = mysqli_query($con,"SELECT * FROM accounts WHERE access=2 AND username = '$in_charge'"); 
                                                        $con->next_result();
                                                        if(mysqli_num_rows($sql)>0){
                                                            while($row=mysqli_fetch_assoc($sql)){
                                                                $emp_name = $row['fname'].' ' .$row['lname'];
                                                                $username = $row['username'];
                                                                $section = $row['sec_id'];
                                                                echo "<option value='".$username."'>".strtoupper($emp_name)."</option>";
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                                <input type="text" value="<?php echo $section ?>" id="task_for" name="task_for" hidden>
                                            </div>

                                            <div class="form-group required">
                                                <label>Due Date:</label><span class="pull-right help-block with-errors" style="margin: 0px; font-size: 11px;"></span>
                                                <input type="date" placeholder="Due Date" class="form-control" name="due_date" id="due_date" required>
                                                <input type="hidden" name="section" id="section" value="<?php echo $section ?>">
                                            </div>

                                            <div class="form-group required">
                                                <label>Need Attachment:</label><span class="pull-right help-block with-errors" style="margin: 0px; font-size: 11px;"></span>
                                                    <select name="requirement_status" required id="requirement_status" class="form-control selectpicker show-menu-arrow" data-live-search="true">
                                                        <option selected value="0">No</option>
                                                        <option value="1">Yes</option>
                                                    </select>
                                            </div>
                                            <button id="submit" type="submit" class="btn btn-success pull-right"> <span class="fa fa-check"></span> Submit</button>
                                            <a href="#" onclick="history.back()"> <button type="button" class="btn btn btn-danger"> <span class="fa fa-times"></span> Cancel</button></a>
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
</div>

</html>