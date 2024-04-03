<div class="modal fade" id="exists" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content panel-danger">
            <div class="modal-header panel-heading">
                <a href="account_add.php"><button type="button" class="close"
                        aria-hidden="true">&times;</button></a>
                <h4 class="modal-title" id="myModalLabel">Warning!</h4>
            </div>
            <div class="modal-body panel-body">
                <center>
                    <i style="color:#e13232; font-size:80px;" class="fa fa-times-circle "></i>
                    <br><br>
                    Account already exists!
                </center>
            </div>
            <div class="modal-footer">
                <a href="account_add.php"><button type="button" name="submit"
                        class="btn btn-danger pull-right">Return</button></a>
            </div>
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>

<?php
include('../include/link.php');
include('../include/connect.php');

$username = $_POST['username'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$access = $_POST['access'];
$section = $_POST['section'];

$con->next_result();
$check=mysqli_query($con,"SELECT * FROM accounts WHERE username='$username'");
$checkrows=mysqli_num_rows($check);

    
if($checkrows>0) {
    echo "<script type='text/javascript'>   $(document).ready(function(){ $('#exists').modal('show');   });</script>";         
    exit;
}
else {
    $con->next_result();   
    $query = "INSERT INTO accounts (username,password,fname,lname,email,access,sec_id,status)
        VALUES(
        (UPPER('$username')),
        (('12345')),
        (UPPER('$fname')),
        (UPPER('$lname')),
        (LOWER('$email')),
        (('2')),
        (UPPER('$section')),
        (('1'))
        )";
        $result = mysqli_query($con, $query) or die('Error querying database.');                        
    header('location: account_list.php'); 
}
?>