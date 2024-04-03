<?php 
include('../include/header.php');
include('../include/connect.php');
include('../include/bubbles.php');
$sql = mysqli_query($con,"SELECT DISTINCT date_off,id FROM `day_off` WHERE status=true");  ?>


<html>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
<link href="../assets/css/darkmode.css" rel="stylesheet">

<head>
<title>Dayoff Calendar</title>
</head>

<div id="content" class="p-4 p-md-5 pt-5">
    <div id="wrapper">
        <div id="page-wrapper">

            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Table of Holiday and Saturday off
                    <a href="dayoff_add.php"> <button class="btn btn-success pull-right" style="margin-top: 10px;"><span class="fa fa-plus-circle fa-fw"></span> Add Record</button></a>
                    </h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><strong>Table of Holiday and Saturday off </strong>
                        </div>

                        <div class="panel-body">
                            <div class="table-responsive">
                                <table width="100%" class="table table-striped table-hover "
                                    id="table">
                                    <thead>
                                        <tr>
                                            <th width="80%">Set Date</th>
                                            <th  width="20%">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody id="show_account">
                                      <tr>
                                        <?php
                                        while($row = mysqli_fetch_array($sql)) {  ?>
                                        <td><?php echo date("l jS \of F Y ",strtotime($row['date_off']));  ?></td>
                                        <td>
                                            <a href="dayoff_edit.php?id=<?php echo $row['id']; ?>" ><button type="button" class="btn btn-primary btn-xs"><span class="fa fa-edit"> </span> Update</button></a>
                                            <a href="dayoff_update.php?id=<?php echo $row['id']; ?>&option=delete&setdate=<?php echo $row['date_off']; ?>" ><button type="button" class="btn btn-danger btn-xs"><span class="fa fa-trash"> </span> Delete</button></a>
                                        </td>
                                      </tr>
                                    <?php } ?>
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
$(document).ready(function() {
    $('#table').DataTable({
        responsive: true,
        destroy: true,
        "order": [[ 3, "desc" ]]
    });
});
</script>
</html>