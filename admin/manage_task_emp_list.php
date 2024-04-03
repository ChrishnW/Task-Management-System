<?php 
include('../include/header.php');
$id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.'); 
$sec=$_GET['section'];
?>

<html>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
<link href="../assets/css/darkmode.css" rel="stylesheet">

<head>
    <title>Employees Assigned Tasks</title>
</head>
<div id="content" class="p-4 p-md-5 pt-5">
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">List of Assigned Tasks
                        <a href='manage_task_emp_xls.php?id=<?php echo $id?>'> <button class='btn btn-md btn-success pull-right'><i class='fas fa-download'></i> Download</button></a>
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
                                <table width="100%" class="table table-striped table-hover " id="table">
                                    <thead>
                                        <tr>
                                            <th class="col">
                                                <center />Task Name
                                            </th>
                                            <th class="col">
                                                <center />Task Details
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Task Classification
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Task Recurrence
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                    <?php
                                    $result = mysqli_query($con,"SELECT *, tasks.id FROM tasks JOIN task_class ON task_class.id=tasks.task_class WHERE in_charge='$id'");
                                    if (mysqli_num_rows($result)>0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $id = $row['id'];
                                            $task_name = $row['task_name'];
                                            $task_details = $row['task_details'];
                                            $task_class = $row['task_class'];
                                            $submission = $row['submission'];

                                            echo "<tr>                                                       
                                                <td id='normalwrap'> " . $task_name . "</td> 
                                                <td id='normalwrap'> " . $task_details . "</td> 
                                                <td>" . $task_class . "</td>
                                                <td>" . $submission . "</td>
                                                <td> <center />
                                                    <button value='".$id."' data-name='".$task_name."' data-details='".$task_details."' data-class='".$task_class."' data-submission='".$submission."' class='btn btn-success' onclick='okButtonClick(this)'><i class='fa fa-pencil-square-o'></i> Edit</button> 
                                                    <button data='".$id."' class='delete btn btn-danger'><i class='fas fa-minus-circle'></i> Remove</button>
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
                    <a href="manage_task_list.php"> <button class='btn btn-danger pull-left'><i class="fa fa-arrow-left"></i> Back to Employees Task List</button></a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#table').DataTable({
        responsive: true,
        "order": [[ 2, "asc"]]
    });
});

$(document).on('click', '.delete', function() {
    var status = ($(this).hasClass("btn-danger"));
    if (confirm("Are you sure you want to delete this task for this employee?")) {
        var current_element = $(this);
        $.ajax({
            type: "POST",
            url: "manage_task_delete.php",
            data: {
                id: $(current_element).attr('data')
            },
            success: function(data) {
                location.reload();
            }
        });
    }
});

function okButtonClick(element) {
    var id = element.value;
    var taskname = element.getAttribute("data-name");
    var taskclass = element.getAttribute("data-class");
    var taskdetails = element.getAttribute("data-details");
    var submission = element.getAttribute("data-submission");

    $(document).ready(function() {
        $('#edit').modal('show');
        document.getElementById('id').value = id;
        document.getElementById('taskname').value = taskname;
        document.getElementById('taskclass').value = taskclass;
        document.getElementById('taskdetails').value = taskdetails;
        document.getElementById('submission').value = submission;
    });
}

function submitEdit() {
    var track = document.getElementById('id').value;
    var recurrence = document.getElementById('submission').value;
    var btn = document.getElementById('saveedit');
    var originalText = btn.textContent; // Store the original button text

    $.ajax({
        type: "POST",
        url: "task_assigned_edit.php",
        data: { id: track, action: recurrence }
    }).done(function(response) {
        document.getElementById('saveedit').disabled = true;
        btn.textContent = 'Saving...'; // Change the button text to "Waiting"
        setTimeout(function() {
            $('#edit').modal('hide');
            window.location.reload();
        }, 2000); // Adjust the delay time (in milliseconds) as needed
    }).fail(function(xhr, status, error) {
        alert("An error occurred: " + status + "\nError: " + error);
    });
}
</script>

<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content panel-success">
			<div class="modal-header panel-heading">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Edit Assinged Task</h4>
			</div>
			<div class="modal-body panel-body">
                <form data-toggle="validator" action="change_password_submit.php" enctype="multipart/form-data" method="post">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Task Name:</label>
                            <input type="text" class="form-control" id="taskname" name="taskname" disabled>
                            <input type="text" id="id" name="id" hidden><br>

                            <label>Task Details:</label>
                            <input type="text" class="form-control" id="taskdetails" name="taskdetails" disabled><br>

                            <label>Task Classification: </label>
                            <input type="text" class="form-control" id="taskclass" name="taskclass" disabled><br>

                            <label>Task Recurrence:</label>
                            <input type="text" class="form-control" id="submission" name="submission">
                        </div>
                    </div>
                </form>
			</div>
			<div class="modal-footer">
				<button id='saveedit' class='btn btn-success pull-right' onclick="submitEdit()"><span class="fa fa-floppy-o"> </span> Save</button>
                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
			</div>
		</div>
	</div>
</div>

</html>