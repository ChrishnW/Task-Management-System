<?php 
include('../include/header.php');
?>

<html>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
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
    <title>Assign Task</title>
</head>

<body>
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Assign Task/(s)</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                        Task/(s) Assignment Form
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">

                                    <form data-toggle="validator" class="className" name="form" id="form"
                                        action="manage_task_add_submit.php" method="POST">
                                        <div class="form-group">

                                            <div class="form-group required">
                                                <label>Employee Name:</label>
                                                <select name="emp_name" id="emp_name" required
                                                    class="form-control selectpicker show-menu-arrow"
                                                    data-live-search="true" placeholder="Select Employee" onchange="selectname(this)">
                                                    <option disabled selected value="">--SELECT EMPLOYEE--</option>
                                                    <?php
                                                        $sql = mysqli_query($con,"SELECT * FROM accounts WHERE access=2"); 
                                                        $con->next_result();
                                                        if(mysqli_num_rows($sql)>0){
                                                            while($row=mysqli_fetch_assoc($sql)){
                                                                $emp_name = $row['fname'].' ' .$row['lname'];
                                                                $username = $row['username'];
                                                                echo "<option value='".$username."'>".strtoupper($emp_name)."</option>";
                                                            }
                                                        } ?>
                                                </select>
                                            </div>

                                            <div class="form-group required">
                                                <label>Section:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                    <select name="emp_section" id="emp_section" required
                                                    class="form-control selectpicker show-menu-arrow"
                                                    data-live-search="true" placeholder="Select Section" onchange="selectsection(this)" >
                                                    </select>
                                            </div>

                                            <div class="form-group required">
                                                <label>Select Task:</label>
                                                <select name="tasks[]" id="tasks" required
                                                    class="form-control selectpicker show-menu-arrow"
                                                    data-live-search="true" placeholder="Select Task" multiple="multiple">
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-12 pull-right">
                                                    <button id="submit" type="submit" class="btn btn-success pull-right">
                                                        <span class="fa  fa-check"></span> Submit</button>
                                                    <a href="manage_task_list.php">
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

<script>
function selectname(element) {
    let sid = $(element).val();
    if (sid) {
        $.ajax({
            type: "post",
            url: "manage_task_ajax_section.php",
            data: {
                "sid": sid
            },
            success: function(response) {
                $("select[name='emp_section']").html(response).selectpicker('refresh');

            }
        });
    }
}

function selectsection(element) {
    let sid2 = $(element).val();
    if (sid2) {
        $.ajax({
            type: "post",
            url: "manage_task_ajax_assign.php",
            data: {
                "sid2": sid2
            },
            success: function(response) {
                $("select[name='tasks[]']").html(response).selectpicker('refresh');

            }
        });
    }
}

</script>

</html>