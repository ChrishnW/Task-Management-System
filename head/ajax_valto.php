<?php 
include ('../include/connect.php');

if(isset($_POST['valfrom'])){
          
    $val_from = $_POST['valfrom'];
    $val_to = $_POST['valto'];
    $status_input = $_POST['status'];

    if($val_from != 0){
                         
        $con->next_result();
        $result = mysqli_query($con,"SELECT tasks_details.task_code, task_list.task_name, task_list.task_details, task_class.task_class, task_list.task_for, tasks_details.date_created, tasks_details.due_date, tasks_details.in_charge, tasks_details.status, tasks_details.date_accomplished, tasks_details.id, accounts.fname, accounts.lname, tasks_details.remarks, tasks_details.reschedule FROM tasks_details LEFT JOIN task_list ON task_list.task_code=tasks_details.task_code LEFT JOIN task_class ON task_list.task_class=task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE tasks_details.task_status IS TRUE AND tasks_details.status='$status_input' AND tasks_details.due_date>='$val_from' AND tasks_details.due_date<='$val_to' AND tasks_details.approval_status IS TRUE  AND (tasks_details.reschedule = '0' OR tasks_details.reschedule = '2' AND tasks_details.approval_status=1)");           
        if (mysqli_num_rows($result)>0) { 
          while ($row = $result->fetch_assoc()) {

              if ($row['date_accomplished']!='') {
                  $class = "";
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
                  } else if ($interval>0) {
                    $achievement = '1';
                  } else {
                      $achievement = '0';
                  }
              } else {
                  $achievement = '0';
                  $today = date("Y-m-d");
                  $due_date = $row["due_date"];
                  $class = "";
                  if ($today > $due_date) {
                      $class = "red";
                  }
              }
              

              if ($row['status'] == 'FINISHED') {
                  $class_label = "success";
                  $status = "FINISHED";
              } else if ($row['status'] == 'IN PROGRESS') {
                  $class_label = "info";
                  $status = "IN PROGRESS";
              } else {
                  $class_label = "danger";
                  $status = "NOT YET STARTED";
              }
              echo "<tr>                                                      
                  <td class='".$class."'> " . $row["task_name"] . " </td>   
                  <td class='".$class."'>" . $row["task_class"] . "</td> 
                  <td class='".$class."'>" . $row["task_for"] . "</td> 
                  <td class='".$class."'>" . $row["date_created"] . "</td> 
                  <td class='".$class."'>" . $row["due_date"] . "</td> 
                  <td class='".$class."'>" . $row["fname"].' '.$row["lname"] . "</td>
                  <td><center/><p class='label label-".$class_label."' style='font-size:100%;'>".$status."</p></td>
                  <td class='".$class."'>" . $row["date_accomplished"] . "</td>
                  <td class='".$class."'>" . $row["remarks"] . "</td>
                  <td class='".$class."'>" . $achievement . "</td>
              </tr>";   
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
        "order": [[ 4, "asc" ]]
    });
});
</script>