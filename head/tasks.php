
<?php 
include('../include/header_head.php');
include('../include/connect.php');
include('../include/bubbles.php');
$date_today = date('Y-m-d');
$status=isset($_GET['status']) ? $_GET['status'] : die('ERROR: Record not found.'); 
?>
<html>
<head>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
<link href="../assets/css/darkmode.css" rel="stylesheet">

    <title>Tasks</title>
</head>

<body>
    <div id="wrapper">
        <div id="page-wrapper">
        <h1 class="page-header"><?php echo $status ?> Tasks
        <a href="tasks_xls.php?status=<?php echo $status ?>" id="btn1"> <button class="btn btn-success pull-right"><span class="fa fa-download"></span> Download</button></a>
        </h1>
            <form method="POST" action="../admin/sortdl.php?status=<?php echo $status ?>">
            <div class="row">
                <div class="form-group col-lg-2">
                    <label>From:</label><br>
                    <input type="date" class="form-control" name="val_from" id="val_from" value="<?php echo $date_today; ?>"
                        onchange="selectfrom(this)">
                </div>
                <div class="form-group col-lg-2">
                    <label>To:</label><br>
                    <input type="date" class="form-control" name="val_to" id="val_to"
                        onchange="selectto(this)">
                </div>
                <input type="submit" id="submit" value="Download" class="btn btn-success pull-left" style="margin-top: 25px; display: none;">
            </div>
            </form>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                        <?php echo $status ?> Task
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table width="100%" class="table table-striped table-hover" id="table_task">
                                    <thead>
                                        <tr>
                                            <th class="col-lg-2">
                                                <center>Task Name</center>
                                            </th>
                                            <th class="col-lg-2">
                                                <center>Task Classification
                                            </th>
                                            <th class="col-lg-1">
                                                <center>Task For</center>
                                            </th>
                                            <th class="col-lg-1">
                                                <center>In-charge</center>
                                            </th>
                                            <th class="col-lg-1">
                                                <center>Date Created</center>
                                            </th>
                                            <th class="col-lg-1">
                                                <center>Due Date</center>
                                            </th>
                                            <th class="col-lg-1">
                                                <center>Status</center>
                                            </th>
                                            <?php
                                            if ($status == "FINISHED"){
                                            echo "
                                                <th class='col-lg-1'>
                                                    <center>Date Accomplished</center>
                                                </th>
                                                <th class='col-lg-2'>
                                                    <center>Achievement</center>
                                                </th>
                                                <th class='col-lg-1'>
                                                    <center>Remarks</center>
                                                </th>
                                                ";
                                            }
                                            ?>
                                        </tr>
                                    </thead>

                                    <tbody id="show_task">
                                        <?php
                                        /* and access!='1' */
                                        $con->next_result();
                                        $result = mysqli_query($con,"SELECT tasks_details.task_code, tasks_details.achievement, task_list.task_name, task_list.task_details, task_class.task_class, task_list.task_for, tasks_details.date_created, tasks_details.due_date, tasks_details.in_charge, tasks_details.status, tasks_details.date_accomplished, tasks_details.id, accounts.fname, accounts.lname, tasks_details.remarks, tasks_details.reschedule FROM tasks_details LEFT JOIN task_list ON task_list.task_code=tasks_details.task_code  LEFT JOIN task_class ON task_list.task_class=task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE tasks_details.task_status IS TRUE AND tasks_details.status='$status' AND tasks_details.approval_status IS TRUE  AND (tasks_details.reschedule = '0' OR tasks_details.reschedule = '2' AND tasks_details.approval_status=1) ORDER BY tasks_details.due_date ASC");               
                                        if (mysqli_num_rows($result)>0) { 
                                            while ($row = $result->fetch_assoc()) {
                                                $today = date("Y-m-d");
                                                $due_date = $row["due_date"];
                                                $nextDate = date('Y-m-d', strtotime($due_date . ' + ' . 1 . ' days'));
                                                $yesterday = date('Y-m-d', strtotime($today . ' -' . 1 . ' days'));
                                                $twodago = date('Y-m-d', strtotime($due_date . ' +' . 2 . ' days'));
                                                $task_class = $row['task_class'] ;
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

                                                if ($status == "FINISHED"){
                                                echo "<tr>  
                                                    <td class='".$class."'>" . $row["task_name"] . " </td>   
                                                    <td class='".$class."'><center />" . $row["task_class"] . "</td> 
                                                    <td class='".$class."'><center />" . $row["task_for"] . "</td>
                                                    <td class='".$class."'><center />" . $row["fname"].' '.$row["lname"] . "</td>
                                                    <td class='".$class."'><center />" . $row["date_created"] . "</td> 
                                                    <td class='".$class."'><center />" . $row["due_date"] . "</td> 
                                                    <td class='".$class."'><center /><p class='label label-".$class_label."' style='font-size:100%;'>".$sign."</p></td>
                                                    <td class='".$class."'><center />" . $row["date_accomplished"] . "</td>
                                                    <td class='".$class."'><center />" . $achievement . "</td>
                                                    <td class='".$class."'>" . $row["remarks"] . "</td>
                                                </tr>";
                                                }
                                                else {
                                                    echo "<tr>  
                                                    <td class='".$class."'>" . $row["task_name"] . " </td>   
                                                    <td class='".$class."'><center />" . $row["task_class"] . "</td> 
                                                    <td class='".$class."'><center />" . $row["task_for"] . "</td>
                                                    <td class='".$class."'><center />" . $row["fname"].' '.$row["lname"] . "</td>
                                                    <td class='".$class."'><center />" . $row["date_created"] . "</td> 
                                                    <td class='".$class."'><center />" . $row["due_date"] . "</td> 
                                                    <td class='".$class."'><center /><p class='label label-".$class_label."' style='font-size:100%;'>".$sign."</p></td>
                                                </tr>";
                                                }
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

<style>
@-webkit-keyframes invalid {
  from { background-color: red; }
  to { background-color: inherit; }
}
@-moz-keyframes invalid {
  from { background-color: red; }
  to { background-color: inherit; }
}
@-o-keyframes invalid {
  from { background-color: red; }
  to { background-color: inherit; }
}
@keyframes invalid {
  from { background-color: red; }
  to { background-color: inherit; }
}
.invalid {
  -webkit-animation: invalid 1s infinite; /* Safari 4+ */
  -moz-animation:    invalid 1s infinite; /* Fx 5+ */
  -o-animation:      invalid 1s infinite; /* Opera 12+ */
  animation:         invalid 1s infinite; /* IE 10+ */
}
</style>

<?php
if ($status == 'NOT YET STARTED'){
echo "<script>";
echo "$(document).ready(function() {
    $('#table_task').DataTable({
        responsive: true,
        'order': [[ 5, 'asc' ]]
    });
});";
echo "</script>";
}
elseif ($status == 'IN PROGRESS') {
echo "<script>";
echo "$(document).ready(function() {
    $('#table_task').DataTable({
        responsive: true,
        'order': [[ 5, 'asc' ]]
    });
});";
echo "</script>";
}
elseif ($status == 'FINISHED'){
echo "<script>";
echo "$(document).ready(function() {
    $('#table_task').DataTable({
        responsive: true,
        'order': [[ 7, 'desc' ]]
    });
});";
echo "</script>";
}
elseif ($status == 'RESCHEDULE'){
echo "<script>";
echo "$(document).ready(function() {
    $('#table_task').DataTable({
        responsive: true,
        'order': [[ 5, 'desc' ]]
    });
});";
echo "</script>";
}
?>

<script>
function selectfrom(element) {
    let valfrom = $(element).val();
    let status = <?php echo json_encode($status) ?>;
    let valto = $('#val_to').val();
    $('#table_task').DataTable().destroy();
    $('#show_task').empty();
    if (valfrom) {
        $.ajax({
            type: "post",
            url: "ajax_valfrom.php",
            data: {
                "valfrom": valfrom,
                "status": status,
                "valto": valto
            },
            success: function(response) {
                $('#show_task').append(response);
                $('#table_task').DataTable();
            }
        });
    }
}
</script>

<script>
function selectto(element) {
    let valto = $(element).val();
    let status = <?php echo json_encode($status) ?>;
    let valfrom = $('#val_from').val();
    $('#table_task').DataTable().destroy();
    $('#show_task').empty();
    if (valto) {
        $.ajax({
            type: "post",
            url: "ajax_valto.php",
            data: {
                "valfrom": valfrom,
                "status": status,
                "valto": valto
            },
            success: function(response) {
                $('#show_task').append(response);
                $('#table_task').DataTable();
            }
        });
    }
}
</script>
<script>
$(document).ready(function(){
    $('input[type="date"]').change(function(){
        if($('#val_from').val() !='' && $('#val_to').val() !=''){
            $('#submit').show();
            $('#btn1').hide();
        }
        else{
            $('#submit').hide();
            $('#btn1').hide();
        }
    });
});
</script>
<style>
    .red {
        color: red;
    }
</style>
</html>