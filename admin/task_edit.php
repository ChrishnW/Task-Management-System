<?php 

include('../include/header.php');

$id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.'); 
$result = mysqli_query($con,"SELECT task_list.task_code, task_list.task_name, task_list.task_details, task_class.task_class, task_class.id as task_class_id, task_list.status, task_list.id, task_list.task_for, section.sec_name FROM task_list LEFT JOIN task_class ON task_list.task_class=task_class.id LEFT JOIN section ON task_list.task_for=section.sec_id WHERE task_list.id=$id");       
$row= mysqli_fetch_assoc($result);

$task_code = $row['task_code'];
$task_name = $row['task_name'];
$task_details = $row['task_details'];
$task_class = $row['task_class'];
$task_class_id = $row['task_class_id'];
$task_for = $row['task_for'];
$sec_name = $row['sec_name'];
$status = $row['status'];
$id = $row['id'];
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
    <title>Edit Task Information</title>
</head>

<body>
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Edit Task Information</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Edit Task Information Form
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">

                                    <form class="className" name="form" id="form" action="task_edit_submit.php"
                                        method="POST">
                                        <div class="form-group">

                                            <div data-toggle="validator" class="form-group required">
                                                <label>Task Code:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="Enter Task Code" class="form-control"
                                                    name="task_code" id="task_code" pattern="[a-zA-Z0-9-/ ]+"
                                                    data-error="Invalid input!" value="<?php echo $task_code; ?>" readonly required>
                                                <input type="hidden" class="form-control" name="id" id="id"
                                                    value="<?php echo $id; ?>" required>
                                            </div>

                                            <div data-toggle="validator" class="form-group required">
                                                <label>Task Name:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="Enter Task Name" class="form-control"
                                                    name="task_name" id="task_name" style="text-transform:uppercase" pattern="[a-zA-Z0-9-/ ]+"
                                                    data-error="Invalid input!" value="<?php echo $task_name; ?>" required>
                                            </div>

                                            <div data-toggle="validator" class="form-group required">
                                                <label>Task Details:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="Enter Task Details" class="form-control"
                                                    name="task_details" id="task_details" style="text-transform:uppercase" pattern="[a-zA-Z0-9-/ ]+"
                                                    data-error="Invalid input!" value="<?php echo $task_details; ?>">
                                            </div>

                                            <div data-toggle="validator" class="form-group required">
                                                <label>Task Class:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <select name="task_class" id="task_class" class="form-control selectpicker show-menu-arrow" data-live-search="true">
                                                <option selected value="<?php echo $task_class_id; ?>"><?php echo strtoupper($task_class); ?></option>
                                                <?php
                                                        $sql = mysqli_query($con,"SELECT * FROM task_class WHERE id!='$task_class_id' "); 
                                                        $con->next_result();
                                                        if(mysqli_num_rows($sql)>0){
                                                            while($row=mysqli_fetch_assoc($sql)){
                                                                $task_class_id1 = $row['id'];
                                                                $task_class1 = $row['task_class'];
                                                                echo "<option value='".$task_class_id1."'>".strtoupper($task_class1)."</option>";
                                                            }
                                                        } ?>
                                                </select>
                                            </div>

                                            <div data-toggle="validator" class="form-group required">
                                                <label>Task For:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <select name="task_for" id="task_for" class="form-control selectpicker show-menu-arrow" data-live-search="true">
                                                <option selected value="<?php echo $task_for; ?>"><?php echo strtoupper($sec_name); ?></option>
                                                <?php
                                                        $sql = mysqli_query($con,"SELECT * FROM section WHERE sec_id!='$task_for' "); 
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

                                            <div class="form-group required">
                                                <label>Status:</label><span class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <select name="status" id="status" class="form-control">
                                                    <?php
                                                        if ($status=="1") {
                                                            echo "<option selected value='1'>".'ACTIVE'."</option>
                                                            <option value='0'>".'INACTIVE'."</option>" ;}
                                                        if ($status=="0") {
                                                            echo "<option selected value='0'>".'INACTIVE'."</option>
                                                            <option value='1'>".'ACTIVE'."</option>" ;}
                                                        else {
                                                            echo "Unknown Status";  } ?>
                                                </select>
                                            </div>
                                            <br>

                                            <div class="form-group">
                                                <div class="col-sm-12 pull-right">
                                                    <button id="submit" type="submit" class="btn btn-success pull-right">
                                                        <span class="fa  fa-check"></span> Submit</button>
                                                    <a href="task_list.php">
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