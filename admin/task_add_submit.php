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

<?php
include('../include/link.php');
include('../include/connect.php');

$task_name = strtoupper($_POST['task_name']);
$task_details = strtoupper($_POST['task_details']);
$task_class = $_POST['task_class'];
$task_for = $_POST['task_for'];

$con->next_result();
$check=mysqli_query($con,"SELECT * FROM task_list WHERE task_name='$task_name' AND task_details='$task_details' AND task_for='$task_for'");
$checkrows=mysqli_num_rows($check);

    
if($checkrows>0) {
    echo "<script type='text/javascript'>   $(document).ready(function(){ $('#exists').modal('show');   });</script>";         
    exit;
}
else {
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

    // Increment the item code
    // $parts = explode('-', $current_item_code);
    // $last_part = intval($parts[count($parts) - 1]) + 1;
    // $parts[count($parts) - 1] = $last_part;
    // $new_item_code = implode('-', $parts);
    
    // $task_code = $task_for.'_'.$prefix . '-' . $new_item_code;

    // Extract the numeric portion from the latest task code

    $numeric_portion = intval(substr($row['latest_task_code'], -6)) + 1;

    // $numeric_portion = substr($row['latest_task_code'], 6);
    // $numeric_portion++; // Increment the numeric portion

    // Format the task code with the prefix and numeric portion
    $task_code = $task_for.'-'.$prefix . '-' . str_pad($numeric_portion, 6, '0', STR_PAD_LEFT);

    // Insert the new task record into the database with the generated task code
    $sql = "INSERT INTO task_list (task_code, task_name, task_details, task_class, task_for, status) VALUES (:task_code, :task_name, :task_details, :task_class, :task_for, 1)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':task_code', $task_code);
    $stmt->bindParam(':task_name', $task_name);
    $stmt->bindParam(':task_details', $task_details);
    $stmt->bindParam(':task_class', $task_class);
    $stmt->bindParam(':task_for', $task_for);
    $stmt->execute();
    header('location: task_list.php'); 
}
?>