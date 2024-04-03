<?php
include('auth.php');
include('connect.php');
include('link.php');


$username = $_GET['username'];
$old_pass = $_POST['old_pass'];
$new_pass = $_POST['new_pass'];
$retype_pass = $_POST['retype_pass'];


if($old_pass == $pass){
    if($new_pass == $retype_pass){
        $sql = mysqli_query($con,"UPDATE accounts SET password='$new_pass' where username='$username'");
    } else{ ?>
        <script>
            $(document).ready(function(){
                $("#errorpass").modal("show");            
            });
        </script>
        <div class="modal fade" id="errorpass" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content panel-danger">
                    <div class="modal-header panel-heading">
                        <a href="javascript:history.back()"><button type="button" class="close"  aria-hidden="true">&times;</button></a>
                        <h4 class="modal-title" id="myModalLabel">Warning!</h4>
                    </div>
                    <div class="modal-body panel-body">
                    <center>
                        <i style="color:#e13232; font-size:80px;" class="fas fa-times-circle "></i>
                        <br><br>
                        New Password did not match!
                    </center>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:history.back()"><button type="button" name="submit" class="btn btn-danger pull-right">Okay</button></a>
                    </div>
                </div>
            </div>
        </div>
        <?php
        exit();
    }
}else { ?>
    <script>
        $(document).ready(function(){
            $("#errorpass1").modal("show");            
        });
    </script>
    <div class="modal fade" id="errorpass1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content panel-danger">
                <div class="modal-header panel-heading">
                    <a href="javascript:history.back()"><button type="button" class="close"  aria-hidden="true">&times;</button></a>
                    <h4 class="modal-title" id="myModalLabel">Warning!</h4>
                </div>
                <div class="modal-body panel-body">
                <center>
                    <i style="color:#e13232; font-size:80px;" class="fas fa-times-circle "></i>
                    <br><br>
                    Old Password did not match!
                    </center>
                </div>
                <div class="modal-footer">
                    <a href="javascript:history.back()"><button type="button" name="submit" class="btn btn-danger pull-right">Okay</button></a>
                </div>
            </div>
        </div>
    </div>
    <?php
    exit();
}

if ($sql){ ?>
    <script>
        $(document).ready(function(){
            $("#sucess").modal("show");            
        });
    </script>
    <div class="modal fade" id="sucess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content panel-success">
                <div class="modal-header panel-heading">
                    <a href="javascript:history.back()"><button type="button" class="close"  aria-hidden="true">&times;</button></a>
                    <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                </div>
                <div class="modal-body panel-body">
                    <center>
                    <i style="color:#3c763d; font-size:80px;" class="fas fa-check-circle "></i>
                    <br><br>
                    Update Successfully!
                    </center>
                </div>
                <div class="modal-footer">
                    <a href="user_profile.php"><button type="submit" name="submit" class="btn btn-success pull-right">Okay</button></a>
                </div>
            </div>
        </div>
    </div>
    <?php
    exit();
}else { ?>
    <script>
        $(document).ready(function(){
            $("#error").modal("show");                
        });
    </script>
    <div class="modal fade" id="error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content panel-danger">
                <div class="modal-header panel-heading">
                    <a href="javascript:history.back()"><button type="button" class="close"  aria-hidden="true">&times;</button></a>
                    <h4 class="modal-title" id="myModalLabel">Warning!</h4>
                </div>
                <div class="modal-body panel-body">
                    <center>
                    <i style="color:#e13232; font-size:80px;" class="fas fa-times-circle "></i>
                    </button> <br><br>
                    Error in updating profile.
                    </center>
                </div>
                <div class="modal-footer">
                    <a href="javascript:history.back()"><button type="submit" name="submit" class="btn btn-danger pull-right">Okay</button></a>
                </div>
            </div>
        </div>
    </div>
    <?php
    exit();
} ?>