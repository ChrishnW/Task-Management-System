<?php 
include('include/connect.php');
if(isset($_POST['submit'])){
    $card = $_POST['card'];
    $date = $_POST['date'];
    $sql = "SELECT * FROM accounts WHERE card = '$card'";
    $result = $con -> query($sql);
         if ($result -> num_rows > 0) {
             $check = "SELECT * FROM attendance WHERE date = '$date' AND card = '$card'";
             $reschk = $con->query($check);
                if ($reschk->num_rows > 0) {
                    ?>
                <div class="alert alert-warning" role="alert" style="z-index: 1">
                <strong>SYSTEM WARNING:</strong> You're already present on this day. (●´⌓`●)
                </div>
                <?php
                }
                else {
                    $query = "INSERT INTO attendance VALUES ('', '$card','$date')";
                    $reschk = mysqli_query($con, $query) or die('Error querying database.');
                    ?>
                    <div class="alert alert-success" role="alert" style="z-index: 1">
                        <strong>SYSTEM NOTIFICATION:</strong> Your attendance has been recorded successfully. (∿°○°)∿
                    </div>
                    <?php
                    }
         }
         else {
             ?>
            <div class="alert alert-danger" role="alert" style="z-index: 1">
            <strong>SYSTEM ALERT:</strong> Card number is invalid! (๏ᆺ๏υ)
            </div>
        <?php
         }
     }
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<html>
    <head>
        <title>GTMS | Attendance</title>
        <!-- Page Refresh -->
        <meta http-equiv="refresh" content="15"> 
        <style>
            html, body {
                margin: 0;
                padding: 0;
                background-color: rgb(0, 4, 73);
            }
            body {
                width: 100%;
                height: 100vh;
                overflow: hidden;
            }
            .container {
                width: inherit;
                height: inherit;
                background-color: rgb(0, 4, 73);
                text-align: center;
            }
            .header {
                width: inherit;
                height: 30vh;
            }
            .header img {
                margin-top: 10%;
                width: 100px;
                height: 100px;
            }
            .header h2 {
                text-align: center;
                color: white;
                font-family: 'Raleway', sans-serif;
                font-size: 35px;
                margin: 0;
            }
            .footer {
                width: inherit;
                height: 50vh;
                color: white;
                font-family: 'Raleway', sans-serif;
            }
            input[type="number"] {
                width: 30%;
                margin-top: 15%;
                font-size: 15px;
                font-weight: 500;
                text-align: center;
                text-transform: capitalize;
                letter-spacing: 1px;
                padding: 7px 10px 7px 10px;
                display:inline-block;
                box-sizing: border-box;
                border: none;
                border-bottom: 1px solid #ccc;
                outline: none;
                background: transparent;
                color: white;
            }
            /* for chrome, edge browsers */
            input::-webkit-outer-spin-button,
            input::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
            /* for mozzila browsers */
            input[type=number] {
                -moz-appearance:textfield;
            }
            #datetime {
                position: fixed;
                font-family: 'Raleway', sans-serif;
                left: 50%;
                bottom: 20px;
                transform: translate(-50%, -50%);
                color: white;
                margin: 0 auto;
                font-size: 30px;
            }
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
        </style>
        <link rel="shortcut icon" href="assets/img/gloryicon.png">
    </head>
<body>
    <div class="container">
        <div class="header">
            <center>
                <a href="index.php"><img src="assets/img/logo.jpg"></a> 
            </center>
            <br>
            <h2>GLORY TASK MANAGEMENT</h2>
            <h2 style="color:#5379fa !important">SYSTEM</h2>
        </div>
        <div class="footer">
            <!-- Card Number will be automatically record after Tap -->
            <form action="" method="post">
                <input type="number" id="card" min="10" name="card" placeholder="Card Number" autocomplete="off">
                <br>
                <input type="date" id="date" name="date" hidden>
                <button type="submit" id="submit" name="submit" hidden> Submit </button>
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
    <!-- Date and Time Display -->
    <div id="datetime"></div>
    <script>
      function updateTime() {
        var now = new Date();
        var datetime = document.getElementById("datetime");
        datetime.innerHTML = now.toLocaleString();
      }
      setInterval(updateTime, 1000);
// Date Picker
        var today = new Date();
        var date = today.getDate();
        var month = today.getMonth() + 1;
        var year = today.getFullYear();
        var dateString = year + "-" + month.toString().padStart(2, '0') + "-" + date.toString().padStart(2, '0');
        document.getElementById("date").value = dateString;

    </script>
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
</body>
</html>