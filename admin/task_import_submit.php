


<?php
date_default_timezone_set('Asia/Manila');

include('../include/link.php');
include('../include/connect.php');

require ('../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if(isset($_POST['save_excel_data']))
{
    $fileName = $_FILES['import_file']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
    $allowed_ext = ['xls','csv','xlsx'];

    if(in_array($file_ext, $allowed_ext))
    {
        $inputFileNamePath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();
        $count_data = count($data)-1;

        $count = "0";
        foreach($data as $row)
        {
            if($count > 0)
            {
                $task_name = $row['0'];
                $task_class = $row['1'];
                $task_for = $row['2'];
                $in_charge = $row['3'];
                $due_date = $row['4'];
                $date_created = date('Y-m-d');
                $status = "NOT YET STARTED";
                  // Insert Data to Task List
                  $pdo = new PDO( "mysql:host=localhost;dbname=gtms", "gtms", "p@55w0rd$$$" );
                  $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); 
                  // Get the latest task code for the specific task class and task for
                  $sql = "SELECT MAX(task_code) AS latest_task_code FROM task_list WHERE task_class = '$task_class' AND task_for = '$task_for'";
                  $stmt = $pdo->prepare($sql);
                  $stmt->bindParam(':task_class', $task_class);
                  $stmt->execute();
                  $row = $stmt->fetch(PDO::FETCH_ASSOC);
              
                  // Parse the task class and generate prefix
                  $prefix = '';
                  if ($task_class == '1') {
                      $prefix = 'TD';
                  } elseif ($task_class == '2') {
                    $prefix = 'TW';
                  } elseif ($task_class == '3') {
                    $prefix = 'TM';
                  } elseif ($task_class == '4') {
                      $prefix = 'TA';
                  } elseif ($task_class == '5') {
                      $prefix = 'TP';
                  }

                  $numeric_portion = intval(substr($row['latest_task_code'], -6)) + 1;
                  $task_code = $task_for.'-'.$prefix . '-' . str_pad($numeric_portion, 6, '0', STR_PAD_LEFT);
              
                  $sql = "INSERT INTO task_list (task_code, task_name, task_class, task_for, status) VALUES (:task_code, :task_name, :task_class, :task_for, 1)";
                  $stmt = $pdo->prepare($sql);
                  $stmt->bindParam(':task_code', $task_code);
                  $stmt->bindParam(':task_name', $task_name);
                  $stmt->bindParam(':task_class', $task_class);
                  $stmt->bindParam(':task_for', $task_for);
                  $stmt->execute();

                  $check1=mysqli_query($con,"SELECT * FROM tasks WHERE task_code='$task_code' AND in_charge='$in_charge'");
                  $checkrows1=mysqli_num_rows($check1);

                  if($checkrows1<=0) {
                    // Insert Data to Tasks
                    $pdo = new PDO( "mysql:host=localhost;dbname=gtms", "gtms", "p@55w0rd$$$" );
                    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                    $insert_tasks = "INSERT INTO tasks (task_code, in_charge) VALUES (:task_code, :in_charge)";
                    $stmt = $pdo->prepare($insert_tasks);
                    $stmt->bindParam(':task_code', $task_code);
                    $stmt->bindParam(':in_charge', $in_charge);
                    $stmt->execute();
                  }

                  $pdo = new PDO( "mysql:host=localhost;dbname=gtms", "gtms", "p@55w0rd$$$" );
                  $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); 

                  $sql = "INSERT INTO tasks_details (task_code, date_created, due_date, in_charge, status, task_status, approval_status, reschedule) VALUES (:task_code, :date_created, :due_date, :in_charge, :status, 1, 1, 0)";
                  $stmt = $pdo->prepare($sql);
                  $stmt->bindParam(':task_code', $task_code);
                  $stmt->bindParam(':date_created', $date_created);
                  $stmt->bindParam(':due_date', $due_date);
                  $stmt->bindParam(':in_charge', $in_charge);
                  $stmt->bindParam(':status', $status);
                  $stmt->execute();
        }
            else {
                $count = '1';
            }
      }
      echo "<script type='text/javascript'>   $(document).ready(function(){ $('#success').modal('show');   });</script>";
    } else {
        echo "<script type='text/javascript'>   $(document).ready(function(){ $('#error2').modal('show');   });</script>";
    }
}


?>

<div class="modal fade" id="exists" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content panel-danger">
            <div class="modal-header panel-heading">
                <a href="task_add.php"><button type="button" class="close"
                        aria-hidden="true">&times;</button></a>
                <h4 class="modal-title" id="myModalLabel">Warning!</h4>
            </div>
            <div class="modal-body panel-body">
                <center>
                    <i style="color:#e13232; font-size:80px;" class="fa fa-times-circle "></i>
                    <br><br>
                    Task already exists!
                </center>
            </div>
            <div class="modal-footer">
                <a href="task_add.php"><button type="button" name="submit"
                        class="btn btn-danger pull-right">Return</button></a>
            </div>
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <a href="task_import.php"><button type="button" class="close"
                        aria-hidden="true">&times;</button></a>
                <h4 class="modal-title" id="myModalLabel">Success!</h4>
            </div>
            <div class="modal-body panel-body">
                <center>
                    <i style="color:#23db16; font-size:80px;" class="fa fa-check-circle "></i>
                    <br><br>
                    Tasks was uploaded successfully!
                </center>
            </div>
            <div class="modal-footer">
                <a href="task_import.php"><button type="button" name="submit"
                        class="btn btn-success pull-right">Return</button></a>
            </div>
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="error2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content panel-danger">
            <div class="modal-header panel-heading">
                <a href="task_import.php"><button type="button" class="close"
                        aria-hidden="true">&times;</button></a>
                <h4 class="modal-title" id="myModalLabel">Warning!</h4>
            </div>
            <div class="modal-body panel-body">
                <center>
                    <i style="color:#e13232; font-size:80px;" class="fa fa-times-circle "></i>
                    <br><br>
                    Invalid File!
                    <br>
                    Please upload XLS, XLSX & CSV file only.
                </center>
            </div>
            <div class="modal-footer">
                <a href="task_import.php"><button type="button" name="submit"
                        class="btn btn-danger pull-right">Return</button></a>
            </div>
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div>