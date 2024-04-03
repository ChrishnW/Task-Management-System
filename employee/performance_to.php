<?php 
include ('../include/connect.php');

if(isset($_POST['valto'])){
  $val_from = $_POST['valfrom'];
  $val_to = $_POST['valto'];
  $username = $_POST['username'];

  if($val_from != 0){
    $donetotal = 0;
    $tasktotal = 0;
    $totavg = 0;
    $donesum = 0;
    $latedone = 0;
    $resdone = 0;
    $remtask = 0;
    $ftask = 0;
    $three = 0;
    $two = 0;
    $one = 0;
    $zero = 0;
    $result = mysqli_query($con,"SELECT tasks_details.task_code, tasks_details.task_name, task_class.task_class, tasks_details.date_accomplished, tasks_details.due_date, tasks_details.remarks, tasks_details.date_created, tasks_details.achievement, tasks_details.status FROM tasks_details LEFT JOIN task_list ON tasks_details.task_name = task_list.task_name LEFT JOIN task_class ON task_list.task_class = task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE in_charge='$username' AND task_status!=0 AND tasks_details.due_date>='$val_from' AND tasks_details.due_date<='$val_to' AND approval_status=0");
    if (mysqli_num_rows($result)>0) { 
      while ($row = $result->fetch_assoc()) { 
        $taskcode = $row['task_code'];
        $taskname = $row['task_name'];
        $taskclass = $row['task_class'];
        $dateaccom = $row['date_accomplished'];
        $datedue = $row['due_date'];
        $remarks = $row['remarks'];
        $datec = $row['date_created'];
        $achievement = $row['achievement'];
        
        if (($row['status'] == 'NOT YET STARTED') || ($row['status'] == 'IN PROGRESS')) {
          $remtask += 1; 
        }
        if ($row['status'] == 'FINISHED') {
          $donetotal += 1;
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
        elseif ($achievement == 0){
          $zero += 1;
        }
      }
    }
    $donesum = ($three * 3) + ($two * 2) + ($one * 1) + ($zero * 0);
    $tasktotal = $remtask + $donetotal;
    if ($donesum != 0){
      $totavg = $donesum / $tasktotal;   
    }
    $formatted_number = number_format($totavg, 2);
    // Rating
    if ($formatted_number == 3) {
      $rate = '<span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star" style="color: yellow">';
    }
    elseif ($formatted_number >= 2.5){
      $rate = '<span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star-half" style="color: yellow">';
    }
    elseif ($formatted_number == 2) {
      $rate = '<span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star" style="color: yellow">';
    }
    elseif ($formatted_number >= 1.5) {
      $rate = '<span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star-half" style="color: yellow">';
    }
    elseif ($formatted_number == 1) {
      $rate = '<span class="fa fa-solid fa-star" style="color: yellow">';
    }
    elseif ($formatted_number > 0) {
      $rate = '<span class="fa fa-solid fa-star-half" style="color: yellow">';
    }
    else {
      $rate = '';
    }
    echo "<tr>                                                   
    <td><center />" . $donetotal . "</td>
    <td><center />" . $tasktotal . "</td>
    <td><center />" . $formatted_number . '<br>' . $rate . "</td>
    <td><center /> "."<a href='my_list_sort.php?from=$val_from&to=$val_to'> <button class='btn btn-sm btn-success'><i class='fas fa-eye'></i> View</button></a>"."</td>
    </tr>";
  }
}
?>