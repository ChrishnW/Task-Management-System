<?php
$month = date('F');
$year = date('Y');
$section = $_GET['section'];
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=".$section."_TASKS-SUMMARY_".$month."_".$year.".xls");  //File name extension was wrong
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
        <h3> <b><?php echo $section ?> TASKS DETAILS</b> </h3>
        <br>
    </center>
    <div id="table-scroll">
        <table width="100%" border="1" align="left">
            <thead>
                <tr>
                    <th scope="col">
                        Task Code
                    </th>
                    <th scope="col">
                        Task Name
                    </th>
                    <th scope="col">
                        Task Classification
                    </th>
                    <th scope="col" title="Legend">
                        Attachment Requirement
                    </th>
                    <th scope="col">
                        Date Created
                    </th>
                    <th scope="col">
                        Due Date
                    </th>
                    <th scope="col">
                        In-charge
                    </th>
                    <th scope="col">
                        Status
                    </th>
                </tr>
            </thead>

            <tbody>
                <?php
            $con->next_result();
            $result = mysqli_query($con,"SELECT tasks_details.id, tasks_details.task_code, task_list.task_name, task_class.task_class, tasks_details.date_created, tasks_details.due_date, tasks_details.in_charge, tasks_details.task_status, tasks_details.requirement_status, accounts.username, accounts.fname, accounts.lname, accounts.file_name FROM tasks LEFT JOIN task_list ON tasks.task_name = task_list.task_name LEFT JOIN tasks_details ON tasks.task_name = tasks_details.task_name LEFT JOIN task_class ON task_class.id = task_list.task_class LEFT JOIN accounts ON tasks_details.in_charge = accounts.username WHERE task_list.task_for='$section' AND tasks_details.task_status = '1'");
            if (mysqli_num_rows($result)>0) { 
                while ($row = $result->fetch_assoc()) {
                    $task_class = $row['task_class'];
                    $emp_name=$row['fname'].' '.$row['lname'];

                    if ($row['task_status'] == '1') {
                            $class_label = "success";
                            $sign = "Deployed";
                        }
                    else {
                        $class_label = "danger";
                        $sign = "Not deployed";
                    }
                    
                    echo "<tr>
                        <td><center />" . $row["task_code"] . " </td>
                        <td><center />" . $row["task_name"] . " </td>
                        <td><center />" . $row["task_class"] . "</td>"; ?>
                        <?php
                        if ($row['requirement_status'] == 1){
                            echo "<td><center />Yes</td>";
                        }
                        else {
                            echo "<td><center /> No </td>";
                        }
                        echo"
                        <td><center />" . $row["date_created"] . "</td> 
                        <td><center />" . $row["due_date"] . "</td> 
                        <td><center />" . $emp_name . "</td>  
                        </td>
                        <td><center /><p class='label label-".$class_label."' style='font-size:100%;'>".$sign."</p></td>
                    </tr>";   
                }
            } 
            ?>
            </tbody>

        </table>
    </div>
</body>

</html>