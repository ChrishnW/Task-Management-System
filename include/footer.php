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