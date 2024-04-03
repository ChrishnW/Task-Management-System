<?php 
	include('../include/header.php');
	include('../include/connect.php');
	?>
<html>
	<head>
		<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
		<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
		<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
		<title>List of Sections</title>
	</head>
	<div id="content" class="p-4 p-md-5 pt-5">
		<div id="wrapper">
			<div id="page-wrapper">
				<br>
				<h1 class="page-header">List of Sections
					<a href="section_xls.php"> <button class="btn btn-success pull-right" style="margin-top: 95px"><span class="fa fa-download fa-fw"></span> Download List</button></a>
				</h1>
				<div class="row">
					<div class="col-lg-4">
						<label>Department:</label><br>
            <select name="dept" id="dept" class="form-control selectpicker show-menu-arrow" data-live-search="true" onchange="selectdepartment(this)">
            <option disabled selected value="">Select Department</option>
              <?php
              $sql = mysqli_query($con,"SELECT * FROM department"); 
              $con->next_result();
              if(mysqli_num_rows($sql)>0){
                  while($row=mysqli_fetch_assoc($sql)){
                      $dept_id1 = $row['dept_id'];
                      $dept_name1 = $row['dept_name'];
                      echo "<option value='".$dept_id1."'>".$dept_name1."</option>";
                  }
              } ?>
            </select>
						<br>
						<br>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								List of Sections
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<a href="section_add.php"> <button class='btn btn-success  pull-right'><i class="fa fa-plus"></i> Register New Section</button></a><br><br><br>
									<table width="100%" class="table table-striped table-hover " id="table_section">
										<thead>
											<tr>
												<th class="col-lg-1">
													<center />
													Action
												</th>
												<th class="col-lg-2">
													<center />
													Section Name
												</th>
												<th class="col-lg-2">
													<center />
													Section ID
												</th>
												<th class="col-lg-2">
													<center />
													Department
												</th>
												<th class="col-lg-1">
													<center />
													Status
												</th>
											</tr>
										</thead>
										<tbody id="show_section">
											<?php
												/* and access!='1' */
												$con->next_result();
												$result = mysqli_query($con,"SELECT section.id, section.sec_id, section.sec_name, department.dept_name , section.status FROM section LEFT JOIN department ON department.dept_id=section.dept_id");               
												if (mysqli_num_rows($result)>0) { 
												    while ($row = $result->fetch_assoc()) {
												        echo "<tr>    
												            <td> <center /><a href='section_edit.php?id=".$row['id']."' <button class='btn btn-primary' ><i class='fa fa-edit fa-1x'></i> Edit</button></a> </td> 
												            <td>" . $row["sec_name"] . "</td> 
												            <td>" . $row["sec_id"] . "</td> 
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
		    $('#table_section').DataTable({
		        responsive: true,
		        'order': [[ 1, 'asc' ]]
		    });
		});
	</script>
	<script>
		function selectdepartment(element) {
		    let sid = $(element).val();
		    $('#table_section').DataTable().destroy();
		    $('#show_section').empty();
		    if (sid) {
		        $.ajax({
		            type: "post",
		            url: "section_ajax.php",
		            data: {
		                "sid": sid
		            },
		            success: function(response) {
		                $('#show_section').append(response);
		                $('#table_section').DataTable();
		            }
		        });
		    }
		}
	</script>
</html>