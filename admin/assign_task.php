<?php 
include('../include/header.php');
$section = $_GET['section'];
$date_created = date("Y-m-d");
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
    <title>Assign Task</title>
</head>

<div id="content" class="p-4 p-md-5 pt-5">
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

                                    <form data-toggle="validator" class="className" name="form" id="form" action="assign_task_submit.php" method="POST">
                                        <div class="form-group">
                                            <div class="form-group required">
                                                <label>Employee Name:</label>
                                                <select name="emp_name" id="emp_name" required
                                                    class="form-control selectpicker show-menu-arrow"
                                                    data-live-search="true" placeholder="Select Employee" onchange="selectname(this)">
                                                    <option disabled selected value="">--SELECT EMPLOYEE--</option>
                                                    <?php
                                                        $sql = mysqli_query($con,"SELECT * FROM accounts WHERE access='2' AND sec_id='$section'"); 
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
                                                <label>Select Tasks:</label><span class="pull-right help-block with-errors" style="margin: 0px; font-size: 11px;"></span>
                                                    <select name="tasks" id="tasks" required class="form-control selectpicker show-menu-arrow" data-live-search="true" placeholder="Select Tasks" >
                                                    </select>
                                            </div>

                                            <div class="form-group required">
                                                <label>Date Created:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="date" placeholder="Date Created" class="form-control"
                                                    name="date_created" id="date_created" value="<?php echo $date_created; ?>" readonly>
                                            </div>

                                            <div class="form-group required">
                                                <label>Due Date:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="date" placeholder="Due Date" class="form-control"
                                                    name="due_date" id="due_date" required>
                                                <input type="hidden" name="section" id="section" value="<?php echo $section ?>">
                                            </div>

                                            <div class="form-group required">
                                                <label>Need Attachment:</label><span class="pull-right help-block with-errors" style="margin: 0px; font-size: 11px;"></span>
                                                    <select name="requirement_status" required id="requirement_status" class="form-control selectpicker show-menu-arrow" data-live-search="true">
                                                        <option selected value="0">No</option>
                                                        <option value="1">Yes</option>
                                                    </select>
                                            </div>

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
</div>

<script>
function selectname(element) {
    let sid = $(element).val();
    if (sid) {
        $.ajax({
            type: "post",
            url: "assign_task_ajax.php",
            data: {
                "sid": sid
            },
            success: function(response) {
                $("select[name='tasks']").html(response).selectpicker('refresh');

            }
        });
    }
}

</script>

</html>