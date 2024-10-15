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

<!-- Notifications -->
<div class="modal fade" id="notificationLogs" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Notification</h5>
      </div>
      <div class="modal-body" id="notificationDetails">
        <table class="table table-hover table-sm table-dark table-borderless" id="notificationTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Date & Time</th>
              <th>Subject</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody id='dataTableBody'>
            <?php
            $query_notif = mysqli_query($con, "SELECT * FROM notification WHERE user='$username'");
            while ($row = $query_notif->fetch_assoc()) { ?>
              <tr>
                <td><?php echo date_format(date_create($row['date_created']), "Y-m-d H:i:s"); ?></td>
                <td id="td-table-shrink"><span class="btn btn-circle btn-sm bg-<?php echo $row['type']; ?> text-white" onclick="<?php echo $row['action']; ?> readNotification(this);"><i class="<?php echo $row['icon']; ?>"></i></span> <?php echo $row['body']; ?></td>
                <td><?php if ($row['status'] == 1) echo "<span class='badge badge-pill text-white bg-warning'>New</span>";
                    else echo "<span class='badge badge-pill text-white bg-secondary'>Read</span>"; ?></td>
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

<!-- Account Password -->
<div class="modal fade" id="passwordModal" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="accountUsername">Change Password</h5>
      </div>
      <div class="modal-body" id="passBody">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success d-none" id="passUpdate">Save</button>
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
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

<!-- Profile Error -->
<div class="modal fade" id="profileError" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Error</h5>
      </div>
      <div class="modal-body text-center">
        <i class="fas fa-sad-cry fa-5x text-danger"></i>
        <br><br>
        <p id="textError"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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

<!-- Custom Scripts Global -->
<script>
  window.onload = function() {
    document.getElementById('preloader').style.display = 'none';
    document.getElementById('wrapper').style.visibility = 'visible';
  };

  function togglePreloader(show) {
    if (show) {
      $('#preloader').show();
      // $('#wrapper').css('visibility', 'hidden');
    } else {
      $('#preloader').hide();
      // $('#wrapper').css('visibility', 'visible');
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    var inactivityTime = 3600000;
    var timeout;

    // Function to reset the timeout
    function resetTimeout() {
      clearTimeout(timeout);
      timeout = setTimeout(function() {
        location.reload();
      }, inactivityTime);
    }

    // Reset timeout when there is activity (using addEventListener for multiple event listeners)
    // window.addEventListener('mousemove', resetTimeout);
    window.addEventListener('keypress', resetTimeout);
    window.addEventListener('touchstart', resetTimeout);

    resetTimeout();
  });

  function openSpecificModal(modalId, size) {
    var modalDialog = document.querySelector(`#${modalId} .modal-dialog`);
    modalDialog.classList.remove('modal-sm', 'modal-lg', 'modal-xl');
    if (size) {
      modalDialog.classList.add(size);
    }
    $(`#${modalId}`).modal('show');
  }

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
        console.log(response);
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
        console.log(response);
      }
    })
  }

  function checkPassword(element) {
    var icPassowrd = element.value;
    var ccPassword = <?php echo json_encode($pass); ?>;
    $.ajax({
      method: "POST",
      url: "../config/accounts.php",
      data: {
        "checkPassword": true,
        "check": icPassowrd,
        "current": ccPassword
      },
      success: function(response) {
        var currentInput = document.getElementById('newPass');
        if (response === 'Success') {
          currentInput.readOnly = false;
          document.getElementById('curPass').classList.remove('border-danger');
          document.getElementById('curPass').classList.add('border-success');
          document.getElementById('incorrect').classList.add('d-none');
        } else {
          currentInput.readOnly = true;
          document.getElementById('curPass').classList.add('border-danger');
          document.getElementById('incorrect').classList.remove('d-none');
        }
      }
    });
  }

  function newPassword(element) {
    var newPass = element.value;
    var chkPass = document.getElementById('curPass').value;
    if (newPass === '') {
      document.getElementById('newPass').classList.remove('border-danger', 'border-success', 'border-warning');
      document.getElementById('used').classList.add('d-none');
      document.getElementById('conPass').readOnly = true;
    } else if (newPass === chkPass) {
      document.getElementById('newPass').classList.add('border-danger');
      document.getElementById('used').classList.remove('d-none');
      document.getElementById('conPass').readOnly = true;
    } else if (newPass.length <= 7) {
      document.getElementById('used').innerHTML = ' Password is too short.';
      document.getElementById('newPass').classList.add('border-warning');
      document.getElementById('used').classList.remove('d-none');
      document.getElementById('conPass').readOnly = true;
    } else {
      document.getElementById('newPass').classList.remove('border-danger', 'border-warning');
      document.getElementById('newPass').classList.add('border-success');
      document.getElementById('used').classList.add('d-none');
      document.getElementById('conPass').readOnly = false;
    }
  }

  function conPassword(element) {
    var confirm = element.value;
    document.getElementById('passUpdate').classList.add('d-none');
    if (document.getElementById('conPass').value === '') {
      document.getElementById('conPass').classList.remove('border-danger', 'border-success');
      document.getElementById('notmatch').classList.add('d-none');
    } else if (confirm !== document.getElementById('newPass').value) {
      document.getElementById('conPass').classList.add('border-danger');
      document.getElementById('notmatch').classList.remove('d-none');
    } else {
      document.getElementById('conPass').classList.remove('border-danger');
      document.getElementById('conPass').classList.add('border-success');
      document.getElementById('notmatch').classList.add('d-none');
      document.getElementById('passUpdate').classList.remove('d-none');
    }
  }

  $('#passUpdate').off('click').on('click', function() {
    var userAcc = <?php echo json_encode($username) ?>;
    var checkSetPass = $('#newPass').val();
    var checkConPass = $('#conPass').val();
    if (checkSetPass != '' && checkConPass != '') {
      $.ajax({
        method: "POST",
        url: "../config/accounts.php",
        data: {
          "passUpdate": true,
          "userAcc": userAcc,
          "setPass": checkSetPass,
          "conPass": checkConPass
        },
        success: function(response) {
          if (response === 'Success') {
            window.location.href = '../include/logout.php';
          } else {
            document.getElementById('passUpdate').classList.add('d-none');
            document.getElementById('textError').innerHTML = response;
            $('#profileError').modal('show');
          }
        }
      });
    } else {
      document.getElementById('textError').innerHTML = 'Empty field has been detected!';
      $('#profileError').modal('show');
    }
  });

  $(document).ready(function() {
    $('#uploadBtn').click(function() {
      $('#fileInput').click();
    });

    $('#fileInput').change(function() {
      var fileData = new FormData();
      var file = $('#fileInput')[0].files[0];
      var fileUser = document.getElementById('inputUsername').value;
      fileData.append('image', file);
      fileData.append('fileUser', fileUser);
      fileData.append('uploadImage', true);
      $.ajax({
        type: 'POST',
        url: '../config/accounts.php',
        data: fileData,
        processData: false,
        contentType: false,
        success: function(response) {
          console.log(response);
        }
      })
    });

    $('#deleteBtn').click(function() {
      var fileNameC = document.getElementById('imgSRC').value;
      var fileUserC = document.getElementById('inputUsername').value;
      $.ajax({
        method: "POST",
        url: "../config/accounts.php",
        data: {
          "deleteImage": true,
          "fileName": fileNameC,
          "userName": fileUserC,
        },
        success: function(response) {
          location.reload();
        }
      });
    });

    $('#editAccount').off('click').on('click', function() {
      var $button = $(this);
      $button.prop('disabled', true);

      // Check for empty inputs and validate specific fields
      var isValid = true;
      var nonStringField = $('#inputCard').val(); // replace with the actual ID of the non-string field
      var errorMessage = '';

      $('#accountDetails').find('input').each(function() {
        if (!$(this).val()) {
          isValid = false;
          errorMessage = 'Please fill out all required fields.';
          return false; // Exit each loop if an empty input is found
        }
      });

      if (isNaN(nonStringField)) {
        isValid = false;
        errorMessage = 'Invalid ID Number (RFID).';
      }

      if (!isValid) {
        $button.prop('disabled', false);
        document.getElementById('textError').innerHTML = errorMessage;
        $('#profileError').modal('show');
        return; // Stop the script from proceeding if validation fails
      }

      var formData = new FormData(document.getElementById('accountDetails'));
      formData.append('editAccount', true);
      console.log(formData);

      $.ajax({
        method: "POST",
        url: "../config/accounts.php",
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
          location.reload();
        }
      });
    });

    $('#editPassword').off('click').on('click', function() {
      let getBody = true;
      $.ajax({
        method: "POST",
        url: "../config/accounts.php",
        data: {
          "getBody": getBody
        },
        success: function(response) {
          $('#passBody').html(response);
          $('#passwordModal').modal('show');
        }
      });
    });

    $('#activityLogs').on('shown.bs.modal', function() {
      if (!$.fn.DataTable.isDataTable('#activityTable')) {
        $('#activityTable').DataTable({
          "paging": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "responsive": true,
          "order": [
            [0, "desc"]
          ],
          "pageLength": 5,
          "lengthMenu": [5, 10, 25, 50, 100]
        });
      }
    });

    $('#notificationLogs').on('shown.bs.modal', function() {
      if (!$.fn.DataTable.isDataTable('#notificationTable')) {
        $('#notificationTable').DataTable({
          "paging": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "responsive": true,
          "order": [
            [0, "desc"]
          ],
          "pageLength": 5,
          "lengthMenu": [5, 10, 25, 50, 100]
        });
      }
    });
  });
</script>

</body>

</html>