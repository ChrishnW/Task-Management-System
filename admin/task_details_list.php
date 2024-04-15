<?php 
	include('../include/header.php');
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
                    <h1 class="page-header">Task Details</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Active Sections</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table width="100%" class="table table-striped table-hover" id="table">
                                    <thead>
                                        <tr>
                                            <th class="col-xs-1"> <center /> Section ID </th>
                                            <th class="col-lg-2"> <center /> Section </th>
                                            <th class="col-xs-1"> <center /> List </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                      <?php
                                        $get_section_task_list = mysqli_query($con,"SELECT * FROM section WHERE status = 1 ORDER BY sec_name ASC");
                                        if (mysqli_num_rows($get_section_task_list)>0) { 
                                          while ($row = $get_section_task_list->fetch_assoc()) {
                                          $seclist = $row['sec_id'];
                                          $secname = $row['sec_name'];
                                          echo "<tr><td> $seclist </td>
                                          <td> $secname </td>
                                          <td> <a href='task_details.php?section=$seclist'>View</a> </td> </tr>";
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