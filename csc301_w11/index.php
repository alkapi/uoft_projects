<?php
        session_start();
		# Display informative message if present
        if (isset($_SESSION['err'])) {
                $err = $_SESSION['err'];
                unset($_SESSION['err']);
        } else {
                $err = false;
        }
?>
<!-- index.php is the main login page for the NTCI Parent Teacher online
  booking system. Here parents can login or register for an account-->
<!DOCTYPE html>
<html>
<head>
        <title>Parent-Teacher Booking System</title>
        <link href="style.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src="jquery-1.7.min.js"></script>
	    <script type="text/javascript" src="login.js"></script>
        <script type="text/javascript">

//        if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i))) {
//           location.replace("./iphone/index.html");
//        }

        </script>
</head>
<body>
        <div id="container">
                <div id="header"><img src="logo.png" alt="NTCI" /><h1 class="mainhead">NTCI's On-line Booking System</h1></div>
                <div id="subheader"><h3>Enter your login information:</h3></div>

                <?php if ($err) { ?>
                        <div id="login_err">
                        <?php echo $err; ?>
                        </div>
                <?php } ?>

                <div id="loginform">
                        <form action="profile.php" method="post">
                        <div id="logindiv">
                                <div>E-mail:<input type="text" name="email" class="input"/></div>
                                <div>Password:<input type="password" name="passwd" class="input"/></div>
                                <div><input class="lbutton" type="submit" value="Login" /></div>
                        </div>
                        </form>
                </div>


                <div id="regdiv">
                        <h3>Don't have an account yet?</h3>
                        <a href="registration.php" class="sqbutton">Register</a>
                </div>
                <p class="footer">
                        Welcome to the Parent-Teacher Interview On-line Booking Service!</br>
                        Here you can:</br>
                        + Book appointments with your child's or children's teachers</br>
                        + View the teacher's schedule of available times</br>
                        + Plan your day for the event!</br>
                </p>
        </div>
    </body>
</html>


