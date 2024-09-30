<?php
include('../include/auth.php');
include('../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_POST['taskImport'])) {
  $fileName     = $_FILES['file']['name'];
  log_action("Performed bulk import of tasks using Excel file {$fileName}.");
  $file_ext     = pathinfo($fileName, PATHINFO_EXTENSION);
  $allowed_ext  = ['xlsx'];
  if (in_array($file_ext, $allowed_ext)) {
    $filePath     = $_FILES['file']['tmp_name'];
    $spreadsheet  = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
    $sheet        = $spreadsheet->getActiveSheet();

    // Get the highest row and column numbers that contain data
    $highestRow     = $sheet->getHighestDataRow();
    $highestColumn  = $sheet->getHighestColumn();

    // Get the number of columns in the first row (header row)
    $headerRow    = $sheet->rangeToArray('A1:' . $highestColumn . '1', NULL, TRUE, FALSE);
    $columnCount  = count($headerRow[0]);


    if ($columnCount !== 7) {
      die("Invalid file format. The Excel file must have exactly 7 columns.");
    }

    $startRow           = 2;
    $today              = date('Y-m-d');
    $validDataFound     = false;
    $missingDataFlag    = false;
    $duplicateDataFound = false;

    for ($row = $startRow; $row <= $highestRow; $row++) {
      // Get the row as an array of cell values
      $rowValues = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

      // Check if the row has all 7 columns filled (no missing data)
      $isRowValid = true;
      foreach ($rowValues[0] as $cellValue) {
        // Trim the value to remove any surrounding whitespace and check if it's empty
        if (trim($cellValue) === '') {
          $isRowValid = false;
          break;
        }
      }

      if ($isRowValid) {
        $validDataFound = true;
        $taskName     = ucwords(strtolower(preg_replace('/\s+/', ' ', mysqli_real_escape_string($con, $rowValues[0][0]))));
        $taskDetails  = ucwords(strtolower(preg_replace('/\s+/', ' ', mysqli_real_escape_string($con, $rowValues[0][1]))));
        $taskClass    = mysqli_real_escape_string($con, $rowValues[0][2]);
        $taskFor      = strtoupper(mysqli_real_escape_string($con, $rowValues[0][3]));
        $taskTo       = strtoupper(mysqli_real_escape_string($con, $rowValues[0][4]));
        $taskDue      = mysqli_real_escape_string($con, $rowValues[0][5]);
        $taskReq      = mysqli_real_escape_string($con, $rowValues[0][6]);
        $import_checker = mysqli_query($con, "SELECT t.*, tl.* FROM tasks t JOIN task_list tl ON tl.task_id=t.task_id WHERE tl.task_name = '$taskName' AND tl.task_class='$taskClass' AND t.in_charge = '$taskTo' AND t.submission = '$taskDue'");
        if (mysqli_num_rows($import_checker) > 0) {
          $duplicateDataFound = true;
          $query_checker = mysqli_query($con, "INSERT INTO task_temp (`task_name`, `task_details`, `task_class`, `task_for`, `in_charge`, `submission`, `attachment`, `status`) values ('$taskName', '$taskDetails', '$taskClass', '$taskFor', '$taskTo', '$taskDue', '$taskReq', 'DUPLICATED')");
        } else {
          $query_checker = mysqli_query($con, "INSERT INTO task_temp (`task_name`, `task_details`, `task_class`, `task_for`, `in_charge`, `submission`, `attachment`, `status`) values ('$taskName', '$taskDetails', '$taskClass', '$taskFor', '$taskTo', '$taskDue', '$taskReq', 'CLEAR')");
        }
      } else {
        $missingDataFlag = true;
        break;
      }
    }

    if ($missingDataFlag) {
      die("One or more rows have missing data.<br>Please ensure all 7 columns are filled.");
    }

    if (!$validDataFound) {
      die("No valid data found in the Excel file.<br>All rows are either blank or missing data in required columns.");
    }

    if ($duplicateDataFound) {
      die("There's a problem deploying tasks!<br>Download the error report <span onclick='generateReport()' style='cursor: pointer;'><font color='red'>here</font>.</span>");
    } else {
      $success = true;
      $query_fetch = mysqli_query($con, "SELECT * FROM task_temp WHERE status='CLEAR' ORDER BY task_name ASC");
      while ($row = mysqli_fetch_assoc($query_fetch)) {
        $task_name    = $row['task_name'];
        $task_class   = $row['task_class'];
        $task_details = $row['task_details'];
        $task_for     = $row['task_for'];
        $submission   = $row['submission'];
        $in_charge    = $row['in_charge'];
        $attachment   = $row['attachment'];

        if (mysqli_num_rows(mysqli_query($con, "SELECT * FROM task_list WHERE task_name='$task_name' AND task_for='$task_for'")) == 0) {
          $register_task = mysqli_query($con, "INSERT INTO task_list (`task_name`, `task_details`, `task_class`, `task_for`, `date_created`, `status`) VALUES ('$task_name', '$task_details', '$task_class', '$task_for', '$today', 1)");
          $task_id = mysqli_insert_id($con);
        }
        $assign_task = mysqli_query($con, "INSERT INTO tasks (`task_id`, `in_charge`, `submission`, `requirement_status`) VALUES ('$task_id', '$in_charge', '$submission', '$attachment')");
      }
      if ($success) {
        log_action("Bulk tasks imported successfully.");
        die("Success");
      }
    }
  } else {
    die("Unsupported file extension. Please select .xlsx files only.");
  }
}

if (isset($_GET['importReport'])) {
  log_action("Downloaded generated report for failed bulk import.");
  header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
  header("Content-Disposition: attachment; filename=Task-Import-Report.xls");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: private", false); ?>

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
            <th>
              <center />Task Name
            </th>
            <th>
              <center />Task Class
            </th>
            <th>
              <center />Task Details
            </th>
            <th>
              <center />Task For
            </th>
            <th>
              <center />In Charge
            </th>
            <th>
              <center />Submission
            </th>
            <th>
              <center />Result
            </th>
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
          } ?>
        </tbody>
      </table>
    </div>
  </body>
<?php }
