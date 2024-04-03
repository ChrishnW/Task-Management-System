<?php 
	include('../include/header.php');
	include('../include/connect.php');
	?>
<html>
	<head>
		<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
		<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
		<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
		<link href="../assets/css/darkmode.css" rel="stylesheet">
		<title>Staff Performace</title>
	</head>
	<div id="content" class="p-4 p-md-5 pt-5">
		<div id="wrapper">
			<div id="page-wrapper">
				<h1 class="page-header"> G-TMS System Logs </h1>
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								Task Management System
							</div>
							<div class="panel-body">
								<table class="table table-striped table-hover" id="table_task">
									<thead>
										<tr>
											<th class="col">
												<center>Date</center>
											</th>
											<th class="col">
												<center>User</center>
											</th>
											<th class="col">
												<center>Action</center>
											</th>
										</tr>
									</thead>
									<tbody id="show_task">
										<?php
											$con->next_result();
											$result = mysqli_query($con,"SELECT * FROM system_log");
											while ($row = $result->fetch_assoc()) {       
                        $date = date('Y-m-d H:i:s', strtotime($row['date_created']));
                        echo "
                        <tr>
                        <td><center />" . $date . "</td>
                        <td><center />" . $row['user'] . "</td>
                        <td><center />" . $row['action'] . "</td>
                        </tr>";
											}
											?>
									</tbody>
								</table>
							</div>
						</div>
						<a href="#" onclick="history.back()"> <button class='btn btn-danger pull-left'><i class="fa fa-arrow-left"></i> Back</button></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
		$('#table_task').DataTable({
		responsive: true,
		"order": [[ 0, "desc" ]] // This will sort first by column 1 in descending order, then by column 2 in ascending order.
		});
		});
	</script>
</html>