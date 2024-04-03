<?php
include('../include/auth.php');
include('../include/connect.php');
include('../include/link.php');

date_default_timezone_set('Asia/Taipei');
$date = date('Y-m-d');
$time = date('H:i:s');

if($access!='2'){ ?>
    <script>
        $(document).ready(function(){
            // Show the Modal on load
            $("#error1").modal("show");            
        });
    </script>
    <div class="modal fade" id="error1" tabindex="-1" role="dialog"  data-backdrop="static" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content panel-danger">
                <div class="modal-header panel-heading">
                    <button type="button" class="close"  aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Warning!</h4>
                </div>
                <div class="modal-body panel-body">
                <center>
                    <i style="color:#e13232; font-size:80px;" class="fa fa-exclamation-circle "></i>
                    <br><br>
                    You are not authorized to access this page. Please Login your account!
                </center>
                </div>
                <div class="modal-footer">
                    <a href="../logout.php"><button type="submit" name="submit" class="btn btn-danger pull-right">Okay</button></a>
                </div>
            </div>
        </div>
    </div>
    <?php
    exit();
}
elseif($access=='2')
{
    if(isset($_POST['submit'])){

        if($_POST['option']=='edit'){

            $id=$_POST['id'];
            $setdate=$_POST['setdate'];

            $sqlcheck = mysqli_query($con,"SELECT * FROM `day_off` WHERE date_off='$setdate' and status=true");
            if(mysqli_num_rows($sqlcheck) > 0){ ?>
                <script>
                    $(document).ready(function(){
                        $("#error").modal("show");
                    });
                </script>
                <div class="modal fade" id="error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"  aria-hidden="true">
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
                                    Sorry, Modify is not allowed. Date already exist.
                                </center>
                            </div>
                            <div class="modal-footer">
                                <a href="javascript:history.back()"><button type="submit" name="submit" class="btn btn-danger pull-right">Back</button></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                exit();
            }
            
            $con->next_result();
            $sql = mysqli_query($con,"UPDATE `day_off` SET date_off='$setdate',status=TRUE WHERE id='$id'");
        }

    }elseif($_GET['option']=='delete'){
            
        $id=$_GET['id'];
        $setdate=$_GET['setdate'];

        $sql = mysqli_query($con,"UPDATE `day_off` SET date_off='$setdate',status=FLASE WHERE id='$id'");
    }
    
    if ($sql){ ?>
        <script>
            $(document).ready(function(){
                $("#success").modal("show");
            });
        </script>
        <div class="modal fade" id="success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"  aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content panel-success">
                    <div class="modal-header panel-heading">
                        <a href="dayoff.php"><button type="button" class="close"  aria-hidden="true">&times;</button></a>
                        <h4 class="modal-title" id="myModalLabel">Warning!</h4>
                    </div>
                    <div class="modal-body panel-body">
                        <center>
                            <i style="color:#3c763d; font-size:80px;" class="fas fa-check-circle "></i>
                            <br><br>
                            Success!
                        </center>
                    </div>
                    <div class="modal-footer">
                        <a href="dayoff.php"><button type="button" name="" class="btn btn-success pull-right">Okay</button></a> 
                    </div>
                </div>
            </div>
        </div>
        <?php 
        exit();
        header('location:dayoff.php');
    }
    else{ ?>
        <script>
            $(document).ready(function(){
                // Show the Modal on load
                $("#error").modal("show");
            });
        </script>
        <div class="modal fade" id="error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"  aria-hidden="true">
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
                            Sorry, You cant Submit the Set date! Error found!
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
    }
} ?>