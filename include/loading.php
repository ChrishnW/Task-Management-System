<!DOCTYPE html>
<html lang="en">
	<head>
		<link href="../assets/css/sb-admin-2.css" rel="stylesheet">
		<style>
			#loader {
			position: fixed;
			left: 0px;
			top: 0px;
			width: 100%;
			height: 100%;
			z-index: 9999;
			background: url('../assets/img/loader.gif') 50% 50% no-repeat rgb(0, 0, 0);
			}
		</style>
	</head>
	<script src="../vendor/jquery/jquery-1.9.1.min.js"></script>
	<script>
		$(window).on('load', function() {
		$('#loader').fadeOut('slow');
		});
	</script>
	<body>
		<div id="loader"></div>
	</body>
</html>