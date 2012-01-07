<?php
	session_start();
	include_once('model.php');
	$result = book_time($_SESSION['userinfo']["accountId"], $_POST["time"], $_POST["stuNum"],$_POST["courseCode"]); 
?>
