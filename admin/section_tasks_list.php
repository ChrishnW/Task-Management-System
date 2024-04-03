<?php 
	include('../include/header.php');
	include('../include/connect.php');
?>

<html>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">

<head>
<title>Manage Tasks</title>
</head>

<div id="content" class="p-4 p-md-5 pt-5">
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Task List</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                <label>Section:</label><br>
                    <select name="show_status" id="show_status" class="form-control selectpicker show-menu-arrow "
                        placeholder="" onchange="selectsection(this)">
                        <option disabled selected value="">--Sort by Section--</option>
                        <?php
                            $sql = mysqli_query($con,"SELECT * FROM section WHERE status=1 ORDER BY sec_name ASC"); 
                            $con->next_result();
                            if(mysqli_num_rows($sql)>0){
                                while($row=mysqli_fetch_assoc($sql)){
                                    $sec_id1 = $row['sec_id'];
                                    $sec_name1 = $row['sec_name'];
                                    echo "<option value='".$sec_id1."'>".strtoupper($sec_name1)."</option>";
                                }
                            } 
                        ?>
                    </select>
                    <br>
                    <br>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">List of Employee</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table width="100%" class="table table-striped table-hover" id="table">
                                    <thead>
                                        <tr>
                                            <th class="col-lg-2">
                                                <center />Section
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Department
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Total Registered Tasks
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody id="tbody">
                                        <?php
                                        $con->next_result();
                                        $getactivesection = mysqli_query($con,"SELECT * FROM section JOIN department ON department.dept_id=section.dept_id WHERE section.status=1");              
                                        if (mysqli_num_rows($getactivesection)>0) {
                                            while ($row = $getactivesection->fetch_assoc()) {
												$task_sec = $row['sec_id'];
												$result = mysqli_query($con,"SELECT COUNT(id) as sec_total_tasks FROM task_list WHERE task_list.task_for = '$task_sec' AND task_list.status IS TRUE");
												$rows = $result->fetch_assoc();
												$total_task=$rows['sec_total_tasks'];
												echo"
												<tr>
													<td>".$row['sec_name']."</td>
													<td>".$row['dept_name']."</td>
													<td><a href='task_list.php?section=$task_sec'> <button class='btn btn-md btn-folder' style='margin-left: 10px'><i class='fa fa-folder'></i> ".$total_task." Registered Tasks</button></a></td>
												</tr>
												";
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
        "order": [[ 0, "asc" ]]
    });
});

function selectsection(element) {
    let sid = $(element).val();
    $('#table').DataTable().destroy();
    $('#tbody').empty();
    if (sid) {
        $.ajax({
            type: "post",
            url: "assigned_tasks_ajax.php",
            data: {
                "sid": sid
            },
            success: function(response) {
                $('#tbody').append(response);
                $('#table').DataTable();
            }
        });
    }
}
</script>
</html>