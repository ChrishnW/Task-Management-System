<?php 
include ('../include/connect.php');

if(isset($_POST['valfrom'])){
          
    $val_from = $_POST['valfrom'];
    $val_to = $_POST['valto'];
    $status_input = $_POST['status'];

    if($val_to != 0){
                         
    $con->next_result();
    $result = mysqli_query($con,"SELECT tasks_details.task_code, tasks_details.achievement, task_list.task_name, task_list.task_details, task_class.task_class, task_list.task_for, tasks_details.date_created, tasks_details.due_date, tasks_details.in_charge, tasks_details.status, tasks_details.date_accomplished, tasks_details.id, accounts.fname, accounts.lname, tasks_details.remarks, tasks_details.reschedule FROM tasks_details LEFT JOIN task_list ON task_list.task_code=tasks_details.task_code LEFT JOIN task_class ON task_list.task_class=task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE tasks_details.task_status IS TRUE AND tasks_details.status='$status_input' AND tasks_details.due_date>='$val_from' AND tasks_details.due_date<='$val_to' AND tasks_details.approval_status IS TRUE  AND (tasks_details.reschedule = '0' OR tasks_details.reschedule = '2' AND tasks_details.approval_status=1)");           
    if (mysqli_num_rows($result)>0) { 
        while ($row = $result->fetch_assoc()) {
            $today = date("Y-m-d");
            $due_date = $row["due_date"];
            $nextDate = date('Y-m-d', strtotime($due_date . ' + ' . 1 . ' days'));
            $yesterday = date('Y-m-d', strtotime($today . ' -' . 1 . ' days'));
            $twodago = date('Y-m-d', strtotime($due_date . ' +' . 2 . ' days'));
            $task_class = $row['task_class'] ;
            $class = "";
            $sign = "";
            
            if ($status_input == "NOT YET STARTED") {
                // DAILY, ADDITIONAL AND PROJECT
                if ($task_class == "DAILY ROUTINE" || $task_class == "ADDITIONAL TASK" || $task_class == "PROJECT"){
                    if ($due_date < $today){
                        $class_label = "danger";
                        $sign = "EXPIRED";
                        $class = "invalid";
                    }
                    elseif ($due_date > $today){
                        $class_label = "info";
                        $sign = "PENDING";
                    }
                    elseif ($due_date == $today){
                        $class_label = "primary";
                        $sign = "NOT YET STARTED";
                    }
                    else {
                        $class_label = "muted";
                        $sign = "INVALID";
                    }
                }
                // WEEKLY
                if ($task_class == "WEEKLY ROUTINE"){
                    if ($twodago  <= $today){
                        $class_label = "danger";
                        $sign = "EXPIRED";
                        $class = "invalid";
                    }
                    elseif ($due_date <= $yesterday){
                        $class_label = "warning";
                        $sign = "EXPIRING";
                    }
                    elseif ($due_date == $today) {
                        $class_label = "primary";
                        $sign = "NOT YET STARTED";
                    }
                    elseif ($due_date >= $today) {
                        $class_label = "info";
                        $sign = "PENDING";
                    }
                    
                }
                // MONTHLY
                if ($task_class == "MONTHLY ROUTINE"){
                    if ($twodago  <= $today){
                        $class_label = "danger";
                        $sign = "EXPIRED";
                        $class = "invalid";
                    }
                    elseif ($due_date <= $yesterday){
                        $class_label = "warning";
                        $sign = "EXPIRING";
                    }
                    elseif ($due_date >= $today) {
                        $class_label = "primary";
                        $sign = "NOT YET STARTED";
                    }
                }
            }
            if ($status_input == "IN PROGRESS"){ 
                if (($today > $due_date && ($task_class == "DAILY ROUTINE" || $task_class == "ADDITIONAL TASK" || $task_class == "PROJECT"))
                || ($twodago  <= $today && ($task_class == "WEEKLY ROUTINE" || $task_class == "MONTHLY ROUTINE"))){
                    $class = "invalid";
                    $sign = "OVERDUE";
                    $class_label = "danger";
                }
                else {
                    $sign = "IN PROGRESS";
                    $class_label = "warning";
                }
            }
            if ($status_input == "FINISHED"){
                $achievement = $row['achievement'];
                if ($achievement == 0){
                    $class_label = "danger";
                    $sign = "FAILED";
                }
                if ($achievement > 0){
                    $class_label = "success";
                    $sign = "FINISHED";
                }
            }
            if ($status_input == "FINISHED"){
                echo "<tr>  
                    <td class='".$class."'>" . $row["task_name"] . " </td>   
                    <td class='".$class."'><center />" . $row["task_class"] . "</td> 
                    <td class='".$class."'><center />" . $row["task_for"] . "</td>
                    <td class='".$class."'><center />" . $row["fname"].' '.$row["lname"] . "</td>
                    <td class='".$class."'><center />" . $row["date_created"] . "</td> 
                    <td class='".$class."'><center />" . $row["due_date"] . "</td> 
                    <td class='".$class."'><center /><p class='label label-".$class_label."' style='font-size:100%;'>".$sign."</p></td>
                    <td class='".$class."'><center />" . $row["date_accomplished"] . "</td>
                    <td class='".$class."'><center />" . $achievement . "</td>
                    <td class='".$class."'>" . $row["remarks"] . "</td>
                </tr>";
                }
            else {
                echo "<tr>  
                    <td class='".$class."'>" . $row["task_name"] . " </td>   
                    <td class='".$class."'><center />" . $row["task_class"] . "</td> 
                    <td class='".$class."'><center />" . $row["task_for"] . "</td>
                    <td class='".$class."'><center />" . $row["fname"].' '.$row["lname"] . "</td>
                    <td class='".$class."'><center />" . $row["date_created"] . "</td> 
                    <td class='".$class."'><center />" . $row["due_date"] . "</td> 
                    <td class='".$class."'><center /><p class='label label-".$class_label."' style='font-size:100%;'>".$sign."</p></td>
                </tr>";
            } 
        }
    }
    else {
        echo "0 results"; }    
    if ($con->connect_error) {
        die("Connection Failed".$con->connect_error); }; 
    }
                                        
}
?>
<script>
$(document).ready(function() {
    $('#table_task').DataTable({
        responsive: true,
        destroy: true,
        "order": [[ 5, "asc" ]]
    });
});
</script>