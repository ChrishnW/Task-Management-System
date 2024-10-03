<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">

  <title>Task Management System</title>

  <link rel="icon" type="image/png" sizes="32x32" href="../assets/img/Logo.png">

  <link href="assets/fonts/Nunito.css" rel="stylesheet">
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
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
              <form class="user" id="loginForm" novalidate>
                <div class="form-group">
                  <input type="text" class="form-control form-control-user" id="username" name="username" placeholder="Please enter your username." autocomplete="off" required>
                  <div class="invalid-feedback">
                    Username cannot be empty.
                  </div>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Please enter your password." autocomplete="off" required minlength="5">
                  <div class="invalid-feedback">
                    Password must be at least 5 characters long.
                  </div>
                </div>
                <div id="alert-box" class="alert alert-danger d-none" role="alert"></div>
                <button class="btn btn-block btn-gradient-cyan mb-4"><i class="fas fa-arrow-alt-circle-right fa-fw"></i> Login</button>
              </form>
              <div class="text-center">
                <small class="text-white-50">System Version 1.0</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <script>
    $(document).ready(function() {
      const form = document.getElementById('loginForm');

      form.addEventListener('submit', function(event) {
        event.preventDefault();

        if (!form.checkValidity()) {
          event.stopPropagation();
          form.classList.add('was-validated');
        } else {
          const username = $('#username').val();
          const password = $('#password').val();

          $('#alert-box').addClass('d-none').text('');

          $.ajax({
            type: 'POST',
            url: 'include/login.php',
            data: {
              "username": username,
              "password": password
            },
            success: function(response) {
              if (response === 'Success') {
                window.location.href = 'include/home.php';
              } else {
                $('#alert-box').removeClass('d-none').text(response);
              }
            },
            error: function() {
              $('#alert-box').removeClass('d-none').text('An error occurred. Please try again.');
            }
          });
        }
      });
    });
  </script>

</body>

</html>