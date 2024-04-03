<?php 
include('../include/header_head.php');
include('../include/connect.php');
include('../include/bubbles.php');

$month = date('m'); //Number of Month
$formatted_num = 0;
$section=isset($_GET['section']) ? $_GET['section'] : die('ERROR: Record not found.'); 
?>
<html>
<head>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
<link href="../assets/css/darkmode.css" rel="stylesheet">

    <title>Staff Performace</title>
</head>

<body>
    <div id="wrapper">
        <div id="page-wrapper">
        <h1 class="page-header pull-left"><?php echo $section ?> Staff Performace </h1>
        <div class='col-lg-4 pull-right'>
            <br>
            <label>For the Month of:</label><br>
                <select name='show_status' id='show_status' class='form-control selectpicker show-menu-arrow '
                    placeholder='' onchange='selectmodel(this)'>
                    <option disabled selected value=''>--Sort by Status--</option>
                    <option selected value='1'>January</option>
                    <option value='2'>Febuary</option>
                    <option value='3'>March</option>
                    <option value='4'>April</option>
                    <option value='5'>May</option>
                    <option value='6'>June</option>
                    <option value='7'>July</option>
                    <option value='8'>August</option>
                    <option value='9'>September</option>
                    <option value='10'>October</option>
                    <option value='11'>November</option>
                    <option value='12'>December</option>
                </select>
                <br>
                <br>
        </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                        <?php echo $section ?> Staff Performace
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table width="100%" class="table table-striped table-hover "
                                    id="table_task">

                                    <thead>
                                        <tr>
                                            <th class="col-lg-3">
                                                <center>Username</center>
                                            </th>
                                            <th class="col-lg-3">
                                                <center>Employee</center>
                                            </th>
                                            <th class="col-lg-3">
                                                <center>Average Score</center>
                                            </th>
                                            <th class="col-lg-3">
                                                <center>Total Tasks</center>
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody id="show_task">
                                        <?php
                                        $con->next_result();
                                        $result = mysqli_query($con,"SELECT * FROM accounts WHERE sec_id='$section' AND access=2");
                                            while ($row = $result->fetch_assoc()) {                                                
                                                $emp_name=$row['fname'].' '.$row['lname'];
                                                $username=$row["username"];
                                                $label='Task/(s)';
                                                $count_task = mysqli_query($con,"SELECT COUNT(tasks_details.task_code) as total_task FROM tasks_details WHERE tasks_details.in_charge = '$username' AND MONTH(tasks_details.due_date) = '$month' AND tasks_details.reschedule != '1'");
                                                $count_task_row = $count_task->fetch_assoc();
                                                $total_task=$count_task_row['total_task'];
                                                if ($total_task=='0') {
                                                    $total_task='No';
                                                    echo 
                                                    "<tr>                                                
                                                    <td><center /<>" . $username . "</td>                  
                                                    <td><center /> " . $emp_name . "</td>
                                                    <td><center />" . $formatted_num . ' ' . "</td>
                                                    <td><center /> " . $total_task .' '.$label. "</td>
                                                    </tr>";
                                                }
                                                
                                                else {
                                                    // Average Computation
                                                    $donetotal = 0;
                                                    $tasktotal = 0;
                                                    $totavg = 0;
                                                    $donesum = 0;
                                                    $ontasks = 0;
                                                    $remtask = 0;
                                                    $ftask = 0;
                                                    $dateaccom = 0;
                                                    $datedue = 0;
                                                    $three = 0;
                                                    $two = 0;
                                                    $one = 0;
                                                    $zero = 0;
                                                    $avg_task = mysqli_query($con,"SELECT tasks_details.date_created, tasks_details.achievement, tasks_details.due_date, tasks_details.date_accomplished, tasks_details.in_charge, accounts.username, accounts.sec_id, tasks_details.task_code, tasks_details.resched_reason, task_list.task_name, task_list.task_class, tasks_details.reschedule, tasks_details.remarks, tasks_details.status FROM tasks_details LEFT JOIN accounts ON tasks_details.in_charge = accounts.username LEFT JOIN task_list ON tasks_details.task_code = task_list.task_code WHERE MONTH(tasks_details.due_date) = '$month' AND accounts.username = '$username' AND tasks_details.reschedule != '1'");
                                                    if (mysqli_num_rows($avg_task)>0) { 
                                                        while ($rows = $avg_task->fetch_assoc()) { 
                                                        $taskcode = $rows['task_code'];
                                                        $taskname = $rows['task_name'];
                                                        $taskclass = $rows['task_class'];
                                                        $dateaccom = $rows['date_accomplished'];
                                                        $remarks = $rows['remarks'];
                                                        $achievement = $rows['achievement'];
                                                            if ($rows['status'] == 'IN PROGRESS') {
                                                                $achievement = 0;
                                                                $ontasks += 1;
                                                            }
                                                            if ($rows['status'] == 'NOT YET STARTED') {
                                                                $achievement = 0;
                                                                $remtask += 1; 
                                                            }
                                                            if ($rows['status'] == 'FINISHED') {
                                                                $donetotal += 1;
                                                            }
                                                            if ($row['status'] == 'FAILED') {
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
                                                    if ($formatted_number >= 3) {
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
                                                    echo 
                                                    "<tr>
                                                    <td><center /<>" . $username . "</td>                  
                                                    <td><center /> " . $emp_name . "</td>
                                                    <td><center />" . $formatted_number . '<br>' . $rate . "</td>
                                                    <td><center /> ". $tasktotal .' '.$label."<a href='performance_list.php?id=".$username."'> <button class='btn btn-sm btn-success pull-right'><i class='fas fa-eye'></i> View</button></a>"."</td>
                                                    </tr>";
                                                }
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <a href="./index.php"> <button class='btn btn-danger pull-left'><i class="fa fa-arrows-left"></i> Return to Dashboard</button></a>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
$(document).ready(function() {
    $('#table_task').DataTable({
        responsive: true,
        "order": [[ 2, "desc" ]]
    });
});
</script>
<script>
    function selectmodel(element) {
        let sid = $(element).val();
        var username = "<?php echo $username; ?>";
        var section = "<?php echo $section; ?>";
        $('#table_task').DataTable().destroy();
        $('#show_task').empty();
        if (sid) {
            $.ajax({
                type: "post",
                url: "performance_ajax.php",
                data: {
                    "sid": sid,
                    "username": username,
                    "section": section
                },
                success: function(response) {
                    $('#show_task').append(response);
                    $('#table_task').DataTable({
                        responsive: true,
                        "order": [[ 2, "desc" ]]
                    });
                }
            });
        }
    }
</script>
</html>