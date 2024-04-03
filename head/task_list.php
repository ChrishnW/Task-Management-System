<?php 
include('../include/header_head.php');
include('../include/connect.php');

$task_sec = $_GET['section'];
?>
<html>
<head>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
<link href="../assets/css/darkmode.css" rel="stylesheet">

    <title>List of Tasks</title>
</head>
<?php
    $section_name = mysqli_query($con, "SELECT * FROM section WHERE sec_id = '$task_sec'");
    $section_name_query = mysqli_fetch_assoc($section_name); // Corrected line
    $name = $section_name_query['sec_name'];
?>
<div id="content" class="p-4 p-md-5 pt-5">
    <div id="wrapper">
        <div id="page-wrapper">
        <br>
        <h1 class="page-header"><?php echo $name ?> ACTIVE TASKS
        </h1>
            <!-- <div class="row">
                <div class="col-lg-2">
                <label>Status:</label><br>
                    <select name="show_status" id="show_status" class="form-control selectpicker show-menu-arrow" placeholder="" onchange="selectmodel(this)">
                        <option disabled selected value="">--Sort by Status--</option>
                        <option selected value="1">ACTIVE</option>
                        <option value="0">INACTIVE</option>
                    </select>
                    <br>
                    <br>
                </div>
            </div> -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            List of Tasks
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                            <a href="task_xls.php?section=<?php echo $task_sec?>"> <button class="btn btn-success pull-right"><span class="fa fa-download"></span> Download List</button></a>
                            <br><br><br>
                                <table width="100%" class="table table-striped table-hover " id="table_account">
                                    <thead>
                                        <tr>
                                            <th class="col">
                                                <center />#
                                            </th>
                                            <th class="col">
                                                <center />Task Name
                                            </th>
                                            <th class="col">
                                                <center />Task Details
                                            </th>
                                            <th class="col">
                                                <center />Task Classification
                                            </th>
                                            <th class="col">
                                                <center />Date Registered
                                            </th>
                                            <th class="col">
                                                <center />Status
                                            </th>
                                            <!-- <th class="col-lg-1">
                                                <center />Action
                                            </th> -->
                                        </tr>
                                    </thead>

                                    <tbody id="show_account">
                                        <?php
                                            /* and access!='1' */
                                            $con->next_result();
                                            $result = mysqli_query($con,"SELECT task_list.id, task_list.task_name, task_list.task_details, task_class.task_class, section.sec_name, task_list.date_created, task_list.status FROM task_list LEFT JOIN task_class ON task_class.id = task_list.task_class LEFT JOIN section ON section.sec_id = task_list.task_for WHERE task_list.status = '1' AND task_list.task_for = '$task_sec'");               
                                            if (mysqli_num_rows($result)>0) { 
                                                $number = 0;
                                                while ($row = $result->fetch_assoc()) {
                                                    $date = date('l, F d, Y', strtotime($row["date_created"]));
                                                    $number += 1;
                                                    echo "<tr>
                                                        <td> " . $number . " </td>
                                                        <td id='normalwrap'> " . $row["task_name"] . " </td>   
                                                        <td id='normalwrap'> " . $row['task_details'] . " </td>
                                                        <td>" . $row["task_class"] . "</td> 
                                                        <td>" . $date . "</td> 
                                                        <td><center/>" .($row['status']=='1' ? '<p class="label label-success" style="font-size:100%;">ACTIVE</p>' : '<p class="label label-danger" style="font-size:100%;">INACTIVE</p>' ). "</td>
                                                        </td>
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
                    <a href="#" onclick="history.back()"> <button class='btn btn-danger pull-left'><i class="fa fa-arrow-left"></i> Return to Tasks Masterlist</button></a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#table_account').DataTable({
        responsive: true,
        'order': [[ 0, 'asc' ]]
    });
});
</script>

<script>
function selectmodel(element) {
    let sid = $(element).val();
    let section = <?php echo json_encode($task_sec) ?>;
    $('#table_account').DataTable().destroy();
    $('#show_account').empty();
    if (sid) {
        $.ajax({
            type: "post",
            url: "task_ajax.php",
            data: {
                "sid": sid,
                "section": section
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