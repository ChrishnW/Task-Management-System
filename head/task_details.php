
<?php 
include('../include/header_head.php');
include('../include/connect.php');
include('../include/bubbles.php');
$section=isset($_GET['section']) ? $_GET['section'] : die('ERROR: Record not found.'); 
?>
<html>
<head>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
<link href="../assets/css/darkmode.css" rel="stylesheet">

    <title>Task Details</title>
</head>

<body>
    <div id="wrapper">
        <div id="page-wrapper">
        <br>
        <h1 class="page-header"><?php echo $section ?> Task Details
        <a href="task_details_xls.php?section=<?php echo $section ?>"> <button class="btn btn-success pull-right"><span class="fa fa-download"></span> Download</button></a></h1>
            <div class="row">
                <div class="col-lg-4">
                <label>Status:</label><br>
                    <select name="show_status" id="show_status" class="form-control selectpicker show-menu-arrow "
                        placeholder="" onchange="selectstatus(this)">
                        <option disabled selected value="">--Filter by Status--</option>
                        <option selected value="ALL">ALL</option>
                        <option value="NOT YET STARTED">NOT YET STARTED</option>
                        <option value="IN PROGRESS">IN PROGRESS</option>
                        <option value="FINISHED">FINISHED</option>
                    </select>
                    <br>
                    <br>
                </div>
                <div class="col-lg-4">
                <label>Task Status:</label><br>
                    <select name="show_status" id="show_status" class="form-control selectpicker show-menu-arrow "
                        placeholder="" onchange="selecttaskstatus(this)">
                        <option disabled selected value="">--Filter by Task Status--</option>
                        <option selected value="ALL">ALL</option>
                        <option value="1">ACTIVE</option>
                        <option value="0">INACTIVE</option>
                    </select>
                    <br>
                    <br>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                        <?php echo $section ?> Task Details
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                           
                                <table width="100%" class="table table-striped table-bordered table-hover "
                                    id="table_task">

                                    <thead>
                                        <tr>
                                            <th class="col-lg-2">
                                                Task Name
                                            </th>
                                            <th class="col-lg-2">
                                                Task Classification
                                            </th>
                                            <th class="col-lg-2">
                                                Task For
                                            </th>
                                            <th class="col-lg-1">
                                                Date Created
                                            </th>
                                            <th class="col-lg-1">
                                                Due Date
                                            </th>
                                            <th class="col-lg-1">
                                                In-charge
                                            </th>
                                            <th class="col-lg-1">
                                                Status
                                            </th>
                                            <th class="col-lg-1">
                                                Date Accomplished
                                            </th>
                                            <th class="col-lg-1">
                                                Remarks
                                            </th>
                                            <th class="col-lg-1">
                                                Achievement
                                            </th>
                                          
                                        </tr>
                                    </thead>

                                    <tbody id="show_task">
                                        <?php
                                        /* and access!='1' */
                                        $con->next_result();
                                        $result = mysqli_query($con,"SELECT tasks_details.task_code, tasks_details.achievement,task_list.task_name, task_list.task_details, task_class.task_class, task_list.task_for, tasks_details.date_created, tasks_details.due_date, tasks_details.in_charge, tasks_details.status, tasks_details.date_accomplished, tasks_details.id, accounts.fname, accounts.lname, tasks_details.remarks, tasks_details.reschedule  FROM tasks_details LEFT JOIN task_list ON task_list.task_code=tasks_details.task_code  LEFT JOIN task_class ON task_list.task_class=task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE task_list.task_for='$section' AND tasks_details.task_status IS TRUE ORDER BY tasks_details.due_date ASC");               
                                        if (mysqli_num_rows($result)>0) { 
                                            while ($row = $result->fetch_assoc()) {
                                                $today = date("Y-m-d");
                                                $achievement = $row['achievement'];
                                                $due_date = $row["due_date"];
                                                $nextDate = date('Y-m-d', strtotime($due_date . ' + ' . 1 . ' days'));
                                                $yesterday = date('Y-m-d', strtotime($today . ' -' . 1 . ' days'));
                                                $twodago = date('Y-m-d', strtotime($due_date . ' +' . 2 . ' days'));
                                                $task_class = $row['task_class'];
                                                $status = $row['status'];
                                                $class = "";
                                                $sign = "";
                                                
                                                if ($status == "NOT YET STARTED") {
                                                    // DAILY, ADDITIONAL AND PROJECT
                                                    if ($task_class == "DAILY ROUTINE" || $task_class == "ADDITIONAL TASK" || $task_class == "PROJECT"){
                                                        if ($due_date < $today){
                                                            $class_label = "danger";
                                                            $sign = "EXPIRED";
                                                            $class = "invalid";
                                                        }
                                                        elseif ($due_date > $today){
                                                            $class_label = "info";
                                                            $sign = "PENDING";
                                                        }
                                                        elseif ($due_date == $today){
                                                            $class_label = "primary";
                                                            $sign = "NOT YET STARTED";
                                                        }
                                                        else {
                                                            $class_label = "muted";
                                                            $sign = "INVALID";
                                                        }
                                                    }
                                                    // WEEKLY
                                                    if ($task_class == "WEEKLY ROUTINE"){
                                                        if ($twodago  <= $today){
                                                            $class_label = "danger";
                                                            $sign = "EXPIRED";
                                                            $class = "invalid";
                                                        }
                                                        elseif ($due_date <= $yesterday){
                                                            $class_label = "warning";
                                                            $sign = "EXPIRING";
                                                        }
                                                        elseif ($due_date == $today) {
                                                            $class_label = "primary";
                                                            $sign = "NOT YET STARTED";
                                                        }
                                                        elseif ($due_date >= $today) {
                                                            $class_label = "info";
                                                            $sign = "PENDING";
                                                        }
                                                        
                                                    }
                                                    // MONTHLY
                                                    if ($task_class == "MONTHLY ROUTINE"){
                                                        if ($twodago  <= $today){
                                                            $class_label = "danger";
                                                            $sign = "EXPIRED";
                                                            $class = "invalid";
                                                        }
                                                        elseif ($due_date <= $yesterday){
                                                            $class_label = "warning";
                                                            $sign = "EXPIRING";
                                                        }
                                                        elseif ($due_date >= $today) {
                                                            $class_label = "primary";
                                                            $sign = "NOT YET STARTED";
                                                        }
                                                    }
                                                }
                                                
                                                if ($status == "IN PROGRESS"){
                                                    if (($today > $due_date && ($task_class == "DAILY ROUTINE" || $task_class == "ADDITIONAL TASK" || $task_class == "PROJECT"))
                                                    || ($twodago  <= $today && ($task_class == "WEEKLY ROUTINE" || $task_class == "MONTHLY ROUTINE"))){
                                                        $class = "invalid";
                                                        $sign = "OVERDUE";
                                                        $class_label = "danger";
                                                    }
                                                    else {
                                                        $sign = "IN PROGRESS";
                                                        $class_label = "warning";
                                                    }
                                                }

                                                if ($status == "FINISHED"){
                                                    $achievement = $row['achievement'];
                                                    if ($achievement == 0){
                                                        $class_label = "danger";
                                                        $sign = "FAILED";
                                                    }
                                                    if ($achievement > 0){
                                                        $class_label = "success";
                                                        $sign = "FINISHED";
                                                    }
                                                }

                                                echo "<tr>   
                                                    <td class='".$class."'> " . $row["task_name"] . " </td>
                                                    <td class='".$class."'>" . $row["task_class"] . "</td> 
                                                    <td class='".$class."'>" . $row["task_for"] . "</td> 
                                                    <td class='".$class."'>" . $row["date_created"] . "</td> 
                                                    <td class='".$class."'>" . $row["due_date"] . "</td> 
                                                    <td class='".$class."'>" . $row["fname"].' '.$row["lname"] . "</td>
                                                    <td><center/><p class='label label-".$class_label."' style='font-size:100%;'>".$sign."</p></td>
                                                    <td class='".$class."'>" . $row["date_accomplished"] . "</td>
                                                    <td class='".$class."'>" . $row["remarks"] . "</td> 
                                                    <td class='".$class."'>" . $achievement . "</td>
                                                 
                                                </tr>";   
                                            }
                                        } 
                                        if ($con->connect_error) {
                                            die("Connection Failed".$con->connect_error); }; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <a href="./index.php"> <button class='btn btn-danger pull-left'><i class="fa fa-arrow-left"></i> Return to Dashboard</button></a>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
$(document).ready(function() {
    $('#table_task').DataTable({
        responsive: true,
        "order": [[ 4, "desc" ]]
    });
});
</script>

<script>
function selectstatus(element) {
    let sid = $(element).val();
    let section = <?php echo json_encode($section) ?>;
    $('#table_task').DataTable().destroy();
    $('#show_task').empty();
    if (sid) {
        $.ajax({
            type: "post",
            url: "task_details_ajax.php",
            data: {
                "sid": sid,
                "section": section
            },
            success: function(response) {
                $('#show_task').append(response);
                $('#table_task').DataTable();
            }
        });
    }
}

function selecttaskstatus(element) {
    let sid = $(element).val();
    let section = <?php echo json_encode($section) ?>;
    $('#table_task').DataTable().destroy();
    $('#show_task').empty();
    if (sid) {
        $.ajax({
            type: "post",
            url: "task_details_status_ajax.php",
            data: {
                "sid": sid,
                "section": section
            },
            success: function(response) {
                $('#show_task').append(response);
                $('#table_task').DataTable();
            }
        });
    }
}
</script>
<style>
    .red {
        color: red;
    }
</style>
</html>