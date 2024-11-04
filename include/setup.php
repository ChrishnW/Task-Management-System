<?php include('auth.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Cache control meta tags -->
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">

  <title>Task Management System</title>

  <link rel="icon" type="image/png" sizes="32x32" href="../assets/img/Logo.png">

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../assets/fonts/Nunito.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">

</head>

<body class="bg-gradient-light">
  <div class="container center-screen">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-header text-center">
          <h3 class="mt-4 font-weight-bolder">Account Setup</h3>
          <p class="text-muted">Please fill in your details to proceed</p>
        </div>
        <div class="card-body">
          <form id="userDetails" class="mb-5">
            <!-- First Name -->
            <div class="mb-3">
              <label for="firstName" class="form-label">First Name <small class="text-danger">*</small></label>
              <input type="text" class="form-control" id="firstName" name="firstName"
                placeholder="Enter your first name" required>
            </div>

            <!-- Last Name -->
            <div class="mb-3">
              <label for="lastName" class="form-label">Last Name <small class="text-danger">*</small></label>
              <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter your last name"
                required>
            </div>

            <!-- Email -->
            <div class="mb-3">
              <label for="email" class="form-label">Email <small class="text-danger">*</small></label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <!-- Employee ID -->
            <div class="mb-3">
              <label for="empId" class="form-label">Employee ID <small class="text-danger">*</small></label>
              <input type="text" class="form-control" id="empId" name="empId" placeholder="Enter your employee ID"
                required>
            </div>
          </form>
          <div class="d-grid">
            <button id="completeButton" class="btn btn-block btn-primary" onclick="submitDetails()" disabled>Complete</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

<!-- Bootstrap core JavaScript-->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../assets/js/sb-admin-2.min.js"></script>

<script>
  function submitDetails() {
    const formData = new FormData(document.getElementById('userDetails'));
    formData.append('submitDetails', true);
    $.ajax({
      method: "POST",
      url: "../config/setup.php",
      data: formData,
      contentType: false,
      processData: false,
      success: function(data) {
        window.location.href = "../pages/index.php";
      }
    });
  }
</script>

<script>
  document.addEventListener('input', function() {
    const form = document.getElementById('userDetails');
    const inputs = form.querySelectorAll('input[required]');
    const button = document.getElementById('completeButton');
    let allFilled = true;

    inputs.forEach(input => {
      if (!input.value.trim()) {
        allFilled = false;
      }
    });

    button.disabled = !allFilled;
  });
</script>

</html>