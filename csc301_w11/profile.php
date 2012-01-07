<?php
	session_start();
	/* profile.php is a dynamically generated page that outputs the 
     * profile contents of either the parent or the teacher depending
     * on which user logged in. If the user is not valid then an error
	 * displayed */
	include_once('model.php');
	if (!isset($_SESSION['userinfo'])) {
		if (isset($_POST['email']) && isset($_POST['passwd'])){
			$email = trim($_POST['email']);
			$passwd = trim($_POST['passwd']);
			$result = verify_account($email, $passwd);
			if ($result) {
				$_SESSION['userinfo'] = $result;
				$_SESSION['user_fname'] = $result['fname'];
				$_SESSION['user_lname'] = $result['lname'];
				$_SESSION['type'] = $result['type'];
				if ($_SESSION['type'] == 'teacher') {
					$_SESSION['teacherId'] = $result['teacherId'];
				}
			} else {
				$_SESSION['err'] = "Incorrect username or password.";
				header('Location: index.php');
			}
		} else { 
			$_SESSION['err'] = "Please log in.";
			header('Location: index.php');
		}
	}
	if ($_SESSION['type'] == 'parent') {
		include_once('./parent_profile.php');

	} else if ($_SESSION['type'] == 'teacher') {
		include_once('./teacher_profile.php');

	} else {
		# Catch errors
		echo '<p>Unknown user type.</p>';
	}
?>
