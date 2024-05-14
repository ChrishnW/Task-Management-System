<?php
$today = date('Y-m-d');
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=UNREGISTERED TASKS SUMMARY_" . $today . ".xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);

include('../include/auth.php');
include('../include/connect.php');
?>
<body>
	<center>
		<b>TASK MANAGEMENT SYSTEM</b>
		<br>
	</center>
	<br>
	<div id="table-scroll">
		<table width="100%" border="1" align="left">
			<thead>
				<tr>
					<th> <center />Task Name </th>
					<th> <center />Task Class </th>
					<th> <center />Task Details </th>
					<th> <center />Task For </th>
					<th> <center />In Charge </th>
					<th> <center />Submission </th>
					<th> <center />Result </th>
				</tr>
			</thead>
			<tbody>
				<?php
				$result = mysqli_query($con, "SELECT * FROM task_temp");
				while ($row = mysqli_fetch_array($result)) {
					echo "<tr>                                                       
						<td><center />" . $row["task_name"] . "</td>
						<td><center />" . $row["task_class"] . "</td>
						<td><center />" . $row['task_details'] . "</td>
						<td><center />" . $row["task_for"] . "</td> 
						<td><center />" . $row["in_charge"] . "</td> 
						<td><center />" . $row["submission"] . "</td>
						<td><center />" . $row["status"] . "</td>
					</tr>";
				}?>
			</tbody>
		</table>
	</div>
</body>