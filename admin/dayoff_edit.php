<?php
include('../include/header.php');

$id=$_GET['id'];
$sql = mysqli_query($con,"SELECT DISTINCT id,date_off,status FROM `day_off` WHERE id='$id'");
$row = mysqli_fetch_array($sql);
{
    $id=$row['id'];
    $date_off=$row['date_off'];
}
    $from=date('Y-m-0');
    $to=date("Y-m-t",strtotime("+12 Month"));
 ?>
<div id="content" class="p-4 p-md-5 pt-5">
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Modify Day off</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                   <strong>Modify Day off</strong>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" data-toggle="validator" action="dayoff_update.php" enctype="multipart/form-data" method="post">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="hidden" name="option" value="edit">
                    <div class="form-group">
                            <label class="control-label col-sm-3">Selected Day off: <font color="red">*</font></label>
                            <div class="col-sm-9">
                                <select class="form-control" id="setdate"  name="oldsetdate" data-live-search="true" data-max-options="1" disabled>
                                    <option value="<?php echo  date('Y-m-d', strtotime($date_off)); ?>" >
                                    <?php echo date("l jS \of F Y ",strtotime($date_off)); ?></option> 
                                </select >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-3">Select Date to modify: <font color="red">*</font></label>
                            <div class="col-sm-9">  
                                <select class="selectpicker show-menu-arrow  form-control" name="setdate" data-live-search="true" data-max-options="1" required>
                                    <option value="" selected disabled>Please select date</option>
                                   <?php
                                    $start = strtotime($from);
                                    $end = strtotime($to);
                                    $count = 0;
                                    while(date('Y-m-d', $start) < date('Y-m-d', $end)){
                                      $count += date('N', $start) < 6 ? 1 : 0;
                                      $start = strtotime("+1 day", $start);
                                    }
                                    $i=$count;
                                    for ($x = 0; $x <= $i; $x++){ 
                                        if($from<=$to){  ?>
                                        <option value="<?php echo $from = date('Y-m-d', strtotime($from.' +1 day')); ?>"><?php
                                        echo date("l jS \of F Y ",strtotime($from));   }
                                    } ?></option> 
                                </select >
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <button type="button" id="submitBtn"class="btn btn-success pull-right" data-toggle="modal" data-target="#submitModal">
                                <span class="fa fa-check"> </span> Submit</button>
                                <button type="button" class="btn btn-danger pull-right" data-toggle="modal" data-target="#cancelModal">
                                <span class="fa fa-times"> </span> Cancel</button>
                            </div>
                        </div>

                        <div class="modal fade" id="submitModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"  aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content panel-success">
                                    <div class="modal-header panel-heading">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                                    </div>
                                    <div class="modal-body panel-body">
                                    <center>
                                        <i style="color:#3581C1; font-size:80px;" class="fa fa-question-circle  "></i>
                                        <br><br>
                                        Are you sure you want to <strong>Update</strong> the set Date?
                                    </center>
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" id="submit-button-no" data-dismiss="modal"><span class="fa fa-times"> </span> No</button>
                                    <button type="submit" id="submit-button" class="btn btn-success" ><span class="fa fa-check"></span> Yes</button>
                                    <input type="hidden" name="submit">
                                    </div>
                                </div>         <!-- /.modal-content -->
                            </div>      <!-- /.modal-dialog -->
                        </div>            
                        <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"  data-backdrop="static" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content panel-success">
                                    <div class="modal-header panel-heading">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                                    </div>
                                    <div class="modal-body panel-body">
                                    <center>
                                        <i style="color:#3581C1; font-size:80px;" class="fa fa-question-circle  "></i>
                                        <br><br>
                                        Are you sure you want to  <strong>Cancel</strong> the set Date?
                                    </center>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="fa fa-times"> </span> No</button>
                                        <a href="javascript:history.back()"><button type="button" class="btn btn-success" ><span class="fa fa-check"> </span> Yes</button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <a href="#" onclick="history.back()"> <button class='btn btn-danger pull-left'><i class="fa fa-arrow-left"></i> Return to Day off</button></a>
        </div>
    </div>
</div>
</div>
</div>
</body>
</html>