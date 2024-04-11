
<?php 
include('../include/header.php');
include('../include/connect.php');
$section=isset($_GET['section']) ? $_GET['section'] : die('ERROR: Record not found.'); 
?>
<html>
<head>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
<title>Task Details</title>
</head>

<div id="content" class="p-4 p-md-5 pt-5">
    <div id="wrapper">
        <div id="page-wrapper">
        <br>
        <h1 class="page-header"><?php echo $section ?> Task Details
        <a href="task_details_xls.php?section=<?php echo $section ?>"> <button class="btn btn-success pull-right" style="margin-top: 95px;"><span class="fa fa-download fa-fw"></span> Download</button></a></h1>
            <div class="row">
                <div class="col-lg-2">
                <label>Status:</label><br>
                    <select name="show_status" id="show_status" class="form-control selectpicker show-menu-arrow" placeholder="" onchange="selectstatus(this)">
                        <option disabled selected value="">--Filter by Status--</option>
                        <option value="ALL">All</option>
                        <option value="NOT YET STARTED">Not Yet Started</option>
                        <option value="IN PROGRESS">In Progress</option>
                        <option value="FINISHED">Finished</option>
                    </select>
                    <br>
                    <br>
                </div>
                <div class="col-lg-2">
                <label>Task Status:</label><br>
                    <select name="show_status" id="show_status" class="form-control selectpicker show-menu-arrow " placeholder="" onchange="selecttaskstatus(this)">
                        <option disabled selected value="">--Filter by Task Status--</option>
                        <option value="ALL">All</option>
                        <option value="1">Deployed</option>
                        <option value="0">Not deployed</option>
                    </select>
                    <br>
                    <br>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                        <?php echo $section ?> Task Details
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                            <a href="assign_task.php?section=<?php echo $section ?>"> <button class='btn btn-success  pull-right'><i class="fa fa-plus fa-fw"></i> Assign New Task</button></a><br><br><br>
                                <table width="100%" class="table table-striped table-hover " id="table_task">

                                    <thead>
                                        <tr>
                                            <th scope="col">
                                                Action
                                            </th>
                                            <th scope="col">
                                                Task Code
                                            </th>
                                            <th scope="col" title="Legend">
                                                <i class='fa fa-asterisk' />
                                            </th>
                                            <th scope="col">
                                                Task Name
                                            </th>
                                            <th scope="col">
                                                Task Classification
                                            </th>
                                            <th scope="col">
                                                Date Created
                                            </th>
                                            <th scope="col">
                                                Due Date
                                            </th>
                                            <th scope="col">
                                                In-charge
                                            </th>
                                            <th scope="col">
                                                Stage
                                            </th>
                                            <th scope="col">
                                                Status
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody id="show_task">
                                        <?php
                                        /* and access!='1' */
                                        $con->next_result();
                                        $result = mysqli_query($con,"SELECT *, (tasks_details.status) FROM tasks_details JOIN accounts ON accounts.username = tasks_details.in_charge JOIN task_class ON tasks_details.task_class = task_class.id WHERE task_status = 1 AND task_for = '$section'");
                                        if (mysqli_num_rows($result)>0) { 
                                            while ($row = $result->fetch_assoc()) {
                                                $task_class = $row['task_class'];
                                                $emp_name=$row['fname'].' '.$row['lname'];

                                                if (empty($row["file_name"])) {
                                                    // Use a default image URL
                                                    $imageURL = '../assets/img/user-profiles/nologo.png';
                                                } else {
                                                    // Use the image URL from the database
                                                    $imageURL = '../assets/img/user-profiles/'.$row["file_name"];
                                                }
                                                if ($row['task_status'] == '1') {
                                                        $class_label = "success";
                                                        $sign = "Deployed";
                                                    }
                                                else {
                                                    $class_label = "danger";
                                                    $sign = "Not deployed";
                                                }

                                                if ($row['status'] == 'NOT YET STARTED') {
                                                    $class_label_status = "info";
                                                    $status = "To Do";
                                                }
                                                elseif ($row['status'] == 'IN PROGRESS') {
                                                    $class_label_status = "warning";
                                                    $status = "In Progress";
                                                }
                                                elseif ($row['status'] == 'FINISHED') {
                                                    $class_label_status = "primary";
                                                    $status = "Complete";
                                                }
                                                
                                                echo "<tr>
                                                    <td> <center /><a href='task_details_edit.php?id=".$row['task_code']."&&section=".$section."'<button class='btn btn-primary' ><i class='fa fa-edit fa-1x'></i> Edit</button></a>
                                                    <td> " . $row["task_code"] . " </td>"; ?>
                                                    <?php
                                                    if ($row['requirement_status'] == 1){
                                                        echo "<td> <span style='color: #00ff26'><i class='fa fa-paperclip' title='Attachment Required'></i></span></td>";
                                                    }
                                                    else {
                                                        echo "<td> </td>";
                                                    }
                                                    echo"
                                                    <td id='normalwrap'> " . $row["task_name"] . " </td>
                                                    <td>" . $row["task_class"] . "</td>
                                                    <td>" . $row["date_created"] . "</td> 
                                                    <td>" . $row["due_date"] . "</td> 
                                                    <td style='text-align: justify'> <img src=".$imageURL." title=".$row["username"]." style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-right: 15px; margin-left: 0'>" . $emp_name . "</td>  
                                                    </td>
                                                    <td><p class='label label-".$class_label_status."' style='font-size:100%;'>" . $status . "</p></td>
                                                    <td><p class='label label-".$class_label."' style='font-size:100%;'>".$sign."</p></td>
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
    $('#table_task').DataTable({
        responsive: true,
        "order": [[ 6, "desc"], [ 8, "asc"]]
    });
});
</script>

<script>
function selectstatus(element) {
    let sid = $(element).val();
    let section = <?php echo json_encode($section) ?>;
    $('#table_task').DataTable().destroy();
    $('#show_task').empty();
    if (sid) {
        $.ajax({
            type: "post",
            url: "task_details_ajax.php",
            data: {
                "sid": sid,
                "section": section
            },
            success: function(response) {
                $('#show_task').append(response);
                $('#table_task').DataTable();
            }
        });
    }
}

function selecttaskstatus(element) {
    let sid = $(element).val();
    let section = <?php echo json_encode($section) ?>;
    $('#table_task').DataTable().destroy();
    $('#show_task').empty();
    if (sid) {
        $.ajax({
            type: "post",
            url: "task_details_status_ajax.php",
            data: {
                "sid": sid,
                "section": section
            },
            success: function(response) {
                $('#show_task').append(response);
                $('#table_task').DataTable();
            }
        });
    }
}
</script>
</html>