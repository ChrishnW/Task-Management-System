<?php
session_start();
if (isset($_SESSION['SESS_MEMBER_ID'])) {
  header('location: include/home.php');
} ?>
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
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="assets/fonts/Nunito.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">

</head>

<body class="bg-image">
  <section class="vh-100">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
          <div class="card shadow" style="border-radius: 1rem;">
            <div class="row g-0">
              <div class="col-xl-6 col-lg-5 d-none d-md-block">
                <img src="assets/img/illustrations/pexels-polina-zimmerman-3778619.jpg"
                  alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
              </div>
              <div class="col-xl-6 col-lg-7 d-flex align-items-center">
                <div class="card-body p-4 p-lg-5 text-black">

                  <form id="userDetails">

                    <div class="d-flex align-items-center mb-3 pb-1">
                      <img src="assets/img/Logo.png" style="width: 30px;" class="mr-1">
                      <span class="h4 font-weight-bold mb-0">Task Management System</span>
                    </div>

                    <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign into your account</h5>

                    <div data-mdb-input-init class="form-outline mb-4">
                      <input type="text" id="username" name="username" class="form-control form-control-lg" autofocus />
                      <label class="form-label" for="username">Username</label> <i class="small text-danger d-none">- Login or password is invalid.</i>
                    </div>

                    <div data-mdb-input-init class="form-outline mb-4">
                      <input type="password" id="password" name="password" class="form-control form-control-lg" />
                      <label class="form-label" for="password">Password</label> <i class="small text-danger d-none">- Login or password is invalid.</i>
                    </div>

                  </form>

                  <div class="pt-1 mb-4">
                    <button class="btn btn-dark btn-lg btn-block" id="login" onclick="Login()">Login</button>
                  </div>

                  <div class="text-center">
                    <small>System Version 1.00</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="assets/js/sb-admin-2.min.js"></script>

  <script>
    $(document).on('keypress', function(event) {
      if (event.which === 13) { // 13 is the Enter key
        $('#login').click();
      }
    });

    function Login(element) {
      const warning = document.querySelectorAll('.text-danger');
      document.getElementById('username').classList.remove('border-danger');
      document.getElementById('password').classList.remove('border-danger');
      var accountDetials = new FormData(document.getElementById('userDetails'));
      if (accountDetials.get('username') !== '' && accountDetials.get('password') !== '') {
        $.ajax({
          method: "POST",
          url: "include/login.php",
          data: accountDetials,
          contentType: false,
          processData: false,
          success: function(response) {
            if (response === 'Success') {
              window.location.href = "include/home.php";
            } else {
              document.getElementById('password').focus();
              warning.forEach(warn => {
                warn.classList.remove('d-none');
              });
            }
          }
        });
      } else {
        if (accountDetials.get('username') === '') {
          document.getElementById('username').classList.add('border-danger');
        }
        if (accountDetials.get('password') === '') {
          document.getElementById('password').classList.add('border-danger');
        }
      }
    }
  </script>
</body>

</html>