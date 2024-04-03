<?php 
include('../include/header_head.php');
include('../include/connect.php');
include('../include/bubbles.php');
$today = date("Y-m-d"); 
$month = date('m'); //Number of Month
$id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.'); 
?>

<html>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
<link href="../assets/css/darkmode.css" rel="stylesheet">

<head>
    <title>Employees Assigned Tasks</title>
</head>
<body>
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">List of Assigned Tasks [ <?php echo $id; ?> ]
                    </h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <?php echo $id; ?>
                        </div>

                        <div class="panel-body">
                            <div class="table-responsive">
                                <table width="100%" class="table table-striped table-bordered table-hover "
                                    id="table_task">

                                    <thead>
                                        <tr>
                                            <th class="col-lg-2">
                                                <center />Task Code
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Task Name
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Task Classification
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Due Date
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Date Accomplished
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Score
                                            </th>
                                            <th class="col-lg-2">
                                                Activity
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                    <?php
                                    $result = mysqli_query($con,"SELECT tasks_details.date_created, tasks_details.achievement, tasks_details.due_date, tasks_details.date_accomplished, tasks_details.in_charge, accounts.username, accounts.sec_id, tasks_details.task_code, tasks_details.resched_reason, task_list.task_name, task_list.task_class, tasks_details.reschedule, tasks_details.remarks FROM tasks_details LEFT JOIN accounts ON tasks_details.in_charge = accounts.username LEFT JOIN task_list ON tasks_details.task_code = task_list.task_code WHERE MONTH(tasks_details.due_date) = '$month' AND accounts.username = '$id' AND tasks_details.reschedule != '1'");
                                    if (mysqli_num_rows($result)>0) { 
                                    while ($row = $result->fetch_assoc()) { 
                                    $taskcode = $row['task_code'];
                                    $taskname = $row['task_name'];
                                    $taskclass = $row['task_class'];
                                    $dateaccom = $row['date_accomplished'];
                                    $datedue = $row['due_date'];
                                    $remarks = $row['remarks'];
                                    $datec = $row['date_created'];
                                    $achievement = $row['achievement'];
                                    if ($taskclass == "1"){
                                        $taskclass = 'Daily Routine';
                                    }
                                    elseif ($taskclass == '2'){
                                        $taskclass = 'Weekly Routine';
                                    }
                                    elseif ($taskclass == '3'){
                                        $taskclass = 'Monthly Routine';
                                    }
                                    elseif ($taskclass == '4'){
                                        $taskclass = 'Additional Task';
                                    }
                                    elseif ($taskclass == '5'){
                                        $taskclass = 'Project';
                                    }
                                    else {
                                        $taskclass = 'Unidentified';
                                    }
                                    echo "<tr>                                                       
                                    <td><center />" . $taskcode . "</td>
                                    <td>" . $taskname . "</td>
                                    <td><center />" . $taskclass . "</td>
                                    <td><center />" . $datedue . "</td>
                                    <td><center />" . $dateaccom . "</td>
                                    <td><center />" . $achievement . "</td>
                                    <td>" . $remarks . "</td>
                                    </tr>";
                                    }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <a href="javascript:history.back()"> <button class='btn btn-danger pull-left'><i class="fa fa-arrow-left"></i> Back</button></a>
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
</html>