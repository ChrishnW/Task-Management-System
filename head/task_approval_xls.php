<?php
$today = date('Y-m-d');
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=FOR_APPROVAL_TASKS-SUMMARY_".$today.".xls");  //File name extension was wrong
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
       <h3> <b>FOR APPROVAL TASKS SUMMARY</b></h3>
        <br>
    </center>
    <br>

    <div id="table-scroll">
        <table width="100%" border="1" align="left">
            <thead>
                <tr>
                    <th>Task Name</th>
                    <th>Task Name</th>
                    <th>Task Class</th>
                    <th>Task For</th>
                    <th>Old Due Date</th>
                    <th>New Due Date</th>
                    <th>In-Charge</th>
                </tr>
            </thead>

            <tbody>
                <?php
            

            $con->next_result();
            $result = mysqli_query($con,"SELECT tasks_details.task_code, task_list.task_name, task_class.task_class, task_list.task_for, tasks_details.date_created, tasks_details.due_date, tasks_details.in_charge, tasks_details.id, accounts.fname, accounts.lname, tasks_details.reschedule, tasks_details.approval_status, tasks_details.resched_reason, (SELECT DISTINCT due_date FROM tasks_details WHERE status='NOT YET STARTED' AND approval_status = 1 AND  task_code = 'tasks_details.task_code' ) as expired_due_date
            FROM tasks_details 
            LEFT JOIN task_list ON task_list.task_code=tasks_details.task_code  
            LEFT JOIN task_class ON task_list.task_class=task_class.id 
            LEFT JOIN accounts ON tasks_details.in_charge=accounts.username 
            WHERE tasks_details.task_status IS TRUE AND tasks_details.status='NOT YET STARTED' AND tasks_details.approval_status = 0  AND tasks_details.reschedule = 2 ORDER BY tasks_details.due_date ASC");          
          
            while($row = mysqli_fetch_array($result))
            {
                $today = date("Y-m-d");
                $due_date = $row["due_date"];
                $class = "";
                if ($today > $due_date) {
                    $class = "red";
                }
                echo "
                <tr> 
                <td>" . $row["task_code"] . "</td>   
                <td>" . $row["task_name"] . "</td>   
                <td>" . $row["task_class"] . "</td> 
                <td>" . $row["task_for"] . "</td> 
                <td>" . $row['expired_due_date'] . "</td> 
                <td>" . $row["due_date"] . "</td> 
                <td>" . $row["fname"].' '.$row["lname"] . "</td>
                </tr> ";
            } 
            ?>
            </tbody>

        </table>
    </div>
</body>

</html>