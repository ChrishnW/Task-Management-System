</div>
<!-- End of Main Content -->

<!-- Footer -->
<footer class="sticky-footer bg-white">
  <div class="container my-auto">
    <div class="copyright text-center my-auto">
      <span>Developed by ICT - Information System</span>
    </div>
  </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>

<!-- Profile Activity Logs -->
<div class="modal fade" id="activityLogs" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Activity Log</h5>
      </div>
      <div class="modal-body" id="activityDetails">
        <table class="table table-hover table-sm table-dark table-borderless" id="activityTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Date & Time</th>
              <th>Activity</th>
            </tr>
          </thead>
          <tbody id='dataTableBody'>
            <?php
            $query_activity = mysqli_query($con, "SELECT * FROM system_log WHERE user='$username'");
            while ($row = $query_activity->fetch_assoc()) { ?>
              <tr>
                <td><?php echo date_format(date_create($row['date_created']), "Y-m-d H:i:s"); ?></td>
                <td><?php echo $row['action']; ?></td>
              </tr>
            <?php }
            ?>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Account Profile -->
<div class="modal fade" id="profileModal" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLabel">Account Settings</h5>
      </div>
      <div class="modal-body">
        <div class="container-xl px-4 mt-4">
          <div class="row">
            <div class="col-xl-4">
              <div class="card mb-2">
                <div class="card-header">Profile Picture</div>
                <div class="card-body text-center">
                  <img class="img-account-profile rounded-circle mb-2 w-75" src="<?php echo $profileURL ?>" alt="<?php echo $username ?>">
                  <input type="text" id="imgSRC" value="<?php echo $fileSRC ?>" hidden>
                  <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                  <button class="btn btn-primary mb-1" type="button" id="uploadBtn">Upload new image</button>
                  <input type="file" id="fileInput" accept="image/*" hidden>
                  <button class="btn btn-danger" type="button" id="deleteBtn">Remove image</button>
                </div>
              </div>
            </div>
            <div class="col-xl-8">
              <div class="card mb-4">
                <div class="card-header">Account Details</div>
                <div class="card-body">
                  <form id="accountDetails" enctype="multipart/form-data">
                    <div class="mb-3">
                      <label class="small mb-1" for="inputUsername">Username</label>
                      <input class="form-control" id="inputUsername" name="inputUsername" type="text" placeholder="Enter your username" value="<?php echo $username ?>" readonly>
                    </div>
                    <div class="row gx-3 mb-3">
                      <div class="col-md-6">
                        <label class="small mb-1" for="inputFirstName">First name</label>
                        <input class="form-control" id="inputFirstName" name="inputFirstName" type="text" placeholder="Enter your first name" value="<?php echo $fname ?>">
                      </div>
                      <div class="col-md-6">
                        <label class="small mb-1" for="inputLastName">Last name</label>
                        <input class="form-control" id="inputLastName" name="inputLastName" type="text" placeholder="Enter your last name" value="<?php echo $lname ?>">
                      </div>
                    </div>
                    <div class="row gx-3 mb-3">
                      <div class="col-md-6">
                        <label class="small mb-1" for="inputDepartment">Department</label>
                        <input class="form-control" id="inputDepartment" name="inputDepartment" type="text" value="<?php echo $dept_name ?>" readonly>
                      </div>
                      <?php if ($access != 3) { ?>
                        <div class="col-md-6">
                          <label class="small mb-1" for="inputSection">Section</label>
                          <input class="form-control" id="inputSection" name="inputSection" type="text" value="<?php echo $sec_name ?>" readonly>
                        </div>
                      <?php } ?>
                    </div>
                    <div class="mb-3">
                      <label class="small mb-1" for="inputEmailAddress">Email address</label>
                      <input class="form-control" id="inputEmailAddress" name="inputEmailAddress" type="email" placeholder="Enter your email address" value="<?php echo $email ?>">
                    </div>
                    <div class="row gx-3 mb-3">
                      <div class="col-md-6">
                        <label class="small mb-1" for="inputCard">ID Number (RFID)</label>
                        <input class="form-control" id="inputCard" name="inputCard" type="text" placeholder="Enter your card number" value="<?php echo $card ?? 'N/A' ?>">
                      </div>
                      <div class="col-md-6">
                        <label class="small mb-1" for="inputAccess">Access</label>
                        <input class="form-control" id="inputAccess" type="text" name="inputAccess" value="<?php echo $access == 1 ? 'Admin' : ($access == 2 ? 'Member' : ($access == 3 ? 'Head' : 'Unknown')); ?>" readonly>
                      </div>
                    </div>
                    <button class="btn btn-primary" type="button" id="editPassword">Change Password</button>
                    <button class="btn btn-success float-right" type="button" id="editAccount">Save changes</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" onclick="location.reload();">Cancel</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="accountEdit" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-secondary">
      <div class="modal-header">
        <h5 class="modal-title">Account Details</h5>
      </div>
      <div class="modal-body" id="editBody">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
      </div>
      <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
      <div class="modal-footer">
        <a class="btn btn-danger" href="../include/logout.php">Logout</a>
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>


<!-- Global Modal -->
<div class="modal fade" id="error" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger justify-content-center">
        <i class="far fa-times-circle fa-5x text-white"></i>
      </div>
      <div class="modal-body text-center">
        <h4 class="modal-title">Ooops!</h4>
        <p id="error_found"></p>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="success" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success justify-content-center">
        <i class="far fa-check-circle fa-5x text-white"></i>
      </div>
      <div class="modal-body text-center">
        <h4 class="modal-title">Success!</h4>
        <p id="success_log"></p>
        <button type="button" class="btn btn-outline-secondary" onclick="location.reload();">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Bootstrap Select plugin -->
<script src="../vendor/snapappointments/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="../vendor/snapappointments/bootstrap-select/dist/js/i18n/defaults-en_US.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../assets/js/sb-admin-2.min.js"></script>
<script src="../assets/js/script.js"></script>

<!-- Page level plugins -->
<script src="../vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="../vendor/chart.js/Chart.min.js"></script>

</body>

</html>