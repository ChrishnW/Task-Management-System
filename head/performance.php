<?php
  include "../include/header_head.php";
  include "../include/connect.php";
  $today = date("Y-m-d");
  $month = date("m");
  $year = date("Y");
  $monthname = date("F");
  $formatted_num = 0;
  $section = isset($_GET["section"]) ? $_GET["section"] : die("ERROR: Record not found.");
  $con->next_result();
  $query = mysqli_query($con, "SELECT * FROM accounts WHERE username='$username'");
  if (mysqli_num_rows($query) > 0) {
    while ($row = $query->fetch_assoc()) {
      // Check if file_name is empty
      if (empty($row["file_name"])) {
        // Use a default image URL
        $imageURL = "../assets/img/user-profiles/nologo.png";
      }
      else {
        // Use the image URL from the database
        $imageURL = "../assets/img/user-profiles/" . $row["file_name"];
      }
    }
  }
?>
<html>
	<head>
		<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
		<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
		<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
		<link href="../assets/css/darkmode.css" rel="stylesheet">
		<title>
			Staff Performace
		</title>
	</head>
	<div id="content" class="p-4 p-md-5 pt-5">
		<div id="wrapper">
			<div id="page-wrapper">
				<h1 class="page-header">
					<?php echo $section ?>
						Staff Performace
				</h1>
					<?php
          if (isset($_GET['monthly'])){
            echo "
            <form method='POST' action='performance_report.php?section=$section&monthly=TRUE'>
            <div class='col-lg-2 pull-left'>
              <label> Date From: </label>
              <br>
              <input type='date' class='form-control' name='val_from' id='val_from' onchange='selectfrom1(this)'>
            </div>
            <div class='col-lg-2 pull-left'>
              <label> Date To: </label>
              <br>
              <input type='date' class='form-control' name='val_to' id='val_to' onchange='selectto1(this)'>
              <br>
            </div>";
          }
          else {
            echo "
            <form method='POST' action='performance_report.php?section=$section'>
            <div class='col-lg-2 pull-left'>
              <label> Date From: </label>
              <br>
              <input type='date' class='form-control' name='val_from' id='val_from' onchange='selectfrom(this)'>
            </div>
            <div class='col-lg-2 pull-left'>
              <label> Date To: </label>
              <br>
              <input type='date' class='form-control' name='val_to' id='val_to' onchange='selectto(this)'>
              <br>
            </div>";
          }
          ?>
					<button class="btn btn-success pull-left" id="submit" style="margin-top: 25px; display: none;">
						<span class="fa fa-download fa-fw">
						</span>
						Download PDF
					</button>
				</form>
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<?php echo $section ?>
									Staff Performace
							</div>
							<div class="panel-body">
								<table width="100%" class="table table-striped table-hover" id="table_task">
									<thead>
										<tr>
											<th class="col-lg-3"> <center> Employee </center> </th>
											<th class="col-lg-3"> <center> Task Performance </center> </th>
                      <th class="col-lg-3"> <center> Monthly Report </center> </th>
											<th class="col-lg-3"> <center> Completed Tasks </center> </th>
										</tr>
									</thead>
									<tbody id="show_task">
                    <?php
                      $con->next_result();
                      $result = mysqli_query($con, "SELECT * FROM accounts WHERE sec_id='$section' AND access=2");
                      while ($row = $result->fetch_assoc()) {
                        $emp_name = $row["fname"] . " " . $row["lname"];
                        $username = $row["username"];
                        $id       = $row["card"];
                        $label    = "Completed Task/s";
                        $emp_avg  = 0;
                        if (empty($row["file_name"])) {
                          // Use a default image URL
                          $imageURL = "../assets/img/user-profiles/nologo.png";
                        }
                        else {
                          // Use the image URL from the database
                          $imageURL = "../assets/img/user-profiles/" . $row["file_name"];
                        }
                        $formatted_num = number_format($emp_avg, 2);
                        $rate = "";
                        $count_task = mysqli_query($con, "SELECT COUNT(tasks_details.task_code) as total_task FROM tasks_details WHERE tasks_details.in_charge = '$username' AND MONTH(tasks_details.due_date)='$month' AND YEAR(tasks_details.due_date)='$year' AND tasks_details.task_status=1 AND tasks_details.date_accomplished IS NOT NULL");
                        $count_task_row = $count_task->fetch_assoc();
                        $total_task = $count_task_row["total_task"];
                        if ($total_task == "0") {
                          $total_task = "No";
                          echo "<tr> <td style='text-align: justify'> <img src=" . $imageURL . " class='profile' title=" . $username . " style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-right: 45px'>" . $emp_name . "</td> <td><center />" . $formatted_num . "<br>" . $rate . "</td> <td><center /> " . $total_task . " " . $label . "</td> </tr>";
                        }
                        else {
                          $m_remtask = 0; $m_donetotal = 0; $m_three = 0; $m_two = 0; $m_one = 0; $m_zero = 0; $m_donesum = 0; $m_tasktotal = 0; $m_totavg = 0; $monthly = 0;
                          $remtask = 0; $donetotal = 0; $three = 0; $two = 0; $one = 0; $zero = 0; $donesum = 0; $tasktotal = 0; $totavg = 0; $formatted_number = 0;
                          $avg_task = mysqli_query($con, "SELECT * FROM tasks_details JOIN task_class ON tasks_details.task_class = task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE in_charge='$username' AND task_status!=0 AND MONTH(tasks_details.due_date)='$month' AND YEAR(tasks_details.due_date)='$year'");
                          if (mysqli_num_rows($avg_task) > 0) {
                            while ($row = $avg_task->fetch_assoc()) {
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
                            <td style='text-align: justify'> <img src=" . $imageURL . " class='profile' title=" . $username . " style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-right: 45px'>" . $emp_name . "</td>
                            <td><center />" . $formatted_number . "</td>
                            <td><center />" . $monthly . "</td>
                            <td><center /><a href='performance_list.php?id=" . $username . "'> <button class='btn btn-sm btn-success'><i class='fas fa-eye'></i> View  " . $fdonetotal . " " . $label . "</button></a>" . "</td>
                          </tr>";
                        }
                      }
                    ?>
									</tbody>
								</table>
							</div>
						</div>
						<a href="#" onclick="history.back()">
							<button class='btn btn-danger pull-left'>
								<i class="fa fa-arrow-left">
								</i>
								Back
							</button>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			$('input[type="date"]').change(function() {
				if ($('#val_from').val() != '' && $('#val_to').val() != '') {
					$('#submit').show();
				} else {
					$('#submit').hide();
				}
			});
		});
	</script>
	<script>
		$(document).ready(function() {
			$('#table_task').DataTable({
				responsive: true,
				"order": [[1, "desc"], [2, "asc"]] // This will sort first by column 1 in descending order, then by column 2 in ascending order.
			});
		});
	</script>
  
	<script>
		function selectfrom(element) {
			let valfrom = $(element).val();
			let section = <?php echo json_encode($section) ?>;
			let valto = $('#val_to').val();
			$('#table_task').DataTable().destroy();
			$('#show_task').empty();
			if (valfrom) {
				$.ajax({
					type: "post",
					url: "performance_ajax_valfrom.php",
					data: {
						"valfrom": valfrom,
						"section": section,
						"valto": valto
					},
					success: function(response) {
						$('#show_task').append(response);
						$('#table_task').DataTable();
					}
				});
			}
		}
	</script>
	<script>
		function selectto(element) {
			let valto = $(element).val();
			let section = <?php echo json_encode($section) ?>;
			let valfrom = $('#val_from').val();
			$('#table_task').DataTable().destroy();
			$('#show_task').empty();
			if (valto) {
				$.ajax({
					type: "post",
					url: "performance_ajax_valto.php",
					data: {
						"valfrom": valfrom,
						"section": section,
						"valto": valto
					},
					success: function(response) {
						$('#show_task').append(response);
						$('#table_task').DataTable();
					}
				});
			}
		}
	</script>
</html>