<?php 
include('../include/connect.php');


if(isset($_POST['sid'])){
  $username = $_POST['sid'];
  $con->next_result();
  $query = "SELECT tasks.task_code, task_list.task_name FROM tasks LEFT JOIN task_list ON task_list.task_code=tasks.task_code WHERE tasks.in_charge='$username'";
  $result=mysqli_query($con, $query);
  if(mysqli_num_rows($result)>0) {
    echo '<option disabled selected value="">---SELECT TASKS---</option>';
      while($row = mysqli_fetch_assoc($result)) {
          $data = array($row['task_code']=>$row['task_name']);
          
          // Initialize an empty array to store the grouped options
          $groupedOptions = array();
  
          // Loop through the data to group the options
          foreach ($data as $code => $name) {
              $groups = substr($code, -9, 2); // Get the group identifier (e.g., TD, TA, TP)
              if ($groups=='TD') {
                  $group='Daily Routine';
              } else if ($groups=='TA') {
                  $group='Additional Task';
              } else if ($groups=='TP') {
                  $group='Project';
              }
              if (!isset($groupedOptions[$group])) {
                  $groupedOptions[$group] = array();
              }
              $groupedOptions[$group][$code] = $name; // Add the option to the corresponding group
          }
  
          
          // Output the grouped options as HTML select element
          foreach ($groupedOptions as $group => $options) {
              echo '<optgroup label="' . $group . '">';
              foreach ($options as $code => $name) {
                  echo '<option value="' . $code . '">' . $code . ' | ' . $name . '</option>';
              }
              echo '</optgroup>';
          }
      }
  }   
  }


?>