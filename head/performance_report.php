<?php
  include('../include/connect.php');
  require('../vendor/fpdf/fpdf.php');

  if (isset($_GET['monthly'])){
    $section = $_GET['section'];
    $val_from = date('Y-m-d 00:00:00', strtotime($_POST['val_from']));
    $val_to = date('Y-m-d 23:00:00', strtotime($_POST['val_to']));
    if ($val_from > $val_to) {
      echo '
      <script>
        alert("Invalid Selected Date Range!")
        window.history.back();
      </script>
      ';
    }
    else {
      class PDF extends FPDF {
        function Header(){
          $section = $_GET['section'];
          $val_from = date('F d, Y', strtotime($_POST['val_from']));
          $val_to = date('F d, Y', strtotime($_POST['val_to']));
          $date = $val_from.' to '.$val_to;
          $this->SetFont('Arial','B',20);
          // $this->Cell(12);
          // $this->Image('../assets/img/gtms.jpg',10,10,10);
          $this->Cell(100,10,$section.' Monthly Performance Report',0,1);
          $this->SetFont('Arial','B',10);
          $this->Cell(100,10,$date,0,1);
          $this->Ln(5);

          $this->SetFont('Arial','B',10);
          $this->SetFillColor(55, 255, 0);
          $this->SetDrawColor(50,50,50);
          $this->Cell(90,5,'Employee',1,0,'C',true);
          $this->Cell(50,5,'Average',1,0,'C',true);
          $this->Cell(50,5,'Equivalent',1,1,'C',true);
        }
        function Footer(){
          $this->Cell(190,0,'','T',1,'',true);

          $this->SetY(-15);

          $this->SetFont('Arial','',8);

          $this->Cell(0,10,'Page '.$this->PageNo()." / {pages}", 0, 0, 'C');
        }
      }

      $pdf = new PDF('P', 'mm', "A4");  

      $pdf->AliasNbPages('{pages}');

      $pdf->SetAutoPageBreak(true,15);

      $pdf->AddPage();

      $pdf->SetFont('Arial', '', 10);
      $pdf->SetDrawColor(50,50,50);

      $con->next_result();
      $result = mysqli_query($con, "SELECT * FROM accounts WHERE sec_id='$section' AND access=2");
      while ($row = $result->fetch_assoc()) {
        $emp_name = $row["fname"] . " " . $row["lname"];
        $username = $row["username"];
        $id = $row["card"];
        $label = "Completed Task/s";
        $emp_avg = 0;
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
        $count_task = mysqli_query($con, "SELECT COUNT(tasks_details.task_code) as total_task FROM tasks_details WHERE tasks_details.in_charge = '$username' AND tasks_details.due_date>='$val_from' AND tasks_details.due_date<='$val_to' AND tasks_details.task_status=1 AND tasks_details.date_accomplished IS NOT NULL AND tasks_details.task_class=3");
        $count_task_row = $count_task->fetch_assoc();
        $total_task = $count_task_row["total_task"];
        if ($total_task == "0") {
          $pdf->Cell(90, 5, $emp_name, 'LR', 0, 'C');
          $pdf->Cell(50, 5, $formatted_num, 'LR', 0, 'C');
          $pdf->Cell(50, 5, '0 %', 'LR', 1, 'C');
        }
        else {
          $donetotal = 0;
          $tasktotal = 0;
          $totavg = 0;
          $donesum = 0;
          $latedone = 0;
          $resdone = 0;
          $remtask = 0;
          $ftask = 0;
          $three = 0;
          $two = 0;
          $one = 0;
          $zero = 0;
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

          if ($formatted_number != 0) {
            // Getting the Equivalent Percentage
            if ($formatted_number >= 2.90) {
              $percentage = 120;
              $equivalent = number_format($percentage, 2);
            }
            elseif ($formatted_number >= 2.75) {
              $difference = 2.75 - $formatted_number;
              $range = 119 - 105;
              $proportion = (($formatted_number - 2.75) * 100) / ((2.89 - 2.75) * 100);
              $percentage = 105 + ($proportion * $range);
              $equivalent = number_format($percentage, 2);
            }
            elseif ($formatted_number >= 2.50) {
              $difference = 2.50 - $formatted_number;
              $range = 104 - 95;
              $proportion = (($formatted_number - 2.50) * 100) / ((2.74 - 2.50) * 100);
              $percentage = 95 + ($proportion * $range);
              $equivalent = number_format($percentage, 2);
            }
            elseif ($formatted_number >= 1.80) {
              $difference = 1.80 - $formatted_number;
              $range = 94 - 80;
              $proportion = (($formatted_number - 1.80) * 100) / ((2.49 - 1.80) * 100);
              $percentage = 80 + ($proportion * $range);
              $equivalent = number_format($percentage, 2);
            }
            elseif ($formatted_number >= 0.01) {
              $percentage = 79;
              $equivalent = number_format($percentage, 2);
            }
          }
          else {
            $equivalent = 0.00;
          }

          $pdf->Cell(90, 5, $emp_name, 'LR', 0, 'C');
          $pdf->Cell(50, 5, $formatted_number, 'LR', 0, 'C');
          $pdf->Cell(50, 5, $equivalent . ' %', 'LR', 1, 'C');
        }
      }
      $pdf->Output();
    }
  }
  else{
    $section = $_GET['section'];
    $val_from = date('Y-m-d 00:00:00', strtotime($_POST['val_from']));
    $val_to = date('Y-m-d 23:00:00', strtotime($_POST['val_to']));
    if ($val_from > $val_to) {
      echo '
      <script>
        alert("Invalid Selected Date Range!")
        window.history.back();
      </script>
      ';
    }
    else {
      class PDF extends FPDF {
        function Header(){
          $section = $_GET['section'];
          $val_from = date('F d, Y', strtotime($_POST['val_from']));
          $val_to = date('F d, Y', strtotime($_POST['val_to']));
          $date = $val_from.' to '.$val_to;
          $this->SetFont('Arial','B',20);
          // $this->Cell(12);
          // $this->Image('../assets/img/gtms.jpg',10,10,10);
          $this->Cell(100,10,$section.' Task Performance Report',0,1);
          $this->SetFont('Arial','B',10);
          $this->Cell(100,10,$date,0,1);
          $this->Ln(5);

          $this->SetFont('Arial','B',10);
          $this->SetFillColor(55, 255, 0);
          $this->SetDrawColor(50,50,50);
          $this->Cell(90,5,'Employee',1,0,'C',true);
          $this->Cell(50,5,'Average',1,0,'C',true);
          $this->Cell(50,5,'Equivalent',1,1,'C',true);
        }
        function Footer(){
          $this->Cell(190,0,'','T',1,'',true);

          $this->SetY(-15);

          $this->SetFont('Arial','',8);

          $this->Cell(0,10,'Page '.$this->PageNo()." / {pages}", 0, 0, 'C');
        }
      }

      $pdf = new PDF('P', 'mm', "A4");  

      $pdf->AliasNbPages('{pages}');

      $pdf->SetAutoPageBreak(true,15);

      $pdf->AddPage();

      $pdf->SetFont('Arial', '', 10);
      $pdf->SetDrawColor(50,50,50);

      $con->next_result();
      $result = mysqli_query($con, "SELECT * FROM accounts WHERE sec_id='$section' AND access=2");
      while ($row = $result->fetch_assoc()) {
        $emp_name = $row["fname"] . " " . $row["lname"];
        $username = $row["username"];
        $id = $row["card"];
        $label = "Completed Task/s";
        $emp_avg = 0;
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
        $count_task = mysqli_query($con, "SELECT COUNT(tasks_details.task_code) as total_task FROM tasks_details WHERE tasks_details.in_charge = '$username' AND tasks_details.due_date>='$val_from' AND tasks_details.due_date<='$val_to' AND tasks_details.task_status=1 AND tasks_details.date_accomplished IS NOT NULL AND tasks_details.task_class!=3");
        $count_task_row = $count_task->fetch_assoc();
        $total_task = $count_task_row["total_task"];
        if ($total_task == "0") {
          $pdf->Cell(90, 5, $emp_name, 'LR', 0, 'C');
          $pdf->Cell(50, 5, $formatted_num, 'LR', 0, 'C');
          $pdf->Cell(50, 5, '0 %', 'LR', 1, 'C');
        }
        else {
          $donetotal = 0;
          $tasktotal = 0;
          $totavg = 0;
          $donesum = 0;
          $latedone = 0;
          $resdone = 0;
          $remtask = 0;
          $ftask = 0;
          $three = 0;
          $two = 0;
          $one = 0;
          $zero = 0;
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

          if ($formatted_number != 0) {
            // Getting the Equivalent Percentage
            if ($formatted_number >= 2.90) {
              $percentage = 120;
              $equivalent = number_format($percentage, 2);
            }
            elseif ($formatted_number >= 2.75) {
              $difference = 2.75 - $formatted_number;
              $range = 119 - 105;
              $proportion = (($formatted_number - 2.75) * 100) / ((2.89 - 2.75) * 100);
              $percentage = 105 + ($proportion * $range);
              $equivalent = number_format($percentage, 2);
            }
            elseif ($formatted_number >= 2.50) {
              $difference = 2.50 - $formatted_number;
              $range = 104 - 95;
              $proportion = (($formatted_number - 2.50) * 100) / ((2.74 - 2.50) * 100);
              $percentage = 95 + ($proportion * $range);
              $equivalent = number_format($percentage, 2);
            }
            elseif ($formatted_number >= 1.80) {
              $difference = 1.80 - $formatted_number;
              $range = 94 - 80;
              $proportion = (($formatted_number - 1.80) * 100) / ((2.49 - 1.80) * 100);
              $percentage = 80 + ($proportion * $range);
              $equivalent = number_format($percentage, 2);
            }
            elseif ($formatted_number >= 0.01) {
              $percentage = 79;
              $equivalent = number_format($percentage, 2);
            }
          }
          else {
            $equivalent = 0.00;
          }

          $pdf->Cell(90, 5, $emp_name, 'LR', 0, 'C');
          $pdf->Cell(50, 5, $formatted_number, 'LR', 0, 'C');
          $pdf->Cell(50, 5, $equivalent . ' %', 'LR', 1, 'C');
        }
      }
      $pdf->Output();
    }
  }
?>