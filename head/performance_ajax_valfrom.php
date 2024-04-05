<?php
include ('../include/connect.php');

if (isset($_POST['valfrom'])) {
  $val_from = $_POST['valfrom'];
  $val_to = $_POST['valto'];
  $section = $_POST['section'];

  if ($val_to != 0) {
    $con->next_result();
    $result = mysqli_query($con, "SELECT * FROM accounts WHERE sec_id='$section' AND access=2");
    while ($row = $result->fetch_assoc()) {
      $emp_name = $row['fname'] . ' ' . $row['lname'];
      $username = $row["username"];
      $id = $row['card'];
      $label = 'Completed Task/s';
      $emp_avg = 0;
      if (empty($row["file_name"])) {
        // Use a default image URL
        $imageURL = '../assets/img/user-profiles/nologo.png';
      }
      else {
        // Use the image URL from the database
        $imageURL = '../assets/img/user-profiles/' . $row["file_name"];
      }
      $formatted_num = number_format($emp_avg, 2);
      $rate = '';
      $count_task = mysqli_query($con, "SELECT COUNT(tasks_details.task_code) as total_task FROM tasks_details WHERE tasks_details.in_charge = '$username' AND tasks_details.due_date>='$val_from' AND tasks_details.due_date<='$val_to' AND tasks_details.task_status=1 AND tasks_details.date_accomplished IS NOT NULL AND tasks_details.task_class!=3");
      $count_task_row = $count_task->fetch_assoc();
      $total_task = $count_task_row['total_task'];
      if ($total_task == '0') {
        $total_task = 'No';
        echo "<tr>                                                               
        <td style='text-align: justify'> <img src=" . $imageURL . " title=" . $username . " style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-right: 45px'>" . $emp_name . "</td>
        <td><center />" . $formatted_num . '<br>' . $rate . "</td>
        <td><center /> " . $total_task . ' ' . $label . "</td>
        </tr>";
      }

      else {
        $donetotal = 0;
        $tasktotal = 0;
        $totavg    = 0;
        $donesum   = 0;
        $latedone  = 0;
        $resdone   = 0;
        $remtask   = 0;
        $ftask     = 0;
        $three     = 0;
        $two       = 0;
        $one       = 0;
        $zero      = 0;
        $avg_task = mysqli_query($con, "SELECT * FROM tasks_details JOIN task_class ON tasks_details.task_class = task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE in_charge='$username' AND task_status!=0 AND tasks_details.due_date>='$val_from' AND tasks_details.due_date<='$val_to' AND tasks_details.task_class!=3");
        if (mysqli_num_rows($avg_task) > 0) {
          while ($row = $avg_task->fetch_assoc()) {
            $achievement = $row['achievement'];

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
          }
        }
        $donesum = ($three * 3) + ($two * 2) + ($one * 1) + ($zero * 0);
        $tasktotal = $remtask + $donetotal;
        if ($donesum != 0) {
          $totavg = $donesum / $tasktotal;
        }
        $formatted_number = number_format($totavg, 2);
        if ($formatted_number == 3) {
          $rate = '<span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star" style="color: yellow">';
        }
        elseif ($formatted_number >= 2.5) {
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
        <td style='text-align: justify'> <img src=" . $imageURL . " title=" . $username . " style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-right: 45px'>" . $emp_name . "</td>
        <td><center />" . $formatted_number . '<br>' . $rate . "</td>
        <td><center /><a href='performance_list.php?id=" . $username . "'> <button class='btn btn-sm btn-success'><i class='fas fa-eye'></i> View  " . $donetotal . ' ' . $label . "</button></a>" . "</td>
        </tr>";
      }
    }
  }
}
else {
  $val_from = $_POST['valfrom1'];
  $val_to = $_POST['valto1'];
  $section = $_POST['section1'];

  if ($val_to != 0) {
    $con->next_result();
    $result = mysqli_query($con, "SELECT * FROM accounts WHERE sec_id='$section' AND access=2");
    while ($row = $result->fetch_assoc()) {
      $emp_name = $row['fname'] . ' ' . $row['lname'];
      $username = $row["username"];
      $id = $row['card'];
      $label = 'Completed Task/s';
      $emp_avg = 0;
      if (empty($row["file_name"])) {
        // Use a default image URL
        $imageURL = '../assets/img/user-profiles/nologo.png';
      }
      else {
        // Use the image URL from the database
        $imageURL = '../assets/img/user-profiles/' . $row["file_name"];
      }
      $formatted_num = number_format($emp_avg, 2);
      $rate = '';
      $count_task = mysqli_query($con, "SELECT COUNT(tasks_details.task_code) as total_task FROM tasks_details WHERE tasks_details.in_charge = '$username' AND tasks_details.due_date>='$val_from' AND tasks_details.due_date<='$val_to' AND tasks_details.task_status=1 AND tasks_details.task_class=3 AND tasks_details.date_accomplished IS NOT NULL");
      $count_task_row = $count_task->fetch_assoc();
      $total_task = $count_task_row['total_task'];
      if ($total_task == '0') {
        $total_task = 'No';
        echo "<tr>                                                               
        <td style='text-align: justify'> <img src=" . $imageURL . " title=" . $username . " style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-right: 45px'>" . $emp_name . "</td>
        <td><center />" . $formatted_num . '<br>' . $rate . "</td>
        <td><center /> " . $total_task . ' ' . $label . "</td>
        </tr>";
      }

      else {
        $donetotal = 0;
        $tasktotal = 0;
        $totavg    = 0;
        $donesum   = 0;
        $latedone  = 0;
        $resdone   = 0;
        $remtask   = 0;
        $ftask     = 0;
        $three     = 0;
        $two       = 0;
        $one       = 0;
        $zero      = 0;
        $avg_task = mysqli_query($con, "SELECT * FROM tasks_details JOIN task_class ON tasks_details.task_class = task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE in_charge='$username' AND task_status!=0 AND tasks_details.due_date>='$val_from' AND tasks_details.due_date<='$val_to' AND tasks_details.task_class=3");
        if (mysqli_num_rows($avg_task) > 0) {
          while ($row = $avg_task->fetch_assoc()) {
            $achievement = $row['achievement'];

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
          }
        }
        $donesum = ($three * 3) + ($two * 2) + ($one * 1) + ($zero * 0);
        $tasktotal = $remtask + $donetotal;
        if ($donesum != 0) {
          $totavg = $donesum / $tasktotal;
        }
        $formatted_number = number_format($totavg, 2);
        if ($formatted_number == 3) {
          $rate = '<span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star" style="color: yellow">';
        }
        elseif ($formatted_number >= 2.5) {
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
        <td style='text-align: justify'> <img src=" . $imageURL . " title=" . $username . " style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-right: 45px'>" . $emp_name . "</td>
        <td><center />" . $formatted_number . '<br>' . $rate . "</td>
        <td><center /><a href='performance_list.php?id=" . $username . "'> <button class='btn btn-sm btn-success'><i class='fas fa-eye'></i> View  " . $donetotal . ' ' . $label . "</button></a>" . "</td>
        </tr>";
      }
    }
  }
}
?>

<script>
  $(document).ready(function(){$('#table_task').DataTable({responsive:true,destroy:true,"order":[[1,"desc"],[2,"asc"]]})});
</script>