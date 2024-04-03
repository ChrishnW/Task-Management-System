<?php
$today = date("F-d-Y");
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
                    <th>*</th>
                    <th>Task Classification</th>
                    <th>Date Deployed</th>
                    <th>Due Date</th>
                    <th>In Charge</th>
                    <th>Task Stage</th>
                    <th>Task Status</th>
                </tr>
            </thead>

            <tbody>
                <?php
                    $con->next_result();
                    $result = mysqli_query($con,"SELECT *, (tasks_details.status) FROM tasks_details LEFT JOIN accounts ON accounts.username = tasks_details.in_charge LEFT JOIN task_list ON tasks_details.task_name = task_list.task_name LEFT JOIN task_class ON task_list.task_class = task_class.id WHERE task_status = 1 AND task_for = '$section'");
                    if (mysqli_num_rows($result)>0) { 
                        while ($row = $result->fetch_assoc()) {
                            $task_class = $row['task_class'];
                            $emp_name=$row['fname'].' '.$row['lname'];

                            if ($row['task_status'] == '1') {
                                $sign = "Deployed";
                            }
                            else {
                                $sign = "Not deployed";
                            }

                            if ($row['status'] == 'NOT YET STARTED') {
                                $status = "To Do";
                            }
                            elseif ($row['status'] == 'IN PROGRESS') {
                                $status = "In Progress";
                            }
                            elseif ($row['status'] == 'FINISHED') {
                                $status = "Complete";
                            }
                            
                            echo "<tr>
                                <td><center />" . $row["task_code"] . " </td>"; ?>
                                <?php
                                if ($row['requirement_status'] == 1){
                                    echo "<td><center />*</td>";
                                }
                                else {
                                    echo "<td> </td>";
                                }
                                echo"
                                <td><center />" . $row["task_name"] . " </td>
                                <td><center />" . $row["task_class"] . "</td>
                                <td><center />" . $row["date_created"] . "</td> 
                                <td><center />" . $row["due_date"] . "</td> 
                                <td><center />" . $emp_name . "</td>  
                                <td><center />" . $status . "</td>
                                <td><center />".$sign."</td>
                            </tr>";   
                        }
                    } 
                    if ($con->connect_error) {
                        die("Connection Failed".$con->connect_error); 
                    }; 
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>