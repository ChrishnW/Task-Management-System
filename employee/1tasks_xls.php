<?php
    $today = date('Y-m-d');
    $monthyear = date('F Y');
    $month = date('m');
    $year = date('Y');
    $status = $_GET['status'];
    $username = $_GET['username'];
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename="."[".$username."] ".$status." TASKS (".$monthyear.").xls");
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
        <b> <font color="blue">GLORY (PHILIPPINES), INC.</font> </b>
        <br>
        <b>TASK MANAGEMENT SYSTEM</b>
        <br>
       <h3> <b><?php echo $status ?> TASKS</b></h3>
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
                    <th scope="col">
                        In-charge
                    </th>
                    <th scope="col">
                        Due Date
                    </th>
                    <?php 
                        if ($status=='FINISHED' || $status=="VERIFICATION"){
                            echo "<th scope='col'>
                                Date Accomplished
                            </th>
                            <th class='col-lg-1'>
                                Achievement
                            </th>";
                        }
                    ?>
                </tr>
            </thead>

            <tbody>
            <?php
                $con->next_result();
                $result = mysqli_query($con,"SELECT * FROM tasks_details JOIN task_class ON tasks_details.task_class = task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE in_charge='$username' AND tasks_details.status='$status' AND tasks_details.task_status=1 AND tasks_details.approval_status=0");
                    while($row = mysqli_fetch_array($result)) {
                        $achievement = $row['achievement'];
                        $due         = date('d-m-Y h:i A', strtotime($row['due_date'].'16:00:00'));
                        $date        = date('d-m-Y h:i A', strtotime($row['date_accomplished']));

                        if ($row['status'] == 'FINISHED') {
                            $status = "COMPLETED";
                        } 
                        else if ($row['status'] == 'IN PROGRESS') {
                            $status = "IN PROGRESS";
                        } 
                        else if ($status == "NOT YET STARTED") {
                            $status = "TO DO";
                        }
                        echo "
                            <tr>
                                <td><center/>" . $row["task_code"] . " </td>"; ?>
                                <?php
                                if ($row['requirement_status'] == 1 && $status == "NOT YET STARTED"){
                                    echo "<td><center/><span style='color: #00ff26'><i class='fa fa-paperclip' title='Attachment Required'></i></span></td>";
                                }
                                echo "
                                <td><center/>" . $row["task_name"] . "</td> 
                                <td><center/>" . $row["task_class"] . "</td>
                                <td><center/>" . $row["fname"].' '.$row["lname"] . "</td>
                                <td><center/>" . $due . "</td>";?>
                                <?php
                                if ($status == "FINISHED"){
                                    echo "<td><center/>" . $date . "</td>
                                            <td><center/>". $achievement ."</td>";
                                }
                                echo"
                            </tr> ";
                    } 
            ?>
            </tbody>
        </table>
    </div>
</body>

</html>