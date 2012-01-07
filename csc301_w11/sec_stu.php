<?php
	session_start();
	include_once('model.php');
	//if (isset($_POST['submit_stu'])) { 
		$chk_stunum = trim($_POST['sec_stu']);	
		$email = trim($_SESSION['userinfo']['email']);
		$prim_stunum = trim($_SESSION['userinfo']['stuNum']);
		if ($prim_stuNum == $chk_stunum) {
			echo 'fail';
		} else {
			$verify = verify_student($chk_stunum);
			if ($verify) {
				$ret = add_student_secondary($email, $chk_stunum);
				echo 'success';
			} else {
				// $_SESSION['err'] = 'The student number does not exist!';
				// header('Location: add_sec_student.php');	
				echo 'fail';
			}
		}
/*
	} else {
		header('Location: index.php');	
	}
*/
?>
