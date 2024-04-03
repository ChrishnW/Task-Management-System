<?php 
include('../include/header.php');
$id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.'); 
?>

<html>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
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
                    <h1 class="page-header">List of Assigned Tasks [ <?php echo $id; ?> ]
                        <a href='manage_task_emp_xls.php?id=<?php echo $id?>'> <button
                                class='btn btn-md btn-success pull-right'><i class='fas fa-download'></i> Download</button></a>
                    </h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <?php echo $id; ?>
                        </div>

                        <div class="panel-body">
                            <div class="table-responsive">
                                <table width="100%" class="table table-striped table-bordered table-hover "
                                    id="table">

                                    <thead>
                                        <tr>
                                            <th class="col-lg-2">
                                                <center />Employee Name
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Task Code
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Task Name
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Task Details
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Task Classification
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                    <?php
                                    $result = mysqli_query($con,"SELECT accounts.fname, accounts.lname, tasks.task_code, task_list.task_name, task_list.task_details, task_class.task_class, tasks.id  FROM tasks LEFT JOIN accounts ON tasks.in_charge=accounts.username INNER JOIN task_list ON tasks.task_code=task_list.task_code AND task_list.status IS TRUE LEFT JOIN task_class ON task_list.task_class=task_class.id WHERE tasks.in_charge='$id'");
                                    if (mysqli_num_rows($result)>0) { 
                                    while ($row = $result->fetch_assoc()) { 
                                    $emp_name = $row['fname'].' '.$row['lname'];
                                    $task_code = $row['task_code'];
                                    $task_name = $row['task_name'];
                                    $task_details = $row['task_details'];
                                    $task_class = $row['task_class'];
                                    $task_id = $row['id'];
                                    echo "<tr>                                                       
                                        <td>" . $emp_name . "</td>                                                     
                                        <td> " . $task_code . "</td> 
                                        <td> " . $task_name . "</td> 
                                        <td> " . $task_details . "</td> 
                                        <td>" . $task_class . "</td>
                                        <td> <center /><button data='".$task_id."' class='delete btn btn-danger '><i class='fas fa-minus-circle'></i> Remove</button>
                                        </td>
                                    </tr>";  
                                    }
                                }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <a href="javascript:history.back()"> <button class='btn btn-danger pull-left'><i class="fa fa-arrow-left"></i> Back</button></a>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
$(document).ready(function() {
    $('#table').DataTable({
        responsive: true
    });
});
</script>

<script>
//delete
$(document).on('click', '.delete', function() {
    var status = ($(this).hasClass("btn-danger"));
    if (confirm("Are you sure you want to remove this data?")) {
        var current_element = $(this);
        $.ajax({
            type: "POST",
            url: "manage_task_delete.php",
            data: {
                id: $(current_element).attr('data')
            },
            success: function(data) {
                //row.closest('tr').remove();
                location.reload();
            }
        });
    }
});
</script>
</html>