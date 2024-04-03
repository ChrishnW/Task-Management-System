<?php 
	include('../include/header.php');
	include('../include/connect.php');
	?>
<html>
	<head>
		<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
		<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
		<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
		<title>List of Departments</title>
	</head>
	<style> </style>
	<div id="content" class="p-4 p-md-5 pt-5">
		<div id="wrapper">
			<div id="page-wrapper">
				<br>
				<h1 class="page-header">List of Departments
				</h1>
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								List of Departments
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<a href="department_add.php"> <button class='btn btn-success  pull-right'><i class="fa fa-plus"></i> Register New Department</button></a><br><br><br>
									<table width="100%" class="table table-striped table-hover " id="table_department">
										<thead>
											<tr>
												<th class="col-lg-1">
													<center />
													Action
												</th>
												<th class="col-lg-2">
													<center />
													Department Code
												</th>
												<th class="col-lg-2">
													<center />
													Department Name
												</th>
												<th class="col-lg-1">
													<center />
													Status
												</th>
											</tr>
										</thead>
										<tbody id="show_department">
											<?php
												/* and access!='1' */
												$con->next_result();
												$result = mysqli_query($con,"SELECT * FROM department");               
												if (mysqli_num_rows($result)>0) { 
												    while ($row = $result->fetch_assoc()) {
												        echo "<tr>    
												            <td> <center /><a href='department_edit.php?id=".$row['id']."' <button class='btn btn-primary' ><i class='fa fa-edit fa-1x'></i> Edit</button></a> </td> 
												            <td>" . $row["dept_id"] . "</td> 
												            <td>" . $row["dept_name"] . "</td> 
												            <td><center/>" .($row['status']=='1' ? '<p class="label label-success" style="font-size:100%;">ACTIVE</p>' : '<p class="label label-danger" style="font-size:100%;">INACTIVE</p>' ). "</td>
												        </tr>";
												    }
												} 
												if ($con->connect_error) {
												    die("Connection Failed".$con->connect_error); }; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
		    $('#table_department').DataTable({
		        responsive: true,
		        'order': [[ 1, 'asc' ]]
		    });
		});
	</script>
</html>