<?php
$today = date('Y-m-d');
$month = date('m'); //Number of Month
$monthname = date('F'); //Name of the Month
$year = date('Y'); //Year
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=ATTENDANCE-SUMMARY_".$monthname.$year.".xls");  //File name extension was wrong
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
       <h3> <b>ATTENDANCE SUMMARY of</b>
            <b><?php echo $monthname,' ', $year ?></b>
       </h3>
        <br>
    </center>
    <br>

    <div id="table-scroll">
        <table width="100%" border="1" align="left">
            <thead>
                <tr>
                    <th>Card Number</th>
                    <th>Employee</th>
                    <th>Section</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>
                <?php
            

            $con->next_result();
            $result = mysqli_query($con,"SELECT attendance.card, attendance.date, accounts.card, accounts.fname, accounts.lname, accounts.sec_id, accounts.email FROM attendance INNER JOIN accounts ON attendance.card=accounts.card WHERE MONTH(attendance.date) = '$month' ORDER BY accounts.sec_id, attendance.date DESC");
          
            while($row = mysqli_fetch_array($result))
            {
                echo "
                <tr>
                <td> <center />" . $row["card"] . "</td>
                <td> <center />" . $row["fname"] . " ". $row["lname"] . "</td>
                <td> <center />" . $row["sec_id"] . "</td>
                <td> <center />" . $row["date"] . "</td>
                </tr> ";
            } 
            ?>
            </tbody>

        </table>
    </div>
</body>

</html>