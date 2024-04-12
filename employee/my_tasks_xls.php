<?php
$year = date('Y');
$employee = $_GET['id'];
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=".$employee." ASSIGNED TASKS LIST ".$year.".xls");  //File name extension was wrong
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
       <h3> <b><?php echo $employee?> ASSIGNED TASKS</b></h3>
        <br>
    </center>
    <br>

    <div id="table-scroll">
        <table width="100%" border="1" align="left">
            <thead>
                <tr>
                    <th scope="col">
                        <center />Task Name
                    </th>
                    <th scope="col">
                        <center />Legend
                    </th>
                    <th scope="col">
                        <center />Task Details
                    </th>
                    <th scope="col">
                        <center />Task Classification
                    </th>
                    <th scope="col">
                        <center />Task Recurrances
                    </th>
                </tr>
            </thead>

            <tbody>
            <?php
                $con->next_result();
                $result = mysqli_query($con,"SELECT *, tasks.id FROM tasks JOIN task_class ON task_class.id=tasks.task_class WHERE in_charge='$username'");
                if (mysqli_num_rows($result)>0) { 
                    while ($row = $result->fetch_assoc()) { 
                        $task_name = $row['task_name'];
                        $task_details = $row['task_details'];
                        $task_class = $row['task_class'];
                        $due_date = $row['submission'];
                        echo "<tr>  
                            <td id='normalwrap'><center />" . $task_name . "</td>";
                            if ($row['requirement_status'] == 1) {
                                echo "<td><center /> * </td>";
                            } 
                            else {
                                echo "<td> </td>";
                            }
                            echo " 
                            <td><center />" . $task_details . "</td>         
                            <td><center />" . $task_class . "</td>
                            <td><center />" . $due_date . "</td> 
                        </tr>";  
                    }
                }
            ?>
            </tbody>

        </table>
    </div>
</body>

</html>