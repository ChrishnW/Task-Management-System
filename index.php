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
    <link href="assets/css/darkmode.css" rel="stylesheet">
    <title>G-TMS</title>
    <link rel="shortcut icon" href="assets/img/gloryicon.png">

    <style>
            @keyframes animate{
                0%
                {
                    transform: translateY(100vh) scale(0);
                }
                100%
                {
                    transform: translateY(-10vh) scale(1);
                }
            }
            .bubbles_1{
                position: fixed;
                display: flex;
                top: 100px;
            }
            .bubbles_1 span{
                position: relative;
                width: 10px;
                height: 10px;
                background-color: rgb(0, 60, 255);
                margin: 0 4px;
                border-radius: 50%;
                box-shadow: 0 0 0 10px rgb(0, 60, 255),
                0 0 50px rgb(0, 60, 255),
                0 0 100px rgb(0, 60, 255);
                animation: animate 15s linear infinite;
                animation-duration: calc(100s / var(--i));
            }
            .bubbles_1 span:nth-child(even){
                background: #16158c;
                box-shadow: 0 0 0 10px #16158c,
                0 0 50px #16158c,
                0 0 100px #16158c;
            }
            .bubbles_2{
                position: fixed;
                display: flex;
                top: 100px;
                right: 0;
            }
            .bubbles_2 span{
                position: relative;
                width: 10px;
                height: 10px;
                background-color: rgb(0, 60, 255);
                margin: 0 4px;
                border-radius: 50%;
                box-shadow: 0 0 0 10px rgb(0, 60, 255),
                0 0 50px rgb(0, 60, 255),
                0 0 100px rgb(0, 60, 255);
                animation: animate 15s linear infinite;
                animation-duration: calc(100s / var(--i));
            }
            .bubbles_2 span:nth-child(even){
                background: #16158c;
                box-shadow: 0 0 0 10px #16158c,
                0 0 50px #16158c,
                0 0 100px #16158c;
            }
            body{
                overflow: hidden;
            }
    </style>
</head>
<body>
</div>
<div class="signupform">
    <div class="container">
        <h1></h1>
        <div class="agile_info">
            <div class="w3_info">
                <center>
                    <a href="index.php">
                    <img src="assets/img/logo.jpg">
                    </a>
                </center>
                <h2>GLORY TASK MANAGEMENT <span> SYSTEM</span></h2>
                <label style="color:red;">
                        <?php echo $error; ?> 
                </label> 
                <form data-toggle="validator" action="" method="post">

                <div class="input-group">
                    <span><i class="fas fa-envelope" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" autocomplete="off" placeholder="User Name" name="username" id="username" style="text-transform:uppercase" required autofocus>
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
    <div class="bubbles_1">
            <span style="--i:11;"></span>
            <span style="--i:20;"></span>
            <span style="--i:15;"></span>
            <span style="--i:12;"></span>
            <span style="--i:22;"></span>
            <span style="--i:26;"></span>
            <span style="--i:14;"></span>
            <span style="--i:10;"></span>
            <span style="--i:19;"></span>
    </div>
    <div class="bubbles_2">
            <span style="--i:11;"></span>
            <span style="--i:20;"></span>
            <span style="--i:15;"></span>
            <span style="--i:12;"></span>
            <span style="--i:22;"></span>
            <span style="--i:26;"></span>
            <span style="--i:14;"></span>
            <span style="--i:10;"></span>
            <span style="--i:19;"></span>
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