<?php
include('../include/link.php');
include('../include/connect.php');
$ID = $_POST['id'];
$ACTION = $_POST['action'];
$today = date("Y-m-d");
$con->next_result();
// $sql = "UPDATE tasks_details SET status='FINISHED', remarks='$ACTION', date_accomplished='$today' WHERE id = '$ID'";
// $result = mysqli_query($con, $first) or die('Error querying database.');
$check = mysqli_query($con,("SELECT * FROM tasks_details WHERE id = '$ID'"));
while ($row = $check->fetch_assoc()){
    $date_accomplished = date_create($row['date_accomplished']);
    $due_date = date_create($row['due_date']);
    $int = date_diff($due_date, $date_accomplished);
    $interval = $int->format("%R%a");
    $resched = $row['reschedule'];

    if ($interval<=0 && $resched == 0 ) {
        $achievement = '3';
    } 
    else if ($interval<=0 && $resched == 2 ) {
        $achievement = '2';
    } 
    else if ($interval>0) {
        $achievement = '1';
    } 
    else {
        $achievement = '0';
    }

    $update = "UPDATE tasks_details SET achievement='$achievement', remarks='$ACTION', status='FINISHED', date_accomplished='$today' WHERE id = '$ID'";
    $update = mysqli_query($con, $update);
}
    
?>