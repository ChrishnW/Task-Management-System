<?php
include('../include/auth.php');
include('../include/connect.php');
include('../include/link.php');

if($access!='1')
{ ?>
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
                <a href="../include/logout.php"><button type="submit" name="submit" class="btn btn-danger pull-right">Okay</button></a>
                </div>
            </div>          <!-- /.modal-content -->
        </div>         <!-- /.modal-dialog -->
    </div>
    <?php
    exit();
}
elseif($access=='1')
{
    if(isset($_POST['submit'])){

        foreach ($_POST['setdate'] as $setdate) {

            $days = date_format(date_create($setdate), ' N ');
            if($days==7){ ?>
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
                                    Sorry, Date is INVALID!
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

            $sqlcheckdate = mysqli_query($con,"SELECT * FROM `day_off` WHERE date_off='$setdate' and status=true");
            if(mysqli_num_rows($sqlcheckdate) > 0) { ?>
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
                                    Sorry, Date is already set as Holiday!
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
            }else{
                $con->next_result();
                $sql = mysqli_query($con,"INSERT INTO day_off (date_off,status) value ('$setdate',true)");
            }
        }

        if ($sql){
            header('location:dayoff.php');
        }
        else        
        { ?>
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
                                Sorry, You cant Submit the set Date!
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
        
    }
} ?>