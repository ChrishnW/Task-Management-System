<?php 
include('../include/connect.php');

if(isset($_POST['sid2'])){
$ID2 = $_POST['sid2'];
$con->next_result();
$query = "SELECT * FROM task_list WHERE task_for='$ID2' AND status is TRUE GROUP BY task_list.task_name ORDER BY task_list.id DESC";

$result=mysqli_query($con, $query);
if(mysqli_num_rows($result)>0) {
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
            } else if ($groups=='TM') {
              $group='Monthly Routine';
            } else if ($groups=='TW') {
              $group='Weekly Routine';
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