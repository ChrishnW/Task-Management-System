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
    <title>Register Section</title>
</head>

<body>
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Register Section</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Register Section Form
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">

                                    <form data-toggle="validator" class="className" name="form" id="form"
                                        action="section_add_submit.php" method="POST">
                                        <div class="form-group">

                                            <div class="form-group required">
                                                <label>Section ID:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="Enter Section ID" class="form-control"
                                                    name="sec_id" id="sec_id" style="text-transform:uppercase"
                                                    pattern="[a-zA-Z0-9-/ ]+" data-error="Invalid input!" required>
                                            </div>

                                            <div class="form-group required">
                                                <label>Section Name:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="Enter Section Name" class="form-control"
                                                    name="sec_name" id="sec_name" style="text-transform:uppercase"
                                                    pattern="[a-zA-Z0-9-/ ]+" data-error="Invalid input!" required>
                                            </div>

                                            <div class="form-group required">
                                                <label>Department:</label>
                                                <select name="dept" id="dept" required
                                                    class="form-control selectpicker show-menu-arrow"
                                                    data-live-search="true" placeholder="Select Access">
                                                    <option disabled selected value="">--SELECT DEPARTMENT--</option>
                                                    <?php
                                                        $sql = mysqli_query($con,"SELECT * FROM department"); 
                                                        $con->next_result();
                                                        if(mysqli_num_rows($sql)>0){
                                                            while($row=mysqli_fetch_assoc($sql)){
                                                                $dept_id = $row['dept_id'];
                                                                $dept_name = $row['dept_name'];
                                                                echo "<option value='".$dept_id."'>".$dept_name."</option>";
                                                            }
                                                        } ?>
                                                </select>
                                            </div>

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

</body>

</html>