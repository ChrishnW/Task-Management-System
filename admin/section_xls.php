<?php
$today = date('Y-m-d');
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=SECTION-SUMMARY_".$today.".xls");  //File name extension was wrong
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
       <h3> <b>REGISTERED SECTION SUMMARY</b></h3>
        <br>
    </center>
    <br>

    <div id="table-scroll">
        <table width="100%" border="1" align="left">
            <thead>
                <tr>
                    <th>Section ID</th>
                    <th>Section Name</th>
                    <th>Department</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php
            

            $con->next_result();
            $result = mysqli_query($con,"SELECT section.id, section.sec_id, section.sec_name, department.dept_name, section.status FROM section LEFT JOIN department ON department.dept_id=section.dept_id ORDER BY department.dept_name ASC");
          
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
                <td><center />" . $row["sec_id"] . "</td> 
                <td><center />" . $row["sec_name"] . "</td> 
                <td><center />" . $row["dept_name"] . "</td> 
                <td><center />". $status ."</td>
                </tr> ";
            } 
            ?>
            </tbody>

        </table>
    </div>
</body>

</html>