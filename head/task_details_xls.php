<?php
$today = date('Y-m-d');
$section = $_GET['section'];
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=".$section."_TASKS-SUMMARY_".$today.".xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);

include('../include/auth.php');
include('../include/connect.php');
?>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
    <center>
        <b>
            <font color="blue">GLORY (PHILIPPINES), INC.</font>
        </b>
        <br>
        <b>TASK MANAGEMENT SYSTEM</b>
        <br>
       <h3> <b><?php echo $section ?> TASKS DETAILS</b></h3>
        <br>
    </center>
    <br>

    <div id="table-scroll">
        <table width="100%" border="1" align="left">
            <thead>
                <tr>
                    <th>Task For</th>
                    <th>Task Name</th>
                    <th>Task Classification</th>
                    <th>Date Created</th>
                    <th>Due Date</th>
                    <th>In Charge</th>
                    <th>Status</th>
                    <th>Date Accomplished</th>
                    <th>Remarks</th>
                    <th>Achievement</th>
                    <th>Task Status</th>
                </tr>
            </thead>

            <tbody>
                <?php
            $con->next_result();
            $result = mysqli_query($con,"SELECT tasks_details.task_code, task_list.task_name, task_list.task_details, task_class.task_class, task_list.task_for, tasks_details.date_created, tasks_details.due_date, tasks_details.in_charge, tasks_details.status, tasks_details.date_accomplished, tasks_details.id, tasks_details.task_status, accounts.fname, accounts.lname, tasks_details.remarks, tasks_details.reschedule FROM tasks_details LEFT JOIN task_list ON task_list.task_code=tasks_details.task_code  LEFT JOIN task_class ON task_list.task_class=task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE task_list.task_for='$section'");
          
            while($row = mysqli_fetch_array($result))
            {

              if ($row['task_status']=='1') {
                $task_status="ACTIVE";
              } else {
                $task_status="INACTIVE";
              }
              
                if ($row['date_accomplished']!='') {
                  $date_accomplished = date_create($row['date_accomplished']);
                  $due_date = date_create($row['due_date']);
                  $int = date_diff($due_date, $date_accomplished);
                  $interval = $int->format("%R%a");
                  $resched = $row['reschedule'];
                  if ($interval<=0 && $resched == 0 ) {
                    $achievement = '3';
                } 
                  else if ($interval<=0 && $resched == 2 ) {
                    $achievement = '2';
                } else if ($interval>0) {
                    $achievement = '1';
                } else {
                    $achievement = '0';
                }
                } else {
                    $achievement = '0';
                  }
                echo "
                <tr>
                <td>" . $row["task_for"] . "</td>   
                <td>" . $row["task_name"] . "</td> 
                <td>" . $row["task_class"] . "</td>
                <td>" . $row["date_created"] . "</td>
                <td>" . $row["due_date"] . "</td>
                <td>" . $row['fname'].' '.$row['lname'] . "</td>
                <td>" . $row["status"] . "</td>
                <td>" . $row["date_accomplished"] . "</td>
                <td>" . $row["remarks"] . "</td>
                <td>" . $achievement . "</td>
                <td>" . $task_status . "</td>
                </tr> ";
            } 
            ?>
            </tbody>

        </table>
    </div>
</body>

</html>