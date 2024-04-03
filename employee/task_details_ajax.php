<?php 
include ('../include/connect.php');
$task_status=isset($_GET['task_status']) ? $_GET['task_status'] : die('ERROR: Record ID not found.');

if(isset($_POST['sid'])){
    $ID = $_POST['sid'];
    if ($ID=='ALL') {
        $con->next_result();
        $result = mysqli_query($con,"SELECT tasks_details.task_code, task_list.task_name, task_list.task_details, task_class.task_class, task_list.task_for, tasks_details.date_created, tasks_details.due_date, tasks_details.in_charge, tasks_details.status, tasks_details.date_accomplished, tasks_details.id, accounts.fname, accounts.lname, tasks_details.reschedule FROM tasks_details LEFT JOIN task_list ON task_list.task_code=tasks_details.task_code  LEFT JOIN task_class ON task_list.task_class=task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE tasks_details.status='$task_status'");    
            
        if (mysqli_num_rows($result)>0) { 
            while ($row = mysqli_fetch_array($result)) {   

                        if ($row['date_accomplished']!='') {
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
                        }

                        if ($row['status'] == 'FINISHED') {
                            $class = "success";
                            $status = "FINISHED";
                        } else if ($row['status'] == 'IN PROGRESS') {
                            $class = "info";
                            $status = "IN PROGRESS";
                        } else {
                            $class = "danger";
                            $status = "NOT YET STARTED";
                        }
                        echo "<tr>                                                      
                        <td> " . $row["task_name"] . " </td>   
                        <td>" . $row["task_details"] . "</td> 
                        <td>" . $row["task_class"] . "</td> 
                        <td>" . $row["due_date"] . "</td> 
                        <td>" . $row["fname"].' '.$row["lname"] . "</td>
                        <td><center/><p class='label label-".$class."' style='font-size:100%;'>".$status."</p></td>
                        <td>" . $row["date_accomplished"] . "</td>
                        <td>" . $achievement . "</td>
                        <td> <center/><button id='task_id' value='".$row['id']."' class='btn btn-primary' onclick='start(this)'><i class='fa fa-play fa-1x'></i></button>

                        <td> <center/><button id='task_id' value='".$row['id']."' class='btn btn-danger' onclick='finish(this)'><i class='fa fa-stop fa-1x'></i></button>
                    </tr>";    
            }
        } 
    } else {
        $con->next_result();
        $result = mysqli_query($con,"SELECT tasks_details.task_code, task_list.task_name, task_list.task_details, task_class.task_class, task_list.task_for, tasks_details.date_created, tasks_details.due_date, tasks_details.in_charge, tasks_details.status, tasks_details.date_accomplished, tasks_details.id, accounts.fname, accounts.lname, tasks_details.reschedule FROM tasks_details LEFT JOIN task_list ON task_list.task_code=tasks_details.task_code  LEFT JOIN task_class ON task_list.task_class=task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE task_list.task_class='$ID' AND tasks_details.status='$task_status'");    
               
        if (mysqli_num_rows($result)>0) { 
            while ($row = mysqli_fetch_array($result)) {    
                
                        if ($row['date_accomplished']!='') {
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
                        }

                        if ($row['status'] == 'FINISHED') {
                            $class = "success";
                            $status = "FINISHED";
                        } else if ($row['status'] == 'IN PROGRESS') {
                            $class = "info";
                            $status = "IN PROGRESS";
                        } else {
                            $class = "danger";
                            $status = "NOT YET STARTED";
                        } 
                                            
                        echo "<tr>                                                      
                        <td> " . $row["task_name"] . " </td>   
                        <td>" . $row["task_details"] . "</td> 
                        <td>" . $row["task_class"] . "</td> 
                        <td>" . $row["due_date"] . "</td> 
                        <td>" . $row["fname"].' '.$row["lname"] . "</td>
                        <td><center/><p class='label label-".$class."' style='font-size:100%;'>".$status."</p></td>
                        <td>" . $row["date_accomplished"] . "</td>
                        <td>" . $achievement . "</td>
                        <td> <center/><button id='task_id' value='".$row['id']."' class='btn btn-primary' onclick='start(this)'><i class='fa fa-play fa-1x'></i></button>

                        <td> <center/><button id='task_id' value='".$row['id']."' class='btn btn-danger' onclick='finish(this)'><i class='fa fa-stop fa-1x'></i></button>
                    </tr>";    
            }
        }
    }   
}
?>