<?php

	session_start();
include_once('model.php');

$j=0;
for ($i=0; $i<10000000; $i++){
	$j +=1;	
}
if ($_POST["action"]==1){
	block_time($_SESSION["teacherId"], $_POST["time"]);
echo "success";
} else if ($_POST["action"]==3){
	unblock_time($_SESSION["teacherId"], $_POST["time"]);
echo "success";
} else if ($_POST["action"]==4){
	cancel_block($_SESSION["teacherId"], $_POST["time"]);
echo "success";
} else {
	$parname = find_booked($_SESSION["teacherId"], $_POST["time"]);
echo $parname["fname"]. " " .$parname["lname"];
} 
?>
