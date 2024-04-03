<?php 
include('../include/header_employee.php');
include('../include/bubbles.php');
$username=isset($_GET['username']) ? $_GET['username'] : die('ERROR: Record ID not found.'); 
?>

<html>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
<link href="../assets/css/darkmode.css" rel="stylesheet">

<style>
.form-group.required label {
    font-weight: bold;
}
.form-group.required label:after {
    color: #e32;
    content: ' *';
    display: inline;
}
</style>

<head>
    <title>Employees Assigned Tasks</title>
</head>
<body>
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">List of Assigned Tasks [ <?php echo $username; ?> ]
                        <a href='my_tasks_xls.php?id=<?php echo $username?>'> <button
                                class='btn btn-md btn-success pull-right'><i class='fas fa-download'></i> Download</button></a>
                    </h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            My Tasks
                        </div>

                        <div class="panel-body">
                            <div class="table-responsive">
                                <table width="100%" class="table table-bordered table-dark" id="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">
                                                <center />Task Code
                                            </th>
                                            <th scope="col">
                                                <center />Task Classification
                                            </th>
                                            <th scope="col">
                                                <center />Task Name
                                            </th>
                                            <th scope="col">
                                                Task Details
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                    <?php
                                    $result = mysqli_query($con,"SELECT accounts.fname, accounts.lname, tasks.task_code, task_list.task_name, task_list.task_details, task_class.task_class, tasks.id  FROM tasks LEFT JOIN accounts ON tasks.in_charge=accounts.username INNER JOIN task_list ON tasks.task_code=task_list.task_code AND task_list.status IS TRUE LEFT JOIN task_class ON task_list.task_class=task_class.id WHERE tasks.in_charge='$username' GROUP BY task_list.task_name ORDER BY task_list.id DESC");
                                    if (mysqli_num_rows($result)>0) { 
                                    while ($row = $result->fetch_assoc()) { 
                                    $task_code = $row['task_code'];
                                    $task_name = $row['task_name'];
                                    $task_details = $row['task_details'];
                                    $task_class = $row['task_class'];
                                    $task_id = $row['id'];
                                    echo "<tr>                                                                                                         
                                        <td><center />" . $task_code . "</td> 
                                        <td><center />" . $task_class . "</td>
                                        <td><center />" . $task_name . "</td> 
                                        <td> " . $task_details . "</td> 
                                        
                                    </tr>";  
                                    }
                                }
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
</body>
<!-- <script>
$(document).ready(function() {
    $('#table').DataTable({
        responsive: true
    });
});
</script> -->
</html>