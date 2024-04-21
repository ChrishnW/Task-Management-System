<?php 
include ('../include/connect.php');
$date_today = date('Y-m-d');
$month = date('m');
$year = date('Y');

if(isset($_POST['valto'])){
  $val_from = $_POST['valfrom'];
  $val_to = $_POST['valto'];
  $status = $_POST['status'];
  $username = $_POST['username'];

  if($val_from != 0){
    $con->next_result();
    if ($status == "NOT YET STARTED") {
      $result = mysqli_query($con, "SELECT *, (SELECT DISTINCT date FROM attendance WHERE card=accounts.card and date = tasks_details.due_date) AS loggedin FROM tasks_details JOIN accounts ON tasks_details.in_charge=accounts.username JOIN task_class on task_class.id=tasks_details.task_class WHERE tasks_details.in_charge='$username' AND tasks_details.task_status=1 AND tasks_details.reschedule=0 AND tasks_details.status='$status' AND tasks_details.due_date>='$val_from' AND tasks_details.due_date<='$val_to'");
      if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
          $today      = date("Y-m-d");
          $due_date   = $row["due_date"];
          $due        = date('d-m-Y h:i A', strtotime($row['due_date'].'16:00:00'));
          $nextDate   = date('Y-m-d', strtotime($due_date . ' + ' . 1 . ' days'));
          $yesterday  = date('Y-m-d', strtotime($today . ' -' . 1 . ' days'));
          $twodago    = date('Y-m-d', strtotime($due_date . ' +' . 2 . ' days'));
          $task_class = $row['task_class'];
          $class      = "";
          $sign       = "";
          $emp_name   = $row['fname'] . ' ' . $row['lname'];
          
          if ($status == "NOT YET STARTED") {
            if ($due_date > $today) {
              $class_label = "info";
              $sign        = "PENDING";
            }
            elseif ($due_date == $today) {
              $class_label = "primary";
              $sign        = "NOT YET STARTED";
            }
            elseif ($yesterday <= $today && $row["loggedin"] == $due_date) {
              $class_label = "primary";
              $sign        = "NOT YET STARTED";
            }
            else {
              $class_label = "danger";
              $sign        = "EXPIRED";
              $class       = "invalid";
            }
          }
          
          echo "<tr>";
          if ($due_date == $today){
            echo "<td class='". $class ."'> <input type='checkbox' class='messageCheckbox' name='item[]' id='flexCheckDefault' value='".$row['task_code']."'/> </td>";
          }
          else{
            echo "<td class='". $class ."'> <i class='fa fa-ban'></i> </td>";
          }
          echo "
          <td class='" . $class . "'>" . $row["task_code"] . " </td>";
          if ($row['requirement_status'] == 1) {
            echo "<td class='" . $class . "'> <span style='color: #00ff26'><i class='fa fa-paperclip' title='Attachment Required'></i></span></td>";
          }
          else {
            echo "<td class='" . $class . "'> </td>";
          }
          echo "                                                
          <td id='normalwrap' class='" . $class . "'> " . $row["task_name"] . " </td>
          <td class='" . $class . "'>" . $row["task_class"] . "</td>
          <td class='" . $class . "'>" . $due . "</td>
          <td class='" . $class . "' style='text-align: center'> <img src=" . $imageURL . " title=" . $row["username"] . " style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-left: 0'></td>  
          <td class='" . $class . "'><center/><p class='label label-" . $class_label . "' style='font-size:100%;'>" . $sign . "</p></td>";
          if ($due_date == $today) {
            echo " <td> <center/><button id='task_id' value='" . $row['task_code'] . "' class='btn btn-primary' onclick='start(this)'><i class='fa fa-play-circle fa-1x'></i> </button></td>";
          }
          elseif ($due_date > $today) {
            echo " <td> <center/><button disabled id='task_id' value='" . $row['task_code'] . "' class='btn btn-info' onclick='start(this)'><i class='fas fa-clock fa-1x'></i> </button></td>";
          }
          elseif ($yesterday <= $today && $row["loggedin"] == $due_date) {
            echo " <td> <center/><button id='task_id' value='" . $row['task_code'] . "' class='btn btn-primary' onclick='start(this)'><i class='fa fa-play-circle fa-1x'></i> </button></td>";
          }
          else {
            echo " <td class='" . $class . "'> <center/><button disabled id='task_id' value='" . $row['task_code'] . "' class='btn btn-danger' onclick='start(this)'><i class='fa fa-exclamation-circle fa-1x'></i> </button></td>";
          }
          echo "</tr>";
        }
      }
    }
    else if ($status == "IN PROGRESS") {
      $result = mysqli_query($con, "SELECT * FROM tasks_details JOIN accounts ON tasks_details.in_charge=accounts.username JOIN task_class on task_class.id=tasks_details.task_class WHERE in_charge='$username' AND tasks_details.status='$status' AND tasks_details.task_status=1 AND tasks_details.due_date>='$val_from' AND tasks_details.due_date<='$val_to'");
      if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
          $due_date   = $row["due_date"];
          $due        = date('d-m-Y h:i A', strtotime($row['due_date'].'16:00:00'));
          $verify     = $row['requirement_status'];
          $twodago    = date('Y-m-d', strtotime($due_date . ' +' . 2 . ' days'));
          $today      = date('Y-m-d');
          $class      = '';
          $task_class = $row['task_class'];
          $emp_name   = $row['fname'] . ' ' . $row['lname'];
          if (empty($row["file_name"])) {
            // Use a default image URL
            $imageURL = '../assets/img/user-profiles/nologo.png';
          } 
          else {
            // Use the image URL from the database
            $imageURL = '../assets/img/user-profiles/' . $row["file_name"];
          }
          if ($today > $due_date) {
            $class       = "invalid";
            $sign        = "OVERDUE";
            $class_label = "danger";
          }
          else {
            $sign        = "IN PROGRESS";
            $class_label = "warning";
          }
          echo "<tr>
          <td class='" . $class . "'> " . $row["task_code"] . " </td>";
          if ($row['requirement_status'] == 1) {
            echo "<td class='" . $class . "'> <span style='color: #00ff26'><i class='fa fa-paperclip' title='Attachment Required'></i></span></td>";
          } 
          else {
            echo "<td class='" . $class . "'> </td>";
          }
          echo "                                                    
          <td id='normalwrap' class='" . $class . "'> " . $row["task_name"] . " </td>  
          <td class='" . $class . "'>" . $row["task_class"] . "</td>  
          <td class='" . $class . "'>" . $due . "</td> 
          <td class='" . $class . "' style='text-align: center'> <img src=" . $imageURL . " title=" . $row["username"] . " style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-left: 0'></td>  
          <td class='" . $class . "'><center/>
          <p class='label label-" . $class_label . "' style='font-size:100%;'>" . $sign . "</p></td>";
          if ($verify == 1) {
            echo "
            <td class='" . $class . "'> <center/><button id='task_id' value='" . $row['task_code'] . "' class='btn btn-danger' onclick='finish_with_attachment(this)'><i class='fa fa-stop fa-1x'></i></button>
            </td>
            </tr>";
          }
          else {
            echo "
            <td class='" . $class . "'> <center/><button id='task_id' value='" . $row['task_code'] . "' class='btn btn-danger' onclick='finish_without_attachment(this)'><i class='fa fa-stop fa-1x'></i></button>
            </td>
            </tr>";
          }
        }
      }
    }
    else if ($status == "FINISHED") {
      $result = mysqli_query($con, "SELECT * FROM tasks_details JOIN task_class ON tasks_details.task_class = task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE in_charge='$username' AND tasks_details.status='$status' AND tasks_details.task_status=1 AND tasks_details.approval_status=0 AND tasks_details.due_date>='$val_from' AND tasks_details.due_date<='$val_to'");
      if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
          $achievement = $row['achievement'];
          $emp_name    = $row['fname'] . ' ' . $row['lname'];
          $date        = date('m / d / Y h:i:s A', strtotime($row['date_accomplished']));
          if (empty($row["file_name"])) {
            // Use a default image URL
            $imageURL = '../assets/img/user-profiles/nologo.png';
          } 
          else {
            // Use the image URL from the database
            $imageURL = '../assets/img/user-profiles/' . $row["file_name"];
          }
          if ($status == 'FINISHED' && $achievement != 0) {
            $class_label = "success";
            $sign        = "FINISHED";
          }
          if ($status == 'FINISHED' && $achievement == 0) {
            $class_label = "danger";
            $sign        = "FAILED";
          }
          echo "<tr>
          <td> " . $row["task_code"] . " </td>";
          if ($row['requirement_status'] == 1) {
            echo "<td> <span style='color: #00ff26'><i class='fa fa-paperclip' title='Attachment Required'></i></span></td>";
          } 
          else {
            echo "<td> </td>";
          }
          echo "                                                  
          <td id='normalwrap'> " . $row["task_name"] . " </td>                                                            
          <td>" . $row["task_class"] . "</td>  
          <td>" . $date . "</td>
          <td style='text-align: center'> <img src=" . $imageURL . " title=" . $row["username"] . " style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-left: 0'></td>  
          <td><center/><p class='label label-" . $class_label . "' style='font-size:100%;'>" . $sign . "</p></td>
          <td><center />" . $achievement . "</td>
          <td><center><button value='" . $row['task_code'] . "' data-name='" . $row['task_name'] . "' data-class='" . $row['task_class'] . "' data-remarks='" . $row['remarks'] . "' data-duedate='" . $row['due_date'] . "' data-datefinish='" . $row['date_accomplished'] . "' data-achievement='" . $row['achievement'] . "' data-file='" . $row['requirement_status'] . "' data-path='" . $row['attachment'] . "' data-note='" . $row['head_note'] . "' data-head='" . $row['head_name'] . "' class='btn btn-primary' onclick='view1(this)'><span class='fa fa-folder-open'></span> View </button></center></td> 
          </tr>";
        }
      }
    }
    else if ($status == "VERIFICATION") {
      $result = mysqli_query($con, "SELECT * FROM tasks_details LEFT JOIN task_list ON tasks_details.task_name = task_list.task_name LEFT JOIN task_class ON task_list.task_class = task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE in_charge='$username' AND tasks_details.status='FINISHED' AND tasks_details.achievement!=0 AND tasks_details.task_status=1 AND tasks_details.approval_status=1 AND tasks_details.due_date>='$val_from' AND tasks_details.due_date<='$val_to'");
      if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
          $achievement = $row['achievement'];
          $emp_name    = $row['fname'] . ' ' . $row['lname'];
          if (empty($row["file_name"])) {
            // Use a default image URL
            $imageURL = '../assets/img/user-profiles/nologo.png';
          } else {
            // Use the image URL from the database
            $imageURL = '../assets/img/user-profiles/' . $row["file_name"];
          }
          if ($status == 'VERIFICATION') {
            $class_label = "danger";
            $sign        = "REVIEWING";
          }
          echo "<tr>
          <td> " . $row["task_code"] . " </td>";
          if ($row['requirement_status'] == 1) {
            echo "<td> <span style='color: #00ff26'><i class='fa fa-paperclip' title='Attachment Required'></i></span></td>";
          }
          else {
            echo "<td> </td>";
          }
          echo "                                                  
          <td id='normalwrap'> " . $row["task_name"] . " </td>                                                            
          <td>" . $row["task_class"] . "</td> 
          <td>" . $row["date_accomplished"] . "</td>
          <td style='text-align: center'> <img src=" . $imageURL . " title=" . $row["username"] . " style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-left: 0'></td>  
          <td><center/><p class='label label-" . $class_label . "' style='font-size:100%;'>" . $sign . "</p></td>
          <td><center />" . $achievement . "</td>
          <td><center><button value='" . $row['task_code'] . "' data-name='" . $row['task_name'] . "' data-class='" . $row['task_class'] . "' data-remarks='" . $row['remarks'] . "' data-duedate='" . $row['due_date'] . "' data-datefinish='" . $row['date_accomplished'] . "' data-achievement='" . $row['achievement'] . "' data-file='" . $row['requirement_status'] . "' data-path='" . $row['attachment'] . "' class='btn btn-primary' onclick='view2(this)'><span class='fa fa-folder-open'></span> View </button></center></td> 
          </tr>";
        }
      }
    }
    else if ($status == "RESCHEDULE") {
      $result = mysqli_query($con, "SELECT * FROM tasks_details LEFT JOIN task_list ON tasks_details.task_name = task_list.task_name LEFT JOIN task_class ON task_list.task_class = task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE tasks_details.status='NOT YET STARTED' AND tasks_details.reschedule=1 AND in_charge='$username' AND tasks_details.due_date>='$val_from' AND tasks_details.due_date<='$val_to'");
      if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
          $achievement = $row['achievement'];
          $emp_name    = $row['fname'] . ' ' . $row['lname'];
          if (empty($row["file_name"])) {
            // Use a default image URL
            $imageURL = '../assets/img/user-profiles/nologo.png';
          } 
          else {
            // Use the image URL from the database
            $imageURL = '../assets/img/user-profiles/' . $row["file_name"];
          }
          if ($status == 'RESCHEDULE') {
            $class_label = "info";
            $sign        = "RESCHEDULE PENDING";
          }
          echo "<tr>
          <td> " . $row["task_code"] . " </td>";
          if ($row['requirement_status'] == 1) {
            echo "<td> <span style='color: #00ff26'><i class='fa fa-paperclip' title='Attachment Required'></i></span></td>";
          } 
          else {
            echo "<td> </td>";
          }
          echo "                                                      
          <td id='normalwrap'> " . $row["task_name"] . " </td>                                                            
          <td>" . $row["task_class"] . "</td>  
          <td>" . $row["due_date"] . "</td> 
          <td style='text-align: center'> <img src=" . $imageURL . " title=" . $row["username"] . " style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-left: 0'></td>  
          <td><center/><p class='label label-" . $class_label . "' style='font-size:100%;'>" . $sign . "</p></td>
          </tr>";
        }
      }
    }
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