<?php 

include('../include/header.php');

$id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.'); 
$result = mysqli_query($con,"SELECT * FROM department WHERE id=$id");       
$row= mysqli_fetch_assoc($result);

$dept_id = $row['dept_id'];
$dept_name = $row['dept_name'];
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
    <title>Edit Department Information</title>
</head>

<div id="content" class="p-4 p-md-5 pt-5">
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Edit Department Information</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Edit Department Information Form
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">

                                    <form class="className" name="form" id="form" action="department_edit_submit.php"
                                        method="POST">
                                        <div class="form-group">

                                            <div data-toggle="validator" class="form-group required">
                                                <label>Department ID:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="Enter Department ID" class="form-control"
                                                    name="dept_id" id="dept_id" pattern="[a-zA-Z0-9-/ ]+"
                                                    data-error="Invalid input!" value="<?php echo $dept_id; ?>" readonly>
                                                <input type="hidden" class="form-control" name="id" id="id"
                                                    value="<?php echo $id; ?>" required>
                                            </div>

                                            <div data-toggle="validator" class="form-group required">
                                                <label>Department Name:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="Department Name" class="form-control"
                                                    name="dept_name" id="dept_name" pattern="[a-zA-Z0-9-/ ]+"
                                                    data-error="Invalid input!" value="<?php echo $dept_name; ?>">
                                            </div>

                                            <div class="form-group required">
                                                <label>Status:</label><span class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <select name="status" id="status" class="form-control selectpicker show-menu-arrow" data-live-search="true">
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
                                                    <a href="department_list.php">
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
</div>

</html>