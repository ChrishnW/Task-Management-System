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
                    <h1 class="page-header">List of Assigned Tasks
                        <a href="manage_task_add.php"> <button class="btn btn-success pull-right"><span
                                    class="fa fa-plus"></span> Assign Tasks</button></a>
                    </h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">List of Assigned Tasks</div>

                        <div class="panel-body">
                            <div class="table-responsive">
                                <table width="100%" class="table table-striped table-bordered table-hover "
                                    id="table">
                                    <thead>
                                        <tr>
                                            <th class="col-lg-2">
                                                <center />Employee
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Section
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Tasks
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody id="tbody">
                                        <?php
                                        $con->next_result();
                                        $result = mysqli_query($con,"SELECT * FROM accounts LEFT JOIN section ON accounts.sec_id=section.sec_id WHERE accounts.status='1' AND accounts.access='2'");              
                                        if (mysqli_num_rows($result)>0) { 
                                            while ($row = $result->fetch_assoc()) {                                                
                                                $emp_name=$row['fname'].' '.$row['lname'];
                                                $section=$row["sec_name"];
                                                $username=$row["username"];
                                                $count_task = mysqli_query($con,"SELECT COUNT(id) as total_task FROM tasks WHERE in_charge='$username'");
                                                $count_task_row = $count_task->fetch_assoc();
                                                $total_task=$count_task_row['total_task'];
                                                $label='Task/(s)';
                                                if ($total_task=='') {
                                                    $total_task='No';
                                                }
                                                    echo "<tr>                                                       
                                                        <td>" . $emp_name . "</td>                                                   
                                                        <td> " . $section . "</td>
                                                        <td> " . $total_task .' '.$label. "
                                                            <a href='manage_task_emp_list.php?id=".$username."'> <button class='btn btn-md btn-primary pull-right' ><i class='fas fa-eye'></i> Table</button></a>
                                                        </td>
                                                    </tr>"; 
                                            } 
                                        }
                                        else {
                                            echo "0 results"; }    
                                        if ($con->connect_error) {
                                            die("Connection Failed".$con->connect_error); }; 
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
        "order": [[ 2, "asc" ]]
    });
});
</script>
</html>