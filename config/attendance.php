<?php
include('../include/auth.php');
if (isset($_POST['viewRecord'])) {
  $id = $_POST['id'];
  $query_result = mysqli_query($con, "SELECT attendance.*, accounts.fname, accounts.lname, accounts.sec_id, accounts.status, section.sec_name, department.dept_name FROM attendance JOIN accounts ON attendance.card=accounts.card JOIN section ON section.sec_id=accounts.sec_id JOIN department ON department.dept_id=section.dept_id WHERE attendance.id='$id'");
  while ($row = mysqli_fetch_assoc($query_result)) { ?>
    <div class="form-group">
      <label>User ID:</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
        </div>
        <input type="text" id="account_id" class="form-control" value="<?php echo $row['card'] ?? "?????" ?>" readonly>
      </div>
    </div>
    <div class="form-group">
      <label>Name:</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
        </div>
        <input type="text" id="account_name" class="form-control" value="<?php echo ucwords(strtolower($row['fname'].' '.$row['lname'])) ?>" readonly>
      </div>
    </div>
    <div class="form-group">
      <label>Date:</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
        </div>
        <input type="datetime-local" id="datetime" class="form-control" value="<?php echo $row['date'] ?>" readonly>
      </div>
    </div>
<?php } }
?>