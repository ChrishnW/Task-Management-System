<!DOCTYPE html>
<html lang="en">
<head>
    <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../vendor/font-awesome/css/fontawesome.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/font-awesome/css/brands.css" rel="stylesheet" type="text/css">
		<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/sb-admin-2.css" rel="stylesheet">
		<link rel="shortcut icon" href="../assets/img/gloryicon.png">
    <title>Session Timeout</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Trebuchet MS', sans-serif;
            font-size: 20px;
        }
        .message {
            text-align: center;
        }
        .icon {
            font-size: 100px;
            color: #f00; /* Red color for the exclamation icon */
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="message">
      <div class="icon">
          <i class="fas fa-exclamation-circle"></i>
      </div>
      <p>Your session has timed out.</p>
      <p>Click <a href="../index.php">here</a> to login again.</p>
    </div>
</body>
</html>
