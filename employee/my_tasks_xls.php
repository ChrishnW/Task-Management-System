<?php
$today = date('Y-m-d');
$employee = $_GET['id'];
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=".$employee."_TASKS-SUMMARY_".$today.".xls");  //File name extension was wrong
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
       <h3> <b>EMPLOYEE TASKS SUMMARY</b></h3>
        <br>
    </center>
    <br>

    <div id="table-scroll">
        <table width="100%" border="1" align="left">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Task Code</th>
                    <th>Task Name</th>
                    <th>Task Details</th>
                    <th>Task Classification</th>
                </tr>
            </thead>

            <tbody>
                <?php
            $con->next_result();
            $result = mysqli_query($con,"SELECT accounts.fname, accounts.lname, tasks.task_code, task_list.task_name, task_list.task_details, task_class.task_class, tasks.id  FROM tasks LEFT JOIN accounts ON tasks.in_charge=accounts.username INNER JOIN task_list ON tasks.task_code=task_list.task_code AND task_list.status IS TRUE LEFT JOIN task_class ON task_list.task_class=task_class.id WHERE tasks.in_charge='$employee'");
          
            while($row = mysqli_fetch_array($result))
            {
                echo "
                <tr>
                <td>" . $row['fname'].' '.$row['lname'] . "</td> 
                <td>" . $row["task_code"] . "</td>  
                <td>" . $row["task_name"] . "</td> 
                <td>" . $row["task_details"] . "</td> 
                <td>" . $row["task_class"] . "</td>
                </tr> ";
            } 
            ?>
            </tbody>

        </table>
    </div>
</body>

</html>