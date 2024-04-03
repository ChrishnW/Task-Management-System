<?php 
include('../include/header.php');
include('../include/connect.php');
include('../include/bubbles.php');
?>
<html>
<head>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
<link href="../assets/css/darkmode.css" rel="stylesheet">

    <title>List of Registered Accounts</title>
</head>
<style> 
</style>
<body>
    <div id="wrapper">
        <div id="page-wrapper">
        <br>
        <h1 class="page-header">List of Registered Accounts
        <a href="account_xls.php"> <button class="btn btn-success pull-right"><span class="fa fa-download"></span> Download</button></a></h1>
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
                            List of Registered Accounts
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                            <a href="account_add.php"> <button class='btn btn-success  pull-right'><i class="fa fa-plus"></i> Add</button></a><br><br><br>
                                <table width="100%" class="table table-striped table-bordered table-hover "
                                    id="table_account">

                                    <thead>
                                        <tr>
                                            <th class="col-lg-2">
                                                <center />Name
                                            </th>
                                            <th class="col-lg-2">
                                                <center />User Name
                                            </th>
                                            <th class="col-lg-2">
                                                <center />E-mail
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Section
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Access
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
                                        $result = mysqli_query($con,"SELECT accounts.fname, accounts.lname, accounts.username, accounts.email, section.sec_name, access.access, accounts.status, accounts.id FROM accounts LEFT JOIN section ON accounts.sec_id=section.sec_id LEFT JOIN access on accounts.access=access.id WHERE accounts.status='1'");               
                                        if (mysqli_num_rows($result)>0) { 
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>                                                       
                                                    <td> " . $row["fname"] . " ". $row["lname"] . "</td>   
                                                    <td>" . $row["username"] . "</td> 
                                                    <td>" . $row["email"] . "</td> 
                                                    <td>" . $row["sec_name"] . "</td> 
                                                    <td>" . strtoupper($row["access"]) . "</td>
                                                    <td><center/>" .($row['status']=='1' ? '<p class="label label-success" style="font-size:100%;">Active</p>' : '<p class="label label-danger" style="font-size:100%;">Inactive</p>' ). "</td>
                                                    <td> <center /><a href='account_edit.php?id=".$row['id']."' <button class='btn btn-primary' ><i class='fa fa-edit fa-1x'></i> Edit</button></a>
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
                    <a href="./index.php"> <button class='btn btn-danger pull-left' id="back"><i class="fa fa-arrow-left"></i> Return to Dashboard</button></a>
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
            url: "account_ajax.php",
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