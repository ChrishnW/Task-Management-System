<?php 
include('../include/header.php');
include('../include/connect.php');
?>
<html>
<head>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">

    <title>List of Tasks</title>
</head>

<body>
    <div id="wrapper">
        <div id="page-wrapper">
        <h1 class="page-header">List of Tasks
        <a href="task_xls.php"> <button class="btn btn-success pull-right"><span class="fa fa-download"></span> Download</button></a></h1>
            <div class="row">
                <div class="col-lg-4">
                <label>Status:</label><br>
                    <select name="show_status" id="show_status" class="form-control selectpicker show-menu-arrow "
                        placeholder="" onchange="selectmodel(this)">
                        <option disabled selected value="">--Sort by Status--</option>
                        <option selected value="1">ACTIVE</option>
                        <option value="0">INACTIVE</option>
                    </select>
                    <br>
                    <br>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            List of Tasks
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                            <a href="task_add.php"> <button class='btn btn-success  pull-right'><i class="fa fa-plus"></i> Add</button></a><br><br><br>
                                <table width="100%" class="table table-striped table-bordered table-hover "
                                    id="table_account">

                                    <thead>
                                        <tr>
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
                                                <center />Task For
                                            </th>
                                            <th class="col-lg-1">
                                                <center />Status
                                            </th>
                                            <th class="col-lg-1">
                                                <center />Action
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody id="show_account">
                                        <?php
                                        /* and access!='1' */
                                        $con->next_result();
                                        $result = mysqli_query($con,"SELECT task_list.task_code, task_list.task_name, task_list.task_details, task_class.task_class, task_list.status, task_list.id, task_list.task_for, section.sec_name, section.sec_name FROM task_list LEFT JOIN task_class ON task_list.task_class=task_class.id  LEFT JOIN section ON task_list.task_for=section.sec_id WHERE task_list.status='1' ORDER BY task_list.task_code ASC");               
                                        if (mysqli_num_rows($result)>0) { 
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>  
                                                    <td> " . $row["task_code"] . " </td>                                                     
                                                    <td> " . $row["task_name"] . " </td>   
                                                    <td>" . $row["task_details"] . "</td> 
                                                    <td>" . $row["task_class"] . "</td> 
                                                    <td>" . $row["sec_name"] . "</td> 
                                                    <td><center/>" .($row['status']=='1' ? '<p class="label label-success" style="font-size:100%;">Active</p>' : '<p class="label label-danger" style="font-size:100%;">Inactive</p>' ). "</td>
                                                    <td> <center /><a href='task_edit.php?id=".$row['id']."' <button class='btn btn-primary' ><i class='fa fa-edit fa-1x'></i> Edit</button></a>
                                                    </td>
                                                </tr>";   
                                            }
                                        } 
                                        if ($con->connect_error) {
                                            die("Connection Failed".$con->connect_error); }; ?>
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
    $('#table_account').DataTable({
        responsive: true
    });
});
</script>

<script>
function selectmodel(element) {
    let sid = $(element).val();
    $('#table_account').DataTable().destroy();
    $('#show_account').empty();
    if (sid) {
        $.ajax({
            type: "post",
            url: "task_ajax.php",
            data: {
                "sid": sid
            },
            success: function(response) {
                $('#show_account').append(response);
                $('#table_account').DataTable();
            }
        });
    }
}
</script>
</html>