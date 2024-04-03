<?php
include('../include/header.php');

@$from=$_GET['from'];
@$to=$_GET['to'];  $from = date('Y-m-d', strtotime($from.' -1 days'));?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Add Day off
            <a href="javascript:history.back()"><button type="button" class="btn btn-danger pull-right"><span class="fas fa-reply"> </span> Back</button></a></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <form class="form-inline" data-toggle="validator" action="" enctype="multipart/form-data" method="GET">
            <div class="form-group">
                <label>From: <font color="red">*</font></label>
                <input type="date" name="from" class="form-control" value="<?php echo $_GET['from']; ?>" required>
            </div>
            <div class="form-group">
                <label>To: <font color="red">*</font></label>
                <input type="date" name="to" class="form-control" value="<?php echo $_GET['to']; ?>" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><span class="fas fa-check"> </span> Submit</button>
            </div>
            </form>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-10">
            <div class="panel panel-primary">
                <div class="panel-heading">
                   <strong>Add Day off</strong>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" data-toggle="validator" action="dayoff_add_submit.php" enctype="multipart/form-data" method="post">
                        <div class="form-group">
                            <label class="control-label col-sm-3">Select Day off: <font color="red">*</font></label>
                            <div class="col-sm-9">  
                                <select class="selectpicker show-menu-arrow  form-control" id="setdate"  name="setdate[]" data-live-search="true" multiple required>
                                   <?php
                                    $start = strtotime($from);
                                    $end = strtotime($to);
                                    $count = 0;
                                    while(date('Y-m-d', $start) < date('Y-m-d', $end)){
                                      $count += date('N', $start) < 8 ? 1 : 0;
                                      $start = strtotime("+1 day", $start);
                                    }
                                    $i=$count;
                                    for ($x = 1; $x <= $i; $x++){ 
                                        if($from<=$to){  ?>
                                        <option value="<?php echo $from = date('Y-m-d', strtotime($from.' +1 days')); ?>"><?php
                                        echo date("l jS \of F Y ",strtotime($from));   }
                                    } ?></option> 
                                </select >
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <button type="button" id="submitBtn"class="btn btn-success pull-right" data-toggle="modal" data-target="#submitModal">
                                <span class="fas fa-check"> </span> Submit</button>
                                <button type="button" class="btn btn-danger pull-right" data-toggle="modal" data-target="#cancelModal">
                                <span class="fas fa-times"> </span> Cancel</button>
                            </div>
                        </div>

                        <div class="modal fade" id="submitModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"  aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content panel-info">
                                    <div class="modal-header panel-heading">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                                    </div>
                                    <div class="modal-body panel-body">
                                    <center>
                                        <i style="color:#3581C1; font-size:80px;" class="fa fa-question-circle  "></i>
                                        <br><br>
                                        Are you sure you want to <strong>Submit</strong> the set Date?
                                    </center>
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" id="submit-button-no" data-dismiss="modal"><span class="fas fa-times"> </span> No</button>
                                    <button type="submit" id="submit-button" class="btn btn-success" ><span class="fas fa-check"> </span> Yes</button>
                                    <input type="hidden" name="submit">
                                    </div>
                                </div>         <!-- /.modal-content -->
                            </div>      <!-- /.modal-dialog -->
                        </div>            
                        <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"  data-backdrop="static" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content panel-info">
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
                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="fas fa-times"> </span> No</button>
                                        <a href="javascript:history.back()"><button type="button" class="btn btn-success" ><span class="fas fa-check"> </span> Yes</button></a>
                                    </div>
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
</body>
</html>