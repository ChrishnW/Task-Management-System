<?php 
include('../include/header_employee.php');
include('../include/connect.php');
include('../include/bubbles.php');
$today = date("Y-m-d"); 
$month = date('m'); //Number of Month
$monthname = date('F'); //Name of the Month
$username=isset($_GET['username']) ? $_GET['username'] : die('ERROR: Record ID not found.'); 
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
                    <h1 class="page-header">My Performance as of <?php echo $monthname ?>
                    </h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <?php echo $username; ?>
                        </div>

                        <div class="panel-body">
                            <div class="table-responsive">
                                <table width="100%" class="table" id="table_task">
                                    <thead>
                                        <tr>
                                            <th class="col-lg-2">
                                                <center />Not Yet Started Tasks
                                            </th>
                                            <th class="col-lg-2">
                                                <center />In Progress Tasks
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Failed Tasks
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Finished Tasks
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Monthly Tasks
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Average Performance
                                            </th>
                                            <th class="col-lg-2">
                                                <center />My Tasks
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                    <?php
                                    $donetotal = 0;
                                    $tasktotal = 0;
                                    $totavg = 0;
                                    $donesum = 0;
                                    $remtask = 0;
                                    $ftask = 0;
                                    $ontasks = 0;
                                    $three = 0;
                                    $two = 0;
                                    $one = 0;
                                    $zero = 0;
                                    $result = mysqli_query($con,"SELECT tasks_details.date_created, tasks_details.achievement, tasks_details.due_date, tasks_details.date_accomplished, tasks_details.in_charge, accounts.username, accounts.sec_id, tasks_details.task_code, tasks_details.resched_reason, task_list.task_name, task_list.task_class, tasks_details.reschedule, tasks_details.remarks, tasks_details.status FROM tasks_details LEFT JOIN accounts ON tasks_details.in_charge = accounts.username LEFT JOIN task_list ON tasks_details.task_code = task_list.task_code WHERE MONTH(tasks_details.due_date) = '$month' AND accounts.username = '$username' AND tasks_details.reschedule != '1'");
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
                                        
                                        if ($row['status'] == 'IN PROGRESS') {
                                            $ontasks += 1;
                                        }
                                        if ($row['status'] == 'NOT YET STARTED') {
                                            $remtask += 1; 
                                        }
                                        if ($row['status'] == 'FINISHED') {
                                            $donetotal += 1;
                                        }
                                        if ($row['status'] == 'FINISHED' && $row['remarks'] == 'Failed to perform task') {
                                            $ftask += 1;
                                        }
                                        if ($achievement == 3) {
                                            $three += 1;
                                        }
                                        elseif ($achievement == 2) {
                                            $two += 1;
                                        }
                                        elseif ($achievement == 1) {
                                            $one += 1;
                                        }
                                        else {
                                            $zero += 1;
                                        }
                                    }
                                }
                                $three = $three * 3;
                                $two = $two * 2;
                                $one = $one * 1;
                                $donesum = $three + $two + $one;
                                $tasktotal = $ontasks + $remtask + $donetotal;
                                $totavg = $donesum / $tasktotal;
                                $formatted_number = number_format($totavg, 2);
                                // Rating
                                // $formatted_number = 1.6; (FOR CHECKING)
                                if ($formatted_number == 3) {
                                    $rate = '<span style="color: yellow">★★★★★</span>';
                                }
                                elseif ($formatted_number >= 2.6){
                                    $rate = '<span style="color: yellow">★★★★</span>☆';
                                }
                                elseif ($formatted_number >= 2) {
                                    $rate = '<span style="color: yellow">★★★</span>☆☆';
                                }
                                elseif ($formatted_number >= 1.6) {
                                    $rate = '<span style="color: yellow">★★</span>☆☆☆';
                                }
                                elseif ($formatted_number >= 0.5) {
                                    $rate = '<span style="color: yellow">★</span>☆☆☆☆';
                                }
                                else {
                                    $rate = '☆☆☆☆☆';
                                }
                                echo "<tr>
                                    <td><center />" . $remtask . "</td>
                                    <td><center />" . $ontasks . "</td> 
                                    <td><center />" . $ftask . "</td>                                                      
                                    <td><center />" . $donetotal . "</td>
                                    <td><center />" . $tasktotal . "</td>
                                    <td><center />" . $formatted_number . '<br>' . $rate . "</td>
                                    <td><center /> "."<a href='my_list.php?id=".$username."'> <button class='btn btn-sm btn-success'><i class='fas fa-eye'></i> View</button></a>"."</td>
                                    <td><center />" . $ontasks . "</td>
                                    </tr>";
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>