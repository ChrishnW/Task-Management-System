<?php 
include ('../include/connect.php');

if(isset($_POST['sid'])){
    $ID = $_POST['sid'];
    $ID2 = $_POST['status'];
    if ($ID=='ALL') {
        $con->next_result();
        $result = mysqli_query($con,"SELECT tasks_details.task_code, task_list.task_name, task_list.task_details, task_class.task_class, task_list.task_for, tasks_details.date_created, tasks_details.due_date, tasks_details.in_charge, tasks_details.status, tasks_details.date_accomplished, tasks_details.id, accounts.fname, accounts.lname FROM tasks_details LEFT JOIN task_list ON task_list.task_code=tasks_details.task_code  LEFT JOIN task_class ON task_list.task_class=task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE tasks_details.status IS TRUE");    
            
        if (mysqli_num_rows($result)>0) { 
            while ($row = mysqli_fetch_array($result)) {   

                        if ($row['date_accomplished']!='') {
                            $class = "";
                            $date_accomplished = date_create($row['date_accomplished']);
                            $due_date = date_create($row['due_date']);
                            $int = date_diff($due_date, $date_accomplished);
                            $interval = $int->format("%R%a");
                            if ($interval<=0) {
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
                        <td class='".$class."'> " . $row["task_code"] . " </td>                                                     
                        <td class='".$class."'> " . $row["task_name"] . " </td>   
                        <td class='".$class."'>" . $row["task_details"] . "</td> 
                        <td class='".$class."'>" . $row["task_class"] . "</td> 
                        <td class='".$class."'>" . $row["task_for"] . "</td> 
                        <td class='".$class."'>" . $row["date_created"] . "</td> 
                        <td class='".$class."'>" . $row["due_date"] . "</td> 
                        <td class='".$class."'>" . $row["fname"].' '.$row["lname"] . "</td>
                        <td><center/><p class='label label-".$class_label."' style='font-size:100%;'>".$status."</p></td>
                        <td class='".$class."'>" . $row["date_accomplished"] . "</td>
                        <td class='".$class."'>" . $achievement . "</td>
                        <td> <center /><a href='task_details_edit.php?id=".$row['id']."&&section=".$ID2."' <button class='btn btn-primary' ><i class='fa fa-edit fa-1x'></i> Edit</button></a>
                        </td>
                    </tr>";     
            }
        } 
    } else {
        $con->next_result();
        $result = mysqli_query($con,"SELECT tasks_details.task_code, task_list.task_name, task_list.task_details, task_class.task_class, task_list.task_for, tasks_details.date_created, tasks_details.due_date, tasks_details.in_charge, tasks_details.status, tasks_details.date_accomplished, tasks_details.id, accounts.fname, accounts.lname FROM tasks_details LEFT JOIN task_list ON task_list.task_code=tasks_details.task_code  LEFT JOIN task_class ON task_list.task_class=task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE tasks_details.task_status'$ID2' AND tasks_details.status='$ID'");    
               
        if (mysqli_num_rows($result)>0) { 
            while ($row = mysqli_fetch_array($result)) {    
                
                        if ($row['date_accomplished']!='') {
                            $class = "";
                            $date_accomplished = date_create($row['date_accomplished']);
                            $due_date = date_create($row['due_date']);
                            $int = date_diff($due_date, $date_accomplished);
                            $interval = $int->format("%R%a");
                            if ($interval<=0) {
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
                        <td class='".$class."'> " . $row["task_code"] . " </td>                                                     
                        <td class='".$class."'> " . $row["task_name"] . " </td>   
                        <td class='".$class."'>" . $row["task_details"] . "</td> 
                        <td class='".$class."'>" . $row["task_class"] . "</td> 
                        <td class='".$class."'>" . $row["task_for"] . "</td> 
                        <td class='".$class."'>" . $row["date_created"] . "</td> 
                        <td class='".$class."'>" . $row["due_date"] . "</td> 
                        <td class='".$class."'>" . $row["fname"].' '.$row["lname"] . "</td>
                        <td><center/><p class='label label-".$class_label."' style='font-size:100%;'>".$status."</p></td>
                        <td class='".$class."'>" . $row["date_accomplished"] . "</td>
                        <td class='".$class."'>" . $achievement . "</td>
                        <td> <center /><a href='task_details_edit.php?id=".$row['id']."&&section=".$ID2."' <button class='btn btn-primary' ><i class='fa fa-edit fa-1x'></i> Edit</button></a>
                        </td>
                    </tr>";     
            }
        }
    }   
}
?>