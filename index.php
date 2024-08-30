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
  <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">

</head>

<body class="bg-image">
  <div class="element"></div>
  <div class="container d-flex login-height justify-content-center align-items-center">
    <div class="row justify-content-center w-100">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="row my-5">
          <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
          <div class="col-lg-6">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h3 text-white font-weight-bold mb-4">Welcome back!</h1>
              </div>
              <small class="form-text text-danger text-center"><?php echo $error; ?></small>
              <br>
              <form class="user" data-toggle="validator" method="POST">
                <div class="form-group">
                  <input type="text" class="form-control form-control-user" id="username" name="username" placeholder="Please enter your username." autocomplete="off" autofocus>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Please enter your password." autocomplete="off">
                  <small class="form-text text-danger d-none text-center" id="password-caps-warning">Warning: Caps lock enabled</small>
                </div>
                <button class="btn btn-block btn-gradient-cyan" name="submit" type="submit" value="submit"><i class="fas fa-arrow-alt-circle-right fa-fw"></i> Login</button>
              </form>
              <hr>
              <div class="text-center">
                <a class="small" href="forgot-password.html">Forgot Password?</a>
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
  </script>
</body>

</html>