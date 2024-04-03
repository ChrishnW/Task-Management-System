<?php 
include('../include/header.php');
include('../include/connect.php');
?>

<html>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
<link href="../assets/css/darkmode.css" rel="stylesheet">

<head>
<title>Manage Tasks</title>
</head>

<div id="content" class="p-4 p-md-5 pt-5">
    <div id="wrapper">
        <div id="page-wrapper">

            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Attendance Record
                    <a href="attendance_xls.php"> <button class="btn btn-success pull-right" style="margin-top: 10px;"><span class="fa fa-download fa-fw"></span> Download Table</button></a>
                    </h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Record Logs</div>

                        <div class="panel-body">
                            <div class="table-responsive">
                                <table width="100%" class="table table-striped table-hover "
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
                                            $result = mysqli_query($con,"SELECT accounts.username, accounts.file_name, attendance.card, attendance.date, accounts.card, accounts.fname, accounts.lname, accounts.sec_id, accounts.email  FROM attendance INNER JOIN accounts ON attendance.card=accounts.card ORDER BY attendance.date DESC");               
                                            if (mysqli_num_rows($result)>0) { 
                                                while ($row = $result->fetch_assoc()) {
                                                    $emp_name=$row['fname'].' '.$row['lname'];
                                                    if (empty($row["file_name"])) {
                                                        // Use a default image URL
                                                        $imageURL = '../assets/img/user-profiles/nologo.png';
                                                    } else {
                                                        // Use the image URL from the database
                                                        $imageURL = '../assets/img/user-profiles/'.$row["file_name"];
                                                    } 
                                                    echo "<tr>
                                                    <td> <center />" . $row["card"] . "</td>
                                                    <td style='text-align: justify'> <img src=".$imageURL." title=".$row["username"]." style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-right: 45px'>" . $emp_name . "</td>  
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
                </div>
            </div>
        </div>
    </div>
</div>

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