<?php 
include('../include/header.php');
include('../include/connect.php');
include('../include/bubbles.php');
?>

<html>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
<link href="../assets/css/darkmode.css" rel="stylesheet">

<head>
<title>Manage Tasks</title>
</head>

<body>
    <div id="wrapper">
        <div id="page-wrapper">

            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Attendance Record
                    <a href="attendance_xls.php"> <button class="btn btn-success pull-right"><span class="fa fa-download"></span> Download</button></a>
                    </h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Record Logs</div>

                        <div class="panel-body">
                            <div class="table-responsive">
                                <table width="100%" class="table table-striped table-bordered table-hover "
                                    id="table">
                                    <thead>
                                        <tr>
                                            <th class="col-lg-2">
                                                <center />Card Number
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Employee
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Section
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Date
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody id="show_account">
                                        <?php
                                            $con->next_result();
                                            $result = mysqli_query($con,"SELECT attendance.card, attendance.date, accounts.card, accounts.fname, accounts.lname, accounts.sec_id, accounts.email  FROM attendance INNER JOIN accounts ON attendance.card=accounts.card ORDER BY attendance.date DESC");               
                                            if (mysqli_num_rows($result)>0) { 
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>
                                                    <td> <center />" . $row["card"] . "</td>
                                                    <td> <center />" . $row["fname"] . " ". $row["lname"] . "</td>
                                                    <td> <center />" . $row["sec_id"] . "</td>
                                                    <td> <center />" . $row["date"] . "</td>
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
                        </div>
                    </div>
                    <a href="./index.php"> <button class='btn btn-danger pull-left'><i class="fa fa-arrow-left"></i> Return to Dashboard</button></a>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
$(document).ready(function() {
    $('#table').DataTable({
        responsive: true,
        destroy: true,
        "order": [[ 3, "desc" ]]
    });
});
</script>
</html>