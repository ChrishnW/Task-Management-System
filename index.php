<?php 
include('include/login.php'); 

if(isset($_SESSION['SESS_MEMBER_ID'])){    
		header('location: include/home.php');
} ?>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta http-equiv="Expires" content="Mon, 26 Jul 1997 05:00:00 GMT">
    <meta http-equiv="Pragma" content="no-cache">

    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/font-awesome/css/fontawesome.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/font-awesome/css/brands.css" rel="stylesheet" type="text/css">
    <link href="vendor/font-awesome/css/solid.css" rel="stylesheet" type="text/css">

    <link href="assets/css/style.css" rel="stylesheet" type="text/css" media="all"/>
    <link href="assets/fonts/fonts.css" rel="stylesheet">

    <title>G-TMS</title>
    <link rel="shortcut icon" href="assets/img/gloryicon.png">
    <!-- <style>
    /* body {
      background-image: url('documents/test_bg.jpg');
      background-size: cover;
    } */
    </style> -->
</head>
<body>
<body>
<div class="signupform">
    <div class="container">
        <h1></h1>
        <div class="agile_info">
            <div class="w3_info">
                <center>
                    <img src="assets/img/logo.jpg" > 
                </center>
                <h2>GLORY TASK MANAGEMENT <span> SYSTEM</span></h2>
                <label style="color:red;">
                        <?php echo $error; ?> 
                </label> 
                <form data-toggle="validator" action="" method="post">

                <div class="input-group">
                    <span><i class="fas fa-envelope" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="User Name" name="username" id="username" style="text-transform:uppercase" required autofocus>
                </div>
                <div class="input-group">
                    <span><i class="fas fa-unlock" aria-hidden="true"></i></span>
                    <input type="password" class="form-control" placeholder="Password" name="password" id="password" required>
                </div>
                    <small id="password-caps-warning" class="form-text text-danger d-none">Warning: Caps lock enabled</small>
                    <button class="btn btn-danger btn-block" type="submit" name="submit" value="submit">Sign In</button >
                    </br>
                </form>
                <br>
                <h6>
                    &copy <?php 
                    $fromyear=2023;
                    $thisyear=(int)date('Y');
                    echo $fromyear . (($fromyear != $thisyear) ? '-' .$thisyear : '');
                    ?>  GLORY (PHILIPPINES), INC. 
                </h6>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

<script src="assets/js/validator.js"></script>

<script type="text/javascript">
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