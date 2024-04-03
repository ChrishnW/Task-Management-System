<?php
    $today = date('Y-m-d');
    $task_sec = $_GET['section'];

    // header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    // header("Content-Disposition: attachment; filename=".$task_sec."_TASKS-MASTERLIST.xls");  //File name extension was wrong
    // header("Expires: 0");
    // header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    // header("Cache-Control: private",false);

    include('../include/auth.php');
    include('../include/connect.php');
?>

<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <?php
        $section_name = mysqli_query($con, "SELECT * FROM section WHERE sec_id = '$task_sec'");
        $section_name_query = mysqli_fetch_assoc($section_name); // Corrected line
        $name = $section_name_query['sec_name'];
    ?>
    <center>
        <b>
            <font color="blue">GLORY (PHILIPPINES), INC.</font>
        </b>
        <br>
        <b>TASK MANAGEMENT SYSTEM</b>
        <br>
        <h3>
            <b><?php echo $name?></b>
            <br>
            <b>TASKS MASTERLIST</b>
        </h3>
        <br>
    </center>
    <br>

    <div id="table-scroll">
        <table width="100%" border="1" align="left">
            <thead>
                <tr>
                    <th class="col-sm-1"> <center>#</center> </th>
                    <th class="col-lg-2"> <center />Task Name </th>
                    <th class="col-lg-2"> <center />Task Details </th>
                    <th class="col-lg-2"> <center />Task Classification </th>
                    <th class="col-lg-2"> <center />Date Registered </th>
                    <th class="col-lg-1"> <center />Status </th>
                </tr>
            </thead>
            <tbody id="show_account">
                <?php
                    $con->next_result();
                    $result = mysqli_query($con,"SELECT task_list.id, task_list.task_name, task_list.task_details, task_class.task_class, section.sec_name, task_list.date_created, task_list.status FROM task_list LEFT JOIN task_class ON task_class.id = task_list.task_class LEFT JOIN section ON section.sec_id = task_list.task_for WHERE task_list.status = '1' AND task_list.task_for = '$task_sec'");               
                    if (mysqli_num_rows($result)>0) {
                        $number = 0; 
                        while ($row = $result->fetch_assoc()) {
                            $date = date('F d, Y', strtotime($row['date_created']));
                            $number += 1;
                            echo "<tr>
                                <td> " . $number . "</td>                               
                                <td id='normalwrap'> " . $row["task_name"] . " </td>
                                <td id='normalwrap'> ". $row["task_details"] ." </td>
                                <td>" . $row["task_class"] . "</td>
                                <td>" . $date . "</td> 
                                <td><center/>" .($row['status']=='1' ? '<p class="label label-success" style="font-size:100%;">ACTIVE</p>' : '<p class="label label-danger" style="font-size:100%;">INACTIVE</p>' ). "</td>
                                </td>
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
</html>