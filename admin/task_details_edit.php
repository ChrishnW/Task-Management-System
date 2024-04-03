<?php 

include('../include/header.php');

$id=$_GET['id']; 
$section=$_GET['section']; 
$result = mysqli_query($con,"SELECT tasks_details.task_code, tasks_details.date_created, tasks_details.due_date, tasks_details.in_charge, tasks_details.status, tasks_details.date_accomplished, tasks_details.id, tasks_details.task_status, accounts.fname, accounts.lname FROM tasks_details INNER JOIN accounts ON accounts.username=tasks_details.in_charge WHERE tasks_details.id=$id");       
$row= mysqli_fetch_assoc($result);

$task_code = $row['task_code'];
$date_created = $row['date_created'];
$due_date = $row['due_date'];
$in_charge = $row['in_charge'];
$full_name = $row['fname']." ".$row['lname'];
$status = $row['status'];
$date_accomplished = $row['date_accomplished'];
$task_status = $row['task_status'];
$id = $row['id'];

if ($row['task_status'] == '1') {
  $task_status_word = "ACTIVE";
} else {
  $task_status_word = "INACTIVE";
}

if ($date_accomplished!=null) {
  $readonly="readonly";
} else {
  $readonly="";
}

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
    <title>Edit Task Details</title>
</head>

<body>
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Edit Task Details</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Edit Task Details Form
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">

                                    <form class="className" name="form" id="form" action="task_details_edit_submit.php"
                                        method="POST">
                                        <div class="form-group">

                                            <div data-toggle="validator" class="form-group">
                                                <label>Task Code:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="Enter Task Code" class="form-control"
                                                    name="task_code" id="task_code" pattern="[a-zA-Z0-9-/ ]+"
                                                    data-error="Invalid input!" value="<?php echo $task_code; ?>" readonly>
                                                <input type="hidden" class="form-control" name="id" id="id"
                                                    value="<?php echo $id; ?>" required>
                                                    <input type="hidden" class="form-control" name="section" id="section"
                                                    value="<?php echo $section; ?>">
                                            </div>

                                            <div data-toggle="validator" class="form-group">
                                                <label>Date Created:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="date" placeholder="Date Created" class="form-control"
                                                    name="date_created" id="date_created" value="<?php echo $date_created; ?>" readonly>
                                            </div>

                                            <div data-toggle="validator" class="form-group">
                                                <label>Due Date:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="date" placeholder="Due Date" class="form-control"
                                                    name="due_date" id="due_date" value="<?php echo $due_date; ?>" <?php echo $readonly ?>>
                                            </div>

                                            <div data-toggle="validator" class="form-group">
                                                <label>In Charge:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <select name="in_charge" id="in_charge" class="form-control selectpicker show-menu-arrow" data-live-search="true">
                                                <option disabled selected value="<?php echo $in_charge; ?>"><?php echo strtoupper($full_name); ?></option>
                                                </select>
                                            </div>

                                            <div data-toggle="validator" class="form-group">
                                                <label>Status:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <select name="status" id="status" class="form-control selectpicker show-menu-arrow" data-live-search="true">
                                                <option disabled selected value="<?php echo $status; ?>"><?php echo strtoupper($status); ?></option>
                                                </select>
                                            </div>

                                            <div data-toggle="validator" class="form-group">
                                                <label>Date Accomplished:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="date" placeholder="Date Accomplished" class="form-control"
                                                    name="date_accomplished" id="date_accomplished" value="<?php echo $date_accomplished; ?>" readonly>
                                            </div>

                                            <div data-toggle="validator" class="form-group">
                                                <label>Task Status:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <select name="task_status" id="task_status" class="form-control selectpicker show-menu-arrow" data-live-search="true">
                                                <option selected value="<?php echo $task_status; ?>"><?php echo strtoupper($task_status_word); ?></option>
                                                <?php
                                                if ($task_status=="1") {
                                                  echo "<option value='0'>INACTIVE</option>";
                                                }
                                                 else {
                                                  echo "<option value='1'>ACTIVE</option>";
                                                }
                                                ?>
                                                </select>
                                            </div>
                                            <br>
                                            <div class="form-group">
                                                <div class="col-sm-12 pull-right">
                                                    <button id="submit" type="submit" class="btn btn-success pull-right">
                                                        <span class="fa  fa-check"></span> Submit</button>
                                                    <a href="task_details.php?section=<?php echo $section ?>">
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