<?php
	session_start();
	include_once('model.php');
	error_reporting(E_ALL);
	# Verify input on form submission 
	if (isset($_POST['Submit'])) {
		$errmsg = array();
		# Check email field
		$email = trim($_POST['reg_email']);
		$email_pattern = '/^[0-9a-zA-Z_.\-]+@([0-9a-zA-Z]+\.)+[a-zA-Z]{2,9}$/';
		if (empty($email)) {
			$errmsg[] = 'A valid email address is required to register.';
		} else if (!preg_match($email_pattern, $email)) {
			$errmsg[] = 'Invalid email format.';
		}
		# Check password fields
		$pass1 = trim($_POST['reg_passwd1']);
		$pass2 = trim($_POST['reg_passwd2']);
		if (empty($pass1) or strlen($pass1) < 6 or empty($pass2)
				or strlen($pass2) < 6) {
			$errmsg[] = 'A password of at least 6 characters is required.';	
		} else if ($pass1 != $pass2) {
			$errmsg[] = 'Passwords do not match.';
		}
		# Check student number
		$snum = trim($_POST['stud_num']);
		$snum_pattern = '/[0-9]+/'; # Find out if student num is set length?
		if (empty($snum)) {
			$errmsg[] = 'Please provide a child\'s student number.';
		} else if (!preg_match($snum_pattern, $snum)) {
			$errmsg[] = 'Invalid student number.';
		}
		# Check name fields
		$fname = trim($_POST['p_first']);
		$lname = trim($_POST['p_last']);
		$name_pattern = "/([A-Za-z]+['A-Za-z]?)+/";
		if (empty($fname) or empty($lname)) {
			$errmsg[] = 'Please enter a first and last name.';
		} else if (!preg_match($name_pattern, $fname) 
					or !preg_match($name_pattern, $lname)) {
			$errmsg[] = 'First or last name contain invalid characters.'; 
		}
	}
	# Input verified, create account
	if (isset($errmsg) and empty($errmsg)) {
		$ver = verify_student($snum);
		if ($ver == 1) {
			$success = create_account($email, $pass1, $snum, $fname, $lname);
			if (!$success) {
				$errmsg[] = 'An error occurred during account creation.<br /> '
						. 'Please contact the site administrator.';
			} else {
				$_SESSION['err'] = 'Registration complete! You may now login.';
				header('Location: index.php');
			}
		} else {
			$errmsg[] = 'The student number you entered cannot be found.<br /> '
					. 'A valid student number must be provided to register.';
		}
	}
?>

<!-- registration.php allows parents of students at NTCI to register
  for a new account with the online booking system. --> <!DOCTYPE html>
<html>
	<head>
		<title>PT Interview Registration</title>
		<link href="style.css" type="text/css" rel="stylesheet" />
	</head>
	<body>
	<div id="container">
		<h1>PT Interview Registration</h1>
		<h3>Please register by providing us with your details below:</h3>

<?php	
		# Display error messages if present
		if (isset($errmsg) and !empty($errmsg)) {
			echo '<ul>';
			foreach ($errmsg as $msg) {
				echo "<li>{$msg}</li>";
			}
			echo '</ul>';
		}
?>

		<form action="registration.php" method="post">
			<div class="reg_fields">
			<div>E-mail:<input type="email" name="reg_email" class="input"/></div>
			<div>Password:<input type="password" name="reg_passwd1"  class="input"/></div>
			<div>Re-type Password:<input type="password" name="reg_passwd2"  class="input"/></div>
			<div>Student Number:<input type="text" name="stud_num" class="input"/></div>
			<div>First name:<input type="text" name="p_first" class="input"/></div>
			<div>Last name:<input type="text" name="p_last" class="input"/></div>
			</div>
			<div><input class="lbutton" type="submit" value="Submit" name="Submit" class="input"/></div>
		</form>
	</div>
	</body>
</html>
