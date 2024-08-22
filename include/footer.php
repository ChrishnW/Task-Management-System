</div>
<!-- End of Main Content -->

<!-- Footer -->
<footer class="sticky-footer bg-white">
  <div class="container my-auto">
    <div class="copyright text-center my-auto">
      <span>Copyright &copy; GPI-Information System</span>
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

<!-- Account Profile -->
<div class="modal fade" id="profileModal" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLabel">Account Settings</h5>
      </div>
      <div class="modal-body">
        <div class="container-xl px-4 mt-4">
          <div class="row">
            <div class="col-xl-4">
              <!-- Profile picture card-->
              <div class="card mb-4 mb-xl-0">
                <div class="card-header">Profile Picture</div>
                <div class="card-body text-center">
                  <!-- Profile picture image-->
                  <img class="img-account-profile rounded-circle mb-2 w-75" src="<?php echo $imageURL ?>" alt="">
                  <!-- Profile picture help block-->
                  <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                  <!-- Profile picture upload button-->
                  <button class="btn btn-primary mb-1" type="button">Upload new image</button>
                  <button class="btn btn-danger" type="button">Remove image</button>
                </div>
              </div>
            </div>
            <div class="col-xl-8">
              <!-- Account details card-->
              <div class="card mb-4">
                <div class="card-header">Account Details</div>
                <div class="card-body">
                  <form>
                    <!-- Form Group (username)-->
                    <div class="mb-3">
                      <label class="small mb-1" for="inputUsername">Username</label>
                      <input class="form-control" id="inputUsername" type="text" placeholder="Enter your username" value="<?php echo $username ?>" readonly>
                    </div>
                    <!-- Form Row-->
                    <div class="row gx-3 mb-3">
                      <!-- Form Group (first name)-->
                      <div class="col-md-6">
                        <label class="small mb-1" for="inputFirstName">First name</label>
                        <input class="form-control" id="inputFirstName" type="text" placeholder="Enter your first name" value="<?php echo $fname?>">
                      </div>
                      <!-- Form Group (last name)-->
                      <div class="col-md-6">
                        <label class="small mb-1" for="inputLastName">Last name</label>
                        <input class="form-control" id="inputLastName" type="text" placeholder="Enter your last name" value="<?php echo $lname?>">
                      </div>
                    </div>
                    <!-- Form Row        -->
                    <div class="row gx-3 mb-3">
                      <!-- Form Group (organization name)-->
                      <div class="col-md-6">
                        <label class="small mb-1" for="inputOrgName">Department</label>
                        <input class="form-control" id="inputOrgName" type="text" value="<?php echo $dept_name ?>" readonly>
                      </div>
                      <!-- Form Group (location)-->
                      <?php if ($access != 3){ ?>
                      <div class="col-md-6">
                        <label class="small mb-1" for="inputLocation">Section</label>
                        <input class="form-control" id="inputLocation" type="text" value="<?php echo $sec_name ?>" readonly>
                      </div>
                      <?php } ?>
                    </div>
                    <!-- Form Group (email address)-->
                    <div class="mb-3">
                      <label class="small mb-1" for="inputEmailAddress">Email address</label>
                      <input class="form-control" id="inputEmailAddress" type="email" placeholder="Enter your email address" value="<?php echo $email ?>">
                    </div>
                    <!-- Form Row-->
                    <div class="row gx-3 mb-3">
                      <!-- Form Group (phone number)-->
                      <div class="col-md-6">
                        <label class="small mb-1" for="inputCard">ID Number (RFID)</label>
                        <input class="form-control" id="inputCard" type="text" placeholder="Enter your phone number" value="<?php echo $card ?? 'N/A' ?>">
                      </div>
                      <!-- Form Group (birthday)-->
                      <div class="col-md-6">
                        <label class="small mb-1" for="inputBirthday">Access</label>
                        <input class="form-control" id="inputBirthday" type="text" name="birthday" value="<?php echo $access == 1 ? 'Admin' : ($access == 2 ? 'Member' : ($access == 3 ? 'Head' : 'Unknown')); ?>" readonly>
                      </div>
                    </div>
                    <!-- Save changes button-->
                    <button class="btn btn-primary float-right" type="button">Save changes</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
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
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
        <a class="btn btn-primary" href="../include/logout.php">Logout</a>
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

<!-- Page level custom scripts -->
<script src="../assets/js/demo/datatables-demo.js"></script>

<!-- Custom Scripts Global -->
<script>
  function readNotification(element) {
    var id = element.value;
    $.ajax({
      method: "POST",
      url: "../config/index.php",
      data: {
        "readNotification": true,
        "id": id,
      },
      success: function(response) {
        if (response === 'Success') {
          location.reload();
        } else {
          location.reload();
        }
      }
    })
  }

  function readAllNotification(element) {
    var checkboxes = document.querySelectorAll('input[type="hidden"][name="notificationID[]"]');
    var checkedIds = [];
    checkboxes.forEach(function(checkbox) {
      if (checkbox.value != null) {
        checkedIds.push(checkbox.value);
      }
    });
    $.ajax({
      method: "POST",
      url: "../config/index.php",
      data: {
        "readAllNotification": true,
        "checkedIds": checkedIds,
      },
      success: function(response) {
        if (response === 'Success') {
          location.reload();
        } else {
          location.reload();
        }
      }
    })
  }
</script>

</body>

</html>