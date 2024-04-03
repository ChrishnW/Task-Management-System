<?php 
	include('include/login.php'); 
	
	if(isset($_SESSION['SESS_MEMBER_ID'])){    
	header('location: include/home.php');
	}
	?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="cache-control" content="no-cache">
		<meta http-equiv="pragma" content="no-cache">
		<meta http-equiv="expires" content="0">
		<link href="assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="vendor/font-awesome/css/fontawesome.min.css" rel="stylesheet" type="text/css">
		<link href="vendor/font-awesome/css/brands.css" rel="stylesheet" type="text/css">
		<link href="vendor/font-awesome/css/solid.css" rel="stylesheet" type="text/css">
		<link href="assets/css/style.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="assets/fonts/fonts.css" rel="stylesheet">
		<link href="assets/css/sb-admin-2.css" rel="stylesheet">
		<link rel="shortcut icon" href="assets/img/gloryicon.png">
		<title>G-TMS</title>
		<style>
			.container {
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100vh;
			margin-left: auto;
			margin-right: auto;
			position: relative;
			}
		</style>
	</head>
	<body>
		<div id="particles-js"></div>
		<div class="container">
			<div class="w3_info">
				<center> 
					<a href="index.php"><img src="assets/img/logo.jpg"></a>
				</center>
				<h2>GLORY TASK MANAGEMENT
				<br>
				<span>SYSTEM</span></h2>
				<label style="color:red;"><?php echo $error; ?></label>
				<form action="" data-toggle="validator" method="post">
					<br>
					<div class="input-group">
						<span><i aria-hidden="true" class="fas fa-user"></i></span>
						<input type="text" autocomplete="off" class="form-control" id="username" name="username" placeholder="Username" style="text-transform:uppercase" required autofocus>
					</div>
					<div class="input-group">
						<span><i aria-hidden="true" class="fas fa-unlock"></i></span>
						<input type="password" class="form-control" id="password" name="password" placeholder="Password">
						<span title="Show Password" style="cursor: pointer;"><i id="togglePassword" class="fa fa-eye" aria-hidden="true"></i></span>
					</div>
					<small class="form-text text-danger d-none" id="password-caps-warning">Warning: Caps lock enabled</small> <button class="btn btn-danger btn-block" name="submit" type="submit" value="submit">Sign In</button><br>
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
		</div>
	</body>
	<script src="assets/js/particles.js"></script>
	<script src="assets/js/app.js"></script>
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
		
		var togglePassword = document.querySelector('#togglePassword');
		var password = document.querySelector('#password');
		
		togglePassword.addEventListener('click', function (e) {
		// toggle the type attribute
		var type = password.getAttribute('type') === 'password' ? 'text' : 'password';
		password.setAttribute('type', type);
		// toggle the eye / eye slash icon
		this.classList.toggle('fa-eye-slash');
		});
	</script>
</html>