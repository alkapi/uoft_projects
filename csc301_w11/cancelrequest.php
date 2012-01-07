<?php
	session_start();
	include_once('model.php');
	$result = cancel_time($_SESSION['userinfo']["accountId"], $_POST["time"]); 
    echo $result;
?>
