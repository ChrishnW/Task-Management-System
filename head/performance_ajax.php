<?php 
include ('../include/connect.php');
$username = $_POST['username'];
$section = $_POST['section'];
if(isset($_POST['sid'])){
    $ID = $_POST['sid'];
    $con->next_result();
    $con->next_result();
    $result = mysqli_query($con,"SELECT * FROM accounts WHERE sec_id='$section' AND access=2");
        while ($row = $result->fetch_assoc()) {                                                
            $emp_name=$row['fname'].' '.$row['lname'];
            $username=$row["username"];
            $label='Task/(s)';
            $emp_avg = 0;
            $formatted_number = number_format($emp_avg, 2);
            $count_task = mysqli_query($con,"SELECT COUNT(tasks_details.task_code) as total_task FROM tasks_details WHERE tasks_details.in_charge = '$username' AND MONTH(tasks_details.due_date) = '$ID' AND tasks_details.reschedule != '1'");
            $count_task_row = $count_task->fetch_assoc();
            $total_task=$count_task_row['total_task'];
            if ($total_task=='0') {
                $total_task='No';
                $rate = '☆☆☆☆☆';
                echo 
                "<tr>                                                
                <td><center /<>" . $username . "</td>                  
                <td><center /> " . $emp_name . "</td>
                <td><center />" . $formatted_number . '<br>' . $rate . "</td>
                <td><center /> " . $total_task .' '.$label. "</td>
                </tr>";
            }
            
            else {
                // Average Computation
                $donetotal = 0;
                $tasktotal = 0;
                $totavg = 0;
                $donesum = 0;
                $ontasks = 0;
                $remtask = 0;
                $ftask = 0;
                $dateaccom = 0;
                $datedue = 0;
                $three = 0;
                $two = 0;
                $one = 0;
                $zero = 0;
                $avg_task = mysqli_query($con,"SELECT tasks_details.date_created, tasks_details.achievement, tasks_details.due_date, tasks_details.date_accomplished, tasks_details.in_charge, accounts.username, accounts.sec_id, tasks_details.task_code, tasks_details.resched_reason, task_list.task_name, task_list.task_class, tasks_details.reschedule, tasks_details.remarks, tasks_details.status FROM tasks_details LEFT JOIN accounts ON tasks_details.in_charge = accounts.username LEFT JOIN task_list ON tasks_details.task_code = task_list.task_code WHERE MONTH(tasks_details.due_date) = '$ID' AND accounts.username = '$username' AND tasks_details.reschedule != '1'");
                if (mysqli_num_rows($avg_task)>0) { 
                    while ($rows = $avg_task->fetch_assoc()) { 
                    $taskcode = $rows['task_code'];
                    $taskname = $rows['task_name'];
                    $taskclass = $rows['task_class'];
                    $dateaccom = $rows['date_accomplished'];
                    $remarks = $rows['remarks'];
                    $achievement = $rows['achievement'];
                        if ($rows['status'] == 'IN PROGRESS') {
                            $achievement = 0;
                            $ontasks += 1;
                        }
                        if ($rows['status'] == 'NOT YET STARTED') {
                            $achievement = 0;
                            $remtask += 1; 
                        }
                        if ($rows['status'] == 'FINISHED') {
                            $donetotal += 1;
                        }
                        if ($row['status'] == 'FAILED') {
                            $ftask += 1;
                        }
                        if ($achievement == 3) {
                            $three += 1;
                        }
                        elseif ($achievement == 2) {
                            $two += 1;
                        }
                        elseif ($achievement == 1) {
                            $one += 1;
                        }
                    }
                }
                $three = $three * 3;
                $two = $two * 2;
                $one = $one * 1;
                $donesum = $three + $two + $one;
                $tasktotal = $ontasks + $remtask + $donetotal;
                $totavg = $donesum / $tasktotal;
                $formatted_number = number_format($totavg, 2);
                // Rating
                // $formatted_number = 1.6; (FOR CHECKING)
                if ($formatted_number >= 3) {
                    $rate = '<span style="color: yellow">★★★★★</span>';
                }
                elseif ($formatted_number >= 2.6){
                    $rate = '<span style="color: yellow">★★★★</span>☆';
                }
                elseif ($formatted_number >= 2) {
                    $rate = '<span style="color: yellow">★★★</span>☆☆';
                }
                elseif ($formatted_number >= 1.6) {
                    $rate = '<span style="color: yellow">★★</span>☆☆☆';
                }
                elseif ($formatted_number >= 0.5) {
                    $rate = '<span style="color: yellow">★</span>☆☆☆☆';
                }
                else {
                    $rate = '☆☆☆☆☆';
                }
                echo 
                "<tr>
                <td><center /<>" . $username . "</td>
                <td><center /> " . $emp_name . "</td>
                <td><center />" . $formatted_number . '<br>' . $rate . "</td>
                <td><center /> ". $tasktotal .' '.$label."<a href='performance_list_ajax.php?id=".$username."&month=".$ID."'> <button class='btn btn-sm btn-success pull-right'><i class='fas fa-eye'></i> View</button></a>"."</td>
                </tr>";
            }
        }
}
?>
