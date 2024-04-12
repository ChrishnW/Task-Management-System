<?php 
include ('../include/connect.php');
$date_today = date('Y-m-d');
$month = date('m');
$year = date('Y');

if(isset($_POST['valfrom'])){
  $val_from = $_POST['valfrom'];
  $val_to = $_POST['valto'];
  $username = $_POST['username'];

  if($val_to != 0){
    $m_remtask = 0; $m_donetotal = 0; $m_three = 0; $m_two = 0; $m_one = 0; $m_zero = 0; $m_donesum = 0; $m_tasktotal = 0; $m_totavg = 0;
    $remtask = 0; $donetotal = 0; $three = 0; $two = 0; $one = 0; $zero = 0; $donesum = 0; $tasktotal = 0; $totavg = 0; $formatted_number = 0;
    $result = mysqli_query($con, "SELECT * FROM tasks_details JOIN task_class ON tasks_details.task_class = task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE in_charge='$username' AND task_status!=0 AND tasks_details.due_date>='$val_from' AND tasks_details.due_date<='$val_to'");
    if (mysqli_num_rows($result) > 0) {
      while ($row = $result->fetch_assoc()) {
        $achievement = $row['achievement'];
        if ($row['task_class'] == 'MONTHLY ROUTINE') {
          if ($row['head_name'] == NULL) {
            $m_remtask += 1;
          }

          if ($row['head_name'] != NULL) {
            $m_donetotal += 1;
          }

          if ($achievement == 3 && $row['head_name'] != NULL) {
            $m_three += 1;
          }
          elseif ($achievement == 2 && $row['head_name'] != NULL) {
            $m_two += 1;
          }
          elseif ($achievement == 1 && $row['head_name'] != NULL) {
            $m_one += 1;
          }
          elseif ($achievement == 0 && $row['head_name'] != NULL) {
            $m_zero += 1;
          }
          $m_donesum = ($m_three * 3) + ($m_two * 2) + ($m_one * 1) + ($m_zero * 0);
          $m_tasktotal = $m_remtask + $m_donetotal;
          if ($m_donesum != 0) {
            $m_totavg = $m_donesum / $m_tasktotal;
          }
          $monthly = number_format($m_totavg, 2);
        }
        elseif ($row['task_class'] != 'MONTHLY ROUTINE') {
          if ($row['head_name'] == NULL) {
            $remtask += 1;
          }

          if ($row['head_name'] != NULL) {
            $donetotal += 1;
          }

          if ($achievement == 3 && $row['head_name'] != NULL) {
            $three += 1;
          }
          elseif ($achievement == 2 && $row['head_name'] != NULL) {
            $two += 1;
          }
          elseif ($achievement == 1 && $row['head_name'] != NULL) {
            $one += 1;
          }
          elseif ($achievement == 0 && $row['head_name'] != NULL) {
            $zero += 1;
          }
          $donesum = ($three * 3) + ($two * 2) + ($one * 1) + ($zero * 0);
          $tasktotal = $remtask + $donetotal;
          if ($donesum != 0) {
            $totavg = $donesum / $tasktotal;
          }
          $formatted_number = number_format($totavg, 2);
        }
      }
    }
    $ftasktotal = $tasktotal + $m_tasktotal;
    $fdonetotal = $donetotal + $m_donetotal;
    $fremtask = $remtask + $m_remtask;
    echo "<tr>
    <td><center />" . $ftasktotal . "</td>                                                 
    <td><center />" . $fdonetotal . "</td>
    <td><center />" . $fremtask . "</td>
    <td><center />" . $formatted_number . "</td>
    <td><center />" . $monthly . "</td>
    <td><center /> "."<a href='my_list_sort.php?from=$val_from&to=$val_to'> <button class='btn btn-sm btn-success'><i class='fas fa-eye'></i> View</button></a>"."</td>
    </tr>";
  }
}
?>