<?php 

include('../include/header.php');

$id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.'); 
$result = mysqli_query($con,"SELECT accounts.fname, accounts.lname, accounts.username, accounts.email, section.sec_name, access.access, accounts.status, accounts.sec_id, accounts.id FROM accounts LEFT JOIN section ON accounts.sec_id=section.sec_id LEFT JOIN access on accounts.access=access.id WHERE accounts.id=$id");       
$row= mysqli_fetch_assoc($result);

$username = $row['username'];
$fname = $row['fname'];
$lname = $row['lname'];
$email = $row['email'];
$sec_id = $row['sec_id'];
$sec_name = $row['sec_name'];
$access = $row['access'];
$access_id = $row['access'];
$status = $row['status'];
$id = $row['id'];
?>

<html>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
<style>
.form-group.required label {
    font-weight: bold;
}

.form-group.required label:after {
    color: #e32;
    content: ' *';
    display: inline;
}
</style>

<head>
    <title>Edit Account Information</title>
</head>

<body>
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Edit Account Information</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Edit Account Information Form
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">

                                    <form class="className" name="form" id="form" action="account_edit_submit.php"
                                        method="POST">
                                        <div class="form-group">

                                            <div data-toggle="validator" class="form-group required">
                                                <label>User Name:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="Enter User Name" class="form-control"
                                                    name="username" id="username" pattern="[a-zA-Z0-9-/ ]+"
                                                    data-error="Invalid input!" value="<?php echo $username; ?>" readonly>
                                                <input type="hidden" class="form-control" name="id" id="id"
                                                    value="<?php echo $id; ?>" required>
                                            </div>

                                            <div data-toggle="validator" class="form-group required">
                                                <label>First Name:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="Enter First Name" class="form-control"
                                                    name="fname" id="fname" pattern="[a-zA-Z0-9-/ ]+"
                                                    data-error="Invalid input!" value="<?php echo $fname; ?>">
                                            </div>

                                            <div data-toggle="validator" class="form-group required">
                                                <label>Last Name:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="Enter Last Name" class="form-control"
                                                    name="lname" id="lname" pattern="[a-zA-Z0-9-/ ]+"
                                                    data-error="Invalid input!" value="<?php echo $lname; ?>">
                                            </div>

                                            <div data-toggle="validator" class="form-group required">
                                                <label>E-mail:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <input type="text" placeholder="Enter E-mail" class="form-control"
                                                    name="email" id="email" value="<?php echo $email; ?>">
                                            </div>

                                            <div data-toggle="validator" class="form-group required">
                                                <label>Access:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <select name="access" id="access" class="form-control selectpicker show-menu-arrow" data-live-search="true">
                                                <option selected value="<?php echo $access_id; ?>"><?php echo strtoupper($access); ?></option>
                                                <?php
                                                        $sql = mysqli_query($con,"SELECT * FROM access WHERE access!='$access' "); 
                                                        $con->next_result();
                                                        if(mysqli_num_rows($sql)>0){
                                                            while($row=mysqli_fetch_assoc($sql)){
                                                                $access_id1 = $row['id'];
                                                                $access1 = $row['access'];
                                                                echo "<option value='".$access_id1."'>".strtoupper($access1)."</option>";
                                                            }
                                                        } ?>
                                                </select>
                                            </div>

                                            <div data-toggle="validator" class="form-group required">
                                                <label>Section:</label><span
                                                    class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <select name="section" id="section" class="form-control selectpicker show-menu-arrow" data-live-search="true">
                                                <option selected value="<?php echo $sec_id; ?>"><?php echo $sec_name; ?></option>
                                                <?php
                                                        $sql = mysqli_query($con,"SELECT * FROM section WHERE status='1' "); 
                                                        $con->next_result();
                                                        if(mysqli_num_rows($sql)>0){
                                                            while($row=mysqli_fetch_assoc($sql)){
                                                                $sec_id1 = $row['sec_id'];
                                                                $sec_name1 = $row['sec_name'];
                                                                echo "<option value='".$sec_id1."'>".$sec_name1."</option>";
                                                            }
                                                        } ?>
                                                </select>
                                            </div>

                                            <div class="form-group required">
                                                <label>Status:</label><span class="pull-right help-block with-errors"
                                                    style="margin: 0px; font-size: 11px;"></span>
                                                <select name="status" id="status" class="form-control">
                                                    <?php
                                                        if ($status=="1") {
                                                            echo "<option selected value='1'>".'ACTIVE'."</option>
                                                            <option value='0'>".'INACTIVE'."</option>" ;}
                                                        if ($status=="0") {
                                                            echo "<option selected value='0'>".'INACTIVE'."</option>
                                                            <option value='1'>".'ACTIVE'."</option>" ;}
                                                        else {
                                                            echo "Unknown Status";  } ?>
                                                </select>
                                            </div>

                                            <br>
                                                <i data="<?php echo $id?>" class='button btn btn-primary '><i class="fa fa-undo"></i> Reset Password</i>
                                            <br>
                                            <br>

                                            <div class="form-group">
                                                <div class="col-sm-12 pull-right">
                                                    <button id="submit" type="submit" class="btn btn-success pull-right">
                                                        <span class="fa  fa-check"></span> Submit</button>
                                                    <a href="account_list.php">
                                                        <button type="button" class="btn btn btn-danger"> <span class="fa fa-times">
                                                        </span> Cancel</button></a>
                                                </div>
                                            </div>

                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
//reset password set to default = 12345
$(document).on('click', '.button', function() {
    if (confirm("Are you sure you want to reset password?")) {
        var current_element = $(this);
        $.ajax({
            type: "POST",
            url: "account_reset_pass.php",
            data: {
                id: $(current_element).attr('data')
            },
            success: function(data) {
                //row.closest('tr').remove();
                alert("Password set to Default: 12345");
                //location.reload();
            }
        });
    }
});
</script>

</html>