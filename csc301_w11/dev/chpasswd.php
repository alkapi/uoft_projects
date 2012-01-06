<?php
	include_once('model.php');
	session_start();
	if (isset($_POST['submit'])) {
		$oldpass = trim($_POST['oldpass']);
		$newpass1 = trim($_POST['newpass1']);
		$newpass2 = trim($_POST['newpass2']);
		$usertype = trim($_SESSION['type']);
		if ($usertype == 'parent') {
			$id = trim($_SESSION['userinfo']['accountId']);
		} else {
			$id = trim($_SESSION['userinfo']['teacherId']);
		}
		if ($oldpass != trim($_SESSION['userinfo']['passwd'])) {
			$_SESSION['err'] = 'Your current password was incorrect!';
			header('Location: index.php');	
		} else if (strlen($newpass1) < 6 || strlen($newpass2) < 6) {
			$_SESSION['err'] = 'Your new password must be at least 6 characters!';
			header('Location: index.php');
		} else if ($newpass1 != $newpass2) {
			$_SESSION['err'] = 'Your new passwords do not match!';
			header('Location: index.php');	
		} else {
			$success = update_passwd($id, $newpass1, $usertype);
			if ($success) {
				$_SESSION['err'] = 'Your password has been updated! <br /> '
					. 'Please log in with your new password.';	
				unset($_SESSION['userinfo']); # force fresh login
				header('Location: index.php');
			} else {
				$_SESSION['err'] = 'An unexpected error occured while updating '
					. 'your password. <br /> Please contact the site '
					. 'administrator if you wish to change you password.';	
				header('Location: index.php');
			}
		}
	} else {
		header('Location: index.php');
	}
?>
