
<?php 
include('../include/header_head.php');
include('../include/connect.php');
include('../include/bubbles.php');
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
        <br>
        <h1 class="page-header"><?php echo $status ?> Tasks
        <a href="task_approval_xls.php?status=<?php echo $status ?>"> <button class="btn btn-success pull-right"><span class="fa fa-download"></span> Download</button></a></h1>
            <div class="row">
                <div class="form-group col-lg-2">
                    <label>From:</label><br>
                    <input type="date" class="form-control" name="val_from" id="val_from"
                        onchange="selectfrom(this)">
                </div>
                <div class="form-group col-lg-2">
                    <label>To:</label><br>
                    <input type="date" class="form-control" name="val_to" id="val_to"
                        onchange="selectto(this)">
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                        <?php echo $status ?> Task
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table width="100%" class="table table-striped table-bordered table-hover"
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
                                                Old Due Date
                                            </th>
                                            <th class="col-lg-1">
                                                New Due Date
                                            </th>
                                            <th class="col-lg-1">
                                                In-charge
                                            </th>
                                            <th class="col-lg-1">
                                                Action
                                            </th>

                                        </tr>
                                    </thead>

                                    <tbody id="show_task">
                                        <?php
                                        /* and access!='1' */
                                        $con->next_result();
                                        $result = mysqli_query($con,"SELECT tasks_details.task_code, task_list.task_name, task_class.task_class, task_list.task_for, tasks_details.date_created, tasks_details.due_date, tasks_details.in_charge, tasks_details.id, accounts.fname, accounts.lname, tasks_details.reschedule, tasks_details.approval_status, tasks_details.resched_reason
                                        FROM tasks_details 
                                        LEFT JOIN task_list ON task_list.task_code=tasks_details.task_code  
                                        LEFT JOIN task_class ON task_list.task_class=task_class.id 
                                        LEFT JOIN accounts ON tasks_details.in_charge=accounts.username 
                                        WHERE tasks_details.task_status IS TRUE AND tasks_details.status='NOT YET STARTED' AND tasks_details.approval_status = 0  AND tasks_details.reschedule = 2 ORDER BY  tasks_details.date_created ASC");               
                                        if (mysqli_num_rows($result)>0) { 
                                            while ($row = $result->fetch_assoc()) {
                                               
                                                $taskcode = $row['task_code'];
                                                $query = mysqli_query($con,"SELECT due_date AS old_due_date FROM tasks_details WHERE status='NOT YET STARTED' AND approval_status = 1 AND  task_code = '$taskcode' AND reschedule = 1");
                                                $row1= $query->fetch_assoc();

                                                $today = date("Y-m-d");
                                                $due_date = $row["due_date"];
                                                $class = "";
                                                if ($today > $due_date) {
                                                    $class = "red";
                                                }

                                                echo "<tr class='".$class."'>  
                                                    <td> " . $row["task_name"] . " </td>   
                                                    <td>" . $row["task_class"] . "</td> 
                                                    <td>" . $row["task_for"] . "</td> 
                                                    <td>" . $row["date_created"] . "</td> 
                                                    <td>" . $row1['old_due_date'] . "</td> 
                                                    <td>" . $row["due_date"] . "</td> 
                                                    <td>" . $row["fname"].' '.$row["lname"] . "</td>
                                                    <td><center><button id='task_id' value='".$row['id']."' data-reason = '".$row['resched_reason']."' data-date = '".$row['due_date']."' class='btn btn-primary' onclick='view(this)'> View </button></center></td>
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
<div class="modal fade" id="view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <a href="task_approval.php?status=RESCHEDULE"><button type="button" class="close" aria-hidden="true">&times;</button></a>
                <h4 class="modal-title" id="myModalLabel">Approve Request</h4>
            </div>
            <div class="modal-body panel-body">
            
                    <input type="hidden" id="hidden_task_id" name="hidden_task_id">
                    <label>Request Due Date:</label>
                        <input type="date" class="form-control" type="request_date" name="request_date"  id="request_date"><br>
                    <label>Reason for reschedule:</label>
                        <textarea readonly name="resched_reason" id="resched_reason" class="form-control"></textarea>
                
            </div>
            <div class="modal-footer">
              <button id='okButton' class='btn btn-success pull-right' onclick='okButtonClick()'>Approve</button>
              <!-- <a href="task_approval.php?status=RESCHEDULE"><button type="button" name="submit" class="btn btn-danger pull-left">Cancel</button></a> -->
            </div>
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <a href="task_approval.php?status=RESCHEDULE"><button type="button" class="close" aria-hidden="true">&times;</button></a>
                <h4 class="modal-title" id="myModalLabel">Notice</h4>
            </div>
            <div class="modal-body panel-body">
                <center>
                    <i style="color:#e13232; font-size:80px;" class="fa fa-fa-check"></i>
                    <br><br>
                    <p>Task has been Rescheduled.</p>
                </center>
            </div>
            <div class="modal-footer">
              <a href="task_approval.php?status=RESCHEDULE"><button type="button" name="submit" class="btn btn-success pull-right">OK</button></a>
            </div>
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>
<script>
$(document).ready(function() {
   
    let table = $('#table_task');
    
    $('#table_task').DataTable({
        "order":[[3,"asc"]],
        responsive: true,
        lengthMenu: [[10,15,20,50],[10,15,20,50]],
        pageLength: 10

    });


});
</script>

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
            url: "ajax_valfrom1.php",
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
            url: "ajax_valto1.php",
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
<style>
    .red {
        color: red;
    }
</style>
<script>   
function view(element) {
    var taskID = element.value;
    var reason = element.getAttribute("data-reason");
    var date = element.getAttribute("data-date");
    var currentDate = new Date();
    var day = currentDate.getDate();
    var month = currentDate.getMonth() + 1;
    var year = currentDate.getFullYear();

    currentDate = year+'-'+month+'-'+day;
    
    if (date < currentDate )
    {
        var expired = 'red';
    }
    else {
        var expired = '';
    }
    $(document).ready(function() { 
        $('#view').modal('show'); 
        document.getElementById('hidden_task_id').
        value = taskID; 
        document.getElementById('resched_reason').
        value = reason;   
        document.getElementById('request_date').
        value = date;   
        document.getElementById("request_date").style.color = expired;
    });
}

function okButtonClick() {
    var taskID = document.getElementById('hidden_task_id').value;
    var reason = document.getElementById('resched_reason').value;
    var date = document.getElementById('request_date').value;
    $.ajax({
        type: "POST",
        url: "task_approval_submit.php",
        data: { id: taskID, reason: reason, date: date}
    }).done(function(response) {
        $('#view').modal('hide'); 
        $('#success').modal('show'); 
    
        //  window.location.reload();
    }).fail(function(xhr, status, error) {
        alert("An error occurred: " + status + "\nError: " + error);
    });
}


</script>
</html>