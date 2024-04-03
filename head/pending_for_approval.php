
<?php 
include('../include/header_head.php');
include('../include/connect.php');
$date_today = date('Y-m-d');
?>
<html>
<head>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
<link href="../assets/css/darkmode.css" rel="stylesheet">

    <title>Tasks</title>
    <style>
        #hidden-btn {
            display: none;
        }
    </style>
</head>

<div id="content" class="p-4 p-md-5 pt-5">
    <div id="wrapper">
        <div id="page-wrapper">
        <h1 class="page-header"> Completed Tasks Approval </h1>
        <button id="myButton" class='btn btn-success pull-right' style="margin-top: -60px; display: none"><i class="fa fa-check"></i> Approve Selected</button>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                        Request for Task Rescheduling
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <button class='btn btn-success pull-right' id='hidden-btn' onclick='done(this)'><i class="fa fa-check-square"></i> Approve Marked</button>
                                <label class="pull-left">
                                    Select All:
                                    <input type="checkbox" id="selectAll" class="messageCheckbox" style="width: 20px; height: 20px"/>
                                </label><br><br><br>
                                <table width="100%" class="table table-striped table-hover" id="table_task">
                                    <thead>
                                        <tr>
                                            <th>
                                                <center>#</center>
                                            </th>
                                            <th class="col">
                                                <center>Task Code</center>
                                            </th>
                                            <th scope='col' title='Legend'>
                                                <i class='fa fa-asterisk' />
                                            </th>
                                            <th class="col">
                                                <center>Task Name</center>
                                            </th>
                                            <th class="col">
                                                <center>Task Classification</center>
                                            </th>
                                            <th class="col">
                                                <center>In-charge</center>
                                            </th>
                                            <th class="col">
                                                <center>Status</center>
                                            </th>
                                            <th class='col'>
                                                <center>Details</center>
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody id="show_task">
                                        <?php
                                        /* and access!='1' */
                                        $con->next_result();
                                        $result = mysqli_query($con,"SELECT * FROM tasks_details JOIN task_class ON tasks_details.task_class = task_class.id JOIN accounts ON tasks_details.in_charge=accounts.username JOIN section ON section.sec_id=tasks_details.task_for WHERE tasks_details.status='FINISHED' AND task_status=1 AND tasks_details.approval_status=1 AND section.dept_id='$dept_id'");           
                                        if (mysqli_num_rows($result)>0) { 
                                            while ($row = $result->fetch_assoc()) {
                                                $today = date("Y-m-d");
                                                $due_date = $row["due_date"];
                                                $class = "";
                                                $emp_name=$row['fname'].' '.$row['lname'];
                                                if (empty($row["file_name"])) {
                                                    // Use a default image URL
                                                    $imageURL = '../assets/img/user-profiles/nologo.png';
                                                } else {
                                                    // Use the image URL from the database
                                                    $imageURL = '../assets/img/user-profiles/'.$row["file_name"];
                                                }
                                                $class_label = "success";
                                                $sign = "FINISHED";

                                                echo "<tr>
                                                    <td> <input type='checkbox' class='messageCheckbox' name='item[]' id='flexCheckDefault' value='".$row['task_code']."' onclick='toggleTask()'/> </td>
                                                    <td class='".$class."'>". $row["task_code"] . " </td>"; ?>
                                                    <?php
                                                    if ($row['requirement_status'] == 1){
                                                        echo "<td class='".$class."'> <span style='color: #00ff26'><i class='fa fa-paperclip' title='Attachment Required'></i></span></td>";
                                                    }
                                                    else {
                                                        echo "<td class='".$class."'> </td>";
                                                    }
                                                    echo "
                                                    <td id='normalwrap'> " . $row["task_name"] . " </td>   
                                                    <td>" . $row["task_class"] . "</td> 
                                                    <td style='text-align: justify'> <img src=".$imageURL." title=".$row["username"]." style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-right: 15px; margin-left: 0'>" . $emp_name . "</td> 
                                                    <td class='".$class."'><center /><p class='label label-".$class_label."' style='font-size:100%;'>".$sign."</p></td>
                                                    <td><center><button value='".$row['task_code']."' data-name='".$row['task_name']."' data-class='".$row['task_class']."' data-remarks='".$row['remarks']."' data-duedate='".$row['due_date']."' data-datefinish='".$row['date_accomplished']."' data-achievement='".$row['achievement']."' data-file='".$row['requirement_status']."' data-path='".$row['attachment']."' data-head = '".$myname."' class='btn btn-primary' onclick='view2(this)'><span class='fa fa-folder-open'></span> View </button></center></td> 
                                                </tr>";
                                            }
                                        }
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
</div>

<script>
    function done(obj) {
        var taskID = obj.value;

        $(document).ready(function() { 
            $('#caution').modal('show');
        });
    }

    window.onload = function() {
    // Get all checkboxes with the same class name
    var checkboxes = document.querySelectorAll('.messageCheckbox');
    var button = document.getElementById('hidden-btn');

    // Function to check if any checkbox is checked
    function checkCheckboxes() {
        var isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
        button.style.display = isChecked ? 'block' : 'none';
    }

    // Add event listener to each checkbox
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', checkCheckboxes);
    });

    // Initial check to set the correct button visibility
    checkCheckboxes();
    };

    function toggleAll(source) {
        const checkboxes = document.querySelectorAll('input[name="item[]"]');

        checkboxes.forEach((checkbox) => {
            checkbox.checked = source.checked;
        });
    }

    // Attach the function to the "Select All" checkbox
    const selectAllCheckbox = document.getElementById('selectAll');
    selectAllCheckbox.addEventListener('change', function () {
        toggleAll(this);
    });

    function view2(element) {
        var taskcode2 = element.value;
        var taskname2 = element.getAttribute("data-name");
        var taskclass2 = element.getAttribute("data-class");
        var remarks2 = element.getAttribute("data-remarks");
        var duedate2 = element.getAttribute("data-duedate");
        var datefinish2 = element.getAttribute("data-datefinish");
        var achievement2 = element.getAttribute("data-achievement");
        var filepath2 = element.getAttribute("data-path");
        var file2 = element.getAttribute("data-file");
        var head = element.getAttribute("data-head");
        var note = element.getAttribute("");

        $(document).ready(function() {
            $('#view2').modal('show');
            document.getElementById('taskcode2').value = taskcode2;
            document.getElementById('taskname2').value = taskname2;
            document.getElementById('taskclass2').value = taskclass2;
            document.getElementById('tracer2').value = taskcode2;
            document.getElementById('remarks2').value = remarks2;
            document.getElementById('duedate2').value = duedate2;
            document.getElementById('datefinish2').value = datefinish2;
            document.getElementById('achievement2').value = achievement2;
            document.getElementById('filepath2').innerHTML = filepath2;
            document.getElementById('file2').value = file2;
            document.getElementById('filepath2').href = '../employee/attachment_download.php?filetrack=' + encodeURIComponent(filepath2);
            document.getElementById('head2').value = head;
            
            var targetDiv = $('#show');
                if (file2 == '1'){
                    tragetDiv.show();
                }
                else {
                    targetDiv.hide();
                }
        });
    }

    $(document).ready(function() {
    
        let table = $('#table_task');
        
        $('#table_task').DataTable({
            "order":[[4,"asc"]],
            responsive: true,
            lengthMenu: [[10,15,20,50],[10,15,20,50]],
            pageLength: 10

        });

    });
    
    function okButtonClick() {
        var taskcode = document.getElementById('tracer2').value;
        var note = document.getElementById('note2').value;
        var head = document.getElementById('head2').value;
        var score = document.getElementById('achievement2').value;
        var btn = document.getElementById('okButton');

        $.ajax({
            type: "POST",
            url: "pending_for_approval_submit.php",
            data: { id: taskcode, note: note, score: score, head: head }
        }).done(function(response) {
            document.getElementById('okButton').disabled = true;
            btn.textContent = 'Approving...'; // Change the button text to "Waiting"
            setTimeout(function() {
                $('#view2').modal('hide');
            }, 2000); // Adjust the delay time (in milliseconds) as needed
            setTimeout(function() {
                $('#success').modal('show');
            }, 2000); // Adjust the delay time (in milliseconds) as needed
        }).fail(function(xhr, status, error) {
            alert("An error occurred: " + status + "\nError: " + error);
        });
    }

    $(document).ready(function () {
        // When the submit button is clicked
        $("#submit-button").on("click", function () {
            var selectedValues = []; // Initialize an empty array
            var headname = '<?php echo $myname; ?>';

            // Loop through each checked checkbox
            $(".messageCheckbox:checked").each(function () {
                selectedValues.push($(this).val()); // Add the value to the array
            });

            // Log the selected values
            console.log("Selected values:", selectedValues);
            console.log("Head:", headname);

            // Send the selectedValues via AJAX (you'll need to implement this part)
            // Example AJAX request:
            $.ajax({
                url: "pending_for_approval_submit_array.php",
                method: "POST",
                data: { selectedValues: selectedValues, headname: headname },
                success: function (response) {
                    console.log("Data sent successfully:", response);
                    $('#caution').modal('hide');
                    $('#success').modal('show'); 
                },
                error: function (error) {
                    console.error("Error sending data:", error);
                }
            });
        });
    });
</script>

<div class="modal fade" id="view2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content panel-success" >
            <div class="modal-header panel-heading">
                <a href="pending_for_approval.php"><button type="button" class="close" aria-hidden="true">&times;</button></a>
                <h4 class="modal-title" id="myModalLabel">Task Details</h4>
                <hr>
            </div>
            <div class="modal-body panel-body">
                <form data-toggle="validator" class="className" name="form" id="form" action="attachment_download.php" method="POST">
                    <div class='form-group col-lg-3'>
                        <label>Task Code:</label>
                        <input type="text" class="form-control" name="taskcode2" id="taskcode2" disabled><br>
                        <input type="text" name="tracer2" id="tracer2" hidden>
                    </div>
                    <div class='form-group col-lg-3'>
                        <label>Due Date:</label>
                        <input type="date" class="form-control" name="duedate2" id="duedate2" disabled><br>
                    </div>
                    <div class='form-group col-lg-4'>
                        <label>Date Accomplished:</label>
                        <input type="datetime-local" class="form-control" name="datefinish2" id="datefinish2" disabled><br>
                    </div>
                    <div class='form-group col-lg-2'>    
                        <label>Initial Score: </label>
                        <input type="number" name="achievement2" id="achievement2" class="form-control" min="1" max="3" style="color: red"><br>
                    </div>
                    <div class='form-group col-lg-12'>
                        <label>Task Name:</label>
                        <input type="text" class="form-control" name="taskname2" id="taskname2" disabled><br>
                        <label>Task Classification:</label>
                        <input type="text" class="form-control" name="taskclass2" id="taskclass2" disabled><br>
                        <label>Task Remarks:</label>
                        <textarea class="form-control" name="remarks2" id="remarks2" readonly></textarea><br>
                        <div id="show">
                            <label>File Attachement:</label>
                            <input type="text" name="file2" id="file2" hidden><br>
                            <span style='color: #00ff26'><i class='fa fa-paperclip'></i></span>
                            <a href="#" id="filepath2"></a>
                        </div><br>
                        <label>Note:</label>
                        <input type="text" name="head2" id="head2" hidden>
                        <input type="text" class="form-control" name="note2" id="note2" placeholder="Write a note here if needed." style="color: red">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id='okButton' class='btn btn-success pull-left' onclick='okButtonClick()'><i class="fa fa-check-square"></i> Approve</button>
                <a href="pending_for_approval.php"><button id='close' class='btn btn-danger pull-right'><i class="fa fa-times"></i> Close</button></a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <a href="pending_for_approval.php"><button type="button" class="close" aria-hidden="true">&times;</button></a>
                <h4 class="modal-title" id="myModalLabel">Notice</h4>
            </div>
            <div class="modal-body panel-body">
                <center>
                    <i style="color:#e13232; font-size:80px;" class="fa fa-fa-check"></i>
                    <br><br>
                    <p>Task/s has been Reviewed.</p>
                </center>
            </div>
            <div class="modal-footer">
              <a href="pending_for_approval.php"><button type="button" name="submit" class="btn btn-success pull-right"><i class="fa fa-times-circle"></i> Close</button></a>
            </div>
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="caution" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <a href="pending_for_approval.php"><button type="button" class="close" aria-hidden="true">&times;</button></a>
                <h4 class="modal-title" id="myModalLabel">Caution</h4>
            </div>
            <div class="modal-body panel-body">
                <center>
                    <i style="color: yellow; font-size:80px;" class="fa fa-exclamation-triangle"></i>
                    <br><br>
                    <p>You're about to approve the marked tasks. <br> Do you want to continue?</p>
                </center>
            </div>
            <div class="modal-footer">
                <button type="button" id='submit-button' class="btn btn-success pull-left"><i class="fa fa-check-circle"></i> Yes</button>
                <a href="pending_for_approval.php"><button type="button" name="submit" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> No</button></a>
            </div>
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>
</html>