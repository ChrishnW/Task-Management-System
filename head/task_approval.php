
<?php 
include('../include/header_head.php');
include('../include/connect.php');
$date_today = date('Y-m-d');
?>
<html>
<head>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
<link href="../assets/css/darkmode.css" rel="stylesheet">

    <title>Tasks</title>
</head>

<div id="content" class="p-4 p-md-5 pt-5">
    <div id="wrapper">
        <div id="page-wrapper">
        <h1 class="page-header"> Request List for Re-Scheduling </h1>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                        Request for Task Rescheduling
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table width="100%" class="table table-striped table-hover" id="table_task">
                                    <thead>
                                        <tr>
                                            <th class="col-lg-2">
                                                Task Code
                                            </th>
                                            <th class="col-lg-2">
                                                Task Name
                                            </th>
                                            <th class="col-lg-2">
                                                Task Classification
                                            </th>
                                            <th class="col-lg-1">
                                                Expired Due Date
                                            </th>
                                            <th class="col-lg-1">
                                                New Due Date
                                            </th>
                                            <th class="col-lg-1">
                                                Assignee
                                            </th>
                                            <th class="col-lg-1">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody id="show_task">
                                    <?php
                                        $con->next_result();
                                        $result = mysqli_query($con,"SELECT * FROM tasks_details JOIN task_class ON tasks_details.task_class = task_class.id JOIN accounts ON tasks_details.in_charge=accounts.username JOIN section ON section.sec_id=tasks_details.task_for WHERE tasks_details.status='NOT YET STARTED' AND tasks_details.reschedule>0 AND section.dept_id='$dept_id'");           
                                        if (mysqli_num_rows($result)>0) { 
                                            while ($row = $result->fetch_assoc()) {
                                                $today = date("Y-m-d");
                                                $due_date = $row["due_date"];
                                                $old_due = $row['old_due'];
                                                $due = date('m / d / Y', strtotime($row['due_date']));
                                                $date = date('m / d / Y', strtotime($row['old_due']));
                                                $class = "";
                                                $emp_name=$row['fname'].' '.$row['lname'];
                                                if (empty($row["file_name"])) {
                                                    // Use a default image URL
                                                    $imageURL = '../assets/img/user-profiles/nologo.png';
                                                } else {
                                                    // Use the image URL from the database
                                                    $imageURL = '../assets/img/user-profiles/'.$row["file_name"];
                                                } 

                                                echo "<tr>
                                                    <td> " . $row["task_code"] . " </td>  
                                                    <td id='normalwrap'> " . $row["task_name"] . " </td>   
                                                    <td>" . $row["task_class"] . "</td>
                                                    <td>" . $date . "</td> 
                                                    <td>" . $due . "</td> 
                                                    <td style='text-align: justify'> <img src=".$imageURL." class='profile' title=".$row["username"]." style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-right: 15px; margin-left: 0'>" . $emp_name . "</td>  
                                                    <td><center><button id='task_id' value='".$row['task_code']."' data-reason = '".$row['resched_reason']."' data-date = '".$row['due_date']."' data-case='".$row['reschedule']."' class='btn btn-primary' onclick='view(this)'> View </button></center></td>
                                                </tr>";
                                            }
                                        }
                                        if ($con->connect_error) {
                                            die("Connection Failed".$con->connect_error); 
                                        }; 
                                    ?>
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
</div>

<div class="modal fade" id="view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <a href="task_approval.php"><button type="button" class="close" aria-hidden="true">&times;</button></a>
                <h4 class="modal-title" id="myModalLabel">Approve Request</h4>
            </div>
            <div class="modal-body panel-body">
                    <input type="hidden" id="hidden_case_id" name="hidden_case_id">
                    <input type="hidden" id="hidden_task_id" name="hidden_task_id">
                    <div class="form-group col-lg-3" requred>
                        <label>Request Due Date:</label>
                        <input type="date" class="form-control" type="request_date" name="request_date"  id="request_date"><br>
                    </div>
                    <p style="color: yellow">Note: You can change the requestor's given due date.</p>
                    <p style="color: yellow">Remember that the requested due date might be behind the actual date today.</p>
                    <div class="form-group col-lg-12" requred>
                        <label>Reason for reschedule:</label>
                        <textarea readonly name="resched_reason" id="resched_reason" class="form-control"></textarea>
                    </div>
            </div>
            <div class="modal-footer">
              <button id='okButton' class='btn btn-success pull-left' onclick='okButtonClick()'>Approve</button>
              <!-- <button id='declineButton' class="btn btn-danger pull-right" onclick='declineButton()'>Decline</button> -->
              <a href="task_approval.php"><button type="button" name="submit" class="btn btn-danger pull-right">Back</button></a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <a href="task_approval.php"><button type="button" class="close" aria-hidden="true">&times;</button></a>
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
              <a href="task_approval.php"><button type="button" name="submit" class="btn btn-success pull-right">OK</button></a>
            </div>
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="declined" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <a href="task_approval.php"><button type="button" class="close" aria-hidden="true">&times;</button></a>
                <h4 class="modal-title" id="myModalLabel">Notice</h4>
            </div>
            <div class="modal-body panel-body">
                <center>
                    <i style="color:#e13232; font-size:80px;" class="fa fa-fa-check"></i>
                    <br><br>
                    <p>Reschedule Request has been Decline.</p>
                </center>
            </div>
            <div class="modal-footer">
              <a href="task_approval.php"><button type="button" name="submit" class="btn btn-success pull-right">OK</button></a>
            </div>
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>

<script>
$(document).ready(function() {
   
    let table = $('#table_task');
    
    $('#table_task').DataTable({
        "order":[[4,"asc"]],
        responsive: true,
        lengthMenu: [[10,15,20,50],[10,15,20,50]],
        pageLength: 10

    });

});
  
function view(element) {
    var taskID = element.value;
    var reason = element.getAttribute("data-reason");
    var date = element.getAttribute("data-date");
    var caseid = element.getAttribute("data-case");
    var currentDate = new Date();
    var day = currentDate.getDate();
    var month = currentDate.getMonth();
    var year = currentDate.getFullYear();
    currentDate = year + '-' + month + '-' + day;

    $(document).ready(function() {
        $('#view').modal('show');
        document.getElementById('hidden_case_id').value = caseid;
        document.getElementById('hidden_task_id').value = taskID;
        document.getElementById('resched_reason').value = reason;
        document.getElementById('request_date').value = date;
    });
}

function okButtonClick() {
    var taskID = document.getElementById('hidden_task_id').value;
    var casetrack = document.getElementById('hidden_case_id').value;
    var reason = document.getElementById('resched_reason').value;
    var date = document.getElementById('request_date').value;
    $.ajax({
        type: "POST",
        url: "task_approval_submit.php",
        data: { id: taskID, date: date, case: casetrack }
    }).done(function(response) {
        console.log("Casetrack value:", casetrack);
        
        $('#view').modal('hide'); 
        $('#success').modal('show'); 
    
        //  window.location.reload();
    }).fail(function(xhr, status, error) {
        alert("An error occurred: " + status + "\nError: " + error);
    });
}

function declineButton() {
    var taskID = document.getElementById('hidden_task_id').value;
    $.ajax({
        type: "POST",
        url: "task_decline_submit.php",
        data: { id: taskID}
    }).done(function(response) {
        $('#view').modal('hide'); 
        $('#declined').modal('show'); 
    
        //  window.location.reload();
    }).fail(function(xhr, status, error) {
        alert("An error occurred: " + status + "\nError: " + error);
    });
}
</script>

</html>