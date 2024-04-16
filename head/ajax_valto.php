<?php 
include ('../include/connect.php');

if(isset($_POST['valfrom'])){
          
    $val_from = $_POST['valfrom'];
    $val_to = $_POST['valto'];
    $status = $_POST['status'];
    $dept_id = $_POST['dept_id'];

    if($val_from != 0){                     
        $con->next_result();
        $result = mysqli_query($con,"SELECT * FROM tasks_details JOIN task_class ON tasks_details.task_class = task_class.id JOIN accounts ON tasks_details.in_charge=accounts.username JOIN section ON section.sec_id=tasks_details.task_for WHERE tasks_details.task_status=1 AND tasks_details.approval_status='0' AND tasks_details.reschedule=0 AND section.dept_id='$dept_id' AND tasks_details.status='$status' AND tasks_details.due_date>='$val_from' AND tasks_details.due_date<='$val_to'");               
        if (mysqli_num_rows($result)>0) { 
            while ($row = $result->fetch_assoc()) {
                $today = date("Y-m-d");
                $due_date = $row['due_date'];
                $due = date('m / d / Y', strtotime($row['due_date']));
                $date = date('m / d / Y H:i:s a', strtotime($row['date_accomplished']));
                $nextDate = date('Y-m-d', strtotime($due_date . ' + ' . 1 . ' days'));
                $yesterday = date('Y-m-d', strtotime($today . ' -' . 1 . ' days'));
                $twodago = date('Y-m-d', strtotime($due_date . ' +' . 2 . ' days'));
                $task_class = $row['task_class'] ;
                $class = "";
                $sign = "";
                $achievement = $row['achievement'];
                $emp_name=$row['fname'].' '.$row['lname'];
                if (empty($row["file_name"])) {
                    // Use a default image URL
                    $imageURL = '../assets/img/user-profiles/nologo.png';
                } else {
                    // Use the image URL from the database
                    $imageURL = '../assets/img/user-profiles/'.$row["file_name"];
                } 
                if ($status == "NOT YET STARTED") {
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
                        elseif ($due_date > $today) {
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
                        elseif ($due_date == $today) {
                            $class_label = "primary";
                            $sign = "NOT YET STARTED";
                        }
                        elseif ($due_date > $today) {
                            $class_label = "info";
                            $sign = "PENDING";
                        }
                    }
                }
                if ($status == "IN PROGRESS"){ 
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
                if ($status == "FINISHED"){
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
        
                if ($status == "FINISHED"){
                echo "<tr>
                    <td class='".$class."'>". $row["task_code"] . " </td>"; ?>
                    <?php
                    if ($row['requirement_status'] == 1){
                        echo "<td class='".$class."'> <span style='color: #00ff26'><i class='fa fa-paperclip' title='Attachment Required'></i></span></td>";
                    }
                    else {
                        echo "<td class='".$class."'> </td>";
                    }
                    echo "
                    <td id='normalwrap' class='".$class."'>" . $row["task_name"] . " </td>   
                    <td class='".$class."'><center />" . $row["task_class"] . "</td> 
                    <td class='".$class."' style='text-align: justify'> <img src=".$imageURL." class='profile' title=".$row["username"]." style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-right: 15px; margin-left: 0'>" . $emp_name . "</td>  
                    <td class='".$class."'><center /><p class='label label-".$class_label."' style='font-size:100%;'>".$sign."</p></td>
                    <td class='".$class."'><center />" . $date . "</td>
                    <td class='".$class."'><center />" . $row['achievement'] . "</td>
                    <td><center><button value='".$row['task_code']."' data-name='".$row['task_name']."' data-class='".$row['task_class']."' data-remarks='".$row['remarks']."' data-duedate='".$row['due_date']."' data-datefinish='".$row['date_accomplished']."' data-achievement='".$row['achievement']."' data-file='".$row['requirement_status']."' data-note='".$row['head_note']."' data-head='".$row['head_name']."' data-path='".$row['attachment']."' class='btn btn-primary' onclick='view1(this)'><span class='fa fa-folder-open'></span> View </button></center></td> 

                </tr>";
                }
                else {
                    echo "<tr>
                    <td class='".$class."'>". $row["task_code"] . " </td>"; ?>
                    <?php
                    if ($row['requirement_status'] == 1){
                        echo "<td class='".$class."'> <span style='color: #00ff26'><i class='fa fa-paperclip' title='Attachment Required'></i></span></td>";
                    }
                    else {
                        echo "<td class='".$class."'> </td>";
                    }
                    echo " 
                    <td id='normalwrap' class='".$class."'>" . $row["task_name"] . " </td>   
                    <td class='".$class."'><center />" . $row["task_class"] . "</td> 
                    <td class='".$class."' style='text-align: justify'> <img src=".$imageURL." class='profile' title=".$row["username"]." style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-right: 15px; margin-left: 0'>" . $emp_name . "</td>  
                    <td class='".$class."'><center />" . $due . "</td> 
                    <td class='".$class."'><center /><p class='label label-".$class_label."' style='font-size:100%;'>".$sign."</p></td>
                </tr>";
                }
            }
        } 
        if ($con->connect_error) {
            die("Connection Failed".$con->connect_error); 
        };                                     
    }
}
?>