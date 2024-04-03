
<?php 
include('../include/header_employee.php');
include('../include/connect.php');
$status=isset($_GET['status']) ? $_GET['status'] : die('ERROR: Record ID not found.'); 
?>
<html>
<head>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">

    <title>My Task Details</title>
</head>

<body>
<div id="wrapper">
    <div id="page-wrapper">
    <h1 class="page-header">My Task Details<a href="tasks_xls.php?status=<?php echo $status ?>&&username=<?php echo $username ?>"> <button class="btn btn-success pull-right"><span class="fa fa-download"></span> Download</button></a></h1>
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
            <div class="col-lg-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                    My Task Details
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
                                    <th style="text-align:center;" class="col-lg-2">
                                        Remarks
                                    </th>
                                    <th class="col-lg-1">
                                        Achievement
                                    </th>
                                    <?php 
                                    if ($status=="NOT YET STARTED"||$status=="IN PROGRESS") {
                                        echo "<th class='col-lg-1'>
                                        Action
                                    </th>";
                                    }
                                    ?>
                                </tr>
                              </thead>
                              <tbody id="show_task">
                                  <?php
                                  $con->next_result();
                                  if ($status=="NOT YET STARTED") {
                                      $result = mysqli_query($con,"SELECT tasks_details.task_code, task_list.task_name, task_list.task_details, task_class.task_class, task_list.task_for, tasks_details.date_created, tasks_details.due_date, tasks_details.in_charge, tasks_details.status, tasks_details.date_accomplished, tasks_details.id, accounts.fname, accounts.lname, tasks_details.remarks FROM tasks_details LEFT JOIN task_list ON task_list.task_code=tasks_details.task_code  LEFT JOIN task_class ON task_list.task_class=task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE tasks_details.in_charge='$username' AND tasks_details.status='$status' AND tasks_details.task_status IS TRUE ORDER BY tasks_details.due_date ASC");
                                      if (mysqli_num_rows($result)>0) { 
                                          while ($row = $result->fetch_assoc()) {
                                              if ($row['date_accomplished']!='') {
                                                  $class = "";
                                                  $date_accomplished = date_create($row['date_accomplished']);
                                                  $due_date = date_create($row['due_date']);
                                                  $int = date_diff($due_date, $date_accomplished);
                                                  $interval = $int->format("%R%a");
                                                  if ($interval<=0) {
                                                    $achievement = '3';
                                                  } else if ($interval>0 && $interval<=7) {
                                                      $achievement = '2';
                                                  } else if ($interval>7) {
                                                    $achievement = '1';
                                                  } else {
                                                      $achievement = '0';
                                                  }
                                              } else {
                                                  $achievement = '0';
                                                  $today = date("Y-m-d");
                                                    $due_date = $row["due_date"];
                                                    $class = "";
                                                    if ($today > $due_date) {
                                                        $class = "red";
                                                    }
                                              }
                                              
                                              if ($row['status'] == 'FINISHED') {
                                                  $class_label = "success";
                                                  $status = "FINISHED";
                                              } else if ($row['status'] == 'IN PROGRESS') {
                                                  $class_label = "info";
                                                  $status = "IN PROGRESS";
                                              } else {
                                                  $class_label = "danger";
                                                  $status = "NOT YET STARTED";
                                              }
                                              echo "<tr>                                                      
                                                  <td class='".$class."'> " . $row["task_name"] . " </td>
                                                  <td class='".$class."'>" . $row["task_class"] . "</td>  
                                                  <td class='".$class."'>" . $row["due_date"] . "</td> 
                                                  <td class='".$class."'>" . $row["fname"].' '.$row["lname"] . "</td>
                                                  <td><center/><p class='label label-".$class_label."' style='font-size:100%;'>".$status."</p></td>
                                                  <td class='".$class."'>" . $row["date_accomplished"] . "</td>
                                                  <td class='".$class."'>" . $row["remarks"] . "</td> 
                                                  <td class='".$class."'>" . $achievement . "</td>
                                                  <td> <center/><button id='task_id' value='".$row['id']."' class='btn btn-primary' onclick='start(this)'><i class='fa fa-play fa-1x'></i></button>
                                                  </td>
                                              </tr>";   
                                          }
                                      } 
                                  } else if ($status=="IN PROGRESS") {
                                      $result = mysqli_query($con,"SELECT tasks_details.task_code, task_list.task_name, task_list.task_details, task_class.task_class, task_list.task_for, tasks_details.date_created, tasks_details.due_date, tasks_details.in_charge, tasks_details.status, tasks_details.date_accomplished, tasks_details.id, accounts.fname, accounts.lname, tasks_details.remarks FROM tasks_details LEFT JOIN task_list ON task_list.task_code=tasks_details.task_code  LEFT JOIN task_class ON task_list.task_class=task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE tasks_details.in_charge='$username' AND tasks_details.status='$status' AND tasks_details.task_status IS TRUE ORDER BY tasks_details.due_date ASC");
                                  
                                      if (mysqli_num_rows($result)>0) { 
                                          while ($row = $result->fetch_assoc()) {
                                              if ($row['date_accomplished']!='') {
                                                  $class = "";
                                                  $date_accomplished = date_create($row['date_accomplished']);
                                                  $due_date = date_create($row['due_date']);
                                                  $int = date_diff($due_date, $date_accomplished);
                                                  $interval = $int->format("%R%a");
                                                  if ($interval<=0) {
                                                    $achievement = '3';
                                                  } else if ($interval>0 && $interval<=7) {
                                                      $achievement = '2';
                                                  } else if ($interval>7) {
                                                    $achievement = '1';
                                                  } else {
                                                      $achievement = '0';
                                                  }
                                              } else {
                                                  $achievement = '0';
                                                  $today = date("Y-m-d");
                                                    $due_date = $row["due_date"];
                                                    $class = "";
                                                    if ($today > $due_date) {
                                                        $class = "red";
                                                    }
                                              }
                                              
                                              if ($row['status'] == 'FINISHED') {
                                                  $class_label = "success";
                                                  $status = "FINISHED";
                                              } else if ($row['status'] == 'IN PROGRESS') {
                                                  $class_label = "info";
                                                  $status = "IN PROGRESS";
                                              } else {
                                                  $class_label = "danger";
                                                  $status = "NOT YET STARTED";
                                              }
                                              echo "<tr>                                                      
                                                  <td class='".$class."'> " . $row["task_name"] . " </td>   
                                                  <td class='".$class."'>" . $row["task_class"] . "</td>  
                                                  <td class='".$class."'>" . $row["due_date"] . "</td> 
                                                  <td class='".$class."'>" . $row["fname"].' '.$row["lname"] . "</td>
                                                  <td><center/><p class='label label-".$class_label."' style='font-size:100%;'>".$status."</p></td>
                                                  <td class='".$class."'>" . $row["date_accomplished"] . "</td>
                                                  <td class='".$class."'>" . $row["remarks"] . "</td> 
                                                  <td class='".$class."'>" . $achievement . "</td>
                                                  <td> <center/><button id='task_id' value='".$row['id']."' class='btn btn-danger' onclick='finish(this)'><i class='fa fa-stop fa-1x'></i></button>
                                                  </td>
                                              </tr>";   
                                          }
                                      }
                                  } else {
                                      $result = mysqli_query($con,"SELECT tasks_details.task_code, task_list.task_name, task_list.task_details, task_class.task_class, task_list.task_for, tasks_details.date_created, tasks_details.due_date, tasks_details.in_charge, tasks_details.status, tasks_details.date_accomplished, tasks_details.id, accounts.fname, accounts.lname, tasks_details.remarks FROM tasks_details LEFT JOIN task_list ON task_list.task_code=tasks_details.task_code  LEFT JOIN task_class ON task_list.task_class=task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE tasks_details.in_charge='$username' AND tasks_details.status='$status' AND tasks_details.task_status IS TRUE ORDER BY tasks_details.due_date ASC");
                                      if (mysqli_num_rows($result)>0) { 
                                          while ($row = $result->fetch_assoc()) {
                                              if ($row['date_accomplished']!='') {
                                                  $class = "";
                                                  $date_accomplished = date_create($row['date_accomplished']);
                                                  $due_date = date_create($row['due_date']);
                                                  $int = date_diff($due_date, $date_accomplished);
                                                  $interval = $int->format("%R%a");
                                                  if ($interval<=0) {
                                                    $achievement = '3';
                                                  } else if ($interval>0 && $interval<=7) {
                                                      $achievement = '2';
                                                  } else if ($interval>7) {
                                                    $achievement = '1';
                                                  } else {
                                                      $achievement = '0';
                                                  }
                                              } else {
                                                  $achievement = '0';
                                                  $today = date("Y-m-d");
                                                    $due_date = $row["due_date"];
                                                    $class = "";
                                                    if ($today > $due_date) {
                                                        $class = "red";
                                                    }
                                              }
                                              
                                              if ($row['status'] == 'FINISHED') {
                                                  $class_label = "success";
                                                  $status = "FINISHED";
                                              } else if ($row['status'] == 'IN PROGRESS') {
                                                  $class_label = "info";
                                                  $status = "IN PROGRESS";
                                              } else {
                                                  $class_label = "danger";
                                                  $status = "NOT YET STARTED";
                                              }
                                              echo "<tr>                                                      
                                                  <td class='".$class."'> " . $row["task_name"] . " </td>   
                                                  <td class='".$class."'>" . $row["task_class"] . "</td>  
                                                  <td class='".$class."'>" . $row["due_date"] . "</td> 
                                                  <td class='".$class."'>" . $row["fname"].' '.$row["lname"] . "</td>
                                                  <td><center/><p class='label label-".$class_label."' style='font-size:100%;'>".$status."</p></td>
                                                  <td class='".$class."'>" . $row["date_accomplished"] . "</td>
                                                  <td class='".$class."'>" . $row["remarks"] . "</td> 
                                                  <td class='".$class."'>" . $achievement . "</td>
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
    .red {
        color: red;
    }
</style>

<script>
$(document).ready(function() {
    $('#table_task').DataTable({
        responsive: true,
        "order": [[ 2, "asc" ]]
    });
});
</script>

<script>   
function start(obj) {
    var taskID = obj.value;
    $(document).ready(function() { 
        $('#start').modal('show'); 
        document.getElementById('modal_task_id2').
        innerHTML = taskID; 
        document.getElementById('hidden_task_id2').
        value = taskID;   
    });
}

function okButtonClick2() {
    var taskID = document.getElementById('hidden_task_id2').value;
    $.ajax({
        type: "POST",
        url: "task_details_start.php",
        data: { id: taskID }
    }).done(function(response) {
        $('#start').modal('hide'); 
        $('#success1').modal('show'); 
        //window.location.reload();
    }).fail(function(xhr, status, error) {
        alert("An error occurred: " + status + "\nError: " + error);
    });
}
</script>

<script>   
function finish(obj) {
    var taskID = obj.value;
    $(document).ready(function() { 
        $('#finish').modal('show'); 
        document.getElementById('modal_task_id').
        innerHTML = taskID; 
        document.getElementById('hidden_task_id').
        value = taskID;   
    });
}

function okButtonClick() {
    var taskID = document.getElementById('hidden_task_id').value;
    var action = document.getElementById('textArea').value;
    $.ajax({
        type: "POST",
        url: "task_details_finish.php",
        data: { id: taskID, action: action }
    }).done(function(response) {
        $('#finish').modal('hide'); 
        $('#success2').modal('show'); 
        // window.location.reload();
    }).fail(function(xhr, status, error) {
        alert("An error occurred: " + status + "\nError: " + error);
    });
}
</script> 

<script>
 function checkTextLength() {
    var textArea = document.getElementById('textArea');
    var okButton = document.getElementById('okButton');

    if (textArea.value.length >= 30) {
      okButton.disabled = false;
    } else {
      okButton.disabled = true;
    }
 }
</script>

<script>
function selectto(element) {
    let valto = $(element).val();
    let status = <?php echo json_encode($status) ?>;
    let valfrom = $('#val_from').val();
    let username = <?php echo json_encode($username) ?>;
    $('#table_task').DataTable().destroy();
    $('#show_task').empty();
    if (valto) {
        $.ajax({
            type: "post",
            url: "ajax_valto.php",
            data: {
                "valfrom": valfrom,
                "status": status,
                "valto": valto,
                "username": username
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
function selectfrom(element) {
    let valfrom = $(element).val();
    let status = <?php echo json_encode($status) ?>;
    let valto = $('#val_to').val();
    let username = <?php echo json_encode($username) ?>;
    $('#table_task').DataTable().destroy();
    $('#show_task').empty();
    if (valfrom) {
        $.ajax({
            type: "post",
            url: "ajax_valfrom.php",
            data: {
                "valfrom": valfrom,
                "status": status,
                "valto": valto,
                "username": username
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
    let username = <?php echo json_encode($username) ?>;
    $('#table_task').DataTable().destroy();
    $('#show_task').empty();
    if (valto) {
        $.ajax({
            type: "post",
            url: "ajax_valto.php",
            data: {
                "valfrom": valfrom,
                "status": status,
                "valto": valto,
                "username": username
            },
            success: function(response) {
                $('#show_task').append(response);
                $('#table_task').DataTable();
            }
        });
    }
}
</script>


<div class="modal fade" id="finish" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <a href="task_details.php?status=IN PROGRESS"><button type="button" class="close" aria-hidden="true">&times;</button></a>
                <h4 class="modal-title" id="myModalLabel">Finish Task</h4>
            </div>
            <div class="modal-body panel-body">
                <center>
                    <span hidden id="modal_task_id"></span> <!-- Add this span -->
                    <p>Please Enter Action</p>
                    <input type="hidden" id="hidden_task_id" name="hidden_task_id">
                    <textarea id="textArea" class="form-control" onkeyup="checkTextLength()" placeholder="Please input atleast 30 characters."></textarea>
                </center>
            </div>
            <div class="modal-footer">
              <button disabled id='okButton' class='btn btn-success pull-right' onclick='okButtonClick()'>OK</button>
              <a href="task_details.php?status=IN PROGRESS"><button type="button" name="submit" class="btn btn-danger pull-left">Cancel</button></a>
            </div>
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="start" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <a href="task_details.php?status=NOT YET STARTED"><button type="button" class="close" aria-hidden="true">&times;</button></a>
                <h4 class="modal-title" id="myModalLabel">Start Task</h4>
            </div>
            <div class="modal-body panel-body">
                <center>
                    <i style="color:#e13232; font-size:80px;" class="fa fa-question-circle"></i>
                    <br><br>
                    <span hidden id="modal_task_id2"></span> <!-- Add this span -->
                    <p>Do you want to start this task?</p>
                    <input type="hidden" id="hidden_task_id2" name="hidden_task_id2">
                </center>
            </div>
            <div class="modal-footer">
              <button id='okButton' class='btn btn-success pull-right' onclick='okButtonClick2()'>OK</button>
              <a href="task_details.php?status=NOT YET STARTED"><button type="button" name="submit" class="btn btn-danger pull-left">Cancel</button></a>
            </div>
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="success1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <a href="task_details.php?status=IN PROGRESS"><button type="button" class="close" aria-hidden="true">&times;</button></a>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body panel-body">
                <center>
                    <i style="color:#e13232; font-size:80px;" class="fa fa-fa-check"></i>
                    <br><br>
                    <p>Task has been started.</p>
                </center>
            </div>
            <div class="modal-footer">
              <a href="task_details.php?status=IN PROGRESS"><button type="button" name="submit" class="btn btn-success pull-right">OK</button></a>
            </div>
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="success2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <a href="task_details.php?status=FINISHED"><button type="button" class="close" aria-hidden="true">&times;</button></a>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body panel-body">
                <center>
                    <i style="color:#e13232; font-size:80px;" class="fa fa-fa-check"></i>
                    <br><br>
                    <p>Task has been finished.</p>
                </center>
            </div>
            <div class="modal-footer">
              <a href="task_details.php?status=FINISHED"><button type="button" name="submit" class="btn btn-success pull-right">OK</button></a>
            </div>
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>
</html>