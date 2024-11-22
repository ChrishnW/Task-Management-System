<?php
include('../include/auth.php');

if (isset($_GET['exportTaskList']) && !empty($_GET['section'])) {
  $sec_ids = explode(',', $_GET['section']);
  header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
  header("Content-Disposition: attachment; filename=REGISTERED_TASKS.xls");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: private", false);
  foreach ($sec_ids as $sec_id) { ?>
    <table width="100%" border="1">
      <thead>
        <tr>
          <?php $getSection = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM section WHERE sec_id='$sec_id'")); ?>
          <th colspan="2"><?php echo $getSection['sec_name']; ?></th>
          <th>ROUTINE</th>
          <?php $getEmpList = mysqli_query($con, "SELECT username FROM accounts WHERE sec_id='$sec_id' AND status=1 AND access=2 ORDER BY username ASC");
          $userList = [];
          while ($row = mysqli_fetch_assoc($getEmpList)) {
            $userList[] = $row['username']; ?>
            <th><?php echo $row['username']; ?></th>
          <?php } ?>
        </tr>
      </thead>

      <tbody>
        <?php
        $result = mysqli_query($con, "SELECT t.task_id, tl.task_name, tl.task_details, tl.task_for, t.submission, tc.task_class, GROUP_CONCAT(in_charge SEPARATOR ', ') AS in_charge_list FROM tasks t RIGHT JOIN task_list tl ON t.task_id=tl.id JOIN task_class tc ON tl.task_class=tc.id WHERE tl.task_for='$sec_id' AND tl.task_class!='4' GROUP BY t.task_id ORDER BY tc.task_class ASC, tl.task_name ASC");
        $inChargeCheckboxes = [];
        if (mysqli_num_rows($result) > 0) {
          $count = 0;
          while ($row = mysqli_fetch_assoc($result)) {
            $count += 1;
            $inChargeArray = explode(', ', $row['in_charge_list']); ?>
            <tr>
              <td>
                <center /><?php echo $count ?>
              </td>
              <td><b><?php echo $row['task_name'] ?>: </b><?php echo $row['task_details']; ?></td>
              <td>
                <center /><?php echo $row['task_class']; ?>
                <br>
                <?php if (!empty($row['submission'])) : ?>
                  [<?php echo $row['submission']; ?>]
                <?php endif; ?>
              </td>
              <?php foreach ($userList as $inCharge) { ?>
                <td>
                  <!-- <center /><input type="checkbox" <?php echo is_array($inChargeArray) && in_array($inCharge, $inChargeArray) ? 'checked' : ''; ?>> -->
                  <center /><?php echo is_array($inChargeArray) && in_array($inCharge, $inChargeArray) ? '=UNICHAR(10003)' : ''; ?>
                </td>
              <?php } ?>
            </tr>
        <?php }
        } ?>
      </tbody>
    </table>
<?php }
} else {
  echo "<script type='text/javascript'>window.close();</script>";
}
