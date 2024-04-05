<?php 
include('../include/header_head.php');
include('../include/connect.php');
$today = date("Y-m-d"); 
$month = date('m');
$year = date('Y');
$monthname = date('F');
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
<div id="content" class="p-4 p-md-5 pt-5">
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
                                <table width="100%" class="table table-striped table-hover "
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
                                            <!-- <th class="col-lg-2">
                                                Details
                                            </th> -->
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                    <?php
                                    if (isset($_GET['monthly'])){
                                        $result = mysqli_query($con,"SELECT * FROM tasks_details JOIN task_class ON tasks_details.task_class = task_class.id JOIN accounts ON tasks_details.in_charge=accounts.username WHERE in_charge='$id' AND task_status=1 AND approval_status=0 AND MONTH(tasks_details.date_accomplished)='$month' AND YEAR(tasks_details.date_accomplished)='$year' AND tasks_details.date_accomplished IS NOT NULL AND tasks_details.task_class=3");
                                        if (mysqli_num_rows($result)>0) { 
                                            while ($row = $result->fetch_assoc()) { 
                                            $taskcode = $row['task_code'];
                                            $taskname = $row['task_name'];
                                            $taskclass = $row['task_class'];
                                            $dateaccom = $row['date_accomplished'];
                                            $datedue = $row['due_date'];
                                            $datec = $row['date_created'];
                                            $achievement = $row['achievement'];

                                            echo "<tr>                                                       
                                            <td><center />" . $taskcode . "</td>
                                            <td id='normalwrap'>" . $taskname . "</td>
                                            <td><center />" . $taskclass . "</td>
                                            <td><center />" . $datedue . "</td>
                                            <td><center />" . $dateaccom . "</td>
                                            <td><center />" . $achievement . "</td>
                                            </tr>";
                                            }
                                        }
                                    }
                                    else {
                                        $result = mysqli_query($con,"SELECT * FROM tasks_details JOIN task_class ON tasks_details.task_class = task_class.id JOIN accounts ON tasks_details.in_charge=accounts.username WHERE in_charge='$id' AND task_status=1 AND approval_status=0 AND MONTH(tasks_details.date_accomplished)='$month' AND YEAR(tasks_details.date_accomplished)='$year' AND tasks_details.date_accomplished IS NOT NULL AND tasks_details.task_class!=3");
                                        if (mysqli_num_rows($result)>0) { 
                                            while ($row = $result->fetch_assoc()) { 
                                            $taskcode = $row['task_code'];
                                            $taskname = $row['task_name'];
                                            $taskclass = $row['task_class'];
                                            $dateaccom = $row['date_accomplished'];
                                            $datedue = $row['due_date'];
                                            $datec = $row['date_created'];
                                            $achievement = $row['achievement'];

                                            echo "<tr>                                                       
                                            <td><center />" . $taskcode . "</td>
                                            <td id='normalwrap'>" . $taskname . "</td>
                                            <td><center />" . $taskclass . "</td>
                                            <td><center />" . $datedue . "</td>
                                            <td><center />" . $dateaccom . "</td>
                                            <td><center />" . $achievement . "</td>
                                            </tr>";
                                            }
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
</div>
<script>
$(document).ready(function() {
    $('#table_task').DataTable({
        responsive: true,
        "order": [[ 4, "desc" ]]
    });
});
</script>
</html>