<?php
	$today = date('Y-m-d');
	$status = $_GET['status'];
  $dept_id = $_GET['dept_id'];

	header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
	header("Content-Disposition: attachment; filename=".$status." TASKS-SUMMARY_".$today.".xls");  //File name extension was wrong
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	
	include('../include/auth.php');
	include('../include/connect.php');
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<body>
		<center>
			<b>
			<font color="blue">GLORY (PHILIPPINES), INC.</font>
			</b>
			<br>
			<b>TASK MANAGEMENT SYSTEM</b>
			<br>
			<h3> <b><?php echo $status ?> TASKS SUMMARY</b></h3>
		</center>
		<div id="table-scroll">
			<table width="100%" border="1" align="left">
				<thead>
					<tr>
						<th> Task Code </th>
						<th> * </th>
						<th> Task Name </th>
						<th> Task Classification </th>
						<th> In-charge </th>
						<?php
							if ($status != 'FINISHED'){
							  echo '<th> Due Date </th>';
							}
            ?>
						<th> Status </th>
						<?php
							if ($status == "FINISHED"){
							echo "
                <th> Date Accomplished </th>
                <th> Score </th>";
							}
							?>
					</tr>
				</thead>
				<tbody>
					<?php
						$con->next_result();
            $result = mysqli_query($con,"SELECT * FROM tasks_details JOIN task_class ON tasks_details.task_class = task_class.id JOIN accounts ON tasks_details.in_charge=accounts.username JOIN section ON section.sec_id=tasks_details.task_for WHERE tasks_details.task_status=1 AND tasks_details.approval_status='0' AND tasks_details.reschedule=0 AND section.dept_id='$dept_id' AND tasks_details.status='$status'");               
						while($row = mysqli_fetch_array($result)) {
              echo "
                <tr>
                  <td><center />" . $row["task_code"] . "</td>";
              if ($row["requirement_status"] == "1") {
              echo "<td><center /> 📎 </td>";
              }
              else {
              echo "<td> </td>";
              }
              echo"
                  <td><center />" . $row["task_name"] . "</td>
                  <td><center />" . $row["task_class"] . "</td>
                  <td><center />" . $row["fname"].' '.$row["lname"] . "</td>
                  <td><center />" . $row["due_date"] . "</td>
                  <td><center />" . $status . "</td>";
              if ($status == "FINISHED") {
              echo"
                <td><center />" . $row["date_accomplished"] . "</td>
                <td><center />". $row['achievement'] ."</td>";
              }
              echo"
              </tr> ";
						} 
						?>
				</tbody>
			</table>
		</div>
	</body>
</html>