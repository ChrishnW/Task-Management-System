<?php
$today = date('Y-m-d');
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=TASKS-SUMMARY_".$today.".xls");  //File name extension was wrong
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
       <h3> <b>TASKS SUMMARY</b></h3>
        <br>
    </center>
    <br>

    <div id="table-scroll">
        <table width="100%" border="1" align="left">
            <thead>
                <tr>
                    <th>Task Code</th>
                    <th>Task Name</th>
                    <th>Task Details</th>
                    <th>Task Classification</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php
            

            $con->next_result();
            $result = mysqli_query($con,"SELECT task_list.task_code, task_list.task_name, task_list.task_details, task_class.task_class, task_list.status, task_list.id FROM task_list INNER JOIN task_class ON task_list.task_class=task_class.id");
          
            while($row = mysqli_fetch_array($result))
            {
                if($row['status']==1) {
                    $status = "ACTIVE";
                }
                else {
                    $status = "INACTIVE";
                }
                echo "
                <tr>
                <td>" . $row["task_code"] . "</td>  
                <td>" . $row["task_name"] . "</td> 
                <td>" . $row["task_details"] . "</td> 
                <td>" . $row["task_class"] . "</td>
                <td>". $status ."</td>
                </tr> ";
            } 
            ?>
            </tbody>

        </table>
    </div>
</body>

</html>