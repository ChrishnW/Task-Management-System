<?php 

include('../include/header.php');

$id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.'); 
$result = mysqli_query($con,"SELECT section.id, section.sec_id, section.sec_name, department.dept_name, department.dept_id, section.status FROM section LEFT JOIN department ON department.dept_id=section.dept_id WHERE section.id=$id");       
$row= mysqli_fetch_assoc($result);

$sec_id = $row['sec_id'];
$sec_name = $row['sec_name'];
$dept_name = $row['dept_name'];
$dept_id = $row['dept_id'];
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
    <title>Edit Section Information</title>
</head>

<div id="content" class="p-4 p-md-5 pt-5">
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Edit Section Information</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Edit Section Information Form
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">

                                    <form class="className" name="form" id="form" action="section_edit_submit.php"
                                        method="POST">
                                        <div class="form-group">

                                            <div data-toggle="validator" class="form-group required">
                                                <label>Section Name:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="Enter Section Name" class="form-control"
                                                    name="sec_name" id="sec_name" pattern="[a-zA-Z0-9-/ ]+"
                                                    data-error="Invalid input!" value="<?php echo $sec_name; ?>" readonly>
                                                <input type="hidden" class="form-control" name="id" id="id"
                                                    value="<?php echo $id; ?>" required>
                                            </div>

                                            <div data-toggle="validator" class="form-group required">
                                                <label>Section ID:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="Section ID" class="form-control"
                                                    name="sec_id" id="sec_id" pattern="[a-zA-Z0-9-/ ]+"
                                                    data-error="Invalid input!" value="<?php echo $sec_id; ?>" readonly>
                                            </div>

                                            
                                            <div data-toggle="validator" class="form-group required">
                                                <label>Department:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <select name="dept" id="dept" class="form-control selectpicker show-menu-arrow" data-live-search="true">
                                                <option selected value="<?php echo $dept_id; ?>"><?php echo $dept_name; ?></option>
                                                <?php
                                                        $sql = mysqli_query($con,"SELECT * FROM department WHERE dept_id!='$dept_id' "); 
                                                        $con->next_result();
                                                        if(mysqli_num_rows($sql)>0){
                                                            while($row=mysqli_fetch_assoc($sql)){
                                                                $dept_id1 = $row['dept_id'];
                                                                $dept_name1 = $row['dept_name'];
                                                                echo "<option value='".$dept_id1."'>".$dept_name1."</option>";
                                                            }
                                                        } ?>
                                                </select>
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
                                                    <a href="section_list.php">
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