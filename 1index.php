<?php
include('include/login.php');

if (isset($_SESSION['SESS_MEMBER_ID'])) {
  header('location: include/home.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SB Admin 2 - Login</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
  <link rel="shortcut icon" href="./assets/img/tms logo 3.png">

  <style>
    body {
      background-image: url('./assets/img/BackgroundImage  1.png');

      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      height: 100vh;
    }

    .element::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(270deg, #0a0f3a, #015c72eb);
      /* Black color with 50% opacity */

    }

    .navbar {
      display: inline-flex;
    }

    .btn-gradient {
      background: linear-gradient(to right, #1BCFD0, #0D6BC2);
      border: none;
      color: white;
    }

    .btn-gradient:hover {
      background: linear-gradient(to right, #007B7F, #0047AB);
      /* Darker shades on hover */
    }
  </style>
</head>

<body>
  <div class="element"></div>
  <nav class="navbar navbar-light px-5 font-weight-bolder w-100">
    <h2 class="font-weight-bold" style="color: white">Task <span style="color: #40FFFD">Management System</span></h2>
  </nav>
  <div style="z-index: 1; position: relative; padding-left: 100px; padding-right: 100px; height: 90%" class="d-flex justify-content-center align-items-center flex-wrap  ">
    <div class="w-50 pr-5 pl-5 d-flex justify-content-center">
      <img src="./assets/img/tms logo 3 metalic 1.png" style="width: 65%" class="logo-container my-auto" alt="Login image" />
    </div>
    <div class="w-50">
      <form data-toggle="validator" method="POST">
        <h2 class="text-white text-center mb-5 display-4 font-weight-bold">Welcome <span class="text-cyan">back!</span></h2>
        <small class="form-text text-danger text-center"><?php echo $error; ?></small>
        <div class="mb-3">
          <label for="">USERNAME <small class="text-danger">*</small></label>
          <input type="text" class="form-control form-control-lg text-dark" id="username" name="username" placeholder="Please enter your username." autofocus />
        </div>
        <small class="form-text text-danger d-none text-center" id="password-caps-warning">Warning: Caps lock enabled</small>
        <div class="mb-3">
          <label for="">PASSWORD <small class="text-danger">*</small></label>
          <input type="password" id="password" name="password" placeholder="Please enter your password." class="form-control form-control-lg text-dark" />
        </div>
        <button class="btn btn-primary w-100 btn-lg btn-gradient" name="submit" type="submit" value="submit">
          <i class="fas fa-arrow-alt-circle-right fa-fw"></i> Button</button>
        <div class="text-center mt-4">
          <a class="small text-white" href="forgot-password.html">Forgot Password?</a>
        </div>
      </form>
    </div>
    <div class="credits">
      <strong><span>Developed by ICT - Information System</span></strong>
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
    (function() {
      const passwordField = document.getElementById("password");
      const errorField = document.getElementById("password-caps-warning");
      passwordField.onkeydown = function(e) {
        if (e.getModifierState("CapsLock")) {
          errorField.classList.remove("d-none");
        } else {
          errorField.classList.add("d-none");
        }
      }
    }());

    var togglePassword = document.querySelector('#togglePassword');
    var password = document.querySelector('#password');

    togglePassword.addEventListener('click',
      function(e) {
        // toggle the type attribute
        var type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        // toggle the eye / eye slash icon
        this.classList.toggle('fa-eye-slash');
      });
  </script>
</body>

</html>