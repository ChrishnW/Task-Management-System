<?php 
    include('../include/header_employee.php');
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
<div id="content" class="p-4 p-md-5 pt-5">
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">My Assigned Tasks <a href='my_tasks_xls.php?id=<?php echo $username?>'> <button class='btn btn-md btn-success pull-right'><i class='fas fa-download'></i> Download</button></a> </h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"> My Tasks </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table width="100%" class="table table-dark" id="table">
                                    <thead>
                                        <tr>
                                            <th scope="col"> Task Names </th>
                                            <th scope='col' title='Legend'> <i class='fa fa-asterisk' /> </th>
                                            <th scope="col"> Task Details </th>
                                            <th scope="col"> Task Classifications </th>
                                            <th scope="col"> Task Recurrances </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                    <?php
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
                                                    echo "<td> <span style='color: #00ff26'><i class='fa fa-paperclip' title='Attachment Required'></i></span></td>";
                                                } 
                                                else {
                                                    echo "<td> </td>";
                                                }
                                                echo " 
                                                <td id='normalwrap'> " . $task_details . "</td>         
                                                <td><center />" . $task_class . "</td>
                                                <td><center />" . $due_date . "</td> 
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
</div>
<script>
$(document).ready(function() {
    $('#table').DataTable({
        responsive: true,
        "order": [[ 3, "asc"]]
    });
});
</script>
<script>
$(document).ready(function () {
  $('#table').DataTable();
  $('.dataTables_length').addClass('bs-select');
});
</script>
</html>