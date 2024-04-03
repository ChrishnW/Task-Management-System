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
    <title>Register Department</title>
</head>

<body>
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Register Department</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Register Department Form
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">

                                    <form data-toggle="validator" class="className" name="form" id="form"
                                        action="department_add_submit.php" method="POST">
                                        <div class="form-group">

                                            <div class="form-group required">
                                                <label>Department ID:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                    <?php
                                                        $sql = mysqli_query($con,"SELECT * FROM department"); 
                                                        $con->next_result();
                                                        if(mysqli_num_rows($sql)>0){
                                                            while($row=mysqli_fetch_assoc($sql)){
                                                                $dept_id = $row['dept_id'];
                                                                $new_dept_id = $dept_id + 1; 
                                                            }
                                                        } ?>
                                                <input type="text" placeholder="Enter Department ID" class="form-control" name="dept_id" id="dept_id" value="<?php echo $new_dept_id?>" required readonly>
                                            </div>

                                            <div class="form-group required">
                                                <label>Department Name:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="Enter Department Name" class="form-control"
                                                    name="dept_name" id="dept_name" style="text-transform:uppercase"
                                                    pattern="[a-zA-Z0-9-/ ]+" data-error="Invalid input!" required>
                                            </div>

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

</body>

</html>