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
  <link href="assets/css/font.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">

</head>

<body class="bg-image">
  <div class="element"></div>
  <div class="container d-flex login-height justify-content-center align-items-center">
    <div class="row justify-content-center w-100">
      <div class="alert v-hidden" id="errorAlert" style="position: absolute;">
      </div>
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="row my-5">
          <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
          <div class="col-lg-6">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-white font-weight-bold mb-4">Task Management System</h1>
              </div>
              <form class="user" id="userDetails" enctype="multipart/form-data">
                <div class="form-group">
                  <input type="text" class="form-control form-control-user" id="username" name="username" placeholder="Please enter your username." autocomplete="off">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Please enter your password." autocomplete="off">
                </div>
              </form>
              <p class="form-text text-center text-warning v-hidden" id="password-caps-warning"><i class="fas fa-lock fa-fw"></i> Caps Lock On</p>
              <button class="btn btn-block btn-gradient-cyan mb-4" id="login" onclick="Login(this)"><i class="fas fa-arrow-alt-circle-right fa-fw"></i> Login</button>
              <div class="text-center">
                <small class="text-white-50">System Version 1.0</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

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
    (function() {
      const passwordField = document.getElementById("password");
      const errorField = document.getElementById("password-caps-warning");
      passwordField.onkeydown = function(e) {
        if (e.getModifierState("CapsLock")) {
          errorField.classList.remove("v-hidden");
        } else {
          errorField.classList.add("v-hidden");
        }
      }
    }());

    function Login(element) {
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
            } else if (response === 'Incorrect') {
              document.getElementById('password').focus();
              $('#errorAlert').html('<i class="fas fa-exclamation-triangle fa-fw"></i> Entered password is incorrect.');
              $('#errorAlert').addClass('alert-warning');
              $('#errorAlert').removeClass('v-hidden');
              setTimeout(function() {
                $('#errorAlert').addClass('alert-warning v-hidden');
                $('#errorAlert').removeClass('alert-warning');
              }, 5000);
            } else {
              $('#errorAlert').html('<i class="fas fa-user-alt-slash"></i> Error accessing your account. Please contact the system administrator immediately!');
              $('#errorAlert').addClass('alert-danger');
              $('#errorAlert').removeClass('v-hidden');
              setTimeout(function() {
                $('#errorAlert').addClass('alert-danger v-hidden');
                $('#errorAlert').removeClass('alert-danger');
              }, 5000);
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