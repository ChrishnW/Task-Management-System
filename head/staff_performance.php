<?php 
	include('../include/header_head.php');
	include('../include/connect.php');
?>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">

<div id="content" class="p-4 p-md-5 pt-5">
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Staff Performance</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Active Sections</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table width="100%" class="table table-striped table-hover" id="table">
                                    <thead>
                                        <tr>
                                            <th class="col-lg-2"> <center /> Section </th>
                                            <th class="col-xs-1"> <center /> Monthly Report </th>
                                            <th class="col-xs-1"> <center /> Task Performance </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                        <?php
                                        $con->next_result();
                                        $getactivesection = mysqli_query($con,"SELECT * FROM section JOIN department ON department.dept_id=section.dept_id WHERE section.status=1 AND section.dept_id='$dept_id'");              
                                        if (mysqli_num_rows($getactivesection)>0) {
                                            while ($row = $getactivesection->fetch_assoc()) {
												$task_sec = $row['sec_id'];
                                                $sec_name = $row['sec_name'];
												$result = mysqli_query($con,"SELECT COUNT('id') as sec_total_tasks FROM tasks_details JOIN accounts ON tasks_details.in_charge=accounts.username JOIN task_class on task_class.id=tasks_details.task_class WHERE task_status = 1 AND task_for = '$task_sec'");
												$rows = $result->fetch_assoc();
												$total_task=$rows['sec_total_tasks'];
												echo"
												<tr>
													<td>".$row['sec_name']."</td>
													<td><a href='performance.php?section=$task_sec&monthly=TRUE'> <button class='btn btn-md btn-primary' style='margin-left: 10px'><i class='fa fa-eye'></i> View</button></a></td>
													<td><a href='performance.php?section=$task_sec'> <button class='btn btn-md btn-primary' style='margin-left: 10px'><i class='fa fa-eye'></i> View</button></a></td>
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